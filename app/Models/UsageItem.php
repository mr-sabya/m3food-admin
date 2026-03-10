<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsageItem extends Model
{
    protected $fillable = ['usage_section_id', 'food_name', 'image_path', 'sort_order'];
}
