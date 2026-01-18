

<?php $__env->startSection('title', 'Address Details | GlobalSkyFleet'); ?>
<?php $__env->startSection('page-title', 'Address Details'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-lg-12">
        <!-- Header with Back Button and Actions -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="d-flex align-items-center">
                <a href="<?php echo e(route('addresses.index')); ?>" class="btn btn-outline-secondary btn-sm me-3">
                    <i class="ri-arrow-left-line"></i>
                </a>
                <div>
                    <h4 class="mb-0">Address Details</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="<?php echo e(route('addresses.index')); ?>">Address Book</a></li>
                            <li class="breadcrumb-item active" aria-current="page"><?php echo e($address->contact_name); ?></li>
                        </ol>
                    </nav>
                </div>
            </div>
            
            <div class="d-flex gap-2">
                <?php if(!$address->is_default): ?>
                <form action="<?php echo e(route('addresses.set-default', $address)); ?>" method="POST" class="d-inline">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="btn btn-outline-primary btn-sm">
                        <i class="ri-star-line me-1"></i>Set as Default
                    </button>
                </form>
                <?php endif; ?>
                
                <a href="<?php echo e(route('addresses.edit', $address)); ?>" class="btn btn-primary btn-sm">
                    <i class="ri-edit-line me-1"></i>Edit Address
                </a>
                
                <div class="dropdown">
                    <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="ri-more-line"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="<?php echo e(route('shipments.create')); ?>?address_id=<?php echo e($address->id); ?>">
                                <i class="ri-send-plane-line me-2"></i>Use in New Shipment
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <form action="<?php echo e(route('addresses.destroy', $address)); ?>" method="POST" class="d-inline">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Are you sure you want to delete this address?')">
                                    <i class="ri-delete-bin-line me-2"></i>Delete Address
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="row">
            <!-- Left Column: Address Information -->
            <div class="col-lg-8">
                <!-- Address Card -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-semibold">
                            <i class="ri-map-pin-line me-2"></i>Address Information
                        </h5>
                        <div>
                            <span class="badge 
                                <?php if($address->type === 'shipping'): ?> bg-primary
                                <?php elseif($address->type === 'billing'): ?> bg-success
                                <?php elseif($address->type === 'home'): ?> bg-warning
                                <?php else: ?> bg-info <?php endif; ?> me-2">
                                <?php echo e(ucfirst($address->type)); ?>

                            </span>
                            <?php if($address->is_default): ?>
                            <span class="badge bg-warning">
                                <i class="ri-star-fill me-1"></i>Default
                            </span>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <!-- Contact Details -->
                        <div class="mb-5">
                            <h6 class="text-muted mb-3 pb-2 border-bottom">Contact Details</h6>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="d-flex align-items-start">
                                        <div class="bg-light p-2 rounded me-3">
                                            <i class="ri-user-3-line text-primary"></i>
                                        </div>
                                        <div>
                                            <small class="text-muted d-block">Contact Name</small>
                                            <strong><?php echo e($address->contact_name); ?></strong>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <div class="d-flex align-items-start">
                                        <div class="bg-light p-2 rounded me-3">
                                            <i class="ri-phone-line text-success"></i>
                                        </div>
                                        <div>
                                            <small class="text-muted d-block">Phone Number</small>
                                            <strong><?php echo e($address->contact_phone); ?></strong>
                                        </div>
                                    </div>
                                </div>
                                
                                <?php if($address->company): ?>
                                <div class="col-12 mb-3">
                                    <div class="d-flex align-items-start">
                                        <div class="bg-light p-2 rounded me-3">
                                            <i class="ri-building-line text-warning"></i>
                                        </div>
                                        <div>
                                            <small class="text-muted d-block">Company</small>
                                            <strong><?php echo e($address->company); ?></strong>
                                        </div>
                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <!-- Address Details -->
                        <div class="mb-5">
                            <h6 class="text-muted mb-3 pb-2 border-bottom">Address Details</h6>
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <div class="d-flex align-items-start">
                                        <div class="bg-light p-2 rounded me-3">
                                            <i class="ri-home-line text-danger"></i>
                                        </div>
                                        <div>
                                            <small class="text-muted d-block">Address Line 1</small>
                                            <strong><?php echo e($address->address_line1); ?></strong>
                                        </div>
                                    </div>
                                </div>
                                
                                <?php if($address->address_line2): ?>
                                <div class="col-12 mb-3">
                                    <div class="d-flex align-items-start">
                                        <div class="bg-light p-2 rounded me-3">
                                            <i class="ri-home-5-line text-info"></i>
                                        </div>
                                        <div>
                                            <small class="text-muted d-block">Address Line 2</small>
                                            <strong><?php echo e($address->address_line2); ?></strong>
                                        </div>
                                    </div>
                                </div>
                                <?php endif; ?>
                                
                                <div class="col-md-4 mb-3">
                                    <div class="d-flex align-items-start">
                                        <div class="bg-light p-2 rounded me-3">
                                            <i class="ri-building-2-line text-secondary"></i>
                                        </div>
                                        <div>
                                            <small class="text-muted d-block">City</small>
                                            <strong><?php echo e($address->city); ?></strong>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-4 mb-3">
                                    <div class="d-flex align-items-start">
                                        <div class="bg-light p-2 rounded me-3">
                                            <i class="ri-government-line text-secondary"></i>
                                        </div>
                                        <div>
                                            <small class="text-muted d-block">State/Province</small>
                                            <strong><?php echo e($address->state); ?></strong>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-4 mb-3">
                                    <div class="d-flex align-items-start">
                                        <div class="bg-light p-2 rounded me-3">
                                            <i class="ri-mail-line text-secondary"></i>
                                        </div>
                                        <div>
                                            <small class="text-muted d-block">Postal Code</small>
                                            <strong><?php echo e($address->postal_code); ?></strong>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-12 mb-3">
                                    <div class="d-flex align-items-start">
                                        <div class="bg-light p-2 rounded me-3">
                                            <i class="ri-flag-line text-secondary"></i>
                                        </div>
                                        <div>
                                            <small class="text-muted d-block">Country</small>
                                            <strong><?php echo e($address->country_code); ?></strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Full Address Display -->
                        <div class="mb-4">
                            <h6 class="text-muted mb-3 pb-2 border-bottom">Complete Address</h6>
                            <div class="p-4 bg-light rounded">
                                <address class="mb-0">
                                    <strong><?php echo e($address->contact_name); ?></strong><br>
                                    <?php if($address->company): ?>
                                    <?php echo e($address->company); ?><br>
                                    <?php endif; ?>
                                    <?php echo e($address->address_line1); ?><br>
                                    <?php if($address->address_line2): ?>
                                    <?php echo e($address->address_line2); ?><br>
                                    <?php endif; ?>
                                    <?php echo e($address->city); ?>, <?php echo e($address->state); ?> <?php echo e($address->postal_code); ?><br>
                                    <strong><?php echo e($address->country_code); ?></strong><br>
                                    <i class="ri-phone-line"></i> <?php echo e($address->contact_phone); ?>

                                </address>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Usage Statistics -->
                <?php
                    $senderCount = $address->senderShipments()->count();
                    $recipientCount = $address->recipientShipments()->count();
                    $totalShipments = $senderCount + $recipientCount;
                ?>
                
                <?php if($totalShipments > 0): ?>
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-0">
                        <h5 class="mb-0 fw-semibold">
                            <i class="ri-pie-chart-line me-2"></i>Usage Statistics
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card border-0 bg-primary bg-opacity-10">
                                    <div class="card-body text-center">
                                        <h2 class="text-primary mb-2"><?php echo e($senderCount); ?></h2>
                                        <p class="text-muted mb-1">Shipments From</p>
                                        <small class="text-muted">As sender address</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card border-0 bg-success bg-opacity-10">
                                    <div class="card-body text-center">
                                        <h2 class="text-success mb-2"><?php echo e($recipientCount); ?></h2>
                                        <p class="text-muted mb-1">Shipments To</p>
                                        <small class="text-muted">As recipient address</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
            
            <!-- Right Column: Side Information -->
            <div class="col-lg-4">
                <!-- Address Type Information -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-0">
                        <h6 class="mb-0 fw-semibold">
                            <i class="ri-information-line me-2"></i>Address Type Information
                        </h6>
                    </div>
                    <div class="card-body">
                        <?php if($address->type === 'shipping'): ?>
                        <div class="d-flex align-items-start">
                            <div class="bg-primary bg-opacity-10 p-2 rounded me-3">
                                <i class="ri-truck-line text-primary"></i>
                            </div>
                            <div>
                                <h6 class="mb-1">Shipping Address</h6>
                                <p class="text-muted small mb-0">
                                    Used for package deliveries and returns. This address will be used when sending packages.
                                </p>
                            </div>
                        </div>
                        <?php elseif($address->type === 'billing'): ?>
                        <div class="d-flex align-items-start">
                            <div class="bg-success bg-opacity-10 p-2 rounded me-3">
                                <i class="ri-bill-line text-success"></i>
                            </div>
                            <div>
                                <h6 class="mb-1">Billing Address</h6>
                                <p class="text-muted small mb-0">
                                    Used for invoices, payments, and official correspondence related to billing.
                                </p>
                            </div>
                        </div>
                        <?php elseif($address->type === 'home'): ?>
                        <div class="d-flex align-items-start">
                            <div class="bg-warning bg-opacity-10 p-2 rounded me-3">
                                <i class="ri-home-5-line text-warning"></i>
                            </div>
                            <div>
                                <h6 class="mb-1">Home Address</h6>
                                <p class="text-muted small mb-0">
                                    Your residential address. Useful for personal deliveries and returns to your home.
                                </p>
                            </div>
                        </div>
                        <?php else: ?>
                        <div class="d-flex align-items-start">
                            <div class="bg-info bg-opacity-10 p-2 rounded me-3">
                                <i class="ri-building-4-line text-info"></i>
                            </div>
                            <div>
                                <h6 class="mb-1">Work Address</h6>
                                <p class="text-muted small mb-0">
                                    Your business or office address. Ideal for business-related shipments and deliveries.
                                </p>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Quick Actions -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-0">
                        <h6 class="mb-0 fw-semibold">
                            <i class="ri-flashlight-line me-2"></i>Quick Actions
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <?php if($totalShipments > 0): ?>
                            <a href="<?php echo e(route('shipments.index', ['filter' => 'sender:' . $address->id])); ?>" class="btn btn-outline-primary">
                                <i class="ri-upload-line me-2"></i>View Shipments From (<?php echo e($senderCount); ?>)
                            </a>
                            <a href="<?php echo e(route('shipments.index', ['filter' => 'recipient:' . $address->id])); ?>" class="btn btn-outline-success">
                                <i class="ri-download-line me-2"></i>View Shipments To (<?php echo e($recipientCount); ?>)
                            </a>
                            <?php endif; ?>
                            
                            <a href="<?php echo e(route('shipments.create')); ?>?address_id=<?php echo e($address->id); ?>" class="btn btn-success">
                                <i class="ri-send-plane-line me-2"></i>Create New Shipment
                            </a>
                            
                            <a href="<?php echo e(route('addresses.create')); ?>" class="btn btn-outline-secondary">
                                <i class="ri-add-line me-2"></i>Add New Address
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Timestamps -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0">
                        <h6 class="mb-0 fw-semibold">
                            <i class="ri-time-line me-2"></i>Additional Information
                        </h6>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled mb-0">
                            <li class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Created</span>
                                <span class="fw-semibold"><?php echo e($address->created_at->format('M d, Y')); ?></span>
                            </li>
                            <li class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Created Time</span>
                                <span class="fw-semibold"><?php echo e($address->created_at->format('h:i A')); ?></span>
                            </li>
                            <li class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Last Updated</span>
                                <span class="fw-semibold"><?php echo e($address->updated_at->format('M d, Y')); ?></span>
                            </li>
                            <li class="d-flex justify-content-between">
                                <span class="text-muted">Last Updated Time</span>
                                <span class="fw-semibold"><?php echo e($address->updated_at->format('h:i A')); ?></span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Help Information -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h6 class="fw-semibold mb-3">
                    <i class="ri-question-line me-2"></i>Need Help with Addresses?
                </h6>
                <div class="row">
                    <div class="col-md-6">
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2">
                                <i class="ri-checkbox-circle-line text-success me-2"></i>
                                Ensure address details are accurate for successful deliveries
                            </li>
                            <li class="mb-2">
                                <i class="ri-checkbox-circle-line text-success me-2"></i>
                                Use different address types for better organization
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2">
                                <i class="ri-checkbox-circle-line text-success me-2"></i>
                                Set default addresses for faster checkout
                            </li>
                            <li>
                                <i class="ri-checkbox-circle-line text-success me-2"></i>
                                Update addresses when moving or details change
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
    // Set default confirmation
    const setDefaultForm = document.querySelector('form[action*="set-default"]');
    if (setDefaultForm) {
        setDefaultForm.addEventListener('submit', function(e) {
            if (!confirm('Set this as your default <?php echo e($address->type); ?> address?')) {
                e.preventDefault();
            }
        });
    }
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Dee\Desktop\General\globalskyfleet\resources\views\addresses\show.blade.php ENDPATH**/ ?>