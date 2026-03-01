<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    protected $fillable = [
        'name',
        'code',
        'contact_person',
        'phone',
        'email',
        'address',
        'lat',
        'long',
        'city_id',
        'is_active'
    ];

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function deliveryPartners(): HasMany
    {
        return $this->hasMany(DeliveryPartner::class);
    }
}
