<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserDeliveryStat extends Model
{
    protected $fillable = [
        'user_id',
        'delivery_partner_id', // Changed from courier_name
        'total_count',
        'delivered_count',
        'undelivered_count',
        'success_rate'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function deliveryPartner(): BelongsTo
    {
        return $this->belongsTo(DeliveryPartner::class);
    }
}
