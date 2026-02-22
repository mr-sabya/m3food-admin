<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon; // Ensure Carbon is imported
use Illuminate\Support\Facades\Storage; // For image URL generation
use Illuminate\Support\Str; // Import Str for slug generation

class BlogPost extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'image_path',
        'excerpt',
        'content', // For full content
        'blog_category_id',
        'published_at',
        'is_published',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'is_published' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | Model Booting & Slug Generation
    |--------------------------------------------------------------------------
    */

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($post) {
            $post->slug = $post->slug ?? Str::slug($post->title);
            $post->published_at = $post->published_at ?? now(); // Default to now if not set
        });

        static::updating(function ($post) {
            if ($post->isDirty('title') && empty($post->slug)) {
                $post->slug = Str::slug($post->title);
            }
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors (for Frontend Data Transformation)
    |--------------------------------------------------------------------------
    */

    /**
     * Get the full URL for the blog post's image.
     */
    public function getImageUrlAttribute(): ?string
    {
        return $this->image_path
            ? url('storage/' . $this->image_path)
            : null;
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * A blog post belongs to a blog category.
     */
    public function category()
    {
        return $this->belongsTo(BlogCategory::class, 'blog_category_id');
    }

    /**
     * A blog post can have many tags (many-to-many relationship).
     * This requires a `blog_tags` table and a `blog_post_blog_tag` pivot table.
     */
    public function tags()
    {
        return $this->belongsToMany(BlogTag::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopePublished($query)
    {
        return $query->where('is_published', true)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }
}
