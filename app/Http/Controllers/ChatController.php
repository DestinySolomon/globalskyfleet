<?php

namespace App\Http\Controllers;

use App\Models\ChatConversation;
use App\Models\ChatMessage;
use App\Events\ChatMessageSent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ChatController extends Controller
{
    // Get or create conversation
    public function getConversation(Request $request)
    {
        try {
            $user = Auth::user();
            $userName = $request->input('name', 'Guest');
            $userEmail = $request->input('email');
            $userIp = $request->ip();
            
            // Generate unique identifier for guest users
            $guestId = $user ? null : 'guest_' . Str::random(10);
            
            // Check for existing active conversation
            $conversation = ChatConversation::where(function($query) use ($user, $guestId, $userIp) {
                if ($user) {
                    $query->where('user_id', $user->id);
                } else {
                    $query->where('user_ip', $userIp)
                          ->orWhere('user_email', $guestId);
                }
            })->where('status', 'active')
              ->latest()
              ->first();
            
            // Create new conversation if none exists
            if (!$conversation) {
                $conversation = ChatConversation::create([
                    'user_id' => $user ? $user->id : null,
                    'user_name' => $user ? $user->name : $userName,
                    'user_email' => $user ? $user->email : ($userEmail ?: $guestId),
                    'user_ip' => $userIp,
                    'status' => 'active',
                    'unread_count' => 0,
                    'last_message_at' => now(),
                ]);
            }
            
            // Get messages
            $messages = $conversation->messages()
                ->orderBy('created_at', 'asc')
                ->get()
                ->map(function($message) {
                    return [
                        'id' => $message->id,
                        'message' => $message->message,
                        'sender_type' => $message->sender_type,
                        'sender_name' => $message->sender ? $message->sender->name : 'Guest',
                        'is_admin' => $message->sender_type === 'admin',
                        'created_at' => $message->created_at->toDateTimeString(),
                    ];
                });
            
            return response()->json([
                'success' => true,
                'conversation' => [
                    'id' => $conversation->id,
                    'user_name' => $conversation->user_name,
                    'status' => $conversation->status,
                ],
                'messages' => $messages,
                'is_authenticated' => !!$user,
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Chat conversation error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to initialize chat'
            ], 500);
        }
    }
    
    // Send message
    public function sendMessage(Request $request)
    {
        $request->validate([
            'conversation_id' => 'required|exists:chat_conversations,id',
            'message' => 'required|string|max:1000',
        ]);
        
        try {
            $user = Auth::user();
            $conversation = ChatConversation::findOrFail($request->conversation_id);
            
            // Create message
            $message = ChatMessage::create([
                'conversation_id' => $conversation->id,
                'sender_id' => $user ? $user->id : null,
                'sender_type' => $user && $user->isAdminOrSuperAdmin() ? 'admin' : 'user',
                'message' => $request->message,
            ]);
            
            // Update conversation
            $conversation->increment('unread_count');
            $conversation->last_message_at = now();
            $conversation->save();
            
            // Broadcast message
            broadcast(new ChatMessageSent($message));
            
            return response()->json([
                'success' => true,
                'message' => [
                    'id' => $message->id,
                    'message' => $message->message,
                    'sender_type' => $message->sender_type,
                    'sender_name' => $user ? $user->name : 'Guest',
                    'is_admin' => $message->sender_type === 'admin',
                    'created_at' => $message->created_at->toDateTimeString(),
                ]
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Chat send message error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to send message'
            ], 500);
        }
    }
    
    // Get conversations for admin
    public function getAdminConversations(Request $request)
    {
        if (!Auth::check() || !Auth::user()->isAdminOrSuperAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        $conversations = ChatConversation::with(['latestMessage'])
            ->orderBy('last_message_at', 'desc')
            ->paginate(20);
        
        return response()->json([
            'conversations' => $conversations->map(function($conversation) {
                return [
                    'id' => $conversation->id,
                    'user_name' => $conversation->user_name,
                    'status' => $conversation->status,
                    'unread_count' => $conversation->unread_count,
                    'last_message' => $conversation->latestMessage ? [
                        'message' => Str::limit($conversation->latestMessage->message, 50),
                        'created_at' => $conversation->latestMessage->created_at->diffForHumans(),
                    ] : null,
                    'created_at' => $conversation->created_at->format('M d, Y H:i'),
                ];
            }),
            'total_unread' => ChatConversation::sum('unread_count'),
        ]);
    }
}