<?php
// app/Models/MenuItem.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MenuItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'category',
        'track_inventory',
        'quantity'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'track_inventory' => 'boolean',
        'quantity' => 'integer'
    ];

    protected $attributes = [
        'track_inventory' => true
    ];

    public function getStatusAttribute(): string
    {
        if (!$this->track_inventory) {
            return 'unlimited';
        }

        if ($this->quantity === 0) {
            return 'out_of_stock';
        }

        if ($this->quantity >= 1 && $this->quantity <= 4) {
            return 'low_stock';
        }

        if ($this->quantity >= 5) {
            return 'available';
        }

        return 'unknown';
    }

    public function getIsAvailableAttribute(): bool
    {
        return $this->status === 'available' || $this->status === 'unlimited';
    }
}