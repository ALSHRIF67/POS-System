<?php
// app/Http/Controllers/OrderController.php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\MenuItem;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $products = MenuItem::where(function($query) {
            $query->where('track_inventory', false)
                  ->orWhere('quantity', '>', 0);
        })
        ->orderBy('category')
        ->orderBy('name')
        ->get();
        
        // Use 'orders.index' since your file is in resources/views/orders/index.blade.php
        return view('orders.index', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:menu_items,id',
            'items.*.quantity' => 'required|integer|min:1',
            'tax' => 'nullable|numeric|min:0|max:100',
            'discount' => 'nullable|numeric|min:0',
            'payment_method' => 'required|in:cash,card,wallet',
            'notes' => 'nullable|string|max:500'
        ]);

        try {
            DB::beginTransaction();

            // Calculate totals
            $subtotal = 0;
            $orderItems = [];

            foreach ($request->items as $item) {
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
            $taxPercent = $request->tax ?? 0;
            $discount = $request->discount ?? 0;
            $taxAmount = $subtotal * ($taxPercent / 100);
            $total = $subtotal + $taxAmount - $discount;

            // Generate unique order number
            $orderNumber = $this->generateOrderNumber();

            // Create order
            $order = Order::create([
                'order_number' => $orderNumber,
                'subtotal' => $subtotal,
                'tax' => $taxAmount,
                'discount' => $discount,
                'total' => $total,
                'status' => 'completed',
                'payment_status' => 'paid',
                'payment_method' => $request->payment_method,
                'notes' => $request->notes,
                'user_id' => Auth::id()
            ]);

            // Create order items
            foreach ($orderItems as $item) {
                $item['order_id'] = $order->id;
                OrderItem::create($item);
            }

            DB::commit();

            // FIXED: Use 'orders.receipt' instead of 'pos.receipt'
            $receiptHtml = view('orders.receipt', compact('order'))->render();

            return response()->json([
                'success' => true,
                'message' => 'تم حفظ الطلب بنجاح',
                'order' => $order->load('items'),
                'receipt' => $receiptHtml
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
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
}