@extends('layouts.dashboard')

@section('title', 'My Invoices - GlobalSkyFleet')

@section('page-title', 'My Invoices')

@section('content')
<div class="invoices-page">
    <!-- Page Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
        <div class="mb-3 mb-md-0">
            <h1 class="h3 mb-2">My Invoices</h1>
            <p class="text-muted mb-0">View and manage all your invoices</p>
        </div>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-outline-secondary" id="refreshBtn" title="Refresh invoice list">
                <i class="ri-refresh-line me-1"></i> Refresh
            </button>
            <a href="{{ route('billing.index') }}" class="btn btn-outline-primary">
                <i class="ri-arrow-left-line me-1"></i> Back to Billing
            </a>
        </div>
    </div>

    <!-- Stats Bar -->
    <div class="row mb-4 g-3">
        <div class="col-6 col-md-3">
            <div class="card border-0 bg-primary bg-opacity-10 h-100">
                <div class="card-body py-3">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="ri-bill-line fs-4 text-primary"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0 small">Total Invoices</h6>
                            <p class="fs-5 fw-bold mb-0">{{ $invoices->total() }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 bg-warning bg-opacity-10 h-100">
                <div class="card-body py-3">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="ri-time-line fs-4 text-warning"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0 small">Pending</h6>
                            <p class="fs-5 fw-bold mb-0">{{ $invoices->where('status', 'pending')->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 bg-success bg-opacity-10 h-100">
                <div class="card-body py-3">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="ri-check-double-line fs-4 text-success"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0 small">Paid</h6>
                            <p class="fs-5 fw-bold mb-0">{{ $invoices->where('status', 'paid')->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 bg-danger bg-opacity-10 h-100">
                <div class="card-body py-3">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="ri-alert-line fs-4 text-danger"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0 small">Overdue</h6>
                            <p class="fs-5 fw-bold mb-0">{{ $invoices->where('status', 'pending')->where('due_date', '<', now())->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-12 col-md-3">
                    <label class="form-label small fw-semibold">Status</label>
                    <select class="form-select form-select-sm" id="statusFilter">
                        <option value="">All Statuses</option>
                        <option value="pending">Pending</option>
                        <option value="paid">Paid</option>
                        <option value="overdue">Overdue</option>
                        <option value="draft">Draft</option>
                    </select>
                </div>
                <div class="col-12 col-md-3">
                    <label class="form-label small fw-semibold">Date From</label>
                    <input type="date" class="form-control form-control-sm" id="dateFrom">
                </div>
                <div class="col-12 col-md-3">
                    <label class="form-label small fw-semibold">Date To</label>
                    <input type="date" class="form-control form-control-sm" id="dateTo">
                </div>
                <div class="col-12 col-md-3 d-flex align-items-end">
                    <button class="btn btn-primary btn-sm w-100" id="applyFilters">
                        <i class="ri-filter-line me-1"></i> Apply Filters
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Invoices Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            @if($invoices->count() > 0)
                <!-- Desktop Table (hidden on mobile) -->
                <div class="table-responsive d-none d-md-block">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>
                                    <input type="checkbox" class="form-check-input" id="selectAll">
                                </th>
                                <th>Invoice #</th>
                                <th>Description</th>
                                <th>Date</th>
                                <th>Due Date</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($invoices as $invoice)
                                <tr class="{{ $invoice->is_overdue ? 'table-danger' : '' }}">
                                    <td>
                                        <input type="checkbox" class="form-check-input invoice-checkbox" value="{{ $invoice->id }}">
                                    </td>
                                    <td class="fw-semibold">
                                        <a href="{{ route('billing.invoices.show', $invoice) }}" class="text-decoration-none">
                                            {{ $invoice->invoice_number }}
                                        </a>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="ri-file-text-line text-muted me-2"></i>
                                            <span class="text-truncate" style="max-width: 200px;" title="{{ $invoice->description }}">
                                                {{ $invoice->description }}
                                            </span>
                                        </div>
                                    </td>
                                    <td>{{ $invoice->invoice_date->format('M d, Y') }}</td>
                                    <td class="{{ $invoice->is_overdue ? 'text-danger fw-bold' : '' }}">
                                        {{ $invoice->due_date->format('M d, Y') }}
                                        @if($invoice->is_overdue)
                                            <span class="badge bg-danger ms-1">Overdue</span>
                                        @endif
                                    </td>
                                    <td class="fw-bold">${{ number_format($invoice->amount, 2) }}</td>
                                    <td>
                                        @php
                                            $badgeClass = match($invoice->status) {
                                                'paid' => 'bg-success',
                                                'pending' => 'bg-warning',
                                                'overdue' => 'bg-danger',
                                                'draft' => 'bg-secondary',
                                                default => 'bg-info'
                                            };
                                        @endphp
                                        <span class="badge {{ $badgeClass }}">
                                            {{ ucfirst($invoice->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('billing.invoices.show', $invoice) }}" 
                                               class="btn btn-outline-primary" 
                                               title="View Invoice">
                                                <i class="ri-eye-line"></i>
                                            </a>
                                            <a href="{{ route('billing.invoices.download', $invoice) }}" 
                                               class="btn btn-outline-secondary" 
                                               title="Download Invoice">
                                                <i class="ri-download-line"></i>
                                            </a>
                                            @if($invoice->status === 'pending')
                                                <a href="{{ route('billing.pay', $invoice) }}" 
                                                   class="btn btn-outline-success" 
                                                   title="Pay Invoice">
                                                    <i class="ri-money-dollar-circle-line"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Mobile Cards (hidden on desktop) -->
                <div class="d-block d-md-none">
                    @foreach($invoices as $invoice)
                    <div class="card border-bottom rounded-0 bg-transparent">
                        <div class="card-body">
                            <!-- Invoice Header -->
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <div class="d-flex align-items-center mb-1">
                                        <input type="checkbox" class="form-check-input me-2 invoice-checkbox" value="{{ $invoice->id }}">
                                        <a href="{{ route('billing.invoices.show', $invoice) }}" class="text-decoration-none">
                                            <strong class="text-primary">{{ $invoice->invoice_number }}</strong>
                                        </a>
                                    </div>
                                    <div class="mb-2">
                                        @php
                                            $badgeClass = match($invoice->status) {
                                                'paid' => 'bg-success',
                                                'pending' => 'bg-warning',
                                                'overdue' => 'bg-danger',
                                                'draft' => 'bg-secondary',
                                                default => 'bg-info'
                                            };
                                        @endphp
                                        <span class="badge {{ $badgeClass }}">
                                            {{ ucfirst($invoice->status) }}
                                        </span>
                                        @if($invoice->is_overdue)
                                            <span class="badge bg-danger ms-1">Overdue</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="text-end">
                                    <div class="fs-5 fw-bold text-primary">${{ number_format($invoice->amount, 2) }}</div>
                                </div>
                            </div>

                            <!-- Description -->
                            <div class="mb-3">
                                <div class="d-flex align-items-start">
                                    <i class="ri-file-text-line text-muted me-2 mt-1"></i>
                                    <div class="small text-muted">{{ $invoice->description }}</div>
                                </div>
                            </div>

                            <!-- Dates -->
                            <div class="row mb-3">
                                <div class="col-6">
                                    <div class="small text-muted mb-1">Invoice Date</div>
                                    <div class="fw-medium">{{ $invoice->invoice_date->format('M d, Y') }}</div>
                                </div>
                                <div class="col-6">
                                    <div class="small text-muted mb-1">Due Date</div>
                                    <div class="fw-medium {{ $invoice->is_overdue ? 'text-danger' : '' }}">
                                        {{ $invoice->due_date->format('M d, Y') }}
                                    </div>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="d-flex justify-content-between gap-2">
                                <a href="{{ route('billing.invoices.show', $invoice) }}" 
                                   class="btn btn-primary btn-sm flex-fill">
                                    <i class="ri-eye-line me-1"></i> View
                                </a>
                                <a href="{{ route('billing.invoices.download', $invoice) }}" 
                                   class="btn btn-outline-secondary btn-sm flex-fill">
                                    <i class="ri-download-line me-1"></i> Download
                                </a>
                                @if($invoice->status === 'pending')
                                    <a href="{{ route('billing.pay', $invoice) }}" 
                                       class="btn btn-success btn-sm flex-fill">
                                        <i class="ri-money-dollar-circle-line me-1"></i> Pay
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                
                <!-- Bulk Actions -->
                <div class="border-top p-3 d-none" id="bulkActions">
                    <div class="d-flex flex-column flex-sm-row align-items-center justify-content-between gap-2">
                        <div>
                            <span class="fw-semibold" id="selectedCount">0</span> invoices selected
                        </div>
                        <div class="btn-group w-100 w-sm-auto">
                            <button class="btn btn-outline-danger btn-sm" id="bulkDelete" disabled>
                                <i class="ri-delete-bin-line me-1"></i> Delete
                            </button>
                            <button class="btn btn-outline-primary btn-sm" id="bulkDownload" disabled>
                                <i class="ri-download-line me-1"></i> Download
                            </button>
                        </div>
                    </div>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="ri-bill-line display-1 text-muted"></i>
                    <h5 class="mt-3">No invoices found</h5>
                    <p class="text-muted">You don't have any invoices yet.</p>
                </div>
            @endif
        </div>
        
        <!-- Pagination -->
        @if($invoices->hasPages())
            <div class="card-footer bg-white border-top">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
                    <div class="text-muted small text-center text-md-start">
                        Showing {{ $invoices->firstItem() }} to {{ $invoices->lastItem() }} of {{ $invoices->total() }} invoices
                    </div>
                    <div class="w-100 w-md-auto">
                        {{ $invoices->onEachSide(1)->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<style>
.invoices-page .table td, 
.invoices-page .table th {
    vertical-align: middle;
}

.invoices-page .badge {
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
}

.invoices-page .btn-group-sm .btn {
    padding: 0.25rem 0.5rem;
}

.invoices-page .text-truncate {
    max-width: 200px;
    display: inline-block;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

/* Mobile responsive adjustments */
@media (max-width: 767.98px) {
    .invoices-page .card-body {
        padding: 1rem !important;
    }
    
    .invoices-page .h3 {
        font-size: 1.5rem;
    }
    
    .invoices-page .fs-5 {
        font-size: 1.25rem !important;
    }
    
    .invoices-page .btn {
        padding: 0.375rem 0.75rem;
        font-size: 0.875rem;
    }
    
    .invoices-page .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }
}

@media (max-width: 575.98px) {
    .invoices-page .display-1 {
        font-size: 3rem !important;
    }
    
    .invoices-page .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }
    
    .invoices-page .form-control-sm,
    .invoices-page .form-select-sm {
        font-size: 0.875rem;
        padding: 0.375rem 0.75rem;
    }
}

/* Card styling for mobile */
.card.border-bottom {
    border-bottom-color: #e9ecef !important;
}

.card.border-bottom:last-child {
    border-bottom: none !important;
}

/* Bulk actions responsive */
@media (max-width: 575.98px) {
    #bulkActions .btn-group {
        width: 100%;
    }
    
    #bulkActions .btn {
        flex: 1;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Select All checkbox
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.invoice-checkbox');
    const bulkActions = document.getElementById('bulkActions');
    const selectedCount = document.getElementById('selectedCount');
    const bulkDelete = document.getElementById('bulkDelete');
    const bulkDownload = document.getElementById('bulkDownload');
    
    // Toggle select all
    if (selectAll) {
        selectAll.addEventListener('change', function() {
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            updateBulkActions();
        });
    }
    
    // Update bulk actions when individual checkboxes change
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateBulkActions);
    });
    
    // Update bulk actions UI
    function updateBulkActions() {
        const selected = Array.from(checkboxes).filter(cb => cb.checked);
        const count = selected.length;
        
        if (count > 0) {
            bulkActions.classList.remove('d-none');
            selectedCount.textContent = count;
            bulkDelete.disabled = false;
            bulkDownload.disabled = false;
        } else {
            bulkActions.classList.add('d-none');
            bulkDelete.disabled = true;
            bulkDownload.disabled = true;
        }
        
        // Update select all checkbox state
        if (selectAll) {
            selectAll.checked = count === checkboxes.length;
            selectAll.indeterminate = count > 0 && count < checkboxes.length;
        }
    }
    
    // Bulk delete action
    if (bulkDelete) {
        bulkDelete.addEventListener('click', function() {
            const selected = Array.from(checkboxes)
                .filter(cb => cb.checked)
                .map(cb => cb.value);
            
            if (selected.length > 0 && confirm(`Delete ${selected.length} selected invoice(s)?`)) {
                // Implement bulk delete here
                console.log('Deleting invoices:', selected);
                alert('Bulk delete would be implemented here with AJAX');
            }
        });
    }
    
    // Bulk download action
    if (bulkDownload) {
        bulkDownload.addEventListener('click', function() {
            const selected = Array.from(checkboxes)
                .filter(cb => cb.checked)
                .map(cb => cb.value);
            
            if (selected.length > 0) {
                alert(`Bulk download for ${selected.length} invoices would be implemented here`);
            }
        });
    }
    
    // Filter functionality
    const applyFilters = document.getElementById('applyFilters');
    if (applyFilters) {
        applyFilters.addEventListener('click', function() {
            const status = document.getElementById('statusFilter').value;
            const dateFrom = document.getElementById('dateFrom').value;
            const dateTo = document.getElementById('dateTo').value;
            
            // Build filter URL
            let url = new URL(window.location.href);
            let params = new URLSearchParams(url.search);
            
            if (status) params.set('status', status);
            if (dateFrom) params.set('date_from', dateFrom);
            if (dateTo) params.set('date_to', dateTo);
            
            // Reload page with filters
            window.location.href = `${url.pathname}?${params.toString()}`;
        });
    }
    
    // Auto-apply filters on mobile for better UX
    if (window.innerWidth < 768) {
        const statusFilter = document.getElementById('statusFilter');
        const dateFrom = document.getElementById('dateFrom');
        const dateTo = document.getElementById('dateTo');
        
        if (statusFilter) {
            statusFilter.addEventListener('change', function() {
                if (this.value) {
                    applyFilters.click();
                }
            });
        }
    }

    // Add refresh button functionality
    const refreshBtn = document.getElementById('refreshBtn');
    if (refreshBtn) {
        refreshBtn.addEventListener('click', function() {
            // Add loading state
            const icon = this.querySelector('i');
            icon.classList.add('ri-loader-4-line');
            icon.classList.remove('ri-refresh-line');
            icon.style.animation = 'spin 1s linear infinite';
            this.disabled = true;
            
            // Reload the page with current filters
            setTimeout(() => {
                location.reload();
            }, 300);
        });
    }

    // Auto-refresh every 30 seconds to catch status updates from admin
    setInterval(() => {
        // Only auto-refresh if the page has been idle for a bit
        fetch(window.location.href, { 
            method: 'HEAD',
            cache: 'no-cache'
        }).then(response => {
            // Check if data might have changed by comparing timestamps
            // For now, we'll do a soft refresh (fetch without full page reload)
            if (document.hidden === false) {
                // Page is visible, could add visual indicator that data was refreshed
                console.log('Invoice data auto-checked at', new Date().toLocaleTimeString());
            }
        }).catch(err => {
            console.error('Auto-refresh check failed:', err);
        });
    }, 30000); // Check every 30 seconds

    // Add CSS animation for spinning refresh icon
    const style = document.createElement('style');
    style.textContent = `
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
    `;
    document.head.appendChild(style);
});
</script>
@endsection