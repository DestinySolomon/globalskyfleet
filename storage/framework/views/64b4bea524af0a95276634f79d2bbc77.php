

<?php $__env->startSection('title', 'Contact Messages'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-navy">Contact Messages</h1>
        <div>
            <span class="badge bg-danger"><?php echo e($unreadCount); ?> Unread</span>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Subject</th>
                            <th>Status</th>
                            <th>Received</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $messages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $message): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="<?php echo e($message->status === 'unread' ? 'table-warning' : ''); ?>">
                            <td>
                                <div class="d-flex align-items-center">
                                    <?php if($message->status === 'unread'): ?>
                                    <span class="badge bg-danger me-2">NEW</span>
                                    <?php endif; ?>
                                    <!-- SECURE: Use safe_name accessor -->
                                    <strong><?php echo e($message->safe_name); ?></strong>
                                </div>
                            </td>
                            <td>
                                <!-- SECURE: Use safe_email accessor -->
                                <a href="mailto:<?php echo e($message->safe_email); ?>" class="text-decoration-none">
                                    <?php echo e($message->safe_email); ?>

                                </a>
                            </td>
                            <td><?php echo e($message->subject_display); ?></td>
                            <td>
                                <span class="<?php echo e($message->status_badge_class); ?>">
                                    <?php echo e(ucfirst($message->status)); ?>

                                </span>
                            </td>
                            <td><?php echo e($message->created_at->diffForHumans()); ?></td>
                            <td>
                                <a href="<?php echo e(route('admin.contact-messages.show', $message->id)); ?>" 
                                   class="btn btn-sm btn-outline-primary" title="View Message">
                                    <i class="ri-eye-line"></i>
                                </a>
                                <button type="button" 
                                        class="btn btn-sm btn-outline-danger" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#deleteModal<?php echo e($message->id); ?>"
                                        title="Delete Message">
                                    <i class="ri-delete-bin-line"></i>
                                </button>
                                
                                <!-- Delete Modal -->
                                <div class="modal fade" id="deleteModal<?php echo e($message->id); ?>" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Delete Message</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <!-- SECURE: Use safe_name accessor -->
                                                Are you sure you want to delete this message from <?php echo e($message->safe_name); ?>?
                                                <div class="mt-2 small text-muted">
                                                    <strong>Email:</strong> <?php echo e($message->safe_email); ?><br>
                                                    <strong>Subject:</strong> <?php echo e($message->subject_display); ?>

                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                <form action="<?php echo e(route('admin.contact-messages.destroy', $message->id)); ?>" method="POST">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('DELETE'); ?>
                                                    <button type="submit" class="btn btn-danger">Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="ri-mail-line fs-1"></i>
                                    <p class="mt-2">No contact messages yet.</p>
                                </div>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php if($messages->hasPages()): ?>
        <div class="card-footer">
            <?php echo e($messages->links()); ?>

        </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Dee\Desktop\General\globalskyfleet\resources\views\admin\contact-messages\index.blade.php ENDPATH**/ ?>