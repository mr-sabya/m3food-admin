<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TitleSection extends Model
{
    protected $fillable = [
        'product_id',
        'title',
        'title_color',
        'title_bg',
        'title_tag',
        'subtitle',
        'subtitle_color',
        'subtitle_bg',
        'subtitle_tag'
    ];

    public function productPageSection()
    {
        return $this->morphOne(ProductPageSection::class, 'sectionable');
    }
}
