<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'phone',
        // Removed 'address', 'city', 'state', 'zip_code', 'country'
        'address', // Keep address as string for specific street info
        'zip_code', // Keep zip_code as string as it's not a reference
        'country_id', // Add foreign keys
        'state_id',
        'city_id',
        'role',
        'is_active',
        'date_of_birth',
        'gender',
        'slug',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean', // Cast to boolean
            'role' => UserRole::class, // Cast to our custom UserRole enum
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * A user can have one vendor profile if they are a vendor.
     */
    public function vendorProfile()
    {
        return $this->hasOne(VendorProfile::class); // Assuming VendorProfile model exists
    }

    /**
     * A user can have one investor profile if they are an investor.
     */
    public function investorProfile()
    {
        return $this->hasOne(InvestorProfile::class);
    } // Assuming InvestorProfile model exists

    /**
     * A user (vendor) can have many products.
     */
    public function products()
    {
        return $this->hasMany(Product::class, 'vendor_id'); // Assuming Product model exists
    }

    /**
     * A user (customer) can place many orders.
     */
    public function orders()
    {
        return $this->hasMany(Order::class); // Assuming Order model exists
    }

    /**
     * A user can write many reviews.
     */
    public function reviews()
    {
        return $this->hasMany(Review::class); // Assuming Review model exists
    }

    /**
     * A user can have many addresses.
     */
    public function addresses()
    {
        return $this->hasMany(Address::class); // Assuming Address model exists
    }

    // Add relationships for dynamic location
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

    /**
     * A user can have many items in their wishlist.
     */
    public function wishlists()
    {
        return $this->hasMany(Wishlist::class); // Assuming Wishlist model exists
    }



    /*
    |--------------------------------------------------------------------------
    | Accessors & Mutators
    |--------------------------------------------------------------------------
    */

    public function hasPurchased($productId)
    {
        return $this->orders()->whereHas('orderItems', function ($query) use ($productId) {
            $query->where('product_id', $productId);
        })->where('order_status', 'delivered')->exists();
        // Adjust 'status' and 'items' based on your actual database schema
    }

    /**
     * Get the full name of the user.
     */
    public function getFullNameAttribute(): string
    {
        return $this->name; // If you later split name into first_name/last_name, update this.
    }


    /**
     * Get the URL to the user's avatar.
     */
    public function getAvatarUrlAttribute(): string
    {
        // If avatar is set, return its path, otherwise return a default placeholder.
        return $this->avatar ? asset('storage/' . $this->avatar) : asset('images/default_avatar.png');
    }



    /*
    |--------------------------------------------------------------------------
    | Helper Methods
    |--------------------------------------------------------------------------
    */

    /**
     * Check if the user is a customer.
     */
    public function isCustomer(): bool
    {
        return $this->role->isCustomer();
    }

    /**
     * Check if the user is a vendor.
     */
    public function isVendor(): bool
    {
        return $this->role->isVendor();
    }

    /**
     * Check if the user is an investor.
     */
    public function isInvestor(): bool
    {
        return $this->role->isInvestor();
    }


    // 
}
