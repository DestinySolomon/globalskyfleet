<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Models\ShipmentStatusHistory;

class Shipment extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';
    protected $primaryKey = 'id';

    protected $fillable = [
        'tracking_number',
        'user_id',
        'invoice_id',
        'service_id',
        'sender_address_id',
        'recipient_address_id',
        'status',
        'current_location',
        'latitude',           // ADDED for maps
        'longitude',          // ADDED for maps
        'location_updated_at', // ADDED for maps
        'weight',
        'dimensions',
        'declared_value',
        'currency',
        'content_description',
        'insurance_amount',
        'insurance_enabled',
        'requires_signature',
        'is_dangerous_goods',
        'special_instructions',
        'estimated_delivery',
        'actual_delivery',
        'pickup_date',
    ];

    protected $casts = [
        'id' => 'string',
        'insurance_enabled' => 'boolean',
        'requires_signature' => 'boolean',
        'is_dangerous_goods' => 'boolean',
        'weight' => 'decimal:3',
        'declared_value' => 'decimal:2',
        'insurance_amount' => 'decimal:2',
        'dimensions' => 'array',
        'estimated_delivery' => 'datetime',
        'actual_delivery' => 'datetime',
        'pickup_date' => 'datetime',
        'user_id' => 'integer',
        'latitude' => 'float',          // ADDED for maps
        'longitude' => 'float',         // ADDED for maps
        'location_updated_at' => 'datetime', // ADDED for maps
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }
            
            if (empty($model->tracking_number)) {
                $model->tracking_number = static::generateTrackingNumber();
            }
        });
    }

    public static function generateTrackingNumber()
    {
        do {
            // Format: GS + 8 random ALPHANUMERIC (only uppercase letters and numbers)
            $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $trackingNumber = 'GS';
            
            // Generate 8 random characters
            for ($i = 0; $i < 8; $i++) {
                $trackingNumber .= $characters[rand(0, strlen($characters) - 1)];
            }
            
            // Check if it already exists
        } while (self::where('tracking_number', $trackingNumber)->exists());
        
        return $trackingNumber;
    }

    // Relationship: Shipment belongs to a User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship: Shipment belongs to a Service
    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    // Relationship: Shipment has a sender address
    public function senderAddress()
    {
        return $this->belongsTo(Address::class, 'sender_address_id');
    }

    // Relationship: Shipment has a recipient address
    public function recipientAddress()
    {
        return $this->belongsTo(Address::class, 'recipient_address_id');
    }

    // Relationship: Shipment has status history
    public function statusHistory()
    {
        return $this->hasMany(ShipmentStatusHistory::class, 'shipment_id')->orderBy('scan_datetime', 'desc');
    }

    // Relationship: Shipment has payments
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    // Relationship: Shipment has customs declaration
    public function customsDeclaration()
    {
        return $this->hasOne(CustomsDeclaration::class);
    }

    // Relationship: Shipment has documents
    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    // Relationship: Shipment has an invoice
    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }

    // Helper: Get latest status
    public function getLatestStatusAttribute()
    {
        return $this->statusHistory()
            ->latest('scan_datetime')
            ->first();
    }

    // Helper: Check if shipment is delivered
    public function getIsDeliveredAttribute()
    {
        return $this->status === 'delivered';
    }

    // Helper: Check if shipment is in transit
    public function getIsInTransitAttribute()
    {
        return in_array($this->status, ['in_transit', 'out_for_delivery', 'customs_hold']);
    }

    // NEW: Helper method to check if shipment has coordinates
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

    // NEW: Helper to get formatted location string
    public function getFullLocationAttribute()
    {
        if ($this->has_coordinates && $this->current_location) {
            return $this->current_location . " [" . round($this->latitude, 4) . ", " . round($this->longitude, 4) . "]";
        }
        return $this->current_location;
    }
}