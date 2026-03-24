<?php

namespace App\Models\Section;

use Illuminate\Database\Eloquent\Model;

class TestimonialItem extends Model
{
    protected $fillable = ['social_proof_section_id', 'type', 'image_path', 'video_url', 'sort_order'];

    public function section()
    {
        return $this->belongsTo(SocialProofSection::class);
    }

    // Helper to get YouTube ID if type is video
    public function getYoutubeIdAttribute()
    {
        if ($this->type === 'video' && $this->video_url) {
            preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $this->video_url, $match);
            return $match[1] ?? null;
        }
        return null;
    }
}
