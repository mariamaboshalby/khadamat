<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarehouseItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'quantity',
        'unit',
        'price',
        'min_quantity',
        'category',
        'image_path',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'price' => 'decimal:2',
        'min_quantity' => 'integer',
    ];

    /**
     * Scope a query to only include items with low stock.
     */
    public function scopeLowStock($query)
    {
        return $query->whereRaw('quantity <= min_quantity');
    }

    /**
     * Scope a query to filter by category.
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Check if the item is low on stock.
     */
    public function isLowStock()
    {
        return $this->quantity <= $this->min_quantity;
    }

    /**
     * Get the stock status.
     */
    public function getStockStatusAttribute()
    {
        if ($this->quantity == 0) {
            return 'نفذ من المخزن';
        } elseif ($this->isLowStock()) {
            return 'كمية قليلة';
        }
        return 'متوفر';
    }
}
