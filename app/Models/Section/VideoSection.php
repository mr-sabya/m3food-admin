<?php

namespace App\Models\Section;


use Illuminate\Database\Eloquent\Model;

class VideoSection extends Model
{
    protected $fillable = [
        'product_id',
        'video_url',
        'video_title'
    ];

    public function productPageSection()
    {
        return $this->morphOne(ProductPageSection::class, 'sectionable');
    }
}
