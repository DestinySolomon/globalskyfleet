@extends('layouts.admin')

@section('page-title', 'User Details - ' . $user->name)

@section('content')
<div class="container-fluid">
    <!-- Back Button -->
    <div class="row mb-4">
        <div class="col-12">
            <a href="{{ route('admin.users') }}" class="btn btn-outline-secondary">
                <i class="ri-arrow-left-line me-2"></i>Back to Users
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Left Column: User Info -->
        <div class="col-lg-4">
            <!-- User Profile Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body text-center">
                    <div class="mb-4">
                        <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center mx-auto mb-3" 
                             style="width: 100px; height: 100px; font-size: 36px; font-weight: bold;">
                            {{ $user->initials }}
                        </div>
                        <h4 class="mb-1">{{ $user->name }}</h4>
                        <p class="text-muted mb-2">{{ $user->email }}</p>
                        <div class="d-flex justify-content-center gap-2 mb-3">
                            @if($user->role === 'admin')
                                <span class="badge bg-danger">Admin</span>
                            @elseif($user->role === 'superadmin')
                                <span class="badge bg-purple">Super Admin</span>
                            @else
                                <span class="badge bg-secondary">User</span>
                            @endif
                            
                            @if($user->email_verified_at)
                                <span class="badge bg-success">Verified</span>
                            @else
                                <span class="badge bg-warning">Unverified</span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="list-group list-group-flush text-start">
                        <div class="list-group-item border-0 px-0 py-2">
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">User ID:</span>
                                <span class="fw-semibold">#{{ $user->id }}</span>
                            </div>
                        </div>
                        <div class="list-group-item border-0 px-0 py-2">
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Phone:</span>
                                <span class="fw-semibold">{{ $user->phone ?? 'Not provided' }}</span>
                            </div>
                        </div>
                        <div class="list-group-item border-0 px-0 py-2">
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Company:</span>
                                <span class="fw-semibold">{{ $user->company ?? 'Not provided' }}</span>
                            </div>
                        </div>
                        <div class="list-group-item border-0 px-0 py-2">
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Joined:</span>
                                <span class="fw-semibold">{{ $user->created_at->format('M d, Y H:i') }}</span>
                            </div>
                        </div>
                        <div class="list-group-item border-0 px-0 py-2">
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Last Login:</span>
                                <span class="fw-semibold">
                                    @if($user->last_login_at)
                                        {{ $user->last_login_at->diffForHumans() }}
                                    @else
                                        Never
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white border-top">
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-primary" 
                                data-bs-toggle="modal" data-bs-target="#roleModal">
                            <i class="ri-user-settings-line me-2"></i>Change Role
                        </button>
                        @if(auth()->id() !== $user->id)
                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST"
                              onsubmit="return confirm('Are you sure you want to delete this user? This will delete all their data permanently.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100">
                                <i class="ri-delete-bin-line me-2"></i>Delete User
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h6 class="mb-0"><i class="ri-bar-chart-line me-2"></i>User Statistics</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="text-center">
                                <div class="h3 text-primary">{{ $userStats['total_shipments'] ?? 0 }}</div>
                                <small class="text-muted">Shipments</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center">
                                <div class="h3 text-success">{{ $userStats['delivered_shipments'] ?? 0 }}</div>
                                <small class="text-muted">Delivered</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center">
                                <div class="h3 text-warning">{{ $userStats['total_documents'] ?? 0 }}</div>
                                <small class="text-muted">Documents</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center">
                                <div class="h3 text-info">{{ $userStats['total_payments'] ?? 0 }}</div>
                                <small class="text-muted">Payments</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center">
                                <div class="h3 text-purple">{{ $userStats['total_invoices'] ?? 0 }}</div>
                                <small class="text-muted">Invoices</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center">
                                <div class="h3 text-danger">{{ $userStats['pending_invoices'] ?? 0 }}</div>
                                <small class="text-muted">Pending Invoices</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: User Activity -->
        <div class="col-lg-8">
            <!-- Recent Shipments -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                    <h6 class="mb-0"><i class="ri-ship-line me-2"></i>Recent Shipments</h6>
                    <a href="#" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body">
                    @if($user->shipments->isEmpty())
                    <div class="text-center py-4">
                        <i class="ri-ship-line fs-1 text-muted opacity-25"></i>
                        <p class="text-muted mt-2">No shipments yet</p>
                    </div>
                    @else
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Tracking #</th>
                                    <th>Status</th>
                                    <th>Destination</th>
                                    <th>Created</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($user->shipments->take(5) as $shipment)
                                <tr>
                                    <td>
                                        <a href="{{ route('admin.shipments.show', $shipment->id) }}" class="text-decoration-none">
                                            <strong>{{ $shipment->tracking_number }}</strong>
                                        </a>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $shipment->status === 'delivered' ? 'success' : ($shipment->status === 'pending' ? 'warning' : 'info') }}">
                                            {{ ucfirst(str_replace('_', ' ', $shipment->status)) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($shipment->recipientAddress)
                                            {{ $shipment->recipientAddress->city }}, {{ $shipment->recipientAddress->country_code }}
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>{{ $shipment->created_at->format('M d') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Recent Documents -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                    <h6 class="mb-0"><i class="ri-file-text-line me-2"></i>Recent Documents</h6>
                    <a href="{{ route('admin.documents') }}?user_email={{ $user->email }}" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body">
                    @if($user->documents->isEmpty())
                    <div class="text-center py-4">
                        <i class="ri-file-text-line fs-1 text-muted opacity-25"></i>
                        <p class="text-muted mt-2">No documents yet</p>
                    </div>
                    @else
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Document</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>Uploaded</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($user->documents->take(5) as $document)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="rounded bg-light p-1 me-2">
                                                @if(str_starts_with($document->mime_type, 'image/'))
                                                    <i class="ri-image-line text-primary"></i>
                                                @elseif($document->mime_type === 'application/pdf')
                                                    <i class="ri-file-pdf-line text-danger"></i>
                                                @else
                                                    <i class="ri-file-text-line text-secondary"></i>
                                                @endif
                                            </div>
                                            <div>
                                                <small class="d-block">{{ Str::limit($document->original_name, 30) }}</small>
                                                @if($document->shipment)
                                                <small class="text-muted">Shipment: {{ $document->shipment->tracking_number }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ ucfirst($document->type) }}</span>
                                    </td>
                                    <td>
                                        @if(property_exists($document, 'status'))
                                            @if($document->status === 'verified')
                                                <span class="badge bg-success">Verified</span>
                                            @elseif($document->status === 'rejected')
                                                <span class="badge bg-danger">Rejected</span>
                                            @else
                                                <span class="badge bg-warning">Pending</span>
                                            @endif
                                        @else
                                            <span class="badge bg-secondary">N/A</span>
                                        @endif
                                    </td>
                                    <td>{{ $document->created_at->format('M d') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Recent Payments -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                    <h6 class="mb-0"><i class="ri-money-dollar-circle-line me-2"></i>Recent Payments</h6>
                    <a href="#" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body">
                    @if($user->payments->isEmpty())
                    <div class="text-center py-4">
                        <i class="ri-money-dollar-circle-line fs-1 text-muted opacity-25"></i>
                        <p class="text-muted mt-2">No payments yet</p>
                    </div>
                    @else
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Amount</th>
                                    <th>Method</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($user->payments->take(5) as $payment)
                                <tr>
                                    <td>
                                        <strong>${{ number_format($payment->amount, 2) }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $payment->currency }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark">{{ ucfirst($payment->payment_method) }}</span>
                                    </td>
                                    <td>
                                        @if($payment->status === 'completed')
                                            <span class="badge bg-success">Completed</span>
                                        @elseif($payment->status === 'pending')
                                            <span class="badge bg-warning">Pending</span>
                                        @else
                                            <span class="badge bg-danger">Failed</span>
                                        @endif
                                    </td>
                                    <td>{{ $payment->created_at->format('M d, Y') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Role Change Modal -->
<div class="modal fade" id="roleModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Change User Role</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.users.update-role', $user->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">User</label>
                        <input type="text" class="form-control bg-light" 
                               value="{{ $user->name }} ({{ $user->email }})" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Current Role</label>
                        <input type="text" class="form-control bg-light" 
                               value="{{ ucfirst($user->role) }}" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">New Role *</label>
                        <select name="role" class="form-select" required>
                            <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>Regular User</option>
                            <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                            @if(auth()->user()->email == 'superadmin@globalskyfleet.com')
                            <option value="superadmin" {{ $user->role == 'superadmin' ? 'selected' : '' }}>Super Admin</option>
                            @endif
                        </select>
                        <small class="text-muted">
                            @if(auth()->user()->email == 'superadmin@globalskyfleet.com')
                                Super Admin: Full access + can create other admins
                            @else
                                Admin: Full access to admin panel
                            @endif
                        </small>
                    </div>
                    <div class="alert alert-warning">
                        <i class="ri-alert-line"></i>
                        <strong>Warning:</strong> Changing role to Admin will give this user full access to the admin dashboard.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Role</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .bg-purple {
        background-color: #6f42c1;
    }
    .text-purple {
        color: #6f42c1;
    }
</style>
@endsection