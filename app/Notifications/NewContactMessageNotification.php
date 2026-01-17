<?php

namespace App\Notifications;

use App\Models\ContactMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;


class NewContactMessageNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $contactMessage;

    public function __construct(ContactMessage $contactMessage)
    {
        $this->contactMessage = $contactMessage;
    }

    public function via($notifiable): array
    {
        return ['database']; // Only in-app notification
    }

    public function toDatabase($notifiable): array
    {
        return [
            'contact_message_id' => $this->contactMessage->id,
            'name' => $this->contactMessage->name,
            'email' => $this->contactMessage->email,
            'subject' => $this->contactMessage->subject_display,
            'message' => Str::limit($this->contactMessage->message, 100),
            'url' => '/admin/contact-messages/' . $this->contactMessage->id,
            'icon' => 'ri-mail-line',
            'type' => 'contact_message',
            'priority' => 'high',
            'category' => 'contact'
        ];
    }

    public function toArray($notifiable): array
    {
        return [
            'contact_message_id' => $this->contactMessage->id,
            'name' => $this->contactMessage->name,
            'subject' => $this->contactMessage->subject_display,
        ];
    }
}