<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Enums\ProductType;
use App\Enums\VolumeUnit;
use App\Enums\WeightUnit;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'brand_id',
        'name',
        'slug',
        'sku',
        'short_description',
        'long_description',
        'thumbnail_image_path',
        'type',
        'regular_price',
        'sale_price',
        'retail_price',
        'distributor_price',
        'purchase_price',
        'weight',
        'weight_unit',
        'volume',
        'volume_unit',
        'is_active',
        'is_featured',
        'is_new',
        'is_manage_stock',
        'quantity',
        'min_order_quantity',
        'max_order_quantity',
        'meta_title',
        'meta_description',
        'og_title',
        'og_description',
        'og_image_path',
        // New Marketing Fields
        'stock_alert_count',
        'sale_ends_at',
    ];

    // Added marketing appends for the landing page
    protected $appends = [
        'thumbnail_url',
        'current_stock',
        'effective_price',
        'discount_percentage',
        'savings_amount'
    ];

    protected $casts = [
        'regular_price'     => 'decimal:2',
        'sale_price'        => 'decimal:2',
        'retail_price'      => 'decimal:2',
        'distributor_price' => 'decimal:2',
        'purchase_price'    => 'decimal:2',
        'weight'            => 'decimal:2',
        'weight_unit'       => WeightUnit::class,
        'volume'            => 'decimal:2',
        'volume_unit'       => VolumeUnit::class,
        'is_active'         => 'boolean',
        'is_featured'       => 'boolean',
        'is_new'            => 'boolean',
        'is_manage_stock'   => 'boolean',
        'quantity'           => 'integer',
        'min_order_quantity' => 'integer',
        'max_order_quantity' => 'integer',
        'type'               => ProductType::class,
        'sale_ends_at'       => 'datetime', // Cast for countdown timer
    ];

    protected $attributes = [
        'type'      => ProductType::Normal,
        'is_active' => true,
        'is_new'    => true,
        'quantity'  => 0,
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(fn($product) => $product->slug = $product->slug ?? Str::slug($product->name));
        static::updating(fn($product) => $product->isDirty('name') && empty($product->slug) ? $product->slug = Str::slug($product->name) : null);
    }

    /* --- Landing Page Orchestration (Sorting Logic) --- */

    /**
     * This is the "Another Model" used for Livewire Drag-and-Drop.
     * It pulls all sections (Title, Media, Benefits, etc.) in correct order.
     */
    public function pageSections()
    {
        return $this->hasMany(ProductPageSection::class)->orderBy('sort_order');
    }

    /* --- Relationships --- */

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_product');
    }
    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }
    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
    public function specifications()
    {
        return $this->hasMany(ProductSpecification::class);
    }
    public function deals()
    {
        return $this->belongsToMany(Deal::class);
    }
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'product_tag');
    }

    /* --- Accessors & Landing Page Logic --- */

    /**
     * Calculate Discount Percentage for the "Sale!" badge
     * e.g., 1690 regular vs 1090 sale = 35% off
     */
    public function getDiscountPercentageAttribute(): int
    {
        if ($this->regular_price > 0 && $this->sale_price > 0) {
            $discount = (($this->regular_price - $this->sale_price) / $this->regular_price) * 100;
            return (int) round($discount);
        }
        return 0;
    }

    /**
     * Calculate Total Savings
     * e.g., ৳ ৬০০/- টাকা ছাড়
     */
    public function getSavingsAmountAttribute(): float
    {
        if ($this->sale_price > 0) {
            return (float) ($this->regular_price - $this->sale_price);
        }
        return 0;
    }

    public function getThumbnailUrlAttribute()
    {
        return $this->thumbnail_image_path ? asset('storage/' . $this->thumbnail_image_path) : asset('images/placeholder.png');
    }

    public function getCurrentStockAttribute(): int
    {
        return $this->isVariable() ? (int) $this->variants()->sum('quantity') : (int) $this->quantity;
    }

    public function getEffectivePriceAttribute()
    {
        $basePrice = $this->sale_price ?? $this->regular_price;
        $activeDeal = $this->deals()->where('is_active', true)->first();

        if ($activeDeal) {
            if ($activeDeal->type === 'percentage') {
                $basePrice = $basePrice * (1 - ($activeDeal->value / 100));
            } elseif ($activeDeal->type === 'fixed') {
                $basePrice = max(0, $basePrice - $activeDeal->value);
            }
        }
        return $basePrice;
    }

    public function isNormal(): bool
    {
        return $this->type === ProductType::Normal;
    }
    public function isVariable(): bool
    {
        return $this->type === ProductType::Variable;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
