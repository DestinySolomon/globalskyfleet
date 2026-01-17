

<?php $__env->startSection('title', 'GlobalSkyFleet - Get a Quote | Instant Shipping Cost Estimate'); ?>

<?php $__env->startSection('description', 'Get an instant shipping quote from GlobalSkyFleet. Calculate costs for international courier services, air freight, sea freight, and express delivery to 220+ countries.'); ?>

<?php $__env->startSection('keywords', 'shipping quote, instant quote, shipping cost, freight quote, courier quote, international shipping rates, logistics pricing'); ?>

<?php $__env->startSection('content'); ?>
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
                        <form id="quoteForm" method="POST" action="<?php echo e(route('quote.submit')); ?>">
                            <?php echo csrf_field(); ?>
                            <div class="row g-4">
                                <!-- Pickup Country with Search -->
                                <div class="col-md-6">
                                    <label for="pickupCountry" class="form-label fw-semibold text-navy mb-2">Pickup Country *</label>
                                    <div class="position-relative">
                                        <input type="text" 
                                               class="form-control form-control-custom" 
                                               id="pickupCountry" 
                                               name="pickup_country"
                                               value="<?php echo e(old('pickup_country', 'United States')); ?>"
                                               placeholder="Start typing country name..."
                                               autocomplete="off"
                                               required>
                                        <div class="position-absolute end-0 top-0 h-100 d-flex align-items-center pe-3">
                                            <i class="ri-search-line text-muted"></i>
                                        </div>
                                    </div>
                                    <div id="pickupSuggestions" class="suggestions-dropdown" style="display: none;"></div>
                                </div>
                                
                                <!-- Delivery Country with Search -->
                                <div class="col-md-6">
                                    <label for="deliveryCountry" class="form-label fw-semibold text-navy mb-2">Delivery Country *</label>
                                    <div class="position-relative">
                                        <input type="text" 
                                               class="form-control form-control-custom" 
                                               id="deliveryCountry" 
                                               name="delivery_country"
                                               value="<?php echo e(old('delivery_country', 'Germany')); ?>"
                                               placeholder="Start typing country name..."
                                               autocomplete="off"
                                               required>
                                        <div class="position-absolute end-0 top-0 h-100 d-flex align-items-center pe-3">
                                            <i class="ri-search-line text-muted"></i>
                                        </div>
                                    </div>
                                    <div id="deliverySuggestions" class="suggestions-dropdown" style="display: none;"></div>
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="weight" class="form-label fw-semibold text-navy mb-2">Package Weight (kg) *</label>
                                    <input type="number" 
                                           class="form-control form-control-custom" 
                                           id="weight" 
                                           name="weight"
                                           value="<?php echo e(old('weight', '25')); ?>"
                                           placeholder="e.g., 25"
                                           step="0.1"
                                           min="0.1"
                                           max="1000"
                                           required>
                                    <div class="form-text">Minimum 0.1 kg, maximum 1000 kg</div>
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="packageType" class="form-label fw-semibold text-navy mb-2">Package Type *</label>
                                    <select class="form-select form-select-custom" id="packageType" name="package_type" required>
                                        <option value="">Select package type</option>
                                        <option value="Document" <?php echo e(old('package_type') == 'Document' ? 'selected' : ''); ?>>Document (Envelopes, Letters)</option>
                                        <option value="Parcel" <?php echo e(old('package_type') == 'Parcel' ? 'selected' : 'selected'); ?>>Parcel (Boxes, Packages)</option>
                                        <option value="Pallet" <?php echo e(old('package_type') == 'Pallet' ? 'selected' : ''); ?>>Pallet (Wooden Pallets)</option>
                                        <option value="Container" <?php echo e(old('package_type') == 'Container' ? 'selected' : ''); ?>>Container (Shipping Containers)</option>
                                    </select>
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="email" class="form-label fw-semibold text-navy mb-2">Email Address *</label>
                                    <input type="email" 
                                           class="form-control form-control-custom" 
                                           id="email" 
                                           name="email"
                                           value="<?php echo e(old('email', '')); ?>"
                                           placeholder="your.email@example.com" 
                                           required>
                                </div>
                                
                               <div class="col-md-6">
    <label for="phone" class="form-label fw-semibold text-navy mb-2">Phone Number *</label>
    <div class="position-relative">
        <div class="phone-input-wrapper">
            <div class="selected-country" id="selectedCountryFlag">
                <img src="https://flagcdn.com/w40/us.png" alt="US" class="country-flag">
                <span class="country-code">+1</span>
                <i class="ri-arrow-down-s-line"></i>
            </div>
            <input type="tel" 
                   class="form-control form-control-custom phone-number-input" 
                   id="phone" 
                   name="phone"
                   value="<?php echo e(old('phone', '')); ?>"
                   placeholder="(555) 123-4567" 
                   required>
        </div>
        <input type="hidden" id="phoneCountryCode" name="phone_country_code" value="+1">
        <input type="hidden" id="phoneCountry" name="phone_country" value="US">
    </div>
    <div id="countryCodeSuggestions" class="country-suggestions-dropdown" style="display: none;"></div>
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
    <div id="quoteResultsModal" class="quote-results-modal <?php if(session('quote_result')): ?> show <?php endif; ?>">
        <div class="quote-results-card">
            <div class="text-center mb-4">
                <div class="quote-icon mb-3" style="width: 60px; height: 60px;">
                    <i class="ri-check-line fs-4"></i>
                </div>
                <h3 class="h3 fw-bold text-navy mb-2">Your Quote is Ready!</h3>
                <p class="text-muted">Estimated shipping cost based on your details</p>
            </div>
            
            <div class="price-display" id="quotePrice">
                <?php if(session('quote_result')): ?>
                    <?php echo e(session('quote_result.price')); ?>

                <?php else: ?>
                    $485.00
                <?php endif; ?>
            </div>
            
            <div class="estimate-details">
                <div class="row g-3">
                    <div class="col-6">
                        <div class="text-muted small">Pickup</div>
                        <div class="fw-semibold" id="modalPickup">
                            <?php if(session('quote_result')): ?>
                                <?php echo e(session('quote_result.pickup')); ?>

                            <?php else: ?>
                                United States
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-muted small">Delivery</div>
                        <div class="fw-semibold" id="modalDelivery">
                            <?php if(session('quote_result')): ?>
                                <?php echo e(session('quote_result.delivery')); ?>

                            <?php else: ?>
                                Germany
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-muted small">Weight</div>
                        <div class="fw-semibold" id="modalWeight">
                            <?php if(session('quote_result')): ?>
                                <?php echo e(session('quote_result.weight')); ?>

                            <?php else: ?>
                                25 kg
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-muted small">Type</div>
                        <div class="fw-semibold" id="modalType">
                            <?php if(session('quote_result')): ?>
                                <?php echo e(session('quote_result.type')); ?>

                            <?php else: ?>
                                Parcel
                            <?php endif; ?>
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
            
            <!-- Create Shipment Button (for logged in users) -->
            <?php if(auth()->guard()->check()): ?>
            <div class="mb-4">
                <a href="<?php echo e(route('shipments.create')); ?>?weight=<?php echo e(session('quote_result.weight', '25')); ?>&pickup=<?php echo e(session('quote_result.pickup', 'United States')); ?>&delivery=<?php echo e(session('quote_result.delivery', 'Germany')); ?>&type=<?php echo e(session('quote_result.type', 'Parcel')); ?>"
                   class="btn btn-success w-100 rounded-2 py-3 fw-semibold">
                    <i class="ri-ship-line me-2"></i>Create Shipment with This Quote
                </a>
                <p class="text-muted small mt-2 text-center">
                    Your quote details will be pre-filled in the shipment form
                </p>
            </div>
            <?php else: ?>
            <!-- Login/Register Prompt for non-logged users -->
            <div class="mb-4">
                <div class="alert alert-warning">
                    <div class="d-flex align-items-center">
                        <i class="ri-login-circle-line me-2 fs-5"></i>
                        <div>
                            <strong>Want to create a shipment?</strong>
                            <p class="mb-0 small">Login or register to create shipments, track packages, and save addresses.</p>
                        </div>
                    </div>
                </div>
                
                <div class="row g-2">
                    <div class="col-6">
                        <a href="<?php echo e(route('login')); ?>?redirect=<?php echo e(urlencode(route('shipments.create') . '?weight=' . session('quote_result.weight', '25') . '&pickup=' . session('quote_result.pickup', 'United States') . '&delivery=' . session('quote_result.delivery', 'Germany') . '&type=' . session('quote_result.type', 'Parcel'))); ?>" class="btn btn-outline-primary w-100">
                            <i class="ri-login-box-line me-1"></i>Login
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="<?php echo e(route('register')); ?>" class="btn btn-primary w-100">
                            <i class="ri-user-add-line me-1"></i>Register
                        </a>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            
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
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
    /* Country Search Suggestions Styling */
