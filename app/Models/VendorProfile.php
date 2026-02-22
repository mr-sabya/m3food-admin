<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'shop_name',
        'shop_slug',
        'shop_description',
        'shop_logo',
        'shop_banner',
        'phone',
        'email',
        
        // Removed 'address', 'city', 'state', 'zip_code', 'country'
        'address', // Keep address as string for specific street info
        'zip_code', // Keep zip_code as string as it's not a reference
        'country_id', // Add foreign keys
        'state_id',
        'city_id',

        'bank_name',
        'bank_account_number',
        'bank_account_holder',
        'commission_rate',
        'is_approved',
        'is_active',
    ];

    protected $casts = [
        'commission_rate' => 'decimal:2',
        'is_approved' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Get the user that owns the vendor profile.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the products owned by this vendor.
     */
    public function products()
    {
        return $this->hasMany(Product::class, 'vendor_id'); // Assuming Product model exists and has vendor_id
    }

    /**
     * Get the orders associated with this vendor's products.
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'vendor_id'); // For multi-vendor order tracking
    }

    /**
     * Get the payouts for this vendor.
     */
    public function payouts()
    {
        return $this->hasMany(Payout::class, 'vendor_id'); // Assuming Payout model exists
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    /**
     * Get the URL to the shop logo.
     */
    public function getShopLogoUrlAttribute(): ?string
    {
        return $this->shop_logo ? asset('storage/' . $this->shop_logo) : null;
    }

    /**
     * Get the URL to the shop banner.
     */
    public function getShopBannerUrlAttribute(): ?string
    {
        return $this->shop_banner ? asset('storage/' . $this->shop_banner) : null;
    }
}
