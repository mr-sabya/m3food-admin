<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'slug',
        'phone',
        'email',
        'password',
        'avatar',
        'date_of_birth',
        'gender',
        'address',
        'country_id',
        'state_id',
        'city_id',
        'zip_code',
        'role',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'date_of_birth' => 'date',
            'is_active' => 'boolean',
            'role' => UserRole::class, // Cast to UserRole Enum
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    // Location Relationships
    public function country()
    {
        return $this->belongsTo(Country::class);
    }
    public function state()
    {
        return $this->belongsTo(State::class);
    }
    public function city()
    {
        return $this->belongsTo(City::class);
    }

    // Profile Relationships
    public function vendorProfile()
    {
        return $this->hasOne(VendorProfile::class);
    }
    public function investorProfile()
    {
        return $this->hasOne(InvestorProfile::class);
    }

    // Business Relationships
    public function products()
    {
        return $this->hasMany(Product::class, 'vendor_id');
    }
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
    public function addresses()
    {
        return $this->hasMany(Address::class);
    }
    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors & Mutators
    |--------------------------------------------------------------------------
    */

    /**
     * Get the URL to the user's avatar.
     */
    public function getAvatarUrlAttribute(): string
    {
        return $this->avatar
            ? asset('storage/' . $this->avatar)
            : asset('images/default_avatar.png');
    }

    /**
     * Check if a user has purchased a specific product.
     */
    public function hasPurchased($productId): bool
    {
        return $this->orders()
            ->where('order_status', 'delivered')
            ->whereHas('orderItems', fn($q) => $q->where('product_id', $productId))
            ->exists();
    }

    /*
    |--------------------------------------------------------------------------
    | Helper Methods (Role Checks)
    |--------------------------------------------------------------------------
    */

    public function isCustomer(): bool
    {
        return $this->role === UserRole::Customer;
    }

    public function isVendor(): bool
    {
        return $this->role === UserRole::Vendor;
    }

    public function isInvestor(): bool
    {
        return $this->role === UserRole::Investor;
    }
}
