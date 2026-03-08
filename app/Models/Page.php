<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class Page extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'page_type',
        'status',
        'theme_id',
        'header_id',
        'footer_id',
        'is_header_visible',
        'is_footer_visible',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'og_image',
        'main_product_id',
        'upsell_product_ids',
        'show_checkout_form',
        'show_timer',
        'timer_ends_at',
        'content',
        'published_at'
    ];

    protected $casts = [
        'content' => 'array',
        'upsell_product_ids' => 'array',
        'timer_ends_at' => 'datetime',
        'is_header_visible' => 'boolean',
        'is_footer_visible' => 'boolean',
        'show_checkout_form' => 'boolean',
        'show_timer' => 'boolean',
        'published_at' => 'datetime',
    ];

    // --- Relationships ---

    public function theme(): BelongsTo
    {
        return $this->belongsTo(Theme::class);
    }
    public function header(): BelongsTo
    {
        return $this->belongsTo(Header::class);
    }
    public function footer(): BelongsTo
    {
        return $this->belongsTo(Footer::class);
    }
    public function mainProduct(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'main_product_id');
    }

    // --- Scopes (For clean queries) ---

    public function scopePublished(Builder $query)
    {
        return $query->where('status', 'published')
            ->where('published_at', '<=', now());
    }

    // --- Accessors (SEO Fallbacks for React) ---

    public function getSeoDataAttribute()
    {
        return [
            'title' => $this->meta_title ?: $this->title,
            'description' => $this->meta_description ?: 'Welcome to M3 Food',
            'keywords' => $this->meta_keywords,
            'og_image' => $this->og_image ? asset('storage/' . $this->og_image) : asset('default-share.jpg'),
            'robots' => $this->meta_robots,
        ];
    }

    // --- Helper Logic ---

    public function hasActiveTimer(): bool
    {
        return $this->show_timer && $this->timer_ends_at && $this->timer_ends_at->isFuture();
    }
}
