<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'product_variant_id',
        'vendor_id',
        'item_name',
        'item_sku',
        'item_attributes',
        'quantity',
        'unit_price',
        'item_discount_amount',
        'item_tax_amount',
        'subtotal',
        'commission_rate',
        'commission_amount',
    ];

    protected $casts = [
        'item_attributes' => 'array',
        'quantity' => 'integer',
        'unit_price' => 'decimal:2',
        'item_discount_amount' => 'decimal:2',
        'item_tax_amount' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'commission_rate' => 'decimal:2',
        'commission_amount' => 'decimal:2',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * Get the order that the item belongs to.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the actual product associated with the order item.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the actual product variant associated with the order item.
     */
    public function productVariant()
    {
        return $this->belongsTo(ProductVariant::class);
    }

    /**
     * Get the vendor for this specific order item.
     */
    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }

    /**
     * Get the downloads for this order item (if it's a digital product).
     */
    public function downloads()
    {
        return $this->hasMany(Download::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    /**
     * Get the calculated total price for this line item including tax and after discount.
     */
    public function getTotalPriceAttribute(): float
    {
        return $this->subtotal; // The subtotal already includes all calculations for this item
    }

    /**
     * Get a formatted display of item attributes.
     */
    public function getFormattedAttributesAttribute(): string
    {
        if (empty($this->item_attributes)) {
            return '';
        }
        $formatted = [];
        foreach ($this->item_attributes as $key => $value) {
            $formatted[] = "{$key}: {$value}";
        }
        return implode(', ', $formatted);
    }
}
