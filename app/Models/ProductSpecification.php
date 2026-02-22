<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductSpecification extends Model
{
    protected $fillable = ['product_id', 'specification_key_id', 'value'];

    public function key()
    {
        return $this->belongsTo(SpecificationKey::class, 'specification_key_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
