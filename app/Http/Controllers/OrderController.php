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
    // Display Orders Table
    public function index()
    {
        $orders = DB::table('orders')
            ->select(
                'id',
                'order_number',
                'total',
                'status',
                'payment_status',
                'created_at',
                DB::raw('(SELECT COUNT(*) FROM order_items WHERE order_id = orders.id) as items_count'),
                DB::raw('(CASE 
                    WHEN status="completed" THEN "bg-green-100 text-green-700"
                    WHEN status="pending" THEN "bg-yellow-100 text-yellow-700"
                    ELSE "bg-gray-100 text-gray-700"
                END) as status_class')
            )
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        $summary = DB::table('orders')
            ->select(
                DB::raw('COUNT(*) as total_orders'),
                DB::raw('(SELECT IFNULL(SUM(quantity),0) FROM order_items) as total_items_sold'),
                DB::raw('IFNULL(SUM(total),0) as total_money')
            )
            ->first();

        return view('orders.index', compact('orders', 'summary'));
    }

    // Display Create Order Page
    public function create()
    {
        $products = MenuItem::where(function($query) {
                $query->where('track_inventory', false)
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
            // Validate the request
            $data = $request->validate([
                'items' => 'required|array|min:1',
                'items.*.product_id' => 'required|exists:menu_items,id',
                'items.*.quantity' => 'required|integer|min:1',
                'customer_name' => 'nullable|string|max:255',
                'table_number' => 'nullable|numeric',
                'tax' => 'nullable|numeric|min:0|max:100',
                'discount' => 'nullable|numeric|min:0',
                'payment_method' => 'required|in:cash,card,wallet',
                'notes' => 'nullable|string|max:500',
            ]);

            DB::beginTransaction();

            // Calculate totals
            $subtotal = 0;
            $orderItems = [];

            foreach ($data['items'] as $item) {
                $product = MenuItem::lockForUpdate()->find($item['product_id']);
                
                if (!$product) {
                    throw new \Exception('المنتج غير موجود');
                }

                // Check stock if inventory tracking is enabled
                if ($product->track_inventory && $product->quantity < $item['quantity']) {
                    throw new \Exception("الكمية غير متوفرة من: {$product->name}");
                }

                $itemTotal = $product->price * $item['quantity'];
                $subtotal += $itemTotal;

                $orderItems[] = [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'price' => $product->price,
                    'quantity' => $item['quantity'],
                    'total' => $itemTotal
                ];

                // Reduce inventory if tracking is enabled
                if ($product->track_inventory) {
                    $product->quantity -= $item['quantity'];
                    $product->save();
                }
            }

            // Calculate tax and discount
            $taxPercent = $data['tax'] ?? 0;
            $discount = $data['discount'] ?? 0;
            $taxAmount = $subtotal * ($taxPercent / 100);
            $total = $subtotal + $taxAmount - $discount;

            // Generate unique order number
            $orderNumber = $this->generateOrderNumber();

            // Create order
            $order = Order::create([
                'order_number' => $orderNumber,
                'user_id' => auth()->id(),
                'customer_name' => $data['customer_name'] ?? null,
                'table_number' => $data['table_number'] ?? null,
                'subtotal' => $subtotal,
                'tax' => $taxAmount,
                'discount' => $discount,
                'total' => $total,
                'payment_method' => $data['payment_method'],
                'payment_status' => 'paid',
                'status' => 'completed',
                'notes' => $data['notes'] ?? null,
            ]);

            // Create order items
            foreach ($orderItems as $item) {
                $item['order_id'] = $order->id;
                OrderItem::create($item);
            }

            DB::commit();

            // Generate receipt HTML
            $receiptHtml = $this->generateReceipt($order);

            return response()->json([
                'success' => true,
                'message' => 'تم حفظ الطلب بنجاح',
                'order' => $order->load('items'),
                'receipt' => $receiptHtml
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'خطأ في البيانات المدخلة',
                'errors' => $e->errors()
            ], 422);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Order creation failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ: ' . $e->getMessage()
            ], 500);
        }
    }

    private function generateOrderNumber()
    {
        $date = now()->format('Ymd');
        $lastOrder = Order::whereDate('created_at', today())->latest()->first();
        
        if ($lastOrder) {
            $lastNumber = intval(substr($lastOrder->order_number, -4));
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }
        
        return "ORD-{$date}-{$newNumber}";
    }

    private function generateReceipt($order)
    {
        $order->load('items', 'user');
        
        $receipt = [];
        $receipt[] = "=" . str_repeat("=", 32);
        $receipt[] = str_pad("مطعم الأصيل", 34, " ", STR_PAD_BOTH);
        $receipt[] = str_pad("AL ASEEL RESTAURANT", 34, " ", STR_PAD_BOTH);
        $receipt[] = "=" . str_repeat("=", 32);
        $receipt[] = "رقم الفاتورة: " . $order->order_number;
        $receipt[] = "التاريخ: " . $order->created_at->format('Y-m-d H:i');
        $receipt[] = "الكاشير: " . ($order->user->name ?? '------');
        
        if ($order->customer_name) {
            $receipt[] = "العميل: " . $order->customer_name;
        }
        
        if ($order->table_number) {
            $receipt[] = "رقم الطاولة: " . $order->table_number;
        }
        
        $receipt[] = "-" . str_repeat("-", 32);
        $receipt[] = str_pad("الصنف", 20) . " كم  السعر";
        
        foreach ($order->items as $item) {
            $name = mb_substr($item->product_name, 0, 15);
            $line = str_pad($name, 20);
            $line .= str_pad($item->quantity, 4, " ", STR_PAD_LEFT);
            $line .= str_pad(number_format($item->price, 2), 8, " ", STR_PAD_LEFT);
            $receipt[] = $line;
        }
        
        $receipt[] = "-" . str_repeat("-", 32);
        $receipt[] = str_pad("المجموع الفرعي: " . number_format($order->subtotal, 2), 32, " ", STR_PAD_LEFT);
        
        if ($order->tax > 0) {
            $receipt[] = str_pad("الضريبة: " . number_format($order->tax, 2), 32, " ", STR_PAD_LEFT);
        }
        
        if ($order->discount > 0) {
            $receipt[] = str_pad("الخصم: " . number_format($order->discount, 2), 32, " ", STR_PAD_LEFT);
        }
        
        $receipt[] = str_pad("الإجمالي: " . number_format($order->total, 2), 32, " ", STR_PAD_LEFT);
        $receipt[] = "=" . str_repeat("=", 32);
        $receipt[] = str_pad("شكراً لتسوقكم معنا", 34, " ", STR_PAD_BOTH);
        $receipt[] = str_pad("نتمنى لكم يوماً سعيداً", 34, " ", STR_PAD_BOTH);
        $receipt[] = "=" . str_repeat("=", 32);
        
        return implode("\n", $receipt);
    }

    public function filterByDate(Request $request)
    {
        try {
            $request->validate([
                'from' => 'required|date',
                'to' => 'required|date'
            ]);

            $from = $request->from . ' 00:00:00';
            $to = $request->to . ' 23:59:59';
            
            $orders = Order::withCount('items')
                ->whereBetween('created_at', [$from, $to])
                ->orderBy('created_at', 'desc')
                ->get();
            
            $summary = (object) [
                'total_orders' => $orders->count(),
                'total_items_sold' => $orders->sum('items_count'),
                'total_money' => $orders->sum('total')
            ];
            
            return response()->json([
                'success' => true,
                'orders' => $orders,
                'summary' => $summary
            ]);
            
        } catch (\Exception $e) {
            Log::error('Date filter failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ في تحميل البيانات'
            ], 500);
        }
    }
}