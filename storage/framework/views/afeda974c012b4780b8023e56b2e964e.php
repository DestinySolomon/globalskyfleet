

<?php $__env->startSection('page-title', 'Edit Wallet - ' . $wallet->label); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <!-- Back Button -->
    <div class="row mb-4">
        <div class="col-12">
            <a href="<?php echo e(route('admin.wallets')); ?>" class="btn btn-outline-secondary">
                <i class="ri-arrow-left-line me-2"></i>Back to Wallets
            </a>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Wallet Details Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0"><i class="ri-wallet-line me-2"></i>Edit Wallet</h5>
                </div>
                <div class="card-body">
                    <!-- Current Wallet Info -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card border">
                                <div class="card-body">
                                    <h6 class="card-title text-muted mb-3">Current Information</h6>
                                    <table class="table table-sm">
                                        <tr>
                                            <th width="120">Crypto Type:</th>
                                            <td>
                                                <span class="badge bg-<?php echo e($wallet->crypto_type === 'BTC' ? 'warning' : 'info'); ?>">
                                                    <?php echo e($wallet->getCryptoTypeName()); ?>

                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Wallet Address:</th>
                                            <td>
                                                <code class="small"><?php echo e($wallet->address); ?></code>
                                                <button type="button" class="btn btn-sm btn-outline-secondary copy-btn ms-2" 
                                                        data-text="<?php echo e($wallet->address); ?>">
                                                    <i class="ri-file-copy-line"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Current Status:</th>
                                            <td>
                                                <?php if($wallet->is_active): ?>
                                                    <span class="badge bg-success">Active</span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary">Inactive</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Usage Count:</th>
                                            <td>
                                                <span class="badge bg-light text-dark"><?php echo e($wallet->usage_count); ?></span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Created:</th>
                                            <td><?php echo e($wallet->created_at->format('M d, Y H:i')); ?></td>
                                        </tr>
                                        <tr>
                                            <th>Created By:</th>
                                            <td><?php echo e($wallet->creator->name ?? 'System'); ?></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border">
                                <div class="card-body">
                                    <h6 class="card-title text-muted mb-3">Quick Actions</h6>
                                    <div class="d-grid gap-2">
                                        <?php if($wallet->is_active): ?>
                                            <form action="<?php echo e(route('admin.wallets.update', $wallet->id)); ?>" method="POST">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('PUT'); ?>
                                                <input type="hidden" name="is_active" value="0">
                                                <button type="submit" class="btn btn-warning w-100">
                                                    <i class="ri-eye-off-line me-2"></i>Deactivate Wallet
                                                </button>
                                            </form>
                                        <?php else: ?>
                                            <form action="<?php echo e(route('admin.wallets.update', $wallet->id)); ?>" method="POST">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('PUT'); ?>
                                                <input type="hidden" name="is_active" value="1">
                                                <input type="hidden" name="label" value="<?php echo e($wallet->label); ?>">
                                                <input type="hidden" name="notes" value="<?php echo e($wallet->notes); ?>">
                                                <button type="submit" class="btn btn-success w-100">
                                                    <i class="ri-eye-line me-2"></i>Activate Wallet
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                        
                                        <?php if($wallet->canBeDeleted()): ?>
                                        <form action="<?php echo e(route('admin.wallets.destroy', $wallet->id)); ?>" method="POST" 
                                              onsubmit="return confirm('Are you sure you want to delete this wallet? This action cannot be undone.');">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="btn btn-danger w-100">
                                                <i class="ri-delete-bin-line me-2"></i>Delete Wallet
                                            </button>
                                        </form>
                                        <?php else: ?>
                                        <button type="button" class="btn btn-secondary w-100" disabled>
                                            <i class="ri-delete-bin-line me-2"></i>Cannot Delete (Used)
                                        </button>
                                        <?php endif; ?>
                                        
                                        <a href="<?php echo e($wallet->getExplorerUrl()); ?>" target="_blank" class="btn btn-outline-info w-100">
                                            <i class="ri-external-link-line me-2"></i>View on Explorer
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Edit Form -->
                    <div class="card border">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">Edit Wallet Details</h6>
                        </div>
                        <div class="card-body">
                            <form action="<?php echo e(route('admin.wallets.update', $wallet->id)); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('PUT'); ?>
                                
                                <div class="row g-3">
                                    <div class="col-12">
                                        <div class="alert alert-info">
                                            <i class="ri-information-line me-2"></i>
                                            <strong>Note:</strong> Crypto type and wallet address cannot be changed. 
                                            If you need to change the address, create a new wallet instead.
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Label/Name *</label>
                                        <input type="text" name="label" class="form-control" 
                                               value="<?php echo e(old('label', $wallet->label)); ?>" required>
                                        <?php $__errorArgs = ['label'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <div class="text-danger small"><?php echo e($message); ?></div>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Status</label>
                                        <div class="form-check form-switch mt-2">
                                            <input type="checkbox" name="is_active" 
                                                   class="form-check-input" id="is_active"
                                                   <?php echo e(old('is_active', $wallet->is_active) ? 'checked' : ''); ?>>
                                            <label class="form-check-label fw-semibold" for="is_active">
                                                Active (visible to users)
                                            </label>
                                        </div>
                                    </div>
                                    
                                    <div class="col-12">
                                        <label class="form-label fw-semibold">Notes</label>
                                        <textarea name="notes" class="form-control" rows="4" 
                                                  placeholder="Any additional information about this wallet..."><?php echo e(old('notes', $wallet->notes)); ?></textarea>
                                        <?php $__errorArgs = ['notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <div class="text-danger small"><?php echo e($message); ?></div>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>
                                    
                                    <div class="col-12">
                                        <div class="d-flex justify-content-between">
                                            <a href="<?php echo e(route('admin.wallets')); ?>" class="btn btn-secondary">
                                                <i class="ri-close-line me-2"></i>Cancel
                                            </a>
                                            <button type="submit" class="btn btn-primary px-4">
                                                <i class="ri-save-line me-2"></i>Update Wallet
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    // Copy wallet address to clipboard
    document.querySelectorAll('.copy-btn').forEach(button => {
        button.addEventListener('click', function() {
            const address = this.getAttribute('data-text');
            navigator.clipboard.writeText(address).then(() => {
                const originalHTML = this.innerHTML;
                this.innerHTML = '<i class="ri-check-line"></i>';
                this.classList.remove('btn-outline-secondary');
                this.classList.add('btn-success');
                
                setTimeout(() => {
                    this.innerHTML = originalHTML;
                    this.classList.remove('btn-success');
                    this.classList.add('btn-outline-secondary');
                }, 2000);
            });
        });
    });
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Dee\Desktop\General\globalskyfleet\resources\views\admin\wallets\edit.blade.php ENDPATH**/ ?>