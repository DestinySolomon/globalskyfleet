

<?php $__env->startSection('title', 'Contact Message - ' . $message->safe_name); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <a href="<?php echo e(route('admin.contact-messages.index')); ?>" class="btn btn-outline-secondary">
                <i class="ri-arrow-left-line"></i> Back to Messages
            </a>
        </div>
        <div class="d-flex gap-2">
            <span class="<?php echo e($message->status_badge_class); ?> fs-6">
                <?php echo e(ucfirst($message->status)); ?>

            </span>
            <?php if($message->replied_at): ?>
            <span class="badge bg-success">
                <i class="ri-check-line me-1"></i>Replied: <?php echo e($message->replied_at->format('M d')); ?>

            </span>
            <?php endif; ?>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light py-3">
                    <h4 class="mb-0">Message Details</h4>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-muted small">FROM</h6>
                            <!-- SECURE: Use safe_name accessor -->
                            <h5 class="mb-1"><?php echo e($message->safe_name); ?></h5>
                            <p class="mb-1">
                                <!-- SECURE: Use safe_email accessor -->
                                <a href="mailto:<?php echo e($message->safe_email); ?>" class="text-decoration-none">
                                    <?php echo e($message->safe_email); ?>

                                </a>
                            </p>
                            <p class="mb-0">
                                <!-- SECURE: Use safe_phone accessor -->
                                <a href="tel:<?php echo e($message->safe_phone); ?>" class="text-decoration-none">
                                    <?php echo e($message->safe_phone); ?>

                                </a>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted small">DETAILS</h6>
                            <p class="mb-1">
                                <strong>Subject:</strong> <?php echo e($message->subject_display); ?>

                            </p>
                            <p class="mb-1">
                                <strong>Received:</strong> <?php echo e($message->created_at->format('F j, Y g:i A')); ?>

                            </p>
                            <p class="mb-1">
                                <strong>Browser:</strong> 
                                <span class="small text-muted"><?php echo e(Str::limit($message->user_agent, 50)); ?></span>
                            </p>
                            <p class="mb-0">
                                <strong>IP Address:</strong> 
                                <span class="small font-monospace"><?php echo e($message->ip_address); ?></span>
                            </p>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <h5 class="mb-3">Message</h5>
                    <div class="bg-light p-4 rounded">
                        <!-- SECURE: Use safe_message accessor -->
                        <?php echo $message->safe_message; ?>

                    </div>
                </div>
            </div>
            
            <?php if($message->admin_notes): ?>
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">Admin Notes</h6>
                        <small class="text-muted">Last updated: <?php echo e($message->updated_at->diffForHumans()); ?></small>
                    </div>
                </div>
                <div class="card-body">
                    <!-- SECURE: Display admin notes safely -->
                    <?php echo nl2br(e($message->admin_notes)); ?>

                </div>
            </div>
            <?php endif; ?>
        </div>
        
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm sticky-top" style="top: 20px;">
                <div class="card-header bg-light py-3">
                    <h6 class="mb-0">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <form action="<?php echo e(route('admin.contact-messages.update-status', $message->id)); ?>" method="POST" class="mb-4">
                        <?php echo csrf_field(); ?>
                        <div class="mb-3">
                            <label class="form-label">Update Status</label>
                            <select name="status" class="form-select" required>
                                <option value="read" <?php echo e($message->status === 'read' ? 'selected' : ''); ?>>Mark as Read</option>
                                <option value="replied" <?php echo e($message->status === 'replied' ? 'selected' : ''); ?>>Mark as Replied</option>
                                <option value="archived" <?php echo e($message->status === 'archived' ? 'selected' : ''); ?>>Archive</option>
                            </select>
                            <small class="text-muted">Changing to "Replied" will timestamp the response.</small>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Add Admin Notes</label>
                            <textarea name="admin_notes" class="form-control" rows="3" 
                                      placeholder="Add internal notes here (visible only to admins)..."
                                      maxlength="1000"><?php echo e(old('admin_notes', $message->admin_notes)); ?></textarea>
                            <small class="text-muted">Max 1000 characters</small>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="ri-save-line"></i> Update Message
                        </button>
                    </form>
                    
                    <hr>
                    
                    <div class="d-grid gap-2">
                        <!-- SECURE: Use safe_email and safe_name for reply link -->
                        <a href="mailto:<?php echo e($message->safe_email); ?>?subject=Re: <?php echo e($message->subject_display); ?> - GlobalSkyFleet&body=Dear <?php echo e(urlencode($message->safe_name)); ?>," 
                           class="btn btn-success" target="_blank">
                            <i class="ri-reply-line"></i> Reply via Email
                        </a>
                        
                        <!-- Copy email button -->
                        <button type="button" 
                                class="btn btn-outline-primary"
                                onclick="copyToClipboard('<?php echo e($message->safe_email); ?>')">
                            <i class="ri-clipboard-line"></i> Copy Email
                        </button>
                        
                        <button type="button" 
                                class="btn btn-danger" 
                                data-bs-toggle="modal" 
                                data-bs-target="#deleteModal">
                            <i class="ri-delete-bin-line"></i> Delete Message
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete Message</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <!-- SECURE: Use safe_name accessor -->
                <p>Are you sure you want to delete this message from <strong><?php echo e($message->safe_name); ?></strong>?</p>
                <div class="alert alert-warning">
                    <i class="ri-alert-line"></i>
                    <small>This action cannot be undone. All message data will be permanently deleted.</small>
                </div>
                <div class="small text-muted">
                    <strong>Details:</strong><br>
                    Email: <?php echo e($message->safe_email); ?><br>
                    Subject: <?php echo e($message->subject_display); ?><br>
                    Received: <?php echo e($message->created_at->format('M d, Y')); ?>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete Permanently</button>
            </div>
        </div>
    </div>
</div>

<!-- Hidden delete form -->
<form id="deleteForm" action="<?php echo e(route('admin.contact-messages.destroy', $message->id)); ?>" method="POST" style="display: none;">
    <?php echo csrf_field(); ?>
    <?php echo method_field('DELETE'); ?>
</form>

<?php $__env->startPush('scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
        if (confirmDeleteBtn) {
            confirmDeleteBtn.addEventListener('click', function() {
                document.getElementById('deleteForm').submit();
            });
        }
    });

    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(function() {
            // Show success message
            const originalButton = event.target.closest('button');
            const originalHtml = originalButton.innerHTML;
            originalButton.innerHTML = '<i class="ri-check-line"></i> Copied!';
            originalButton.classList.remove('btn-outline-primary');
            originalButton.classList.add('btn-success');

            
            setTimeout(function() {
                originalButton.innerHTML = originalHtml;
                originalButton.classList.remove('btn-success');
                originalButton.classList.add('btn-outline-primary');
            }, 2000);
        }).catch(function(err) {
            console.error('Failed to copy: ', err);
            alert('Failed to copy email to clipboard');
        });
    }
    
    // Auto-resize textarea
    document.addEventListener('DOMContentLoaded', function() {
        const textarea = document.querySelector('textarea[name="admin_notes"]');
        if (textarea) {
            textarea.addEventListener('input', function() {
                this.style.height = 'auto';
                this.style.height = (this.scrollHeight) + 'px';
            });
            
            // Trigger once on load
            textarea.dispatchEvent(new Event('input'));
        }
    });
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Dee\Desktop\General\globalskyfleet\resources\views\admin\contact-messages\show.blade.php ENDPATH**/ ?>