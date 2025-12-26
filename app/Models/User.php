<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // ==================== RELATIONSHIPS ====================

    /**
     * Get all addresses for the user.
     */
    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    /**
     * Get all shipments created by the user.
     */
    public function shipments()
    {
        return $this->hasMany(Shipment::class);
    }

    /**
     * Get all payments made by the user.
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    // ==================== HELPER METHODS ====================

    /**
     * Get the user's default shipping address.
     */
    public function defaultShippingAddress()
    {
        return $this->addresses()
            ->where('type', 'shipping')
            ->where('is_default', true)
            ->first();
    }

    /**
     * Get the user's default billing address.
     */
    public function defaultBillingAddress()
    {
        return $this->addresses()
            ->where('type', 'billing')
            ->where('is_default', true)
            ->first();
    }

    /**
     * Get all shipping addresses.
     */
    public function shippingAddresses()
    {
        return $this->addresses()->where('type', 'shipping');
    }

    /**
     * Get all billing addresses.
     */
    public function billingAddresses()
    {
        return $this->addresses()->where('type', 'billing');
    }

    /**
     * Get recent shipments (last 10).
     */
    public function recentShipments($limit = 10)
    {
        return $this->shipments()
            ->latest()
            ->limit($limit)
            ->get();
    }

    /**
     * Get pending shipments.
     */
    public function pendingShipments()
    {
        return $this->shipments()
            ->whereIn('status', ['pending', 'confirmed'])
            ->get();
    }

    /**
     * Get in-transit shipments.
     */
    public function inTransitShipments()
    {
        return $this->shipments()
            ->whereIn('status', ['picked_up', 'in_transit', 'customs_hold', 'out_for_delivery'])
            ->get();
    }

    /**
     * Get delivered shipments.
     */
    public function deliveredShipments()
    {
        return $this->shipments()
            ->where('status', 'delivered')
            ->get();
    }

    /**
     * Get shipment statistics.
     */
    public function shipmentStats()
    {
        $total = $this->shipments()->count();
        $pending = $this->shipments()->whereIn('status', ['pending', 'confirmed'])->count();
        $inTransit = $this->shipments()->whereIn('status', ['picked_up', 'in_transit', 'customs_hold', 'out_for_delivery'])->count();
        $delivered = $this->shipments()->where('status', 'delivered')->count();

        return [
            'total' => $total,
            'pending' => $pending,
            'in_transit' => $inTransit,
            'delivered' => $delivered,
        ];
    }

    /**
     * Check if user has any saved addresses.
     */
    public function hasAddresses()
    {
        return $this->addresses()->count() > 0;
    }

    /**
     * Check if user has any shipments.
     */
    public function hasShipments()
    {
        return $this->shipments()->count() > 0;
    }

    /**
     * Get user's full name (for display).
     */
    public function getFullNameAttribute()
    {
        return $this->name;
    }

    /**
     * Get user's initials (for avatars).
     */
    public function getInitialsAttribute()
    {
        $names = explode(' ', $this->name);
        $initials = '';
        
        foreach ($names as $name) {
            if (isset($name[0])) {
                $initials .= strtoupper($name[0]);
            }
        }
        
        return substr($initials, 0, 2);
    }
}