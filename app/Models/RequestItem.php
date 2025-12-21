<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestItem extends Model
{
    protected $fillable = [
        'request_id',
        'warehouse_item_id',
        'quantity',
        'unit_price',
        'total_price',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
    ];

    public function request()
    {
        return $this->belongsTo(Request::class);
    }

    public function warehouseItem()
    {
        return $this->belongsTo(WarehouseItem::class);
    }
}