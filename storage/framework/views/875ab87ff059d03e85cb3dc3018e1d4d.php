

<?php $__env->startSection('title', 'Notification Details | GlobalSkyFleet'); ?>
<?php $__env->startSection('page-title', 'Notification Details'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Back Button -->
            <div class="mb-3">
                <a href="<?php echo e(route('admin.notifications.index')); ?>" class="btn btn-outline-secondary">
                    <i class="ri-arrow-left-line me-2"></i>Back to Notifications
                </a>
            </div>

            <!-- Notification Details Card -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0">
                            <?php
                                $data = is_array($notification->data) ? $notification->data : (array) $notification->data;
                            ?>
                            <i class="<?php echo e($data['icon'] ?? 'ri-notification-line'); ?> me-2"></i>
                            <?php echo e($data['title'] ?? 'Notification'); ?>

                        </h5>
                        <small class="text-muted">
                            <?php echo e($notification->created_at->format('F d, Y \a\t H:i')); ?>

                        </small>
                    </div>
                    <div>
                        <?php if($notification->unread()): ?>
                            <span class="badge bg-primary">Unread</span>
                        <?php else: ?>
                            <span class="badge bg-success">Read</span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Notification Message -->
                    <div class="mb-4">
                        <h6 class="text-muted mb-2">Message</h6>
                        <p class="lead"><?php echo e($data['message'] ?? 'No message content'); ?></p>
                    </div>

                    <!-- Notification Details -->
                    <div class="row mb-4">
                        <?php if(isset($data['type'])): ?>
                        <div class="col-md-6">
                            <h6 class="text-muted mb-2">Type</h6>
                            <p><?php echo e($data['type']); ?></p>
                        </div>
                        <?php endif; ?>

                        <?php if(isset($data['category'])): ?>
                        <div class="col-md-6">
                            <h6 class="text-muted mb-2">Category</h6>
                            <p>
                                <span class="badge bg-light text-dark"><?php echo e(ucfirst($data['category'])); ?></span>
                            </p>
                        </div>
                        <?php endif; ?>

                        <?php if(isset($data['priority'])): ?>
                        <div class="col-md-6">
                            <h6 class="text-muted mb-2">Priority</h6>
                            <p>
                                <?php
                                    $priorityClass = match($data['priority'] ?? 'normal') {
                                        'urgent' => 'danger',
                                        'high' => 'warning',
                                        'low' => 'info',
                                        default => 'secondary'
                                    };
                                ?>
                                <span class="badge bg-<?php echo e($priorityClass); ?>"><?php echo e(ucfirst($data['priority'] ?? 'Normal')); ?></span>
                            </p>
                        </div>
                        <?php endif; ?>

                        <?php if(isset($data['tracking_number'])): ?>
                        <div class="col-md-6">
                            <h6 class="text-muted mb-2">Tracking Number</h6>
                            <p><?php echo e($data['tracking_number']); ?></p>
                        </div>
                        <?php endif; ?>
                    </div>

                    <!-- Additional Info -->
                    <?php if(isset($data['shipment_id'])): ?>
                    <div class="alert alert-info" role="alert">
                        <i class="ri-ship-line me-2"></i>
                        <strong>Related Shipment</strong>
                        <p class="mb-0 mt-2">
                            <a href="<?php echo e(route('admin.shipments.show', $data['shipment_id'])); ?>" class="btn btn-sm btn-outline-info">
                                <i class="ri-external-link-line me-1"></i>View Shipment
                            </a>
                        </p>
                    </div>
                    <?php endif; ?>

                    <?php if(isset($data['payment_id'])): ?>
                    <div class="alert alert-info" role="alert">
                        <i class="ri-money-dollar-circle-line me-2"></i>
                        <strong>Related Payment</strong>
                        <p class="mb-0 mt-2">
                            <a href="<?php echo e(route('admin.payments.show', $data['payment_id'])); ?>" class="btn btn-sm btn-outline-info">
                                <i class="ri-external-link-line me-1"></i>View Payment
                            </a>
                        </p>
                    </div>
                    <?php endif; ?>

                    <?php if(isset($data['user_id'])): ?>
                    <div class="alert alert-info" role="alert">
                        <i class="ri-user-line me-2"></i>
                        <strong>Related User</strong>
                        <p class="mb-0 mt-2">
                            <a href="<?php echo e(route('admin.users.show', $data['user_id'])); ?>" class="btn btn-sm btn-outline-info">
                                <i class="ri-external-link-line me-1"></i>View User
                            </a>
                        </p>
                    </div>
                    <?php endif; ?>
                </div>

                <div class="card-footer bg-light">
                    <div class="d-flex justify-content-between">
                        <a href="<?php echo e(route('admin.notifications.index')); ?>" class="btn btn-outline-secondary">
                            <i class="ri-arrow-left-line me-2"></i>Back
                        </a>
                        
                        <div>
                            <?php if($notification->unread()): ?>
                            <form action="<?php echo e(route('admin.notifications.mark-read', $notification->id)); ?>" method="POST" class="d-inline">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="btn btn-primary">
                                    <i class="ri-check-line me-2"></i>Mark as Read
                                </button>
                            </form>
                            <?php endif; ?>
                            
                            <form action="<?php echo e(route('admin.notifications.destroy', $notification->id)); ?>" method="POST" class="d-inline">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Delete this notification?')">
                                    <i class="ri-delete-bin-line me-2"></i>Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Dee\Desktop\globalsky-final\globalskyfleet_fixed\resources\views/admin/notifications/show.blade.php ENDPATH**/ ?>