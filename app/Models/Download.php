<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str; // For token generation

class Download extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'order_item_id',
        'download_token',
        'download_count',
        'expires_at',
        'file_path',
    ];

    protected $casts = [
        'download_count' => 'integer',
        'expires_at' => 'datetime',
    ];

    /**
     * The "booting" method of the model.
     * Generate unique download token upon creation.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($download) {
            if (empty($download->download_token)) {
                $download->download_token = Str::random(32); // Generate a unique token
                // Ensure uniqueness in a loop if high contention is expected
                while (static::where('download_token', $download->download_token)->exists()) {
                    $download->download_token = Str::random(32);
                }
            }
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * Get the user who owns this download.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the digital product associated with this download.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the order item that led to this download.
     */
    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Helper Methods
    |--------------------------------------------------------------------------
    */

    /**
     * Check if the download link is valid.
     */
    public function isValid(): bool
    {
        return (!$this->expires_at || $this->expires_at->isFuture()) &&
            (!$this->product->download_limit || $this->download_count < $this->product->download_limit);
    }

    /**
     * Get the full URL to the downloadable file.
     */
    public function getDownloadUrlAttribute(): string
    {
        // This would typically go through a route that validates the token and serves the file
        return route('download.file', ['token' => $this->download_token]);
    }

    /**
     * Get the direct path to the file in storage.
     */
    public function getStoragePathAttribute(): string
    {
        return storage_path('app/' . $this->file_path);
    }
}
