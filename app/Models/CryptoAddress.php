<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CryptoAddress extends Model
{
    protected $fillable = [
        'crypto_type',
        'address',
        'label',
        'is_active',
        'usage_count',
        'created_by',
        'notes',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // ==================== RELATIONSHIPS ====================

    /**
     * Get the user who created this crypto address
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get all payments made to this address
     */
    public function payments(): HasMany
    {
        return $this->hasMany(CryptoPayment::class, 'payment_address', 'address');
    }

    /**
     * Get all regular payments made to this address
     */
    public function regularPayments(): HasMany
    {
        return $this->hasMany(Payment::class, 'crypto_address', 'address');
    }

    // ==================== SCOPES ====================

    /**
     * Scope to get only active addresses
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get addresses for a specific crypto type
     */
    public function scopeForCrypto($query, $cryptoType)
    {
        return $query->where('crypto_type', $cryptoType);
    }

    /**
     * Scope to get addresses with usage count above a threshold
     */
    public function scopeUsedMoreThan($query, $count)
    {
        return $query->where('usage_count', '>', $count);
    }

    /**
     * Scope to get addresses ordered by usage
     */
    public function scopeMostUsed($query)
    {
        return $query->orderBy('usage_count', 'desc');
    }

    /**
     * Scope to get addresses ordered by creation date
     */
    public function scopeRecentlyAdded($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    /**
     * Scope to search addresses by label or address
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('label', 'LIKE', "%{$search}%")
              ->orWhere('address', 'LIKE', "%{$search}%")
              ->orWhere('notes', 'LIKE', "%{$search}%");
        });
    }

    // ==================== METHODS ====================

    /**
     * Increment the usage count
     */
    public function incrementUsage(): void
    {
        $this->increment('usage_count');
        $this->save();
    }

    /**
     * Decrement the usage count
     */
    public function decrementUsage(): void
    {
        if ($this->usage_count > 0) {
            $this->decrement('usage_count');
            $this->save();
        }
    }

    /**
     * Get display name for the address
     */
    public function getDisplayName(): string
    {
        if ($this->label) {
            return "{$this->crypto_type} - {$this->label}";
        }
        
        return "{$this->crypto_type} Wallet";
    }

    /**
     * Get shortened address for display
     */
    public function getShortAddress(): string
    {
        if (strlen($this->address) > 16) {
            return substr($this->address, 0, 8) . '...' . substr($this->address, -8);
        }
        
        return $this->address;
    }

    /**
     * Get formatted crypto type name
     */
    public function getCryptoTypeName(): string
    {
        $types = [
            'BTC' => 'Bitcoin',
            'USDT_ERC20' => 'USDT (ERC20)',
            'USDT_TRC20' => 'USDT (TRC20)',
        ];
        
        return $types[$this->crypto_type] ?? $this->crypto_type;
    }

    /**
     * Get formatted creation date
     */
    public function getCreatedAtFormatted(): string
    {
        return $this->created_at->format('M d, Y H:i');
    }

    /**
     * Get the total amount received in this address
     */
    public function getTotalReceived(): float
    {
        $total = $this->payments()->where('status', 'completed')->sum('usdt_amount');
        return $total ? (float) $total : 0;
    }

    /**
     * Get the total amount received in USD
     */
    public function getTotalReceivedUsd(): float
    {
        $total = $this->regularPayments()->where('status', 'completed')->sum('amount');
        return $total ? (float) $total : 0;
    }

    /**
     * Check if the address has been used for payments
     */
    public function hasBeenUsed(): bool
    {
        return $this->usage_count > 0 || 
               $this->payments()->exists() || 
               $this->regularPayments()->exists();
    }

    /**
     * Get the last payment made to this address
     */
    public function getLastPayment()
    {
        $cryptoPayment = $this->payments()->latest()->first();
        $regularPayment = $this->regularPayments()->latest()->first();
        
        if (!$cryptoPayment && !$regularPayment) {
            return null;
        }
        
        if (!$cryptoPayment) {
            return $regularPayment;
        }
        
        if (!$regularPayment) {
            return $cryptoPayment;
        }
        
        return $cryptoPayment->created_at > $regularPayment->created_at 
            ? $cryptoPayment 
            : $regularPayment;
    }

    /**
     * Get status badge HTML
     */
    public function getStatusBadge(): string
    {
        if ($this->is_active) {
            return '<span class="badge bg-success">Active</span>';
        }
        
        return '<span class="badge bg-secondary">Inactive</span>';
    }

    /**
     * Get crypto type badge HTML
     */
    public function getCryptoTypeBadge(): string
    {
        $classes = [
            'BTC' => 'bg-warning text-dark',
            'USDT_ERC20' => 'bg-info text-dark',
            'USDT_TRC20' => 'bg-success',
        ];
        
        $class = $classes[$this->crypto_type] ?? 'bg-secondary';
        
        return '<span class="badge ' . $class . '">' . $this->getCryptoTypeName() . '</span>';
    }

    /**
     * Activate the address
     */
    public function activate(): bool
    {
        $this->is_active = true;
        return $this->save();
    }

    /**
     * Deactivate the address
     */
    public function deactivate(): bool
    {
        $this->is_active = false;
        return $this->save();
    }

    /**
     * Toggle active status
     */
    public function toggleActive(): bool
    {
        $this->is_active = !$this->is_active;
        return $this->save();
    }

    /**
     * Check if address can be deleted (not used)
     */
    public function canBeDeleted(): bool
    {
        return !$this->hasBeenUsed();
       
    }

    /**
     * Get QR code URL for the address (using external service)
     */
    public function getQrCodeUrl($size = 200): string
    {
        $address = urlencode($this->address);
        
        if ($this->crypto_type === 'BTC') {
            return "https://api.qrserver.com/v1/create-qr-code/?size={$size}x{$size}&data=bitcoin:{$address}";
        }
        
        return "https://api.qrserver.com/v1/create-qr-code/?size={$size}x{$size}&data={$address}";
    }

    /**
     * Get explorer URL for the address
     */
    public function getExplorerUrl(): string
    {
        $explorers = [
            'BTC' => "https://www.blockchain.com/explorer/addresses/btc/{$this->address}",
            'USDT_ERC20' => "https://etherscan.io/address/{$this->address}",
            'USDT_TRC20' => "https://tronscan.org/#/address/{$this->address}",
        ];
        
        return $explorers[$this->crypto_type] ?? '#';
    }

    /**
     * Get usage level based on usage count
     */
    public function getUsageLevel(): string
    {
        if ($this->usage_count === 0) {
            return 'Never Used';
        }
        
        if ($this->usage_count <= 5) {
            return 'Lightly Used';
        }
        
        if ($this->usage_count <= 20) {
            return 'Moderately Used';
        }
        
        return 'Heavily Used';
    }

    /**
     * Get statistics for this address
     */
    public function getStats(): array
    {
        return [
            'total_payments' => $this->payments()->count() + $this->regularPayments()->count(),
            'completed_payments' => $this->payments()->where('status', 'completed')->count() + 
                                   $this->regularPayments()->where('status', 'completed')->count(),
            'pending_payments' => $this->payments()->where('status', 'pending')->count() + 
                                 $this->regularPayments()->where('status', 'pending')->count(),
            'total_received_crypto' => $this->getTotalReceived(),
            'total_received_usd' => $this->getTotalReceivedUsd(),
            'last_payment_date' => $this->getLastPayment()?->created_at,
            'usage_level' => $this->getUsageLevel(),
        ];
    }

    /**
     * Check if address is valid format (basic validation)
     */
    public function isValidFormat(): bool
    {
        $lengths = [
            'BTC' => [26, 35], // Bitcoin addresses are 26-35 chars
            'USDT_ERC20' => [42, 42], // Ethereum addresses are 42 chars (0x + 40 hex)
            'USDT_TRC20' => [34, 34], // Tron addresses are 34 chars
        ];
        
        if (!isset($lengths[$this->crypto_type])) {
            return false;
        }
        
        [$min, $max] = $lengths[$this->crypto_type];
        $length = strlen($this->address);
        
        return $length >= $min && $length <= $max;
    }

    /**
     * Get the next recommended wallet based on usage
     * (For round-robin distribution among active wallets)
     */
    public static function getNextAvailable(string $cryptoType): ?self
    {
        return self::active()
            ->forCrypto($cryptoType)
            ->orderBy('usage_count')
            ->orderBy('created_at')
            ->first();
    }

    /**
     * Get all crypto types with their display names
     */
    public static function getCryptoTypes(): array
    {
        return [
            'BTC' => 'Bitcoin (BTC)',
            'USDT_ERC20' => 'USDT (ERC20)',
            'USDT_TRC20' => 'USDT (TRC20)',
        ];
    }
}