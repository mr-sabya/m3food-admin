<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'image',
        'type',
        'instructions',
        'is_default',
        'status',
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'status' => 'boolean',
    ];

    /**
     * Scope to only include active payment methods.
     */
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    public function shippingMethods()
    {
        return $this->belongsToMany(ShippingMethod::class, 'payment_method_shipping_method');
    }
}
