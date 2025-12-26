<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'contact_name',
        'contact_phone',
        'company',
        'address_line1',
        'address_line2',
        'city',
        'state',
        'postal_code',
        'country_code',
        'is_default',
        'latitude',
        'longitude',
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    // Relationship: Address belongs to a User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship: Address as sender for shipments
    public function senderShipments()
    {
        return $this->hasMany(Shipment::class, 'sender_address_id');
    }

    // Relationship: Address as recipient for shipments
    public function recipientShipments()
    {
        return $this->hasMany(Shipment::class, 'recipient_address_id');
    }
}