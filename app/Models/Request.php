<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Request extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'user_id',
        'service_id',
        'assigned_technician_id',
        'status',
        'description',
        'scheduled_at',
        'address',
        'latitude',
        'longitude',
        'proposed_price',
        'price_notes',
        'price_status',
        'customer_notes',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'images' => 'array',
    ];

    // علاقة صاحب الطلب
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // علاقة الخدمة
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    // علاقة الفني المعين
    public function assignedTechnician(): BelongsTo
    {
        return $this->belongsTo(Technician::class, 'assigned_technician_id');
    }

    // علاقة قطع الطلب
    public function requestItems()
    {
        return $this->hasMany(RequestItem::class);
    }

    // علاقة عروض الأسعار
    public function proposals()
    {
        return $this->hasMany(RequestProposal::class);
    }

    // علاقة التقييمات
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // حساب إجمالي سعر القطع
    public function getTotalItemsPriceAttribute()
    {
        return $this->requestItems->sum('total_price');
    }

    // حساب السعر الإجمالي
    public function getTotalPriceAttribute()
    {
        return ($this->proposed_price ?? 0) + $this->total_items_price;
    }
}