.suggestions-dropdown {
    position: absolute;
    z-index: 1000;
    width: 40%;
    max-height: 250px;
    overflow-y: auto;
    background: white;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    margin-top: 2px;
}

.suggestion-item {
    padding: 12px 15px;
    cursor: pointer;
    border-bottom: 1px solid #f8f9fa;
    transition: all 0.2s;
    font-size: 0.95rem;
}

.suggestion-item:hover {
    background-color: #f8f9fa;
}

.suggestion-item.active {
    background-color: #0a2463;
    color: white;
}

.suggestion-item:last-child {
    border-bottom: none;
}

/* Quote Results Modal Updates */
.price-display {
    font-size: 2.5rem;
    font-weight: 700;
    color: #0a2463;
    text-align: center;
    margin: 1.5rem 0;
}

.estimate-details {
    background: #f8f9fa;
    padding: 1.5rem;
    border-radius: 10px;
    margin: 1.5rem 0;
}

/* Mobile Responsive Styles */
@media (max-width: 768px) {
    .suggestions-dropdown {
        max-height: 200px;
          width: 90%;
        font-size: 0.9rem;
        border-radius: 6px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    }
    
    .suggestion-item {
        padding: 10px 12px;
        font-size: 0.9rem;
    }
    
    .price-display {
        font-size: 2rem;
    }
    
    .estimate-details {
        padding: 1rem;
    }
}

