<?php

namespace App\Models\Section;


use Illuminate\Database\Eloquent\Model;

class CautionSection extends Model
{
    protected $fillable = [
        'product_id',
        'description',
        'text_color',
        'border_color',
        'divider_icon'
    ];

    // Links to the Livewire Sorter
    public function productPageSection()
    {
        return $this->morphOne(ProductPageSection::class, 'sectionable');
    }
}
