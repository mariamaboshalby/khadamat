<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestProposal extends Model
{
    protected $fillable = [
        'request_id',
        'technician_id',
        'proposed_price',
        'price_notes',
        'status',
    ];

    public function request()
    {
        return $this->belongsTo(Request::class);
    }

    public function technician()
    {
        return $this->belongsTo(Technician::class);
    }

    public function items()
    {
        return $this->hasMany(ProposalItem::class, 'proposal_id');
    }

    public function getTotalPriceAttribute()
    {
        return $this->proposed_price + $this->items->sum('total_price');
    }
}