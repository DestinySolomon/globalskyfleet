<?php

namespace App\Notifications;

use App\Models\Document;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DocumentNotification extends BaseNotification // Changed from implements ShouldQueue, ShouldBroadcast
{
    use Queueable;

    public $document;
    public $title;
    public $message;
    public $priority;

    public function __construct(Document $document, $title, $message, $priority = 'normal')
    {
        $this->document = $document;
        $this->title = $title;
        $this->message = $message;
        $this->priority = $priority;
    }

    public function via(object $notifiable): array
    {
        return $this->getChannels($notifiable, ['database', 'mail']);
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject($this->title)
            ->line($this->message)
            ->action('View Document', route('admin.documents.show', $this->document->id))
            ->line('Document Type: ' . ucfirst(str_replace('_', ' ', $this->document->type)))
            ->line('Status: ' . ucfirst($this->document->status));
    }

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
}