<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class BaseNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Get the notification channels based on user preferences
     */
    protected function getChannels($notifiable, array $defaultChannels = ['database']): array
    {
        $channels = $defaultChannels;
        
        // Get user preferences safely
        $preferences = $notifiable->notification_preferences ?? [];
        
        if (is_string($preferences)) {
            $preferences = json_decode($preferences, true) ?? [];
        }
        
        // Add email if enabled
        if (($preferences['email_notifications'] ?? true) && in_array('mail', $defaultChannels)) {
            $channels[] = 'mail';
        }
        
        // Remove broadcast for now (requires Pusher setup)
        if (($key = array_search('broadcast', $channels)) !== false) {
            unset($channels[$key]);
        }
        
        return array_values(array_unique($channels));
    }
}