<div class="payment-details-modal">
    <div class="row g-3">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-light border-bottom">
                    <h6 class="mb-0">Payment Information</h6>
                </div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-5 text-muted">Invoice:</dt>
                        <dd class="col-sm-7">
                            @if($payment->invoice)
                                <a href="{{ route('billing.invoices.show', $payment->invoice) }}" 
                                   class="text-decoration-none fw-semibold">
                                    {{ $payment->invoice->invoice_number }}
                                </a>
                            @else
                                <span class="text-muted">N/A</span>
                            @endif
                        </dd>
                        
                        <dt class="col-sm-5 text-muted">Payment Method:</dt>
                        <dd class="col-sm-7">
                            <span class="badge bg-info">
                                {{ str_replace('_', ' ', $payment->crypto_type) }}
                            </span>
                        </dd>
                        
                        <dt class="col-sm-5 text-muted">Amount:</dt>
                        <dd class="col-sm-7">
                            <div class="fw-semibold">
                                @if($payment->crypto_type === 'BTC')
                                    {{ number_format($payment->crypto_amount ?? 0, 8) }} BTC
                                @else
                                    {{ number_format($payment->usdt_amount ?? 0, 2) }} USDT
                                @endif
                            </div>
                            @if($payment->exchange_rate)
                                <div class="small text-muted">
                                    ≈ ${{ number_format(
                                        $payment->crypto_type === 'BTC' 
                                            ? ($payment->crypto_amount ?? 0) * $payment->exchange_rate 
                                            : ($payment->usdt_amount ?? 0) * $payment->exchange_rate, 
                                        2
                                    ) }}
                                </div>
                            @endif
                        </dd>
                        
                        <dt class="col-sm-5 text-muted">Exchange Rate:</dt>
                        <dd class="col-sm-7">
                            @if($payment->exchange_rate)
                                1 {{ $payment->crypto_type === 'BTC' ? 'BTC' : 'USDT' }} = ${{ number_format($payment->exchange_rate, 2) }}
                            @else
                                <span class="text-muted">N/A</span>
                            @endif
                        </dd>
                    </dl>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-light border-bottom">
                    <h6 class="mb-0">Transaction Details</h6>
                </div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-5 text-muted">Transaction ID:</dt>
                        <dd class="col-sm-7">
                            <div class="d-flex align-items-center">
                                <code class="small text-truncate" style="max-width: 150px;" 
                                      title="{{ $payment->transaction_id }}">
                                    {{ $payment->transaction_id }}
                                </code>
                                <button class="btn btn-sm btn-outline-secondary ms-2" 
                                        onclick="copyToClipboard('{{ $payment->transaction_id }}', this)">
                                    <i class="ri-file-copy-line"></i>
                                </button>
                            </div>
                        </dd>
                        
                        <dt class="col-sm-5 text-muted">Payment Address:</dt>
                        <dd class="col-sm-7">
                            <div class="d-flex align-items-center">
                                <code class="small text-truncate" style="max-width: 150px;" 
                                      title="{{ $payment->payment_address }}">
                                    {{ $payment->payment_address }}
                                </code>
                                <button class="btn btn-sm btn-outline-secondary ms-2" 
                                        onclick="copyToClipboard('{{ $payment->payment_address }}', this)">
                                    <i class="ri-file-copy-line"></i>
                                </button>
                            </div>
                        </dd>
                        
                        <dt class="col-sm-5 text-muted">Status:</dt>
                        <dd class="col-sm-7">
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
                        </dd>
                        
                        <dt class="col-sm-5 text-muted">Confirmations:</dt>
                        <dd class="col-sm-7">
                            {{ $payment->confirmations ?? 0 }}
                        </dd>
                        
                        <dt class="col-sm-5 text-muted">Created:</dt>
                        <dd class="col-sm-7">
                            {{ $payment->created_at->format('M d, Y - h:i A') }}
                        </dd>
                        
                        @if($payment->paid_at)
                            <dt class="col-sm-5 text-muted">Paid At:</dt>
                            <dd class="col-sm-7">
                                {{ $payment->paid_at->format('M d, Y - h:i A') }}
                            </dd>
                        @endif
                        
                        @if($payment->confirmed_at)
                            <dt class="col-sm-5 text-muted">Confirmed At:</dt>
                            <dd class="col-sm-7">
                                {{ $payment->confirmed_at->format('M d, Y - h:i A') }}
                            </dd>
                        @endif
                    </dl>
                </div>
            </div>
        </div>
    </div>
    
    @if($payment->payment_proof)
        <div class="row mt-3">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-light border-bottom">
                        <h6 class="mb-0">Payment Proof</h6>
                    </div>
                    <div class="card-body">
                        <div class="text-center">
                            <img src="{{ Storage::url($payment->payment_proof) }}" 
                                 alt="Payment Proof" 
                                 class="img-fluid rounded shadow-sm mb-3"
                                 style="max-height: 200px; max-width: 100%;">
                            <div class="mt-2">
                                <a href="{{ Storage::url($payment->payment_proof) }}" 
                                   target="_blank" 
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="ri-external-link-line"></i> View Full Image
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    
    @if($payment->admin_notes)
        <div class="row mt-3">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-light border-bottom">
                        <h6 class="mb-0">Admin Notes</h6>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info mb-0">
                            <i class="ri-information-line me-2"></i> {{ $payment->admin_notes }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<style>
.payment-details-modal .card {
    border-radius: 10px;
}

.payment-details-modal .card-header {
    background-color: #f8f9fa;
    font-weight: 600;
}

.payment-details-modal dl {
    margin-bottom: 0;
}

.payment-details-modal dt {
    font-weight: 500;
    color: #6c757d;
    padding: 0.25rem 0;
}

.payment-details-modal dd {
    padding: 0.25rem 0;
    margin-bottom: 0;
}

.payment-details-modal code {
    background-color: #f8f9fa;
    padding: 0.125rem 0.25rem;
    border-radius: 0.25rem;
    font-size: 0.875rem;
    font-family: 'SFMono-Regular', Consolas, 'Liberation Mono', Menlo, monospace;
}

.payment-details-modal .text-truncate {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.payment-details-modal .badge {
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
}
</style>

<script>
function copyToClipboard(text, button) {
    navigator.clipboard.writeText(text).then(() => {
        const originalHtml = button.innerHTML;
        button.innerHTML = '<i class="ri-check-line"></i>';
        button.classList.remove('btn-outline-secondary');
        button.classList.add('btn-success');
        
        setTimeout(() => {
            button.innerHTML = originalHtml;
            button.classList.remove('btn-success');
            button.classList.add('btn-outline-secondary');
        }, 2000);
    });
}
</script>