@media (max-width: 576px) {
    .suggestions-dropdown {
        max-height: 180px;
        font-size: 0.85rem;
    }
    
    .suggestion-item {
        padding: 8px 10px;
        font-size: 0.85rem;
    }
    
    .price-display {
        font-size: 1.75rem;
    }
}

/* Prevent body scroll when suggestions are open on mobile */
@media (max-width: 768px) {
    .position-relative {
        position: relative !important;
    }
    
    /* Ensure parent containers don't overflow */
    .col-md-6 {
        overflow: visible;
    }
    
    /* Make sure the suggestions don't cause horizontal scroll */
    .suggestions-dropdown {
        left: 0;
        right: 0;
    }
}

/* Smooth scrolling for suggestions */
.suggestions-dropdown {
    -webkit-overflow-scrolling: touch;
    scroll-behavior: smooth;
}

/* Custom scrollbar for suggestions */
.suggestions-dropdown::-webkit-scrollbar {
    width: 6px;
}

.suggestions-dropdown::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

.suggestions-dropdown::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 10px;
}

.suggestions-dropdown::-webkit-scrollbar-thumb:hover {
    background: #555;
}



/* Phone Input Styling */
.phone-input-wrapper {
    display: flex;
    align-items: center;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    background: white;
    transition: all 0.3s;
    overflow: hidden;
}

.phone-input-wrapper:focus-within {
    border-color: #ff6b35;
    box-shadow: 0 0 0 0.2rem rgba(255, 107, 53, 0.15);
}

.selected-country {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 12px 15px;
    background: #f8f9fa;
    border-right: 1px solid #dee2e6;
    cursor: pointer;
    transition: all 0.2s;
    min-width: 110px;
}

.selected-country:hover {
    background: #e9ecef;
}

.country-flag {
    width: 24px;
    height: 16px;
    object-fit: cover;
    border-radius: 2px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.country-code {
    font-weight: 600;
    color: #0a2463;
    font-size: 0.95rem;
}

.phone-number-input {
    border: none !important;
    padding: 12px 15px;
    flex: 1;
    outline: none;
    box-shadow: none !important;
}

.phone-number-input:focus {
    box-shadow: none !important;
}

/* Country Code Suggestions Dropdown */
.country-suggestions-dropdown {
    position: absolute;
    z-index: 1050;
    width: 40%;
    max-height: 200px;
    overflow-y: auto;
    background: white;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    box-shadow: 0 4px 16px rgba(0,0,0,0.15);
    margin-top: 4px;
}

.country-suggestion-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 15px;
    cursor: pointer;
    border-bottom: 1px solid #f8f9fa;
    transition: all 0.2s;
}

.country-suggestion-item:hover,
.country-suggestion-item.active {
    background-color: #f8f9fa;
}

.country-suggestion-item.active {
    background-color: #e3f2fd;
}

.country-suggestion-item:last-child {
    border-bottom: none;
}

.country-info {
    display: flex;
    align-items: center;
    gap: 10px;
    flex: 1;
}

.country-name {
    font-weight: 500;
    color: #212529;
    font-size: 0.95rem;
}

