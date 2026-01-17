@extends('layouts.app')

@section('title', 'Air Freight Services | GlobalSkyFleet')
@section('description', 'Fast and reliable air freight solutions for time-sensitive shipments. International air cargo services with real-time tracking.')
@section('keywords', 'air freight, air cargo, international air shipping, air freight services, express air freight')

@section('content')
    <!-- Hero Section -->
    <section class="services-hero">
        <div class="container position-relative" style="z-index: 2;">
            <div class="row justify-content-center text-center">
                <div class="col-lg-8">
                    <p class="text-skyblue text-uppercase fw-semibold letter-spacing mb-3">AIR FREIGHT</p>
                    <h1 class="display-4 fw-bold text-white mb-4">Global Air Freight Solutions</h1>
                    <p class="text-white opacity-80 fs-5 mb-0 mx-auto" style="max-width: 700px;">
                        Fast, reliable air cargo services for time-sensitive shipments across 220+ countries. Priority handling and real-time tracking.
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
                    <img src="{{ asset('images/air-freight.jpg') }}" 
                         alt="Air Freight Operations" 
                         class="img-fluid rounded-4 shadow-lg">
                </div>
                <div class="col-lg-6">
                    <div class="service-icon">
                        <i class="ri-plane-line"></i>
                    </div>
                    <h2 class="display-5 fw-bold text-navy mb-4">Premium Air Cargo Services</h2>
                    <p class="text-muted fs-5 mb-4">
                        Our comprehensive air freight solutions ensure your shipments reach their destination with maximum speed and reliability. Perfect for time-sensitive goods, perishables, and high-value cargo.
                    </p>
                    <div class="row">
                        <div class="col-md-6">
                            <ul class="list-unstyled">
                                <li class="mb-3"><i class="ri-check-line text-skyblue me-2"></i>Express Air Freight</li>
                                <li class="mb-3"><i class="ri-check-line text-skyblue me-2"></i>Standard Air Cargo</li>
                                <li class="mb-3"><i class="ri-check-line text-skyblue me-2"></i>Charter Services</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <ul class="list-unstyled">
                                <li class="mb-3"><i class="ri-check-line text-skyblue me-2"></i>Perishables Handling</li>
                                <li class="mb-3"><i class="ri-check-line text-skyblue me-2"></i>Dangerous Goods</li>
                                <li class="mb-3"><i class="ri-check-line text-skyblue me-2"></i>Oversized Cargo</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Key Features -->
    <section class="section-padding bg-gray-50">
        <div class="container">
            <div class="row justify-content-center text-center mb-5">
                <div class="col-lg-8">
                    <h2 class="display-5 fw-bold text-navy mb-4">Why Choose Our Air Freight Services</h2>
                </div>
            </div>
            
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="feature-card text-center p-4">
                        <div class="feature-icon mb-4">
                            <i class="ri-time-line"></i>
                        </div>
                        <h4 class="h5 fw-bold text-navy mb-3">Fast Transit Times</h4>
                        <p class="text-muted">1-5 business days delivery worldwide with priority handling.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card text-center p-4">
                        <div class="feature-icon mb-4">
                            <i class="ri-global-line"></i>
                        </div>
                        <h4 class="h5 fw-bold text-navy mb-3">Global Network</h4>
                        <p class="text-muted">Access to 500+ airports and 220+ countries worldwide.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card text-center p-4">
                        <div class="feature-icon mb-4">
                            <i class="ri-shield-check-line"></i>
                        </div>
                        <h4 class="h5 fw-bold text-navy mb-3">Secure Handling</h4>
                        <p class="text-muted">IATA certified handling with 24/7 security monitoring.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Service Details -->
    <section class="section-padding bg-white">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-4">
                    <div class="service-detail-card">
                        <h3 class="h4 fw-bold text-navy mb-3">Service Options</h3>
                        <ul class="list-unstyled">
                            <li class="mb-3"><strong>Express Air:</strong> 1-3 days</li>
                            <li class="mb-3"><strong>Standard Air:</strong> 3-5 days</li>
                            <li class="mb-3"><strong>Economy Air:</strong> 5-7 days</li>
                            <li><strong>Charter:</strong> Custom schedules</li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="service-detail-card">
                        <h3 class="h4 fw-bold text-navy mb-3">Weight & Dimensions</h3>
                        <ul class="list-unstyled">
                            <li class="mb-3"><strong>Maximum Weight:</strong> No limit</li>
                            <li class="mb-3"><strong>Package Size:</strong> Up to 300cm</li>
                            <li class="mb-3"><strong>Stackable:</strong> Yes</li>
                            <li><strong>Special Handling:</strong> Available</li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="service-detail-card">
                        <h3 class="h4 fw-bold text-navy mb-3">Additional Services</h3>
                        <ul class="list-unstyled">
                            <li class="mb-3">Door-to-door delivery</li>
                            <li class="mb-3">Customs clearance</li>
                            <li class="mb-3">Cargo insurance</li>
                            <li>Real-time tracking</li>
                        </ul>
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
                    <h2 class="display-5 fw-bold text-white mb-4">Ready to Ship by Air?</h2>
                    <p class="text-white opacity-90 fs-5 mb-5">
                        Get a custom quote for your air freight needs. Our experts will help you choose the best option.
                    </p>
                    <div class="d-flex flex-column flex-sm-row justify-content-center gap-3">
                        <a href="{{ route('quote') }}" class="btn btn-orange btn-lg rounded-pill px-5 py-3 shadow">
                            Get Air Freight Quote
                        </a>
                        <a href="{{ route('contact') }}" class="btn btn-outline-white btn-lg rounded-pill px-5 py-3">
                            Contact Air Team
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('styles')
<style>
    .service-icon {
        width: 80px;
        height: 80px;
        background: linear-gradient(to bottom right, var(--skyblue), var(--navy));
        border-radius: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 2rem;
    }
    .service-icon i {
        font-size: 2rem;
        color: white;
    }
    .feature-card {
        background: white;
        border-radius: 1rem;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        transition: transform 0.3s ease;
    }
    .feature-card:hover {
        transform: translateY(-5px);
    }
    .feature-icon {
        width: 64px;
        height: 64px;
        background: rgba(62, 146, 204, 0.1);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
    }
    .feature-icon i {
        font-size: 1.5rem;
        color: var(--skyblue);
    }
    .service-detail-card {
        background: #f8fafc;
        border-radius: 1rem;
        padding: 2rem;
        height: 100%;
        border-left: 4px solid var(--skyblue);
    }
</style>
@endpush