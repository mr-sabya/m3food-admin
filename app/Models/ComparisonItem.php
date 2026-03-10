<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComparisonItem extends Model
{
    protected $fillable = ['comparison_section_id', 'point_text', 'sort_order'];
}