.country-dial-code {
    color: #6c757d;
    font-size: 0.9rem;
    margin-left: auto;
}

/* Mobile Responsive */
@media (max-width: 768px) {
    .selected-country {
        min-width: 95px;
        padding: 10px 12px;
        gap: 6px;
    }
    
    .country-code {
        font-size: 0.9rem;
    }
    
    .country-flag {
        width: 20px;
        height: 14px;
    }
    
    .phone-number-input {
        padding: 10px 12px;
        font-size: 0.95rem;
    }
    
    .country-suggestions-dropdown {
        max-height: 250px;
          width: 60%;
    }
    
    .country-suggestion-item {
        padding: 10px 12px;
    }
    
    .country-name {
        font-size: 0.9rem;
    }
}

@media (max-width: 576px) {
    .selected-country {
        min-width: 85px;
        padding: 8px 10px;
        gap: 5px;
    }
    
    .country-flag {
        width: 18px;
        height: 12px;
    }
    
    .country-code {
        font-size: 0.85rem;
    }
    
    .phone-number-input {
        padding: 8px 10px;
        font-size: 0.9rem;
    }
}

/* Custom scrollbar for country suggestions */
.country-suggestions-dropdown::-webkit-scrollbar {
    width: 8px;
}

.country-suggestions-dropdown::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

.country-suggestions-dropdown::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 10px;
}

.country-suggestions-dropdown::-webkit-scrollbar-thumb:hover {
    background: #555;
}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    // All countries list (200+ countries)
const allCountries = [
    "Afghanistan", "Albania", "Algeria", "Andorra", "Angola", "Antigua and Barbuda",
    "Argentina", "Armenia", "Australia", "Austria", "Azerbaijan", "Bahamas", "Bahrain",
    "Bangladesh", "Barbados", "Belarus", "Belgium", "Belize", "Benin", "Bhutan",
    "Bolivia", "Bosnia and Herzegovina", "Botswana", "Brazil", "Brunei", "Bulgaria",
    "Burkina Faso", "Burundi", "Cabo Verde", "Cambodia", "Cameroon", "Canada",
    "Central African Republic", "Chad", "Chile", "China", "Colombia", "Comoros",
    "Congo", "Costa Rica", "Croatia", "Cuba", "Cyprus", "Czech Republic",
    "Democratic Republic of the Congo", "Denmark", "Djibouti", "Dominica",
    "Dominican Republic", "Ecuador", "Egypt", "El Salvador", "Equatorial Guinea",
    "Eritrea", "Estonia", "Eswatini", "Ethiopia", "Fiji", "Finland", "France", "Gabon",
    "Gambia", "Georgia", "Germany", "Ghana", "Greece", "Grenada", "Guatemala",
    "Guinea", "Guinea-Bissau", "Guyana", "Haiti", "Honduras", "Hungary", "Iceland",
    "India", "Indonesia", "Iran", "Iraq", "Ireland", "Israel", "Italy", "Jamaica",
    "Japan", "Jordan", "Kazakhstan", "Kenya", "Kiribati", "Kosovo", "Kuwait",
    "Kyrgyzstan", "Laos", "Latvia", "Lebanon", "Lesotho", "Liberia", "Libya",
    "Liechtenstein", "Lithuania", "Luxembourg", "Madagascar", "Malawi", "Malaysia",
    "Maldives", "Mali", "Malta", "Marshall Islands", "Mauritania", "Mauritius",
    "Mexico", "Micronesia", "Moldova", "Monaco", "Mongolia", "Montenegro", "Morocco",
    "Mozambique", "Myanmar", "Namibia", "Nauru", "Nepal", "Netherlands", "New Zealand",
    "Nicaragua", "Niger", "Nigeria", "North Korea", "North Macedonia", "Norway",
    "Oman", "Pakistan", "Palau", "Palestine", "Panama", "Papua New Guinea", "Paraguay",
    "Peru", "Philippines", "Poland", "Portugal", "Qatar", "Romania", "Russia",
    "Rwanda", "Saint Kitts and Nevis", "Saint Lucia", "Saint Vincent and the Grenadines",
    "Samoa", "San Marino", "Sao Tome and Principe", "Saudi Arabia", "Senegal",
    "Serbia", "Seychelles", "Sierra Leone", "Singapore", "Slovakia", "Slovenia",
    "Solomon Islands", "Somalia", "South Africa", "South Korea", "South Sudan", "Spain",
    "Sri Lanka", "Sudan", "Suriname", "Sweden", "Switzerland", "Syria", "Taiwan",
    "Tajikistan", "Tanzania", "Thailand", "Timor-Leste", "Togo", "Tonga",
    "Trinidad and Tobago", "Tunisia", "Turkey", "Turkmenistan", "Tuvalu", "Uganda",
    "Ukraine", "United Arab Emirates", "United Kingdom", "United States", "Uruguay",
    "Uzbekistan", "Vanuatu", "Vatican City", "Venezuela", "Vietnam", "Yemen", "Zambia", "Zimbabwe"
];

