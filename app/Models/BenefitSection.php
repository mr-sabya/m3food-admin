<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BenefitSection extends Model
{
    protected $fillable = ['product_id', 'heading', 'heading_color', 'infographic_image'];

    public function items()
    {
        return $this->hasMany(BenefitItem::class)->orderBy('sort_order');
    }
}
