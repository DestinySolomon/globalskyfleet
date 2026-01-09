<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\DatabaseNotification;

class NotificationController extends Controller
{
    /**
     * Get user notifications
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $perPage = $request->get('per_page', 15);
        
        $notifications = $user->notifications()
            ->when($request->category, function($query, $category) {
                return $query->where('category', $category);
            })
            ->when($request->read, function($query, $read) {
                if ($read === 'read') {
                    return $query->whereNotNull('read_at');
                } elseif ($read === 'unread') {
                    return $query->whereNull('read_at');
                }
                return $query;
            })
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
            
        // Transform notifications
        $notifications->getCollection()->transform(function ($notification) {
            return [
                'id' => $notification->id,
                'title' => $notification->data['title'] ?? 'Notification',
                'message' => $notification->data['message'] ?? '',
                'category' => $notification->category,
                'priority' => $notification->priority,
                'read' => $notification->read_at !== null,
                'time' => $notification->created_at->diffForHumans(),
                'url' => $notification->data['url'] ?? null,
                'icon' => $notification->data['icon'] ?? 'ri-notification-line',
                'color' => $notification->data['color'] ?? 'primary',
                'data' => $notification->data,
                'created_at' => $notification->created_at->toISOString(),
            ];
        });
        
        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $user->unreadNotifications()->count(),
        ]);
    }
    
    /**
     * Mark notification as read
     */
    public function markAsRead(Request $request, $id)
    {
        $user = Auth::user();
        $notification = $user->notifications()->where('id', $id)->first();
        
        if (!$notification) {
            return response()->json(['error' => 'Notification not found'], 404);
        }
        
        $notification->markAsRead();
        
        return response()->json([
            'success' => true,
            'unread_count' => $user->unreadNotifications()->count(),
        ]);
    }
    
    /**
     * Mark all notifications as read
     */
    public function markAllAsRead(Request $request)
    {
        $user = Auth::user();
        $user->unreadNotifications()->update(['read_at' => now()]);
        
        return response()->json([
            'success' => true,
            'unread_count' => 0,
        ]);
    }
    
    /**
     * Clear all notifications
     */
    public function clearAll(Request $request)
    {
        $user = Auth::user();
        $user->notifications()->delete();
        
        return response()->json([
            'success' => true,
            'unread_count' => 0,
        ]);
    }
    
    /**
     * Get unread notifications count
     */
    public function unreadCount()
    {
        $user = Auth::user();
        
        return response()->json([
            'count' => $user->unreadNotifications()->count(),
        ]);
    }
}