document.addEventListener('DOMContentLoaded', function() {
    const quoteForm = document.getElementById('quoteForm');
    const quoteResultsModal = document.getElementById('quoteResultsModal');
    const closeModalBtn = document.getElementById('closeModal');
    const contactSalesBtn = document.getElementById('contactSales');
    
    // Country search elements
    const pickupInput = document.getElementById('pickupCountry');
    const deliveryInput = document.getElementById('deliveryCountry');
    const pickupSuggestions = document.getElementById('pickupSuggestions');
    const deliverySuggestions = document.getElementById('deliverySuggestions');
    
    let selectedPickup = '';
    let selectedDelivery = '';
    
    // Highlight matching text in suggestions
    function highlightMatch(text, searchTerm) {
        const regex = new RegExp(`(${searchTerm})`, 'gi');
        return text.replace(regex, '<strong>$1</strong>');
    }
    
    // Country search function
    function showSuggestions(input, suggestionsDiv, type) {
        const searchTerm = input.value.toLowerCase().trim();
        
        if (searchTerm.length < 1) {
            suggestionsDiv.style.display = 'none';
            return;
        }
        
        // Filter countries (case-insensitive)
        const filtered = allCountries.filter(country => 
            country.toLowerCase().includes(searchTerm)
        ).slice(0, 8); // Show top 8 matches
        
        if (filtered.length === 0) {
            suggestionsDiv.innerHTML = '<div class="suggestion-item" style="cursor: default; color: #999;">No countries found</div>';
            suggestionsDiv.style.display = 'block';
            return;
        }
        
        // Build suggestions HTML
        suggestionsDiv.innerHTML = filtered.map(country => `
            <div class="suggestion-item" data-country="${country}">
                ${highlightMatch(country, searchTerm)}
            </div>
        `).join('');
        
        suggestionsDiv.style.display = 'block';
        
        // Add mousedown handlers to suggestions
        suggestionsDiv.querySelectorAll('.suggestion-item[data-country]').forEach(item => {
            item.addEventListener('mousedown', function(e) {
                e.preventDefault(); // Prevent blur event
                const country = this.getAttribute('data-country');
                input.value = country;
                suggestionsDiv.style.display = 'none';
                
                if (type === 'pickup') {
                    selectedPickup = country;
                } else {
                    selectedDelivery = country;
                }
                
                // Refocus the input after selection
                setTimeout(() => input.blur(), 100);
            });
        });
    }
    
    // Setup country search for pickup
    if (pickupInput) {
        pickupInput.addEventListener('input', function() {
            showSuggestions(this, pickupSuggestions, 'pickup');
        });
        
        pickupInput.addEventListener('focus', function() {
            if (this.value.length >= 1) {
                showSuggestions(this, pickupSuggestions, 'pickup');
            }
        });
        
        pickupInput.addEventListener('blur', function() {
            setTimeout(() => {
                pickupSuggestions.style.display = 'none';
            }, 300);
        });
    }
    
    // Setup country search for delivery
    if (deliveryInput) {
        deliveryInput.addEventListener('input', function() {
            showSuggestions(this, deliverySuggestions, 'delivery');
        });
        
        deliveryInput.addEventListener('focus', function() {
            if (this.value.length >= 1) {
                showSuggestions(this, deliverySuggestions, 'delivery');
            }
        });
        
        deliveryInput.addEventListener('blur', function() {
            setTimeout(() => {
                deliverySuggestions.style.display = 'none';
            }, 300);
        });
    }
    
    // Close suggestions when clicking outside
    document.addEventListener('click', function(e) {
        if (pickupInput && pickupSuggestions && !pickupInput.contains(e.target) && !pickupSuggestions.contains(e.target)) {
            pickupSuggestions.style.display = 'none';
        }
        if (deliveryInput && deliverySuggestions && !deliveryInput.contains(e.target) && !deliverySuggestions.contains(e.target)) {
            deliverySuggestions.style.display = 'none';
        }
    });
    
    // Navigate suggestions with arrow keys
    function setupKeyboardNavigation(input, suggestionsDiv) {
        let selectedIndex = -1;
        
        input.addEventListener('keydown', function(e) {
            const items = suggestionsDiv.querySelectorAll('.suggestion-item[data-country]');
            
            if (items.length === 0 || suggestionsDiv.style.display === 'none') return;
            
            if (e.key === 'ArrowDown') {
                e.preventDefault();
                selectedIndex = Math.min(selectedIndex + 1, items.length - 1);
                updateSelection(items, selectedIndex);
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                selectedIndex = Math.max(selectedIndex - 1, -1);
                updateSelection(items, selectedIndex);
            } else if (e.key === 'Enter' && selectedIndex >= 0) {
                e.preventDefault();
                const country = items[selectedIndex].getAttribute('data-country');
                input.value = country;
                suggestionsDiv.style.display = 'none';
                selectedIndex = -1;
            } else if (e.key === 'Escape') {
                suggestionsDiv.style.display = 'none';
                selectedIndex = -1;
            }
        });
        
        function updateSelection(items, index) {
            items.forEach((item, i) => {
                item.classList.toggle('active', i === index);
            });
            
            if (index >= 0 && items[index]) {
                items[index].scrollIntoView({ block: 'nearest' });
            }
        }
    }
    
    if (pickupInput && pickupSuggestions) {
        setupKeyboardNavigation(pickupInput, pickupSuggestions);
    }
    if (deliveryInput && deliverySuggestions) {
        setupKeyboardNavigation(deliveryInput, deliverySuggestions);
    }
    
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
    
    // Form validation and submission
    if (quoteForm) {
        quoteForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Get form values
            const pickup = pickupInput ? pickupInput.value.trim() : '';
            const delivery = deliveryInput ? deliveryInput.value.trim() : '';
            const weight = document.getElementById('weight') ? document.getElementById('weight').value : '';
            const type = document.getElementById('packageType') ? document.getElementById('packageType').value : '';
            const email = document.getElementById('email') ? document.getElementById('email').value : '';
            const phone = document.getElementById('phone') ? document.getElementById('phone').value : '';
            
            // Validate form
            if (!pickup || !delivery || !weight || !type || !email || !phone) {
                alert('Please fill in all required fields.');
                return;
            }
            
            // Validate countries (case-insensitive check)
            const pickupValid = allCountries.some(country => 
                country.toLowerCase() === pickup.toLowerCase()
            );
            const deliveryValid = allCountries.some(country => 
                country.toLowerCase() === delivery.toLowerCase()
            );
            
            if (!pickupValid) {
                alert('Please select a valid pickup country from the suggestions.');
                if (pickupInput) pickupInput.focus();
                return;
            }
            
            if (!deliveryValid) {
                alert('Please select a valid delivery country from the suggestions.');
                if (deliveryInput) deliveryInput.focus();
                return;
            }
            
            if (pickup.toLowerCase() === delivery.toLowerCase()) {
                alert('Pickup and delivery countries cannot be the same.');
                return;
            }
            
            // Show loading state
            const submitBtn = quoteForm.querySelector('button[type="submit"]');
            if (submitBtn) {
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Calculating...';
                submitBtn.disabled = true;
            }
            
            // Submit the form
            setTimeout(() => {
                quoteForm.submit();
            }, 1500);
        });
    }
    
    // Auto-focus pickup country on page load
    if (pickupInput && quoteResultsModal && !quoteResultsModal.classList.contains('show')) {
        setTimeout(() => pickupInput.focus(), 100);
    }
});







