<?php

namespace App\Models\Section;

use Illuminate\Database\Eloquent\Model;

class ComparisonItem extends Model
{
    protected $fillable = [
        'comparison_section_id',
        'point_text',
        'sort_order'
    ];
}
