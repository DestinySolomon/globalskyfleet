@extends('layouts.dashboard')

@section('title', 'Create Shipment | GlobalSkyFleet')
@section('page-title', 'Create Shipment')

@php
    // Get quote data from URL parameters
    $quoteWeight = request('weight', old('weight', ''));
    $quotePickupCountry = request('pickup', old('pickup', ''));
    $quoteDeliveryCountry = request('delivery', old('delivery', ''));
    $quotePackageType = request('type', old('package_type', 'Parcel'));
    $quoteValue = request('value', old('declared_value', ''));
    
    // Clean currency value if present
    if ($quoteValue && str_contains($quoteValue, '$')) {
        $quoteValue = preg_replace('/[^0-9.]/', '', $quoteValue);
    }
    
    // Map quote package type to service type
    $serviceMap = [
        'Document' => 'express',
        'Parcel' => 'standard',
        'Pallet' => 'economy',
        'Container' => 'economy'
    ];
    $defaultService = $serviceMap[$quotePackageType] ?? 'standard';
@endphp

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <!-- Quote Info Card (if coming from quote) -->
        @if($quoteWeight || $quotePickupCountry || $quoteDeliveryCountry)
        <div class="card border-0 shadow-sm mb-4 border-start border-4 border-info">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="bg-info bg-opacity-10 p-3 rounded me-3">
                        <i class="ri-file-list-3-line text-info fs-4"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="mb-1 fw-semibold text-info">
                            <i class="ri-arrow-right-line me-1"></i>Continuing from your quote
                        </h6>
                        <p class="text-muted small mb-0">
                            @if($quotePickupCountry && $quoteDeliveryCountry)
                                {{ $quotePickupCountry }} → {{ $quoteDeliveryCountry }}
                            @endif
                            @if($quoteWeight)
                                • {{ $quoteWeight }} kg
                            @endif
                            @if($quotePackageType)
                                • {{ $quotePackageType }}
                            @endif
                        </p>
                    </div>
                    <a href="{{ route('quote') }}" class="btn btn-sm btn-outline-info">
                        <i class="ri-edit-line me-1"></i>Edit Quote
                    </a>
                </div>
            </div>
        </div>
        @endif
        
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-semibold">
                        <i class="ri-add-circle-line me-2"></i>Create New Shipment
                    </h5>
                    @if($quoteWeight)
                    <span class="badge bg-info">Quote Data Pre-filled</span>
                    @endif
                </div>
            </div>
            
            <div class="card-body">
                <form action="{{ route('shipments.store') }}" method="POST">
                    @csrf
                    
                    <!-- Hidden fields to pass quote data -->
                    @if($quoteWeight)
                        <input type="hidden" name="from_quote" value="1">
                        <input type="hidden" name="quote_weight" value="{{ $quoteWeight }}">
                        <input type="hidden" name="quote_pickup" value="{{ $quotePickupCountry }}">
                        <input type="hidden" name="quote_delivery" value="{{ $quoteDeliveryCountry }}">
                        <input type="hidden" name="quote_type" value="{{ $quotePackageType }}">
                    @endif
                    
                    <!-- Sender & Recipient -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="mb-3 text-primary">
                                <i class="ri-map-pin-line me-2"></i>Sender Address
                            </h6>
                            <div class="mb-3">
                                <label class="form-label">Select Sender Address *</label>
                                <select name="sender_address_id" class="form-select" required id="sender_address">
                                    <option value="">Choose sender address...</option>
                                    @foreach($addresses as $address)
                                    <option value="{{ $address->id }}" 
                                        {{ old('sender_address_id') == $address->id ? 'selected' : '' }}
                                        data-country="{{ $address->country }}">
                                        {{ $address->name }} - {{ $address->address_line1 }}, {{ $address->city }}, {{ $address->country }}
                                    </option>
                                    @endforeach
                                </select>
                                @if($quotePickupCountry && $addresses->count() > 0)
                                <div class="form-text text-info">
                                    <i class="ri-information-line me-1"></i>
                                    Looking for addresses in {{ $quotePickupCountry }}
                                </div>
                                @endif
                            </div>
                            
                            @if($addresses->count() === 0)
                            <div class="alert alert-warning">
                                <div class="d-flex">
                                    <i class="ri-alert-line me-2 mt-1"></i>
                                    <div>
                                        <strong>No addresses found</strong>
                                        <p class="small mb-0">You need to add an address to your address book first.</p>
                                        <a href="{{ route('addresses.create') }}" class="btn btn-sm btn-warning mt-2">
                                            <i class="ri-add-line me-1"></i>Add Address
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                        
                        <div class="col-md-6">
                            <h6 class="mb-3 text-success">
                                <i class="ri-map-pin-line me-2"></i>Recipient Address
                            </h6>
                            <div class="mb-3">
                                <label class="form-label">Select Recipient Address *</label>
                                <select name="recipient_address_id" class="form-select" required id="recipient_address">
                                    <option value="">Choose recipient address...</option>
                                    @foreach($addresses as $address)
                                    <option value="{{ $address->id }}"
                                        {{ old('recipient_address_id') == $address->id ? 'selected' : '' }}
                                        data-country="{{ $address->country }}">
                                        {{ $address->name }} - {{ $address->address_line1 }}, {{ $address->city }}, {{ $address->country }}
                                    </option>
                                    @endforeach
                                </select>
                                @if($quoteDeliveryCountry && $addresses->count() > 0)
                                <div class="form-text text-info">
                                    <i class="ri-information-line me-1"></i>
                                    Looking for addresses in {{ $quoteDeliveryCountry }}
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <!-- Shipment Details -->
                    <div class="mb-4">
                        <h6 class="mb-3">
                            <i class="ri-box-line me-2"></i>Package Details
                        </h6>
                        
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Weight (kg) *</label>
                                <input type="number" 
                                       name="weight" 
                                       class="form-control" 
                                       value="{{ old('weight', $quoteWeight) }}"
                                       step="0.01" 
                                       min="0.1" 
                                       max="1000" 
                                       required
                                       placeholder="e.g., 2.5"
                                       id="weight_input">
                                <div class="form-text">
                                    Minimum 0.1 kg, maximum 1000 kg
                                    @if($quoteWeight)
                                    <span class="text-info">
                                        <i class="ri-check-line me-1"></i>From your quote
                                    </span>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">Declared Value ($) *</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" 
                                           name="declared_value" 
                                           class="form-control" 
                                           value="{{ old('declared_value', $quoteValue) }}"
                                           step="0.01" 
                                           min="0" 
                                           max="1000000" 
                                           required
                                           placeholder="e.g., 500.00"
                                           id="declared_value_input">
                                </div>
                                <div class="form-text">Value of contents for insurance purposes</div>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">Service Type *</label>
                                <select name="service_type" class="form-select" required id="service_type">
                                    <option value="">Select service...</option>
                                    <option value="express" {{ old('service_type', $defaultService) == 'express' ? 'selected' : '' }}>
                                        Express Delivery (1-3 days) - Fastest
                                    </option>
                                    <option value="standard" {{ old('service_type', $defaultService) == 'standard' ? 'selected' : '' }}>
                                        Standard (3-5 days) - Recommended
                                    </option>
                                    <option value="economy" {{ old('service_type', $defaultService) == 'economy' ? 'selected' : '' }}>
                                        Economy Shipping (5-7 days) - Most Economical
                                    </option>
                                </select>
                                <div class="form-text">
                                    @if($quotePackageType)
                                    <span class="text-info">
                                        <i class="ri-information-line me-1"></i>
                                        Suggested based on your {{ $quotePackageType }} quote
                                    </span>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">Content Description *</label>
                                <input type="text" 
                                       name="content_description" 
                                       class="form-control" 
                                       value="{{ old('content_description') }}"
                                       required 
                                       placeholder="e.g., Electronics, Documents, Clothing">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Dimensions (Optional but recommended) -->
                    <div class="mb-4">
                        <h6 class="mb-3">
                            <i class="ri-ruler-line me-2"></i>Package Dimensions (Optional)
                        </h6>
                        
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Length (cm)</label>
                                <input type="number" 
                                       name="dimensions_length" 
                                       class="form-control" 
                                       value="{{ old('dimensions_length') }}"
                                       step="0.1" 
                                       min="1" 
                                       placeholder="Length">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Width (cm)</label>
                                <input type="number" 
                                       name="dimensions_width" 
                                       class="form-control" 
                                       value="{{ old('dimensions_width') }}"
                                       step="0.1" 
                                       min="1" 
                                       placeholder="Width">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Height (cm)</label>
                                <input type="number" 
                                       name="dimensions_height" 
                                       class="form-control" 
                                       value="{{ old('dimensions_height') }}"
                                       step="0.1" 
                                       min="1" 
                                       placeholder="Height">
                            </div>
                        </div>
                        <div class="form-text">Accurate dimensions help with proper pricing and handling</div>
                    </div>
                    
                    <!-- Additional Options -->
                    <div class="mb-4">
                        <h6 class="mb-3">
                            <i class="ri-settings-3-line me-2"></i>Additional Options
                        </h6>
                        
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" 
                                           type="checkbox" 
                                           name="requires_signature" 
                                           id="requires_signature" 
                                           value="1"
                                           {{ old('requires_signature') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="requires_signature">
                                        <i class="ri-signature-line me-1"></i>Require Signature on Delivery
                                    </label>
                                    <div class="form-text small">Extra security for valuable items</div>
                                </div>
                                
                                <div class="form-check mt-3">
                                    <input class="form-check-input" 
                                           type="checkbox" 
                                           name="is_dangerous_goods" 
                                           id="is_dangerous_goods" 
                                           value="1"
                                           {{ old('is_dangerous_goods') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_dangerous_goods">
                                        <i class="ri-alert-line me-1"></i>Contains Dangerous Goods
                                    </label>
                                    <div class="form-text small">Batteries, liquids, flammable materials</div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" 
                                           type="checkbox" 
                                           name="insurance_enabled" 
                                           id="insurance_enabled" 
                                           value="1"
                                           {{ old('insurance_enabled') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="insurance_enabled">
                                        <i class="ri-shield-check-line me-1"></i>Add Insurance
                                    </label>
                                    <div class="form-text small">Protect your shipment</div>
                                </div>
                                
                                <div class="mt-3" id="insurance_amount_field" style="{{ old('insurance_enabled') ? 'display: block;' : 'display: none;' }}">
                                    <label class="form-label">Insurance Amount ($)</label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" 
                                               name="insurance_amount" 
                                               class="form-control" 
                                               value="{{ old('insurance_amount', $quoteValue) }}"
                                               step="0.01" 
                                               min="0" 
                                               placeholder="Insurance amount">
                                    </div>
                                    <div class="form-text small">Recommended: Same as declared value</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Special Instructions -->
                    <div class="mb-4">
                        <label class="form-label">
                            <i class="ri-chat-3-line me-1"></i>Special Instructions (Optional)
                        </label>
                        <textarea name="special_instructions" 
                                  class="form-control" 
                                  rows="3" 
                                  placeholder="Any special handling instructions, delivery time preferences, or additional information...">{{ old('special_instructions') }}</textarea>
                        <div class="form-text">These instructions will be shared with the delivery team</div>
                    </div>
                    
                    <!-- Pickup Date (Optional) -->
                    <div class="mb-4">
                        <label class="form-label">
                            <i class="ri-calendar-line me-1"></i>Preferred Pickup Date (Optional)
                        </label>
                        <input type="date" 
                               name="pickup_date" 
                               class="form-control" 
                               value="{{ old('pickup_date') }}"
                               min="{{ date('Y-m-d') }}">
                        <div class="form-text">Leave empty for earliest possible pickup</div>
                    </div>
                    
                    <!-- Submit Buttons -->
                    <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                        <div>
                            <a href="{{ route('shipments.index') }}" class="btn btn-outline-secondary">
                                <i class="ri-arrow-left-line me-2"></i>Back to Shipments
                            </a>
                            @if($quoteWeight)
                            <a href="{{ route('quote') }}" class="btn btn-outline-info ms-2">
                                <i class="ri-file-list-3-line me-2"></i>Back to Quote
                            </a>
                            @endif
                        </div>
                        
                        <div>
                            <button type="button" class="btn btn-outline-primary me-2" id="saveDraftBtn">
                                <i class="ri-save-line me-2"></i>Save as Draft
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="ri-ship-line me-2"></i>Create Shipment
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Help Card -->
        <div class="card border-0 shadow-sm mt-4">
            <div class="card-body">
                <h6 class="mb-3">
                    <i class="ri-question-line me-2"></i>Need Help?
                </h6>
                <div class="row">
                    <div class="col-md-6">
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2">
                                <i class="ri-checkbox-circle-line text-success me-2"></i>
                                Make sure addresses are correct
                            </li>
                            <li class="mb-2">
                                <i class="ri-checkbox-circle-line text-success me-2"></i>
                                Weight must be accurate for pricing
                            </li>
                            <li class="mb-2">
                                <i class="ri-checkbox-circle-line text-success me-2"></i>
                                Select appropriate service type
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2">
                                <i class="ri-checkbox-circle-line text-success me-2"></i>
                                Add insurance for valuable items
                            </li>
                            <li class="mb-2">
                                <i class="ri-checkbox-circle-line text-success me-2"></i>
                                Include dimensions for accuracy
                            </li>
                            <li>
                                <a href="{{ route('contact') }}" class="btn btn-sm btn-outline-primary mt-2">
                                    <i class="ri-customer-service-line me-1"></i>Contact Support
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Show/hide insurance amount field
    const insuranceCheckbox = document.getElementById('insurance_enabled');
    const insuranceField = document.getElementById('insurance_amount_field');
    
    if (insuranceCheckbox && insuranceField) {
        insuranceCheckbox.addEventListener('change', function() {
            insuranceField.style.display = this.checked ? 'block' : 'none';
            
            // Auto-fill insurance amount with declared value if empty
            if (this.checked) {
                const declaredValue = document.getElementById('declared_value_input').value;
                const insuranceAmountInput = insuranceField.querySelector('input[name="insurance_amount"]');
                if (declaredValue && (!insuranceAmountInput.value || insuranceAmountInput.value === '0')) {
                    insuranceAmountInput.value = declaredValue;
                }
            }
        });
    }
    
    // Auto-select addresses based on quote countries
    const quotePickupCountry = "{{ $quotePickupCountry }}";
    const quoteDeliveryCountry = "{{ $quoteDeliveryCountry }}";
    
    if (quotePickupCountry) {
        const senderSelect = document.getElementById('sender_address');
        const pickupOptions = Array.from(senderSelect.options);
        
        // Try to find exact match first
        let exactMatch = pickupOptions.find(option => 
            option.getAttribute('data-country') === quotePickupCountry
        );
        
        if (exactMatch) {
            exactMatch.selected = true;
        } else {
            // Try partial match
            let partialMatch = pickupOptions.find(option => 
                option.textContent.includes(quotePickupCountry)
            );
            
            if (partialMatch) {
                partialMatch.selected = true;
            }
        }
    }
    
    if (quoteDeliveryCountry) {
        const recipientSelect = document.getElementById('recipient_address');
        const deliveryOptions = Array.from(recipientSelect.options);
        
        // Try to find exact match first
        let exactMatch = deliveryOptions.find(option => 
            option.getAttribute('data-country') === quoteDeliveryCountry
        );
        
        if (exactMatch) {
            exactMatch.selected = true;
        } else {
            // Try partial match
            let partialMatch = deliveryOptions.find(option => 
                option.textContent.includes(quoteDeliveryCountry)
            );
            
            if (partialMatch) {
                partialMatch.selected = true;
            }
        }
    }
    
    // Form validation
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            const sender = this.querySelector('[name="sender_address_id"]');
            const recipient = this.querySelector('[name="recipient_address_id"]');
            const weight = this.querySelector('[name="weight"]');
            
            // Validate sender and recipient are different
            if (sender.value && recipient.value && sender.value === recipient.value) {
                e.preventDefault();
                alert('❌ Sender and recipient addresses cannot be the same.');
                sender.focus();
                return;
            }
            
            // Validate weight
            if (weight.value && (weight.value < 0.1 || weight.value > 1000)) {
                e.preventDefault();
                alert('❌ Weight must be between 0.1 kg and 1000 kg.');
                weight.focus();
                return;
            }
            
            // Validate at least one address exists
            const addressSelects = document.querySelectorAll('select[name$="_address_id"]');
            const hasAddresses = Array.from(addressSelects).some(select => select.options.length > 1);
            
            if (!hasAddresses) {
                e.preventDefault();
                alert('❌ You need to add addresses to your address book first.\n\nPlease click "Add Address" to create an address.');
                return;
            }
            
            // Show loading
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Creating...';
            submitBtn.disabled = true;
        });
    }
    
    // Save as draft button
    const saveDraftBtn = document.getElementById('saveDraftBtn');
    if (saveDraftBtn) {
        saveDraftBtn.addEventListener('click', function() {
            const form = document.querySelector('form');
            const draftInput = document.createElement('input');
            draftInput.type = 'hidden';
            draftInput.name = 'save_as_draft';
            draftInput.value = '1';
            form.appendChild(draftInput);
            
            // Change form action if you have a draft endpoint
            
            // For now, just alert and submit
            if (confirm('Save as draft? You can complete this shipment later.')) {
                form.submit();
            }
        });
    }
    
    // Auto-calculate estimated delivery based on service type
    const serviceSelect = document.getElementById('service_type');
    const deliveryEstimateDiv = document.createElement('div');
    deliveryEstimateDiv.className = 'form-text text-info mt-1';
    serviceSelect.parentNode.appendChild(deliveryEstimateDiv);
    
    function updateDeliveryEstimate() {
        const service = serviceSelect.value;
        let estimate = '';
        
        switch(service) {
            case 'express':
                estimate = '📦 Estimated delivery: 1-3 business days';
                break;
            case 'standard':
                estimate = '📦 Estimated delivery: 3-5 business days';
                break;
            case 'economy':
                estimate = '📦 Estimated delivery: 5-7 business days';
                break;
            default:
                estimate = '';
        }
        
        deliveryEstimateDiv.innerHTML = estimate;
    }
    
    serviceSelect.addEventListener('change', updateDeliveryEstimate);
    updateDeliveryEstimate(); // Initial call
});
</script>
@endsection