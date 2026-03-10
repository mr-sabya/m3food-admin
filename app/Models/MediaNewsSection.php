<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MediaNewsSection extends Model
{
    protected $fillable = [
        'product_id',
        'title',
        'title_color',
        'title_bg',
        'title_tag'
    ];

    public function videos()
    {
        return $this->hasMany(MediaVideo::class);
    }
}
