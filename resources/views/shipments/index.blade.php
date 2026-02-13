@extends('layouts.dashboard')

@section('title', 'My Shipments | GlobalSkyFleet')
@section('page-title', 'My Shipments')

@section('content')
<!-- Security Alert -->
<div class="alert alert-info border-0 mb-4">
    <div class="d-flex align-items-center">
        <i class="ri-shield-keyhole-line me-3" style="font-size: 1.5rem;"></i>
        <div>
            <strong>Security Notice:</strong> Your shipment data is encrypted and protected. Each action is logged for security auditing.
        </div>
    </div>
</div>

<!-- Quick Stats -->
<div class="row mb-4">
    <div class="col-6 col-md-3 mb-3 mb-md-0">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1 small">Total Shipments</h6>
                        <h4 class="mb-0">{{ Auth::user()->shipments()->count() }}</h4>
                    </div>
                    <div class="bg-primary bg-opacity-10 p-2 rounded">
                        <i class="ri-ship-2-line text-primary" style="font-size: 1.25rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3 mb-3 mb-md-0">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1 small">In Transit</h6>
                        <h4 class="mb-0">{{ Auth::user()->inTransitShipments()->count() }}</h4>
                    </div>
                    <div class="bg-warning bg-opacity-10 p-2 rounded">
                        <i class="ri-truck-line text-warning" style="font-size: 1.25rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3 mb-3 mb-md-0">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1 small">Pending</h6>
                        <h4 class="mb-0">{{ Auth::user()->pendingShipments()->count() }}</h4>
                    </div>
                    <div class="bg-info bg-opacity-10 p-2 rounded">
                        <i class="ri-time-line text-info" style="font-size: 1.25rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1 small">Delivered</h6>
                        <h4 class="mb-0">{{ Auth::user()->deliveredShipments()->count() }}</h4>
                    </div>
                    <div class="bg-success bg-opacity-10 p-2 rounded">
                        <i class="ri-checkbox-circle-line text-success" style="font-size: 1.25rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filters and Actions -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body py-3">
        <div class="row align-items-center">
            <div class="col-md-8 mb-3 mb-md-0">
                <form action="{{ route('shipments.index') }}" method="GET" class="row g-2">
                    <div class="col-12 col-md-6 mb-2 mb-md-0">
                        <div class="input-group">
                            <span class="input-group-text bg-transparent border-end-0">
                                <i class="ri-search-line"></i>
                            </span>
                            <input type="text" 
                                   name="search" 
                                   class="form-control border-start-0" 
                                   placeholder="Search shipments..."
                                   value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <select name="status" class="form-select" onchange="this.form.submit()">
                            <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All Statuses</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                            <option value="picked_up" {{ request('status') == 'picked_up' ? 'selected' : '' }}>Picked Up</option>
                            <option value="in_transit" {{ request('status') == 'in_transit' ? 'selected' : '' }}>In Transit</option>
                            <option value="customs_hold" {{ request('status') == 'customs_hold' ? 'selected' : '' }}>Customs Hold</option>
                            <option value="out_for_delivery" {{ request('status') == 'out_for_delivery' ? 'selected' : '' }}>Out for Delivery</option>
                            <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Delivered</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="col-md-4 text-md-end">
               <a href="{{ route('shipments.create') }}" class="btn btn-primary w-100 w-md-auto">
                   <i class="ri-add-line me-2"></i>Create New Shipment
               </a>
            </div>
        </div>
    </div>
</div>

