<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class AttributeValue extends Model
{
    use HasFactory;

    protected $fillable = [
        'attribute_id',
        'value',
        'slug',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array', // Automatically cast metadata to array/json
    ];

    /**
     * The "booting" method of the model.
     * Automatically generate slug.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($attributeValue) {
            if (empty($attributeValue->slug)) {
                $attributeValue->slug = Str::slug($attributeValue->value);
            }
        });

        static::updating(function ($attributeValue) {
            if (empty($attributeValue->slug) && $attributeValue->isDirty('value')) {
                $attributeValue->slug = Str::slug($attributeValue->value);
            }
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * Get the attribute that this value belongs to.
     */
    public function attribute()
    {
        return $this->belongsTo(Attribute::class);
    }

    /**
     * Get the products that have this attribute value as a specification.
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_attribute_value');
    }

    /**
     * Get the product variants that use this attribute value.
     */
    public function productVariants()
    {
        return $this->belongsToMany(ProductVariant::class, 'product_variant_attribute_value');
    }
}