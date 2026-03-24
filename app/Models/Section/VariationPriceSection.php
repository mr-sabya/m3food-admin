<?php

namespace App\Models\Section;


use Illuminate\Database\Eloquent\Model;

class VariationPriceSection extends Model
{
    protected $fillable = [
        'product_id',
        'footer_note'
    ];

    public function productPageSection()
    {
        return $this->morphOne(ProductPageSection::class, 'sectionable');
    }
}
