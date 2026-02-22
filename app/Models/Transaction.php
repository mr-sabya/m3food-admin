<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\TransactionStatus; // We'll create these enums
use App\Enums\TransactionType;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'payment_method',
        'transaction_id',
        'amount',
        'currency',
        'status',
        'type',
        'gateway_response',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'gateway_response' => 'array', // Cast JSON to array
        'status' => TransactionStatus::class, // Cast to Enum
        'type' => TransactionType::class,     // Cast to Enum
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * Get the order associated with the transaction.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeSuccessful($query)
    {
        return $query->where('status', TransactionStatus::Successful);
    }

    public function scopeFailed($query)
    {
        return $query->where('status', TransactionStatus::Failed);
    }

    public function scopePayments($query)
    {
        return $query->where('type', TransactionType::Payment);
    }

    public function scopeRefunds($query)
    {
        return $query->where('type', TransactionType::Refund);
    }
}
