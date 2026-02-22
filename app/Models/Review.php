<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\ReviewStatus; // We'll create this enum

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'rating',
        'comment',
        'is_approved',
        'status',
    ];

    protected $casts = [
        'rating' => 'integer',
        'is_approved' => 'boolean',
        'status' => ReviewStatus::class, // Cast to Enum
    ];

    /**
     * The "booting" method of the model.
     * Ensure rating is within 1-5 range.
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($review) {
            $review->rating = max(1, min(5, $review->rating)); // Ensure rating is between 1 and 5
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * Get the user who wrote the review.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the product that the review is for.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeApproved($query)
    {
        return $query->where('status', ReviewStatus::Approved);
    }

    public function scopePending($query)
    {
        return $query->where('status', ReviewStatus::Pending);
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    /**
     * Check if the review has been approved.
     */
    public function getIsApprovedAttribute(): bool
    {
        return $this->status === ReviewStatus::Approved;
    }
}
