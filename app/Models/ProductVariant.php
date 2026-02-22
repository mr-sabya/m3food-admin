<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductVariant extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'product_id',
        'sku',
        'price',
        'compare_at_price',
        'cost_price',
        'quantity',
        'weight',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'compare_at_price' => 'decimal:2',
        'cost_price' => 'decimal:2',
        'quantity' => 'integer',
        'weight' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * Get the parent product.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the attribute values that define this variant (e.g., "Red", "Large").
     */
    public function attributeValues()
    {
        return $this->belongsToMany(AttributeValue::class, 'product_variant_attribute_value');
    }

    /**
     * Get the images specifically for this variant.
     */
    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    /**
     * Get the order items associated with this specific variant.
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    /**
     * Get the main thumbnail image URL for the variant.
     */
    public function getThumbnailUrlAttribute(): ?string
    {
        $thumbnail = $this->images()->where('is_thumbnail', true)->first();
        return $thumbnail ? asset('storage/' . $thumbnail->image_path) : ($this->product->thumbnail_url ?? null);
    }

    /**
     * Get a descriptive name for the variant (e.g., "Red, Large").
     */
    public function getDisplayNameAttribute(): string
    {
        return $this->attributeValues->pluck('value')->implode(', ');
    }
}
