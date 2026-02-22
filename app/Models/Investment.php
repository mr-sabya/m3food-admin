<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Investment extends Model
{
    use HasFactory;

    protected $fillable = [
        'investor_profile_id',
        'project_id', // Optional: Link to a specific project or opportunity
        'amount',
        'currency',
        'investment_date',
        'status', // e.g., 'pending', 'committed', 'funded', 'returned', 'failed'
        'return_on_investment', // Could be a percentage or specific amount
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'investment_date' => 'date', // Cast as a date object
    ];

    /**
     * Get the investor profile that made this investment.
     */
    public function investorProfile()
    {
        return $this->belongsTo(InvestorProfile::class);
    }

    /**
     * Get the project or opportunity associated with this investment.
     * This assumes you will have a 'Project' model later.
     */
    public function project()
    {
        return $this->belongsTo(Project::class); // Assuming Project model exists
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors & Mutators (Optional)
    |--------------------------------------------------------------------------
    */

    /**
     * Get the formatted investment amount with currency.
     */
    public function getFormattedAmountAttribute(): string
    {
        return number_format($this->amount, 2) . ' ' . ($this->currency ?? 'USD');
    }
}