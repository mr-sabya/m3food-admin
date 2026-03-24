<?php

namespace App\Models\Section;


use Illuminate\Database\Eloquent\Model;

class SocialProofSection extends Model
{
    protected $fillable = [
        'product_id',
        'heading',
        'bg_color',
        'text_color'
    ];

    // Link to the Orchestrator for Drag & Drop
    public function productPageSection()
    {
        return $this->morphOne(ProductPageSection::class, 'sectionable');
    }

    public function items()
    {
        return $this->hasMany(TestimonialItem::class)->orderBy('sort_order');
    }
}
