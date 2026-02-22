<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Collection extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category_id', // Add category_id to fillable
        'title',
        'description',
        'featured_price',
        'image_path',
        'image_alt',
        'tag',
        'display_order',
        'is_active',
    ];

    protected $casts = [
        'featured_price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | Accessors (for Frontend Data Transformation)
    |--------------------------------------------------------------------------
    */

    public function getImageUrlAttribute(): ?string
    {
        if ($this->image_path) {
            return Storage::url($this->image_path);
        }
        return null;
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    // A collection belongs to a category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }


}