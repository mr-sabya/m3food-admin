<?php

namespace App\Models\Section;


use Illuminate\Database\Eloquent\Model;

class ComparisonSection extends Model
{
    protected $fillable = [
        'product_id',
        'title',
        'title_tag',
        'title_color',
        'border_color'
    ];

    public function productPageSection()
    {
        return $this->morphOne(ProductPageSection::class, 'sectionable');
    }

    public function items()
    {
        return $this->hasMany(ComparisonItem::class)->orderBy('sort_order');
    }
}
