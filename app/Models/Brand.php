<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str; // For slug generation
use Illuminate\Database\Eloquent\Builder;

class Brand extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'logo',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];



    // Add this local scope
    public function scopeActive(Builder $query): void
    {
        $query->where('is_active', true);
    }

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        // Automatically generate a slug when creating or updating a brand if not provided
        static::creating(function ($brand) {
            if (empty($brand->slug)) {
                $brand->slug = Str::slug($brand->name);
            }
        });

        static::updating(function ($brand) {
            if (empty($brand->slug) && $brand->isDirty('name')) { // Regenerate slug only if name changed and slug is empty
                $brand->slug = Str::slug($brand->name);
            }
        });
    }

    /**
     * Get the products that belong to this brand.
     */
    public function products()
    {
        return $this->hasMany(Product::class); // Assuming Product model exists and has brand_id
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    /**
     * Get the URL to the brand logo.
     */
    public function getLogoUrlAttribute(): ?string
    {
        return $this->logo ? asset('storage/' . $this->logo) : null;
    }
}
