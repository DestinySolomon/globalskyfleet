<?php

namespace App\Notifications;

use App\Models\Document;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Notifications\Messages\BroadcastMessage;

class DocumentNotification extends Notification implements ShouldQueue, ShouldBroadcast
{
    use Queueable;

    public $document;
    public $title;
    public $message;
    public $priority;

    /**
     * Create a new notification instance.
     */
    public function __construct(Document $document, $title, $message, $priority = 'normal')
    {
        $this->document = $document;
        $this->title = $title;
        $this->message = $message;
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
            ->action('View Document', route('admin.documents.show', $this->document->id))
            ->line('Document Type: ' . ucfirst(str_replace('_', ' ', $this->document->type)))
            ->line('Status: ' . ucfirst($this->document->status));
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
        return [
            'title' => $this->title,
            'message' => $this->message,
            'icon' => 'ri-file-text-line',
            'url' => route('admin.documents.show', $this->document->id),
            'category' => 'document',
            'priority' => $this->priority,
            'document_id' => (string) $this->document->id,
            'document_type' => $this->document->type,
            'status' => $this->document->status,
            'user_name' => $this->document->user->name ?? 'Unknown',
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