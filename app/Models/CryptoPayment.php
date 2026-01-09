<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CryptoPayment extends Model
{
    protected $fillable = [
        'invoice_id',
        'user_id',
        'crypto_type',
        'crypto_amount',
        'usdt_amount',
        'payment_address',
        'transaction_id',
        'payment_proof',
        'status',
        'confirmations',
        'exchange_rate',
        'paid_at',
        'confirmed_at',
        'expires_at',
        'admin_notes',
        'verified_by',
        'verified_at',
    ];

    protected $casts = [
        'crypto_amount' => 'decimal:8',
        'usdt_amount' => 'decimal:6',
        'exchange_rate' => 'decimal:8',
        'paid_at' => 'datetime',
        'confirmed_at' => 'datetime',
        'expires_at' => 'datetime',
        'verified_at' => 'datetime',
    ];

    // ==================== RELATIONSHIPS ====================

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    // ==================== SCOPES ====================

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    // ==================== METHODS ====================

    public function markAsConfirmed(): void
    {
        $this->update([
            'status' => 'confirmed',
            'confirmed_at' => now(),
        ]);
    }

    public function markAsCompleted(): void
    {
        $this->update(['status' => 'completed']);
    }

    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at < now();
    }

    public function getStatusBadgeClass(): string
    {
        return match($this->status) {
            'pending' => 'bg-warning',
            'processing' => 'bg-info',
            'confirmed' => 'bg-primary',
            'completed' => 'bg-success',
            'failed' => 'bg-danger',
            'expired' => 'bg-dark',
            default => 'bg-secondary',
        };
    }
}