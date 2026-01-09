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
        'service_id',
        'sender_address_id',
        'recipient_address_id',
        'status',
        'current_location',
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
        return $this->hasMany(ShipmentStatusHistory::class);
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


    // Relationship: Shipment has documents
public function documents()
{
    return $this->hasMany(Document::class);
}
}