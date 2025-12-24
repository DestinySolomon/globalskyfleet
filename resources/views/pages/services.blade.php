@extends('layouts.app')

@section('title', 'GlobalSkyFleet - Services | International Courier & Logistics Solutions')

@section('description', 'Explore GlobalSkyFleet\'s comprehensive logistics services: International Express Delivery, Air Cargo, Sea Cargo, Door-to-Door Delivery, and Warehousing solutions for businesses worldwide.')

@section('keywords', 'logistics services, international express delivery, air freight, sea freight, door-to-door delivery, warehousing, cargo services, shipping solutions')

@section('content')
    <!-- Hero Section -->
    <section class="services-hero">
        <div class="container position-relative" style="z-index: 2;">
            <div class="row justify-content-center text-center">
                <div class="col-lg-8">
                    <p class="text-skyblue text-uppercase fw-semibold letter-spacing mb-3">OUR SERVICES</p>
                    <h1 class="display-4 fw-bold text-white mb-4">Tailored Logistics Solutions</h1>
                    <p class="text-white opacity-80 fs-5 mb-0 mx-auto" style="max-width: 700px;">
                        From express delivery to comprehensive warehousing, we offer a complete range of logistics services designed to meet your unique shipping needs.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section class="section-padding">
        <div class="container">
            <!-- Service 1: International Express Delivery -->
            <div class="service-card bg-white rounded-4">
                <div class="row g-4 g-lg-5 align-items-center p-4 p-lg-5">
                    <div class="col-lg-6">
                        <div class="service-icon">
                            <i class="ri-flashlight-line"></i>
                        </div>
                        <h2 class="display-6 fw-bold text-navy mb-4">International Express Delivery</h2>
                        <p class="text-muted fs-5 mb-4">
                            Our premium express service ensures your urgent shipments reach their destination with maximum speed and reliability. Perfect for time-sensitive documents, samples, and high-value goods that require immediate attention.
                        </p>
                        
                        <div class="service-specs">
                            <div class="spec-item">
                                <i class="ri-time-line spec-icon"></i>
                                <div>
                                    <div class="spec-label">Delivery Time</div>
                                    <div class="spec-value">1-3 business days</div>
                                </div>
                            </div>
                            <div class="spec-item">
                                <i class="ri-global-line spec-icon"></i>
                                <div>
                                    <div class="spec-label">Regions</div>
                                    <div class="spec-value">Worldwide coverage</div>
                                </div>
                            </div>
                            <div class="spec-item">
                                <i class="ri-weight-line spec-icon"></i>
                                <div>
                                    <div class="spec-label">Capacity</div>
                                    <div class="spec-value">Up to 70kg per package</div>
                                </div>
                            </div>
                            <div class="spec-item">
                                <i class="ri-map-pin-line spec-icon"></i>
                                <div>
                                    <div class="spec-label">Tracking</div>
                                    <div class="spec-value">Real-time GPS tracking</div>
                                </div>
                            </div>
                        </div>
                        
                        <a href="{{ route('quote') }}" class="btn btn-outline-orange rounded-pill px-4 py-2">
                            Request Quote <i class="ri-arrow-right-line ms-2"></i>
                        </a>
                    </div>
                    
                    <div class="col-lg-6">
                        <img src="https://readdy.ai/api/search-image?query=fast%20delivery%20courier%20holding%20express%20package%20running%20with%20speed%20motion%20blur%20modern%20urban%20background%20professional%20logistics%20service%20dynamic%20action%20shot%20bright%20daylight%20simple%20clean%20background&width=800&height=600&seq=service1&orientation=landscape" 
                             alt="International Express Delivery" 
                             class="service-image">
                    </div>
                </div>
            </div>
            
            <!-- Service 2: Air Cargo -->
            <div class="service-card bg-gray-50 rounded-4">
                <div class="row g-4 g-lg-5 align-items-center p-4 p-lg-5 flex-lg-row-reverse">
                    <div class="col-lg-6">
                        <div class="service-icon">
                            <i class="ri-plane-line"></i>
                        </div>
                        <h2 class="display-6 fw-bold text-navy mb-4">Air Cargo</h2>
                        <p class="text-muted fs-5 mb-4">
                            Comprehensive air freight solutions for businesses requiring fast international shipping. Our extensive network of airline partnerships ensures competitive rates and flexible scheduling for your cargo needs.
                        </p>
                        
                        <div class="service-specs">
                            <div class="spec-item">
                                <i class="ri-time-line spec-icon"></i>
                                <div>
                                    <div class="spec-label">Delivery Time</div>
                                    <div class="spec-value">2-5 business days</div>
                                </div>
                            </div>
                            <div class="spec-item">
                                <i class="ri-global-line spec-icon"></i>
                                <div>
                                    <div class="spec-label">Regions</div>
                                    <div class="spec-value">220+ countries</div>
                                </div>
                            </div>
                            <div class="spec-item">
                                <i class="ri-weight-line spec-icon"></i>
                                <div>
                                    <div class="spec-label">Capacity</div>
                                    <div class="spec-value">No weight limit</div>
                                </div>
                            </div>
                            <div class="spec-item">
                                <i class="ri-map-pin-line spec-icon"></i>
                                <div>
                                    <div class="spec-label">Tracking</div>
                                    <div class="spec-value">Milestone tracking</div>
                                </div>
                            </div>
                        </div>
                        
                        <a href="{{ route('quote') }}" class="btn btn-outline-orange rounded-pill px-4 py-2">
                            Request Quote <i class="ri-arrow-right-line ms-2"></i>
                        </a>
                    </div>
                    
                    <div class="col-lg-6">
                        <img src="https://readdy.ai/api/search-image?query=large%20cargo%20airplane%20being%20loaded%20with%20freight%20containers%20at%20modern%20airport%20facility%20professional%20aviation%20logistics%20operations%20blue%20sky%20background%20clean%20organized%20warehouse%20setting&width=800&height=600&seq=service2&orientation=landscape" 
                             alt="Air Cargo" 
                             class="service-image">
                    </div>
                </div>
            </div>
            
            <!-- Service 3: Sea Cargo -->
            <div class="service-card bg-white rounded-4">
                <div class="row g-4 g-lg-5 align-items-center p-4 p-lg-5">
                    <div class="col-lg-6">
                        <div class="service-icon">
                            <i class="ri-ship-line"></i>
                        </div>
                        <h2 class="display-6 fw-bold text-navy mb-4">Sea Cargo</h2>
                        <p class="text-muted fs-5 mb-4">
                            Cost-effective ocean freight services ideal for large volume shipments and non-urgent cargo. We offer both full container load and less than container load options with comprehensive port-to-port and door-to-door services.
                        </p>
                        
                        <div class="service-specs">
                            <div class="spec-item">
                                <i class="ri-time-line spec-icon"></i>
                                <div>
                                    <div class="spec-label">Delivery Time</div>
                                    <div class="spec-value">15-45 days</div>
                                </div>
                            </div>
                            <div class="spec-item">
                                <i class="ri-global-line spec-icon"></i>
                                <div>
                                    <div class="spec-label">Regions</div>
                                    <div class="spec-value">Major ports worldwide</div>
                                </div>
                            </div>
                            <div class="spec-item">
                                <i class="ri-weight-line spec-icon"></i>
                                <div>
                                    <div class="spec-label">Capacity</div>
                                    <div class="spec-value">Unlimited capacity</div>
                                </div>
                            </div>
                            <div class="spec-item">
                                <i class="ri-map-pin-line spec-icon"></i>
                                <div>
                                    <div class="spec-label">Tracking</div>
                                    <div class="spec-value">Container tracking</div>
                                </div>
                            </div>
                        </div>
                        
                        <a href="{{ route('quote') }}" class="btn btn-outline-orange rounded-pill px-4 py-2">
                            Request Quote <i class="ri-arrow-right-line ms-2"></i>
                        </a>
                    </div>
                    
                    <div class="col-lg-6">
                        <img src="https://readdy.ai/api/search-image?query=massive%20container%20ship%20loaded%20with%20colorful%20shipping%20containers%20sailing%20on%20calm%20ocean%20waters%20modern%20maritime%20logistics%20international%20trade%20blue%20sky%20simple%20clean%20background&width=800&height=600&seq=service3&orientation=landscape" 
                             alt="Sea Cargo" 
                             class="service-image">
                    </div>
                </div>
            </div>
            
            <!-- Service 4: Door-to-Door Delivery -->
            <div class="service-card bg-gray-50 rounded-4">
                <div class="row g-4 g-lg-5 align-items-center p-4 p-lg-5 flex-lg-row-reverse">
                    <div class="col-lg-6">
                        <div class="service-icon">
                            <i class="ri-truck-line"></i>
                        </div>
                        <h2 class="display-6 fw-bold text-navy mb-4">Door-to-Door Delivery</h2>
                        <p class="text-muted fs-5 mb-4">
                            Complete end-to-end logistics solution that handles every aspect of your shipment from pickup to final delivery. Our dedicated team manages customs clearance, documentation, and last-mile delivery for a seamless experience.
                        </p>
                        
                        <div class="service-specs">
                            <div class="spec-item">
                                <i class="ri-time-line spec-icon"></i>
                                <div>
                                    <div class="spec-label">Delivery Time</div>
                                    <div class="spec-value">3-7 business days</div>
                                </div>
                            </div>
                            <div class="spec-item">
                                <i class="ri-global-line spec-icon"></i>
                                <div>
                                    <div class="spec-label">Regions</div>
                                    <div class="spec-value">Domestic & international</div>
                                </div>
                            </div>
                            <div class="spec-item">
                                <i class="ri-weight-line spec-icon"></i>
                                <div>
                                    <div class="spec-label">Capacity</div>
                                    <div class="spec-value">Flexible options</div>
                                </div>
                            </div>
                            <div class="spec-item">
                                <i class="ri-map-pin-line spec-icon"></i>
                                <div>
                                    <div class="spec-label">Tracking</div>
                                    <div class="spec-value">Full visibility</div>
                                </div>
                            </div>
                        </div>
                        
                        <a href="{{ route('quote') }}" class="btn btn-outline-orange rounded-pill px-4 py-2">
                            Request Quote <i class="ri-arrow-right-line ms-2"></i>
                        </a>
                    </div>
                    
                    <div class="col-lg-6">
                        <img src="https://readdy.ai/api/search-image?query=professional%20delivery%20driver%20in%20uniform%20handing%20package%20to%20customer%20at%20residential%20doorstep%20friendly%20service%20modern%20delivery%20van%20in%20background%20daytime%20simple%20clean%20suburban%20setting&width=800&height=600&seq=service4&orientation=landscape" 
                             alt="Door-to-Door Delivery" 
                             class="service-image">
                    </div>
                </div>
            </div>
            
            <!-- Service 5: Warehousing & Logistics -->
            <div class="service-card bg-white rounded-4">
                <div class="row g-4 g-lg-5 align-items-center p-4 p-lg-5">
                    <div class="col-lg-6">
                        <div class="service-icon">
                            <i class="ri-building-line"></i>
                        </div>
                        <h2 class="display-6 fw-bold text-navy mb-4">Warehousing & Logistics</h2>
                        <p class="text-muted fs-5 mb-4">
                            State-of-the-art warehousing facilities with advanced inventory management systems. We provide secure storage, order fulfillment, distribution services, and supply chain optimization to streamline your operations.
                        </p>
                        
                        <div class="service-specs">
                            <div class="spec-item">
                                <i class="ri-time-line spec-icon"></i>
                                <div>
                                    <div class="spec-label">Delivery Time</div>
                                    <div class="spec-value">On-demand</div>
                                </div>
                            </div>
                            <div class="spec-item">
                                <i class="ri-global-line spec-icon"></i>
                                <div>
                                    <div class="spec-label">Regions</div>
                                    <div class="spec-value">Strategic locations</div>
                                </div>
                            </div>
                            <div class="spec-item">
                                <i class="ri-weight-line spec-icon"></i>
                                <div>
                                    <div class="spec-label">Capacity</div>
                                    <div class="spec-value">Bulk storage</div>
                                </div>
                            </div>
                            <div class="spec-item">
                                <i class="ri-map-pin-line spec-icon"></i>
                                <div>
                                    <div class="spec-label">Tracking</div>
                                    <div class="spec-value">Inventory management</div>
                                </div>
                            </div>
                        </div>
                        
                        <a href="{{ route('quote') }}" class="btn btn-outline-orange rounded-pill px-4 py-2">
                            Request Quote <i class="ri-arrow-right-line ms-2"></i>
                        </a>
                    </div>
                    
                    <div class="col-lg-6">
                        <img src="https://readdy.ai/api/search-image?query=modern%20warehouse%20interior%20with%20organized%20shelving%20systems%20stacked%20boxes%20and%20packages%20efficient%20logistics%20facility%20bright%20lighting%20clean%20organized%20space%20professional%20storage%20operations&width=800&height=600&seq=service5&orientation=landscape" 
                             alt="Warehousing & Logistics" 
                             class="service-image">
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
                    <h2 class="display-5 fw-bold text-white mb-4">Ready to Get Started?</h2>
                    <p class="text-white opacity-90 fs-5 mb-5">
                        Contact our logistics experts today to discuss your shipping needs and receive a customized solution.
                    </p>
                    <div class="d-flex flex-column flex-sm-row justify-content-center gap-3">
                        <a href="{{ route('quote') }}" class="btn btn-orange btn-lg rounded-pill px-5 py-3 shadow">
                            Get a Quote
                        </a>
                        <a href="{{ route('contact') }}" class="btn btn-outline-white btn-lg rounded-pill px-5 py-3">
                            Contact Us
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
    // Simple animation for service cards on scroll
    document.addEventListener('DOMContentLoaded', function() {
        const serviceCards = document.querySelectorAll('.service-card');
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, {
            threshold: 0.1
        });
        
        serviceCards.forEach(card => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(30px)';
            card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            observer.observe(card);
        });
    });
</script>
@endpush