<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Strategy extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'step_number',
        'points',
        'color',
    ];

    protected $casts = [
        'points' => 'array',
    ];
}
