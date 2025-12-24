@extends('layouts.app')

@section('title', 'GlobalSkyFleet - Track Shipment | Real-Time Package Tracking')

@section('description', 'Track your GlobalSkyFleet shipments in real-time. Enter your tracking number to get live updates on delivery status, location, and estimated arrival time.')

@section('keywords', 'track shipment, package tracking, real-time tracking, delivery status, courier tracking, shipment tracking, logistics tracking')

@section('content')
    <!-- Hero Section -->
    <section class="track-hero">
        <div class="container position-relative" style="z-index: 2;">
            <div class="row justify-content-center text-center">
                <div class="col-lg-8">
                    <div class="track-icon">
                        <i class="ri-map-pin-line"></i>
                    </div>
                    <h1 class="display-4 fw-bold text-white mb-4">Track Your Shipment</h1>
                    <p class="text-white opacity-80 fs-5 mb-0">
                        Enter your tracking number below to get real-time updates on your shipment
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Tracking Form Section -->
    <section class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="tracking-form">
                        <form id="trackingForm" method="GET" action="{{ route('tracking.submit') }}">
                            @csrf
                            <div class="mb-4">
                                <label for="trackingNumber" class="form-label fw-semibold text-navy mb-3">Tracking Number</label>
                                <input type="text" 
                                       class="form-control form-control-custom" 
                                       id="trackingNumber" 
                                       name="tracking_number"
                                       placeholder="Enter tracking number (e.g., GSF123456789)" 
                                       value="{{ old('tracking_number', request('tracking_number')) }}"
                                       required>
                                <div class="form-text mt-2 text-muted">
                                    You can find your tracking number in your confirmation email or shipping receipt.
                                </div>
                            </div>
                            <button type="submit" class="btn btn-orange w-100 py-3 rounded-2 fw-semibold fs-5">
                                Track Now <i class="ri-search-line ms-2"></i>
                            </button>
                        </form>
                    </div>
                    
                    <!-- Tracking Results -->
                    @if(isset($trackingResult) || request('tracking_number'))
                        <div id="trackingResults" class="tracking-results show">
                            <div class="status-card mb-4">
                                <div class="row align-items-center">
                                    <div class="col-md-8">
                                        <h3 class="h4 fw-bold text-navy mb-2">Shipment #GSF789456123</h3>
                                        <p class="text-muted mb-0">Shanghai, China → New York, USA</p>
                                    </div>
                                    <div class="col-md-4 text-md-end">
                                        <span class="badge bg-orange fs-6 px-3 py-2">In Transit</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row mb-4">
                                <div class="col-md-3">
                                    <div class="text-center p-3">
                                        <div class="text-skyblue fs-4 fw-bold">2.5kg</div>
                                        <div class="text-muted small">Weight</div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-center p-3">
                                        <div class="text-skyblue fs-4 fw-bold">Express</div>
                                        <div class="text-muted small">Service</div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-center p-3">
                                        <div class="text-skyblue fs-4 fw-bold">Dec 28</div>
                                        <div class="text-muted small">Estimated Delivery</div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-center p-3">
                                        <div class="text-skyblue fs-4 fw-bold">2/5</div>
                                        <div class="text-muted small">Status</div>
                                    </div>
                                </div>
                            </div>
                            
                            <h4 class="h5 fw-bold text-navy mb-3">Shipment Timeline</h4>
                            <div class="timeline">
                                <div class="timeline-item completed">
                                    <div class="d-flex justify-content-between">
                                        <h5 class="h6 fw-bold text-navy mb-1">Package Picked Up</h5>
                                        <span class="text-muted small">Dec 22, 10:30 AM</span>
                                    </div>
                                    <p class="text-muted small mb-0">Shanghai International Airport, China</p>
                                </div>
                                
                                <div class="timeline-item completed">
                                    <div class="d-flex justify-content-between">
                                        <h5 class="h6 fw-bold text-navy mb-1">In Transit</h5>
                                        <span class="text-muted small">Dec 22, 2:15 PM</span>
                                    </div>
                                    <p class="text-muted small mb-0">Departed from Shanghai</p>
                                </div>
                                
                                <div class="timeline-item active">
                                    <div class="d-flex justify-content-between">
                                        <h5 class="h6 fw-bold text-navy mb-1">Arrived at Hub</h5>
                                        <span class="text-muted small">Dec 23, 8:45 AM</span>
                                    </div>
                                    <p class="text-muted small mb-0">Los Angeles International Airport, USA</p>
                                </div>
                                
                                <div class="timeline-item">
                                    <div class="d-flex justify-content-between">
                                        <h5 class="h6 fw-bold text-navy mb-1">Out for Delivery</h5>
                                        <span class="text-muted small">Estimated Dec 28</span>
                                    </div>
                                    <p class="text-muted small mb-0">New York Distribution Center</p>
                                </div>
                                
                                <div class="timeline-item">
                                    <div class="d-flex justify-content-between">
                                        <h5 class="h6 fw-bold text-navy mb-1">Delivered</h5>
                                        <span class="text-muted small">Estimated Dec 28</span>
                                    </div>
                                    <p class="text-muted small mb-0">New York, USA</p>
                                </div>
                            </div>
                            
                            <div class="mt-4 text-center">
                                <button id="trackAnother" class="btn btn-outline-orange rounded-pill px-4">
                                    <i class="ri-refresh-line me-2"></i> Track Another Shipment
                                </button>
                            </div>
                        </div>
                    @else
                        <!-- Empty tracking results template (hidden by default) -->
                        <div id="trackingResults" class="tracking-results">
                            <!-- Results will be shown here via JavaScript -->
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="faq-section">
        <div class="container">
            <div class="row justify-content-center mb-5">
                <div class="col-lg-8 text-center">
                    <h2 class="display-5 fw-bold text-navy mb-3">Tracking FAQ</h2>
                    <p class="text-muted fs-5">Common questions about shipment tracking</p>
                </div>
            </div>
            
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="faq-card">
                        <h5 class="h6 fw-bold text-navy mb-2">Where can I find my tracking number?</h5>
                        <p class="text-muted small mb-0">Your tracking number is provided in the confirmation email sent after booking. You can also find it on your shipping receipt or invoice.</p>
                    </div>
                    
                    <div class="faq-card">
                        <h5 class="h6 fw-bold text-navy mb-2">How often is tracking information updated?</h5>
                        <p class="text-muted small mb-0">Tracking information is updated in real-time at major milestones: pickup, departure, arrival at hubs, and delivery. Some locations may have brief delays in updates.</p>
                    </div>
                    
                    <div class="faq-card">
                        <h5 class="h6 fw-bold text-navy mb-2">My tracking hasn't updated in 24 hours. What should I do?</h5>
                        <p class="text-muted small mb-0">Some international shipments may experience temporary tracking gaps during transit. If updates don't appear within 48 hours, contact our customer support.</p>
                    </div>
                    
                    <div class="faq-card">
                        <h5 class="h6 fw-bold text-navy mb-2">Can I track multiple shipments at once?</h5>
                        <p class="text-muted small mb-0">Yes, registered users can track multiple shipments through their dashboard. For one-time tracking, enter each tracking number separately.</p>
                    </div>
                    
                    <div class="faq-card">
                        <h5 class="h6 fw-bold text-navy mb-2">Is mobile tracking available?</h5>
                        <p class="text-muted small mb-0">Yes, our tracking system is fully responsive and works on all mobile devices. You can also save the tracking page as a bookmark for quick access.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
    // Tracking form functionality
    document.addEventListener('DOMContentLoaded', function() {
        const trackingForm = document.getElementById('trackingForm');
        const trackingResults = document.getElementById('trackingResults');
        const trackAnotherBtn = document.getElementById('trackAnother');
        const trackingNumberInput = document.getElementById('trackingNumber');
        
        // Check if we have a tracking number in URL
        const urlParams = new URLSearchParams(window.location.search);
        const trackingNumberFromUrl = urlParams.get('tracking_number');
        
        if (trackingNumberFromUrl) {
            trackingNumberInput.value = trackingNumberFromUrl;
        } else {
            // Sample tracking numbers for demo
            const sampleTrackingNumbers = [
                'GSF789456123',
                'GSF123456789',
                'GSF987654321',
                'GSF456123789'
            ];
            
            // Set a sample tracking number as placeholder
            trackingNumberInput.value = sampleTrackingNumbers[0];
        }
        
        if (trackingForm) {
            trackingForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const trackingNumber = trackingNumberInput.value.trim();
                
                if (trackingNumber) {
                    // Show tracking results with animation
                    if (trackingResults) {
                        trackingResults.classList.add('show');
                        trackingResults.scrollIntoView({ behavior: 'smooth' });
                    }
                    
                    // Simulate loading state
                    const submitBtn = trackingForm.querySelector('button[type="submit"]');
                    const originalText = submitBtn.innerHTML;
                    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Tracking...';
                    submitBtn.disabled = true;
                    
                    // In a real application, you would make an AJAX request here
                    // For now, we'll simulate a delay and then submit the form
                    setTimeout(() => {
                        submitBtn.innerHTML = originalText;
                        submitBtn.disabled = false;
                        
                        // Submit the form
                        trackingForm.submit();
                    }, 1500);
                }
            });
        }
        
        if (trackAnotherBtn) {
            trackAnotherBtn.addEventListener('click', function() {
                if (trackingResults) {
                    trackingResults.classList.remove('show');
                }
                trackingNumberInput.value = '';
                trackingNumberInput.focus();
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
        }
        
        // Add click to copy tracking number functionality
        if (trackingNumberInput) {
            trackingNumberInput.addEventListener('click', function() {
                if (this.value && this.value.startsWith('GSF')) {
                    this.select();
                }
            });
        }
    });
</script>
@endpush