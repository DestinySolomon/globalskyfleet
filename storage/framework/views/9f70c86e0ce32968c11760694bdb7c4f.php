

<?php $__env->startSection('page-title', 'Analytics & Reports'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <!-- Date Range Filter -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <form action="<?php echo e(route('admin.analytics')); ?>" method="GET" class="row g-3 align-items-end">
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Start Date</label>
                            <input type="date" name="start_date" class="form-control" 
                                   value="<?php echo e(request('start_date', now()->subDays(30)->format('Y-m-d'))); ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">End Date</label>
                            <input type="date" name="end_date" class="form-control" 
                                   value="<?php echo e(request('end_date', now()->format('Y-m-d'))); ?>">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold">Report Type</label>
                            <select name="report_type" class="form-select">
                                <option value="daily" <?php echo e(request('report_type') == 'daily' ? 'selected' : ''); ?>>Daily</option>
                                <option value="weekly" <?php echo e(request('report_type') == 'weekly' ? 'selected' : ''); ?>>Weekly</option>
                                <option value="monthly" <?php echo e(request('report_type') == 'monthly' ? 'selected' : ''); ?>>Monthly</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100">Generate Report</button>
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-success w-100" id="exportBtn">
                                <i class="ri-download-line me-2"></i>Export
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Stats -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-primary bg-opacity-10 p-3 me-3">
                            <i class="ri-user-line text-primary fs-3"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h3 class="mb-1"><?php echo e($userCount ?? 0); ?></h3>
                            <p class="text-muted mb-0">New Users</p>
                            <small class="text-success">
                                <?php if($userGrowth ?? 0 > 0): ?>
                                    +<?php echo e($userGrowth ?? 0); ?>% growth
                                <?php elseif($userGrowth ?? 0 < 0): ?>
                                    <?php echo e($userGrowth ?? 0); ?>% decline
                                <?php else: ?>
                                    No change
                                <?php endif; ?>
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-info bg-opacity-10 p-3 me-3">
                            <i class="ri-ship-line text-info fs-3"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h3 class="mb-1"><?php echo e($shipmentCount ?? 0); ?></h3>
                            <p class="text-muted mb-0">Shipments</p>
                            <small class="text-success">
                                <?php if($shipmentGrowth ?? 0 > 0): ?>
                                    +<?php echo e($shipmentGrowth ?? 0); ?>% growth
                                <?php elseif($shipmentGrowth ?? 0 < 0): ?>
                                    <?php echo e($shipmentGrowth ?? 0); ?>% decline
                                <?php else: ?>
                                    No change
                                <?php endif; ?>
                            </small>
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
                            <i class="ri-money-dollar-circle-line text-success fs-3"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h3 class="mb-1">$<?php echo e(number_format($revenue ?? 0, 0)); ?></h3>
                            <p class="text-muted mb-0">Revenue</p>
                            <small class="text-success">
                                <?php if($revenueGrowth ?? 0 > 0): ?>
                                    +<?php echo e($revenueGrowth ?? 0); ?>% growth
                                <?php elseif($revenueGrowth ?? 0 < 0): ?>
                                    <?php echo e($revenueGrowth ?? 0); ?>% decline
                                <?php else: ?>
                                    No change
                                <?php endif; ?>
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-warning bg-opacity-10 p-3 me-3">
                            <i class="ri-file-text-line text-warning fs-3"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h3 class="mb-1"><?php echo e($documentCount ?? 0); ?></h3>
                            <p class="text-muted mb-0">Documents</p>
                            <small class="text-success">
                                <?php if($documentGrowth ?? 0 > 0): ?>
                                    +<?php echo e($documentGrowth ?? 0); ?>% growth
                                <?php elseif($documentGrowth ?? 0 < 0): ?>
                                    <?php echo e($documentGrowth ?? 0); ?>% decline
                                <?php else: ?>
                                    No change
                                <?php endif; ?>
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row g-4 mb-4">
        <!-- Shipments Chart -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom">
                    <h6 class="mb-0"><i class="ri-bar-chart-line me-2"></i>Shipment Trends</h6>
                </div>
                <div class="card-body">
                    <canvas id="shipmentsChart" height="250"></canvas>
                </div>
            </div>
        </div>

        <!-- Shipment Status Distribution -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom">
                    <h6 class="mb-0"><i class="ri-pie-chart-line me-2"></i>Shipment Status</h6>
                </div>
                <div class="card-body">
                    <canvas id="statusChart" height="250"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Reports -->
    <div class="row g-4">
        <!-- Top Users -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                    <h6 class="mb-0"><i class="ri-medal-line me-2"></i>Top Users by Shipments</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>User</th>
                                    <th>Shipments</th>
                                    <th>Delivered</th>
                                    <th>Revenue</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $topUsers ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $topUser): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center me-2" 
                                                 style="width: 32px; height: 32px; font-size: 12px; font-weight: bold;">
                                                <?php echo e(substr($topUser->name, 0, 2)); ?>

                                            </div>
                                            <div>
                                                <div class="fw-semibold"><?php echo e($topUser->name); ?></div>
                                                <small class="text-muted"><?php echo e($topUser->email); ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td><?php echo e($topUser->shipment_count ?? 0); ?></td>
                                    <td><?php echo e($topUser->delivered_count ?? 0); ?></td>
                                    <td>$<?php echo e(number_format($topUser->total_spent ?? 0, 2)); ?></td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                    <h6 class="mb-0"><i class="ri-history-line me-2"></i>Recent Activity</h6>
                </div>
                <div class="card-body">
                    <div class="activity-timeline">
                        <?php $__empty_1 = true; $__currentLoopData = $recentActivity ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="activity-item mb-3">
                            <div class="d-flex">
                                <div class="activity-icon me-3">
                                    <div class="rounded-circle bg-light p-2">
                                        <?php if($activity['type'] === 'shipment'): ?>
                                            <i class="ri-ship-line text-primary"></i>
                                        <?php elseif($activity['type'] === 'user'): ?>
                                            <i class="ri-user-add-line text-success"></i>
                                        <?php elseif($activity['type'] === 'payment'): ?>
                                            <i class="ri-money-dollar-circle-line text-warning"></i>
                                        <?php elseif($activity['type'] === 'document'): ?>
                                            <i class="ri-file-text-line text-info"></i>
                                        <?php elseif($activity['type'] === 'crypto'): ?>
                                            <i class="ri-bit-coin-line text-warning"></i>
                                        <?php elseif($activity['type'] === 'status_update'): ?>
                                            <i class="ri-refresh-line text-secondary"></i>
                                        <?php else: ?>
                                            <i class="ri-notification-line text-info"></i>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between">
                                        <h6 class="mb-1"><?php echo e($activity['title']); ?></h6>
                                        <small class="text-muted"><?php echo e($activity['time']); ?></small>
                                    </div>
                                    <p class="mb-0 text-muted small"><?php echo e($activity['description']); ?></p>
                                    <?php if(isset($activity['user_name']) && $activity['user_name'] !== 'N/A'): ?>
                                    <small class="text-muted">
                                        <i class="ri-user-line me-1"></i><?php echo e($activity['user_name']); ?>

                                    </small>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="text-center py-4">
                            <i class="ri-inbox-line display-4 text-muted mb-3"></i>
                            <p class="text-muted mb-0">No recent activity found for the selected date range.</p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Shipments Chart
    const shipmentsCtx = document.getElementById('shipmentsChart').getContext('2d');
    const shipmentsChart = new Chart(shipmentsCtx, {
        type: 'line',
        data: {
            labels: <?php echo json_encode($shipmentLabels ?? []); ?>,
            datasets: [{
                label: 'Shipments',
                data: <?php echo json_encode($shipmentData ?? []); ?>,
                borderColor: '#0a2463',
                backgroundColor: 'rgba(10, 36, 99, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });

    // Status Chart
    const statusCtx = document.getElementById('statusChart').getContext('2d');
    const statusChart = new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: ['Pending', 'In Transit', 'Delivered', 'Cancelled'],
            datasets: [{
                data: <?php echo json_encode($statusData ?? [10, 15, 60, 5]); ?>,
                backgroundColor: [
                    '#f59e0b',
                    '#3b82f6',
                    '#10b981',
                    '#ef4444'
                ],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            cutout: '70%',
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // Export button
    document.getElementById('exportBtn').addEventListener('click', function() {
        const startDate = document.querySelector('input[name="start_date"]').value;
        const endDate = document.querySelector('input[name="end_date"]').value;
        const reportType = document.querySelector('select[name="report_type"]').value;
        
        const url = new URL('<?php echo e(route("admin.analytics.export")); ?>', window.location.origin);
        url.searchParams.set('start_date', startDate);
        url.searchParams.set('end_date', endDate);
        url.searchParams.set('report_type', reportType);
        
        window.location.href = url.toString();
    });
});
</script>

<style>
.activity-timeline {
    position: relative;
}
.activity-timeline::before {
    content: '';
    position: absolute;
    left: 24px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #e5e7eb;
}
.activity-item {
    position: relative;
}
.activity-icon {
    position: relative;
    z-index: 1;
}
</style>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Dee\Desktop\General\globalskyfleet\resources\views/admin/analytics/index.blade.php ENDPATH**/ ?>