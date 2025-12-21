<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'request_id',
        'technician_id',
        'user_id',
        'rating',
        'comment',
        'title',
        'status',
    ];

    protected $casts = [
        'rating' => 'integer',
        'service_date' => 'date',
        'service_cost' => 'decimal:2',
        'is_verified' => 'boolean',
        'verified_at' => 'datetime',
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    public function request(): BelongsTo
    {
        return $this->belongsTo(Request::class);
    }

    public function technician(): BelongsTo
    {
        return $this->belongsTo(Technician::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    // Scopes
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeByTechnician($query, $technicianId)
    {
        return $query->where('technician_id', $technicianId);
    }

    public function scopeByRating($query, $rating)
    {
        return $query->where('rating', $rating);
    }

    // Accessors
    public function getRatingStarsAttribute(): string
    {
        return str_repeat('⭐', $this->rating);
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending' => 'في انتظار المراجعة',
            'approved' => 'موافق عليه',
            'rejected' => 'مرفوض',
            default => 'غير معروف'
        };
    }

    public function getServiceTypeLabelAttribute(): string
    {
        return match($this->service_type) {
            'maintenance' => 'صيانة',
            'installation' => 'تركيب',
            'repair' => 'إصلاح',
            default => 'أخرى'
        };
    }
}
