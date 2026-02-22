<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    
    protected $fillable = [
        'name',
        'description',
        'target_amount',
        'status'
    ];

    // Casting target_amount to decimal
    protected $casts = [
        'target_amount' => 'decimal:2'
    ];

    // Define any relationships a project might have, e.g., to investments.
    // For now, we'll leave this empty, but you might add:
    public function investments()
    {
        return $this->hasMany(Investment::class);
    }
}
