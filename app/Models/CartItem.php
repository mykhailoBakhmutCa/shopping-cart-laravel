<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $fillable = [
        'session_id',
        'product_id',
        'product_name',
        'price',
        'quantity',
    ];

    protected $casts = [
        'price'    => 'decimal:2',
        'quantity' => 'integer',
    ];

    public function getTotalPriceAttribute(): float
    {
        return round($this->price * $this->quantity, 2);
    }

    public function getTotalPriceFormattedAttribute(): float
    {
        return number_format($this->price * $this->quantity, 2, '.', '');
    }
}
