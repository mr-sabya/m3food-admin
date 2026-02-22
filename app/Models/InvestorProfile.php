<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvestorProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'company_name',
        'investment_focus',
        'website',
        'contact_person_name',
        'contact_person_email',
        'contact_person_phone',

        // Removed 'address', 'city', 'state', 'zip_code', 'country'
        'address', // Keep address as string for specific street info
        'zip_code', // Keep zip_code as string as it's not a reference
        'country_id', // Add foreign keys
        'state_id',
        'city_id',

        'min_investment_amount',
        'max_investment_amount',
        'notes',
        'is_approved',
        'is_active',
    ];

    protected $casts = [
        'min_investment_amount' => 'decimal:2',
        'max_investment_amount' => 'decimal:2',
        'is_approved' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Get the user that owns the investor profile.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

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
     * Define any relationships an investor might have, e.g., to investment opportunities.
     * For now, we'll leave this empty, but you might add:
     */
    public function investments()
    {
        return $this->hasMany(Investment::class);
    }
}
