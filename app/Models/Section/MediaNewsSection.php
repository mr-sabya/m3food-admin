<?php

namespace App\Models\Section;


use Illuminate\Database\Eloquent\Model;

class MediaNewsSection extends Model
{
    protected $fillable = [
        'product_id',
        'title',
        'title_color',
        'title_bg',
        'title_tag'
    ];

    public function videos()
    {
        return $this->hasMany(MediaVideo::class);
    }

    public function productPageSection()
    {
        return $this->morphOne(ProductPageSection::class, 'sectionable');
    }
}
