<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = ['name', 'icon', 'color_class', 'route_name', 'specialization_id'];

    /**
     * Get the specialization that owns the service.
     */
    public function specialization()
    {
        return $this->belongsTo(Specialization::class);
    }
}
