@extends('layouts.dashboard')

@section('title', 'Create Shipment | GlobalSkyFleet')
@section('page-title', 'Create Shipment')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-semibold">
                        <i class="ri-add-circle-line me-2"></i>Create New Shipment
                    </h5>
                </div>
            </div>
            
            <div class="card-body">
                @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <div class="d-flex">
                        <i class="ri-error-warning-line me-2 mt-1"></i>
                        <div>
                            <strong>Validation Errors:</strong>
                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                <li>
                                    {!! nl2br(e($error)) !!}
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif
                
                @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <div class="d-flex">
                        <i class="ri-alert-line me-2 mt-1"></i>
                        <div>
                            {!! nl2br(e(session('error'))) !!}
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                <form action="{{ route('shipments.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="dimensions_unit" value="cm">
                    <input type="hidden" name="currency" value="USD">
                    
                    <!-- Sender & Recipient -->
                    <div class="row mb-4">
                        <!-- Sender Address (Billing) -->
                        <div class="col-md-6">
                            <h6 class="mb-3 text-primary">
                                <i class="ri-map-pin-line me-2"></i>Sender Address (Billing)
                            </h6>
                            <div class="mb-3">
                                <label class="form-label">Select Sender Address *</label>
                                <select name="sender_address_id" class="form-select" required id="sender_address">
                                    <option value="">Choose billing address...</option>
                                    @foreach($senderAddresses as $address)
                                    <option value="{{ $address->id }}" 
                                        {{ old('sender_address_id') == $address->id ? 'selected' : '' }}
                                        data-country="{{ $address->country_code }}">
                                        {{ $address->contact_name }} - {{ $address->address_line1 }}, {{ $address->city }}, {{ $address->country_code }}
                                    </option>
                                    @endforeach
                                </select>
                                
                                @if($senderAddresses->count() === 0)
                                <div class="alert alert-warning mt-2">
                                    <div class="d-flex">
                                        <i class="ri-alert-line me-2 mt-1"></i>
                                        <div>
                                            <strong>No billing addresses found</strong>
                                            <p class="small mb-0">You need to add a billing address first.</p>
                                            <a href="{{ route('addresses.create') }}?type=billing" class="btn btn-sm btn-warning mt-2">
                                                <i class="ri-add-line me-1"></i>Add Billing Address
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Recipient Address (Shipping) -->
                        <div class="col-md-6">
                            <h6 class="mb-3 text-success">
                                <i class="ri-map-pin-line me-2"></i>Recipient Address (Shipping)
                            </h6>
                            <div class="mb-3">
                                <label class="form-label">Select Recipient Address *</label>
                                <select name="recipient_address_id" class="form-select" required id="recipient_address">
                                    <option value="">Choose shipping address...</option>
                                    @foreach($recipientAddresses as $address)
                                    <option value="{{ $address->id }}"
                                        {{ old('recipient_address_id') == $address->id ? 'selected' : '' }}
                                        data-country="{{ $address->country_code }}">
                                        {{ $address->contact_name }} - {{ $address->address_line1 }}, {{ $address->city }}, {{ $address->country_code }}
                                    </option>
                                    @endforeach
                                </select>
                                
                                @if($recipientAddresses->count() === 0)
                                <div class="alert alert-warning mt-2">
                                    <div class="d-flex">
                                        <i class="ri-alert-line me-2 mt-1"></i>
                                        <div>
                                            <strong>No shipping addresses found</strong>
                                            <p class="small mb-0">You need to add a shipping address first.</p>
                                            <a href="{{ route('addresses.create') }}?type=shipping" class="btn btn-sm btn-warning mt-2">
                                                <i class="ri-add-line me-1"></i>Add Shipping Address
                                            </a>
                                        </div>
                                    </div>
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
                                       value="{{ old('weight') }}"
                                       step="0.01" 
                                       min="0.1" 
                                       max="1000" 
                                       required
                                       placeholder="e.g., 2.5"
                                       id="weight_input">
                                <div class="form-text">
                                    Minimum 0.1 kg, maximum 1000 kg
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">Declared Value ($) *</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" 
                                           name="declared_value" 
                                           class="form-control" 
                                           value="{{ old('declared_value') }}"
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
                            <select name="service_type" class="form-select" required id="service_type" data-services="{{ json_encode($serviceWeights ?? []) }}">
                                <option value="">Select service...</option>
                                <option value="express" {{ old('service_type') == 'express' ? 'selected' : '' }} data-min="0.1" data-max="50">
                                    Express Delivery (2-5 days) - Fastest
                                </option>
                                <option value="economy" {{ old('service_type') == 'economy' ? 'selected' : '' }} data-min="0.1" data-max="100">
                                    Economy Shipping (5-10 days) - Most Economical
                                </option>
                                <option value="freight" {{ old('service_type') == 'freight' ? 'selected' : '' }} data-min="10" data-max="2000">
                                    Freight Service (7-14 days) - Heavy Cargo
                                </option>
                                <option value="documents" {{ old('service_type') == 'documents' ? 'selected' : '' }} data-min="0.1" data-max="5">
                                    Document Delivery (3-7 days) - Secure
                                </option>
                            </select>

                                  {{-- Content description --}}
                            <div class="col-md-6">
                                <label class="form-label">Content Description *</label>
                                <input type="text" 
                                       name="content_description" 
                                       class="form-control" 
                                       value="{{ old('content_description') }}"
                                       required 
                                       placeholder="e.g., Electronics, Documents, Clothing">
                            </div>
                            
                            <!-- Weight Limits Info Box -->
                            <div id="service_weight_info" class="alert alert-info mt-3" style="display: none;">
                                <div class="d-flex">
                                    <i class="ri-information-line me-2 mt-1"></i>
                                    <div>
                                        <small id="weight_info_text">Please select a service type</small>
                                    </div>
                                </div>
                            </div>
                        </div>
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
                                               value="{{ old('insurance_amount') }}"
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
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center pt-3 border-top gap-3">
                        <div class="d-flex flex-wrap gap-2 w-100 w-md-auto">
                            <a href="{{ route('shipments.index') }}" class="btn btn-outline-secondary flex-grow-1 flex-md-grow-0">
                                <i class="ri-arrow-left-line me-1 me-md-2"></i>
                                <span class="d-none d-md-inline">Back to Shipments</span>
                                <span class="d-md-none">Back</span>
                            </a>
                        </div>
                        
                        <div class="d-flex flex-wrap gap-2 w-100 w-md-auto">
                            <button type="button" class="btn btn-outline-primary flex-grow-1 flex-md-grow-0" id="saveDraftBtn">
                                <i class="ri-save-line me-1 me-md-2"></i>
                                <span class="d-none d-md-inline">Save as Draft</span>
                                <span class="d-md-none">Draft</span>
                            </button>
                            <button type="submit" class="btn btn-primary flex-grow-1 flex-md-grow-0">
                                <i class="ri-ship-line me-1 me-md-2"></i>
                                <span class="d-none d-md-inline">Create Shipment</span>
                                <span class="d-md-none">Create</span>
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
    // Service weight limits
    const serviceWeights = {
        'express': { min: 0.1, max: 50, name: 'Express Delivery' },
        'economy': { min: 0.1, max: 100, name: 'Economy Shipping' },
        'freight': { min: 10, max: 2000, name: 'Freight Service' },
        'documents': { min: 0.1, max: 5, name: 'Document Delivery' }
    };
    
    // DOM Elements
    const serviceSelect = document.getElementById('service_type');
    const weightInput = document.getElementById('weight_input');
    const serviceWeightInfo = document.getElementById('service_weight_info');
    const weightInfoText = document.getElementById('weight_info_text');
    
    /**
     * Update weight info box based on selected service
     */
    function updateWeightInfo() {
        const selectedService = serviceSelect.value;
        
        if (!selectedService) {
            serviceWeightInfo.style.display = 'none';
            return;
        }
        
        const limits = serviceWeights[selectedService];
        if (!limits) return;
        
        let infoHTML = `<strong>${limits.name}</strong><br>
            ✓ Weight range: <strong>${limits.min}kg - ${limits.max}kg</strong><br>`;
        
        // Add current weight validation feedback
        if (weightInput.value) {
            const weight = parseFloat(weightInput.value);
            if (weight < limits.min) {
                infoHTML += `<span class="text-danger">⚠️ Your package (${weight}kg) is too light!</span>`;
            } else if (weight > limits.max) {
                infoHTML += `<span class="text-danger">⚠️ Your package (${weight}kg) exceeds the limit!</span>`;
            } else {
                infoHTML += `<span class="text-success">✓ Your package weight (${weight}kg) is compatible</span>`;
            }
        }
        
        weightInfoText.innerHTML = infoHTML;
        serviceWeightInfo.style.display = 'block';
    }
    
    /**
     * Validate weight against selected service
     */
    function validateWeight() {
        const selectedService = serviceSelect.value;
        const weight = parseFloat(weightInput.value) || 0;
        
        if (!selectedService || weight === 0) return true;
        
        const limits = serviceWeights[selectedService];
        if (!limits) return true;
        
        // Visual feedback
        if (weight < limits.min) {
            weightInput.classList.add('is-invalid');
            weightInput.classList.remove('is-valid');
        } else if (weight > limits.max) {
            weightInput.classList.add('is-invalid');
            weightInput.classList.remove('is-valid');
        } else {
            weightInput.classList.remove('is-invalid');
            weightInput.classList.add('is-valid');
        }
        
        updateWeightInfo();
    }
    
    // Event listeners
    serviceSelect.addEventListener('change', function() {
        updateWeightInfo();
        validateWeight();
    });
    
    weightInput.addEventListener('change', validateWeight);
    weightInput.addEventListener('input', updateWeightInfo);
    
    // Initial update if service is already selected
    if (serviceSelect.value) {
        updateWeightInfo();
    }
    
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
    
    // Form validation
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            const sender = this.querySelector('[name="sender_address_id"]');
            const recipient = this.querySelector('[name="recipient_address_id"]');
            const weight = this.querySelector('[name="weight"]');
            const selectedService = serviceSelect.value;
            
            // Validate sender and recipient are different
            if (sender.value && recipient.value && sender.value === recipient.value) {
                e.preventDefault();
                alert('❌ Sender and recipient addresses cannot be the same.');
                sender.focus();
                return;
            }
            
            // Validate weight against selected service
            if (selectedService && weight.value) {
                const limits = serviceWeights[selectedService];
                const currentWeight = parseFloat(weight.value);
                
                if (currentWeight < limits.min) {
                    e.preventDefault();
                    alert(`❌ Package Too Light for ${limits.name}\n\nMinimum weight: ${limits.min}kg\nYour package: ${currentWeight}kg\n\n💡 Please either:\n• Choose a different service\n• Combine with other items`);
                    weight.focus();
                    return;
                }
                
                if (currentWeight > limits.max) {
                    e.preventDefault();
                    alert(`❌ Package Too Heavy for ${limits.name}\n\nMaximum weight: ${limits.max}kg\nYour package: ${currentWeight}kg\n\n💡 Please either:\n• Choose the Freight Service for heavy packages\n• Split into multiple shipments`);
                    weight.focus();
                    return;
                }
            }
            
            // Validate at least one address exists in each dropdown
            const senderSelect = document.getElementById('sender_address');
            const recipientSelect = document.getElementById('recipient_address');
            
            if (senderSelect && senderSelect.options.length <= 1) {
                e.preventDefault();
                alert('❌ You need to add a billing address first.\n\nPlease click "Add Billing Address" to create a billing address.');
                return;
            }
            
            if (recipientSelect && recipientSelect.options.length <= 1) {
                e.preventDefault();
                alert('❌ You need to add a shipping address first.\n\nPlease click "Add Shipping Address" to create a shipping address.');
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
            
            // For now, just alert and submit
            if (confirm('Save as draft? You can complete this shipment later.')) {
                form.submit();
            }
        });
    }
});
</script>
@endsection