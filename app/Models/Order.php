<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Enums\PaymentStatus;
use App\Enums\OrderStatus;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'vendor_id',
        'order_number',

        // Billing
        'billing_first_name',
        'billing_last_name',
        'billing_email',
        'billing_phone',
        'billing_address_line_1',
        'billing_address_line_2',
        'billing_country_id',
        'billing_state_id',
        'billing_city_id',
        'billing_zip_code',

        // Shipping
        'shipping_first_name',
        'shipping_last_name',
        'shipping_email',
        'shipping_phone',
        'shipping_address_line_1',
        'shipping_address_line_2',
        'shipping_country_id',
        'shipping_state_id',
        'shipping_city_id',
        'shipping_zip_code',

        // Money
        'subtotal',
        'discount_amount',
        'shipping_cost',
        'tax_amount',
        'total_amount',
        'currency',

        // Methods (The New Structure)
        'payment_method_id',
        'payment_method_name',
        'transaction_id',
        'payment_phone_number', // Add this line
        'payment_status',

        'shipping_method_id',
        'shipping_method_name',
        'order_status',

        'tracking_number',
        'notes',
        'placed_at',
        'shipped_at',
        'delivered_at',
        'cancelled_at',
        'cancel_reason',
        'cancelled_by',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'payment_status' => PaymentStatus::class,
        'order_status' => OrderStatus::class,
        'placed_at' => 'datetime',
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($order) {
            if (empty($order->order_number)) {
                $order->order_number = 'ORD-' . date('Ymd') . '-' . Str::upper(Str::random(6));
            }
        });
    }

    /* 
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the vendor assigned to this order.
     */
    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Method Relationships
    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }


    public function shippingMethod()
    {
        return $this->belongsTo(ShippingMethod::class);
    }

    // Address Relationships
    public function shippingCountry()
    {
        return $this->belongsTo(Country::class, 'shipping_country_id');
    }
    public function shippingState()
    {
        return $this->belongsTo(State::class, 'shipping_state_id');
    }
    public function shippingCity()
    {
        return $this->belongsTo(City::class, 'shipping_city_id');
    }

    /**
     * Check if the order was placed by a guest.
     */
    public function isGuest()
    {
        return $this->user_id === null;
    }

    // Add this relationship
    public function cancelledBy()
    {
        return $this->belongsTo(User::class, 'cancelled_by');
    }

    /**
     * Helper to check if order can be cancelled.
     * Usually allowed only if status is Pending.
     */
    public function canBeCancelled(): bool
    {
        // Check against your OrderStatus Enum
        return $this->order_status === OrderStatus::Pending;
    }

    /* 
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    public function getFullShippingAddressAttribute(): string
    {
        $address = "{$this->shipping_address_line_1}";
        if ($this->shipping_address_line_2) $address .= ", {$this->shipping_address_line_2}";

        // Fetch names safely even if relations are null
        $city = $this->shippingCity?->name ?? 'Unknown City';
        $state = $this->shippingState?->name ?? 'Unknown State';
        $country = $this->shippingCountry?->name ?? 'Unknown Country';

        return "{$address}, {$city}, {$state} {$this->shipping_zip_code}, {$country}";
    }


    /**
     * Get the customer name (User name or Billing name if guest).
     */
    public function getCustomerNameAttribute(): string
    {
        if ($this->user) {
            return $this->user->name;
        }
        return $this->billing_first_name . ' ' . $this->billing_last_name . ' (Guest)';
    }
}
