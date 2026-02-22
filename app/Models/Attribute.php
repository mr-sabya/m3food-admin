<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Enums\AttributeDisplayType; // We'll create this enum
use Illuminate\Database\Eloquent\Builder;

class Attribute extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'display_type',
        'is_filterable',
        'is_active',
    ];

    protected $casts = [
        'is_filterable' => 'boolean',
        'is_active' => 'boolean',
        'display_type' => AttributeDisplayType::class, // Cast to our custom enum
    ];


    // Add this local scope
    public function scopeActive(Builder $query): void
    {
        $query->where('is_active', true);
    }

    
    /**
     * The "booting" method of the model.
     * Automatically generate slug.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($attribute) {
            if (empty($attribute->slug)) {
                $attribute->slug = Str::slug($attribute->name);
            }
        });

        static::updating(function ($attribute) {
            if (empty($attribute->slug) && $attribute->isDirty('name')) {
                $attribute->slug = Str::slug($attribute->name);
            }
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * Get the values for this attribute.
     */
    public function values()
    {
        return $this->hasMany(AttributeValue::class);
    }

    /**
     * Get the attribute sets that this attribute belongs to.
     */
    public function attributeSets()
    {
        return $this->belongsToMany(AttributeSet::class, 'attribute_attribute_set');
    }
}
