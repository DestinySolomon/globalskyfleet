@extends('layouts.dashboard')

@section('title', 'Payment History - GlobalSkyFleet')

@section('page-title', 'Payment History')

@section('content')
<div class="payments-history-page">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-2">Payment History</h1>
            <p class="text-muted mb-0">Track all your cryptocurrency payments</p>
        </div>
        <div>
            <a href="{{ route('billing.index') }}" class="btn btn-outline-primary">
                <i class="ri-arrow-left-line"></i> Back to Billing
            </a>
        </div>
    </div>

    <!-- Stats Bar -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-0 bg-primary bg-opacity-10">
                <div class="card-body py-3">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="ri-history-line fs-4 text-primary"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0">Total Payments</h6>
                            <p class="fs-5 fw-bold mb-0">{{ $payments->total() }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 bg-success bg-opacity-10">
                <div class="card-body py-3">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="ri-check-double-line fs-4 text-success"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0">Completed</h6>
                            <p class="fs-5 fw-bold mb-0">{{ $payments->where('status', 'completed')->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 bg-warning bg-opacity-10">
                <div class="card-body py-3">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="ri-time-line fs-4 text-warning"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0">Pending</h6>
                            <p class="fs-5 fw-bold mb-0">{{ $payments->where('status', 'pending')->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 bg-info bg-opacity-10">
                <div class="card-body py-3">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="ri-exchange-dollar-line fs-4 text-info"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0">Total Volume</h6>
                            <p class="fs-5 fw-bold mb-0">
                                @php
                                    $total = 0;
                                    foreach($payments as $payment) {
                                        if($payment->crypto_type === 'BTC') {
                                            $total += $payment->crypto_amount * ($payment->exchange_rate ?? 45000);
                                        } else {
                                            $total += $payment->usdt_amount * ($payment->exchange_rate ?? 1);
                                        }
                                    }
                                @endphp
                                ${{ number_format($total, 2) }}
                            </p>
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
                <div class="col-md-3">
                    <label class="form-label">Status</label>
                    <select class="form-select" id="statusFilter">
                        <option value="">All Statuses</option>
                        <option value="completed">Completed</option>
                        <option value="pending">Pending</option>
                        <option value="confirmed">Confirmed</option>
                        <option value="failed">Failed</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Payment Method</label>
                    <select class="form-select" id="methodFilter">
                        <option value="">All Methods</option>
                        <option value="BTC">Bitcoin (BTC)</option>
                        <option value="USDT_ERC20">USDT (ERC-20)</option>
                        <option value="USDT_TRC20">USDT (TRC-20)</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Date From</label>
                    <input type="date" class="form-control" id="dateFrom">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Date To</label>
                    <input type="date" class="form-control" id="dateTo">
                </div>
                <div class="col-md-12">
                    <div class="d-flex justify-content-end gap-2">
                        <button class="btn btn-outline-secondary" id="resetFilters">
                            <i class="ri-refresh-line"></i> Reset
                        </button>
                        <button class="btn btn-primary" id="applyFilters">
                            <i class="ri-filter-line"></i> Apply Filters
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Payments Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
            <h5 class="mb-0">All Payments</h5>
            <div>
                <button class="btn btn-sm btn-outline-primary" id="exportPayments">
                    <i class="ri-download-line"></i> Export
                </button>
            </div>
        </div>
        <div class="card-body p-0">
            @if($payments->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Invoice</th>
                                <th>Method</th>
                                <th>Amount</th>
                                <th>Transaction ID</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($payments as $payment)
                                <tr>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="fw-semibold">{{ $payment->created_at->format('M d, Y') }}</span>
                                            <small class="text-muted">{{ $payment->created_at->format('h:i A') }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        @if($payment->invoice)
                                            <a href="{{ route('billing.invoices.show', $payment->invoice) }}" 
                                               class="text-decoration-none">
                                                {{ $payment->invoice->invoice_number }}
                                            </a>
                                            <div class="small text-muted">
                                                ${{ number_format($payment->invoice->amount, 2) }}
                                            </div>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @php
                                                $icon = match($payment->crypto_type) {
                                                    'BTC' => 'ri-bit-coin-line text-warning',
                                                    'USDT_ERC20' => 'ri-coin-line text-info',
                                                    'USDT_TRC20' => 'ri-coin-line text-success',
                                                    default => 'ri-coin-line'
                                                };
                                            @endphp
                                            <i class="{{ $icon }} me-2"></i>
                                            <span>{{ str_replace('_', ' ', $payment->crypto_type) }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="fw-semibold">
                                            @if($payment->crypto_type === 'BTC')
                                                {{ number_format($payment->crypto_amount, 8) }} BTC
                                            @else
                                                {{ number_format($payment->usdt_amount, 2) }} USDT
                                            @endif
                                        </div>
                                        <div class="small text-muted">
                                            @if($payment->exchange_rate)
                                                @php
                                                    $usdValue = $payment->crypto_type === 'BTC' 
                                                        ? $payment->crypto_amount * $payment->exchange_rate
                                                        : $payment->usdt_amount * $payment->exchange_rate;
                                                @endphp
                                                ≈ ${{ number_format($usdValue, 2) }}
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <code class="small" title="{{ $payment->transaction_id }}">
                                            {{ substr($payment->transaction_id, 0, 12) }}...
                                        </code>
                                        <div class="small text-muted">
                                            {{ $payment->confirmations ?? 0 }} confirmations
                                        </div>
                                    </td>
                                    <td>
                                        @php
                                            $badgeClass = match($payment->status) {
                                                'completed' => 'bg-success',
                                                'confirmed' => 'bg-primary',
                                                'pending' => 'bg-warning',
                                                'failed' => 'bg-danger',
                                                'expired' => 'bg-dark',
                                                default => 'bg-secondary'
                                            };
                                        @endphp
                                        <span class="badge {{ $badgeClass }}">
                                            {{ ucfirst($payment->status) }}
                                        </span>
                                        @if($payment->paid_at)
                                            <div class="small text-muted mt-1">
                                                Paid: {{ $payment->paid_at->format('M d') }}
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-outline-primary view-payment-btn" 
                                                    data-payment-id="{{ $payment->id }}"
                                                    title="View Details">
                                                <i class="ri-eye-line"></i>
                                            </button>
                                            @if($payment->status === 'pending')
                                                <a href="{{ route('billing.upload-proof', $payment) }}" 
                                                   class="btn btn-outline-warning" 
                                                   title="Upload Proof">
                                                    <i class="ri-upload-line"></i>
                                                </a>
                                            @endif
                                            @if($payment->payment_proof)
                                                <a href="{{ Storage::url($payment->payment_proof) }}" 
                                                   target="_blank" 
                                                   class="btn btn-outline-info" 
                                                   title="View Proof">
                                                    <i class="ri-image-line"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="ri-history-line display-1 text-muted"></i>
                    <h5 class="mt-3">No payments found</h5>
                    <p class="text-muted">You haven't made any payments yet.</p>
                    <a href="{{ route('billing.invoices') }}" class="btn btn-primary mt-2">
                        <i class="ri-bill-line"></i> View Invoices
                    </a>
                </div>
            @endif
        </div>
        
        <!-- Pagination -->
        @if($payments->hasPages())
            <div class="card-footer bg-white border-top">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="text-muted">
                        Showing {{ $payments->firstItem() }} to {{ $payments->lastItem() }} of {{ $payments->total() }} payments
                    </div>
                    <div>
                        {{ $payments->links() }}
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Payment Details Modal -->
<div class="modal fade" id="paymentDetailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Payment Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4" id="paymentDetailsContent">
                <!-- Content will be loaded via AJAX -->
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-3 text-muted">Loading payment details...</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<style>
.payments-history-page .table td, 
.payments-history-page .table th {
    vertical-align: middle;
}

.payments-history-page .badge {
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
}

.payments-history-page .btn-group-sm .btn {
    padding: 0.25rem 0.5rem;
}

.payments-history-page code {
    background-color: #f1f5f9;
    padding: 0.125rem 0.25rem;
    border-radius: 0.25rem;
    font-size: 0.875rem;
    font-family: monospace;
}

.payments-history-page .table tbody tr:hover {
    background-color: #f8fafc;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Filter functionality
    const applyFilters = document.getElementById('applyFilters');
    const resetFilters = document.getElementById('resetFilters');
    
    if (applyFilters) {
        applyFilters.addEventListener('click', function() {
            const status = document.getElementById('statusFilter').value;
            const method = document.getElementById('methodFilter').value;
            const dateFrom = document.getElementById('dateFrom').value;
            const dateTo = document.getElementById('dateTo').value;
            
            // Build filter URL
            let url = new URL(window.location.href);
            let params = new URLSearchParams(url.search);
            
            if (status) params.set('status', status);
            if (method) params.set('method', method);
            if (dateFrom) params.set('date_from', dateFrom);
            if (dateTo) params.set('date_to', dateTo);
            
            // Reload page with filters
            window.location.href = `${url.pathname}?${params.toString()}`;
        });
    }
    
    if (resetFilters) {
        resetFilters.addEventListener('click', function() {
            window.location.href = '{{ route("billing.payments") }}';
        });
    }
    
    // Export payments
    const exportPayments = document.getElementById('exportPayments');
    if (exportPayments) {
        exportPayments.addEventListener('click', function() {
            // Get current filters
            const status = document.getElementById('statusFilter').value;
            const method = document.getElementById('methodFilter').value;
            const dateFrom = document.getElementById('dateFrom').value;
            const dateTo = document.getElementById('dateTo').value;
            
            // Build export URL
            let url = new URL('{{ route("billing.payments") }}');
            let params = new URLSearchParams();
            
            params.set('export', 'csv');
            if (status) params.set('status', status);
            if (method) params.set('method', method);
            if (dateFrom) params.set('date_from', dateFrom);
            if (dateTo) params.set('date_to', dateTo);
            
            // Trigger download
            window.location.href = `${url.pathname}?${params.toString()}`;
        });
    }
    
    // View payment details
    const viewPaymentBtns = document.querySelectorAll('.view-payment-btn');
    const paymentDetailsModal = new bootstrap.Modal(document.getElementById('paymentDetailsModal'));
    
    viewPaymentBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const paymentId = this.dataset.paymentId;
            
            // Show loading
            document.getElementById('paymentDetailsContent').innerHTML = `
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            `;
            
         
        // Load payment details
           fetch(`/billing/payment/${paymentId}/details`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('paymentDetailsContent').innerHTML = data.html;
                    } else {
                        document.getElementById('paymentDetailsContent').innerHTML = `
                            <div class="alert alert-danger">
                                Failed to load payment details.
                            </div>
                        `;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('paymentDetailsContent').innerHTML = `
                        <div class="alert alert-danger">
                            Error loading payment details. Please try again.
                        </div>
                    `;
                });
            
            paymentDetailsModal.show();
        });
    });
    
    // Apply URL filters on page load
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('status')) {
        document.getElementById('statusFilter').value = urlParams.get('status');
    }
    if (urlParams.get('method')) {
        document.getElementById('methodFilter').value = urlParams.get('method');
    }
    if (urlParams.get('date_from')) {
        document.getElementById('dateFrom').value = urlParams.get('date_from');
    }
    if (urlParams.get('date_to')) {
        document.getElementById('dateTo').value = urlParams.get('date_to');
    }
});
</script>
@endsection