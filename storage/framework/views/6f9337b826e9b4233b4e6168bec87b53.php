

<?php $__env->startSection('title', 'Shipment Details | GlobalSkyFleet'); ?>
<?php $__env->startSection('page-title', 'Shipment Details'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h4 class="mb-1">Shipment #<?php echo e($shipment->tracking_number); ?></h4>
                        <p class="text-muted mb-0">
                            Created: <?php echo e($shipment->created_at->format('F d, Y')); ?>

                        </p>
                    </div>
                    <span class="badge 
                        <?php if($shipment->status === 'delivered'): ?> bg-success
                        <?php elseif($shipment->status === 'cancelled'): ?> bg-danger
                        <?php elseif(in_array($shipment->status, ['in_transit', 'out_for_delivery'])): ?> bg-info
                        <?php else: ?> bg-warning <?php endif; ?>" 
                        style="font-size: 1rem;">
                        <?php echo e(ucfirst($shipment->status)); ?>

                    </span>
                </div>
                
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="text-muted mb-2">From</h6>
                        <div class="bg-light p-3 rounded">
                            <strong><?php echo e($shipment->senderAddress->name ?? 'N/A'); ?></strong><br>
                            <?php echo e($shipment->senderAddress->address_line1 ?? ''); ?><br>
                            <?php echo e($shipment->senderAddress->city ?? ''); ?>, 
                            <?php echo e($shipment->senderAddress->country ?? ''); ?>

                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted mb-2">To</h6>
                        <div class="bg-light p-3 rounded">
                            <strong><?php echo e($shipment->recipientAddress->name ?? 'N/A'); ?></strong><br>
                            <?php echo e($shipment->recipientAddress->address_line1 ?? ''); ?><br>
                            <?php echo e($shipment->recipientAddress->city ?? ''); ?>, 
                            <?php echo e($shipment->recipientAddress->country ?? ''); ?>

                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-4">
                        <h6 class="text-muted mb-2">Weight</h6>
                        <p class="mb-0"><strong><?php echo e($shipment->weight); ?> kg</strong></p>
                    </div>
                    <div class="col-md-4">
                        <h6 class="text-muted mb-2">Value</h6>
                        <p class="mb-0">
                            <strong>
                                <?php echo e($shipment->currency); ?> <?php echo e(number_format($shipment->declared_value, 2)); ?>

                            </strong>
                        </p>
                    </div>
                    <div class="col-md-4">
                        <h6 class="text-muted mb-2">Estimated Delivery</h6>
                        <p class="mb-0">
                            <strong>
                                <?php if($shipment->estimated_delivery): ?>
                                    <?php echo e($shipment->estimated_delivery->format('M d, Y')); ?>

                                <?php else: ?>
                                    N/A
                                <?php endif; ?>
                            </strong>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h5 class="mb-3">Actions</h5>
                <div class="d-grid gap-2">
                    <a href="<?php echo e(route('tracking')); ?>?tracking_number=<?php echo e($shipment->tracking_number); ?>" 
                       class="btn btn-outline-primary" target="_blank">
                        <i class="ri-search-line me-2"></i>Track Shipment
                    </a>
                    
                    <a href="<?php echo e(route('shipments.index')); ?>" class="btn btn-outline-secondary">
                        <i class="ri-arrow-left-line me-2"></i>Back to List
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Dee\Desktop\globalsky-final\globalskyfleet_fixed\resources\views/shipments/show.blade.php ENDPATH**/ ?>