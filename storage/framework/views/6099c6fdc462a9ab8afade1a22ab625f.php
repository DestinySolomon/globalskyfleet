

<?php $__env->startSection('title', 'Create Shipment | GlobalSkyFleet'); ?>
<?php $__env->startSection('page-title', 'Create Shipment'); ?>

<?php $__env->startSection('content'); ?>
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
                <?php if($errors->any()): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <div class="d-flex">
                        <i class="ri-error-warning-line me-2 mt-1"></i>
                        <div>
                            <strong>Validation Errors:</strong>
                            <ul class="mb-0 mt-2">
                                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($error); ?></li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>

                <form action="<?php echo e(route('shipments.store')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
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
                                    <?php $__currentLoopData = $senderAddresses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $address): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($address->id); ?>" 
                                        <?php echo e(old('sender_address_id') == $address->id ? 'selected' : ''); ?>

                                        data-country="<?php echo e($address->country_code); ?>">
                                        <?php echo e($address->contact_name); ?> - <?php echo e($address->address_line1); ?>, <?php echo e($address->city); ?>, <?php echo e($address->country_code); ?>

                                    </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                
                                <?php if($senderAddresses->count() === 0): ?>
                                <div class="alert alert-warning mt-2">
                                    <div class="d-flex">
                                        <i class="ri-alert-line me-2 mt-1"></i>
                                        <div>
                                            <strong>No billing addresses found</strong>
                                            <p class="small mb-0">You need to add a billing address first.</p>
                                            <a href="<?php echo e(route('addresses.create')); ?>?type=billing" class="btn btn-sm btn-warning mt-2">
                                                <i class="ri-add-line me-1"></i>Add Billing Address
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <?php endif; ?>
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
                                    <?php $__currentLoopData = $recipientAddresses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $address): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($address->id); ?>"
                                        <?php echo e(old('recipient_address_id') == $address->id ? 'selected' : ''); ?>

                                        data-country="<?php echo e($address->country_code); ?>">
                                        <?php echo e($address->contact_name); ?> - <?php echo e($address->address_line1); ?>, <?php echo e($address->city); ?>, <?php echo e($address->country_code); ?>

                                    </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                
                                <?php if($recipientAddresses->count() === 0): ?>
                                <div class="alert alert-warning mt-2">
                                    <div class="d-flex">
                                        <i class="ri-alert-line me-2 mt-1"></i>
                                        <div>
                                            <strong>No shipping addresses found</strong>
                                            <p class="small mb-0">You need to add a shipping address first.</p>
                                            <a href="<?php echo e(route('addresses.create')); ?>?type=shipping" class="btn btn-sm btn-warning mt-2">
                                                <i class="ri-add-line me-1"></i>Add Shipping Address
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <?php endif; ?>
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
                                       value="<?php echo e(old('weight')); ?>"
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
                                           value="<?php echo e(old('declared_value')); ?>"
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
                             <option value="express" <?php echo e(old('service_type') == 'express' ? 'selected' : ''); ?>>
                             Express Delivery (2-5 days) - Fastest
                                 </option>
                              <option value="economy" <?php echo e(old('service_type') == 'economy' ? 'selected' : ''); ?>>
                                Economy Shipping (5-10 days) - Most Economical
                          </option>
                     <option value="freight" <?php echo e(old('service_type') == 'freight' ? 'selected' : ''); ?>>
                      Freight Service (7-14 days) - Heavy Cargo
                   </option>
                     <option value="documents" <?php echo e(old('service_type') == 'documents' ? 'selected' : ''); ?>>
                    Document Delivery (3-7 days) - Secure
                        </option>
                             </select>

                                <div class="form-text">
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">Content Description *</label>
                                <input type="text" 
                                       name="content_description" 
                                       class="form-control" 
                                       value="<?php echo e(old('content_description')); ?>"
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
                                       value="<?php echo e(old('dimensions_length')); ?>"
                                       step="0.1" 
                                       min="1" 
                                       placeholder="Length">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Width (cm)</label>
                                <input type="number" 
                                       name="dimensions_width" 
                                       class="form-control" 
                                       value="<?php echo e(old('dimensions_width')); ?>"
                                       step="0.1" 
                                       min="1" 
                                       placeholder="Width">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Height (cm)</label>
                                <input type="number" 
                                       name="dimensions_height" 
                                       class="form-control" 
                                       value="<?php echo e(old('dimensions_height')); ?>"
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
                                           <?php echo e(old('requires_signature') ? 'checked' : ''); ?>>
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
                                           <?php echo e(old('is_dangerous_goods') ? 'checked' : ''); ?>>
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
                                           <?php echo e(old('insurance_enabled') ? 'checked' : ''); ?>>
                                    <label class="form-check-label" for="insurance_enabled">
                                        <i class="ri-shield-check-line me-1"></i>Add Insurance
                                    </label>
                                    <div class="form-text small">Protect your shipment</div>
                                </div>
                                
                                <div class="mt-3" id="insurance_amount_field" style="<?php echo e(old('insurance_enabled') ? 'display: block;' : 'display: none;'); ?>">
                                    <label class="form-label">Insurance Amount ($)</label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" 
                                               name="insurance_amount" 
                                               class="form-control" 
                                               value="<?php echo e(old('insurance_amount')); ?>"
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
                                  placeholder="Any special handling instructions, delivery time preferences, or additional information..."><?php echo e(old('special_instructions')); ?></textarea>
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
                               value="<?php echo e(old('pickup_date')); ?>"
                               min="<?php echo e(date('Y-m-d')); ?>">
                        <div class="form-text">Leave empty for earliest possible pickup</div>
                    </div>
                    
                    <!-- Submit Buttons -->
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center pt-3 border-top gap-3">
                        <div class="d-flex flex-wrap gap-2 w-100 w-md-auto">
                            <a href="<?php echo e(route('shipments.index')); ?>" class="btn btn-outline-secondary flex-grow-1 flex-md-grow-0">
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
                                <a href="<?php echo e(route('contact')); ?>" class="btn btn-sm btn-outline-primary mt-2">
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
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
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
                estimate = 'Estimated delivery: 1-3 business days';
                break;
            case 'standard':
                estimate = 'Estimated delivery: 3-5 business days';
                break;
            case 'economy':
                estimate = 'Estimated delivery: 5-7 business days';
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
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Dee\Desktop\globalsky-final\globalskyfleet_fixed\resources\views/shipments/create.blade.php ENDPATH**/ ?>