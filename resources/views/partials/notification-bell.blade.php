@php
    $user = auth()->user();
    if (!$user) {
        return;
    }
    
    $unreadCount = $user->unreadNotifications()->count();
    $notifications = $user->notifications()->latest()->take(10)->get();
    
    // Determine route prefix based on user role
    if ($user->isSuperAdmin() || $user->isAdmin()) {
        $routePrefix = 'admin';
    } else {
        $routePrefix = 'user';
    }
@endphp

<div class="dropdown">
    <button class="btn btn-light rounded-circle position-relative p-2" 
            type="button" data-bs-toggle="dropdown" 
            style="width: 44px; height: 44px;"
            id="notificationBell">
        <i class="ri-notification-3-line fs-5"></i>
        @if($unreadCount > 0)
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" 
                  style="font-size: 10px; padding: 2px 6px;"
                  id="notificationBadge">
                {{ $unreadCount }}
            </span>
        @endif
    </button>
    
    <ul class="dropdown-menu dropdown-menu-end p-0" style="width: 350px; max-height: 400px; overflow-y: auto;">
        <li class="p-3 border-bottom">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold">Notifications</h6>
                @if($unreadCount > 0)
                    <form action="{{ route($routePrefix . '.notifications.read.all') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-primary">
                            Mark all read
                        </button>
                    </form>
                @endif
            </div>
        </li>
        <li>
            <div class="notification-list">
                @forelse($notifications as $notification)
                    @php
                        // Safely access data
                        $data = is_array($notification->data) ? $notification->data : (array) $notification->data;
                    @endphp
                    @php
                        // Determine the correct route based on user role
                        if ($user->isSuperAdmin() || $user->isAdmin()) {
                            $notificationRoute = route('admin.notifications.show', $notification->id);
                        } else {
                            $notificationRoute = '#'; // Default to # for non-admin users
                        }
                    @endphp
                    <a href="{{ $notificationRoute }}" 
                       class="dropdown-item py-3 px-3 border-bottom {{ is_null($notification->read_at) ? 'bg-light' : '' }}">
                        <div class="d-flex align-items-start">
                            <div class="rounded-circle bg-primary bg-opacity-10 text-primary p-2 me-3">
                                <i class="{{ $data['icon'] ?? 'ri-notification-line' }}"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="fw-semibold mb-1">{{ $data['title'] ?? 'Notification' }}</div>
                                <small class="text-muted">{{ $data['message'] ?? '' }}</small>
                                <div class="text-end mt-1">
                                    <small class="text-muted">
                                        {{ $notification->created_at->diffForHumans() }}
                                    </small>
                                </div>
                            </div>
                            @if(is_null($notification->read_at))
                                <div class="ms-2">
                                    <span class="badge bg-primary rounded-pill" style="font-size: 8px;">New</span>
                                </div>
                            @endif
                        </div>
                    </a>
                @empty
                    <div class="dropdown-item text-center py-4">
                        <i class="ri-notification-off-line fs-1 text-muted mb-2"></i>
                        <p class="text-muted mb-0">No notifications</p>
                    </div>
                @endforelse
            </div>
        </li>
        <li class="border-top">
            <a class="dropdown-item text-center py-2" href="{{ route($routePrefix . '.notifications.index') }}">
                <i class="ri-eye-line me-2"></i>View all notifications
            </a>
        </li>
    </ul>
</div>