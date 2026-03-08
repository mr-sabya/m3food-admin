<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class Theme extends Model
{
    protected $fillable = [
        'title',
        'preview_image',
        'type',
        'settings',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'settings' => 'array', // Automatically handles JSON conversion
    ];

    /**
     * Scope to filter themes by page type (home, shop, etc.)
     */
    public function scopeActiveByType(Builder $query, string $type): Builder
    {
        return $query->where('type', $type)->where('is_active', true);
    }

    /**
     * Helper to get the full URL for the preview image.
     */
    public function getPreviewImageUrlAttribute(): ?string
    {
        return $this->preview_image ? asset('storage/' . $this->preview_image) : null;
    }

    /**
     * Relationship: One theme can be used on many pages.
     */
    public function pages(): HasMany
    {
        return $this->hasMany(Page::class);
    }
}
