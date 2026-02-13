

<?php $__env->startSection('title', 'Billing & Invoices - GlobalSkyFleet'); ?>

<?php $__env->startSection('page-title', 'Billing & Invoices'); ?>

<?php $__env->startSection('content'); ?>
<div class="billing-dashboard">
    <!-- Pay Now Button -->
    <?php if($stats['balance_due'] > 0): ?>
    <div class="alert alert-warning">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <strong><i class="ri-alert-line"></i> You have outstanding balance: $<?php echo e(number_format($stats['balance_due'], 2)); ?></strong>
                <p class="mb-0 small">Pay now to avoid service interruptions</p>
            </div>
            <div>
                <?php
                    $oldestPendingInvoice = Auth::user()->invoices()->where('status', 'pending')->oldest()->first();
                ?>
                <?php if($oldestPendingInvoice): ?>
                    <a href="<?php echo e(route('billing.pay', $oldestPendingInvoice)); ?>" class="btn btn-warning">
                        <i class="ri-money-dollar-circle-line"></i> Pay Now
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Stats Overview -->
    <div class="stats-grid mb-4">
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #3b82f6, #1e40af);">
                <i class="ri-bill-line text-white"></i>
            </div>
            <div class="stat-value"><?php echo e($stats['total_invoices']); ?></div>
            <div class="stat-label">Total Invoices</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
                <i class="ri-time-line text-white"></i>
            </div>
            <div class="stat-value"><?php echo e($stats['pending_invoices']); ?></div>
            <div class="stat-label">Pending Invoices</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #ef4444, #dc2626);">
                <i class="ri-alert-line text-white"></i>
            </div>
            <div class="stat-value"><?php echo e($stats['overdue_invoices']); ?></div>
            <div class="stat-label">Overdue Invoices</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #10b981, #059669);">
                <i class="ri-wallet-3-line text-white"></i>
            </div>
            <div class="stat-value">$<?php echo e(number_format($stats['balance_due'], 2)); ?></div>
            <div class="stat-label">Balance Due</div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-3">Quick Actions</h5>
                    <div class="d-grid gap-2">
                        <a href="<?php echo e(route('billing.invoices')); ?>" class="btn btn-primary">
                            <i class="ri-list-check-2"></i> View All Invoices
                        </a>
                        <a href="<?php echo e(route('billing.payments')); ?>" class="btn btn-outline-primary">
                            <i class="ri-history-line"></i> Payment History
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-3">Need Help?</h5>
                    <p class="text-muted small mb-3">
                        Having trouble with payments? Contact our billing support team.
                    </p>
                    <a href="<?php echo e(route('contact')); ?>" class="btn btn-outline-secondary">
                        <i class="ri-customer-service-2-line"></i> Contact Support
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Invoices -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white border-bottom">
            <h5 class="mb-0">Recent Invoices</h5>
        </div>
        <div class="card-body p-0">
            <?php if($recentInvoices->count() > 0): ?>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Invoice #</th>
                                <th>Date</th>
                                <th>Due Date</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $recentInvoices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $invoice): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td class="fw-semibold"><?php echo e($invoice->invoice_number); ?></td>
                                    <td><?php echo e($invoice->invoice_date->format('M d, Y')); ?></td>
                                    <td class="<?php echo e($invoice->is_overdue ? 'text-danger' : ''); ?>">
                                        <?php echo e($invoice->due_date->format('M d, Y')); ?>

                                        <?php if($invoice->is_overdue): ?>
                                            <span class="badge bg-danger ms-1">Overdue</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="fw-semibold">$<?php echo e(number_format($invoice->amount, 2)); ?></td>
                                    <td>
                                        <span class="badge <?php echo e($invoice->status === 'paid' ? 'bg-success' : ($invoice->status === 'pending' ? 'bg-warning' : 'bg-secondary')); ?>">
                                            <?php echo e(ucfirst($invoice->status)); ?>

                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="<?php echo e(route('billing.invoices.show', $invoice)); ?>" class="btn btn-outline-primary">
                                                <i class="ri-eye-line"></i>
                                            </a>
                                            <?php if($invoice->status === 'pending'): ?>
                                                <a href="<?php echo e(route('billing.pay', $invoice)); ?>" class="btn btn-outline-success">
                                                    <i class="ri-money-dollar-circle-line"></i>
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-center py-5">
                    <i class="ri-bill-line display-1 text-muted"></i>
                    <p class="mt-3 text-muted">No invoices found.</p>
                </div>
            <?php endif; ?>
        </div>
        <?php if($recentInvoices->count() > 0): ?>
            <div class="card-footer bg-white border-top">
                <a href="<?php echo e(route('billing.invoices')); ?>" class="btn btn-link">
                    View All Invoices <i class="ri-arrow-right-line"></i>
                </a>
            </div>
        <?php endif; ?>
    </div>

    <!-- Recent Payments -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom">
            <h5 class="mb-0">Recent Payments</h5>
        </div>
        <div class="card-body p-0">
            <?php if($recentPayments->count() > 0): ?>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Invoice #</th>
                                <th>Method</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $recentPayments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($payment->created_at->format('M d, Y')); ?></td>
                                    <td class="fw-semibold"><?php echo e($payment->invoice->invoice_number ?? 'N/A'); ?></td>
                                    <td>
                                        <span class="badge bg-info">
                                            <?php echo e(str_replace('_', ' ', $payment->crypto_type)); ?>

                                        </span>
                                    </td>
                                    <td class="fw-semibold">
                                        <?php if($payment->crypto_type === 'BTC'): ?>
                                            <?php echo e($payment->crypto_amount ? number_format($payment->crypto_amount, 8) : 'N/A'); ?> BTC
                                        <?php else: ?>
                                            <?php echo e($payment->usdt_amount ? number_format($payment->usdt_amount, 2) : 'N/A'); ?> USDT
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="badge <?php echo e($payment->status === 'completed' ? 'bg-success' : ($payment->status === 'pending' ? 'bg-warning' : 'bg-secondary')); ?>">
                                            <?php echo e(ucfirst($payment->status)); ?>

                                        </span>
                                    </td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-outline-secondary">
                                            <i class="ri-information-line"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-center py-5">
                    <i class="ri-history-line display-1 text-muted"></i>
                    <p class="mt-3 text-muted">No payment history yet.</p>
                </div>
            <?php endif; ?>
        </div>
        <?php if($recentPayments->count() > 0): ?>
            <div class="card-footer bg-white border-top">
                <a href="<?php echo e(route('billing.payments')); ?>" class="btn btn-link">
                    View All Payments <i class="ri-arrow-right-line"></i>
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
.billing-dashboard .card {
    border-radius: 12px;
}

.billing-dashboard .table {
    margin-bottom: 0;
}

.billing-dashboard .table th {
    background-color: #f8fafc;
    font-weight: 600;
    color: #475569;
    padding: 1rem 1.5rem;
}

.billing-dashboard .table td {
    padding: 1rem 1.5rem;
    vertical-align: middle;
}

.billing-dashboard .btn-group-sm .btn {
    padding: 0.25rem 0.5rem;
}
</style>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Dee\Desktop\globalsky-final\globalskyfleet_fixed\resources\views/billing/index.blade.php ENDPATH**/ ?>