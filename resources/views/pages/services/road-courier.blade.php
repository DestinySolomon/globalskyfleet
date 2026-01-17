@extends('layouts.app')

@section('title', 'Road Courier & Domestic Shipping | GlobalSkyFleet')
@section('description', 'Reliable road courier services for domestic and cross-border shipments. Fast delivery with comprehensive tracking.')
@section('keywords', 'road courier, domestic shipping, trucking, land transport, cross-border shipping')

@section('content')
    <!-- Hero Section -->
    <section class="services-hero">
        <div class="container position-relative" style="z-index: 2;">
            <div class="row justify-content-center text-center">
                <div class="col-lg-8">
                    <p class="text-skyblue text-uppercase fw-semibold letter-spacing mb-3">ROAD COURIER</p>
                    <h1 class="display-4 fw-bold text-white mb-4">Reliable Road Transportation</h1>
                    <p class="text-white opacity-80 fs-5 mb-0 mx-auto" style="max-width: 700px;">
                        Efficient road courier services for domestic and cross-border shipments. Fast, secure, and cost-effective land transport solutions.
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
                    <img src="{{ asset('images/road-courier.jpg') }}" 
                         alt="Road Courier Fleet" 
                         class="img-fluid rounded-4 shadow-lg">
                </div>
                <div class="col-lg-6">
                    <div class="service-icon">
                        <i class="ri-truck-line"></i>
                    </div>
                    <h2 class="display-5 fw-bold text-navy mb-4">Comprehensive Road Courier Services</h2>
                    <p class="text-muted fs-5 mb-4">
                        Our road transportation network spans across continents, providing reliable and timely delivery for all types of cargo. From small parcels to full truckloads, we've got you covered.
                    </p>
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <ul class="list-unstyled">
                                <li class="mb-3"><i class="ri-check-line text-skyblue me-2"></i>Same-day Delivery</li>
                                <li class="mb-3"><i class="ri-check-line text-skyblue me-2"></i>Next-day Delivery</li>
                                <li><i class="ri-check-line text-skyblue me-2"></i>Scheduled Delivery</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <ul class="list-unstyled">
                                <li class="mb-3"><i class="ri-check-line text-skyblue me-2"></i>Temperature Control</li>
                                <li class="mb-3"><i class="ri-check-line text-skyblue me-2"></i>Hazardous Materials</li>
                                <li><i class="ri-check-line text-skyblue me-2"></i>Oversized Cargo</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Service Types -->
    <section class="section-padding bg-gray-50">
        <div class="container">
            <div class="row justify-content-center text-center mb-5">
                <div class="col-lg-8">
                    <h2 class="display-5 fw-bold text-navy mb-4">Our Road Courier Solutions</h2>
                    <p class="text-muted fs-5">Flexible options for every shipping need</p>
                </div>
            </div>
            
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="service-type-card">
                        <div class="service-type-icon mb-4">
                            <i class="ri-car-line"></i>
                        </div>
                        <h4 class="h5 fw-bold text-navy mb-3">Express Courier</h4>
                        <p class="text-muted small">Small parcels and documents with same-day or next-day delivery.</p>
                        <div class="service-features">
                            <span class="badge bg-light text-navy">Up to 30kg</span>
                            <span class="badge bg-light text-navy">Door-to-door</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="service-type-card">
                        <div class="service-type-icon mb-4">
                            <i class="ri-truck-line"></i>
                        </div>
                        <h4 class="h5 fw-bold text-navy mb-3">LTL (Less Than Truckload)</h4>
                        <p class="text-muted small">Partial truckloads for medium-sized shipments with cost sharing.</p>
                        <div class="service-features">
                            <span class="badge bg-light text-navy">30kg - 5,000kg</span>
                            <span class="badge bg-light text-navy">Consolidated</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="service-type-card">
                        <div class="service-type-icon mb-4">
                            <i class="ri-trailer-line"></i>
                        </div>
                        <h4 class="h5 fw-bold text-navy mb-3">FTL (Full Truckload)</h4>
                        <p class="text-muted small">Dedicated trucks for large volume shipments with exclusive use.</p>
                        <div class="service-features">
                            <span class="badge bg-light text-navy">5,000kg+</span>
                            <span class="badge bg-light text-navy">Dedicated</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Coverage Area -->
    <section class="section-padding bg-white">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-6">
                    <h2 class="display-5 fw-bold text-navy mb-4">Nationwide Coverage</h2>
                    <p class="text-muted mb-4">
                        Our extensive road network covers all major cities and regions with regular schedules and reliable service.
                    </p>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <ul class="list-unstyled">
                                <li class="mb-3"><i class="ri-map-pin-fill text-skyblue me-2"></i> All Major Cities</li>
                                <li class="mb-3"><i class="ri-map-pin-fill text-skyblue me-2"></i> Regional Towns</li>
                                <li class="mb-3"><i class="ri-map-pin-fill text-skyblue me-2"></i> Industrial Zones</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <ul class="list-unstyled">
                                <li class="mb-3"><i class="ri-map-pin-fill text-skyblue me-2"></i> Cross-border Routes</li>
                                <li class="mb-3"><i class="ri-map-pin-fill text-skyblue me-2"></i> Port Connections</li>
                                <li><i class="ri-map-pin-fill text-skyblue me-2"></i> Airport Links</li>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <a href="{{ route('contact') }}" class="btn btn-outline-orange rounded-pill px-4 py-2">
                            Check Your Area
                        </a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="coverage-map p-4 rounded-3" style="background: #f8fafc;">
                        <h4 class="h5 fw-bold text-navy mb-3">Delivery Time Estimates</h4>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Distance</th>
                                        <th>Standard</th>
                                        <th>Express</th>
                                        <th>Priority</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Within City</td>
                                        <td>24-48 hours</td>
                                        <td>Same-day</td>
                                        <td>4 hours</td>
                                    </tr>
                                    <tr>
                                        <td>Regional (300km)</td>
                                        <td>2-3 days</td>
                                        <td>1-2 days</td>
                                        <td>24 hours</td>
                                    </tr>
                                    <tr>
                                        <td>National (1000km)</td>
                                        <td>3-5 days</td>
                                        <td>2-3 days</td>
                                        <td>48 hours</td>
                                    </tr>
                                    <tr>
                                        <td>Cross-border</td>
                                        <td>5-7 days</td>
                                        <td>3-4 days</td>
                                        <td>72 hours</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
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
                    <h2 class="display-5 fw-bold text-white mb-4">Need Road Transportation?</h2>
                    <p class="text-white opacity-90 fs-5 mb-5">
                        Get instant quotes for your road shipping needs. Our fleet is ready to serve you.
                    </p>
                    <div class="d-flex flex-column flex-sm-row justify-content-center gap-3">
                        <a href="{{ route('quote') }}" class="btn btn-orange btn-lg rounded-pill px-5 py-3 shadow">
                            Get Road Quote
                        </a>
                        <a href="tel:+15551234567" class="btn btn-outline-white btn-lg rounded-pill px-5 py-3">
                            <i class="ri-phone-line me-2"></i> Call Dispatch
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('styles')
<style>
    .service-type-card {
        background: white;
        border-radius: 1rem;
        padding: 2rem;
        text-align: center;
        height: 100%;
        transition: transform 0.3s ease;
        border: 1px solid #e5e7eb;
    }
    .service-type-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }
    .service-type-icon {
        width: 64px;
        height: 64px;
        background: linear-gradient(to bottom right, var(--skyblue), var(--navy));
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
    }
    .service-type-icon i {
        font-size: 1.75rem;
        color: white;
    }
    .service-features {
        margin-top: 1rem;
    }
    .service-features .badge {
        margin-right: 0.5rem;
        margin-bottom: 0.5rem;
    }
    .coverage-map {
        height: 100%;
    }
    .table th {
        background-color: #f8fafc;
        color: var(--navy);
        font-weight: 600;
    }
</style>
@endpush