<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Footer extends Model
{
    protected $fillable = ['title', 'preview_image', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Helper to get the full URL for the preview image.
     */
    public function getPreviewImageUrlAttribute(): ?string
    {
        return $this->preview_image ? asset('storage/' . $this->preview_image) : null;
    }

    /**
     * Relationship: One footer can be used on many pages.
     */
    public function pages(): HasMany
    {
        return $this->hasMany(Page::class);
    }
}
