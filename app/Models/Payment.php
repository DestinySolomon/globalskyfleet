<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'shipment_id',
        'user_id',
        'amount',
        'currency',
        'status',
        'payment_method',
        'transaction_id',
        'crypto_address',
        'crypto_amount',
        'transaction_hash',
        'confirmations',
        'crypto_status',
        'gateway_response',
        'paid_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'gateway_response' => 'array',
        'paid_at' => 'datetime',
    ];

    // Relationship: Payment belongs to a shipment
    public function shipment()
    {
        return $this->belongsTo(Shipment::class);
    }

    // Relationship: Payment belongs to a user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Helper: Check if payment is completed
    public function getIsPaidAttribute()
    {
        return $this->status === 'completed';
    }

    // Helper: Check if payment is pending
    public function getIsPendingAttribute()
    {
        return $this->status === 'pending';
    }
}