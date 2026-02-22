<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str; // For slug generation
use App\Enums\ProductType; // We'll create this enum next
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_id',
        'brand_id',
        'name',
        'slug',
        'short_description',
        'long_description',
        'thumbnail_image_path',
        'type',
        'sku',
        'price',
        'compare_at_price',
        'cost_price',
        'quantity',
        'weight',
        'is_active',
        'is_featured',
        'is_new',
        'is_manage_stock',
        'min_order_quantity',
        'max_order_quantity',
        'seo_title',
        'seo_description',
        'affiliate_url',
        'digital_file',
        'download_limit',
        'download_expiry_days',
        'free_delivery_threshold',
        'free_delivery_starts_at',
        'free_delivery_ends_at',

    ];

    // Add this line
    protected $appends = ['thumbnail_url', 'current_stock', 'effective_price']; // Make sure all accessors you want included by default are here

    protected $casts = [
        'price' => 'decimal:2',
        'compare_at_price' => 'decimal:2',
        'cost_price' => 'decimal:2',
        'quantity' => 'integer',
        'weight' => 'decimal:2',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'is_new' => 'boolean',
        'is_manage_stock' => 'boolean',
        'min_order_quantity' => 'integer',
        'max_order_quantity' => 'integer',
        'download_limit' => 'integer',
        'download_expiry_days' => 'integer',
        'type' => ProductType::class, // Cast to ProductType Enum
        'free_delivery_threshold' => 'integer',
        'free_delivery_starts_at' => 'datetime',
        'free_delivery_ends_at' => 'datetime',
    ];


    // Inside Product.php class
    protected $attributes = [
        'type' => ProductType::Normal, // Assuming 'Normal' is a case in your Enum
        'is_active' => true,
        'quantity' => 0,
    ];

    /**
     * The "booting" method of the model.
     * Automatically generate slug.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
        });

        static::updating(function ($product) {
            if (empty($product->slug) && $product->isDirty('name')) {
                $product->slug = Str::slug($product->name);
            }
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * Get the vendor that owns the product.
     */
    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }

    /**
     * A product can belong to many categories.
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_product');
    }

    /**
     * Get the brand that the product belongs to.
     */
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    /**
     * Get the images for the product.
     */
    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    /**
     * Get the variants for the product (if it's a variable product).
     */
    public function variants()
    {
        return $this->hasMany(ProductVariant::class)->orderBy('id');
    }

    /**
     * Get the reviews for the product.
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Get the tags associated with the product.
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class); // Using default pivot table 'product_tag'
    }

    /**
     * Get the attribute values for non-variable products (e.g., specifications).
     */
    public function attributeValues()
    {
        return $this->belongsToMany(AttributeValue::class, 'product_attribute_value');
    }

    /**
     * Get the technical specifications for the product.
     */
    public function specifications()
    {
        return $this->hasMany(ProductSpecification::class);
    }



    /**
     * Get the order items associated with this product.
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Get the downloads for this digital product.
     */
    public function downloads()
    {
        return $this->hasMany(Download::class);
    }


    /**
     * The deals that this product is part of.
     */
    public function deals()
    {
        return $this->belongsToMany(Deal::class);
    }
    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeOfType($query, ProductType $type)
    {
        return $query->where('type', $type->value);
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */



    /**
     * Check if the product is a normal product.
     */
    public function isNormal(): bool
    {
        // Use the null-safe operator (?->) and null coalescing operator (??)
        return $this->type?->isNormal() ?? false;
    }

    /**
     * Check if the product is a variable product.
     */
    public function isVariable(): bool
    {
        return $this->type?->isVariable() ?? false;
    }

    /**
     * Check if the product is an affiliate product.
     */
    public function isAffiliate(): bool
    {
        return $this->type?->isAffiliate() ?? false;
    }

    /**
     * Check if the product is a digital product.
     */
    public function isDigital(): bool
    {
        return $this->type?->isDigital() ?? false;
    }

    /**
     * Get the current stock for the product (considers variants if applicable).
     */
    public function getCurrentStockAttribute(): int
    {
        if ($this->isVariable()) {
            // sum() returns numeric, but casting ensures it's strictly an integer
            return (int) $this->variants()->sum('quantity');
        }

        // Cast to int ensures 'null' becomes 0
        return (int) $this->quantity;
    }

    /**
     * Get the main thumbnail image URL for the product.
     */
    // Add this accessor for convenience
    public function getThumbnailUrlAttribute()
    {
        return $this->thumbnail_image_path
            ? url('storage/' . $this->thumbnail_image_path)
            : null;
    }


    /**
     * Get the current effective price of the product, considering active deals.
     * This is a more advanced accessor.
     */
    public function getEffectivePriceAttribute()
    {
        // Eager load active deals if not already loaded
        if (!$this->relationLoaded('deals')) {
            $this->load(['deals' => function ($query) {
                $query->active();
            }]);
        }

        // Find the most impactful active deal for this product
        $activeDeal = $this->deals->filter(fn($deal) => $deal->is_active)
            ->sortByDesc('value') // Prioritize higher discounts
            ->first();

        if ($activeDeal) {
            $originalPrice = $this->price;
            if ($activeDeal->type === 'percentage') {
                return $originalPrice * (1 - ($activeDeal->value / 100));
            } elseif ($activeDeal->type === 'fixed') {
                return max(0, $originalPrice - $activeDeal->value); // Price shouldn't go below 0
            }
        }

        return $this->price; // No active deal, return original price
    }


    /**
     * Helper to get grouped specifications for the frontend table.
     * Returns: ['Dimensions' => [...], 'Technical' => [...]]
     */
    public function getGroupedSpecificationsAttribute()
    {
        return $this->specifications()
            ->with('key')
            ->get()
            ->groupBy(function ($spec) {
                return $spec->key->group ?? 'General';
            });
    }

    /**
     * Find the specific variant ID based on selected options (e.g., Size, Color)
     */
    public function findVariantByOptions(array $options)
    {
        if (!$this->isVariable() || empty($options)) {
            return null;
        }

        // We search through the variants of this product
        return $this->variants()->with('attributeValues.attribute')->get()->filter(function ($variant) use ($options) {

            // Convert the variant's database relationships into an array like: ['Color' => 'Black', 'Size' => 'XL']
            $variantAttributes = $variant->attributeValues->mapWithKeys(function ($attrValue) {
                // attrValue->attribute->name is "Color", attrValue->value is "Black"
                return [$attrValue->attribute->name => $attrValue->value];
            })->toArray();

            // Sort both arrays by key so the comparison is order-independent
            ksort($variantAttributes);
            ksort($options);

            // Check if the combination matches exactly
            return $variantAttributes === $options;
        })->first();
    }


    /**
     * Get products bundled with this product (Combos).
     */
    public function bundleProducts()
    {
        return $this->belongsToMany(Product::class, 'product_bundles', 'main_product_id', 'bundled_product_id')
            ->withPivot('special_price')
            ->withTimestamps();
    }

    /*
    |--------------------------------------------------------------------------
    | Business Logic / Accessors
    |--------------------------------------------------------------------------
    */

    /**
     * Check if the product currently qualifies for free delivery based on time.
     * (Rule 3)
     */
    public function getIsFreeDeliveryActiveAttribute(): bool
    {
        if (!$this->free_delivery_starts_at || !$this->free_delivery_ends_at) {
            return false;
        }

        return now()->between($this->free_delivery_starts_at, $this->free_delivery_ends_at);
    }

    /**
     * Check if a specific quantity qualifies for free delivery.
     * (Rule 1)
     */
    public function qualifiesForFreeDelivery(int $quantity): bool
    {
        // Check time-based rule first
        if ($this->is_free_delivery_active) {
            return true;
        }

        // Check quantity-based rule
        if ($this->free_delivery_threshold && $quantity >= $this->free_delivery_threshold) {
            return true;
        }

        return false;
    }

    /**
     * Calculate combo price
     * (Rule 2)
     */
    public function getComboDiscount(int $bundledProductId)
    {
        $bundle = $this->bundleProducts()->where('bundled_product_id', $bundledProductId)->first();
        return $bundle ? $bundle->pivot->special_price : null;
    }
}