// Country codes data with flags
const countryCodes = [
    { name: "United States", code: "US", dial: "+1" },
    { name: "United Kingdom", code: "GB", dial: "+44" },
    { name: "Canada", code: "CA", dial: "+1" },
    { name: "Australia", code: "AU", dial: "+61" },
    { name: "Germany", code: "DE", dial: "+49" },
    { name: "France", code: "FR", dial: "+33" },
    { name: "Nigeria", code: "NG", dial: "+234" },
    { name: "South Africa", code: "ZA", dial: "+27" },
    { name: "Kenya", code: "KE", dial: "+254" },
    { name: "Ghana", code: "GH", dial: "+233" },
    { name: "India", code: "IN", dial: "+91" },
    { name: "China", code: "CN", dial: "+86" },
    { name: "Japan", code: "JP", dial: "+81" },
    { name: "South Korea", code: "KR", dial: "+82" },
    { name: "Brazil", code: "BR", dial: "+55" },
    { name: "Mexico", code: "MX", dial: "+52" },
    { name: "Argentina", code: "AR", dial: "+54" },
    { name: "Spain", code: "ES", dial: "+34" },
    { name: "Italy", code: "IT", dial: "+39" },
    { name: "Netherlands", code: "NL", dial: "+31" },
    { name: "Belgium", code: "BE", dial: "+32" },
    { name: "Switzerland", code: "CH", dial: "+41" },
    { name: "Sweden", code: "SE", dial: "+46" },
    { name: "Norway", code: "NO", dial: "+47" },
    { name: "Denmark", code: "DK", dial: "+45" },
    { name: "Finland", code: "FI", dial: "+358" },
    { name: "Poland", code: "PL", dial: "+48" },
    { name: "Russia", code: "RU", dial: "+7" },
    { name: "Turkey", code: "TR", dial: "+90" },
    { name: "Saudi Arabia", code: "SA", dial: "+966" },
    { name: "United Arab Emirates", code: "AE", dial: "+971" },
    { name: "Egypt", code: "EG", dial: "+20" },
    { name: "Israel", code: "IL", dial: "+972" },
    { name: "Pakistan", code: "PK", dial: "+92" },
    { name: "Bangladesh", code: "BD", dial: "+880" },
    { name: "Philippines", code: "PH", dial: "+63" },
    { name: "Indonesia", code: "ID", dial: "+62" },
    { name: "Malaysia", code: "MY", dial: "+60" },
    { name: "Singapore", code: "SG", dial: "+65" },
    { name: "Thailand", code: "TH", dial: "+66" },
    { name: "Vietnam", code: "VN", dial: "+84" },
    { name: "New Zealand", code: "NZ", dial: "+64" },
    { name: "Ireland", code: "IE", dial: "+353" },
    { name: "Portugal", code: "PT", dial: "+351" },
    { name: "Greece", code: "GR", dial: "+30" },
    { name: "Austria", code: "AT", dial: "+43" },
    { name: "Czech Republic", code: "CZ", dial: "+420" },
    { name: "Hungary", code: "HU", dial: "+36" },
    { name: "Romania", code: "RO", dial: "+40" },
    { name: "Ukraine", code: "UA", dial: "+380" },
    { name: "Colombia", code: "CO", dial: "+57" },
    { name: "Chile", code: "CL", dial: "+56" },
    { name: "Peru", code: "PE", dial: "+51" },
    { name: "Venezuela", code: "VE", dial: "+58" },
    { name: "Ethiopia", code: "ET", dial: "+251" },
    { name: "Tanzania", code: "TZ", dial: "+255" },
    { name: "Uganda", code: "UG", dial: "+256" },
    { name: "Morocco", code: "MA", dial: "+212" },
    { name: "Algeria", code: "DZ", dial: "+213" },
    { name: "Tunisia", code: "TN", dial: "+216" }
].sort((a, b) => a.name.localeCompare(b.name));

