<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeliveryPartner extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'name',
        'contact_person',
        'phone',
        'address',
        'warehouse_id',
        'fee_inside',
        'fee_outside',
        'fee_suburb',
        'express_fee_inside',
        'express_fee_outside',
        'express_fee_suburb',
        'courier_delivery_cost',
        'courier_return_cost',
        'cod_charge_percent',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'fee_inside' => 'decimal:2',
        'fee_outside' => 'decimal:2',
        'fee_suburb' => 'decimal:2',
        'cod_charge_percent' => 'decimal:2',
    ];

    /**
     * Get the warehouse associated with the delivery partner.
     */
    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(ShopWarehouse::class);
    }
}
