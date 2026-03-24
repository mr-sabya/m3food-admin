<?php

namespace App\Models\Section;


use Illuminate\Database\Eloquent\Model;

class WhyChoose extends Model
{
    protected $fillable = [
        'title',
        'image',
        'items'
    ];

    protected $casts = [
        'items' => 'array',
    ];

    public function productPageSection()
    {
        return $this->morphOne(ProductPageSection::class, 'sectionable');
    }
}
