<?php

namespace App\Http\Controllers;

use App\Models\ChatConversation;
use App\Models\ChatMessage;
use App\Models\User;
use App\Notifications\NewChatMessageNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class ChatController extends Controller
{
    /**
     * Get or create a conversation for the current user
     */
    public function getOrCreateConversation(Request $request)
    {
        try {
            $user = Auth::user();
            
            // Log for debugging
            \Log::info('Chat: User requesting conversation', [
                'user_id' => $user->id,
                'user_name' => $user->name,
                'ip' => $request->ip()
            ]);
            
            // Check if we should force a new conversation
            $forceNew = $request->input('force_new', false) || 
                        $request->header('X-Force-New') === 'true';
            
            if ($forceNew) {
                \Log::info('Chat: Forcing new conversation creation');
                
                // Always create new conversation when forced
                $conversation = ChatConversation::create([
                    'user_id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'status' => 'active',
                    'last_message_at' => now(),
                ]);
                
                \Log::info('Chat: Created new conversation', [
                    'conversation_id' => $conversation->id,
                    'user_id' => $conversation->user_id
                ]);
            } else {
                // Find RECENT active conversation for this user (last 24 hours)
              $conversation = ChatConversation::where('user_id', (int)$user->id)
                    ->where('status', 'active')
                    ->where('created_at', '>', now()->subHours(24))
                    ->orderBy('created_at', 'desc')
                    ->first();
                
                if ($conversation) {
                    \Log::info('Chat: Found existing conversation', [
                        'conversation_id' => $conversation->id,
                        'user_id' => $conversation->user_id,
                        'created_at' => $conversation->created_at
                    ]);
                } else {
                    // Create new conversation
                    $conversation = ChatConversation::create([
                        'user_id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'status' => 'active',
                        'last_message_at' => now(),
                    ]);
                    
                    \Log::info('Chat: Created new conversation (no recent found)', [
                        'conversation_id' => $conversation->id,
                        'user_id' => $conversation->user_id
                    ]);
                }
            }
            
            // Get all messages for this conversation
            $messages = ChatMessage::where('conversation_id', $conversation->id)
                ->orderBy('created_at', 'asc')
                ->get()
                ->map(function ($message) {
                    return [
                        'id' => $message->id,
                        'message' => $message->message,
                        'is_admin' => $message->is_admin,
                        'sender_name' => $message->is_admin ? 'Support' : $message->sender_name,
                        'created_at' => $message->created_at->toISOString(),
                    ];
                });
            
            return response()->json([
                'success' => true,
                'conversation' => [
                    'id' => $conversation->id,
                    'status' => $conversation->status,
                    'user_id' => $conversation->user_id, // Include for debugging
                ],
                'messages' => $messages,
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Chat conversation error: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to load conversation: ' . $e->getMessage(),
            ], 500);
        }
    }
    
    /**
     * Send a message
     */
   public function sendMessage(Request $request)
{
    try {
        $request->validate([
            'conversation_id' => 'required|exists:chat_conversations,id',
            'message' => 'required|string|max:1000',
        ]);
        
        $user = Auth::user();
        $conversationId = $request->conversation_id;
        
        \Log::info('Chat: User sending message', [
            'user_id' => $user->id,
            'user_id_type' => gettype($user->id),
            'conversation_id' => $conversationId,
            'message_preview' => substr($request->message, 0, 50)
        ]);
        
        // Get the conversation
        $conversation = ChatConversation::find($conversationId);
        
        if (!$conversation) {
            \Log::warning('Chat: Conversation not found', ['conversation_id' => $conversationId]);
            return response()->json([
                'success' => false,
                'message' => 'Conversation not found',
            ], 404);
        }
        
        \Log::info('Chat: Conversation found', [
            'conversation_user_id' => $conversation->user_id,
            'conversation_user_id_type' => gettype($conversation->user_id),
            'current_user_id' => $user->id,
            'current_user_id_type' => gettype($user->id),
            'comparison' => ($conversation->user_id == $user->id) ? 'equal with ==' : 'not equal with ==',
            'strict_comparison' => ($conversation->user_id === $user->id) ? 'equal with ===' : 'not equal with ==='
        ]);
        
        // Verify the conversation belongs to the user (FIXED COMPARISON)
        if ((int)$conversation->user_id !== (int)$user->id) {
            \Log::warning('Chat: Type mismatch in user_id comparison', [
                'conversation_user_id' => $conversation->user_id . ' (' . gettype($conversation->user_id) . ')',
                'current_user_id' => $user->id . ' (' . gettype($user->id) . ')',
                'conversation_id' => $conversationId
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized: This conversation does not belong to you.',
                'debug' => [
                    'conversation_user_id' => $conversation->user_id,
                    'conversation_user_id_type' => gettype($conversation->user_id),
                    'current_user_id' => $user->id,
                    'current_user_id_type' => gettype($user->id),
                ]
            ], 403);
        }
        
        // Create the message
        $chatMessage = ChatMessage::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $user->id,
            'sender_type' => 'user',
            'message' => $request->message,
            'is_admin' => false,
            'sender_name' => $user->name,
            'is_read' => false,
        ]);
        
        // Update conversation's last message time
        $conversation->update([
            'last_message_at' => now(),
            'status' => 'active',
        ]);
        
        \Log::info('Chat: Message sent successfully', [
            'message_id' => $chatMessage->id,
            'conversation_id' => $conversation->id
        ]);
        
        // Notify all admins about the new message
        try {
            $admins = User::where('role', 'admin')->get();
            if ($admins->count() > 0) {
                Notification::send($admins, new NewChatMessageNotification(
                    $chatMessage, 
                    $conversation->id, 
                    $user->name
                ));
                \Log::info('Chat: Notifications sent to admins', ['admin_count' => $admins->count()]);
            }
        } catch (\Exception $notificationError) {
            \Log::error('Notification error: ' . $notificationError->getMessage());
            // Don't fail the message sending if notification fails
        }
        
        return response()->json([
            'success' => true,
            'message' => [
                'id' => $chatMessage->id,
                'message' => $chatMessage->message,
                'is_admin' => false,
                'sender_name' => $chatMessage->sender_name,
                'created_at' => $chatMessage->created_at->toISOString(),
            ],
        ]);
        
    } catch (\Illuminate\Validation\ValidationException $e) {
        \Log::warning('Chat: Validation failed', ['errors' => $e->errors()]);
        return response()->json([
            'success' => false,
            'message' => 'Invalid input',
            'errors' => $e->errors(),
        ], 422);
        
    } catch (\Exception $e) {
        \Log::error('Chat send message error: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
        
        return response()->json([
            'success' => false,
            'message' => 'Failed to send message: ' . $e->getMessage(),
        ], 500);
    }
}
    
    /**
     * Get messages for a conversation
     */
    public function getMessages($conversationId)
    {
        try {
            $user = Auth::user();
            
            \Log::info('Chat: Getting messages', [
                'user_id' => $user->id,
                'conversation_id' => $conversationId
            ]);
            
            // Verify the conversation belongs to the user
            $conversation = ChatConversation::where('id', $conversationId)
                ->where('user_id', $user->id)
                ->first();
            
            if (!$conversation) {
                \Log::warning('Chat: Conversation not found or unauthorized', [
                    'conversation_id' => $conversationId,
                    'user_id' => $user->id
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Conversation not found or unauthorized',
                ], 404);
            }
            
            $messages = ChatMessage::where('conversation_id', $conversation->id)
                ->orderBy('created_at', 'asc')
                ->get()
                ->map(function ($message) {
                    return [
                        'id' => $message->id,
                        'message' => $message->message,
                        'is_admin' => $message->is_admin,
                        'sender_name' => $message->is_admin ? 'Support' : $message->sender_name,
                        'created_at' => $message->created_at->toISOString(),
                    ];
                });
            
            return response()->json([
                'success' => true,
                'messages' => $messages,
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Chat get messages error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to load messages',
            ], 500);
        }
    }
    
    /**
     * Debug endpoint to check conversation ownership
     */
    public function debugConversation(Request $request)
    {
        try {
            $user = Auth::user();
            $conversationId = $request->conversation_id;
            
            $conversation = ChatConversation::find($conversationId);
            
            $userConversations = ChatConversation::where('user_id', $user->id)->get();
            
            return response()->json([
                'success' => true,
                'debug' => [
                    'current_user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email
                    ],
                    'requested_conversation' => $conversation ? [
                        'id' => $conversation->id,
                        'user_id' => $conversation->user_id,
                        'name' => $conversation->name,
                        'email' => $conversation->email,
                        'created_at' => $conversation->created_at->toISOString(),
                        'last_message_at' => $conversation->last_message_at
                    ] : null,
                    'user_conversations' => $userConversations->map(function ($conv) {
                        return [
                            'id' => $conv->id,
                            'user_id' => $conv->user_id,
                            'status' => $conv->status,
                            'created_at' => $conv->created_at->toISOString()
                        ];
                    }),
                    'belongs_to_user' => $conversation && $conversation->user_id === $user->id
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Debug failed: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Admin: View all conversations
     */
    public function adminIndex()
    {
        $conversations = ChatConversation::with(['user', 'messages' => function ($query) {
            $query->latest()->limit(1);
        }])
            ->orderBy('last_message_at', 'desc')
            ->paginate(20);
        
        return view('admin.chat.index', compact('conversations'));
    }
    
    /**
     * Admin: View specific conversation
     */
    public function adminViewConversation($conversationId)
    {
        $conversation = ChatConversation::with(['user', 'messages'])
            ->findOrFail($conversationId);
        
        $messages = $conversation->messages()
            ->orderBy('created_at', 'asc')
            ->get();
        
        return view('admin.chat.conversation', compact('conversation', 'messages'));
    }
    
    /**
     * Admin: Get conversation as JSON (for AJAX)
     */
    public function adminGetConversation($conversationId)
    {
        try {
            $conversation = ChatConversation::with(['user'])
                ->findOrFail($conversationId);
            
            $messages = ChatMessage::where('conversation_id', $conversationId)
                ->orderBy('created_at', 'asc')
                ->get()
                ->map(function ($message) {
                    return [
                        'id' => $message->id,
                        'message' => $message->message,
                        'is_admin' => $message->is_admin,
                        'sender_name' => $message->is_admin ? 'Admin' : ($message->sender_name ?? 'User'),
                        'created_at' => $message->created_at->toISOString(),
                    ];
                });
            
            return response()->json([
                'success' => true,
                'conversation' => [
                    'id' => $conversation->id,
                    'user_id' => $conversation->user_id,
                    'user_name' => $conversation->user ? $conversation->user->name : ($conversation->name ?? 'Guest'),
                    'user_email' => $conversation->user ? $conversation->user->email : $conversation->email,
                    'status' => $conversation->status,
                ],
                'messages' => $messages,
            ]);
        } catch (\Exception $e) {
            \Log::error('Admin get conversation error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to load conversation',
            ], 500);
        }
    }
    
    /**
     * Admin: Reply to a conversation
     */
    public function adminReply(Request $request)
    {
        try {
            $request->validate([
                'conversation_id' => 'required|exists:chat_conversations,id',
                'message' => 'required|string|max:1000',
            ]);
            
            $conversation = ChatConversation::findOrFail($request->conversation_id);
            
            $chatMessage = ChatMessage::create([
                'conversation_id' => $conversation->id,
                'sender_id' => Auth::id(),
                'sender_type' => 'admin',
                'message' => $request->message,
                'is_admin' => true,
                'sender_name' => Auth::user()->name,
                'is_read' => false,
            ]);
            
            $conversation->update([
                'last_message_at' => now(),
            ]);
            
            return response()->json([
                'success' => true,
                'message' => [
                    'id' => $chatMessage->id,
                    'message' => $chatMessage->message,
                    'is_admin' => true,
                    'sender_name' => $chatMessage->sender_name,
                    'created_at' => $chatMessage->created_at->toISOString(),
                ],
            ]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Admin reply error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to send reply: ' . $e->getMessage(),
            ], 500);
        }
    }
    
    /**
     * Admin: Get updates for all conversations (for polling)
     */
    public function adminGetUpdates()
    {
        $conversations = ChatConversation::with(['user'])
            ->where('last_message_at', '>', now()->subMinutes(5))
            ->orderBy('last_message_at', 'desc')
            ->get();
        
        return response()->json([
            'success' => true,
            'conversations' => $conversations,
        ]);
    }
    
    /**
     * Force create a new conversation (for debugging/fixing issues)
     */
    public function forceNewConversation(Request $request)
    {
        try {
            $user = Auth::user();
            
            \Log::info('Chat: Forcing new conversation', ['user_id' => $user->id]);
            
            // Create new conversation
            $conversation = ChatConversation::create([
                'user_id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'status' => 'active',
                'last_message_at' => now(),
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'New conversation created',
                'conversation' => [
                    'id' => $conversation->id,
                    'user_id' => $conversation->user_id,
                    'status' => $conversation->status,
                ],
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Force new conversation error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to create new conversation',
            ], 500);
        }
    }
}