<?php

namespace App\Models\Section;


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

    public function productPageSection()
    {
        return $this->morphOne(ProductPageSection::class, 'sectionable');
    }
}
