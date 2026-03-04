{{-- resources/views/orders/receipt.blade.php --}}
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: 'Courier New', monospace;
            margin: 0;
            padding: 10px;
            width: 80mm;
            max-width: 80mm;
            background: white;
            font-size: 12px;
            line-height: 1.4;
        }
        .receipt {
            width: 100%;
        }
        .header {
            text-align: center;
            margin-bottom: 10px;
        }
        .header h2 {
            margin: 0;
            font-size: 18px;
            font-weight: bold;
        }
        .header p {
            margin: 5px 0;
            font-size: 12px;
        }
        .divider {
            border-top: 1px dashed #000;
            margin: 10px 0;
        }
        .items {
            margin: 10px 0;
        }
        .item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }
        .item-name {
            width: 50%;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .item-qty {
            width: 20%;
            text-align: center;
        }
        .item-total {
            width: 30%;
            text-align: left;
        }
        .totals {
            margin-top: 10px;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 3px;
        }
        .grand-total {
            font-weight: bold;
            font-size: 14px;
            margin-top: 5px;
            border-top: 1px solid #000;
            padding-top: 5px;
        }
        .footer {
            text-align: center;
            margin-top: 15px;
            font-size: 12px;
        }
        .thank-you {
            font-weight: bold;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="receipt">
        <!-- Header -->
        <div class="header">
            <h2>مطعم الأصيل</h2>
            <p>AL ASEEL RESTAURANT</p>
            <p class="divider"></p>
            <p>رقم الفاتورة: {{ $order->order_number }}</p>
            <p>التاريخ: {{ $order->created_at->format('Y-m-d H:i') }}</p>
            <p>الكاشير: {{ $order->user->name ?? '------' }}</p>
        </div>

        <div class="divider"></div>

        <!-- Items -->
        <div class="items">
            <div class="item" style="font-weight: bold; margin-bottom: 8px;">
                <span class="item-name">الصنف</span>
                <span class="item-qty">الكمية</span>
                <span class="item-total">الإجمالي</span>
            </div>
            
            @foreach($order->items as $item)
            <div class="item">
                <span class="item-name">{{ $item->product_name }}</span>
                <span class="item-qty">{{ $item->quantity }}</span>
                <span class="item-total">{{ number_format($item->total, 2) }}</span>
            </div>
            @endforeach
        </div>

        <div class="divider"></div>

        <!-- Totals -->
        <div class="totals">
            <div class="total-row">
                <span>المجموع الفرعي:</span>
                <span>{{ number_format($order->subtotal, 2) }} ر.س</span>
            </div>
            
            @if($order->tax > 0)
            <div class="total-row">
                <span>الضريبة:</span>
                <span>{{ number_format($order->tax, 2) }} ر.س</span>
            </div>
            @endif
            
            @if($order->discount > 0)
            <div class="total-row">
                <span>الخصم:</span>
                <span>{{ number_format($order->discount, 2) }} ر.س</span>
            </div>
            @endif
            
            <div class="total-row grand-total">
                <span>الإجمالي النهائي:</span>
                <span>{{ number_format($order->total, 2) }} ر.س</span>
            </div>
        </div>

        <div class="divider"></div>

        <!-- Footer -->
        <div class="footer">
            <div class="thank-you">شكراً لتسوقكم معنا</div>
            <p>نتمنى لكم يوماً سعيداً</p>
            <p>📞 920000000</p>
            <p style="font-size: 10px; margin-top: 10px;">{{ now()->format('Y-m-d H:i:s') }}</p>
        </div>
    </div>
</body>
</html>