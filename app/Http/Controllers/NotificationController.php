<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\DatabaseNotification;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Redirect admins/super admins to admin notifications (only for HTML requests)
        if ($user->isAdminOrSuperAdmin() && !$request->expectsJson()) {
            return redirect()->route('admin.notifications.index');
        }
        
        // Regular users see their notifications
        $perPage = $request->query('per_page', 20);
        $notifications = $user->notifications()->latest()->paginate($perPage);
        
        // Return JSON if requested
        if ($request->expectsJson()) {
            $formattedNotifications = $notifications->map(function ($notification) {
                $data = $notification->data;
                return [
                    'id' => $notification->id,
                    'title' => $data['title'] ?? 'Notification',
                    'message' => $data['message'] ?? '',
                    'icon' => $data['icon'] ?? 'ri-notification-line',
                    'color' => $this->getColorClass($data['priority'] ?? 'normal'),
                    'read' => $notification->read_at !== null,
                    'category' => $data['category'] ?? $notification->category,
                    'tracking_number' => $data['tracking_number'] ?? null,
                    'url' => $data['url'] ?? null,
                    'created_at' => $notification->created_at->toIso8601String(),
                    'data' => $data,
                ];
            })->toArray();
            
            return response()->json([
                'notifications' => [
                    'data' => $formattedNotifications,
                    'links' => [],
                    'pagination' => [
                        'current_page' => $notifications->currentPage(),
                        'per_page' => $notifications->perPage(),
                        'total' => $notifications->total(),
                        'last_page' => $notifications->lastPage(),
                    ]
                ]
            ]);
        }
        
        return view('notifications.index', compact('notifications'));
    }
    
    private function getColorClass($priority)
    {
        return match($priority) {
            'low' => 'bg-info bg-opacity-10 text-info',
            'normal' => 'bg-primary bg-opacity-10 text-primary',
            'high' => 'bg-warning bg-opacity-10 text-warning',
            'urgent' => 'bg-danger bg-opacity-10 text-danger',
            default => 'bg-secondary bg-opacity-10 text-secondary',
        };
    }
    
    public function markAsRead($id)
    {
        $user = Auth::user();
        $notification = $user->notifications()->where('id', $id)->first();
        
        if ($notification) {
            $notification->markAsRead();
            
            // Redirect to notification URL if available
            if (isset($notification->data['url']) && $notification->data['url'] !== '#') {
                $url = $notification->data['url'];
                
                // If user is admin, check if we should redirect to admin URL
                if ($user->isAdminOrSuperAdmin()) {
                    // Check if there's an admin-specific URL in the notification data
                    if (isset($notification->data['admin_url']) && !empty($notification->data['admin_url'])) {
                        $url = $notification->data['admin_url'];
                    } else if (strpos($url, '/shipments/') !== false) {
                        // Convert regular shipment URL to admin URL
                        $url = str_replace('/shipments/', '/admin/shipments/', $url);
                    } else if (strpos($url, '/users/') !== false) {
                        // Convert regular user URL to admin URL
                        $url = str_replace('/users/', '/admin/users/', $url);
                    }
                }
                
                return redirect($url);
            }
        }
        
        // If no URL or admin, redirect appropriately
        if ($user->isAdminOrSuperAdmin()) {
            return redirect()->route('admin.dashboard')->with('success', 'Notification marked as read');
        }
        
        return redirect()->back()->with('success', 'Notification marked as read');
    }
    
    public function markAllAsRead()
    {
        $user = Auth::user();
        $user->unreadNotifications->markAsRead();
        
        // Redirect admins back to admin notifications page
        if ($user->isAdminOrSuperAdmin()) {
            return redirect()->route('admin.notifications.index')->with('success', 'All notifications marked as read');
        }
        
        return redirect()->back()->with('success', 'All notifications marked as read');
    }
    
    public function unreadCount()
    {
        $count = Auth::user()->unreadNotifications()->count();
        
        return response()->json(['count' => $count]);
    }
    
    public function clearAll()
    {
        $user = Auth::user();
        $user->notifications()->delete();
        
        // Redirect admins back to admin notifications page
        if ($user->isAdminOrSuperAdmin()) {
            return redirect()->route('admin.notifications.index')->with('success', 'All notifications cleared');
        }
        
        return redirect()->back()->with('success', 'All notifications cleared');
    }
}