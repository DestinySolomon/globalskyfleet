@extends('layouts.dashboard')

@section('title', 'Dashboard | GlobalSkyFleet')
@section('page-title', 'Dashboard')

@section('content')
    <!-- Welcome Section - NOW AT TOP -->
    <div class="alert alert-primary border-0 mb-4" style="background: linear-gradient(135deg, #1e40af, #3b82f6); color: white; border-radius: 12px;">
        <div class="d-flex align-items-center flex-wrap gap-3">
            <div class="me-3">
                <i class="ri-information-line" style="font-size: 2rem;"></i>
            </div>
            <div class="flex-grow-1">
                <h5 class="mb-1">Welcome back, {{ Auth::user()->name }}! 👋</h5>
                <p class="mb-0 opacity-90">Here's what's happening with your shipments today. You have <strong>{{ $stats['in_transit'] ?? 0 }}</strong> active shipments in transit.</p>
            </div>
                          <a href="{{ route('shipments.create') }}" class="btn btn-primary">
    <i class="ri-add-line me-2"></i>Create Shipment
</a>
        </div>
    </div>

    <!-- Stats Overview -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon" style="background: #e0f2fe; color: #0284c7;">
                <i class="ri-ship-2-line"></i>
            </div>
            <div class="stat-value">{{ $stats['total'] ?? 0 }}</div>
            <div class="stat-label">Total Shipments</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon" style="background: #fef3c7; color: #d97706;">
                <i class="ri-time-line"></i>
            </div>
            <div class="stat-value">{{ $stats['pending'] ?? 0 }}</div>
            <div class="stat-label">Pending</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon" style="background: #d1fae5; color: #059669;">
                <i class="ri-roadster-line"></i>
            </div>
            <div class="stat-value">{{ $stats['in_transit'] ?? 0 }}</div>
            <div class="stat-label">In Transit</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon" style="background: #dcfce7; color: #166534;">
                <i class="ri-checkbox-circle-line"></i>
            </div>
            <div class="stat-value">{{ $stats['delivered'] ?? 0 }}</div>
            <div class="stat-label">Delivered</div>
        </div>
    </div>

    <!-- Recent Shipments Table -->
    <div class="shipments-table mb-4">
        <div class="table-header">
            <h5 class="mb-0 fw-semibold">Recent Shipments</h5>
            <a href="#" class="btn btn-sm btn-outline-primary">
                View All <i class="ri-arrow-right-line ms-1"></i>
            </a>
        </div>
        
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Tracking ID</th>
                        <th>Status</th>
                        <th>From</th>
                        <th>To</th>
                        <th>ETA</th>
                        <th>Progress</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentShipments as $shipment)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="bg-primary bg-opacity-10 p-2 rounded me-3">
                                    <i class="ri-ship-line text-primary"></i>
                                </div>
                                <div>
                                    <strong class="d-block">{{ $shipment->tracking_number }}</strong>
                                    <small class="text-muted">{{ $shipment->created_at->format('M d, Y') }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            @php
                                $statusConfig = [
                                    'pending' => ['class' => 'status-pending', 'icon' => 'ri-time-line', 'label' => 'Pending'],
                                    'confirmed' => ['class' => 'status-processing', 'icon' => 'ri-check-line', 'label' => 'Confirmed'],
                                    'picked_up' => ['class' => 'status-in-transit', 'icon' => 'ri-truck-line', 'label' => 'Picked Up'],
                                    'in_transit' => ['class' => 'status-in-transit', 'icon' => 'ri-roadster-line', 'label' => 'In Transit'],
                                    'out_for_delivery' => ['class' => 'status-out-for-delivery', 'icon' => 'ri-delivery-line', 'label' => 'Out for Delivery'],
                                    'delivered' => ['class' => 'status-delivered', 'icon' => 'ri-check-double-line', 'label' => 'Delivered'],
                                    'cancelled' => ['class' => 'status-cancelled', 'icon' => 'ri-close-circle-line', 'label' => 'Cancelled'],
                                ];
                                $config = $statusConfig[$shipment->status] ?? $statusConfig['pending'];
                            @endphp
                            <span class="status-badge {{ $config['class'] }}">
                                <i class="{{ $config['icon'] }}"></i>
                                {{ $config['label'] }}
                            </span>
                        </td>
                        <td>
                            <div>
                                <strong class="d-block">{{ $shipment->senderAddress->city ?? 'N/A' }}</strong>
                                <small class="text-muted">{{ $shipment->senderAddress->country_code ?? '' }}</small>
                            </div>
                        </td>
                        <td>
                            <div>
                                <strong class="d-block">{{ $shipment->recipientAddress->city ?? 'N/A' }}</strong>
                                <small class="text-muted">{{ $shipment->recipientAddress->country_code ?? '' }}</small>
                            </div>
                        </td>
                        <td>
                            @if($shipment->estimated_delivery)
                                <div>
                                    <strong class="d-block">{{ $shipment->estimated_delivery->format('M d, Y') }}</strong>
                                    <small class="text-muted">{{ $shipment->estimated_delivery->diffForHumans() }}</small>
                                </div>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td style="width: 150px;">
                            @php
                                $progressMap = [
                                    'pending' => 10,
                                    'confirmed' => 25,
                                    'picked_up' => 40,
                                    'in_transit' => 65,
                                    'out_for_delivery' => 85,
                                    'delivered' => 100,
                                    'cancelled' => 0,
                                ];
                                $progress = $progressMap[$shipment->status] ?? 10;
                            @endphp
                            <div class="d-flex align-items-center gap-2">
                                <div class="progress flex-grow-1">
                                    <div class="progress-bar" style="width: {{ $progress }}%"></div>
                                </div>
                                <small class="text-muted">{{ $progress }}%</small>
                            </div>
                        </td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    Actions
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('tracking', ['tracking_number' => $shipment->tracking_number]) }}">
                                            <i class="ri-search-line me-2"></i>Track
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="#">
                                            <i class="ri-file-text-line me-2"></i>Details
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="#">
                                            <i class="ri-download-line me-2"></i>Download Label
                                        </a>
                                    </li>
                                    @if($shipment->status === 'pending')
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li>
                                        <a class="dropdown-item text-danger" href="#">
                                            <i class="ri-close-circle-line me-2"></i>Cancel Shipment
                                        </a>
                                    </li>
                                    @endif
                                </ul>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <div class="mb-4">
                                <i class="ri-ship-line text-muted" style="font-size: 3rem;"></i>
                            </div>
                            <h5 class="text-muted mb-3">No shipments yet</h5>
                            <p class="text-muted mb-4">Create your first shipment to get started</p>
                                          <a href="{{ route('shipments.create') }}" class="btn btn-primary">
    <i class="ri-add-line me-2"></i>Create Shipment
