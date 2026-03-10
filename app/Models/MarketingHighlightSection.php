<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarketingHighlightSection extends Model
{
    protected $fillable = [
        'product_id',
        'top_boxed_text',
        'top_box_border_color',
        'bottom_boxed_text',
        'bottom_box_border_color'
    ];
}
