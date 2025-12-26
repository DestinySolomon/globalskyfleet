<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShipmentStatusHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'shipment_id',
        'status',
        'location',
        'description',
        'scan_datetime',
    ];

    protected $casts = [
        'scan_datetime' => 'datetime',
    ];

    // Relationship: Status history belongs to a shipment
    public function shipment()
    {
        return $this->belongsTo(Shipment::class);
    }
}