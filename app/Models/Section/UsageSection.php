<?php

namespace App\Models\Section;


use Illuminate\Database\Eloquent\Model;

class UsageSection extends Model
{
    protected $fillable = ['product_id', 'description'];

    public function items()
    {
        return $this->hasMany(UsageItem::class)->orderBy('sort_order');
    }

    public function productPageSection()
    {
        return $this->morphOne(ProductPageSection::class, 'sectionable');
    }
}
