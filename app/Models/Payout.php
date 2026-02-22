<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payout extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_id',
        'amount',
        'status', // e.g., 'pending', 'processed', 'failed'
        'transaction_id', // Optional: for tracking payment gateway transactions
        'paid_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    /**
     * Get the vendor that owns the payout.
     */
    public function vendor()
    {
        return $this->belongsTo(VendorProfile::class);
    }
}
