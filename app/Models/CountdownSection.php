<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CountdownSection extends Model
{
    protected $fillable = [
        'product_id',
        'offer_title',
        'offer_title_color',
        'stock_count_text',
        'stock_count',
        'end_time',
        'bg_color'
    ];
}
