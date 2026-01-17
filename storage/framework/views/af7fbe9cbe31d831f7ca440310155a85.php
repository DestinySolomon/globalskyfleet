

<?php $__env->startSection('page-title', 'Shipment Management'); ?>

<?php $__env->startSection('content'); ?>
<div class="filters-card">
    <form action="<?php echo e(route('admin.shipments')); ?>" method="GET" class="row g-3">
        <div class="col-md-3">
            <input type="text" name="tracking_number" class="form-control" 
                   placeholder="Tracking Number" value="<?php echo e(request('tracking_number')); ?>">
        </div>
        <div class="col-md-3">
            <input type="email" name="user_email" class="form-control" 
                   placeholder="User Email" value="<?php echo e(request('user_email')); ?>">
        </div>
        <div class="col-md-2">
            <select name="status" class="form-control">
                <?php $__currentLoopData = $statuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($value); ?>" <?php echo e(request('status') == $value ? 'selected' : ''); ?>>
                        <?php echo e($label); ?>

                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div class="col-md-2">
            <input type="date" name="date_from" class="form-control" 
                   value="<?php echo e(request('date_from')); ?>">
        </div>
        <div class="col-md-2">
            <input type="date" name="date_to" class="form-control" 
                   value="<?php echo e(request('date_to')); ?>">
        </div>
        <div class="col-md-12">
            <button type="submit" class="btn btn-primary">Filter</button>
            <a href="<?php echo e(route('admin.shipments')); ?>" class="btn btn-secondary">Clear</a>
        </div>
    </form>
</div>

<div class="admin-table">
    <div class="table-header">
        <h3 class="table-title">All Shipments (<?php echo e($shipments->total()); ?>)</h3>
    </div>
    <div class="table-responsive">
        <table class="table table-hover datatable">
            <thead>
                <tr>
                    <th>Tracking #</th>
                    <th>Customer</th>
                    <th>From</th>
                    <th>To</th>
                    <th>Status</th>
                    <th>Weight</th>
                    <th>Value</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $shipments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $shipment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td>
                        <strong><?php echo e($shipment->tracking_number); ?></strong>
                    </td>
                    <td>
                        <?php echo e($shipment->user->email); ?>

                        <?php if($shipment->user->name): ?>
                        <br><small><?php echo e($shipment->user->name); ?></small>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if($shipment->senderAddress): ?>
                            <?php echo e($shipment->senderAddress->city); ?>, <?php echo e($shipment->senderAddress->country_code); ?>

                        <?php else: ?>
                            <span class="text-muted">N/A</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if($shipment->recipientAddress): ?>
                            <?php echo e($shipment->recipientAddress->city); ?>, <?php echo e($shipment->recipientAddress->country_code); ?>

                        <?php else: ?>
                            <span class="text-muted">N/A</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <span class="badge badge-<?php echo e(str_replace('_', '-', $shipment->status)); ?>">
                            <?php echo e(ucfirst(str_replace('_', ' ', $shipment->status))); ?>

                        </span>
                    </td>
                    <td><?php echo e($shipment->weight); ?> kg</td>
                    <td>$<?php echo e(number_format($shipment->declared_value, 2)); ?></td>
                    <td><?php echo e($shipment->created_at->format('M d, Y')); ?></td>
                    <td>
                        <a href="<?php echo e(route('admin.shipments.show', $shipment->id)); ?>" 
                           class="btn btn-sm btn-primary" title="View Details">
                            <i class="ri-eye-line"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
    <div class="p-3">
        <?php echo e($shipments->appends(request()->query())->links()); ?>

    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Dee\Desktop\General\globalskyfleet\resources\views/admin/shipments/index.blade.php ENDPATH**/ ?>