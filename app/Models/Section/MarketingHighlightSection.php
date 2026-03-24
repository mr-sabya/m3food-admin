<?php

namespace App\Models\Section;


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

    public function productPageSection()
    {
        return $this->morphOne(ProductPageSection::class, 'sectionable');
    }
}
