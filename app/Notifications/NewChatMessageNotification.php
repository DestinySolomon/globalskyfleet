<?php

namespace App\Notifications;

use App\Models\ChatMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Illuminate\Notifications\Notification;

class NewChatMessageNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $message;
    protected $conversationId;
    protected $userName;

    /**
     * Create a new notification instance.
     */
    public function __construct(ChatMessage $message, $conversationId, $userName)
    {
        $this->message = $message;
        $this->conversationId = $conversationId;
        $this->userName = $userName;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     */
    public function toDatabase(object $notifiable): DatabaseMessage
    {
        return new DatabaseMessage([
            'type' => 'chat_message',
            'title' => 'New Chat Message',
            'message' => "New message from {$this->userName}: " . substr($this->message->message, 0, 50),
            'body' => $this->message->message,
            'sender_name' => $this->userName,
            'conversation_id' => $this->conversationId,
            'icon' => 'ri-chat-3-line',
            'action_url' => route('admin.chat.index'),
            'read' => false,
        ]);
    }
}
