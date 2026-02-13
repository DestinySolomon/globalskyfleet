<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShipmentStatusHistory extends Model
{
    use HasFactory;

    protected $table = 'shipment_status_history';

    protected $fillable = [
        'shipment_id',
        'status',
        'location',
        'latitude',      // ADDED for maps
        'longitude',     // ADDED for maps
        'description',
        'scan_datetime',
    ];

    protected $casts = [
        'scan_datetime' => 'datetime',
        'latitude' => 'float',    // ADDED for maps
        'longitude' => 'float',   // ADDED for maps
    ];

    // Relationship: Status history belongs to a shipment
    public function shipment()
    {
        return $this->belongsTo(Shipment::class);
    }

    // NEW: Helper method to check if has coordinates
    public function getHasCoordinatesAttribute()
    {
        return !is_null($this->latitude) && !is_null($this->longitude);
    }

    // NEW: Helper to get coordinates as array
    public function getCoordinatesAttribute()
    {
        if ($this->has_coordinates) {
            return [
                'latitude' => $this->latitude,
                'longitude' => $this->longitude
            ];
        }
        return null;
    }

    // NEW: Helper to get formatted location string with coordinates
    public function getLocationWithCoordinatesAttribute()
    {
        if ($this->has_coordinates && $this->location) {
            return $this->location . " [" . round($this->latitude, 4) . ", " . round($this->longitude, 4) . "]";
        }
        return $this->location;
    }
}