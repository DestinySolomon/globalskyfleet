@extends('layouts.app')

@section('title', 'GlobalSkyFleet - Get a Quote | Instant Shipping Cost Estimate')

@section('description', 'Get an instant shipping quote from GlobalSkyFleet. Calculate costs for international courier services, air freight, sea freight, and express delivery to 220+ countries.')

@section('keywords', 'shipping quote, instant quote, shipping cost, freight quote, courier quote, international shipping rates, logistics pricing')

@section('content')
    <!-- Hero Section -->
    <section class="quote-hero">
        <div class="container position-relative" style="z-index: 2;">
            <div class="row justify-content-center text-center">
                <div class="col-lg-8">
                    <div class="quote-icon">
                        <i class="ri-file-list-3-line"></i>
                    </div>
                    <h1 class="display-4 fw-bold text-white mb-4">Get an Instant Quote</h1>
                    <p class="text-white opacity-80 fs-5 mb-0">
                        Fill in the details below and we'll get back to you within 2 hours
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Quote Form Section -->
    <section class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="quote-form">
                        <form id="quoteForm" method="POST" action="{{ route('quote.submit') }}">
                            @csrf
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label for="pickupCountry" class="form-label fw-semibold text-navy mb-2">Pickup Country</label>
                                    <select class="form-select form-select-custom" id="pickupCountry" name="pickup_country" required>
                                        <option value="">Select pickup country</option>
                                        <option value="USA" {{ old('pickup_country') == 'USA' ? 'selected' : '' }}>United States</option>
                                        <option value="UK" {{ old('pickup_country') == 'UK' ? 'selected' : '' }}>United Kingdom</option>
                                        <option value="Canada" {{ old('pickup_country') == 'Canada' ? 'selected' : '' }}>Canada</option>
                                        <option value="Germany" {{ old('pickup_country') == 'Germany' ? 'selected' : '' }}>Germany</option>
                                        <option value="France" {{ old('pickup_country') == 'France' ? 'selected' : '' }}>France</option>
                                        <option value="China" {{ old('pickup_country') == 'China' ? 'selected' : '' }}>China</option>
                                        <option value="Japan" {{ old('pickup_country') == 'Japan' ? 'selected' : '' }}>Japan</option>
                                        <option value="Australia" {{ old('pickup_country') == 'Australia' ? 'selected' : '' }}>Australia</option>
                                        <option value="India" {{ old('pickup_country') == 'India' ? 'selected' : '' }}>India</option>
                                        <option value="Brazil" {{ old('pickup_country') == 'Brazil' ? 'selected' : '' }}>Brazil</option>
                                    </select>
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="deliveryCountry" class="form-label fw-semibold text-navy mb-2">Delivery Country</label>
                                    <select class="form-select form-select-custom" id="deliveryCountry" name="delivery_country" required>
                                        <option value="">Select delivery country</option>
                                        <option value="USA" {{ old('delivery_country') == 'USA' ? 'selected' : '' }}>United States</option>
                                        <option value="UK" {{ old('delivery_country') == 'UK' ? 'selected' : '' }}>United Kingdom</option>
                                        <option value="Canada" {{ old('delivery_country') == 'Canada' ? 'selected' : '' }}>Canada</option>
                                        <option value="Germany" {{ old('delivery_country') == 'Germany' ? 'selected' : '' }}>Germany</option>
                                        <option value="France" {{ old('delivery_country') == 'France' ? 'selected' : '' }}>France</option>
                                        <option value="China" {{ old('delivery_country') == 'China' ? 'selected' : '' }}>China</option>
                                        <option value="Japan" {{ old('delivery_country') == 'Japan' ? 'selected' : '' }}>Japan</option>
                                        <option value="Australia" {{ old('delivery_country') == 'Australia' ? 'selected' : '' }}>Australia</option>
                                        <option value="India" {{ old('delivery_country') == 'India' ? 'selected' : '' }}>India</option>
                                        <option value="Brazil" {{ old('delivery_country') == 'Brazil' ? 'selected' : '' }}>Brazil</option>
                                    </select>
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="weight" class="form-label fw-semibold text-navy mb-2">Package Weight (kg)</label>
                                    <input type="text" 
                                           class="form-control form-control-custom" 
                                           id="weight" 
                                           name="weight"
                                           value="{{ old('weight', '25') }}"
                                           placeholder="e.g., 25 kg" 
                                           required>
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="packageType" class="form-label fw-semibold text-navy mb-2">Package Type</label>
                                    <select class="form-select form-select-custom" id="packageType" name="package_type" required>
                                        <option value="">Select package type</option>
                                        <option value="Document" {{ old('package_type') == 'Document' ? 'selected' : '' }}>Document</option>
                                        <option value="Parcel" {{ old('package_type') == 'Parcel' ? 'selected' : 'selected' }}>Parcel</option>
                                        <option value="Pallet" {{ old('package_type') == 'Pallet' ? 'selected' : '' }}>Pallet</option>
                                        <option value="Container" {{ old('package_type') == 'Container' ? 'selected' : '' }}>Container</option>
                                    </select>
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="email" class="form-label fw-semibold text-navy mb-2">Email Address</label>
                                    <input type="email" 
                                           class="form-control form-control-custom" 
                                           id="email" 
                                           name="email"
                                           value="{{ old('email', 'demo@example.com') }}"
                                           placeholder="your.email@example.com" 
                                           required>
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="phone" class="form-label fw-semibold text-navy mb-2">Phone Number</label>
                                    <input type="tel" 
                                           class="form-control form-control-custom" 
                                           id="phone" 
                                           name="phone"
                                           value="{{ old('phone', '+1 (555) 123-4567') }}"
                                           placeholder="+1 (555) 123-4567" 
                                           required>
                                </div>
                            </div>
                            
                            <button type="submit" class="btn btn-orange w-100 mt-5 py-3 rounded-2 fw-semibold fs-5">
                                Get My Quote <i class="ri-arrow-right-line ms-2"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Benefits Section -->
            <div class="row mt-5 g-4">
                <div class="col-md-4">
                    <div class="benefit-card">
                        <i class="ri-time-line benefit-icon"></i>
                        <h4 class="h5 fw-semibold text-navy mb-2">Quick Response</h4>
                        <p class="text-muted small mb-0">Get your quote within 2 hours</p>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="benefit-card">
                        <i class="ri-shield-check-line benefit-icon"></i>
                        <h4 class="h5 fw-semibold text-navy mb-2">No Obligation</h4>
                        <p class="text-muted small mb-0">Free quote with no commitment</p>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="benefit-card">
                        <i class="ri-customer-service-2-line benefit-icon"></i>
                        <h4 class="h5 fw-semibold text-navy mb-2">Expert Support</h4>
                        <p class="text-muted small mb-0">Dedicated logistics advisors</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Quote Results Modal -->
    <div id="quoteResultsModal" class="quote-results-modal @if(session('quote_result')) show @endif">
        <div class="quote-results-card">
            <div class="text-center mb-4">
                <div class="quote-icon mb-3" style="width: 60px; height: 60px;">
                    <i class="ri-check-line fs-4"></i>
                </div>
                <h3 class="h3 fw-bold text-navy mb-2">Your Quote is Ready!</h3>
                <p class="text-muted">Estimated shipping cost based on your details</p>
            </div>
            
            <div class="price-display" id="quotePrice">
                @if(session('quote_result'))
                    {{ session('quote_result.price') }}
                @else
                    $485.00
                @endif
            </div>
            
            <div class="estimate-details">
                <div class="row g-3">
                    <div class="col-6">
                        <div class="text-muted small">Pickup</div>
                        <div class="fw-semibold" id="modalPickup">
                            @if(session('quote_result'))
                                {{ session('quote_result.pickup') }}
                            @else
                                USA
                            @endif
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-muted small">Delivery</div>
                        <div class="fw-semibold" id="modalDelivery">
                            @if(session('quote_result'))
                                {{ session('quote_result.delivery') }}
                            @else
                                Germany
                            @endif
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-muted small">Weight</div>
                        <div class="fw-semibold" id="modalWeight">
                            @if(session('quote_result'))
                                {{ session('quote_result.weight') }}
                            @else
                                25 kg
                            @endif
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-muted small">Type</div>
                        <div class="fw-semibold" id="modalType">
                            @if(session('quote_result'))
                                {{ session('quote_result.type') }}
                            @else
                                Parcel
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="alert alert-info mb-4">
                <div class="d-flex align-items-start">
                    <i class="ri-information-line me-2 mt-1"></i>
                    <div class="small">
                        This is an estimated quote. Final pricing may vary based on exact dimensions, special handling requirements, and customs fees.
                    </div>
                </div>
            </div>
            
            <div class="row g-3">
                <div class="col-6">
                    <button id="closeModal" class="btn btn-outline-secondary w-100 rounded-2">
                        Close
                    </button>
                </div>
                <div class="col-6">
                    <button id="contactSales" class="btn btn-orange w-100 rounded-2">
                        Contact Sales
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const quoteForm = document.getElementById('quoteForm');
        const quoteResultsModal = document.getElementById('quoteResultsModal');
        const closeModalBtn = document.getElementById('closeModal');
        const contactSalesBtn = document.getElementById('contactSales');
        
        // Form elements
        const pickupCountry = document.getElementById('pickupCountry');
        const deliveryCountry = document.getElementById('deliveryCountry');
        const weightInput = document.getElementById('weight');
        const packageType = document.getElementById('packageType');
        const emailInput = document.getElementById('email');
        const phoneInput = document.getElementById('phone');
        
        // Modal display elements
        const modalPickup = document.getElementById('modalPickup');
        const modalDelivery = document.getElementById('modalDelivery');
        const modalWeight = document.getElementById('modalWeight');
        const modalType = document.getElementById('modalType');
        const quotePrice = document.getElementById('quotePrice');
        
        // Country mapping for display names
        const countryNames = {
            'USA': 'United States',
            'UK': 'United Kingdom',
            'Canada': 'Canada',
            'Germany': 'Germany',
            'France': 'France',
            'China': 'China',
            'Japan': 'Japan',
            'Australia': 'Australia',
            'India': 'India',
            'Brazil': 'Brazil'
        };
        
        // Format price with currency
        function formatPrice(price) {
            return new Intl.NumberFormat('en-US', {
                style: 'currency',
                currency: 'USD',
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }).format(price);
        }
        
        // If modal should be shown on page load (from session)
        @if(session('quote_result'))
            document.body.style.overflow = 'hidden';
        @endif
        
        // Close modal
        if (closeModalBtn) {
            closeModalBtn.addEventListener('click', function() {
                quoteResultsModal.classList.remove('show');
                document.body.style.overflow = 'auto';
            });
        }
        
        // Contact sales button
        if (contactSalesBtn) {
            contactSalesBtn.addEventListener('click', function() {
                alert('Thank you for your interest! Our sales team will contact you within 2 hours.');
                quoteResultsModal.classList.remove('show');
                document.body.style.overflow = 'auto';
                
                // In a real application, you would make an AJAX request to save lead
                // fetch('{{ route("contact.sales") }}', {
                //     method: 'POST',
                //     headers: {
                //         'Content-Type': 'application/json',
                //         'X-CSRF-TOKEN': '{{ csrf_token() }}'
                //     },
                //     body: JSON.stringify({
                //         email: emailInput.value,
                //         phone: phoneInput.value,
                //         quote_details: {
                //             pickup: pickupCountry.value,
                //             delivery: deliveryCountry.value,
                //             weight: weightInput.value,
                //             type: packageType.value
                //         }
                //     })
                // });
            });
        }
        
        // Close modal when clicking outside
        if (quoteResultsModal) {
            quoteResultsModal.addEventListener('click', function(e) {
                if (e.target === quoteResultsModal) {
                    quoteResultsModal.classList.remove('show');
                    document.body.style.overflow = 'auto';
                }
            });
        }
        
        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && quoteResultsModal && quoteResultsModal.classList.contains('show')) {
                quoteResultsModal.classList.remove('show');
                document.body.style.overflow = 'auto';
            }
        });
        
        // Client-side validation and AJAX submission
        if (quoteForm && !quoteResultsModal.classList.contains('show')) {
            // Remove the form submit handler if we're showing results from session
            quoteForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Get form values
                const pickup = pickupCountry.value;
                const delivery = deliveryCountry.value;
                const weight = weightInput.value;
                const type = packageType.value;
                const email = emailInput.value;
                const phone = phoneInput.value;
                
                // Validate form
                if (!pickup || !delivery || !weight || !type || !email || !phone) {
                    alert('Please fill in all required fields.');
                    return;
                }
                
                // Show loading state
                const submitBtn = quoteForm.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Calculating...';
                submitBtn.disabled = true;
                
                // Simulate API call delay
                setTimeout(() => {
                    // Submit the form
                    quoteForm.submit();
                }, 1000);
            });
        }
        
        // Set demo values only if form is empty and we're not showing results
        if (!quoteResultsModal.classList.contains('show')) {
            if (!pickupCountry.value) pickupCountry.value = 'USA';
            if (!deliveryCountry.value) deliveryCountry.value = 'Germany';
            if (!weightInput.value) weightInput.value = '25';
            if (!packageType.value) packageType.value = 'Parcel';
            if (!emailInput.value) emailInput.value = 'demo@example.com';
            if (!phoneInput.value) phoneInput.value = '+1 (555) 123-4567';
        }
    });
</script>
@endpush