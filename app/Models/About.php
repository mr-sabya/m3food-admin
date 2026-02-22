<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class About extends Model
{
    protected $fillable = [
        'title',
        'sub_title',
        'description',
        'image',
        'experience_year',
        'experience_text',
        'author_name',
        'features'
    ];

    // Cast features to array automatically
    protected $casts = [
        'features' => 'array',
    ];
}
