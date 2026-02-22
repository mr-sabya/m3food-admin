<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str; // For slug generation

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'parent_id',
        'image',
        'icon',
        'is_active',
        'show_on_homepage',
        'sort_order',
        'seo_title',
        'seo_description',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'show_on_homepage' => 'boolean',
        'sort_order' => 'integer',
    ];


    /**
     * The "booting" method of the model.
     * Automatically generate slug.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });

        static::updating(function ($category) {
            if (empty($category->slug) && $category->isDirty('name')) {
                $category->slug = Str::slug($category->name);
            }
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * Get the parent category.
     */
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    /**
     * Get the child categories.
     */
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    /**
     * A category can have many products.
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'category_product');
    }

    /**
     * Get the coupons associated with this category.
     */
    public function coupons()
    {
        return $this->belongsToMany(Coupon::class); // Requires a pivot table: category_coupon
    }


    // A category can have many collections
    public function collections()
    {
        return $this->hasMany(Collection::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    // Add this local scope
    public function scopeActive(Builder $query): void
    {
        $query->where('is_active', true);
    }

    public function scopeFeaturedOnHomepage($query)
    {
        return $query->where('show_on_homepage', true)->where('is_active', true);
    }

    public function scopeParentCategories($query)
    {
        return $query->whereNull('parent_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    /**
     * Get the URL to the category image.
     */
    /**
     * Get the URL to the category image.
     */
    public function getImageUrlAttribute(): ?string
    {
        if (empty($this->image)) {
            return null;
        }
        // Handle both seeded assets and uploaded storage files
        if (str_starts_with($this->image, 'assets/')) {
            return asset($this->image);
        }
        return asset('storage/' . $this->image);
    }

    /**
     * Get the URL to the category icon.
     * Usage: $category->icon_url
     */
    public function getIconUrlAttribute(): ?string
    {
        if (empty($this->icon)) {
            // Optional: Return a default placeholder icon if null
            // return asset('assets/frontend/images/default-icon.svg'); 
            return null;
        }

        // Handle both seeded assets and uploaded storage files
        if (str_starts_with($this->icon, 'assets/')) {
            return asset($this->icon);
        }

        return asset('storage/' . $this->icon);
    }
}
