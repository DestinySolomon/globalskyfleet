

<?php $__env->startSection('page-title', 'Crypto Payments Management'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <!-- Stats Overview -->
    <div class="row mb-4">
        <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-warning bg-opacity-10 p-3 me-3">
                            <i class="ri-time-line text-warning fs-3"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h3 class="mb-1"><?php echo e($pendingCount ?? 0); ?></h3>
                            <p class="text-muted mb-0">Pending</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-primary bg-opacity-10 p-3 me-3">
                            <i class="ri-check-line text-primary fs-3"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h3 class="mb-1"><?php echo e($confirmedCount ?? 0); ?></h3>
                            <p class="text-muted mb-0">Confirmed</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-success bg-opacity-10 p-3 me-3">
                            <i class="ri-check-double-line text-success fs-3"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h3 class="mb-1"><?php echo e($completedCount ?? 0); ?></h3>
                            <p class="text-muted mb-0">Completed</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-danger bg-opacity-10 p-3 me-3">
                            <i class="ri-close-line text-danger fs-3"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h3 class="mb-1"><?php echo e($failedCount ?? 0); ?></h3>
                            <p class="text-muted mb-0">Failed/Expired</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <form action="<?php echo e(route('admin.payments.crypto')); ?>" method="GET" class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="">All Statuses</option>
                                <?php $__currentLoopData = $statuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($key); ?>" <?php echo e(request('status') == $key ? 'selected' : ''); ?>>
                                        <?php echo e($label); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        
                        <div class="col-md-3">
                            <label class="form-label">Crypto Type</label>
                            <select name="crypto_type" class="form-select">
                                <option value="">All Types</option>
                                <?php $__currentLoopData = $cryptoTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($key); ?>" <?php echo e(request('crypto_type') == $key ? 'selected' : ''); ?>>
                                        <?php echo e($label); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        
                        <div class="col-md-3">
                            <label class="form-label">Date From</label>
                            <input type="date" name="date_from" class="form-control" value="<?php echo e(request('date_from')); ?>">
                        </div>
                        
                        <div class="col-md-3">
                            <label class="form-label">Date To</label>
                            <input type="date" name="date_to" class="form-control" value="<?php echo e(request('date_to')); ?>">
                        </div>
                        
                        <div class="col-md-12">
                            <label class="form-label">Search (Transaction ID, Address, User Email)</label>
                            <input type="text" name="search" class="form-control" placeholder="Search..." value="<?php echo e(request('search')); ?>">
                        </div>
                        
                        <div class="col-md-12">
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="ri-search-line me-2"></i>Filter
                                </button>
                                <a href="<?php echo e(route('admin.payments.crypto')); ?>" class="btn btn-secondary">
                                    <i class="ri-refresh-line me-2"></i>Reset
                                </a>
                                <button type="button" class="btn btn-success ms-auto" data-bs-toggle="modal" data-bs-target="#exportModal">
                                    <i class="ri-download-line me-2"></i>Export
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Payments Table -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                    <h6 class="mb-0"><i class="ri-currency-line me-2"></i>Crypto Payments</h6>
                    <div class="text-muted small">
                        Total: <?php echo e($payments->total()); ?> payments
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>User</th>
                                    <th>Crypto Type</th>
                                    <th>Amount</th>
                                    <th>Address</th>
                                    <th>Transaction</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td>
                                        <a href="<?php echo e(route('admin.payments.crypto.show', $payment->id)); ?>" class="text-decoration-none">
                                            <div class="fw-semibold text-primary">#<?php echo e($payment->id); ?></div>
                                        </a>
                                        <?php if($payment->invoice): ?>
                                            <small class="text-muted">Invoice: <?php echo e($payment->invoice->invoice_number); ?></small>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if($payment->user): ?>
                                            <div class="d-flex align-items-center">
                                                <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center me-2" 
                                                     style="width: 32px; height: 32px; font-size: 12px; font-weight: bold;">
                                                    <?php echo e(substr($payment->user->name, 0, 2)); ?>

                                                </div>
                                                <div>
                                                    <div class="fw-semibold"><?php echo e($payment->user->name); ?></div>
                                                    <small class="text-muted"><?php echo e($payment->user->email); ?></small>
                                                </div>
                                            </div>
                                        <?php else: ?>
                                            <span class="text-muted">User not found</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <?php if($payment->crypto_type == 'BTC'): ?>
                                                <i class="ri-bit-coin-line text-warning fs-5 me-2"></i>
                                            <?php else: ?>
                                                <i class="ri-currency-line text-success fs-5 me-2"></i>
                                            <?php endif; ?>
                                            <div>
                                                <div class="fw-semibold"><?php echo e($payment->crypto_type); ?></div>
                                                <small class="text-muted">
                                                    <?php if($payment->crypto_type == 'BTC'): ?>
                                                        <?php echo e($payment->crypto_amount); ?> BTC
                                                    <?php else: ?>
                                                        <?php echo e($payment->usdt_amount); ?> USDT
                                                    <?php endif; ?>
                                                </small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <?php if($payment->exchange_rate): ?>
                                            <div class="fw-semibold">$<?php echo e(number_format($payment->usdt_amount, 2)); ?></div>
                                            <small class="text-muted">
                                                Rate: <?php echo e(number_format($payment->exchange_rate, 8)); ?>

                                            </small>
                                        <?php else: ?>
                                            <span class="text-muted">Calculating...</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="text-truncate" style="max-width: 150px;" 
                                             title="<?php echo e($payment->payment_address); ?>">
                                            <code><?php echo e($payment->payment_address); ?></code>
                                        </div>
                                        <?php if($payment->confirmations > 0): ?>
                                            <small class="text-success">
                                                <?php echo e($payment->confirmations); ?> confirmations
                                            </small>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if($payment->transaction_id): ?>
                                            <div class="text-truncate" style="max-width: 150px;"
                                                 title="<?php echo e($payment->transaction_id); ?>">
                                                <code><?php echo e($payment->transaction_id); ?></code>
                                            </div>
                                            <?php if($payment->paid_at): ?>
                                                <small class="text-muted">
                                                    <?php echo e($payment->paid_at->format('M d, H:i')); ?>

                                                </small>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <span class="text-muted">Not provided</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="badge <?php echo e($payment->getStatusBadgeClass()); ?>">
                                            <?php echo e(ucfirst($payment->status)); ?>

                                        </span>
                                        <?php if($payment->isExpired()): ?>
                                            <small class="d-block text-danger">Expired</small>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div><?php echo e($payment->created_at->format('M d, Y')); ?></div>
                                        <small class="text-muted"><?php echo e($payment->created_at->format('H:i')); ?></small>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="<?php echo e(route('admin.payments.crypto.show', $payment->id)); ?>" 
                                               class="btn btn-primary" title="View Details">
                                                <i class="ri-eye-line"></i>
                                            </a>
                                            <?php if($payment->status == 'pending' || $payment->status == 'processing'): ?>
                                                <button type="button" class="btn btn-success" 
                                                        onclick="updatePaymentStatus(<?php echo e($payment->id); ?>, 'confirmed')"
                                                        title="Confirm Payment">
                                                    <i class="ri-check-line"></i>
                                                </button>
                                                <button type="button" class="btn btn-danger"
                                                        onclick="updatePaymentStatus(<?php echo e($payment->id); ?>, 'failed')"
                                                        title="Reject Payment">
                                                    <i class="ri-close-line"></i>
                                                </button>
                                            <?php endif; ?>
                                            <?php if($payment->status == 'confirmed'): ?>
                                                <button type="button" class="btn btn-success"
                                                        onclick="updatePaymentStatus(<?php echo e($payment->id); ?>, 'completed')"
                                                        title="Mark as Completed">
                                                    <i class="ri-check-double-line"></i>
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="9" class="text-center py-5">
                                        <i class="ri-inbox-line display-4 text-muted mb-3"></i>
                                        <p class="text-muted mb-0">No crypto payments found.</p>
                                    </td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <?php if($payments->hasPages()): ?>
                    <div class="mt-4">
                        <?php echo e($payments->links()); ?>

                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Export Modal -->
<div class="modal fade" id="exportModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Export Crypto Payments</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="<?php echo e(route('admin.payments.crypto.export')); ?>" method="GET">
                    <div class="mb-3">
                        <label class="form-label">Format</label>
                        <select name="format" class="form-select">
                            <option value="csv">CSV</option>
                            <option value="excel">Excel</option>
                            <option value="pdf">PDF</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Date Range</label>
                        <div class="row g-2">
                            <div class="col-6">
                                <input type="date" name="export_date_from" class="form-control" placeholder="From">
                            </div>
                            <div class="col-6">
                                <input type="date" name="export_date_to" class="form-control" placeholder="To">
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Include Fields</label>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="fields[]" value="user" checked>
                                    <label class="form-check-label">User Info</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="fields[]" value="amounts" checked>
                                    <label class="form-check-label">Amounts</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="fields[]" value="address" checked>
                                    <label class="form-check-label">Wallet Address</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="fields[]" value="transaction" checked>
                                    <label class="form-check-label">Transaction ID</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="fields[]" value="status" checked>
                                    <label class="form-check-label">Status</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="fields[]" value="timestamps" checked>
                                    <label class="form-check-label">Timestamps</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success w-100">
                        <i class="ri-download-line me-2"></i>Export Data
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    // Quick status update
    function updatePaymentStatus(paymentId, status) {
        if (!confirm(`Are you sure you want to mark this payment as ${status}?`)) {
            return;
        }
        
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/payments/crypto/${paymentId}/update`;
        
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = csrfToken;
        form.appendChild(csrfInput);
        
        const statusInput = document.createElement('input');
        statusInput.type = 'hidden';
        statusInput.name = 'status';
        statusInput.value = status;
        form.appendChild(statusInput);
        
        document.body.appendChild(form);
        form.submit();
    }
    
    // Filter form auto-submit on some changes
    document.querySelectorAll('select[name="status"], select[name="crypto_type"]').forEach(select => {
        select.addEventListener('change', function() {
            this.form.submit();
        });
    });
</script>
<?php $__env->stopPush(); ?>

<style>
    .text-truncate {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    
    .badge {
        font-size: 0.75rem;
        font-weight: 500;
    }
    
    .table-hover tbody tr:hover {
        background-color: rgba(10, 36, 99, 0.02);
    }
    
    code {
        font-size: 0.875rem;
        background: #f8f9fa;
        padding: 2px 6px;
        border-radius: 4px;
        color: #d63384;
    }
</style>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Dee\Desktop\General\globalskyfleet\resources\views\admin\payments\crypto.blade.php ENDPATH**/ ?>