</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Two Column Layout -->
    <div class="row g-4">
        <!-- Quick Actions -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0 fw-semibold">
                        <i class="ri-flashlight-line me-2"></i>Quick Actions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <a href="{{ route('shipments.create') }}" class="card border-0 shadow-sm text-decoration-none h-100">
                                <div class="card-body text-center py-4">
                                    <div class="bg-primary bg-opacity-10 p-3 rounded-circle d-inline-flex mb-3">
                                        <i class="ri-add-line text-primary" style="font-size: 1.5rem;"></i>
                                    </div>
                                    <h6 class="fw-semibold mb-2">Create Shipment</h6>
                                    <p class="text-muted small mb-0">Schedule a new delivery</p>
                                </div>
                            </a>
                        </div>
                        
                        <div class="col-md-4">
                            <a href="{{ route('tracking') }}" class="card border-0 shadow-sm text-decoration-none h-100">
                                <div class="card-body text-center py-4">
                                    <div class="bg-info bg-opacity-10 p-3 rounded-circle d-inline-flex mb-3">
                                        <i class="ri-search-line text-info" style="font-size: 1.5rem;"></i>
                                    </div>
                                    <h6 class="fw-semibold mb-2">Track Shipment</h6>
                                    <p class="text-muted small mb-0">Enter tracking number</p>
                                </div>
                            </a>
                        </div>
                        
                        <div class="col-md-4">
                            <a href="{{ route('addresses.index') }}" class="card border-0 shadow-sm text-decoration-none h-100">
                                <div class="card-body text-center py-4">
                                    <div class="bg-success bg-opacity-10 p-3 rounded-circle d-inline-flex mb-3">
                                        <i class="ri-map-pin-line text-success" style="font-size: 1.5rem;"></i>
                                    </div>
                                    <h6 class="fw-semibold mb-2">Manage Addresses</h6>
                                    <p class="text-muted small mb-0">Add/edit delivery addresses</p>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Service Status -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0 fw-semibold">
                        <i class="ri-bar-chart-2-line me-2"></i>Service Status
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="text-muted small">Express Delivery</span>
                            <span class="badge bg-success">Normal</span>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar bg-success" style="width: 95%"></div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="text-muted small">Economy Shipping</span>
                            <span class="badge bg-success">Normal</span>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar bg-success" style="width: 90%"></div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="text-muted small">Customs Clearance</span>
                            <span class="badge bg-warning">Delays</span>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar bg-warning" style="width: 65%"></div>
                        </div>
                    </div>
                    
                    <div class="alert alert-warning border-0 small mt-3 mb-0">
                        <i class="ri-alert-line me-2"></i>
                        Customs processing may experience 24-48 hour delays due to increased volume.
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection