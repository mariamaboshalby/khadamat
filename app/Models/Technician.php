<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Technician extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'specialization_id',
        'availability_status',
        'rating',
        'completed_tasks',
        'bio',
        'address',
        'latitude',
        'longitude',
    ];

    protected $casts = [
        'rating' => 'decimal:2',
        'completed_tasks' => 'integer',
    ];

    /**
     * Get the user that owns the technician profile.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the specialization for this technician.
     */
    public function specialization()
    {
        return $this->belongsTo(Specialization::class);
    }

    /**
     * Scope a query to only include available technicians.
     */
    public function scopeAvailable($query)
    {
        return $query->where('availability_status', 'available');
    }

    /**
     * Scope a query to filter by specialization.
     */
    public function scopeBySpecialization($query, $specializationId)
    {
        return $query->where('specialization_id', $specializationId);
    }

    /**
     * Get the technician's full name from user relationship.
     */
    public function getFullNameAttribute()
    {
        return $this->user->name;
    }

    /**
     * Get reviews for this technician.
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Get badges for this technician.
     */
    public function badges()
    {
        return $this->hasMany(TechnicianBadge::class);
    }
}
