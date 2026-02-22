<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingMethod extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'is_default',
        'status'
    ];

    public function rules()
    {
        return $this->hasMany(ShippingRule::class);
    }

    public function paymentMethods()
    {
        return $this->belongsToMany(PaymentMethod::class, 'payment_method_shipping_method');
    }
}
