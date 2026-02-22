<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str; // Import Str for slug generation

class BlogCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
    ];

    /**
     * Boot the model.
     * Automatically generate slug before saving.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            $category->slug = $category->slug ?? Str::slug($category->name);
        });

        static::updating(function ($category) {
            if ($category->isDirty('name') && empty($category->slug)) { // Only update slug if name changed and slug is empty
                $category->slug = Str::slug($category->name);
            }
        });
    }

    /**
     * A blog category can have many blog posts.
     */
    public function blogPosts()
    {
        return $this->hasMany(BlogPost::class, 'blog_category_id'); // Specify foreign key
    }
}
