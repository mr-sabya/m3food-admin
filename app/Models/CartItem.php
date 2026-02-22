<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $fillable = [
        'user_id',
        'session_id',
        'product_id',
        'main_product_id', // Add this
        'quantity',
        'options',
        'price',           // Add this
        'is_combo'         // Add this
    ];

    protected $casts = [
        'options' => 'array',
        'is_combo' => 'boolean',
    ];

    // Relationship to the main product to access bundle rules
    public function mainProduct()
    {
        return $this->belongsTo(Product::class, 'main_product_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
