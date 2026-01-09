@extends('layouts.admin')

@section('page-title', 'Admin Dashboard')

@section('content')
<div class="container-fluid">
    <!-- Stats Cards -->
    <div class="row g-3 mb-4">
        <!-- Total Users -->
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-primary bg-opacity-10 p-3 me-3">
                            <i class="ri-user-line text-primary fs-3"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h3 class="mb-1">{{ $stats['total_users'] }}</h3>
                            <p class="text-muted mb-0">Total Users</p>
                        </div>
                        <div class="text-end">
                            <span class="badge bg-success">+{{ $stats['new_users_today'] }} today</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Shipments -->
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-info bg-opacity-10 p-3 me-3">
                            <i class="ri-ship-line text-info fs-3"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h3 class="mb-1">{{ $stats['total_shipments'] }}</h3>
                            <p class="text-muted mb-0">Total Shipments</p>
                        </div>
                        <div class="text-end">
                            <span class="badge bg-success">+{{ $stats['shipments_today'] }} today</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Documents -->
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-warning bg-opacity-10 p-3 me-3">
                            <i class="ri-file-text-line text-warning fs-3"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h3 class="mb-1">{{ $stats['pending_documents'] ?? 0 }}</h3>
                            <p class="text-muted mb-0">Pending Docs</p>
                        </div>
                        <div class="text-end">
                            <span class="badge bg-danger">Needs Review</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Crypto Payments Pending -->
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-success bg-opacity-10 p-3 me-3">
                            <i class="ri-currency-line text-success fs-3"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h3 class="mb-1">{{ $stats['crypto_payments_pending'] }}</h3>
                            <p class="text-muted mb-0">Pending Payments</p>
                        </div>
                        <div class="text-end">
                            <span class="badge bg-warning">Verify Now</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- In Transit Shipments -->
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-secondary bg-opacity-10 p-3 me-3">
                            <i class="ri-roadster-line text-secondary fs-3"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h3 class="mb-1">{{ $stats['in_transit_shipments'] }}</h3>
                            <p class="text-muted mb-0">In Transit</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delivered Shipments -->
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-success bg-opacity-10 p-3 me-3">
                            <i class="ri-checkbox-circle-line text-success fs-3"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h3 class="mb-1">{{ $stats['delivered_shipments'] }}</h3>
                            <p class="text-muted mb-0">Delivered</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Revenue -->
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-danger bg-opacity-10 p-3 me-3">
                            <i class="ri-money-dollar-circle-line text-danger fs-3"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h3 class="mb-1">${{ number_format($stats['total_revenue'] ?? 0, 0) }}</h3>
                            <p class="text-muted mb-0">Total Revenue</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Active Wallets -->
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-success bg-opacity-10 p-3 me-3">
                          <i class="ri-wallet-3-line text-purple fs-3"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h3 class="mb-1">{{ $stats['active_wallets'] ?? 0 }}</h3>
                            <p class="text-muted mb-0">Active Wallets</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts and Tables Row -->
    <div class="row g-4">
        <!-- Recent Shipments -->
        <div class="col-xl-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="ri-ship-line me-2"></i>Recent Shipments</h5>
                    <a href="{{ route('admin.shipments') }}" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th width="120">Tracking #</th>
                                    <th>Customer</th>
                                    <th width="100">From</th>
                                    <th width="100">To</th>
                                    <th width="120">Status</th>
                                    <th width="100">Created</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentShipments as $shipment)
                                <tr>
                                    <td>
                                        <a href="{{ route('admin.shipments.show', $shipment->id) }}" 
                                           class="text-decoration-none">
                                            <strong>{{ $shipment->tracking_number }}</strong>
                                        </a>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center me-2" 
                                                 style="width: 28px; height: 28px; font-size: 11px; font-weight: bold;">
                                                {{ substr($shipment->user->name, 0, 2) }}
                                            </div>
                                            <div>
                                                <div class="fw-semibold">{{ $shipment->user->name }}</div>
                                                <small class="text-muted">{{ $shipment->user->email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($shipment->senderAddress)
                                            <small>{{ $shipment->senderAddress->city }}</small>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($shipment->recipientAddress)
                                            <small>{{ $shipment->recipientAddress->city }}</small>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $shipment->status === 'delivered' ? 'success' : ($shipment->status === 'pending' ? 'warning' : 'info') }}">
                                            {{ ucfirst(str_replace('_', ' ', $shipment->status)) }}
                                        </span>
                                    </td>
                                    <td>
                                        <small>{{ $shipment->created_at->format('M d') }}</small>
                                        <br>
                                        <small class="text-muted">{{ $shipment->created_at->format('H:i') }}</small>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <i class="ri-ship-line fs-1 text-muted opacity-25"></i>
                                        <p class="text-muted mt-2">No shipments yet</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Stats & Actions -->
        <div class="col-xl-4">
            <!-- Recent Users -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0"><i class="ri-user-line me-2"></i>Recent Users</h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        @forelse($recentUsers as $user)
                        <div class="list-group-item border-0 px-0 py-2">
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center me-3" 
                                     style="width: 36px; height: 36px; font-weight: bold;">
                                    {{ $user->initials }}
                                </div>
                                <div class="flex-grow-1">
                                    <div class="fw-semibold">{{ $user->name }}</div>
                                    <small class="text-muted">{{ $user->email }}</small>
                                </div>
                                <div class="text-end">
                                    <small class="text-muted">{{ $user->created_at->diffForHumans() }}</small>
                                    <br>
                                    <span class="badge bg-{{ $user->role === 'admin' ? 'danger' : 'secondary' }}">
                                        {{ $user->role }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-3">
                            <i class="ri-user-line fs-1 text-muted opacity-25"></i>
                            <p class="text-muted mt-2">No users yet</p>
                        </div>
                        @endforelse
                    </div>
                    @if($recentUsers->isNotEmpty())
                    <div class="mt-3">
                        <a href="{{ route('admin.users') }}" class="btn btn-sm btn-outline-primary w-100">
                            <i class="ri-arrow-right-line me-1"></i> View All Users
                        </a>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0"><i class="ri-flashlight-line me-2"></i>Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="row g-2">
                        <div class="col-6">
                            <a href="{{ route('admin.documents') }}" class="btn btn-outline-warning w-100 d-flex flex-column align-items-center py-3">
                                <i class="ri-file-text-line fs-2 mb-2"></i>
                                <span>Review Documents</span>
                                @if(($stats['pending_documents'] ?? 0) > 0)
                                <span class="badge bg-danger mt-1">{{ $stats['pending_documents'] }}</span>
                                @endif
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('admin.payments.crypto') }}" class="btn btn-outline-success w-100 d-flex flex-column align-items-center py-3">
                                <i class="ri-currency-line fs-2 mb-2"></i>
                                <span>Verify Payments</span>
                                @if($stats['crypto_payments_pending'] > 0)
                                <span class="badge bg-danger mt-1">{{ $stats['crypto_payments_pending'] }}</span>
                                @endif
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('admin.wallets') }}" class="btn btn-outline-info w-100 d-flex flex-column align-items-center py-3">
                                <i class="ri-wallet-line fs-2 mb-2"></i>
                                <span>Manage Wallets</span>
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('admin.shipments') }}" class="btn btn-outline-primary w-100 d-flex flex-column align-items-center py-3">
                                <i class="ri-ship-line fs-2 mb-2"></i>
                                <span>All Shipments</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
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