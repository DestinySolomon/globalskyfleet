@extends('layouts.dashboard')

@section('title', 'Billing & Invoices - GlobalSkyFleet')

@section('page-title', 'Billing & Invoices')

@section('content')
<div class="billing-dashboard">
    <!-- Stats Overview -->
    <div class="stats-grid mb-4">
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #3b82f6, #1e40af);">
                <i class="ri-bill-line text-white"></i>
            </div>
            <div class="stat-value">{{ $stats['total_invoices'] }}</div>
            <div class="stat-label">Total Invoices</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
                <i class="ri-time-line text-white"></i>
            </div>
            <div class="stat-value">{{ $stats['pending_invoices'] }}</div>
            <div class="stat-label">Pending Invoices</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #ef4444, #dc2626);">
                <i class="ri-alert-line text-white"></i>
            </div>
            <div class="stat-value">{{ $stats['overdue_invoices'] }}</div>
            <div class="stat-label">Overdue Invoices</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #10b981, #059669);">
                <i class="ri-wallet-3-line text-white"></i>
            </div>
            <div class="stat-value">${{ number_format($stats['balance_due'], 2) }}</div>
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
                        <a href="{{ route('billing.invoices') }}" class="btn btn-primary">
                            <i class="ri-list-check-2"></i> View All Invoices
                        </a>
                        <a href="{{ route('billing.payments') }}" class="btn btn-outline-primary">
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
                    <a href="{{ route('contact') }}" class="btn btn-outline-secondary">
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
            @if($recentInvoices->count() > 0)
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
                            @foreach($recentInvoices as $invoice)
                                <tr>
                                    <td class="fw-semibold">{{ $invoice->invoice_number }}</td>
                                    <td>{{ $invoice->invoice_date->format('M d, Y') }}</td>
                                    <td class="{{ $invoice->is_overdue ? 'text-danger' : '' }}">
                                        {{ $invoice->due_date->format('M d, Y') }}
                                        @if($invoice->is_overdue)
                                            <span class="badge bg-danger ms-1">Overdue</span>
                                        @endif
                                    </td>
                                    <td class="fw-semibold">${{ number_format($invoice->amount, 2) }}</td>
                                    <td>
                                        <span class="badge {{ $invoice->status === 'paid' ? 'bg-success' : ($invoice->status === 'pending' ? 'bg-warning' : 'bg-secondary') }}">
                                            {{ ucfirst($invoice->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('billing.invoices.show', $invoice) }}" class="btn btn-outline-primary">
                                                <i class="ri-eye-line"></i>
                                            </a>
                                            @if($invoice->status === 'pending')
                                                <a href="{{ route('billing.pay', $invoice) }}" class="btn btn-outline-success">
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
            @else
                <div class="text-center py-5">
                    <i class="ri-bill-line display-1 text-muted"></i>
                    <p class="mt-3 text-muted">No invoices found.</p>
                </div>
            @endif
        </div>
        @if($recentInvoices->count() > 0)
            <div class="card-footer bg-white border-top">
                <a href="{{ route('billing.invoices') }}" class="btn btn-link">
                    View All Invoices <i class="ri-arrow-right-line"></i>
                </a>
            </div>
        @endif
    </div>

    <!-- Recent Payments -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom">
            <h5 class="mb-0">Recent Payments</h5>
        </div>
        <div class="card-body p-0">
            @if($recentPayments->count() > 0)
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
                            @foreach($recentPayments as $payment)
                                <tr>
                                    <td>{{ $payment->created_at->format('M d, Y') }}</td>
                                    <td class="fw-semibold">{{ $payment->invoice->invoice_number ?? 'N/A' }}</td>
                                    <td>
                                        <span class="badge bg-info">
                                            {{ str_replace('_', ' ', $payment->crypto_type) }}
                                        </span>
                                    </td>
                                    <td class="fw-semibold">
                                        @if($payment->crypto_type === 'BTC')
                                            {{ $payment->crypto_amount ? number_format($payment->crypto_amount, 8) : 'N/A' }} BTC
                                        @else
                                            {{ $payment->usdt_amount ? number_format($payment->usdt_amount, 2) : 'N/A' }} USDT
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge {{ $payment->status === 'completed' ? 'bg-success' : ($payment->status === 'pending' ? 'bg-warning' : 'bg-secondary') }}">
                                            {{ ucfirst($payment->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-outline-secondary">
                                            <i class="ri-information-line"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="ri-history-line display-1 text-muted"></i>
                    <p class="mt-3 text-muted">No payment history yet.</p>
                </div>
            @endif
        </div>
        @if($recentPayments->count() > 0)
            <div class="card-footer bg-white border-top">
                <a href="{{ route('billing.payments') }}" class="btn btn-link">
                    View All Payments <i class="ri-arrow-right-line"></i>
                </a>
            </div>
        @endif
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
@endsection