<!-- Shipments Table -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-0 d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center py-3">
        <h5 class="mb-2 mb-md-0 fw-semibold">
            <i class="ri-ship-line me-2"></i>My Shipments
        </h5>
        <div class="text-muted small">
            @if($shipments->total() > 0)
                Showing {{ $shipments->firstItem() }} - {{ $shipments->lastItem() }} of {{ $shipments->total() }} shipments
            @else
                No shipments found
            @endif
        </div>
    </div>
    
    <div class="card-body p-0">
        @if($shipments->total() > 0)
        <!-- Desktop Table (hidden on mobile) -->
        <div class="table-responsive d-none d-md-block">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">Tracking Number</th>
                        <th>Status</th>
                        <th>From → To</th>
                        <th>ETA</th>
                        <th>Weight/Value</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($shipments as $shipment)
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center">
                                <div class="bg-primary bg-opacity-10 p-2 rounded me-3">
                                    <i class="ri-ship-line text-primary"></i>
                                </div>
                                <div>
                                    <strong class="d-block">{{ $shipment->tracking_number }}</strong>
                                    <small class="text-muted">{{ formatUserTime($shipment->created_at, 'M d, Y') }}</small>
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
                                    'customs_hold' => ['class' => 'status-out-for-delivery', 'icon' => 'ri-shield-keyhole-line', 'label' => 'Customs Hold'],
                                    'out_for_delivery' => ['class' => 'status-out-for-delivery', 'icon' => 'ri-delivery-line', 'label' => 'Out for Delivery'],
                                    'delivered' => ['class' => 'status-delivered', 'icon' => 'ri-check-double-line', 'label' => 'Delivered'],
                                    'cancelled' => ['class' => 'status-cancelled', 'icon' => 'ri-close-circle-line', 'label' => 'Cancelled'],
                                    'returned' => ['class' => 'status-cancelled', 'icon' => 'ri-arrow-go-back-line', 'label' => 'Returned'],
                                ];
                                $config = $statusConfig[$shipment->status] ?? $statusConfig['pending'];
                            @endphp
                            <span class="status-badge {{ $config['class'] }}">
                                <i class="{{ $config['icon'] }}"></i>
                                <span class="d-none d-lg-inline">{{ $config['label'] }}</span>
                            </span>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="text-truncate" style="max-width: 120px;">
                                    <small class="text-muted d-block">From:</small>
                                    <strong class="small">{{ $shipment->senderAddress->city ?? 'N/A' }}, {{ $shipment->senderAddress->country ?? '' }}</strong>
                                </div>
                                <i class="ri-arrow-right-line mx-2 text-muted d-none d-lg-inline"></i>
                                <div class="text-truncate" style="max-width: 120px;">
                                    <small class="text-muted d-block">To:</small>
                                    <strong class="small">{{ $shipment->recipientAddress->city ?? 'N/A' }}, {{ $shipment->recipientAddress->country ?? '' }}</strong>
                                </div>
                            </div>
                        </td>
                        <td>
                            @if($shipment->estimated_delivery)
                                <div>
                                    <strong class="d-block small">{{ formatUserTime($shipment->estimated_delivery, 'M d, Y') }}</strong>
                                    <small class="text-muted">{{ $shipment->estimated_delivery->diffForHumans() }}</small>
                                </div>
                            @else
                                <span class="text-muted">Calculating...</span>
                            @endif
                        </td>
                        <td>
                            <div>
                                <div class="mb-1">
                                    <small class="text-muted d-block">Weight:</small>
                                    <strong class="small">{{ $shipment->weight }} kg</strong>
                                </div>
                                <div>
                                    <small class="text-muted d-block">Value:</small>
                                    <strong class="small">{{ $shipment->currency }} {{ number_format($shipment->declared_value, 2) }}</strong>
                                </div>
                            </div>
                        </td>
                        <td class="pe-4">
                            <div class="d-flex justify-content-end gap-2">
                                <!-- View Details Button -->
                                <a href="{{ route('shipments.show', $shipment) }}" 
                                   class="btn btn-sm btn-primary"
                                   title="View Details">
                                    <i class="ri-eye-line d-none d-lg-inline me-1"></i>
                                    <span class="d-lg-none">View</span>
                                </a>
                                
                                <!-- Track Shipment Button -->
                                <a href="{{ route('tracking') }}?tracking_number={{ $shipment->tracking_number }}" 
                                   target="_blank"
                                   class="btn btn-sm btn-info"
                                   title="Track Shipment">
                                    <i class="ri-search-line d-none d-lg-inline me-1"></i>
                                    <span class="d-lg-none">Track</span>
                                </a>
                                
                                <!-- Cancel Button (Conditional) -->
                                @if(in_array($shipment->status, ['pending', 'confirmed']))
                                <button class="btn btn-sm btn-danger"
                                        data-bs-toggle="modal" 
                                        data-bs-target="#cancelModal{{ $shipment->id }}"
                                        title="Cancel Shipment">
                                    <i class="ri-close-circle-line d-none d-lg-inline me-1"></i>
                                    <span class="d-lg-none">Cancel</span>
                                </button>
                                @else
                                <!-- Disabled Cancel Button for non-cancellable shipments -->
                                <button class="btn btn-sm btn-secondary" disabled 
                                        title="Cancellation not available for this status">
                                    <i class="ri-close-circle-line d-none d-lg-inline me-1"></i>
                                    <span class="d-lg-none">Cancel</span>
                                </button>
                                @endif
                            </div>
                            
                            <!-- Cancel Modal -->
                            <div class="modal fade" id="cancelModal{{ $shipment->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Cancel Shipment</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form action="{{ route('shipments.cancel', $shipment) }}" method="POST">
                                            @csrf
                                            <div class="modal-body">
                                                <p>Are you sure you want to cancel shipment <strong>{{ $shipment->tracking_number }}</strong>?</p>
                                                <div class="mb-3">
                                                    <label for="cancellation_reason" class="form-label">Reason for cancellation *</label>
                                                    <textarea class="form-control" id="cancellation_reason" name="cancellation_reason" rows="3" required placeholder="Please provide a reason for cancellation..." minlength="10" maxlength="500"></textarea>
                                                    <div class="form-text">Required for audit purposes (10-500 characters).</div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-danger">Confirm Cancellation</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Mobile Cards (hidden on desktop) -->
        <div class="d-block d-md-none">
            @foreach($shipments as $shipment)
            <div class="card border-0 border-bottom rounded-0 bg-transparent">
                <div class="card-body">
                    <!-- Tracking Header -->
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="d-flex align-items-center">
                            <div class="bg-primary bg-opacity-10 p-2 rounded me-3">
                                <i class="ri-ship-line text-primary"></i>
                            </div>
                            <div>
                                <strong class="d-block">{{ $shipment->tracking_number }}</strong>
                                <small class="text-muted">{{ formatUserTime($shipment->created_at, 'M d, Y') }}</small>
                            </div>
                        </div>
                        
                        @php
                            $statusConfig = [
                                'pending' => ['class' => 'status-pending', 'icon' => 'ri-time-line', 'label' => 'Pending'],
                                'confirmed' => ['class' => 'status-processing', 'icon' => 'ri-check-line', 'label' => 'Confirmed'],
                                'picked_up' => ['class' => 'status-in-transit', 'icon' => 'ri-truck-line', 'label' => 'Picked Up'],
                                'in_transit' => ['class' => 'status-in-transit', 'icon' => 'ri-roadster-line', 'label' => 'In Transit'],
                                'customs_hold' => ['class' => 'status-out-for-delivery', 'icon' => 'ri-shield-keyhole-line', 'label' => 'Customs Hold'],
                                'out_for_delivery' => ['class' => 'status-out-for-delivery', 'icon' => 'ri-delivery-line', 'label' => 'Out for Delivery'],
                                'delivered' => ['class' => 'status-delivered', 'icon' => 'ri-check-double-line', 'label' => 'Delivered'],
                                'cancelled' => ['class' => 'status-cancelled', 'icon' => 'ri-close-circle-line', 'label' => 'Cancelled'],
                                'returned' => ['class' => 'status-cancelled', 'icon' => 'ri-arrow-go-back-line', 'label' => 'Returned'],
                            ];
                            $config = $statusConfig[$shipment->status] ?? $statusConfig['pending'];
                        @endphp
                        <span class="status-badge {{ $config['class'] }} small">
                            <i class="{{ $config['icon'] }}"></i>
                            {{ $config['label'] }}
                        </span>
                    </div>

                    <!-- Route Info -->
                    <div class="mb-3">
                        <div class="d-flex align-items-center mb-2">
                            <div class="flex-grow-1">
                                <small class="text-muted d-block">From:</small>
                                <strong>{{ $shipment->senderAddress->city ?? 'N/A' }}, {{ $shipment->senderAddress->country ?? '' }}</strong>
                            </div>
                            <i class="ri-arrow-right-line mx-2 text-muted"></i>
                            <div class="flex-grow-1">
                                <small class="text-muted d-block">To:</small>
                                <strong>{{ $shipment->recipientAddress->city ?? 'N/A' }}, {{ $shipment->recipientAddress->country ?? '' }}</strong>
                            </div>
                        </div>
                    </div>

                    <!-- ETA -->
                    <div class="mb-3">
                        <small class="text-muted d-block">Estimated Delivery:</small>
                        @if($shipment->estimated_delivery)
                            <strong class="d-block">{{ formatUserTime($shipment->estimated_delivery, 'M d, Y') }}</strong>
                            <small class="text-muted">{{ $shipment->estimated_delivery->diffForHumans() }}</small>
                        @else
                            <span class="text-muted">Calculating...</span>
                        @endif
                    </div>

                    <!-- Weight & Value -->
                    <div class="row mb-3">
                        <div class="col-6">
                            <small class="text-muted d-block">Weight:</small>
                            <strong>{{ $shipment->weight }} kg</strong>
                        </div>
                        <div class="col-6">
                            <small class="text-muted d-block">Value:</small>
                            <strong>{{ $shipment->currency }} {{ number_format($shipment->declared_value, 2) }}</strong>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="d-flex justify-content-between gap-2">
                        <!-- View Details Button -->
                        <a href="{{ route('shipments.show', $shipment) }}" 
                           class="btn btn-primary flex-fill">
                            <i class="ri-eye-line me-1"></i>View
                        </a>
                        
                        <!-- Track Shipment Button -->
                        <a href="{{ route('tracking') }}?tracking_number={{ $shipment->tracking_number }}" 
                           target="_blank"
                           class="btn btn-info flex-fill">
                            <i class="ri-search-line me-1"></i>Track
                        </a>
                        
                        <!-- Cancel Button (Conditional) -->
                        @if(in_array($shipment->status, ['pending', 'confirmed']))
                        <button class="btn btn-danger flex-fill"
                                data-bs-toggle="modal" 
                                data-bs-target="#cancelModal{{ $shipment->id }}">
                            <i class="ri-close-circle-line me-1"></i>Cancel
                        </button>
                        @else
                        <!-- Disabled Cancel Button for non-cancellable shipments -->
                        <button class="btn btn-secondary flex-fill" disabled>
                            <i class="ri-close-circle-line me-1"></i>Cancel
                        </button>
                        @endif
                    </div>
                    
                    <!-- Cancel Modal for Mobile -->
                    <div class="modal fade" id="cancelModal{{ $shipment->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Cancel Shipment</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <form action="{{ route('shipments.cancel', $shipment) }}" method="POST">
                                    @csrf
                                    <div class="modal-body">
                                        <p>Are you sure you want to cancel shipment <strong>{{ $shipment->tracking_number }}</strong>?</p>
                                        <div class="mb-3">
                                            <label for="cancellation_reason" class="form-label">Reason for cancellation *</label>
                                            <textarea class="form-control" id="cancellation_reason" name="cancellation_reason" rows="3" required placeholder="Please provide a reason for cancellation..." minlength="10" maxlength="500"></textarea>
                                            <div class="form-text">Required for audit purposes (10-500 characters).</div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-danger">Confirm Cancellation</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-5">
            <div class="mb-4">
                <i class="ri-ship-line text-muted" style="font-size: 3rem;"></i>
            </div>
            <h5 class="text-muted mb-3">No shipments found</h5>
            <p class="text-muted mb-4">Create your first shipment to get started</p>
            <a href="{{ route('shipments.create') }}" class="btn btn-primary">
                <i class="ri-add-line me-2"></i>Create Shipment
            </a>
        </div>
        @endif
    </div>
    
    @if($shipments->hasPages())
    <div class="card-footer bg-white border-0 py-3">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
            <div class="text-muted small mb-2 mb-md-0">
                Showing {{ $shipments->firstItem() }} - {{ $shipments->lastItem() }} of {{ $shipments->total() }} shipments
            </div>
            <div class="w-100 w-md-auto">
                {{ $shipments->withQueryString()->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Security Information -->
<div class="row mt-4">
    <div class="col-12 col-md-6 mb-4 mb-md-0">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <h6 class="fw-semibold mb-3">
                    <i class="ri-shield-keyhole-line me-2"></i>Security & Privacy
                </h6>
                <ul class="list-unstyled mb-0">
                    <li class="mb-2">
                        <i class="ri-checkbox-circle-line text-success me-2"></i>
                        End-to-end encrypted shipment data
                    </li>
                    <li class="mb-2">
                        <i class="ri-checkbox-circle-line text-success me-2"></i>
                        All actions are logged for audit trails
                    </li>
                    <li class="mb-2">
                        <i class="ri-checkbox-circle-line text-success me-2"></i>
                        Rate limiting prevents abuse
                    </li>
                    <li>
                        <i class="ri-checkbox-circle-line text-success me-2"></i>
                        GDPR compliant data handling
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <h6 class="fw-semibold mb-3">
                    <i class="ri-question-line me-2"></i>Need Help?
                </h6>
                <p class="text-muted small mb-3">For shipment inquiries or security concerns:</p>
                <div class="d-flex flex-column flex-sm-row gap-2">
                    <a href="{{ route('contact') }}" class="btn btn-outline-primary btn-sm flex-fill">
                        <i class="ri-customer-service-line me-1"></i>Contact Support
                    </a>
                    <a href="#" class="btn btn-outline-secondary btn-sm flex-fill">
                        <i class="ri-file-text-line me-1"></i>Security FAQ
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-submit search on enter
        const searchInput = document.querySelector('input[name="search"]');
        if (searchInput) {
            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    this.form.submit();
                }
            });
        }
        
        // Initialize tooltips for desktop
        if (window.innerWidth >= 768) {
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
            const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        }
        
        // Validate cancellation reason length
        document.querySelectorAll('form[action*="cancel"]').forEach(form => {
            form.addEventListener('submit', function(e) {
                const reason = this.querySelector('textarea[name="cancellation_reason"]');
                if (reason && reason.value.length < 10) {
                    e.preventDefault();
                    alert('Please provide a cancellation reason of at least 10 characters.');
                    reason.focus();
                }
            });
        });
    });
