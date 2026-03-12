<!DOCTYPE html>
<html dir="rtl">
<head>
<meta charset="UTF-8">
<title>فاتورة #{{ $order->id }}</title>
<style>
@page {
    size: 80mm auto;
    margin: 0;
}

body {
    font-family: 'Courier New', monospace;
    width: 76mm;
    margin: auto;
    font-size: 14px;
    font-weight: bold;
    color: #000;
    text-align: center;
}

.center {
    text-align: center;
}

.line {
    border-top: 2px dashed #000;
    margin: 5px 0;
}

table {
    width: 100%;
    border-collapse: collapse;
    table-layout: fixed; /* مهم لمنع قص النصوص */
}

th, td {
    padding: 3px 0;
    word-wrap: break-word; /* لتجنب قص النصوص */
}

th {
    border-bottom: 2px dashed #000;
}

td {
    border-bottom: 1px dashed #999;
}

.name-qty {
    text-align: right;
    width: 60%;
    white-space: normal; /* السماح بالانكسار للأسماء الطويلة */
}

.qty {
    display: inline-block;
    margin-left: 5px;
}

.price {
    width: 40%;
    text-align: center; /* السعر في الوسط */
    white-space: nowrap; /* منع قص السعر */
}

.total-section {
    margin-top: 5px;
    text-align: center;
}

.total-row {
    display: flex;
    justify-content: space-between;
    margin: 3px 0;
}

.grand {
    font-size: 16px;
    border-top: 2px dashed #000;
    padding-top: 5px;
}

.footer {
    text-align: center;
    margin-top: 10px;
    font-size: 13px;
}

</style>
</head>
<body>

<div class="center" style="font-size:18px">بيت شاروما</div>
<div class="center">نظام نقاط البيع</div>
<div class="line"></div>

<div style="text-align:right;">
رقم الطلب : {{ $order->id }}<br>
التاريخ : {{ $order->created_at->format('Y-m-d H:i') }}<br>
نوع الطلب :
@switch($order->order_type ?? 'dine_in')
@case('dine_in') داخل المطعم @break
@case('takeaway') سفري @break
@case('delivery') توصيل @break
@default داخل المطعم
@endswitch
</div>

<div class="line"></div>

<table>
<thead>
<tr>
<th class="name-qty">المنتج × الكمية</th>
<th class="price">السعر</th>
</tr>
</thead>
<tbody>
@foreach($order->items as $item)
<tr>
<td class="name-qty">
    {{ $item->product->name }} <span class="qty">×{{ $item->quantity }}</span>
</td>
<td class="price">{{ number_format($item->price, 2) }}</td>
</tr>
@endforeach
</tbody>
</table>

<div class="line"></div>

<div class="total-section">
<div class="total-row"><span>المجموع الفرعي</span><span>{{ number_format($order->subtotal, 2) }}</span></div>
@if($order->tax > 0)
<div class="total-row"><span>الضريبة</span><span>{{ number_format($order->tax, 2) }}</span></div>
@endif
@if($order->discount > 0)
<div class="total-row"><span>الخصم</span><span>{{ number_format($order->discount, 2) }}</span></div>
@endif
<div class="total-row grand"><span>الإجمالي</span><span>{{ number_format($order->total, 2) }}</span></div>
</div>

<div class="line"></div>

<div class="center">
طريقة الدفع :
@switch($order->payment_method)
@case('cash') نقدي @break
@case('card') بطاقة @break
@case('wallet') محفظة @break
@default نقدي
@endswitch
</div>

<div class="footer">
شكراً لتسوقكم معنا<br>
نتمنى لكم يوماً سعيداً
</div>

</body>
</html>