<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class AdBanner extends Model
{
    protected $fillable = ['title', 'image_path', 'link', 'slug', 'is_active'];

    /**
     * Get the full URL for the banner image.
     */
    public function getImageUrlAttribute(): string
    {
        return asset('storage/' . $this->image_path);
    }

    /**
     * Static helper to find a banner by its position slug.
     * Usage: AdBanner::getPosition('home-top-left')
     */
    public static function getPosition(string $slug)
    {
        return self::where('slug', $slug)->where('is_active', true)->first();
    }
}
