<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProposalItem extends Model
{
    protected $fillable = [
        'proposal_id',
        'warehouse_item_id',
        'quantity',
        'unit_price',
        'total_price',
    ];

    public function proposal()
    {
        return $this->belongsTo(RequestProposal::class, 'proposal_id');
    }

    public function warehouseItem()
    {
        return $this->belongsTo(WarehouseItem::class);
    }
}