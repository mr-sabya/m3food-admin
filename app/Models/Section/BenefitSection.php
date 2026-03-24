<?php

namespace App\Models\Section;

use Illuminate\Database\Eloquent\Model;

class BenefitSection extends Model
{
    protected $fillable = [
        'product_id',
        'heading',
        'heading_color',
        'infographic_image'
    ];

    public function items()
    {
        return $this->hasMany(BenefitItem::class)->orderBy('sort_order');
    }

    public function productPageSection()
    {
        return $this->morphOne(ProductPageSection::class, 'sectionable');
    }
}
