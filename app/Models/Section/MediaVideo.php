<?php

namespace App\Models\Section;

use Illuminate\Database\Eloquent\Model;

class MediaVideo extends Model
{
    protected $fillable = [
        'media_news_section_id',
        'video_title',
        'video_link',
        'thumbnail'
    ];
}
