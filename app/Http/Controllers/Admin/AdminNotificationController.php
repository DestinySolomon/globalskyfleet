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
        
        $query = $user->notifications()->with('notifiable');
        
        // Filter by read status
        if ($request->has('status')) {
            if ($request->status === 'read') {
                $query->whereNotNull('read_at');
            } elseif ($request->status === 'unread') {
                $query->whereNull('read_at');
            }
        }
        
        // Filter by category
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        
        // Filter by priority
        if ($request->filled('priority')) {
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
        $count = Auth::user()->unreadNotifications()->count();
        
        return response()->json([
            'unread_count' => $count,
        ]);
    }

    /**
     * Mark a notification as read.
     */
    public function markAsRead(Request $request, $id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->markAsRead();
        
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'unread_count' => Auth::user()->unreadNotifications()->count(),
            ]);
        }
        
        return redirect()->back()->with('success', 'Notification marked as read.');
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllAsRead(Request $request)
    {
        Auth::user()->unreadNotifications()->update(['read_at' => now()]);
        
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
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->delete();
        
        return redirect()->back()->with('success', 'Notification deleted successfully.');
    }

    /**
     * Clear all notifications.
     */
    public function clearAll(Request $request)
    {
        $status = $request->get('status', 'all');
        
        if ($status === 'read') {
            Auth::user()->notifications()->whereNotNull('read_at')->delete();
            $message = 'All read notifications cleared.';
        } elseif ($status === 'unread') {
            Auth::user()->notifications()->whereNull('read_at')->delete();
            $message = 'All unread notifications cleared.';
        } else {
            Auth::user()->notifications()->delete();
            $message = 'All notifications cleared.';
        }
        
        return redirect()->back()->with('success', $message);
    }

    /**
     * Mark notifications as read and redirect.
     */
    public function readAndRedirect($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        
        // Mark as read
        $notification->markAsRead();
        
        // Get redirect URL from notification data
        $data = $notification->data;
        $url = $data['url'] ?? route('admin.dashboard');
        
        return redirect($url);
    }

    /**
     * Get recent notifications for dropdown (AJAX).
     */
    public function getRecentNotifications()
    {
        $notifications = Auth::user()
            ->notifications()
            ->latest()
            ->limit(5)
            ->get()
            ->map(function ($notification) {
                $data = $notification->data;
                return [
                    'id' => $notification->id,
                    'title' => $data['title'] ?? 'Notification',
                    'message' => $data['message'] ?? '',
                    'icon' => $data['icon'] ?? 'ri-notification-line',
                    'time' => $notification->created_at->diffForHumans(),
                    'unread' => $notification->unread(),
                    'url' => $data['url'] ?? '#',
                ];
            });
        
        return response()->json([
            'notifications' => $notifications,
            'unread_count' => Auth::user()->unreadNotifications()->count(),
        ]);
    }
}