<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'description',
        'is_international',
        'max_weight',
        'max_dimensions',
        'transit_time_min',
        'transit_time_max',
        'is_active',
    ];

    protected $casts = [
        'is_international' => 'boolean',
        'is_active' => 'boolean',
        'max_weight' => 'decimal:3',
        'max_dimensions' => 'array',
    ];

    // Relationship: Service has many shipments
    public function shipments()
    {
        return $this->hasMany(Shipment::class);
    }
}