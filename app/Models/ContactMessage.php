<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class ContactMessage extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'subject',
        'message',
        'status',
        'ip_address',
        'user_agent',
        'admin_notes',
        'replied_at'
    ];

    protected $casts = [
        'replied_at' => 'datetime',
    ];

    // Subject display mapping
    public function getSubjectDisplayAttribute()
    {
        $subjects = [
            'general' => 'General Inquiry',
            'quote' => 'Request a Quote',
            'tracking' => 'Tracking Issue',
            'partnership' => 'Partnership Opportunity',
            'complaint' => 'Complaint',
            'other' => 'Other'
        ];
        
        return $subjects[$this->subject] ?? ucfirst($this->subject);
    }

    // Status badge class
    public function getStatusBadgeClassAttribute()
    {
        return match($this->status) {
            'unread' => 'badge bg-danger',
            'read' => 'badge bg-warning',
            'replied' => 'badge bg-success',
            'archived' => 'badge bg-secondary',
            default => 'badge bg-light text-dark'
        };
    }

// Automatically sanitize before saving
protected static function boot()
{
    parent::boot();
    
    static::creating(function ($model) {
        $model->sanitizeAttributes();
    });
    
    static::updating(function ($model) {
        $model->sanitizeAttributes();
    });
}

protected function sanitizeAttributes()
{
    // Sanitize all string attributes
    $attributes = ['name', 'email', 'phone', 'subject', 'message', 'admin_notes'];
    
    foreach ($attributes as $attribute) {
        if (isset($this->$attribute) && is_string($this->$attribute)) {
            $this->$attribute = htmlspecialchars($this->$attribute, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        }
    }
}

// Accessor to safely retrieve message (prevents XSS on display)
public function getSafeMessageAttribute()
{
    return nl2br(e($this->message));
}

public function getSafeNameAttribute()
{
    return e($this->name);
}

public function getSafeEmailAttribute()
{
    return e($this->email);
}

public function getSafePhoneAttribute()
{
    return e($this->phone);
}
}