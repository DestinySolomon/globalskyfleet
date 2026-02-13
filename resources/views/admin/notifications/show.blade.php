@extends('layouts.admin')

@section('title', 'Notification Details | GlobalSkyFleet')
@section('page-title', 'Notification Details')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Back Button -->
            <div class="mb-3">
                <a href="{{ route('admin.notifications.index') }}" class="btn btn-outline-secondary">
                    <i class="ri-arrow-left-line me-2"></i>Back to Notifications
                </a>
            </div>

            <!-- Notification Details Card -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0">
                            @php
                                $data = is_array($notification->data) ? $notification->data : (array) $notification->data;
                            @endphp
                            <i class="{{ $data['icon'] ?? 'ri-notification-line' }} me-2"></i>
                            {{ $data['title'] ?? 'Notification' }}
                        </h5>
                        <small class="text-muted">
                            {{ $notification->created_at->format('F d, Y \a\t H:i') }}
                        </small>
                    </div>
                    <div>
                        @if($notification->unread())
                            <span class="badge bg-primary">Unread</span>
                        @else
                            <span class="badge bg-success">Read</span>
                        @endif
                    </div>
                </div>

                <div class="card-body">
                    <!-- Notification Message -->
                    <div class="mb-4">
                        <h6 class="text-muted mb-2">Message</h6>
                        <p class="lead">{{ $data['message'] ?? 'No message content' }}</p>
                    </div>

                    <!-- Notification Details -->
                    <div class="row mb-4">
                        @if(isset($data['type']))
                        <div class="col-md-6">
                            <h6 class="text-muted mb-2">Type</h6>
                            <p>{{ $data['type'] }}</p>
                        </div>
                        @endif

                        @if(isset($data['category']))
                        <div class="col-md-6">
                            <h6 class="text-muted mb-2">Category</h6>
                            <p>
                                <span class="badge bg-light text-dark">{{ ucfirst($data['category']) }}</span>
                            </p>
                        </div>
                        @endif

                        @if(isset($data['priority']))
                        <div class="col-md-6">
                            <h6 class="text-muted mb-2">Priority</h6>
                            <p>
                                @php
                                    $priorityClass = match($data['priority'] ?? 'normal') {
                                        'urgent' => 'danger',
                                        'high' => 'warning',
                                        'low' => 'info',
                                        default => 'secondary'
                                    };
                                @endphp
                                <span class="badge bg-{{ $priorityClass }}">{{ ucfirst($data['priority'] ?? 'Normal') }}</span>
                            </p>
                        </div>
                        @endif

                        @if(isset($data['tracking_number']))
                        <div class="col-md-6">
                            <h6 class="text-muted mb-2">Tracking Number</h6>
                            <p>{{ $data['tracking_number'] }}</p>
                        </div>
                        @endif
                    </div>

                    <!-- Additional Info -->
                    @if(isset($data['shipment_id']))
                    <div class="alert alert-info" role="alert">
                        <i class="ri-ship-line me-2"></i>
                        <strong>Related Shipment</strong>
                        <p class="mb-0 mt-2">
                            <a href="{{ route('admin.shipments.show', $data['shipment_id']) }}" class="btn btn-sm btn-outline-info">
                                <i class="ri-external-link-line me-1"></i>View Shipment
                            </a>
                        </p>
                    </div>
                    @endif

                    @if(isset($data['payment_id']))
                    <div class="alert alert-info" role="alert">
                        <i class="ri-money-dollar-circle-line me-2"></i>
                        <strong>Related Payment</strong>
                        <p class="mb-0 mt-2">
                            <a href="{{ route('admin.payments.show', $data['payment_id']) }}" class="btn btn-sm btn-outline-info">
                                <i class="ri-external-link-line me-1"></i>View Payment
                            </a>
                        </p>
                    </div>
                    @endif

                    @if(isset($data['user_id']))
                    <div class="alert alert-info" role="alert">
                        <i class="ri-user-line me-2"></i>
                        <strong>Related User</strong>
                        <p class="mb-0 mt-2">
                            <a href="{{ route('admin.users.show', $data['user_id']) }}" class="btn btn-sm btn-outline-info">
                                <i class="ri-external-link-line me-1"></i>View User
                            </a>
                        </p>
                    </div>
                    @endif
                </div>

                <div class="card-footer bg-light">
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.notifications.index') }}" class="btn btn-outline-secondary">
                            <i class="ri-arrow-left-line me-2"></i>Back
                        </a>
                        
                        <div>
                            @if($notification->unread())
                            <form action="{{ route('admin.notifications.mark-read', $notification->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-primary">
                                    <i class="ri-check-line me-2"></i>Mark as Read
                                </button>
                            </form>
                            @endif
                            
                            <form action="{{ route('admin.notifications.destroy', $notification->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Delete this notification?')">
                                    <i class="ri-delete-bin-line me-2"></i>Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