document.addEventListener('DOMContentLoaded', function() {
    const phoneInput = document.getElementById('phone');
    const selectedCountryFlag = document.getElementById('selectedCountryFlag');
    const countryCodeSuggestions = document.getElementById('countryCodeSuggestions');
    const phoneCountryCodeInput = document.getElementById('phoneCountryCode');
    const phoneCountryInput = document.getElementById('phoneCountry');
    
    let currentCountry = countryCodes[0]; // Default to US
    let selectedIndex = -1;
    
    // Update the displayed country flag and code
    function updateSelectedCountry(country) {
        currentCountry = country;
        selectedCountryFlag.innerHTML = `
            <img src="https://flagcdn.com/w40/${country.code.toLowerCase()}.png" 
                 alt="${country.code}" 
                 class="country-flag"
                 onerror="this.src='https://flagcdn.com/w40/un.png'">
            <span class="country-code">${country.dial}</span>
            <i class="ri-arrow-down-s-line"></i>
        `;
        phoneCountryCodeInput.value = country.dial;
        phoneCountryInput.value = country.code;
    }
    
    // Show country suggestions
    function showCountrySuggestions(searchTerm = '') {
        let filtered = countryCodes;
        
        if (searchTerm) {
            const term = searchTerm.toLowerCase().replace('+', '');
            filtered = countryCodes.filter(country => 
                country.name.toLowerCase().includes(term) ||
                country.dial.replace('+', '').startsWith(term) ||
                country.code.toLowerCase().includes(term)
            );
        }
        
        if (filtered.length === 0) {
            countryCodeSuggestions.innerHTML = '<div class="country-suggestion-item" style="cursor: default; color: #999;">No countries found</div>';
            countryCodeSuggestions.style.display = 'block';
            return;
        }
        
        countryCodeSuggestions.innerHTML = filtered.slice(0, 10).map(country => `
            <div class="country-suggestion-item" data-code="${country.code}" data-dial="${country.dial}">
                <div class="country-info">
                    <img src="https://flagcdn.com/w40/${country.code.toLowerCase()}.png" 
                         alt="${country.code}" 
                         class="country-flag"
                         onerror="this.src='https://flagcdn.com/w40/un.png'">
                    <span class="country-name">${country.name}</span>
                    <span class="country-dial-code">${country.dial}</span>
                </div>
            </div>
        `).join('');
        
        countryCodeSuggestions.style.display = 'block';
        selectedIndex = -1;
        
        // Add click handlers
        countryCodeSuggestions.querySelectorAll('.country-suggestion-item[data-code]').forEach(item => {
            item.addEventListener('mousedown', function(e) {
                e.preventDefault();
                const countryCode = this.getAttribute('data-code');
                const country = countryCodes.find(c => c.code === countryCode);
                if (country) {
                    updateSelectedCountry(country);
                    countryCodeSuggestions.style.display = 'none';
                    phoneInput.focus();
                    phoneInput.value = '';
                }
            });
        });
    }
    
    // Toggle country dropdown on flag click
    if (selectedCountryFlag) {
        selectedCountryFlag.addEventListener('click', function(e) {
            e.stopPropagation();
            if (countryCodeSuggestions.style.display === 'block') {
                countryCodeSuggestions.style.display = 'none';
            } else {
                showCountrySuggestions();
            }
        });
    }
    
    // Phone input listeners
    if (phoneInput) {
        // Detect when user types + or numbers that could be country codes
        phoneInput.addEventListener('input', function(e) {
            const value = this.value.trim();
            
            // If user types + or numbers at the start
            if (value.startsWith('+') || (value.length <= 4 && /^\d+$/.test(value))) {
                showCountrySuggestions(value);
            } else if (value.length === 0) {
                countryCodeSuggestions.style.display = 'none';
            }
        });
        
        phoneInput.addEventListener('focus', function() {
            // Show suggestions if input starts with + or is a number
            const value = this.value.trim();
            if (value.startsWith('+') || (value.length > 0 && value.length <= 4 && /^\d+$/.test(value))) {
                showCountrySuggestions(value);
            }
        });
        
        phoneInput.addEventListener('blur', function() {
            setTimeout(() => {
                countryCodeSuggestions.style.display = 'none';
            }, 300);
        });
        
        // Keyboard navigation
        phoneInput.addEventListener('keydown', function(e) {
            const items = countryCodeSuggestions.querySelectorAll('.country-suggestion-item[data-code]');
            
            if (items.length === 0 || countryCodeSuggestions.style.display === 'none') return;
            
            if (e.key === 'ArrowDown') {
                e.preventDefault();
                selectedIndex = Math.min(selectedIndex + 1, items.length - 1);
                updateSelectionHighlight(items, selectedIndex);
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                selectedIndex = Math.max(selectedIndex - 1, -1);
                updateSelectionHighlight(items, selectedIndex);
            } else if (e.key === 'Enter' && selectedIndex >= 0) {
                e.preventDefault();
                const countryCode = items[selectedIndex].getAttribute('data-code');
                const country = countryCodes.find(c => c.code === countryCode);
                if (country) {
                    updateSelectedCountry(country);
                    countryCodeSuggestions.style.display = 'none';
                    this.value = '';
                }
            } else if (e.key === 'Escape') {
                countryCodeSuggestions.style.display = 'none';
                selectedIndex = -1;
            }
        });
    }
    
    function updateSelectionHighlight(items, index) {
        items.forEach((item, i) => {
            item.classList.toggle('active', i === index);
        });
        
        if (index >= 0 && items[index]) {
            items[index].scrollIntoView({ block: 'nearest' });
        }
    }
    
    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (countryCodeSuggestions && 
            !phoneInput.contains(e.target) && 
            !selectedCountryFlag.contains(e.target) &&
            !countryCodeSuggestions.contains(e.target)) {
            countryCodeSuggestions.style.display = 'none';
        }
    });
    
    // Initialize with default country
    updateSelectedCountry(currentCountry);
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Dee\Desktop\General\globalskyfleet\resources\views/pages/quote.blade.php ENDPATH**/ ?>