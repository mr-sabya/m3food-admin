<?php

namespace App\Models\Section;

use Illuminate\Database\Eloquent\Model;

class FeatureCard extends Model
{
    protected $fillable = [
        'feature_card_section_id',
        'image_path',
        'sort_order'
    ];
}
