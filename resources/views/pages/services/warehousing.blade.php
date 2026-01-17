@extends('layouts.app')

@section('title', 'Warehousing & Logistics Solutions | GlobalSkyFleet')
@section('description', 'Secure warehousing and inventory management solutions. Storage, fulfillment, and distribution services.')
@section('keywords', 'warehousing, storage solutions, inventory management, fulfillment, logistics, distribution')

@section('content')
    <!-- Hero Section -->
    <section class="services-hero">
        <div class="container position-relative" style="z-index: 2;">
            <div class="row justify-content-center text-center">
                <div class="col-lg-8">
                    <p class="text-skyblue text-uppercase fw-semibold letter-spacing mb-3">WAREHOUSING</p>
                    <h1 class="display-4 fw-bold text-white mb-4">Smart Warehousing Solutions</h1>
                    <p class="text-white opacity-80 fs-5 mb-0 mx-auto" style="max-width: 700px;">
                        Secure storage, efficient inventory management, and seamless fulfillment services for businesses of all sizes.
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
                    <img src="{{ asset('images/warehousing.jpg') }}" 
                         alt="Modern Warehouse Facility" 
                         class="img-fluid rounded-4 shadow-lg">
                </div>
                <div class="col-lg-6">
                    <div class="service-icon">
                        <i class="ri-building-line"></i>
                    </div>
                    <h2 class="display-5 fw-bold text-navy mb-4">Comprehensive Warehousing Services</h2>
                    <p class="text-muted fs-5 mb-4">
                        Our state-of-the-art warehousing facilities provide secure storage, advanced inventory management, and efficient fulfillment services. Scale your operations without the overhead of managing your own storage.
                    </p>
                    
                    <div class="warehouse-features mb-4">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="d-flex align-items-center mb-3">
                                    <i class="ri-shield-check-line text-success fs-4 me-3"></i>
                                    <span>24/7 Security</span>
                                </div>
                                <div class="d-flex align-items-center mb-3">
                                    <i class="ri-temp-cold-line text-success fs-4 me-3"></i>
                                    <span>Climate Control</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center mb-3">
                                    <i class="ri-bar-chart-line text-success fs-4 me-3"></i>
                                    <span>Inventory Tracking</span>
                                </div>
                                <div class="d-flex align-items-center mb-3">
                                    <i class="ri-robot-line text-success fs-4 me-3"></i>
                                    <span>Automation Ready</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <a href="{{ route('contact') }}" class="btn btn-orange rounded-pill px-4 py-2">
                        Schedule Facility Tour
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Service Options -->
    <section class="section-padding bg-gray-50">
        <div class="container">
            <div class="row justify-content-center text-center mb-5">
                <div class="col-lg-8">
                    <h2 class="display-5 fw-bold text-navy mb-4">Warehousing Solutions</h2>
                    <p class="text-muted fs-5">Flexible storage options for every business need</p>
                </div>
            </div>
            
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="warehouse-option-card">
                        <div class="option-icon mb-4">
                            <i class="ri-palette-line"></i>
                        </div>
                        <h4 class="h5 fw-bold text-navy mb-3">Dedicated Warehousing</h4>
                        <p class="text-muted small mb-4">Exclusive warehouse space with custom configuration for your specific needs.</p>
                        <div class="option-specs">
                            <div class="spec">
                                <small class="text-muted">Minimum Term</small>
                                <div class="fw-bold">12 Months</div>
                            </div>
                            <div class="spec">
                                <small class="text-muted">Space From</small>
                                <div class="fw-bold">5,000 sq ft</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="warehouse-option-card popular">
                        <div class="popular-tag">MOST FLEXIBLE</div>
                        <div class="option-icon mb-4">
                            <i class="ri-share-line"></i>
                        </div>
                        <h4 class="h5 fw-bold text-navy mb-3">Shared Warehousing</h4>
                        <p class="text-muted small mb-4">Cost-effective shared space with dedicated inventory areas and common facilities.</p>
                        <div class="option-specs">
                            <div class="spec">
                                <small class="text-muted">Minimum Term</small>
                                <div class="fw-bold">3 Months</div>
                            </div>
                            <div class="spec">
                                <small class="text-muted">Space From</small>
                                <div class="fw-bold">100 sq ft</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="warehouse-option-card">
                        <div class="option-icon mb-4">
                            <i class="ri-dropbox-line"></i>
                        </div>
                        <h4 class="h5 fw-bold text-navy mb-3">Fulfillment Services</h4>
                        <p class="text-muted small mb-4">Complete order fulfillment including picking, packing, and shipping management.</p>
                        <div class="option-specs">
                            <div class="spec">
                                <small class="text-muted">Order Volume</small>
                                <div class="fw-bold">50+ orders/day</div>
                            </div>
                            <div class="spec">
                                <small class="text-muted">Integration</small>
                                <div class="fw-bold">API Available</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Facility Features -->
    <section class="section-padding bg-white">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-6">
                    <h3 class="h4 fw-bold text-navy mb-4">Facility Specifications</h3>
                    
                    <div class="facility-specs">
                        <div class="spec-item d-flex justify-content-between py-3 border-bottom">
                            <span>Total Facility Area</span>
                            <span class="fw-bold">500,000 sq ft</span>
                        </div>
                        <div class="spec-item d-flex justify-content-between py-3 border-bottom">
                            <span>Clear Height</span>
                            <span class="fw-bold">40 feet</span>
                        </div>
                        <div class="spec-item d-flex justify-content-between py-3 border-bottom">
                            <span>Loading Docks</span>
                            <span class="fw-bold">50+ docks</span>
                        </div>
                        <div class="spec-item d-flex justify-content-between py-3 border-bottom">
                            <span>Security Systems</span>
                            <span class="fw-bold">24/7 monitoring</span>
                        </div>
                        <div class="spec-item d-flex justify-content-between py-3 border-bottom">
                            <span>Fire Protection</span>
                            <span class="fw-bold">ESFR sprinklers</span>
                        </div>
                        <div class="spec-item d-flex justify-content-between py-3">
                            <span>Parking</span>
                            <span class="fw-bold">200+ trailer spots</span>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-6">
                    <h3 class="h4 fw-bold text-navy mb-4">Value-Added Services</h3>
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="service-item p-3 rounded-3 border">
                                <i class="ri-barcode-line text-skyblue fs-4 mb-2"></i>
                                <h6 class="fw-bold mb-2">Inventory Management</h6>
                                <p class="text-muted small mb-0">Real-time tracking and reporting</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="service-item p-3 rounded-3 border">
                                <i class="ri-quality-line text-skyblue fs-4 mb-2"></i>
                                <h6 class="fw-bold mb-2">Quality Control</h6>
                                <p class="text-muted small mb-0">Inspection and quality checks</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="service-item p-3 rounded-3 border">
                                <i class="ri-recycle-line text-skyblue fs-4 mb-2"></i>
                                <h6 class="fw-bold mb-2">Repackaging</h6>
                                <p class="text-muted small mb-0">Custom packaging solutions</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="service-item p-3 rounded-3 border">
                                <i class="ri-printer-line text-skyblue fs-4 mb-2"></i>
                                <h6 class="fw-bold mb-2">Labeling</h6>
                                <p class="text-muted small mb-0">Custom labeling and kitting</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <a href="{{ route('contact') }}" class="btn btn-outline-orange rounded-pill px-4 py-2">
                            Request Service Catalog
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Locations -->
    <section class="section-padding bg-gray-50">
        <div class="container">
            <div class="row justify-content-center text-center mb-5">
                <div class="col-lg-8">
                    <h2 class="display-5 fw-bold text-navy mb-4">Strategic Locations</h2>
                    <p class="text-muted fs-5">Warehouse facilities in key logistics hubs</p>
                </div>
            </div>
            
            <div class="row g-4">
                <div class="col-md-3">
                    <div class="location-card text-center p-4">
                        <div class="location-icon mb-3">
                            <i class="ri-map-pin-fill"></i>
                        </div>
                        <h5 class="fw-bold text-navy mb-2">Los Angeles, CA</h5>
                        <p class="text-muted small">Port proximity<br>300,000 sq ft</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="location-card text-center p-4">
                        <div class="location-icon mb-3">
                            <i class="ri-map-pin-fill"></i>
                        </div>
                        <h5 class="fw-bold text-navy mb-2">New Jersey, NY</h5>
                        <p class="text-muted small">Airport access<br>250,000 sq ft</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="location-card text-center p-4">
                        <div class="location-icon mb-3">
                            <i class="ri-map-pin-fill"></i>
                        </div>
                        <h5 class="fw-bold text-navy mb-2">Chicago, IL</h5>
                        <p class="text-muted small">Rail hub<br>200,000 sq ft</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="location-card text-center p-4">
                        <div class="location-icon mb-3">
                            <i class="ri-map-pin-fill"></i>
                        </div>
                        <h5 class="fw-bold text-navy mb-2">Atlanta, GA</h5>
                        <p class="text-muted small">Highway network<br>150,000 sq ft</p>
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
                    <h2 class="display-5 fw-bold text-white mb-4">Ready to Optimize Your Storage?</h2>
                    <p class="text-white opacity-90 fs-5 mb-5">
                        Contact our warehousing experts for a customized storage solution.
                    </p>
                    <div class="d-flex flex-column flex-sm-row justify-content-center gap-3">
                        <a href="{{ route('contact') }}" class="btn btn-orange btn-lg rounded-pill px-5 py-3 shadow">
                            Request Quote
                        </a>
                        <a href="{{ route('contact') }}" class="btn btn-outline-white btn-lg rounded-pill px-5 py-3">
                            Schedule Tour
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('styles')
<style>
    .warehouse-features .d-flex {
        padding: 0.75rem;
        background: #f8fafc;
        border-radius: 0.5rem;
    }
    .warehouse-option-card {
        background: white;
        border-radius: 1rem;
        padding: 2rem;
        height: 100%;
        position: relative;
        border: 1px solid #e5e7eb;
        transition: transform 0.3s ease;
    }
    .warehouse-option-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }
    .warehouse-option-card.popular {
        border: 2px solid var(--skyblue);
    }
    .popular-tag {
        position: absolute;
        top: -10px;
        left: 50%;
        transform: translateX(-50%);
        background: var(--skyblue);
        color: white;
        padding: 0.25rem 1rem;
        border-radius: 2rem;
        font-size: 0.75rem;
        font-weight: 600;
    }
    .option-icon {
        width: 64px;
        height: 64px;
        background: rgba(62, 146, 204, 0.1);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
    }
    .option-icon i {
        font-size: 1.75rem;
        color: var(--skyblue);
    }
    .option-specs {
        display: flex;
        justify-content: space-between;
        padding-top: 1rem;
        border-top: 1px solid #e5e7eb;
    }
    .facility-specs .spec-item span:first-child {
        color: #666;
    }
    .service-item {
        transition: all 0.3s ease;
    }
    .service-item:hover {
        background: #f8fafc;
        border-color: var(--skyblue);
    }
    .location-card {
        background: white;
        border-radius: 1rem;
        transition: transform 0.3s ease;
    }
    .location-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    }
    .location-icon {
        width: 48px;
        height: 48px;
        background: rgba(62, 146, 204, 0.1);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
    }
    .location-icon i {
        font-size: 1.5rem;
        color: var(--skyblue);
    }
</style>
@endpush