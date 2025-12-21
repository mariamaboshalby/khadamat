<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TechnicianBadge extends Model
{
    protected $fillable = ['technician_id', 'badge_type'];

    public function technician()
    {
        return $this->belongsTo(Technician::class);
    }

    public function getBadgeNameAttribute()
    {
        return match($this->badge_type) {
            'beginner' => 'مبتدئ',
            'intermediate' => 'متوسط',
            'expert' => 'خبير',
            'master' => 'متمكن',
            default => $this->badge_type,
        };
    }

    public function getBadgeColorAttribute()
    {
        return match($this->badge_type) {
            'beginner' => '#10b981',
            'intermediate' => '#3b82f6',
            'expert' => '#f59e0b',
            'master' => '#8b5cf6',
            default => '#6b7280',
        };
    }

    public function getBadgeIconAttribute()
    {
        return match($this->badge_type) {
            'beginner' => 'fa-seedling',
            'intermediate' => 'fa-star-half-stroke',
            'expert' => 'fa-star',
            'master' => 'fa-crown',
            default => 'fa-certificate',
        };
    }
}
