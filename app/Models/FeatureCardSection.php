<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeatureCardSection extends Model
{
    protected $fillable = [
        'product_id',
        'section_title'
    ];

    public function cards()
    {
        return $this->hasMany(FeatureCard::class)->orderBy('sort_order');
    }
}
