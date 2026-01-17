@extends('layouts.app')

@section('title', 'Sea Freight & Ocean Shipping | GlobalSkyFleet')
@section('description', 'Cost-effective sea freight solutions for bulk shipments. FCL and LCL container shipping worldwide.')
@section('keywords', 'sea freight, ocean shipping, container shipping, FCL, LCL, maritime logistics')

@section('content')
    <!-- Hero Section -->
    <section class="services-hero">
        <div class="container position-relative" style="z-index: 2;">
            <div class="row justify-content-center text-center">
                <div class="col-lg-8">
                    <p class="text-skyblue text-uppercase fw-semibold letter-spacing mb-3">SEA FREIGHT</p>
                    <h1 class="display-4 fw-bold text-white mb-4">Ocean Shipping Solutions</h1>
                    <p class="text-white opacity-80 fs-5 mb-0 mx-auto" style="max-width: 700px;">
                        Cost-effective sea freight services for bulk shipments. Full container load (FCL) and less than container load (LCL) options available.
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
                        <i class="ri-ship-line"></i>
                    </div>
                    <h2 class="display-5 fw-bold text-navy mb-4">Comprehensive Sea Freight Services</h2>
                    <p class="text-muted fs-5 mb-4">
                        Our ocean freight solutions provide reliable and economical shipping for large volumes. With strategic partnerships with major shipping lines, we offer competitive rates and flexible schedules.
                    </p>
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="mb-4">
                                <h4 class="h5 fw-bold text-navy mb-2">FCL Services</h4>
                                <p class="text-muted small">20ft and 40ft container options with dedicated space.</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-4">
                                <h4 class="h5 fw-bold text-navy mb-2">LCL Services</h4>
                                <p class="text-muted small">Consolidated shipments for smaller cargo volumes.</p>
                            </div>
                        </div>
                    </div>
                    
                    <a href="{{ route('quote') }}" class="btn btn-orange rounded-pill px-4 py-2">
                        Request Sea Freight Quote
                    </a>
                </div>
                <div class="col-lg-6">
                    <img src="{{ asset('images/sea-freight.jpg') }}" 
                         alt="Sea Freight Operations" 
                         class="img-fluid rounded-4 shadow-lg">
                </div>
            </div>
        </div>
    </section>

    <!-- Container Options -->
    <section class="section-padding bg-gray-50">
        <div class="container">
            <div class="row justify-content-center text-center mb-5">
                <div class="col-lg-8">
                    <h2 class="display-5 fw-bold text-navy mb-4">Container Options</h2>
                    <p class="text-muted fs-5">Choose the right container for your shipping needs</p>
                </div>
            </div>
            
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="container-card text-center p-4">
                        <div class="container-icon mb-4">
                            <i class="ri-box-3-line"></i>
                        </div>
                        <h4 class="h5 fw-bold text-navy mb-3">20ft Container</h4>
                        <p class="text-muted small mb-3">33 m³ capacity<br>Max weight: 28,000kg</p>
                        <div class="price-label">From $1,200</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="container-card text-center p-4">
                        <div class="container-icon mb-4">
                            <i class="ri-box-3-fill"></i>
                        </div>
                        <h4 class="h5 fw-bold text-navy mb-3">40ft Container</h4>
                        <p class="text-muted small mb-3">67 m³ capacity<br>Max weight: 26,500kg</p>
                        <div class="price-label">From $2,100</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="container-card text-center p-4">
                        <div class="container-icon mb-4">
                            <i class="ri-temp-hot-line"></i>
                        </div>
                        <h4 class="h5 fw-bold text-navy mb-3">Reefer Container</h4>
                        <p class="text-muted small mb-3">Temperature controlled<br>For perishable goods</p>
                        <div class="price-label">Custom Quote</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Transit Times & Routes -->
    <section class="section-padding bg-white">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-8">
                    <h2 class="display-5 fw-bold text-navy mb-4">Major Shipping Routes</h2>
                    <p class="text-muted mb-4">
                        We operate on all major global shipping routes with regular departures and arrivals.
                    </p>
                    
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Route</th>
                                    <th>Transit Time</th>
                                    <th>Frequency</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Asia to North America</td>
                                    <td>18-25 days</td>
                                    <td>Weekly</td>
                                </tr>
                                <tr>
                                    <td>Europe to Asia</td>
                                    <td>28-35 days</td>
                                    <td>Bi-weekly</td>
                                </tr>
                                <tr>
                                    <td>Middle East to Europe</td>
                                    <td>14-21 days</td>
                                    <td>Weekly</td>
                                </tr>
                                <tr>
                                    <td>Australia to Americas</td>
                                    <td>30-40 days</td>
                                    <td>Monthly</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="info-card bg-navy text-white p-4 rounded-3">
                        <h3 class="h5 fw-bold mb-4">Need Help?</h3>
                        <p class="mb-4">Our sea freight experts can help you choose the best shipping option.</p>
                        <ul class="list-unstyled">
                            <li class="mb-3"><i class="ri-phone-line me-2"></i> +1 (555) SEA-FREIGHT</li>
                            <li class="mb-3"><i class="ri-mail-line me-2"></i> sea@globalskyfleet.com</li>
                            <li><i class="ri-time-line me-2"></i> 24/7 Support Available</li>
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
                    <h2 class="display-5 fw-bold text-white mb-4">Start Shipping by Sea Today</h2>
                    <p class="text-white opacity-90 fs-5 mb-5">
                        Get competitive rates for your ocean freight shipments. Volume discounts available.
                    </p>
                    <div class="d-flex flex-column flex-sm-row justify-content-center gap-3">
                        <a href="{{ route('quote') }}" class="btn btn-orange btn-lg rounded-pill px-5 py-3 shadow">
                            Get Sea Freight Quote
                        </a>
                        <a href="{{ route('contact') }}" class="btn btn-outline-white btn-lg rounded-pill px-5 py-3">
                            Contact Sea Team
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('styles')
<style>
    .container-card {
        background: white;
        border-radius: 1rem;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        border: 2px solid transparent;
        transition: all 0.3s ease;
    }
    .container-card:hover {
        border-color: var(--skyblue);
        transform: translateY(-5px);
    }
    .container-icon {
        width: 64px;
        height: 64px;
        background: rgba(62, 146, 204, 0.1);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
    }
    .container-icon i {
        font-size: 1.75rem;
        color: var(--skyblue);
    }
    .price-label {
        background: var(--orange);
        color: white;
        padding: 0.25rem 1rem;
        border-radius: 2rem;
        display: inline-block;
        font-weight: 600;
    }
    .info-card {
        height: 100%;
    }
    .table th {
        color: var(--navy);
        font-weight: 600;
        border-bottom: 2px solid var(--navy);
    }
</style>
@endpush