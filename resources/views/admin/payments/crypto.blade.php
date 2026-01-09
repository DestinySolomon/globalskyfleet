@extends('layouts.admin')

@section('page-title', 'Crypto Payments Management')

@section('content')
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
                            <h3 class="mb-1">{{ $pendingCount ?? 0 }}</h3>
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
                            <h3 class="mb-1">{{ $confirmedCount ?? 0 }}</h3>
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
                            <h3 class="mb-1">{{ $completedCount ?? 0 }}</h3>
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
                            <h3 class="mb-1">{{ $failedCount ?? 0 }}</h3>
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
                    <form action="{{ route('admin.payments.crypto') }}" method="GET" class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="">All Statuses</option>
                                @foreach($statuses as $key => $label)
                                    <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="col-md-3">
                            <label class="form-label">Crypto Type</label>
                            <select name="crypto_type" class="form-select">
                                <option value="">All Types</option>
                                @foreach($cryptoTypes as $key => $label)
                                    <option value="{{ $key }}" {{ request('crypto_type') == $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="col-md-3">
                            <label class="form-label">Date From</label>
                            <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                        </div>
                        
                        <div class="col-md-3">
                            <label class="form-label">Date To</label>
                            <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                        </div>
                        
                        <div class="col-md-12">
                            <label class="form-label">Search (Transaction ID, Address, User Email)</label>
                            <input type="text" name="search" class="form-control" placeholder="Search..." value="{{ request('search') }}">
                        </div>
                        
                        <div class="col-md-12">
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="ri-search-line me-2"></i>Filter
                                </button>
                                <a href="{{ route('admin.payments.crypto') }}" class="btn btn-secondary">
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
                        Total: {{ $payments->total() }} payments
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
                                @forelse($payments as $payment)
                                <tr>
                                    <td>
                                        <a href="{{ route('admin.payments.crypto.show', $payment->id) }}" class="text-decoration-none">
                                            <div class="fw-semibold text-primary">#{{ $payment->id }}</div>
                                        </a>
                                        @if($payment->invoice)
                                            <small class="text-muted">Invoice: {{ $payment->invoice->invoice_number }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        @if($payment->user)
                                            <div class="d-flex align-items-center">
                                                <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center me-2" 
                                                     style="width: 32px; height: 32px; font-size: 12px; font-weight: bold;">
                                                    {{ substr($payment->user->name, 0, 2) }}
                                                </div>
                                                <div>
                                                    <div class="fw-semibold">{{ $payment->user->name }}</div>
                                                    <small class="text-muted">{{ $payment->user->email }}</small>
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-muted">User not found</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($payment->crypto_type == 'BTC')
                                                <i class="ri-bit-coin-line text-warning fs-5 me-2"></i>
                                            @else
                                                <i class="ri-currency-line text-success fs-5 me-2"></i>
                                            @endif
                                            <div>
                                                <div class="fw-semibold">{{ $payment->crypto_type }}</div>
                                                <small class="text-muted">
                                                    @if($payment->crypto_type == 'BTC')
                                                        {{ $payment->crypto_amount }} BTC
                                                    @else
                                                        {{ $payment->usdt_amount }} USDT
                                                    @endif
                                                </small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($payment->exchange_rate)
                                            <div class="fw-semibold">${{ number_format($payment->usdt_amount, 2) }}</div>
                                            <small class="text-muted">
                                                Rate: {{ number_format($payment->exchange_rate, 8) }}
                                            </small>
                                        @else
                                            <span class="text-muted">Calculating...</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="text-truncate" style="max-width: 150px;" 
                                             title="{{ $payment->payment_address }}">
                                            <code>{{ $payment->payment_address }}</code>
                                        </div>
                                        @if($payment->confirmations > 0)
                                            <small class="text-success">
                                                {{ $payment->confirmations }} confirmations
                                            </small>
                                        @endif
                                    </td>
                                    <td>
                                        @if($payment->transaction_id)
                                            <div class="text-truncate" style="max-width: 150px;"
                                                 title="{{ $payment->transaction_id }}">
                                                <code>{{ $payment->transaction_id }}</code>
                                            </div>
                                            @if($payment->paid_at)
                                                <small class="text-muted">
                                                    {{ $payment->paid_at->format('M d, H:i') }}
                                                </small>
                                            @endif
                                        @else
                                            <span class="text-muted">Not provided</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge {{ $payment->getStatusBadgeClass() }}">
                                            {{ ucfirst($payment->status) }}
                                        </span>
                                        @if($payment->isExpired())
                                            <small class="d-block text-danger">Expired</small>
                                        @endif
                                    </td>
                                    <td>
                                        <div>{{ $payment->created_at->format('M d, Y') }}</div>
                                        <small class="text-muted">{{ $payment->created_at->format('H:i') }}</small>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('admin.payments.crypto.show', $payment->id) }}" 
                                               class="btn btn-primary" title="View Details">
                                                <i class="ri-eye-line"></i>
                                            </a>
                                            @if($payment->status == 'pending' || $payment->status == 'processing')
                                                <button type="button" class="btn btn-success" 
                                                        onclick="updatePaymentStatus({{ $payment->id }}, 'confirmed')"
                                                        title="Confirm Payment">
                                                    <i class="ri-check-line"></i>
                                                </button>
                                                <button type="button" class="btn btn-danger"
                                                        onclick="updatePaymentStatus({{ $payment->id }}, 'failed')"
                                                        title="Reject Payment">
                                                    <i class="ri-close-line"></i>
                                                </button>
                                            @endif
                                            @if($payment->status == 'confirmed')
                                                <button type="button" class="btn btn-success"
                                                        onclick="updatePaymentStatus({{ $payment->id }}, 'completed')"
                                                        title="Mark as Completed">
                                                    <i class="ri-check-double-line"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center py-5">
                                        <i class="ri-inbox-line display-4 text-muted mb-3"></i>
                                        <p class="text-muted mb-0">No crypto payments found.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    @if($payments->hasPages())
                    <div class="mt-4">
                        {{ $payments->links() }}
                    </div>
                    @endif
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
                <form action="{{ route('admin.payments.crypto.export') }}" method="GET">
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
@endsection

@push('scripts')
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
@endpush

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