</script>

<style>
    /* Responsive adjustments */
    @media (max-width: 767.98px) {
        .card-body {
            padding: 1rem !important;
        }
        
        .btn {
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;
        }
        
        h4 {
            font-size: 1.25rem;
        }
        
        h6 {
            font-size: 0.875rem;
        }
    }
    
    @media (max-width: 575.98px) {
        .btn {
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
        }
        
        .status-badge {
            font-size: 0.65rem;
            padding: 0.2rem 0.5rem;
        }
    }
    
    /* Action buttons styling */
    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
        border-radius: 0.25rem;
        min-width: 60px;
    }
    
    @media (min-width: 992px) {
        .btn-sm {
            min-width: 70px;
        }
    }
    
    /* Status badge styles */
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        padding: 0.25rem 0.75rem;
        border-radius: 50rem;
        font-size: 0.75rem;
        font-weight: 500;
        white-space: nowrap;
    }
    
    .status-pending {
        background-color: rgba(13, 110, 253, 0.1);
        color: #0d6efd;
    }
    
    .status-processing {
        background-color: rgba(13, 202, 240, 0.1);
        color: #0dcaf0;
    }
    
    .status-in-transit {
        background-color: rgba(255, 193, 7, 0.1);
        color: #ffc107;
    }
    
    .status-out-for-delivery {
        background-color: rgba(111, 66, 193, 0.1);
        color: #6f42c1;
    }
    
    .status-delivered {
        background-color: rgba(25, 135, 84, 0.1);
        color: #198754;
    }
    
    .status-cancelled {
        background-color: rgba(220, 53, 69, 0.1);
        color: #dc3545;
    }
    
    /* Mobile card styling */
    .card.border-bottom {
        border-bottom-color: #e9ecef !important;
    }
    
    .card.border-bottom:last-child {
        border-bottom: none !important;
    }
</style>
@endsection