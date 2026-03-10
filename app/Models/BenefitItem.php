<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BenefitItem extends Model
{
    protected $fillable = ['benefit_section_id', 'benefit_text', 'sort_order'];
}
