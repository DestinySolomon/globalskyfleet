<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

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
        'profile_picture',
        'company',
        'bio',
        'settings',
        'two_factor_enabled',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'password_changed_at',
        'last_login_at',
        'last_login_ip',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
        'two_factor_recovery_codes',
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
            'settings' => 'array',
            'two_factor_enabled' => 'boolean',
            'two_factor_recovery_codes' => 'array',
            'password_changed_at' => 'datetime',
            'last_login_at' => 'datetime',
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
     * Get all documents uploaded by the user.
     */
    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    /**
     * Get all payments made by the user.
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    // ==================== ADMIN METHODS ====================

   public function isAdmin()
   {
    return $this->role === 'admin';
   }

// Super Admin Check
public function isSuperAdmin()
{
    return $this->email === 'superadmin@globalskyfleet.com' || $this->role === 'super_admin';
}

public function isAdminOrSuperAdmin()
{
    return $this->isAdmin() || $this->isSuperAdmin();
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

    /**
     * Get all invoices for the user.
     */
    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    /**
     * Get all crypto payments made by the user.
     */
    public function cryptoPayments()
    {
        return $this->hasMany(CryptoPayment::class);
    }

    /**
     * Get pending invoices.
     */
    public function pendingInvoices()
    {
        return $this->invoices()->where('status', 'pending');
    }

    /**
     * Get overdue invoices.
     */
    public function overdueInvoices()
    {
        return $this->invoices()->where('status', 'pending')
                           ->where('due_date', '<', now());
    }

    /**
     * Get total balance due.
     */
    public function getBalanceDueAttribute()
    {
        return $this->invoices()->where('status', 'pending')->sum('amount');
    }

    /**
     * Get user's profile picture URL.
     */
    public function getProfilePictureUrlAttribute()
    {
        if ($this->profile_picture && Storage::disk('public')->exists('profile-pictures/' . $this->profile_picture)) {
            return Storage::disk('public')->url('profile-pictures/' . $this->profile_picture);
        }
        
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&color=FFFFFF&background=0a2463';
    }

    /**
     * Get user's notification settings.
     */
    public function getNotificationSettingsAttribute()
    {
        $defaults = [
            'email_notifications' => true,
            'sms_notifications' => false,
            'shipment_updates' => true,
            'promotional_emails' => false,
            'billing_notifications' => true,
        ];
        
        return array_merge($defaults, data_get($this->settings, 'notifications', []));
    }

    /**
     * Get user's display preferences.
     */
    public function getDisplayPreferencesAttribute()
    {
        $defaults = [
            'language' => 'en',
            'timezone' => 'UTC',
            'currency' => 'USD',
            'date_format' => 'm/d/Y',
            'time_format' => '12h',
            'theme' => 'light',
        ];
        
        return array_merge($defaults, data_get($this->settings, 'display', []));
    }

    /**
     * Get active sessions.
     */
    public function activeSessions()
    {
        return DB::table('sessions')
            ->where('user_id', $this->id)
            ->where('last_activity', '>=', now()->subMinutes(config('auth.guards.web.expire', 120)))
            ->orderBy('last_activity', 'desc')
            ->get();
    }

    /**
     * Check if user has recently changed password.
     */
    public function passwordRecentlyChanged($days = 90)
    {
        if (!$this->password_changed_at) {
            return false;
        }
        
        return $this->password_changed_at->addDays($days)->isFuture();
    }

    /**
     * Record password change.
     */
    public function recordPasswordChange()
    {
        $this->update(['password_changed_at' => now()]);
    }

    /**
     * Record login activity.
     */
    public function recordLogin($ipAddress)
    {
        $this->update([
            'last_login_at' => now(),
            'last_login_ip' => $ipAddress,
        ]);
    }


    // Add to your User model methods:

/**
 * Get user's notification preferences with defaults
 */
public function getNotificationPreferencesAttribute()
{
    $defaults = [
        'shipment_created' => true,
        'shipment_status_updated' => true,
        'shipment_out_for_delivery' => true,
        'shipment_delivered' => true,
        'shipment_customs_hold' => true,
        'shipment_cancelled' => true,
        'invoice_created' => true,
        'invoice_paid' => true,
        'invoice_overdue' => true,
        'email_notifications' => true,
        'push_notifications' => true,
    ];
    
    $preferences = $this->attributes['notification_preferences'] ?? [];
    
    if (is_string($preferences)) {
        $preferences = json_decode($preferences, true) ?? [];
    }
    
    return array_merge($defaults, $preferences);
}

/**
 * Update notification preferences
 */
public function updateNotificationPreferences(array $preferences)
{
    $current = $this->notification_preferences;
    $updated = array_merge($current, $preferences);
    
    $this->notification_preferences = $updated;
    $this->save();
}

}