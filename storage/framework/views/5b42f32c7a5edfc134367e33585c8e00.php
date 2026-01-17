

<?php $__env->startSection('title', 'Express Delivery Services | GlobalSkyFleet'); ?>
<?php $__env->startSection('description', 'Priority express delivery for urgent shipments. Same-day and next-day delivery options available.'); ?>
<?php $__env->startSection('keywords', 'express delivery, same-day delivery, next-day delivery, priority shipping, urgent courier'); ?>

<?php $__env->startSection('content'); ?>
    <!-- Hero Section -->
    <section class="services-hero">
        <div class="container position-relative" style="z-index: 2;">
            <div class="row justify-content-center text-center">
                <div class="col-lg-8">
                    <p class="text-skyblue text-uppercase fw-semibold letter-spacing mb-3">EXPRESS DELIVERY</p>
                    <h1 class="display-4 fw-bold text-white mb-4">Priority Express Services</h1>
                    <p class="text-white opacity-80 fs-5 mb-0 mx-auto" style="max-width: 700px;">
                        Lightning-fast delivery for your most urgent shipments. Same-day and next-day options available for critical documents and packages.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Service Overview -->
    <section class="section-padding bg-white">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-6">
                    <div class="service-icon">
                        <i class="ri-flashlight-line"></i>
                    </div>
                    <h2 class="display-5 fw-bold text-navy mb-4">Ultra-Fast Express Delivery</h2>
                    <p class="text-muted fs-5 mb-4">
                        When time is critical, our express delivery service ensures your shipments reach their destination with maximum speed and reliability. Perfect for urgent documents, medical supplies, and time-sensitive business materials.
                    </p>
                    
                    <div class="express-features mb-4">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="feature-item d-flex align-items-center mb-3">
                                    <i class="ri-time-line text-orange fs-4 me-3"></i>
                                    <div>
                                        <div class="fw-bold">Same-Day Delivery</div>
                                        <small class="text-muted">Within 4-8 hours</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="feature-item d-flex align-items-center mb-3">
                                    <i class="ri-calendar-check-line text-orange fs-4 me-3"></i>
                                    <div>
                                        <div class="fw-bold">Next-Day Delivery</div>
                                        <small class="text-muted">Before 10 AM</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex gap-3">
                        <a href="<?php echo e(route('quote')); ?>" class="btn btn-orange rounded-pill px-4 py-2">
                            Book Express Delivery
                        </a>
                        <a href="tel:+15551234567" class="btn btn-outline-navy rounded-pill px-4 py-2">
                            <i class="ri-phone-line me-2"></i> Urgent Pickup
                        </a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <img src="<?php echo e(asset('images/express-delivery.jpg')); ?>" 
                         alt="Express Delivery Service" 
                         class="img-fluid rounded-4 shadow-lg">
                </div>
            </div>
        </div>
    </section>

    <!-- Service Tiers -->
    <section class="section-padding bg-gray-50">
        <div class="container">
            <div class="row justify-content-center text-center mb-5">
                <div class="col-lg-8">
                    <h2 class="display-5 fw-bold text-navy mb-4">Express Service Tiers</h2>
                    <p class="text-muted fs-5">Choose the speed that matches your urgency</p>
                </div>
            </div>
            
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="service-tier-card">
                        <div class="tier-header bg-skyblue text-white p-4 rounded-top">
                            <h4 class="h5 fw-bold mb-2">Same-Day</h4>
                            <div class="tier-price">From $49.99</div>
                        </div>
                        <div class="tier-body p-4">
                            <ul class="list-unstyled">
                                <li class="mb-3"><i class="ri-check-line text-skyblue me-2"></i> Delivery within 4-8 hours</li>
                                <li class="mb-3"><i class="ri-check-line text-skyblue me-2"></i> Priority handling</li>
                                <li class="mb-3"><i class="ri-check-line text-skyblue me-2"></i> Real-time GPS tracking</li>
                                <li class="mb-3"><i class="ri-check-line text-skyblue me-2"></i> Dedicated courier</li>
                                <li><i class="ri-check-line text-skyblue me-2"></i> Signature confirmation</li>
                            </ul>
                            <a href="<?php echo e(route('quote')); ?>" class="btn btn-outline-skyblue w-100 rounded-pill">
                                Book Now
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="service-tier-card popular">
                        <div class="popular-badge">MOST POPULAR</div>
                        <div class="tier-header bg-orange text-white p-4 rounded-top">
                            <h4 class="h5 fw-bold mb-2">Next-Day AM</h4>
                            <div class="tier-price">From $29.99</div>
                        </div>
                        <div class="tier-body p-4">
                            <ul class="list-unstyled">
                                <li class="mb-3"><i class="ri-check-line text-orange me-2"></i> Delivery before 10 AM</li>
                                <li class="mb-3"><i class="ri-check-line text-orange me-2"></i> Next business day</li>
                                <li class="mb-3"><i class="ri-check-line text-orange me-2"></i> Live tracking updates</li>
                                <li class="mb-3"><i class="ri-check-line text-orange me-2"></i> Email notifications</li>
                                <li><i class="ri-check-line text-orange me-2"></i> Photo proof of delivery</li>
                            </ul>
                            <a href="<?php echo e(route('quote')); ?>" class="btn btn-orange w-100 rounded-pill">
                                Book Now
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="service-tier-card">
                        <div class="tier-header bg-navy text-white p-4 rounded-top">
                            <h4 class="h5 fw-bold mb-2">Next-Day PM</h4>
                            <div class="tier-price">From $19.99</div>
                        </div>
                        <div class="tier-body p-4">
                            <ul class="list-unstyled">
                                <li class="mb-3"><i class="ri-check-line text-navy me-2"></i> Delivery before 6 PM</li>
                                <li class="mb-3"><i class="ri-check-line text-navy me-2"></i> Next business day</li>
                                <li class="mb-3"><i class="ri-check-line text-navy me-2"></i> Standard tracking</li>
                                <li class="mb-3"><i class="ri-check-line text-navy me-2"></i> SMS notifications</li>
                                <li><i class="ri-check-line text-navy me-2"></i> Delivery confirmation</li>
                            </ul>
                            <a href="<?php echo e(route('quote')); ?>" class="btn btn-outline-navy w-100 rounded-pill">
                                Book Now
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Service Details -->
    <section class="section-padding bg-white">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-6">
                    <h3 class="h4 fw-bold text-navy mb-4">Express Delivery Features</h3>
                    
                    <div class="feature-detail mb-4">
                        <div class="d-flex align-items-start mb-3">
                            <i class="ri-shield-check-line text-skyblue fs-4 me-3 mt-1"></i>
                            <div>
                                <h5 class="fw-bold text-navy mb-1">Secure Handling</h5>
                                <p class="text-muted small">Tamper-evident packaging and chain of custody tracking for sensitive documents.</p>
                            </div>
                        </div>
                        
                        <div class="d-flex align-items-start mb-3">
                            <i class="ri-map-pin-line text-skyblue fs-4 me-3 mt-1"></i>
                            <div>
                                <h5 class="fw-bold text-navy mb-1">Real-Time Tracking</h5>
                                <p class="text-muted small">Live GPS tracking with ETAs and delivery notifications via email/SMS.</p>
                            </div>
                        </div>
                        
                        <div class="d-flex align-items-start mb-3">
                            <i class="ri-phone-line text-skyblue fs-4 me-3 mt-1"></i>
                            <div>
                                <h5 class="fw-bold text-navy mb-1">24/7 Support</h5>
                                <p class="text-muted small">Round-the-clock customer service for urgent delivery inquiries and support.</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-6">
                    <h3 class="h4 fw-bold text-navy mb-4">Eligible Items</h3>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="eligible-item mb-3">
                                <i class="ri-file-text-line text-success me-2"></i>
                                <span>Legal Documents</span>
                            </div>
                            <div class="eligible-item mb-3">
                                <i class="ri-medicine-bottle-line text-success me-2"></i>
                                <span>Medical Supplies</span>
                            </div>
                            <div class="eligible-item mb-3">
                                <i class="ri-computer-line text-success me-2"></i>
                                <span>Electronics</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="eligible-item mb-3">
                                <i class="ri-gift-line text-success me-2"></i>
                                <span>Gifts & Samples</span>
                            </div>
                            <div class="eligible-item mb-3">
                                <i class="ri-tools-line text-success me-2"></i>
                                <span>Spare Parts</span>
                            </div>
                            <div class="eligible-item mb-3">
                                <i class="ri-passport-line text-success me-2"></i>
                                <span>Passports & Visas</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="alert alert-warning mt-4">
                        <i class="ri-alert-line me-2"></i>
                        <strong>Note:</strong> Maximum weight for express delivery is 70kg. Restricted items apply.
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="services-cta">
        <div class="container">
            <div class="row justify-content-center text-center">
                <div class="col-lg-8">
                    <h2 class="display-5 fw-bold text-white mb-4">Need Urgent Delivery?</h2>
                    <p class="text-white opacity-90 fs-5 mb-5">
                        Book your express delivery now. Our couriers are standing by for immediate pickup.
                    </p>
                    <div class="d-flex flex-column flex-sm-row justify-content-center gap-3">
                        <a href="<?php echo e(route('quote')); ?>" class="btn btn-orange btn-lg rounded-pill px-5 py-3 shadow">
                            Book Express Now
                        </a>
                        <a href="tel:+15551234567" class="btn btn-outline-white btn-lg rounded-pill px-5 py-3">
                            <i class="ri-phone-line me-2"></i> Call for Emergency
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .express-features .feature-item {
        background: #f8fafc;
        padding: 1rem;
        border-radius: 0.75rem;
    }
    .service-tier-card {
        background: white;
        border-radius: 1rem;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        position: relative;
        height: 100%;
    }
    .service-tier-card.popular {
        transform: scale(1.05);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }
    .popular-badge {
        position: absolute;
        top: -12px;
        right: 20px;
        background: var(--orange);
        color: white;
        padding: 0.25rem 1rem;
        border-radius: 2rem;
        font-size: 0.75rem;
        font-weight: 600;
        z-index: 2;
    }
    .tier-header {
        text-align: center;
    }
    .tier-price {
        font-size: 1.5rem;
        font-weight: 700;
    }
    .tier-body ul li {
        padding: 0.5rem 0;
        border-bottom: 1px solid #f0f0f0;
    }
    .tier-body ul li:last-child {
        border-bottom: none;
    }
    .eligible-item {
        padding: 0.5rem;
        background: #f0f9ff;
        border-radius: 0.5rem;
    }
    .bg-skyblue {
        background-color: var(--skyblue) !important;
    }
    .btn-outline-skyblue {
        border: 2px solid var(--skyblue);
        color: var(--skyblue);
    }
    .btn-outline-skyblue:hover {
        background-color: var(--skyblue);
        color: white;
    }
</style>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Dee\Desktop\General\globalskyfleet\resources\views/pages/services/express-delivery.blade.php ENDPATH**/ ?>