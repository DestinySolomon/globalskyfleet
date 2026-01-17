<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Notifications\Messages\BroadcastMessage;

class SystemNotification extends Notification implements ShouldQueue, ShouldBroadcast
{
    use Queueable;

    public $title;
    public $message;
    public $category;
    public $priority;

    /**
     * Create a new notification instance.
     */
    public function __construct($title, $message, $category = 'system', $priority = 'normal')
    {
        $this->title = $title;
        $this->message = $message;
        $this->category = $category;
        $this->priority = $priority;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        $channels = ['database'];
        
        // Add email if enabled
        if ($notifiable->notification_preferences['email_notifications'] ?? true) {
            $channels[] = 'mail';
        }
        
        // Add broadcast for real-time
        if ($notifiable->notification_preferences['push_notifications'] ?? true) {
            $channels[] = 'broadcast';
        }
        
        return $channels;
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject($this->title)
            ->line($this->message)
            ->action('View Dashboard', route('admin.dashboard'))
            ->line('Priority: ' . ucfirst($this->priority));
    }

    /**
     * Get the broadcastable representation of the notification.
     */
    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'id' => $this->id,
            'type' => get_class($this),
            'data' => $this->toArray($notifiable),
            'read_at' => null,
            'created_at' => now()->toDateTimeString(),
        ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $icon = match($this->category) {
            'system' => 'ri-server-line',
            'user' => 'ri-user-line',
            'security' => 'ri-shield-check-line',
            'maintenance' => 'ri-tools-line',
            'update' => 'ri-refresh-line',
            default => 'ri-notification-line',
        };

        return [
            'title' => $this->title,
            'message' => $this->message,
            'icon' => $icon,
            'url' => route('admin.dashboard'),
            'category' => $this->category,
            'priority' => $this->priority,
        ];
    }

    /**
     * Get the database representation of the notification.
     */
    public function toDatabase(object $notifiable): array
    {
        return $this->toArray($notifiable);
    }
}