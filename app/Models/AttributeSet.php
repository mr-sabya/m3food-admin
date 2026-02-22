<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Attribute[] $attributes
 */
class AttributeSet extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * Get the attributes that belong to this attribute set.
     */
    public function attributes()
    {
        return $this->belongsToMany(Attribute::class, 'attribute_attribute_set');
    }

    /**
     * Get the products that use this attribute set (for defining static specs).
     */
    public function products()
    {
        return $this->hasMany(Product::class); // If you decide to link products directly to an attribute set for specifications
    }
}
