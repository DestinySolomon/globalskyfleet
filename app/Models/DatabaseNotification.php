<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\DatabaseNotification as BaseDatabaseNotification;

class DatabaseNotification extends BaseDatabaseNotification
{
    use HasFactory;

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'data' => 'array',
        'read_at' => 'datetime',
    ];

    /**
     * Get the notification category name.
     */
    public function getCategoryNameAttribute()
    {
        return match($this->category) {
            'shipment' => 'Shipment',
            'payment' => 'Payment',
            'document' => 'Document',
            'system' => 'System',
            'user' => 'User',
            default => ucfirst($this->category),
        };
    }

    /**
     * Get the priority badge class.
     */
    public function getPriorityBadgeClassAttribute()
    {
        return match($this->priority) {
            'low' => 'badge bg-info',
            'normal' => 'badge bg-primary',
            'high' => 'badge bg-warning',
            'urgent' => 'badge bg-danger',
            default => 'badge bg-secondary',
        };
    }

    /**
     * Get the notification icon.
     */
    public function getIconAttribute()
    {
        $data = $this->data;
        return $data['icon'] ?? match($this->category) {
            'shipment' => 'ri-ship-line',
            'payment' => 'ri-currency-line',
            'document' => 'ri-file-text-line',
            'user' => 'ri-user-line',
            'system' => 'ri-notification-line',
            default => 'ri-notification-line',
        };
    }

    /**
     * Scope a query to only include unread notifications.
     */
    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    /**
     * Scope a query to only include read notifications.
     */
    public function scopeRead($query)
    {
        return $query->whereNotNull('read_at');
    }

    /**
     * Scope a query to only include notifications by category.
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope a query to only include notifications by priority.
     */
    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    /**
     * Check if notification is unread.
     */
    public function isUnread()
    {
        return is_null($this->read_at);
    }

    /**
     * Alias for isUnread() for compatibility with views
     */
    public function unread()
    {
        return $this->isUnread();
    }

    /**
     * Mark notification as read.
     */
    public function markAsRead()
    {
        if ($this->isUnread()) {
            $this->update(['read_at' => now()]);
        }
    }

    /**
     * Mark notification as unread.
     */
    public function markAsUnread()
    {
        if (!$this->isUnread()) {
            $this->update(['read_at' => null]);
        }
    }

    /**
     * Get the notification URL.
     */
    public function getUrlAttribute()
    {
        $data = $this->data;
        return $data['url'] ?? '#';
    }

    /**
     * Get the notification title.
     */
    public function getTitleAttribute()
    {
        $data = $this->data;
        return $data['title'] ?? 'Notification';
    }

    /**
     * Get the notification message.
     */
    public function getMessageAttribute()
    {
        $data = $this->data;
        return $data['message'] ?? '';
    }
}