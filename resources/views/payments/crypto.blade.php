@extends('layouts.app')

@section('title', 'Make Payment')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Make Payment with Crypto</h4>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <h5>Invoice Details</h5>
                        <p><strong>Invoice #:</strong> {{ $invoice->invoice_number }}</p>
                        <p><strong>Amount Due:</strong> ${{ number_format($invoice->amount, 2) }}</p>
                        <p><strong>Due Date:</strong> {{ $invoice->due_date->format('M d, Y') }}</p>
                    </div>
                    
                    <h5 class="mt-4 mb-3">Select Payment Method</h5>
                    
                    <div class="accordion" id="cryptoAccordion">
                        <!-- Bitcoin -->
                        @if(isset($cryptoWallets['BTC']) && $cryptoWallets['BTC']->count() > 0)
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#bitcoinCollapse">
                                    <i class="ri-bit-coin-line text-warning me-2"></i> Pay with Bitcoin (BTC)
                                </button>
                            </h2>
                            <div id="bitcoinCollapse" class="accordion-collapse collapse show" data-bs-parent="#cryptoAccordion">
                                <div class="accordion-body">
                                    <div class="alert alert-warning">
                                        <i class="ri-alert-line"></i> 
                                        <strong>Important:</strong> Send exact amount only to the address below
                                    </div>
                                    
                                    <h6>Available Bitcoin Wallets:</h6>
                                    @foreach($cryptoWallets['BTC'] as $wallet)
                                    <div class="card mb-3">
                                        <div class="card-body">
                                            <h6>{{ $wallet->label }}</h6>
                                            <div class="input-group">
                                                <input type="text" class="form-control" 
                                                       value="{{ $wallet->address }}" readonly 
                                                       id="btcAddress{{ $wallet->id }}">
                                                <button class="btn btn-outline-secondary copy-btn" 
                                                        type="button" 
                                                        data-target="#btcAddress{{ $wallet->id }}">
                                                    <i class="ri-file-copy-line"></i>
                                                </button>
                                            </div>
                                            @if($wallet->notes)
                                            <p class="text-muted small mt-2">{{ $wallet->notes }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    @endforeach
                                    
                                    <form action="{{ route('payments.crypto.submit') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="invoice_id" value="{{ $invoice->id }}">
                                        <input type="hidden" name="crypto_type" value="BTC">
                                        
                                        <div class="mb-3">
                                            <label class="form-label">Select Wallet</label>
                                            <select name="wallet_address" class="form-control" required>
                                                @foreach($cryptoWallets['BTC'] as $wallet)
                                                <option value="{{ $wallet->address }}">{{ $wallet->label }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label class="form-label">Transaction Hash/ID</label>
                                            <input type="text" name="transaction_hash" class="form-control" 
                                                   placeholder="Enter your transaction hash" required>
                                        </div>
                                        
                                        <button type="submit" class="btn btn-primary">
                                            <i class="ri-send-plane-line"></i> Submit Payment
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        <!-- USDT (ERC20) -->
                        @if(isset($cryptoWallets['USDT_ERC20']) && $cryptoWallets['USDT_ERC20']->count() > 0)
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#usdtErc20Collapse">
                                    <i class="ri-currency-line text-info me-2"></i> Pay with USDT (ERC20)
                                </button>
                            </h2>
                            <div id="usdtErc20Collapse" class="accordion-collapse collapse" data-bs-parent="#cryptoAccordion">
                                <div class="accordion-body">
                                    <!-- Similar structure as Bitcoin -->
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        <!-- USDT (TRC20) -->
                        @if(isset($cryptoWallets['USDT_TRC20']) && $cryptoWallets['USDT_TRC20']->count() > 0)
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#usdtTrc20Collapse">
                                    <i class="ri-currency-line text-success me-2"></i> Pay with USDT (TRC20)
                                </button>
                            </h2>
                            <div id="usdtTrc20Collapse" class="accordion-collapse collapse" data-bs-parent="#cryptoAccordion">
                                <div class="accordion-body">
                                    <!-- Similar structure as Bitcoin -->
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Copy address to clipboard
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
</script>
@endpush