<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\MenuItem;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{

    public function index(Request $request)
    {
        $dateFrom = $request->input('dateFrom', date('Y-m-d'));
        $dateTo   = $request->input('dateTo', date('Y-m-d'));

        $from = $dateFrom . ' 00:00:00';
        $to   = $dateTo . ' 23:59:59';

        $orders = DB::table('orders')
            ->select(
                'id',
                'order_number',
                'total',
                'status',
                'payment_status',
                'created_at',
                DB::raw('(SELECT COUNT(*) FROM order_items WHERE order_id = orders.id) as items_count')
            )
            ->whereBetween('created_at', [$from, $to])
            ->orderByDesc('created_at')
            ->paginate(12);

        $totalItemsSold = DB::table('order_items')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->whereBetween('orders.created_at', [$from, $to])
            ->sum('order_items.quantity');

        $summaryData = DB::table('orders')
            ->whereBetween('created_at', [$from, $to])
            ->selectRaw('
                COUNT(*) as total_orders,
                IFNULL(SUM(total), 0) as total_money
            ')
            ->first();

        $summary = (object) [
            'total_orders'     => $summaryData->total_orders ?? 0,
            'total_items_sold' => $totalItemsSold,
            'total_money'      => $summaryData->total_money ?? 0,
        ];

        return view('orders.index', compact('orders', 'summary'))
            ->with([
                'dateFrom' => $dateFrom,
                'dateTo'   => $dateTo,
            ]);
    }


    public function create()
    {
        $products = MenuItem::where(function ($q) {
            $q->where('track_inventory', false)
                ->orWhere('quantity', '>', 0);
        })
            ->orderBy('category')
            ->orderBy('name')
            ->get();

        return view('orders.create', compact('products'));
    }


    public function store(Request $request)
    {
        try {

            $data = $request->validate([
                'items' => 'required|array|min:1',
                'items.*.product_id' => 'required|exists:menu_items,id',
                'items.*.quantity' => 'required|integer|min:1',

                'tax' => 'nullable|numeric|min:0|max:100',
                'discount' => 'nullable|numeric|min:0',

                'payment_method' => 'required|in:cash,card,wallet',
                'order_type' => 'nullable|in:local,takeaway',

                'notes' => 'nullable|string|max:500'
            ]);


            DB::beginTransaction();

            $subtotal = 0;
            $items = [];

            foreach ($data['items'] as $item) {

                $product = MenuItem::lockForUpdate()->find($item['product_id']);

                if (!$product) {
                    throw new \Exception("Product not found");
                }

                if ($product->track_inventory && $product->quantity < $item['quantity']) {
                    throw new \Exception("Insufficient stock for " . $product->name);
                }

                $itemTotal = $product->price * $item['quantity'];

                $subtotal += $itemTotal;

                $items[] = [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'price' => $product->price,
                    'quantity' => $item['quantity'],
                    'total' => $itemTotal
                ];

                if ($product->track_inventory) {
                    $product->decrement('quantity', $item['quantity']);
                }
            }

            $taxPercent = $data['tax'] ?? 0;
            $discount = $data['discount'] ?? 0;

            $taxAmount = $subtotal * ($taxPercent / 100);
            $total = $subtotal + $taxAmount - $discount;

            $orderNumber = $this->generateOrderNumber();

            $order = Order::create([
                'order_number' => $orderNumber,
                'subtotal' => $subtotal,
                'tax' => $taxAmount,
                'discount' => $discount,
                'total' => $total,
                'payment_method' => $data['payment_method'],
                'order_type' => $data['order_type'] ?? 'local',
                'notes' => $data['notes'] ?? null,
                'user_id' => Auth::id(),
                'status' => 'completed',
            ]);

            foreach ($items as $item) {
                $item['order_id'] = $order->id;
                OrderItem::create($item);
            }

            DB::commit();

            $receipt = view('orders.receipt', compact('order'))->render();

            return response()->json([
                'success' => true,
                'message' => 'Order created successfully',
                'order' => $order->load('items'),
                'receipt' => $receipt
            ]);
        } catch (\Exception $e) {

            DB::rollBack();

            Log::error('Order Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }


    /**
     * Display the specified order.
     */
    public function show($id)
    {
        $order = Order::with('items')->findOrFail($id);
        return view('orders.show', compact('order'));
    }


    /**
     * Show the form for editing the specified order.
     */
    public function edit($id)
    {
        $order = Order::with('items')->findOrFail($id);
        $products = MenuItem::orderBy('category')->orderBy('name')->get();
        return view('orders.edit', compact('order', 'products'));
    }


    /**
     * Update the specified order in storage.
     */
   public function update(Request $request, $id)
{
    try {
        $data = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:menu_items,id',
            'items.*.quantity' => 'required|integer|min:1',

            'tax' => 'nullable|numeric|min:0|max:100',
            'discount' => 'nullable|numeric|min:0',

            'payment_method' => 'required|in:cash,card,wallet',
            'order_type' => 'nullable|in:local,takeaway,delivery',

            'notes' => 'nullable|string|max:500',
            'status' => 'nullable|in:pending,completed,cancelled'
        ]);

        DB::beginTransaction();

        $order = Order::with('items')->findOrFail($id);

        // Return previous quantities to stock
        foreach ($order->items as $oldItem) {
            $product = MenuItem::find($oldItem->product_id);
            if ($product && $product->track_inventory) {
                $product->increment('quantity', $oldItem->quantity);
            }
        }

        // Delete old items
        OrderItem::where('order_id', $order->id)->delete();

        // Process new items
        $subtotal = 0;
        $newItems = [];

        foreach ($data['items'] as $itemData) {
            $product = MenuItem::lockForUpdate()->find($itemData['product_id']);

            if (!$product) {
                throw new \Exception("Product not found");
            }

            if ($product->track_inventory && $product->quantity < $itemData['quantity']) {
                throw new \Exception("Insufficient stock for " . $product->name);
            }

            $itemTotal = $product->price * $itemData['quantity'];
            $subtotal += $itemTotal;

            $newItems[] = [
                'order_id'     => $order->id,
                'product_id'   => $product->id,
                'product_name' => $product->name,
                'price'        => $product->price,
                'quantity'     => $itemData['quantity'],
                'total'        => $itemTotal,
                'created_at'   => now(),
                'updated_at'   => now(),
            ];

            if ($product->track_inventory) {
                $product->decrement('quantity', $itemData['quantity']);
            }
        }

        OrderItem::insert($newItems);

        $taxPercent = $data['tax'] ?? 0;
        $discount = $data['discount'] ?? 0;
        $taxAmount = $subtotal * ($taxPercent / 100);
        $total = $subtotal + $taxAmount - $discount;

        $order->update([
            'subtotal'       => $subtotal,
            'tax'            => $taxAmount,
            'discount'       => $discount,
            'total'          => $total,
            'payment_method' => $data['payment_method'],
            'order_type'     => $data['order_type'] ?? $order->order_type,
            'notes'          => $data['notes'] ?? $order->notes,
            'status'         => $data['status'] ?? $order->status,
        ]);

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث الطلب بنجاح',
            'order'   => $order->fresh('items')
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Order Update Error: ' . $e->getMessage());

        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
}

    /**
     * Remove the specified order from storage.
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $order = Order::findOrFail($id);

            // Return quantities to stock if tracking inventory
            foreach ($order->items as $item) {
                $product = MenuItem::find($item->product_id);
                if ($product && $product->track_inventory) {
                    $product->increment('quantity', $item->quantity);
                }
            }

            $order->delete();

            DB::commit();

            return redirect()->route('orders.index')
                ->with('success', 'تم حذف الطلب بنجاح');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Order Delete Error: ' . $e->getMessage());
            return back()->with('error', 'حدث خطأ أثناء حذف الطلب');
        }
    }


    public function filterByDate(Request $request)
    {
        $data = $request->validate([
            'from' => 'required|date',
            'to' => 'required|date'
        ]);

        $orders = Order::withCount('items')
            ->whereBetween('created_at', [
                $data['from'] . ' 00:00:00',
                $data['to'] . ' 23:59:59'
            ])
            ->orderByDesc('created_at')
            ->get();

        return response()->json([
            'success' => true,
            'orders' => $orders
        ]);
    }


    private function generateOrderNumber()
    {
        $date = now()->format('Ymd');

        $lastOrder = Order::whereDate('created_at', today())
            ->orderByDesc('id')
            ->first();

        $number = 1;

        if ($lastOrder) {
            $last = intval(substr($lastOrder->order_number, -4));
            $number = $last + 1;
        }

        return "ORD-" . $date . "-" . str_pad($number, 4, '0', STR_PAD_LEFT);
    }

      public function dailyReport(Request $request)
    {
        $date = $request->input('date', date('Y-m-d'));
        $from = $date . ' 00:00:00';
        $to   = $date . ' 23:59:59';

        // 1. Get orders for the selected day (with items count)
        $orders = DB::table('orders')
            ->select(
                'id',
                'order_number',
                'total',
                'status',
                'payment_method',
                'created_at',
                DB::raw('(SELECT COUNT(*) FROM order_items WHERE order_id = orders.id) as items_count')
            )
            ->whereBetween('created_at', [$from, $to])
            ->orderByDesc('created_at')
            ->get();

        // 2. Calculate summary data
        $summaryData = DB::table('orders')
            ->whereBetween('created_at', [$from, $to])
            ->selectRaw('
                COUNT(*) as total_orders,
                IFNULL(SUM(total), 0) as total_money,
                IFNULL(SUM(CASE WHEN payment_method = "cash" THEN total ELSE 0 END), 0) as cash_total,
                IFNULL(SUM(CASE WHEN payment_method = "card" THEN total ELSE 0 END), 0) as card_total,
                IFNULL(SUM(CASE WHEN payment_method = "wallet" THEN total ELSE 0 END), 0) as wallet_total
            ')
            ->first();

        // 3. Total items sold (sum of quantities)
        $totalItemsSold = DB::table('order_items')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->whereBetween('orders.created_at', [$from, $to])
            ->sum('order_items.quantity');

        // 4. Build summary object
        $summary = (object) [
            'total_orders'     => $summaryData->total_orders ?? 0,
            'total_items_sold' => $totalItemsSold,
            'total_money'      => $summaryData->total_money ?? 0,
            'cash_total'       => $summaryData->cash_total ?? 0,
            'card_total'       => $summaryData->card_total ?? 0,
            'wallet_total'     => $summaryData->wallet_total ?? 0,
        ];

        return view('orders.dailyreport', compact('orders', 'summary', 'date'));
    }
}