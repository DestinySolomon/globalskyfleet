

<?php $__env->startSection('title', 'GlobalSkyFleet - Track Shipment | Real-Time Package Tracking'); ?>

<?php $__env->startSection('description', 'Track your GlobalSkyFleet shipments in real-time. Enter your tracking number to get live updates on delivery status, location, and estimated arrival time.'); ?>

<?php $__env->startSection('keywords', 'track shipment, package tracking, real-time tracking, delivery status, courier tracking, shipment tracking, logistics tracking'); ?>

<?php $__env->startSection('content'); ?>
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
                        <form id="trackingForm" method="POST" action="<?php echo e(route('tracking.submit')); ?>">
                            <?php echo csrf_field(); ?>
                            <div class="mb-4">
                                <label for="trackingNumber" class="form-label fw-semibold text-navy mb-3">Tracking Number</label>
                                <input type="text" 
                                       class="form-control form-control-custom" 
                                       id="trackingNumber" 
                                       name="tracking_number"
                                       placeholder="Enter tracking number (e.g., GS12345678)" 
                                       value="<?php echo e(old('tracking_number')); ?>"
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
                    <?php if(isset($shipment) && $shipment): ?>
                        <div id="trackingResults" class="tracking-results show">
                            <div class="status-card mb-4">
                                <div class="row align-items-center">
                                    <div class="col-md-8">
                                        <h3 class="h4 fw-bold text-navy mb-2">Shipment #<?php echo e($shipment->tracking_number); ?></h3>
                                        <p class="text-muted mb-0">
                                            From: <?php echo e($shipment->senderAddress->city ?? 'Unknown'); ?> 
                                            → To: <?php echo e($shipment->recipientAddress->city ?? 'Unknown'); ?>

                                        </p>
                                    </div>
                                    <div class="col-md-4 text-md-end">
                                        <span class="badge bg-orange fs-6 px-3 py-2">
                                            <?php echo e(ucfirst(str_replace('_', ' ', $shipment->status))); ?>

                                        </span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row mb-4">
                                <div class="col-md-3">
                                    <div class="text-center p-3">
                                        <div class="text-skyblue fs-4 fw-bold"><?php echo e($shipment->weight ?? 'N/A'); ?></div>
                                        <div class="text-muted small">Weight</div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-center p-3">
                                        <div class="text-skyblue fs-4 fw-bold"><?php echo e($shipment->service->name ?? 'N/A'); ?></div>
                                        <div class="text-muted small">Service</div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-center p-3">
                                        <div class="text-skyblue fs-4 fw-bold"><?php echo e($shipment->estimated_delivery ? formatUserTime($shipment->estimated_delivery, 'M d') : 'TBD'); ?></div>
                                        <div class="text-muted small">Est. Delivery</div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-center p-3">
                                        <div class="text-skyblue fs-4 fw-bold"><?php echo e($shipment->statusHistory ? $shipment->statusHistory->count() : '0'); ?>/5</div>
                                        <div class="text-muted small">Progress</div>
                                    </div>
                                </div>
                            </div>
                            
                            <?php if($shipment->current_location): ?>
                            <div class="alert alert-info border-0 mb-4" style="background: linear-gradient(135deg, #0ea5e9, #06b6d4); color: white; border-radius: 12px;">
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <i class="ri-map-pin-2-line" style="font-size: 1.5rem;"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-1">Current Location</h6>
                                        <p class="mb-0 opacity-90"><?php echo e($shipment->current_location); ?></p>
                                        <?php if($shipment->has_coordinates): ?>
                                            <small class="opacity-75 d-block mt-1">
                                                Coordinates: <?php echo e(round($shipment->latitude, 4)); ?>, <?php echo e(round($shipment->longitude, 4)); ?>

                                            </small>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>

                            <!-- Map Container - Only show if coordinates exist -->
                            <?php if($shipment->has_coordinates): ?>
                            <div class="card border-0 shadow-sm mb-4">
                                <div class="card-header bg-white">
                                    <h5 class="mb-0">
                                        <i class="ri-map-2-line me-2"></i>Live Location Map
                                    </h5>
                                </div>
                                <div class="card-body p-0">
                                    <div id="trackingMap" style="height: 400px; width: 100%; border-radius: 0 0 8px 8px;"></div>
                                </div>
                                <div class="card-footer bg-white border-top py-2">
                                    <small class="text-muted">
                                        <i class="ri-information-line me-1"></i>
                                        Last updated: <?php echo e(formatUserTime($shipment->location_updated_at, 'M d, Y H:i:s') ?? 'Never'); ?>

                                    </small>
                                </div>
                            </div>
                            <?php elseif($shipment->current_location): ?>
                            <!-- Show placeholder if no coordinates yet -->
                            <div class="card border-0 shadow-sm mb-4">
                                <div class="card-header bg-white">
                                    <h5 class="mb-0">
                                        <i class="ri-map-2-line me-2"></i>Live Location Map
                                    </h5>
                                </div>
                                <div class="card-body text-center py-5">
                                    <div class="mb-3">
                                        <i class="ri-map-pin-line text-muted" style="font-size: 3rem;"></i>
                                    </div>
                                    <h6 class="text-muted mb-2">Location Map Not Available</h6>
                                    <p class="text-muted small mb-0">Live tracking coordinates will appear here when available.</p>
                                    <p class="text-muted small">Current location: <strong><?php echo e($shipment->current_location); ?></strong></p>
                                </div>
                            </div>
                            <?php endif; ?>
                            
                            <h4 class="h5 fw-bold text-navy mb-3">Shipment Timeline</h4>
                            <div class="timeline">
                                <?php if($shipment->statusHistory && $shipment->statusHistory->count() > 0): ?>
                                    <?php $__currentLoopData = $shipment->statusHistory; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $update): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="timeline-item <?php echo e($loop->first ? 'completed active' : ($loop->index < 2 ? 'completed' : '')); ?>">
                                            <div class="d-flex justify-content-between">
                                                <h5 class="h6 fw-bold text-navy mb-1"><?php echo e(ucfirst(str_replace('_', ' ', $update->status))); ?></h5>
                                                <span class="text-muted small"><?php echo e(formatUserTime($update->scan_datetime, 'M d, Y H:i')); ?></span>
                                            </div>
                                            <p class="text-muted small mb-0"><?php echo e($update->description ?? 'Status Update'); ?></p>
                                            <?php if($update->location): ?>
                                                <small class="text-muted">
                                                    <i class="ri-map-pin-line me-1"></i><?php echo e($update->location); ?>

                                                </small>
                                            <?php endif; ?>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php else: ?>
                                    <div class="text-center py-4">
                                        <i class="ri-time-line fs-1 text-muted"></i>
                                        <p class="mt-3 text-muted">No tracking updates yet</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="mt-4 text-center">
                                <button id="trackAnother" class="btn btn-outline-orange rounded-pill px-4">
                                    <i class="ri-refresh-line me-2"></i> Track Another Shipment
                                </button>
                            </div>
                        </div>
                    <?php else: ?>
                        <!-- Empty tracking results template (hidden by default) -->
                        <div id="trackingResults" class="tracking-results">
                            <!-- Results will be shown here via JavaScript -->
                        </div>
                    <?php endif; ?>
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
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
    /* Pulsing marker for map */
    .marker-pulse {
        width: 30px;
        height: 30px;
        position: relative;
    }

    .pulse-dot {
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background-color: #0a2463; /* Your brand color */
        position: absolute;
        top: 5px;
        left: 5px;
        box-shadow: 0 0 0 0 rgba(10, 36, 99, 1);
        animation: pulse-blue 2s infinite;
        z-index: 1;
    }

    .pulse-dot::after {
        content: '';
        position: absolute;
        top: -5px;
        left: -5px;
        right: -5px;
        bottom: -5px;
        border-radius: 50%;
        background-color: rgba(10, 36, 99, 0.3);
        z-index: 0;
    }

    @keyframes pulse-blue {
        0% {
            transform: scale(0.95);
            box-shadow: 0 0 0 0 rgba(10, 36, 99, 0.7);
        }
        70% {
            transform: scale(1);
            box-shadow: 0 0 0 10px rgba(10, 36, 99, 0);
        }
        100% {
            transform: scale(0.95);
            box-shadow: 0 0 0 0 rgba(10, 36, 99, 0);
        }
    }

    /* Map container styling */
    #trackingMap {
        min-height: 400px;
    }

    .mapboxgl-popup {
        max-width: 300px;
    }

    .mapboxgl-popup-content {
        padding: 15px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
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
                if (this.value) {
                    this.select();
                }
            });
        }

        // Mapbox Tracking Map - Only initialize if we have a shipment with coordinates
        <?php if(isset($shipment) && $shipment && $shipment->has_coordinates): ?>
        // Initialize Mapbox
        mapboxgl.accessToken = '<?php echo e(config("mapbox.access_token")); ?>';
        
        // Check if map container exists
        const mapContainer = document.getElementById('trackingMap');
        if (!mapContainer) {
            console.error('Map container not found');
            return;
        }
        
        try {
            // Create map
            const map = new mapboxgl.Map({
                container: 'trackingMap',
                style: 'mapbox://styles/mapbox/streets-v12',
                center: [<?php echo e($shipment->longitude); ?>, <?php echo e($shipment->latitude); ?>],
                zoom: 12
            });
            
            // Wait for map to load
            map.on('load', function() {
                // Add navigation controls
                map.addControl(new mapboxgl.NavigationControl());
                
                // Add marker with pulsing effect
                const el = document.createElement('div');
                el.className = 'marker-pulse';
                el.innerHTML = '<div class="pulse-dot"></div>';
                
                // Create marker with popup
                const marker = new mapboxgl.Marker(el)
                    .setLngLat([<?php echo e($shipment->longitude); ?>, <?php echo e($shipment->latitude); ?>])
                    .addTo(map);
                
                // Create and add popup
                const popup = new mapboxgl.Popup({ offset: 25 })
                    .setHTML(`
                        <div class="p-2">
                            <h6 class="mb-1 fw-bold">Current Location</h6>
                            <p class="mb-1 small"><?php echo e($shipment->current_location); ?></p>
                            <p class="mb-0 small text-muted">Tracking: <?php echo e($shipment->tracking_number); ?></p>
                            <p class="mb-0 small text-muted">Coordinates: <?php echo e(round($shipment->latitude, 4)); ?>, <?php echo e(round($shipment->longitude, 4)); ?></p>
                            <p class="mb-0 small text-muted mt-1">Status: <?php echo e(ucfirst(str_replace('_', ' ', $shipment->status))); ?></p>
                        </div>
                    `);
                
                marker.setPopup(popup);
                
                // Add geolocate control (optional)
                map.addControl(new mapboxgl.GeolocateControl({
                    positionOptions: {
                        enableHighAccuracy: true
                    },
                    trackUserLocation: true,
                    showUserLocation: false
                }));
                
                console.log('Map initialized successfully for tracking: <?php echo e($shipment->tracking_number); ?>');
            });
            
            // Handle map errors
            map.on('error', function(e) {
                console.error('Map error:', e.error);
                document.getElementById('trackingMap').innerHTML = `
                    <div class="alert alert-warning m-3">
                        <i class="ri-error-warning-line me-2"></i>
                        Map could not be loaded. Please try again later.
                    </div>
                `;
            });
            
        } catch (error) {
            console.error('Map initialization error:', error);
            document.getElementById('trackingMap').innerHTML = `
                <div class="alert alert-warning m-3">
                    <i class="ri-error-warning-line me-2"></i>
                    Map could not be loaded. Please check your internet connection and try again.
                </div>
            `;
        }
        <?php endif; ?>
    });
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Dee\Desktop\globalsky-final\globalskyfleet_fixed\resources\views/pages/tracking.blade.php ENDPATH**/ ?>