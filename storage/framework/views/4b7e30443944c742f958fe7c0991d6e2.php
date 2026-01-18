

<?php $__env->startSection('page-title', 'Shipment Details - ' . $shipment->tracking_number); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <!-- Back Button -->
    <div class="row mb-4">
        <div class="col-12">
            <a href="<?php echo e(route('admin.shipments')); ?>" class="btn btn-outline-secondary">
                <i class="ri-arrow-left-line me-2"></i>Back to Shipments
            </a>
        </div>
    </div>

    <!-- Shipment Header -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="mb-1">Shipment: <strong><?php echo e($shipment->tracking_number); ?></strong></h4>
                    <p class="text-muted mb-0">
                        Created: <?php echo e($shipment->created_at->format('M d, Y H:i')); ?> | 
                        Estimated Delivery: <?php echo e($shipment->estimated_delivery ? $shipment->estimated_delivery->format('M d, Y') : 'Not set'); ?>

                    </p>
                </div>
                <div class="col-md-4 text-md-end">
                    <span class="badge bg-<?php echo e($shipment->status === 'delivered' ? 'success' : ($shipment->status === 'pending' ? 'warning' : 'info')); ?> fs-6">
                        <?php echo e(ucfirst(str_replace('_', ' ', $shipment->status))); ?>

                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Left Column: Shipment Details -->
        <div class="col-lg-8">
            <!-- Status Timeline -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom">
                    <h6 class="mb-0"><i class="ri-history-line me-2"></i>Status Timeline</h6>
                </div>
                <div class="card-body">
                    <?php if($shipment->statusHistory->isEmpty()): ?>
                        <div class="text-center py-4">
                            <i class="ri-time-line fs-1 text-muted"></i>
                            <p class="mt-3">No tracking updates yet</p>
                        </div>
                    <?php else: ?>
                        <div class="timeline">
                            <?php $__currentLoopData = $shipment->statusHistory; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $update): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="timeline-item <?php echo e($loop->first ? 'current' : ''); ?>">
                                <div class="timeline-marker">
                                    <?php if($loop->first): ?>
                                        <i class="ri-checkbox-blank-circle-fill text-primary"></i>
                                    <?php else: ?>
                                        <i class="ri-checkbox-blank-circle-line text-muted"></i>
                                    <?php endif; ?>
                                </div>
                                <div class="timeline-content">
                                    <div class="d-flex justify-content-between">
                                        <h6 class="mb-1"><?php echo e(ucfirst(str_replace('_', ' ', $update->status))); ?></h6>
                                        <small class="text-muted"><?php echo e($update->scan_datetime->format('M d, Y H:i')); ?></small>
                                    </div>
                                    <p class="mb-1"><?php echo e($update->location); ?></p>
                                    <?php if($update->description): ?>
                                        <p class="text-muted small mb-0"><?php echo e($update->description); ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="card-footer bg-white border-top">
                    <form action="<?php echo e(route('admin.shipments.update-status', $shipment->id)); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <select name="status" class="form-select" required>
                                    <option value="">Select Status</option>
                                    <option value="pending">Pending</option>
                                    <option value="confirmed">Confirmed</option>
                                    <option value="picked_up">Picked Up</option>
                                    <option value="in_transit">In Transit</option>
                                    <option value="customs_hold">Customs Hold</option>
                                    <option value="out_for_delivery">Out for Delivery</option>
                                    <option value="delivered">Delivered</option>
                                    <option value="cancelled">Cancelled</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <input type="text" name="location" class="form-control" 
                                       placeholder="Location" required>
                            </div>
                            <div class="col-md-4">
                                <input type="text" name="description" class="form-control" 
                                       placeholder="Description (optional)">
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">Update Status</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Package Details -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h6 class="mb-0"><i class="ri-box-3-line me-2"></i>Package Details</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-sm">
                                <tr>
                                    <th width="150">Weight:</th>
                                    <td><?php echo e($shipment->weight); ?> kg</td>
                                </tr>
                                <tr>
                                    <th>Dimensions:</th>
                                    <td>
                                        <?php if($shipment->dimensions): ?>
                                            <?php echo e(json_decode($shipment->dimensions)->length ?? 'N/A'); ?> x 
                                            <?php echo e(json_decode($shipment->dimensions)->width ?? 'N/A'); ?> x 
                                            <?php echo e(json_decode($shipment->dimensions)->height ?? 'N/A'); ?> cm
                                        <?php else: ?>
                                            Not specified
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Declared Value:</th>
                                    <td>$<?php echo e(number_format($shipment->declared_value, 2)); ?> <?php echo e($shipment->currency); ?></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-sm">
                                <tr>
                                    <th width="150">Insurance:</th>
                                    <td>
                                        <?php if($shipment->insurance_enabled): ?>
                                            <span class="badge bg-success">Enabled</span> 
                                            $<?php echo e(number_format($shipment->insurance_amount, 2)); ?>

                                        <?php else: ?>
                                            <span class="badge bg-secondary">Disabled</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Signature Required:</th>
                                    <td>
                                        <?php if($shipment->requires_signature): ?>
                                            <span class="badge bg-info">Yes</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">No</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Dangerous Goods:</th>
                                    <td>
                                        <?php if($shipment->is_dangerous_goods): ?>
                                            <span class="badge bg-danger">Yes</span>
                                        <?php else: ?>
                                            <span class="badge bg-success">No</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                    <?php if($shipment->content_description): ?>
                    <div class="mt-3">
                        <h6>Content Description:</h6>
                        <p class="text-muted"><?php echo e($shipment->content_description); ?></p>
                    </div>
                    <?php endif; ?>
                    
                    <?php if($shipment->special_instructions): ?>
                    <div class="mt-3">
                        <h6>Special Instructions:</h6>
                        <p class="text-muted"><?php echo e($shipment->special_instructions); ?></p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Right Column: Sender/Recipient & User Info -->
        <div class="col-lg-4">
            <!-- Sender Information -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom">
                    <h6 class="mb-0"><i class="ri-user-location-line me-2"></i>Sender Information</h6>
                </div>
                <div class="card-body">
                    <?php if($shipment->senderAddress): ?>
                        <p class="mb-1"><strong><?php echo e($shipment->senderAddress->contact_name); ?></strong></p>
                        <p class="mb-1"><?php echo e($shipment->senderAddress->company); ?></p>
                        <p class="mb-1"><?php echo e($shipment->senderAddress->address_line1); ?></p>
                        <p class="mb-1"><?php echo e($shipment->senderAddress->address_line2); ?></p>
                        <p class="mb-1"><?php echo e($shipment->senderAddress->city); ?>, <?php echo e($shipment->senderAddress->state); ?> <?php echo e($shipment->senderAddress->postal_code); ?></p>
                        <p class="mb-0"><?php echo e($shipment->senderAddress->country_code); ?></p>
                        <p class="mt-2 mb-0">
                            <i class="ri-phone-line me-1"></i>
                            <?php echo e($shipment->senderAddress->contact_phone); ?>

                        </p>
                    <?php else: ?>
                        <p class="text-muted">Not available</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Recipient Information -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom">
                    <h6 class="mb-0"><i class="ri-user-received-line me-2"></i>Recipient Information</h6>
                </div>
                <div class="card-body">
                    <?php if($shipment->recipientAddress): ?>
                        <p class="mb-1"><strong><?php echo e($shipment->recipientAddress->contact_name); ?></strong></p>
                        <p class="mb-1"><?php echo e($shipment->recipientAddress->company); ?></p>
                        <p class="mb-1"><?php echo e($shipment->recipientAddress->address_line1); ?></p>
                        <p class="mb-1"><?php echo e($shipment->recipientAddress->address_line2); ?></p>
                        <p class="mb-1"><?php echo e($shipment->recipientAddress->city); ?>, <?php echo e($shipment->recipientAddress->state); ?> <?php echo e($shipment->recipientAddress->postal_code); ?></p>
                        <p class="mb-0"><?php echo e($shipment->recipientAddress->country_code); ?></p>
                        <p class="mt-2 mb-0">
                            <i class="ri-phone-line me-1"></i>
                            <?php echo e($shipment->recipientAddress->contact_phone); ?>

                        </p>
                    <?php else: ?>
                        <p class="text-muted">Not available</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Customer Information -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h6 class="mb-0"><i class="ri-user-line me-2"></i>Customer Information</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center me-3" 
                             style="width: 48px; height: 48px; font-weight: bold;">
                            <?php echo e(substr($shipment->user->name, 0, 2)); ?>

                        </div>
                        <div>
                            <h6 class="mb-0"><?php echo e($shipment->user->name); ?></h6>
                            <p class="text-muted mb-0"><?php echo e($shipment->user->email); ?></p>
                        </div>
                    </div>
                    
                    <table class="table table-sm">
                        <tr>
                            <th width="100">User ID:</th>
                            <td>#<?php echo e($shipment->user->id); ?></td>
                        </tr>
                        <tr>
                            <th>Phone:</th>
                            <td><?php echo e($shipment->user->phone ?? 'Not provided'); ?></td>
                        </tr>
                        <tr>
                            <th>Company:</th>
                            <td><?php echo e($shipment->user->company ?? 'Not provided'); ?></td>
                        </tr>
                        <tr>
                            <th>Joined:</th>
                            <td><?php echo e($shipment->user->created_at->format('M d, Y')); ?></td>
                        </tr>
                    </table>
                    
                    <div class="text-center mt-3">
                        <a href="<?php echo e(route('admin.users.show', $shipment->user_id)); ?>" 
                           class="btn btn-sm btn-outline-primary w-100">
                            <i class="ri-user-line me-1"></i>View Customer Profile
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.timeline {
    position: relative;
    padding-left: 30px;
}
.timeline::before {
    content: '';
    position: absolute;
    left: 15px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #e5e7eb;
}
.timeline-item {
    position: relative;
    margin-bottom: 25px;
}
.timeline-marker {
    position: absolute;
    left: -30px;
    top: 0;
    font-size: 1.2rem;
}
.timeline-item.current .timeline-marker {
    color: #0a2463;
}
</style>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Dee\Desktop\General\globalskyfleet\resources\views\admin\shipments\show.blade.php ENDPATH**/ ?>