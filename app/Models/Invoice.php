<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Casts\Attribute;


class Invoice extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'invoice_number',
        'amount',
        'currency',
        'description',
        'invoice_date',
        'due_date',
        'status',
        'items',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'invoice_date' => 'date',
        'due_date' => 'date',
        'items' => 'array',
    ];

    protected $appends = [
        'formatted_amount',
        'is_overdue',
        'days_until_due',
    ];

    // ==================== RELATIONSHIPS ====================

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(CryptoPayment::class);
    }

    // ==================== ACCESSORS & MUTATORS ====================

    protected function formattedAmount(): Attribute
    {
        return Attribute::make(
            get: fn () => number_format($this->amount, 2) . ' ' . $this->currency
        );
    }

    protected function isOverdue(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->status === 'pending' && $this->due_date < now()
        );
    }

    protected function daysUntilDue(): Attribute
    {
        return Attribute::make(
            get: function () {
                if ($this->status === 'paid' || $this->status === 'cancelled') {
                    return null;
                }
                
                $dueDate = $this->due_date;
                $now = now();
                
                if ($dueDate < $now) {
                    return -$dueDate->diffInDays($now);
                }
                
                return $dueDate->diffInDays($now);
            }
        );
    }

    // ==================== SCOPES ====================

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeOverdue($query)
    {
        return $query->where('status', 'pending')
                    ->where('due_date', '<', now());
    }

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    // ==================== METHODS ====================

    public function markAsPaid(): void
    {
        $this->update(['status' => 'paid']);
    }

    public function markAsCancelled(): void
    {
        $this->update(['status' => 'cancelled']);
    }

    public function getStatusBadgeClass(): string
    {
        return match($this->status) {
            'draft' => 'bg-secondary',
            'sent' => 'bg-info',
            'pending' => 'bg-warning',
            'paid' => 'bg-success',
            'overdue' => 'bg-danger',
            'cancelled' => 'bg-dark',
            default => 'bg-secondary',
        };
    }
}