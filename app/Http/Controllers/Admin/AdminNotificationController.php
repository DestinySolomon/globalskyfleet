<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminNotificationController extends Controller
{
    /**
     * Display a listing of notifications.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Check if user is admin/super admin
        if (!$user->isAdminOrSuperAdmin()) {
            return redirect()->route('dashboard')->with('error', 'Access denied.');
        }
        
        $query = $user->notifications();
        
        // Filter by read status
        if ($request->has('status') && $request->status !== 'all') {
            if ($request->status === 'read') {
                $query->whereNotNull('read_at');
            } elseif ($request->status === 'unread') {
                $query->whereNull('read_at');
            }
        }
        
        // Filter by category
        if ($request->filled('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }
        
        // Filter by priority
        if ($request->filled('priority') && $request->priority !== 'all') {
            $query->where('priority', $request->priority);
        }
        
        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('data', 'like', '%' . $search . '%')
                  ->orWhere('type', 'like', '%' . $search . '%');
            });
        }
        
        $notifications = $query->latest()->paginate(20);
        
        // Get counts for filters
        $totalNotifications = $user->notifications()->count();
        $unreadCount = $user->unreadNotifications()->count();
        
        // Get categories and priorities for filter dropdowns
        $categories = [
            'all' => 'All Categories',
            'shipment' => 'Shipment',
            'payment' => 'Payment',
            'document' => 'Document',
            'user' => 'User',
            'system' => 'System',
            'admin' => 'Admin',
        ];
        
        $priorities = [
            'all' => 'All Priorities',
            'low' => 'Low',
            'normal' => 'Normal',
            'high' => 'High',
            'urgent' => 'Urgent',
        ];
        
        $statuses = [
            'all' => 'All Notifications',
            'unread' => 'Unread Only',
            'read' => 'Read Only',
        ];
        
        return view('admin.notifications.index', compact(
            'notifications',
            'totalNotifications',
            'unreadCount',
            'categories',
            'priorities',
            'statuses'
        ));
    }

    /**
     * Get unread notification count (for badge).
     */
    public function unreadCount()
    {
        $user = Auth::user();
        
        // Return 0 if not admin/super admin
        if (!$user->isAdminOrSuperAdmin()) {
            return response()->json([
                'unread_count' => 0,
            ]);
        }
        
        $count = $user->unreadNotifications()->count();
        
        return response()->json([
            'unread_count' => $count,
        ]);
    }

    /**
     * Mark a notification as read.
     */
    public function markAsRead(Request $request, $id)
    {
        $user = Auth::user();
        
        if (!$user->isAdminOrSuperAdmin()) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Access denied'], 403);
            }
            return redirect()->route('dashboard')->with('error', 'Access denied.');
        }
        
        $notification = $user->notifications()->findOrFail($id);
        $notification->markAsRead();
        
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'unread_count' => $user->unreadNotifications()->count(),
            ]);
        }
        
        return redirect()->back()->with('success', 'Notification marked as read.');
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllAsRead(Request $request)
    {
        $user = Auth::user();
        
        if (!$user->isAdminOrSuperAdmin()) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Access denied'], 403);
            }
            return redirect()->route('dashboard')->with('error', 'Access denied.');
        }
        
        $user->unreadNotifications()->update(['read_at' => now()]);
        
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'unread_count' => 0,
            ]);
        }
        
        return redirect()->back()->with('success', 'All notifications marked as read.');
    }

    /**
     * Delete a notification.
     */
    public function destroy($id)
    {
        $user = Auth::user();
        
        if (!$user->isAdminOrSuperAdmin()) {
            return redirect()->route('dashboard')->with('error', 'Access denied.');
        }
        
        $notification = $user->notifications()->findOrFail($id);
        $notification->delete();
        
        return redirect()->back()->with('success', 'Notification deleted successfully.');
    }

    /**
     * Clear all notifications.
     */
    public function clearAll(Request $request)
    {
        $user = Auth::user();
        
        if (!$user->isAdminOrSuperAdmin()) {
            return redirect()->route('dashboard')->with('error', 'Access denied.');
        }
        
        $status = $request->get('status', 'all');
        
        if ($status === 'read') {
            $user->notifications()->whereNotNull('read_at')->delete();
            $message = 'All read notifications cleared.';
        } elseif ($status === 'unread') {
            $user->notifications()->whereNull('read_at')->delete();
            $message = 'All unread notifications cleared.';
        } else {
            $user->notifications()->delete();
            $message = 'All notifications cleared.';
        }
        
        return redirect()->back()->with('success', $message);
    }

    /**
     * Mark notifications as read and redirect.
     */
    public function readAndRedirect($id)
    {
        $user = Auth::user();
        
        if (!$user->isAdminOrSuperAdmin()) {
            return redirect()->route('dashboard')->with('error', 'Access denied.');
        }
        
        $notification = $user->notifications()->findOrFail($id);
        
        // Mark as read
        $notification->markAsRead();
        
        // Get redirect URL from notification data
        $data = $notification->data;
        
        // Check if the URL should be admin-specific
        if (isset($data['url']) && $data['url'] !== '#') {
            $url = $data['url'];
            
            // Ensure admin stays in admin area
            if (strpos($url, '/admin/') === false && $user->isAdminOrSuperAdmin()) {
                // If it's a regular URL, check if we should redirect to admin equivalent
                if (isset($data['admin_url']) && !empty($data['admin_url'])) {
                    $url = $data['admin_url'];
                } else {
                    // Try to convert to admin URL if possible
                    if (strpos($url, '/shipments/') !== false) {
                        $url = str_replace('/shipments/', '/admin/shipments/', $url);
                    } elseif (strpos($url, '/users/') !== false) {
                        $url = str_replace('/users/', '/admin/users/', $url);
                    } elseif (strpos($url, '/documents/') !== false) {
                        $url = str_replace('/documents/', '/admin/documents/', $url);
                    }
                }
            }
        } else {
            $url = route('admin.dashboard');
        }
        
        return redirect($url);
    }

    /**
     * Get recent notifications for dropdown (AJAX).
     */
    public function getRecentNotifications()
    {
        $user = Auth::user();
        
        // Return empty if not admin/super admin
        if (!$user->isAdminOrSuperAdmin()) {
            return response()->json([
                'notifications' => [],
                'unread_count' => 0,
            ]);
        }
        
        $notifications = $user->notifications()
            ->latest()
            ->limit(10)
            ->get()
            ->map(function ($notification) use ($user) {
                $data = $notification->data;
                
                // Process URL for admin
                $url = $data['url'] ?? '#';
                if ($url !== '#' && strpos($url, '/admin/') === false && $user->isAdminOrSuperAdmin()) {
                    if (isset($data['admin_url']) && !empty($data['admin_url'])) {
                        $url = $data['admin_url'];
                    } else {
                        // Try to convert to admin URL
                        if (strpos($url, '/shipments/') !== false) {
                            $url = str_replace('/shipments/', '/admin/shipments/', $url);
                        } elseif (strpos($url, '/users/') !== false) {
                            $url = str_replace('/users/', '/admin/users/', $url);
                        }
                    }
                }
                
                return [
                    'id' => $notification->id,
                    'title' => $data['title'] ?? 'Notification',
                    'message' => $data['message'] ?? '',
                    'icon' => $data['icon'] ?? 'ri-notification-line',
                    'time' => $notification->created_at->diffForHumans(),
                    'unread' => $notification->unread(),
                    'url' => $url,
                    'priority' => $notification->priority ?? 'normal',
                ];
            });
        
        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $user->unreadNotifications()->count(),
        ]);
    }

    /**
     * Show a specific notification.
     */
    public function show($id)
    {
        $user = Auth::user();
        
        if (!$user->isAdminOrSuperAdmin()) {
            return redirect()->route('dashboard')->with('error', 'Access denied.');
        }
        
        $notification = $user->notifications()->findOrFail($id);
        
        // Mark as read when viewing
        if ($notification->unread()) {
            $notification->markAsRead();
        }
        
        return view('admin.notifications.show', compact('notification'));
    }

    /**
     * Get notifications for dropdown (legacy method).
     */
    public function getNotifications(Request $request)
    {
        $user = Auth::user();
        
        if (!$user->isAdminOrSuperAdmin()) {
            return response()->json(['notifications' => [], 'unread_count' => 0]);
        }
        
        $notifications = $user->notifications()
            ->latest()
            ->limit(10)
            ->get();
        
        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $user->unreadNotifications()->count(),
        ]);
    }
}