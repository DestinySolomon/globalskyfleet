

<?php $__env->startSection('page-title', 'Crypto Payment Details - #' . $payment->id); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <!-- Back Button -->
    <div class="row mb-4">
        <div class="col-12">
            <a href="<?php echo e(route('admin.payments.crypto')); ?>" class="btn btn-outline-secondary">
                <i class="ri-arrow-left-line me-2"></i>Back to Payments
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Left Column - Payment Details -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                    <h6 class="mb-0"><i class="ri-information-line me-2"></i>Payment Information</h6>
                    <span class="badge <?php echo e($payment->getStatusBadgeClass()); ?> fs-6">
                        <?php echo e(ucfirst($payment->status)); ?>

                    </span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <dl class="row mb-0">
                                <dt class="col-sm-4">Payment ID:</dt>
                                <dd class="col-sm-8">#<?php echo e($payment->id); ?></dd>
                                
                                <dt class="col-sm-4">Crypto Type:</dt>
                                <dd class="col-sm-8">
                                    <span class="badge bg-dark">
                                        <?php echo e($payment->crypto_type); ?>

                                    </span>
                                </dd>
                                
                                <dt class="col-sm-4">Amount:</dt>
                                <dd class="col-sm-8">
                                    <?php if($payment->crypto_type == 'BTC'): ?>
                                        <strong><?php echo e(number_format($payment->crypto_amount, 8)); ?> BTC</strong>
                                    <?php else: ?>
                                        <strong><?php echo e(number_format($payment->usdt_amount, 6)); ?> USDT</strong>
                                    <?php endif; ?>
                                    <?php if($payment->exchange_rate): ?>
                                        <div class="text-muted small">
                                            ≈ $<?php echo e(number_format($payment->usdt_amount, 2)); ?>

                                            @ Rate: <?php echo e(number_format($payment->exchange_rate, 8)); ?>

                                        </div>
                                    <?php endif; ?>
                                </dd>
                                
                                <dt class="col-sm-4">Confirmations:</dt>
                                <dd class="col-sm-8">
                                    <?php echo e($payment->confirmations); ?>

                                    <?php if($payment->confirmations >= 3): ?>
                                        <span class="badge bg-success ms-2">Secure</span>
                                    <?php endif; ?>
                                </dd>
                            </dl>
                        </div>
                        
                        <div class="col-md-6">
                            <dl class="row mb-0">
                                <dt class="col-sm-4">Created:</dt>
                                <dd class="col-sm-8"><?php echo e($payment->created_at->format('M d, Y H:i:s')); ?></dd>
                                
                                <dt class="col-sm-4">Paid:</dt>
                                <dd class="col-sm-8">
                                    <?php if($payment->paid_at): ?>
                                        <?php echo e($payment->paid_at->format('M d, Y H:i:s')); ?>

                                    <?php else: ?>
                                        <span class="text-muted">Not paid yet</span>
                                    <?php endif; ?>
                                </dd>
                                
                                <dt class="col-sm-4">Confirmed:</dt>
                                <dd class="col-sm-8">
                                    <?php if($payment->confirmed_at): ?>
                                        <?php echo e($payment->confirmed_at->format('M d, Y H:i:s')); ?>

                                    <?php else: ?>
                                        <span class="text-muted">Not confirmed</span>
                                    <?php endif; ?>
                                </dd>
                                
                                <dt class="col-sm-4">Expires:</dt>
                                <dd class="col-sm-8">
                                    <?php if($payment->expires_at): ?>
                                        <?php echo e($payment->expires_at->format('M d, Y H:i:s')); ?>

                                        <?php if($payment->isExpired()): ?>
                                            <span class="badge bg-danger ms-2">Expired</span>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span class="text-muted">No expiry</span>
                                    <?php endif; ?>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Wallet & Transaction Info -->
            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white border-bottom">
                            <h6 class="mb-0"><i class="ri-wallet-line me-2"></i>Payment Address</h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label small text-muted mb-1">Wallet Address</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" 
                                           value="<?php echo e($payment->payment_address); ?>" 
                                           id="paymentAddress" readonly>
                                    <button class="btn btn-outline-secondary copy-btn" 
                                            type="button"
                                            data-target="#paymentAddress">
                                        <i class="ri-file-copy-line"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <?php if($payment->transaction_id): ?>
                            <div class="mb-3">
                                <label class="form-label small text-muted mb-1">Transaction ID/Hash</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" 
                                           value="<?php echo e($payment->transaction_id); ?>" 
                                           id="transactionId" readonly>
                                    <button class="btn btn-outline-secondary copy-btn" 
                                            type="button"
                                            data-target="#transactionId">
                                        <i class="ri-file-copy-line"></i>
                                    </button>
                                </div>
                            </div>
                            <?php endif; ?>
                            
                            <?php if($payment->verifier): ?>
                            <div class="mt-3 pt-3 border-top">
                                <label class="form-label small text-muted mb-1">Verified By</label>
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center me-2" 
                                         style="width: 36px; height: 36px; font-size: 14px; font-weight: bold;">
                                        <?php echo e(substr($payment->verifier->name, 0, 2)); ?>

                                    </div>
                                    <div>
                                        <div class="fw-semibold"><?php echo e($payment->verifier->name); ?></div>
                                        <small class="text-muted">
                                            <?php echo e($payment->verified_at->format('M d, Y H:i')); ?>

                                        </small>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white border-bottom">
                            <h6 class="mb-0"><i class="ri-user-line me-2"></i>User Information</h6>
                        </div>
                        <div class="card-body">
                            <?php if($payment->user): ?>
                            <div class="d-flex align-items-center mb-4">
                                <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center me-3" 
                                     style="width: 48px; height: 48px; font-size: 16px; font-weight: bold;">
                                    <?php echo e(substr($payment->user->name, 0, 2)); ?>

                                </div>
                                <div>
                                    <div class="fw-semibold fs-5"><?php echo e($payment->user->name); ?></div>
                                    <div class="text-muted"><?php echo e($payment->user->email); ?></div>
                                    <small class="text-muted">User ID: <?php echo e($payment->user->id); ?></small>
                                </div>
                            </div>
                            
                            <div class="row small">
                                <div class="col-6">
                                    <div class="text-muted mb-1">Phone</div>
                                    <div class="fw-semibold"><?php echo e($payment->user->phone ?? 'Not provided'); ?></div>
                                </div>
                                <div class="col-6">
                                    <div class="text-muted mb-1">Role</div>
                                    <div>
                                        <span class="badge bg-<?php echo e($payment->user->role == 'admin' ? 'danger' : 'secondary'); ?>">
                                            <?php echo e(ucfirst($payment->user->role)); ?>

                                        </span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mt-3 pt-3 border-top">
                                <a href="<?php echo e(route('admin.users.show', $payment->user->id)); ?>" 
                                   class="btn btn-outline-primary btn-sm">
                                    <i class="ri-external-link-line me-1"></i>View User Profile
                                </a>
                            </div>
                            <?php else: ?>
                            <div class="text-center py-4">
                                <i class="ri-user-unfollow-line display-4 text-muted mb-3"></i>
                                <p class="text-muted mb-0">User not found or deleted</p>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Invoice Information -->
            <?php if($payment->invoice): ?>
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom">
                    <h6 class="mb-0"><i class="ri-file-text-line me-2"></i>Invoice Information</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <dl class="row mb-0">
                                <dt class="col-sm-4">Invoice #:</dt>
                                <dd class="col-sm-8"><?php echo e($payment->invoice->invoice_number); ?></dd>
                                
                                <dt class="col-sm-4">Amount Due:</dt>
                                <dd class="col-sm-8">$<?php echo e(number_format($payment->invoice->amount, 2)); ?></dd>
                                
                                <dt class="col-sm-4">Description:</dt>
                                <dd class="col-sm-8"><?php echo e($payment->invoice->description); ?></dd>
                            </dl>
                        </div>
                        <div class="col-md-6">
                            <dl class="row mb-0">
                                <dt class="col-sm-4">Due Date:</dt>
                                <dd class="col-sm-8"><?php echo e($payment->invoice->due_date->format('M d, Y')); ?></dd>
                                
                                <dt class="col-sm-4">Status:</dt>
                                <dd class="col-sm-8">
                                    <span class="badge bg-<?php echo e($payment->invoice->status == 'paid' ? 'success' : ($payment->invoice->status == 'pending' ? 'warning' : 'secondary')); ?>">
                                        <?php echo e(ucfirst($payment->invoice->status)); ?>

                                    </span>
                                </dd>
                                
                                <dt class="col-sm-4">Created:</dt>
                                <dd class="col-sm-8"><?php echo e($payment->invoice->created_at->format('M d, Y')); ?></dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Payment Proof -->
            <?php if($payment->payment_proof): ?>
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                    <h6 class="mb-0"><i class="ri-image-line me-2"></i>Payment Proof</h6>
                    <a href="<?php echo e(Storage::url($payment->payment_proof)); ?>" 
                       target="_blank" 
                       class="btn btn-sm btn-outline-primary">
                        <i class="ri-external-link-line me-1"></i>Open Full Size
                    </a>
                </div>
                <div class="card-body">
                    <div class="text-center">
                        <img src="<?php echo e(Storage::url($payment->payment_proof)); ?>" 
                             alt="Payment Proof" 
                             class="img-fluid rounded border" 
                             style="max-height: 400px;">
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Admin Notes -->
            <?php if($payment->admin_notes): ?>
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h6 class="mb-0"><i class="ri-sticky-note-line me-2"></i>Admin Notes</h6>
                </div>
                <div class="card-body">
                    <div class="bg-light p-3 rounded">
                        <p class="mb-0"><?php echo e($payment->admin_notes); ?></p>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- Right Column - Update Form -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm sticky-top" style="top: 20px;">
                <div class="card-header bg-white border-bottom">
                    <h6 class="mb-0"><i class="ri-edit-line me-2"></i>Update Payment</h6>
                </div>
                <div class="card-body">
                    <form action="<?php echo e(route('admin.payments.update', $payment->id)); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select" required>
                                <option value="pending" <?php echo e($payment->status == 'pending' ? 'selected' : ''); ?>>Pending</option>
                                <option value="processing" <?php echo e($payment->status == 'processing' ? 'selected' : ''); ?>>Processing</option>
                                <option value="confirmed" <?php echo e($payment->status == 'confirmed' ? 'selected' : ''); ?>>Confirmed</option>
                                <option value="completed" <?php echo e($payment->status == 'completed' ? 'selected' : ''); ?>>Completed</option>
                                <option value="failed" <?php echo e($payment->status == 'failed' ? 'selected' : ''); ?>>Failed</option>
                                <option value="expired" <?php echo e($payment->status == 'expired' ? 'selected' : ''); ?>>Expired</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Confirmations</label>
                            <input type="number" name="confirmations" 
                                   class="form-control" 
                                   value="<?php echo e($payment->confirmations); ?>" 
                                   min="0" 
                                   placeholder="Number of blockchain confirmations">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Admin Notes</label>
                            <textarea name="admin_notes" class="form-control" rows="4" 
                                      placeholder="Add notes about this payment..."><?php echo e($payment->admin_notes); ?></textarea>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="ri-save-line me-2"></i>Update Payment
                            </button>
                            
                            <a href="<?php echo e(route('admin.payments.crypto')); ?>" class="btn btn-outline-secondary">
                                <i class="ri-close-line me-2"></i>Cancel
                            </a>
                        </div>
                    </form>
                    
                    <!-- Quick Actions -->
                    <div class="mt-4 pt-4 border-top">
                        <h6 class="mb-3">Quick Actions</h6>
                        <div class="d-grid gap-2">
                            <?php if($payment->status == 'pending' || $payment->status == 'processing'): ?>
                            <button type="button" class="btn btn-success" 
                                    onclick="quickUpdate('confirmed')">
                                <i class="ri-check-line me-2"></i>Confirm Payment
                            </button>
                            <button type="button" class="btn btn-danger"
                                    onclick="quickUpdate('failed')">
                                <i class="ri-close-line me-2"></i>Reject Payment
                            </button>
                            <?php endif; ?>
                            
                            <?php if($payment->status == 'confirmed'): ?>
                            <button type="button" class="btn btn-success"
                                    onclick="quickUpdate('completed')">
                                <i class="ri-check-double-line me-2"></i>Mark as Completed
                            </button>
                            <?php endif; ?>
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
    // Copy to clipboard function
    document.querySelectorAll('.copy-btn').forEach(button => {
        button.addEventListener('click', function() {
            const targetId = this.getAttribute('data-target');
            const input = document.querySelector(targetId);
            input.select();
            document.execCommand('copy');
            
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
    
    // Quick status update
    function quickUpdate(status) {
        if (!confirm(`Are you sure you want to mark this payment as ${status}?`)) {
            return;
        }
        
        // Create a hidden form
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '<?php echo e(route("admin.payments.update", $payment->id)); ?>';
        
        // Add CSRF token
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = '<?php echo e(csrf_token()); ?>';
        form.appendChild(csrfInput);
        
        // Add status input
        const statusInput = document.createElement('input');
        statusInput.type = 'hidden';
        statusInput.name = 'status';
        statusInput.value = status;
        form.appendChild(statusInput);
        
        // Add confirmations input (keep current value)
        const confirmationsInput = document.createElement('input');
        confirmationsInput.type = 'hidden';
        confirmationsInput.name = 'confirmations';
        confirmationsInput.value = '<?php echo e($payment->confirmations); ?>';
        form.appendChild(confirmationsInput);
        
        // Add admin notes input (keep current value)
        const notesInput = document.createElement('input');
        notesInput.type = 'hidden';
        notesInput.name = 'admin_notes';
        notesInput.value = `<?php echo e($payment->admin_notes); ?>`;
        form.appendChild(notesInput);
        
        // Submit form
        document.body.appendChild(form);
        form.submit();
    }
</script>
<?php $__env->stopPush(); ?>

<style>
    .sticky-top {
        position: sticky;
        z-index: 1;
    }
    
    .badge {
        font-size: 0.75rem;
        font-weight: 500;
    }
    
    code {
        font-size: 0.875rem;
        background: #f8f9fa;
        padding: 2px 6px;
        border-radius: 4px;
        color: #d63384;
    }
    
    .input-group input:readonly {
        background-color: #f8f9fa;
    }
</style>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Dee\Desktop\General\globalskyfleet\resources\views\admin\payments\show.blade.php ENDPATH**/ ?>