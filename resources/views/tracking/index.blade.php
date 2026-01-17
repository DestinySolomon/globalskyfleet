@extends('layouts.app')

@section('title', 'Track Shipment | GlobalSkyFleet')
@section('page-title', 'Track Shipment')

@section('content')
<div class="row">
    <div class="col-lg-8 mx-auto">
        <!-- Header -->
        <div class="text-center mb-5 mt-5 pt-4">
            <div class="mb-3">
                <i class="ri-search-line text-primary" style="font-size: 3rem;"></i>
            </div>
            <h1 class="h2 fw-bold mb-3">Track Your Shipment</h1>
            <p class="text-muted">Enter your tracking number to get real-time updates on your shipment status</p>
        </div>

        <!-- Tracking Form Card -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4 p-md-5">
                <h5 class="card-title mb-4">
                    <i class="ri-barcode-line me-2"></i>Enter Tracking Number
                </h5>
                
                @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="ri-error-warning-line me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="ri-checkbox-circle-line me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                <form action="{{ route('dashboard.tracking') }}" method="GET" id="trackingForm">
                    <div class="mb-4">
                        <label for="tracking_number" class="form-label fw-semibold">
                            Tracking Number <span class="text-danger">*</span>
                        </label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text bg-primary text-white border-primary">
                                <i class="ri-search-eye-line"></i>
                            </span>
                            <input type="text" 
                                   name="tracking_number" 
                                   id="tracking_number"
                                   class="form-control form-control-lg border-primary"
                                   placeholder="GS12345678"
                                   value="{{ old('tracking_number', request('tracking_number')) }}"
                                   required
                                   maxlength="10"
                                   pattern="^GS[A-Z0-9]{8}$">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="ri-search-line me-2"></i>Track
                            </button>
                        </div>
                        <div class="form-text mt-2">
                            <i class="ri-information-line me-1"></i>
                            Format: GS followed by 8 letters/numbers (e.g., GS12345678)
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tracking Results -->
        @if(request('tracking_number'))
            @if($shipment)
                <!-- Tracking Results Card -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-0 py-4">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h5 class="mb-0 fw-semibold">
                                    <i class="ri-map-pin-line me-2"></i>Tracking Results
                                </h5>
                            </div>
                            <div class="col-md-4 text-md-end">
                                <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-md-end gap-2">
                                    <span class="badge bg-light text-dark">
                                        <i class="ri-barcode-line me-1"></i>
                                        {{ $shipment->tracking_number }}
                                    </span>
                                    <span class="badge 
                                        @if($shipment->status === 'delivered') bg-success
                                        @elseif($shipment->status === 'cancelled') bg-danger
                                        @elseif($shipment->status === 'in_transit') bg-primary
                                        @elseif(in_array($shipment->status, ['pending', 'confirmed'])) bg-warning
                                        @else bg-info @endif">
                                        {{ ucwords(str_replace('_', ' ', $shipment->status)) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <!-- Status Summary -->
                        <div class="row mb-4">
                            <div class="col-md-4 mb-3">
                                <div class="card border-0 bg-light">
                                    <div class="card-body text-center">
                                        <h6 class="text-muted mb-2">Current Status</h6>
                                        <h4 class="fw-bold text-primary mb-0">
                                            {{ ucwords(str_replace('_', ' ', $shipment->status)) }}
                                        </h4>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="card border-0 bg-light">
                                    <div class="card-body text-center">
                                        <h6 class="text-muted mb-2">Current Location</h6>
                                        <h4 class="fw-bold mb-0">
                                            {{ $shipment->current_location ?? 'N/A' }}
                                        </h4>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="card border-0 bg-light">
                                    <div class="card-body text-center">
                                        <h6 class="text-muted mb-2">Estimated Delivery</h6>
                                        <h4 class="fw-bold mb-0">
                                            @if($shipment->estimated_delivery)
                                                {{ $shipment->estimated_delivery->format('M d, Y') }}
                                            @else
                                                N/A
                                            @endif
                                        </h4>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tracking Timeline -->
                        @if($shipment->statusHistory && $shipment->statusHistory->count() > 0)
                        <div class="mb-4">
                            <h6 class="mb-3">
                                <i class="ri-history-line me-2"></i>Tracking History
                            </h6>
                            <div class="timeline">
                                @foreach($shipment->statusHistory as $history)
                                <div class="timeline-item {{ $loop->first ? 'active' : '' }}">
                                    <div class="timeline-marker 
                                        @if($history->status === 'delivered') bg-success
                                        @elseif($history->status === 'cancelled') bg-danger
                                        @elseif($loop->first) bg-primary
                                        @else bg-secondary @endif">
                                    </div>
                                    <div class="timeline-content">
                                        <div class="d-flex justify-content-between align-items-start mb-1">
                                            <h6 class="mb-0 fw-semibold">
                                                {{ ucwords(str_replace('_', ' ', $history->status)) }}
                                            </h6>
                                            <small class="text-muted">
                                                {{ $history->scan_datetime->format('M d, Y h:i A') }}
                                            </small>
                                        </div>
                                        <p class="mb-1">
                                            <i class="ri-map-pin-line me-1"></i>
                                            {{ $history->location }}
                                        </p>
                                        @if($history->description)
                                        <p class="mb-0 text-muted small">
                                            {{ $history->description }}
                                        </p>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <!-- Action Buttons -->
                        <div class="d-flex flex-wrap gap-2">
                            <a href="{{ route('shipments.show', $shipment) }}" class="btn btn-primary">
                                <i class="ri-eye-line me-2"></i>View Full Details
                            </a>
                            <button class="btn btn-outline-primary" onclick="copyTrackingNumber()">
                                <i class="ri-file-copy-line me-2"></i>Copy Tracking Number
                            </button>
                            <button class="btn btn-outline-secondary" onclick="window.print()">
                                <i class="ri-printer-line me-2"></i>Print
                            </button>
                            <a href="{{ route('dashboard.tracking') }}" class="btn btn-outline-secondary">
                                <i class="ri-search-line me-2"></i>Track Another
                            </a>
                        </div>
                    </div>
                </div>
            @else
                <!-- No Results Found -->
                <div class="alert alert-warning">
                    <div class="d-flex">
                        <div class="me-3">
                            <i class="ri-search-line fs-4"></i>
                        </div>
                        <div>
                            <h6 class="alert-heading mb-1">No Shipment Found</h6>
                            <p class="mb-0">Tracking number <strong>{{ request('tracking_number') }}</strong> was not found in your shipments.</p>
                            <p class="mb-0 mt-2 small">Please check the tracking number or create a new shipment.</p>
                        </div>
                    </div>
                </div>
            @endif
        @endif

        <!-- Help Section -->
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <h6 class="fw-semibold mb-3">
                            <i class="ri-question-line me-2"></i>Need Help Finding Your Tracking Number?
                        </h6>
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2 d-flex align-items-start">
                                <i class="ri-mail-line text-primary me-2 mt-1"></i>
                                <span>Check your shipment confirmation email</span>
                            </li>
                            <li class="mb-2 d-flex align-items-start">
                                <i class="ri-receipt-line text-primary me-2 mt-1"></i>
                                <span>Look on your shipping receipt or invoice</span>
                            </li>
                            <li class="mb-2 d-flex align-items-start">
                                <i class="ri-package-line text-primary me-2 mt-1"></i>
                                <span>Find it on your package label</span>
                            </li>
                            <li class="d-flex align-items-start">
                                <i class="ri-dashboard-line text-primary me-2 mt-1"></i>
                                <span>View all your shipments in your dashboard</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <h6 class="fw-semibold mb-3">
                            <i class="ri-shield-check-line me-2"></i>Secure Tracking
                        </h6>
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2 d-flex align-items-start">
                                <i class="ri-lock-line text-success me-2 mt-1"></i>
                                <span>Private tracking - only you can see your shipments</span>
                            </li>
                            <li class="mb-2 d-flex align-items-start">
                                <i class="ri-eye-off-line text-success me-2 mt-1"></i>
                                <span>Your shipment details are confidential</span>
                            </li>
                            <li class="mb-2 d-flex align-items-start">
                                <i class="ri-history-line text-success me-2 mt-1"></i>
                                <span>Real-time status updates</span>
                            </li>
                            <li class="d-flex align-items-start">
                                <i class="ri-shield-keyhole-line text-success me-2 mt-1"></i>
                                <span>All tracking attempts are logged for security</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
.timeline {
    position: relative;
    padding-left: 3rem;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 1.5rem;
    top: 0;
    bottom: 0;
    width: 2px;
    background-color: #e9ecef;
}

.timeline-item {
    position: relative;
    margin-bottom: 1.5rem;
}

.timeline-item:last-child {
    margin-bottom: 0;
}

.timeline-marker {
    position: absolute;
    left: -3rem;
    top: 0;
    width: 3rem;
    height: 3rem;
    border-radius: 50%;
    background: #e9ecef;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.2rem;
    z-index: 1;
}

.timeline-item.active .timeline-marker {
    box-shadow: 0 0 0 3px rgba(10, 36, 99, 0.2);
}

.timeline-content {
    background: white;
    border-radius: 0.5rem;
    padding: 1rem;
    border: 1px solid #e9ecef;
}

@media print {
    .btn, .alert, .card:not(.card-body) {
        display: none !important;
    }
    
    .card {
        border: none !important;
        box-shadow: none !important;
    }
}
</style>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const trackingForm = document.getElementById('trackingForm');
    const trackingInput = document.getElementById('tracking_number');
    
    // Format tracking number on input
    if (trackingInput) {
        trackingInput.addEventListener('input', function(e) {
            let value = e.target.value.toUpperCase().replace(/[^A-Z0-9]/g, '');
            
            // Add GS prefix if not present
            if (!value.startsWith('GS')) {
                value = 'GS' + value.replace('GS', '');
            }
            
            // Limit to 10 characters (GS + 8)
            value = value.substring(0, 10);
            
            e.target.value = value;
        });
        
        // Auto-focus on input
        trackingInput.focus();
    }
    
    // Validate form submission
    if (trackingForm) {
        trackingForm.addEventListener('submit', function(e) {
            const value = trackingInput.value.trim();
            const pattern = /^GS[A-Z0-9]{8}$/;
            
            if (!pattern.test(value)) {
                e.preventDefault();
                alert('Please enter a valid tracking number in the format: GS followed by 8 letters/numbers.');
                trackingInput.focus();
                return false;
            }
        });
    }
});

function copyTrackingNumber() {
    const trackingNumber = '{{ request('tracking_number') }}';
    navigator.clipboard.writeText(trackingNumber).then(function() {
        // Show a nice toast or alert
        const alertDiv = document.createElement('div');
        alertDiv.className = 'alert alert-success alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-3';
        alertDiv.style.zIndex = '9999';
        alertDiv.innerHTML = `
            <i class="ri-checkbox-circle-line me-2"></i>
            Tracking number copied to clipboard!
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        document.body.appendChild(alertDiv);
        
        // Auto remove after 3 seconds
        setTimeout(() => {
            alertDiv.remove();
        }, 3000);
    }).catch(function(err) {
        console.error('Failed to copy: ', err);
        alert('Failed to copy tracking number. Please copy manually: ' + trackingNumber);
    });
}
</script>
@endsection