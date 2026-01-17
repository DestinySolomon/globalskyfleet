<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatConversation extends Model
{
    protected $fillable = [
        'user_id', 'user_name', 'user_email', 'user_ip',
        'status', 'assigned_to', 'last_message_at', 'unread_count'
    ];

    protected $casts = [
        'last_message_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function messages()
    {
        return $this->hasMany(ChatMessage::class)->latest();
    }

    public function latestMessage()
    {
        return $this->hasOne(ChatMessage::class)->latest();
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeUnassigned($query)
    {
        return $query->whereNull('assigned_to');
    }

    public function markAsRead()
    {
        $this->messages()->where('is_read', false)->update([
            'is_read' => true,
            'read_at' => now()
        ]);
        $this->unread_count = 0;
        $this->save();
    }

    public function incrementUnread()
    {
        $this->increment('unread_count');
        $this->last_message_at = now();
        $this->save();
    }
}