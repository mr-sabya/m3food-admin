<?php

namespace App\Models\Section;


use Illuminate\Database\Eloquent\Model;

class DisclaimerSection extends Model
{
    protected $fillable = [
        'product_id',
        'question',
        'answer',
        'bg_color',
        'text_color'
    ];

    public function productPageSection()
    {
        return $this->morphOne(ProductPageSection::class, 'sectionable');
    }
}
