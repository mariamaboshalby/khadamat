<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    protected $fillable = [
        'title', 'subtitle_1', 'subtitle_2', 'badge_text', 
        'discount_value', 'discount_label', 'gradient_class', 
        'icon', 'discount_color_class'
    ];
}
