<?php

namespace App\Events;

use App\Models\ChatConversation;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ChatConversationUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $conversation;

    public function __construct(ChatConversation $conversation)
    {
        $this->conversation = $conversation;
    }

    public function broadcastOn()
    {
        // Channel for admins to see new conversations
        return new PrivateChannel('admin.chat.updates');
    }

    public function broadcastAs()
    {
        return 'conversation.updated';
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->conversation->id,
            'user_name' => $this->conversation->user_name,
            'unread_count' => $this->conversation->unread_count,
            'status' => $this->conversation->status,
            'updated_at' => $this->conversation->updated_at->toDateTimeString(),
        ];
    }
}