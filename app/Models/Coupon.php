<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\CouponType; // We'll create this enum

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'description',
        'type',
        'value',
        'min_spend',
        'max_discount_amount',
        'usage_limit_per_coupon',
        'usage_count',
        'usage_limit_per_user',
        'valid_from',
        'valid_until',
        'is_active',
    ];

    protected $casts = [
        'type' => CouponType::class, // Cast to Enum
        'value' => 'decimal:2',
        'min_spend' => 'decimal:2',
        'max_discount_amount' => 'decimal:2',
        'usage_limit_per_coupon' => 'integer',
        'usage_count' => 'integer',
        'usage_limit_per_user' => 'integer',
        'valid_from' => 'datetime',
        'valid_until' => 'datetime',
        'is_active' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * Get the orders that have used this coupon.
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Get the users who are specifically allowed to use this coupon (if applicable).
     * (Requires pivot table: coupon_user)
     */
    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    /**
     * Get the products that this coupon applies to (if applicable).
     * (Requires pivot table: coupon_product)
     */
    public function products()
    {
        return $this->belongsToMany(Product::class);
    }

    /**
     * Get the categories that this coupon applies to (if applicable).
     * (Requires pivot table: coupon_category)
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }


    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where('valid_from', '<=', now())
            ->where(function ($q) {
                $q->whereNull('valid_until')
                    ->orWhere('valid_until', '>=', now());
            })
            ->where(function ($q) {
                $q->whereNull('usage_limit_per_coupon')
                    ->orWhereColumn('usage_count', '<', 'usage_limit_per_coupon');
            });
    }

    public function scopeByCode($query, string $code)
    {
        return $query->where('code', $code);
    }

    /*
    |--------------------------------------------------------------------------
    | Helper Methods
    |--------------------------------------------------------------------------
    */

    /**
     * Check if the coupon is valid at the current time.
     */
    public function isValid(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        if ($this->valid_from && $this->valid_from->isFuture()) {
            return false;
        }

        if ($this->valid_until && $this->valid_until->isPast()) {
            return false;
        }

        if ($this->usage_limit_per_coupon && $this->usage_count >= $this->usage_limit_per_coupon) {
            return false;
        }

        return true;
    }

    /**
     * Apply the discount to a given amount.
     * This is a basic calculation, more complex logic (like max_discount) would be in a service.
     */
    public function applyToAmount(float $amount): float
    {
        if (!$this->isValid() || $amount < ($this->min_spend ?? 0)) {
            return 0.00; // No discount if not valid or min_spend not met
        }

        switch ($this->type) {
            case CouponType::Percentage:
                $discount = ($amount * $this->value) / 100;
                if ($this->max_discount_amount && $discount > $this->max_discount_amount) {
                    $discount = $this->max_discount_amount;
                }
                return round($discount, 2);
            case CouponType::FixedAmount:
                return round(min($this->value, $amount), 2); // Discount cannot exceed item/order value
            case CouponType::FreeShipping:
                return 0.00; // Free shipping is handled separately by setting shipping_cost to 0
            default:
                return 0.00;
        }
    }
}
