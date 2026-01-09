@extends('layouts.dashboard')

@section('title', 'Invoice ' . $invoice->invoice_number . ' - GlobalSkyFleet')

@section('page-title', 'Invoice Details')

@section('content')
<div class="invoice-detail-page">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-2">Invoice {{ $invoice->invoice_number }}</h1>
            <p class="text-muted mb-0">Issued on {{ $invoice->invoice_date->format('F d, Y') }}</p>
        </div>
        <div class="btn-group">
            <a href="{{ route('billing.invoices') }}" class="btn btn-outline-primary">
                <i class="ri-arrow-left-line"></i> Back to Invoices
            </a>
            <a href="{{ route('billing.invoices.download', $invoice) }}" class="btn btn-primary">
                <i class="ri-download-line"></i> Download
            </a>
        </div>
    </div>

    <!-- Invoice Status Alert -->
    @if($invoice->is_overdue)
        <div class="alert alert-danger border-0 shadow-sm mb-4">
            <div class="d-flex align-items-center">
                <i class="ri-alert-line fs-4 me-3"></i>
                <div>
                    <h5 class="alert-heading mb-1">Invoice Overdue</h5>
                    <p class="mb-0">This invoice was due on {{ $invoice->due_date->format('F d, Y') }} ({{ abs($invoice->days_until_due) }} days ago). Please make payment immediately.</p>
                </div>
            </div>
        </div>
    @elseif($invoice->status === 'pending')
        <div class="alert alert-warning border-0 shadow-sm mb-4">
            <div class="d-flex align-items-center">
                <i class="ri-time-line fs-4 me-3"></i>
                <div>
                    <h5 class="alert-heading mb-1">Payment Pending</h5>
                    <p class="mb-0">
                        This invoice is due on {{ $invoice->due_date->format('F d, Y') }} 
                        (in {{ $invoice->days_until_due }} days).
                    </p>
                </div>
            </div>
        </div>
    @elseif($invoice->status === 'paid')
        <div class="alert alert-success border-0 shadow-sm mb-4">
            <div class="d-flex align-items-center">
                <i class="ri-check-double-line fs-4 me-3"></i>
                <div>
                    <h5 class="alert-heading mb-1">Payment Received</h5>
                    <p class="mb-0">Thank you! This invoice was paid on {{ $invoice->updated_at->format('F d, Y') }}.</p>
                </div>
            </div>
        </div>
    @endif

    <div class="row">
        <!-- Left Column: Invoice Details -->
        <div class="col-lg-8">
            <!-- Invoice Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">Invoice Details</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-muted mb-2">Bill To:</h6>
                            <p class="mb-1 fw-semibold">{{ Auth::user()->name }}</p>
                            <p class="mb-1">{{ Auth::user()->email }}</p>
                            <p class="mb-0">{{ Auth::user()->phone ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <h6 class="text-muted mb-2">Invoice Information:</h6>
                            <p class="mb-1"><span class="text-muted">Invoice #:</span> {{ $invoice->invoice_number }}</p>
                            <p class="mb-1"><span class="text-muted">Issue Date:</span> {{ $invoice->invoice_date->format('F d, Y') }}</p>
                            <p class="mb-1"><span class="text-muted">Due Date:</span> {{ $invoice->due_date->format('F d, Y') }}</p>
                            <p class="mb-0">
                                <span class="text-muted">Status:</span> 
                                <span class="badge {{ $invoice->status === 'paid' ? 'bg-success' : ($invoice->status === 'pending' ? 'bg-warning' : 'bg-secondary') }}">
                                    {{ ucfirst($invoice->status) }}
                                </span>
                            </p>
                        </div>
                    </div>

                    <!-- Invoice Items -->
                    <div class="table-responsive mb-4">
                        <table class="table table-bordered">
                            <thead class="bg-light">
                                <tr>
                                    <th width="60%">Description</th>
                                    <th width="20%" class="text-center">Quantity</th>
                                    <th width="20%" class="text-end">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($invoice->items && is_array($invoice->items))
                                    @foreach($invoice->items as $item)
                                        <tr>
                                            <td>{{ $item['description'] ?? 'Item' }}</td>
                                            <td class="text-center">1</td>
                                            <td class="text-end">${{ number_format($item['amount'] ?? 0, 2) }}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td>{{ $invoice->description }}</td>
                                        <td class="text-center">1</td>
                                        <td class="text-end">${{ number_format($invoice->amount, 2) }}</td>
                                    </tr>
                                @endif
                            </tbody>
                            <tfoot class="bg-light">
                                <tr>
                                    <td colspan="2" class="text-end fw-bold">Subtotal:</td>
                                    <td class="text-end fw-bold">${{ number_format($invoice->amount, 2) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="text-end fw-bold">Tax (0%):</td>
                                    <td class="text-end fw-bold">$0.00</td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="text-end fw-bold">Total:</td>
                                    <td class="text-end fw-bold fs-5 text-primary">${{ number_format($invoice->amount, 2) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <!-- Notes -->
                    @if($invoice->notes)
                        <div class="border rounded p-3 bg-light">
                            <h6 class="text-muted mb-2">Notes:</h6>
                            <p class="mb-0">{{ $invoice->notes }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Payment History -->
            @if($invoice->payment)
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="mb-0">Payment Details</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p class="mb-2"><span class="text-muted">Payment Method:</span> 
                                    <span class="badge bg-info">
                                        {{ str_replace('_', ' ', $invoice->payment->crypto_type) }}
                                    </span>
                                </p>
                                <p class="mb-2"><span class="text-muted">Transaction ID:</span> 
                                    <code>{{ $invoice->payment->transaction_id }}</code>
                                </p>
                                <p class="mb-2"><span class="text-muted">Payment Address:</span> 
                                    <code class="small">{{ $invoice->payment->payment_address }}</code>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-2"><span class="text-muted">Amount Paid:</span> 
                                    <strong>
                                        @if($invoice->payment->crypto_type === 'BTC')
                                            {{ number_format($invoice->payment->crypto_amount, 8) }} BTC
                                        @else
                                            {{ number_format($invoice->payment->usdt_amount, 2) }} USDT
                                        @endif
                                    </strong>
                                </p>
                                <p class="mb-2"><span class="text-muted">Exchange Rate:</span> 
                                    1 {{ $invoice->payment->crypto_type === 'BTC' ? 'BTC' : 'USDT' }} = ${{ number_format($invoice->payment->exchange_rate, 2) }}
                                </p>
                                <p class="mb-0"><span class="text-muted">Payment Status:</span> 
                                    <span class="badge {{ $invoice->payment->status === 'completed' ? 'bg-success' : 'bg-warning' }}">
                                        {{ ucfirst($invoice->payment->status) }}
                                    </span>
                                </p>
                            </div>
                        </div>
                        @if($invoice->payment->payment_proof)
                            <div class="mt-3">
                                <h6 class="text-muted mb-2">Payment Proof:</h6>
                                <a href="{{ Storage::url($invoice->payment->payment_proof) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                    <i class="ri-image-line"></i> View Proof Image
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        <!-- Right Column: Actions & Summary -->
        <div class="col-lg-4">
            <!-- Invoice Summary -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">Invoice Summary</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Invoice Number:</span>
                        <span class="fw-semibold">{{ $invoice->invoice_number }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Issue Date:</span>
                        <span>{{ $invoice->invoice_date->format('M d, Y') }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Due Date:</span>
                        <span class="{{ $invoice->is_overdue ? 'text-danger fw-bold' : '' }}">
                            {{ $invoice->due_date->format('M d, Y') }}
                        </span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Status:</span>
                        <span class="badge {{ $invoice->status === 'paid' ? 'bg-success' : ($invoice->status === 'pending' ? 'bg-warning' : 'bg-secondary') }}">
                            {{ ucfirst($invoice->status) }}
                        </span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Amount Due:</span>
                        <span class="fs-4 fw-bold text-primary">${{ number_format($invoice->amount, 2) }}</span>
                    </div>
                    
                    @if($invoice->status === 'pending')
                        <a href="{{ route('billing.pay', $invoice) }}" class="btn btn-success w-100 mb-2">
                            <i class="ri-money-dollar-circle-line"></i> Pay Now
                        </a>
                    @endif
                    
                    <div class="d-grid gap-2">
                        <a href="{{ route('billing.invoices.download', $invoice) }}" class="btn btn-outline-primary">
                            <i class="ri-download-line"></i> Download Invoice
                        </a>
                        <button class="btn btn-outline-secondary" onclick="window.print()">
                            <i class="ri-printer-line"></i> Print Invoice
                        </button>
                    </div>
                </div>
            </div>

            <!-- Payment Instructions -->
            @if($invoice->status === 'pending')
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="mb-0">Payment Instructions</h5>
                    </div>
                    <div class="card-body">
                        <p class="small text-muted mb-3">
                            <i class="ri-information-line"></i> Please complete payment before the due date to avoid service interruption.
                        </p>
                        <ul class="list-unstyled small">
                            <li class="mb-2">
                                <i class="ri-checkbox-circle-line text-success me-2"></i>
                                Pay with Bitcoin (BTC) or USDT
                            </li>
                            <li class="mb-2">
                                <i class="ri-checkbox-circle-line text-success me-2"></i>
                                Payment verification within 24 hours
                            </li>
                            <li class="mb-2">
                                <i class="ri-checkbox-circle-line text-success me-2"></i>
                                Keep your transaction ID for reference
                            </li>
                        </ul>
                        <div class="text-center mt-3">
                            <a href="{{ route('contact') }}" class="btn btn-link btn-sm">
                                <i class="ri-question-line"></i> Need help with payment?
                            </a>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Timeline -->
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">Invoice Timeline</h5>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item completed">
                            <div class="timeline-marker"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Invoice Created</h6>
                                <p class="text-muted small mb-0">{{ $invoice->created_at->format('M d, Y - h:i A') }}</p>
                            </div>
                        </div>
                        
                        @if($invoice->status === 'paid' && $invoice->payment)
                            <div class="timeline-item completed">
                                <div class="timeline-marker"></div>
                                <div class="timeline-content">
                                    <h6 class="mb-1">Payment Submitted</h6>
                                    <p class="text-muted small mb-0">{{ $invoice->payment->created_at->format('M d, Y - h:i A') }}</p>
                                </div>
                            </div>
                            
                            <div class="timeline-item completed">
                                <div class="timeline-marker"></div>
                                <div class="timeline-content">
                                    <h6 class="mb-1">Payment Verified</h6>
                                    <p class="text-muted small mb-0">{{ $invoice->payment->verified_at?->format('M d, Y - h:i A') ?? 'Pending' }}</p>
                                </div>
                            </div>
                            
                            <div class="timeline-item completed">
                                <div class="timeline-marker"></div>
                                <div class="timeline-content">
                                    <h6 class="mb-1">Invoice Paid</h6>
                                    <p class="text-muted small mb-0">{{ $invoice->updated_at->format('M d, Y - h:i A') }}</p>
                                </div>
                            </div>
                        @else
                            <div class="timeline-item {{ $invoice->status === 'pending' ? 'active' : '' }}">
                                <div class="timeline-marker"></div>
                                <div class="timeline-content">
                                    <h6 class="mb-1">Awaiting Payment</h6>
                                    <p class="text-muted small mb-0">Due by {{ $invoice->due_date->format('M d, Y') }}</p>
                                </div>
                            </div>
                            
                            <div class="timeline-item">
                                <div class="timeline-marker"></div>
                                <div class="timeline-content">
                                    <h6 class="mb-1">Payment Verification</h6>
                                    <p class="text-muted small mb-0">Within 24 hours of payment</p>
                                </div>
                            </div>
                            
                            <div class="timeline-item">
                                <div class="timeline-marker"></div>
                                <div class="timeline-content">
                                    <h6 class="mb-1">Completed</h6>
                                    <p class="text-muted small mb-0">Invoice will be marked as paid</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.invoice-detail-page .table th {
    background-color: #f8f9fa;
    font-weight: 600;
}

.invoice-detail-page .timeline {
    position: relative;
    padding-left: 2rem;
}

.invoice-detail-page .timeline::before {
    content: '';
    position: absolute;
    left: 0.5rem;
    top: 0;
    bottom: 0;
    width: 2px;
    background-color: #e5e7eb;
}

.invoice-detail-page .timeline-item {
    position: relative;
    padding-bottom: 1.5rem;
}

.invoice-detail-page .timeline-item:last-child {
    padding-bottom: 0;
}

.invoice-detail-page .timeline-marker {
    position: absolute;
    left: -1.625rem;
    top: 0.25rem;
    width: 1rem;
    height: 1rem;
    border-radius: 50%;
    background-color: #e5e7eb;
    border: 3px solid white;
    box-shadow: 0 0 0 2px #e5e7eb;
}

.invoice-detail-page .timeline-item.completed .timeline-marker {
    background-color: #10b981;
    box-shadow: 0 0 0 2px #10b981;
}

.invoice-detail-page .timeline-item.active .timeline-marker {
    background-color: #3b82f6;
    box-shadow: 0 0 0 2px #3b82f6;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { box-shadow: 0 0 0 0 rgba(59, 130, 246, 0.7); }
    70% { box-shadow: 0 0 0 10px rgba(59, 130, 246, 0); }
    100% { box-shadow: 0 0 0 0 rgba(59, 130, 246, 0); }
}

.invoice-detail-page .timeline-content h6 {
    font-size: 0.875rem;
    margin-bottom: 0.25rem;
}

.invoice-detail-page .timeline-content p {
    font-size: 0.75rem;
}

.invoice-detail-page code {
    background-color: #f1f5f9;
    padding: 0.125rem 0.25rem;
    border-radius: 0.25rem;
    font-size: 0.875rem;
}

@media print {
    .invoice-detail-page .btn,
    .invoice-detail-page .alert,
    .invoice-detail-page .timeline {
        display: none !important;
    }
}
</style>
@endsection