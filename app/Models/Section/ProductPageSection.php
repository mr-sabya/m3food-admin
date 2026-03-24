<?php

namespace App\Models\Section;

use Illuminate\Database\Eloquent\Model;

class ProductPageSection extends Model
{
    protected $fillable = [
        'product_id',
        'sectionable_id',
        'sectionable_type',
        'sort_order',
        'is_active'
    ];

    public function sectionable()
    {
        return $this->morphTo();
    }
}
