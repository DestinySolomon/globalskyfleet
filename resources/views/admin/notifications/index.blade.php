@extends('layouts.admin')

@section('page-title', 'Notifications')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-0">Notifications</h4>
                    <p class="text-muted mb-0">Manage your notifications and preferences</p>
                </div>
                <div>
                    @if($unreadCount > 0)
                        <form action="{{ route('admin.notifications.mark-all-read') }}" method="POST" class="d-inline me-2">
                            @csrf
                            <button type="submit" class="btn btn-success">
                                <i class="ri-check-double-line me-2"></i> Mark All as Read
                            </button>
                        </form>
                    @endif
                    
                    <div class="dropdown d-inline">
                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="ri-filter-line me-2"></i> Filter
                        </button>
                        <div class="dropdown-menu dropdown-menu-end p-3" style="min-width: 300px;">
                            <form method="GET" action="{{ route('admin.notifications.index') }}">
                                <div class="mb-3">
                                    <label class="form-label">Status</label>
                                    <select class="form-select" name="status">
                                        @foreach($statuses as $key => $label)
                                            <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Category</label>
                                    <select class="form-select" name="category">
                                        @foreach($categories as $key => $label)
                                            <option value="{{ $key }}" {{ request('category') == $key ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Priority</label>
                                    <select class="form-select" name="priority">
                                        @foreach($priorities as $key => $label)
                                            <option value="{{ $key }}" {{ request('priority') == $key ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary">Apply Filters</button>
                                    <a href="{{ route('admin.notifications.index') }}" class="btn btn-outline-secondary">Clear Filters</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary bg-opacity-10 border-primary">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="ri-notification-line fs-2 text-primary"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h4 class="mb-0">{{ $totalNotifications }}</h4>
                            <small class="text-muted">Total Notifications</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card bg-warning bg-opacity-10 border-warning">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="ri-mail-unread-line fs-2 text-warning"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h4 class="mb-0">{{ $unreadCount }}</h4>
                            <small class="text-muted">Unread</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card bg-success bg-opacity-10 border-success">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="ri-mail-check-line fs-2 text-success"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h4 class="mb-0">{{ $totalNotifications - $unreadCount }}</h4>
                            <small class="text-muted">Read</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card bg-info bg-opacity-10 border-info">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="ri-delete-bin-line fs-2 text-info"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="d-grid">
                                <form action="{{ route('admin.notifications.clear-all') }}" method="POST" 
                                      onsubmit="return confirm('Are you sure you want to clear all notifications?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-info">
                                        Clear All Notifications
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Notifications List -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body p-0">
                    @if($notifications->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($notifications as $notification)
                                @php
                                    $data = $notification->data;
                                    $icon = $data['icon'] ?? 'ri-notification-line';
                                    $bgColor = match($notification->priority) {
                                        'low' => 'info',
                                        'normal' => 'primary',
                                        'high' => 'warning',
                                        'urgent' => 'danger',
                                        default => 'secondary',
                                    };
                                @endphp
                                
                                <div class="list-group-item p-4 {{ $notification->unread() ? 'bg-light' : '' }}">
                                    <div class="d-flex align-items-start">
                                        <div class="flex-shrink-0 me-3">
                                            <div class="rounded-circle bg-{{ $bgColor }}-subtle text-{{ $bgColor }} p-3">
                                                <i class="{{ $icon }} fs-4"></i>
                                            </div>
                                        </div>
                                        
                                        <div class="flex-grow-1">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <div>
                                                    <h6 class="mb-1">{{ $data['title'] ?? 'Notification' }}</h6>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <span class="badge bg-{{ $bgColor }}">
                                                            {{ ucfirst($notification->priority) }}
                                                        </span>
                                                        <span class="badge bg-secondary">
                                                            {{ ucfirst($notification->category) }}
                                                        </span>
                                                        @if($notification->unread())
                                                            <span class="badge bg-warning">New</span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="text-end">
                                                    <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                                    <div class="mt-2">
                                                        @if($notification->unread())
                                                            <form action="{{ route('admin.notifications.mark-read', $notification) }}" 
                                                                  method="POST" class="d-inline">
                                                                @csrf
                                                                <button type="submit" class="btn btn-sm btn-outline-success">
                                                                    Mark as Read
                                                                </button>
                                                            </form>
                                                        @endif
                                                        
                                                        <form action="{{ route('admin.notifications.destroy', $notification) }}" 
                                                              method="POST" class="d-inline ms-1"
                                                              onsubmit="return confirm('Delete this notification?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                                Delete
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <p class="mb-2">{{ $data['message'] ?? '' }}</p>
                                            
                                            @if(isset($data['url']) && $data['url'] != '#')
                                                <a href="{{ $data['url'] }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="ri-external-link-line me-1"></i> View Details
                                                </a>
                                            @endif
                                            
                                            @if($notification->shipment_id)
                                                <span class="badge bg-light text-dark ms-2">
                                                    <i class="ri-ship-line me-1"></i> Shipment
                                                </span>
                                            @endif
                                            
                                            @if($notification->invoice_id)
                                                <span class="badge bg-light text-dark ms-2">
                                                    <i class="ri-file-text-line me-1"></i> Invoice
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <!-- Pagination -->
                        <div class="card-footer">
                            {{ $notifications->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="ri-notification-off-line fs-1 text-muted mb-3"></i>
                            <h5 class="text-muted">No notifications found</h5>
                            <p class="text-muted">You're all caught up!</p>
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-primary mt-3">
                                <i class="ri-dashboard-line me-2"></i> Back to Dashboard
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Bulk Actions -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Bulk Actions</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex gap-2">
                        <form action="{{ route('admin.notifications.mark-all-read') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-success" 
                                    {{ $unreadCount == 0 ? 'disabled' : '' }}>
                                <i class="ri-check-double-line me-2"></i> Mark All as Read
                            </button>
                        </form>
                        
                        <div class="dropdown">
                            <button class="btn btn-outline-danger dropdown-toggle" type="button" 
                                    data-bs-toggle="dropdown" {{ $totalNotifications == 0 ? 'disabled' : '' }}>
                                <i class="ri-delete-bin-line me-2"></i> Clear Notifications
                            </button>
                            <ul class="dropdown-menu">
                                <li>
                                    <form action="{{ route('admin.notifications.clear-all') }}" method="POST"
                                          onsubmit="return confirm('Clear all notifications?')">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="status" value="all">
                                        <button type="submit" class="dropdown-item">Clear All</button>
                                    </form>
                                </li>
                                <li>
                                    <form action="{{ route('admin.notifications.clear-all') }}" method="POST"
                                          onsubmit="return confirm('Clear all read notifications?')">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="status" value="read">
                                        <button type="submit" class="dropdown-item">Clear Read Only</button>
                                    </form>
                                </li>
                                <li>
                                    <form action="{{ route('admin.notifications.clear-all') }}" method="POST"
                                          onsubmit="return confirm('Clear all unread notifications?')">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="status" value="unread">
                                        <button type="submit" class="dropdown-item">Clear Unread Only</button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .list-group-item:hover {
        background-color: var(--bs-light) !important;
    }
</style>
@endpush
@endsection