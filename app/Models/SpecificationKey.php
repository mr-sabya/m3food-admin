<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class SpecificationKey extends Model
{
    protected $fillable = ['name', 'slug', 'group'];

    protected static function boot()
    {
        parent::boot();
        static::creating(fn($model) => $model->slug = $model->slug ?? Str::slug($model->name));
    }

    public function productSpecifications()
    {
        return $this->hasMany(ProductSpecification::class);
    }
}
