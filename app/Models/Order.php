<?php
// app/Models/Order.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';
    
  protected $fillable = [
    'order_number',
    'subtotal',
    'tax',
    'discount',
    'total',
    'status',
    'payment_status',
    'payment_method',
    'order_type',   // أضف هذا
    'notes',
    'user_id'
];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'tax' => 'decimal:2',
        'discount' => 'decimal:2',
        'total' => 'decimal:2'
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}   