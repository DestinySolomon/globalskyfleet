@extends('layouts.app')

@section('title', 'About GlobalSkyFleet | Fast, Secure, Worldwide Delivery')

@section('description', 'Learn about GlobalSkyFleet\'s mission, vision, and commitment to providing fast, secure international courier and logistics services to 220+ countries worldwide.')

@section('keywords', 'about GlobalSkyFleet, logistics company, international shipping, global delivery, company mission, logistics network')

@section('content')
    <!-- Hero Section -->
  
    <section class="about-hero">
        <img src="https://readdy.ai/api/search-image?query=modern%20logistics%20warehouse%20team%20of%20diverse%20professionals%20working%20together%20with%20cargo%20operations%20in%20background%20bright%20professional%20corporate%20environment%20teamwork%20collaboration%20global%20business&width=1920&height=800&seq=about1&orientation=landscape" 
             alt="About GlobalSkyFleet" 
             class="about-hero-bg">
        <div class="about-hero-overlay"></div>
        <div class="container position-relative" style="z-index: 3;">
            <div class="row justify-content-center text-center">
                <div class="col-lg-8">
                    <h1 class="display-4 fw-bold text-white mb-4">About GlobalSkyFleet</h1>
                    <p class="text-white opacity-90 fs-5 mb-0">
                        Connecting the world, one shipment at a time
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Our Story Section -->
    <section class="section-py-20 bg-white">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-6">
                    <p class="text-skyblue text-uppercase fw-semibold letter-spacing mb-3">OUR STORY</p>
                    <h2 class="display-5 fw-bold text-navy mb-4">Leading Global Logistics Since 2005</h2>
                    <p class="text-muted fs-5 mb-4">
                        GlobalSkyFleet was founded with a simple mission: to make international shipping fast, secure, and accessible to businesses of all sizes. What started as a small courier service has grown into a global logistics powerhouse serving 220+ countries.
                    </p>
                    <p class="text-muted fs-5">
                        Today, we handle over 10 million shipments annually, combining cutting-edge technology with personalized service to deliver excellence at every touchpoint. Our commitment to innovation and customer satisfaction has made us a trusted partner for thousands of businesses worldwide.
                    </p>
                </div>
                <div class="col-lg-6">
                    <img src="https://readdy.ai/api/search-image?query=modern%20cargo%20airplane%20at%20international%20airport%20with%20ground%20crew%20and%20logistics%20operations%20professional%20aviation%20photography%20golden%20hour%20lighting%20global%20shipping%20network%20infrastructure&width=800&height=600&seq=about2&orientation=landscape" 
                         alt="Our Operations" 
                         class="img-fluid rounded-4 shadow-lg">
                </div>
            </div>
        </div>
    </section>

    <!-- Mission & Vision Section -->
    <section class="section-py-20 bg-gray-50">
        <div class="container">
            <div class="row justify-content-center text-center mb-5">
                <div class="col-lg-8">
                    <h2 class="display-5 fw-bold text-navy mb-4">Mission & Vision</h2>
                </div>
            </div>
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="mission-vision-card">
                        <div class="mb-4">
                            <div class="d-inline-flex align-items-center justify-content-center rounded-3 p-3" style="background: linear-gradient(to bottom right, var(--skyblue), var(--navy)); width: 64px; height: 64px;">
                                <i class="ri-compass-3-line text-white fs-3"></i>
                            </div>
                        </div>
                        <h3 class="h2 fw-bold text-navy mb-3">Our Mission</h3>
                        <p class="text-muted">
                            To provide fast, secure, and reliable logistics solutions that empower businesses to reach global markets. We strive to exceed customer expectations through innovation, transparency, and unwavering commitment to service excellence.
                        </p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mission-vision-card">
                        <div class="mb-4">
                            <div class="d-inline-flex align-items-center justify-content-center rounded-3 p-3" style="background: linear-gradient(to bottom right, var(--orange), var(--orange-dark)); width: 64px; height: 64px;">
                                <i class="ri-eye-line text-white fs-3"></i>
                            </div>
                        </div>
                        <h3 class="h2 fw-bold text-navy mb-3">Our Vision</h3>
                        <p class="text-muted">
                            To be the world's most trusted logistics partner, recognized for our innovation, sustainability, and positive impact on global trade. We envision a future where distance is no barrier to business success.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Global Presence Section -->
    <section class="section-py-20 bg-white">
        <div class="container">
            <div class="row justify-content-center text-center mb-5">
                <div class="col-lg-8">
                    <p class="text-skyblue text-uppercase fw-semibold letter-spacing mb-3">WORLDWIDE NETWORK</p>
                    <h2 class="display-5 fw-bold text-navy mb-4">Global Presence</h2>
                    <p class="text-muted fs-5 mb-4">
                        With strategic offices and distribution centers across six continents, we're always close to your business.
                    </p>
                </div>
            </div>
            
            <div class="row mb-5">
                <div class="col-12">
                    <img src="https://readdy.ai/api/search-image?query=world%20map%20with%20glowing%20orange%20location%20pins%20marking%20major%20cities%20connected%20by%20blue%20network%20lines%20global%20logistics%20hubs%20international%20shipping%20routes%20modern%20minimalist%20design%20professional%20visualization&width=1400&height=600&seq=about3&orientation=landscape" 
                         alt="Global Network" 
                         class="img-fluid rounded-4 shadow-lg">
                </div>
            </div>
            
            <div class="row g-4">
                <div class="col-md-6 col-lg-4">
                    <div class="office-card">
                        <div class="d-flex align-items-start">
                            <i class="ri-map-pin-line text-skyblue fs-4 me-3 mt-1"></i>
                            <div>
                                <h4 class="h5 fw-bold text-navy mb-1">New York, USA</h4>
                                <p class="text-muted small mb-0">350 Fifth Avenue, NY 10118</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="office-card">
                        <div class="d-flex align-items-start">
                            <i class="ri-map-pin-line text-skyblue fs-4 me-3 mt-1"></i>
                            <div>
                                <h4 class="h5 fw-bold text-navy mb-1">London, UK</h4>
                                <p class="text-muted small mb-0">1 Canada Square, E14 5AB</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="office-card">
                        <div class="d-flex align-items-start">
                            <i class="ri-map-pin-line text-skyblue fs-4 me-3 mt-1"></i>
                            <div>
                                <h4 class="h5 fw-bold text-navy mb-1">Dubai, UAE</h4>
                                <p class="text-muted small mb-0">Sheikh Zayed Road, Dubai</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="office-card">
                        <div class="d-flex align-items-start">
                            <i class="ri-map-pin-line text-skyblue fs-4 me-3 mt-1"></i>
                            <div>
                                <h4 class="h5 fw-bold text-navy mb-1">Singapore, Singapore</h4>
                                <p class="text-muted small mb-0">1 Marina Boulevard, 018989</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="office-card">
                        <div class="d-flex align-items-start">
                            <i class="ri-map-pin-line text-skyblue fs-4 me-3 mt-1"></i>
                            <div>
                                <h4 class="h5 fw-bold text-navy mb-1">Hong Kong, China</h4>
                                <p class="text-muted small mb-0">1 Connaught Place, Central</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="office-card">
                        <div class="d-flex align-items-start">
                            <i class="ri-map-pin-line text-skyblue fs-4 me-3 mt-1"></i>
                            <div>
                                <h4 class="h5 fw-bold text-navy mb-1">Sydney, Australia</h4>
                                <p class="text-muted small mb-0">1 Macquarie Place, NSW 2000</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Safety & Compliance Section -->
    <section class="section-py-20 bg-gray-50">
        <div class="container">
            <div class="row justify-content-center text-center mb-5">
                <div class="col-lg-8">
                    <p class="text-skyblue text-uppercase fw-semibold letter-spacing mb-3">CERTIFICATIONS</p>
                    <h2 class="display-5 fw-bold text-navy mb-4">Safety & Compliance</h2>
                    <p class="text-muted fs-5 mb-4">
                        We maintain the highest industry standards and certifications to ensure your shipments are handled with care and professionalism.
                    </p>
                </div>
            </div>
            
            <div class="row g-4">
                <div class="col-md-6 col-lg-4">
                    <div class="certification-card">
                        <div class="certification-icon">
                            <i class="ri-shield-check-line"></i>
                        </div>
                        <h4 class="h5 fw-bold text-navy mb-2">ISO 9001:2015</h4>
                        <p class="text-muted small mb-0">Quality Management</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="certification-card">
                        <div class="certification-icon">
                            <i class="ri-plane-line"></i>
                        </div>
                        <h4 class="h5 fw-bold text-navy mb-2">IATA Certified</h4>
                        <p class="text-muted small mb-0">Air Transport</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="certification-card">
                        <div class="certification-icon">
                            <i class="ri-ship-line"></i>
                        </div>
                        <h4 class="h5 fw-bold text-navy mb-2">IMO Compliant</h4>
                        <p class="text-muted small mb-0">Maritime Safety</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="certification-card">
                        <div class="certification-icon">
                            <i class="ri-lock-line"></i>
                        </div>
                        <h4 class="h5 fw-bold text-navy mb-2">AEO Certified</h4>
                        <p class="text-muted small mb-0">Customs Security</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="certification-card">
                        <div class="certification-icon">
                            <i class="ri-leaf-line"></i>
                        </div>
                        <h4 class="h5 fw-bold text-navy mb-2">ISO 14001</h4>
                        <p class="text-muted small mb-0">Environmental</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="certification-card">
                        <div class="certification-icon">
                            <i class="ri-shield-star-line"></i>
                        </div>
                        <h4 class="h5 fw-bold text-navy mb-2">TAPA FSR</h4>
                        <p class="text-muted small mb-0">Freight Security</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="section-py-20 bg-navy-light">
        <div class="container">
            <div class="row justify-content-center text-center">
                <div class="col-lg-8">
                    <h2 class="display-4 fw-bold text-white mb-4">Join Thousands of Satisfied Customers</h2>
                    <p class="text-white opacity-90 fs-5 mb-5">
                        Experience the GlobalSkyFleet difference. Fast, secure, and reliable logistics solutions for your business.
                    </p>
                    <div class="d-flex flex-column flex-sm-row justify-content-center gap-4">
                        <a href="{{ route('quote') }}" class="btn btn-orange btn-lg rounded-pill px-5 py-3 shadow-lg">
                            Get Started Today
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
    // Animation for cards on scroll
    document.addEventListener('DOMContentLoaded', function() {
        const cards = document.querySelectorAll('.mission-vision-card, .certification-card, .office-card');
        
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
        
        cards.forEach(card => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            observer.observe(card);
        });
    });
</script>
@endpush