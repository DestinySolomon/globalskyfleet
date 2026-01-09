@extends('layouts.dashboard')

@section('title', 'Pay Invoice ' . $invoice->invoice_number . ' - GlobalSkyFleet')

@section('page-title', 'Pay Invoice')

@section('content')
<div class="payment-page">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-2">Pay Invoice {{ $invoice->invoice_number }}</h1>
            <p class="text-muted mb-0">Amount Due: <strong class="text-primary">${{ number_format($invoice->amount, 2) }}</strong></p>
        </div>
        <div>
            <a href="{{ route('billing.invoices.show', $invoice) }}" class="btn btn-outline-primary">
                <i class="ri-arrow-left-line"></i> Back to Invoice
            </a>
        </div>
    </div>

    <!-- Payment Steps -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="steps">
                <div class="step active">
                    <div class="step-number">1</div>
                    <div class="step-label">Choose Payment Method</div>
                </div>
                <div class="step">
                    <div class="step-number">2</div>
                    <div class="step-label">Send Crypto Payment</div>
                </div>
                <div class="step">
                    <div class="step-number">3</div>
                    <div class="step-label">Submit Proof</div>
                </div>
                <div class="step">
                    <div class="step-number">4</div>
                    <div class="step-label">Verification</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Left Column: Payment Methods -->
        <div class="col-lg-8">
            <!-- Payment Methods Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">Choose Payment Method</h5>
                </div>
                <div class="card-body">
                    <!-- Bitcoin Payment -->
                    @if($btcAddress)
                        <div class="payment-method-card mb-4" id="btcMethod">
                            <div class="form-check mb-0">
                                <input class="form-check-input" type="radio" name="payment_method" 
                                       id="btc" value="BTC" data-rate="{{ $btcRate ?? 0 }}" 
                                       data-amount="{{ $btcAmount ?? 0 }}">
                                <label class="form-check-label w-100" for="btc">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center">
                                            <div class="payment-icon bg-warning bg-opacity-10 text-warning">
                                                <i class="ri-bit-coin-line"></i>
                                            </div>
                                            <div class="ms-3">
                                                <h6 class="mb-1">Bitcoin (BTC)</h6>
                                                <p class="text-muted small mb-0">
                                                    Pay with Bitcoin. Fast and secure.
                                                  @if($btcRate)
                                                    <span class="text-success">1 BTC = ${{ $formattedBtcRate }}</span>
                                                   @endif
                                                </p>
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            @if($btcAmount)
                                                <div class="h5 mb-1">{{ number_format($btcAmount, 8) }} BTC</div>
                                                <small class="text-muted">≈ ${{ number_format($invoice->amount, 2) }}</small>
                                            @else
                                                <div class="text-danger small">Rate unavailable</div>
                                            @endif
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>
                    @endif

                    <!-- USDT ERC-20 Payment -->
                    @if($usdtErc20Address)
                        <div class="payment-method-card mb-4" id="usdtErc20Method">
                            <div class="form-check mb-0">
                                <input class="form-check-input" type="radio" name="payment_method" 
                                       id="usdt_erc20" value="USDT_ERC20" data-rate="{{ $usdtRate ?? 0 }}" 
                                       data-amount="{{ $usdtAmount ?? 0 }}">
                                <label class="form-check-label w-100" for="usdt_erc20">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center">
                                            <div class="payment-icon bg-info bg-opacity-10 text-info">
                                                <i class="ri-coin-line"></i>
                                            </div>
                                            <div class="ms-3">
                                                <h6 class="mb-1">USDT (ERC-20)</h6>
                                                <p class="text-muted small mb-0">
                                                    Tether on Ethereum network
                                                  
                                               @if($usdtRate)
                                              <span class="text-success">1 USDT = ${{ $formattedUsdtRate }}</span>
                                                @endif
                                                </p>
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            @if($usdtAmount)
                                                <div class="h5 mb-1">{{ number_format($usdtAmount, 2) }} USDT</div>
                                                <small class="text-muted">≈ ${{ number_format($invoice->amount, 2) }}</small>
                                            @else
                                                <div class="text-danger small">Rate unavailable</div>
                                            @endif
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>
                    @endif

                    <!-- USDT TRC-20 Payment -->
                    @if($usdtTrc20Address)
                        <div class="payment-method-card mb-4" id="usdtTrc20Method">
                            <div class="form-check mb-0">
                                <input class="form-check-input" type="radio" name="payment_method" 
                                       id="usdt_trc20" value="USDT_TRC20" data-rate="{{ $usdtRate ?? 0 }}" 
                                       data-amount="{{ $usdtAmount ?? 0 }}">
                                <label class="form-check-label w-100" for="usdt_trc20">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center">
                                            <div class="payment-icon bg-success bg-opacity-10 text-success">
                                                <i class="ri-coin-line"></i>
                                            </div>
                                            <div class="ms-3">
                                                <h6 class="mb-1">USDT (TRC-20)</h6>
                                                <p class="text-muted small mb-0">
                                                    Tether on Tron network. Lower fees.
                                                    @if($usdtRate)
                                                        <span class="text-success">1 USDT = ${{ number_format($usdtRate, 2) }}</span>
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            @if($usdtAmount)
                                                <div class="h5 mb-1">{{ number_format($usdtAmount, 2) }} USDT</div>
                                                <small class="text-muted">≈ ${{ number_format($invoice->amount, 2) }}</small>
                                            @else
                                                <div class="text-danger small">Rate unavailable</div>
                                            @endif
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>
                    @endif

                    @if(!$btcAddress && !$usdtErc20Address && !$usdtTrc20Address)
                        <div class="alert alert-warning">
                            <i class="ri-alert-line"></i> No payment methods are currently available. Please contact support.
                        </div>
                    @endif
                </div>
            </div>

            <!-- Payment Instructions -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">Payment Instructions</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <h6><i class="ri-information-line"></i> Important:</h6>
                        <ul class="mb-0">
                            <li>Send the <strong>exact amount</strong> shown above</li>
                            <li>Include sufficient network fees for timely confirmation</li>
                            <li>Save your transaction ID (TXID) for verification</li>
                            <li>Payments are verified within 24 hours</li>
                        </ul>
                    </div>

                    <!-- Selected Method Details (Hidden by default) -->
                    <div id="paymentDetails" class="d-none">
                        <hr>
                        <h6 class="mb-3">Payment Details for <span id="selectedMethod"></span>:</h6>
                        
                        <!-- Wallet Address -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Send To Address:</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="walletAddress" readonly>
                                <button class="btn btn-outline-secondary" type="button" onclick="copyAddress()">
                                    <i class="ri-file-copy-line"></i> Copy
                                </button>
                            </div>
                            <small class="text-muted">Send <span id="cryptoAmount"></span> to this address</small>
                        </div>

                        <!-- QR Code -->
                        <div class="mb-4 text-center">
                            <div class="qr-code-container bg-light p-3 rounded d-inline-block">
                                <div id="qrCode"></div>
                                <small class="text-muted d-block mt-2">Scan to pay</small>
                            </div>
                        </div>

                        <!-- Transaction ID Input -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Transaction ID (TXID):</label>
                            <input type="text" class="form-control" name="transaction_id" 
                                   placeholder="Enter the transaction ID from your wallet" required>
                            <small class="text-muted">Find this in your wallet's transaction history</small>
                        </div>

                        <!-- Payment Proof Upload -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Payment Proof (Optional):</label>
                            <input type="file" class="form-control" name="payment_proof" accept="image/*">
                            <small class="text-muted">Upload screenshot of successful transaction (Max 2MB)</small>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-grid">
                            <button type="button" class="btn btn-success btn-lg" id="submitPaymentBtn">
                                <i class="ri-check-double-line"></i> Submit Payment Details
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Invoice Summary -->
        <div class="col-lg-4">
            <!-- Invoice Summary Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">Invoice Summary</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Invoice Number:</span>
                        <span class="fw-semibold">{{ $invoice->invoice_number }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Due Date:</span>
                        <span class="{{ $invoice->is_overdue ? 'text-danger fw-bold' : 'fw-semibold' }}">
                            {{ $invoice->due_date->format('M d, Y') }}
                            @if($invoice->is_overdue)
                                <span class="badge bg-danger ms-1">Overdue</span>
                            @endif
                        </span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Amount Due:</span>
                        <span class="fs-4 fw-bold text-primary">${{ number_format($invoice->amount, 2) }}</span>
                    </div>
                    <hr>
                    
                    <!-- Selected Crypto Amount -->
                    <div id="cryptoSummary" class="d-none">
                        <div class="alert alert-success">
                            <h6 class="alert-heading mb-2">To Pay:</h6>
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <div class="h4 mb-1" id="displayCryptoAmount">0.00000000 BTC</div>
                                    <small class="text-muted" id="displayUsdAmount">≈ $0.00</small>
                                </div>
                                <div id="cryptoIcon" class="fs-1"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Important Notes -->
                    <div class="alert alert-warning">
                        <h6><i class="ri-alert-line"></i> Important Notes:</h6>
                        <ul class="small mb-0">
                            <li>Double-check the address before sending</li>
                            <li>Cryptocurrency transactions cannot be reversed</li>
                            <li>Network fees are not included in the amount</li>
                            <li>Contact support if you encounter issues</li>
                        </ul>
                    </div>

                    <!-- Countdown Timer -->
                    <div class="text-center mt-4">
                        <div class="countdown-timer">
                            <div class="timer-label">Exchange rate valid for:</div>
                            <div class="timer" id="rateTimer">15:00</div>
                            <small class="text-muted">Rate updates every 15 minutes</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Help Card -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">Need Help?</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('contact') }}" class="btn btn-outline-primary">
                            <i class="ri-customer-service-2-line"></i> Contact Support
                        </a>
                        <button class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#faqModal">
                            <i class="ri-question-line"></i> View FAQs
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- FAQ Modal -->
<div class="modal fade" id="faqModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Payment FAQs</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="accordion" id="faqAccordion">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                How long does payment verification take?
                            </button>
                        </h2>
                        <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Payments are typically verified within 24 hours. You'll receive an email confirmation once verified.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                What if I send the wrong amount?
                            </button>
                        </h2>
                        <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Contact support immediately with your transaction details. Partial payments may require additional transactions.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                Can I get a refund?
                            </button>
                        </h2>
                        <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Cryptocurrency payments are non-refundable. Please double-check all details before sending.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Payment Form (Hidden) -->
<form id="paymentForm" action="{{ route('billing.pay.submit', $invoice) }}" method="POST" enctype="multipart/form-data" class="d-none">
    @csrf
    <input type="hidden" name="crypto_type" id="formCryptoType">
    <input type="hidden" name="transaction_id" id="formTransactionId">
</form>

<style>
.payment-page .steps {
    display: flex;
    justify-content: space-between;
    position: relative;
}

.payment-page .steps::before {
    content: '';
    position: absolute;
    top: 24px;
    left: 0;
    right: 0;
    height: 2px;
    background-color: #e5e7eb;
    z-index: 1;
}

.payment-page .step {
    text-align: center;
    position: relative;
    z-index: 2;
    flex: 1;
}

.payment-page .step-number {
    width: 48px;
    height: 48px;
    background-color: white;
    border: 2px solid #e5e7eb;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 0.5rem;
    font-weight: 600;
    color: #6b7280;
}

.payment-page .step.active .step-number {
    background-color: #3b82f6;
    border-color: #3b82f6;
    color: white;
}

.payment-page .step-label {
    font-size: 0.875rem;
    color: #6b7280;
}

.payment-page .step.active .step-label {
    color: #3b82f6;
    font-weight: 500;
}

.payment-page .payment-method-card {
    border: 2px solid #e5e7eb;
    border-radius: 12px;
    padding: 1.5rem;
    transition: all 0.3s;
    cursor: pointer;
}

.payment-page .payment-method-card:hover {
    border-color: #3b82f6;
    background-color: #f8fafc;
}

.payment-page .payment-method-card.active {
    border-color: #3b82f6;
    background-color: #eff6ff;
}

.payment-page .payment-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
}

.payment-page .form-check-input {
    width: 1.25rem;
    height: 1.25rem;
}

.payment-page .form-check-input:checked {
    background-color: #3b82f6;
    border-color: #3b82f6;
}

.payment-page .qr-code-container {
    max-width: 200px;
}

.payment-page .countdown-timer {
    background: linear-gradient(135deg, #f8fafc, #f1f5f9);
    border-radius: 12px;
    padding: 1rem;
    border: 1px solid #e5e7eb;
}

.payment-page .timer-label {
    font-size: 0.875rem;
    color: #6b7280;
    margin-bottom: 0.5rem;
}

.payment-page .timer {
    font-size: 2rem;
    font-weight: 700;
    color: #3b82f6;
    font-family: monospace;
}

.payment-page .accordion-button:not(.collapsed) {
    background-color: #eff6ff;
    color: #1e40af;
}

@media (max-width: 768px) {
    .payment-page .steps {
        overflow-x: auto;
        padding-bottom: 1rem;
    }
    
    .payment-page .step {
        min-width: 80px;
    }
    
    .payment-page .step-label {
        font-size: 0.75rem;
    }
}
</style>

<!-- QR Code Library -->
<script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.3/build/qrcode.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Payment method selection
    const paymentMethods = document.querySelectorAll('input[name="payment_method"]');
    const paymentDetails = document.getElementById('paymentDetails');
    const cryptoSummary = document.getElementById('cryptoSummary');
    const selectedMethod = document.getElementById('selectedMethod');
    const walletAddress = document.getElementById('walletAddress');
    const cryptoAmount = document.getElementById('cryptoAmount');
    const displayCryptoAmount = document.getElementById('displayCryptoAmount');
    const displayUsdAmount = document.getElementById('displayUsdAmount');
    const cryptoIcon = document.getElementById('cryptoIcon');
    const qrCode = document.getElementById('qrCode');
    const formCryptoType = document.getElementById('formCryptoType');
    const formTransactionId = document.getElementById('formTransactionId');
    const paymentForm = document.getElementById('paymentForm');
    const submitPaymentBtn = document.getElementById('submitPaymentBtn');
    
    // Timer
    let timerMinutes = 15;
    let timerSeconds = 0;
    const rateTimer = document.getElementById('rateTimer');
    
    function updateTimer() {
        if (timerSeconds === 0) {
            if (timerMinutes === 0) {
                // Timer expired - refresh page for new rates
                location.reload();
                return;
            }
            timerMinutes--;
            timerSeconds = 59;
        } else {
            timerSeconds--;
        }
        
        const formattedMinutes = timerMinutes.toString().padStart(2, '0');
        const formattedSeconds = timerSeconds.toString().padStart(2, '0');
        rateTimer.textContent = `${formattedMinutes}:${formattedSeconds}`;
    }
    
    // Start timer
    setInterval(updateTimer, 1000);
    
    // Payment method selection handler
    paymentMethods.forEach(method => {
        method.addEventListener('change', function() {
            if (this.checked) {
                const methodValue = this.value;
                const methodRate = parseFloat(this.dataset.rate);
                const methodAmount = parseFloat(this.dataset.amount);
                
                // Update UI
                paymentDetails.classList.remove('d-none');
                cryptoSummary.classList.remove('d-none');
                
                // Get wallet address based on selected method
                let address = '';
                let iconClass = '';
                let iconColor = '';
                
                switch(methodValue) {
                    case 'BTC':
                        address = '{{ $btcAddress->address ?? "" }}';
                        selectedMethod.textContent = 'Bitcoin (BTC)';
                        cryptoAmount.textContent = methodAmount.toFixed(8) + ' BTC';
                        displayCryptoAmount.textContent = methodAmount.toFixed(8) + ' BTC';
                        iconClass = 'ri-bit-coin-line';
                        iconColor = 'text-warning';
                        break;
                    case 'USDT_ERC20':
                        address = '{{ $usdtErc20Address->address ?? "" }}';
                        selectedMethod.textContent = 'USDT (ERC-20)';
                        cryptoAmount.textContent = methodAmount.toFixed(2) + ' USDT';
                        displayCryptoAmount.textContent = methodAmount.toFixed(2) + ' USDT';
                        iconClass = 'ri-coin-line';
                        iconColor = 'text-info';
                        break;
                    case 'USDT_TRC20':
                        address = '{{ $usdtTrc20Address->address ?? "" }}';
                        selectedMethod.textContent = 'USDT (TRC-20)';
                        cryptoAmount.textContent = methodAmount.toFixed(2) + ' USDT';
                        displayCryptoAmount.textContent = methodAmount.toFixed(2) + ' USDT';
                        iconClass = 'ri-coin-line';
                        iconColor = 'text-success';
                        break;
                }
                
                // Update form fields
                formCryptoType.value = methodValue;
                
                // Update wallet address
                walletAddress.value = address;
                
                // Update crypto icon
                cryptoIcon.innerHTML = `<i class="${iconClass} ${iconColor}"></i>`;
                
                // Update USD amount
                displayUsdAmount.textContent = `≈ ${{ number_format($invoice->amount, 2) }}`;
                
                // Generate QR Code
                qrCode.innerHTML = '';
                const qrData = `${methodValue}:${address}?amount=${methodAmount}`;
                QRCode.toCanvas(qrCode, qrData, {
                    width: 160,
                    height: 160,
                    margin: 1,
                    color: {
                        dark: '#000000',
                        light: '#ffffff'
                    }
                }, function(error) {
                    if (error) console.error(error);
                });
                
                // Highlight selected method card
                document.querySelectorAll('.payment-method-card').forEach(card => {
                    card.classList.remove('active');
                });
                document.getElementById(`${methodValue.toLowerCase()}Method`).classList.add('active');
            }
        });
    });
    
    // Copy address to clipboard
    window.copyAddress = function() {
        navigator.clipboard.writeText(walletAddress.value).then(() => {
            const copyBtn = document.querySelector('[onclick="copyAddress()"]');
            const originalHtml = copyBtn.innerHTML;
            copyBtn.innerHTML = '<i class="ri-check-line"></i> Copied!';
            copyBtn.classList.remove('btn-outline-secondary');
            copyBtn.classList.add('btn-success');
            
            setTimeout(() => {
                copyBtn.innerHTML = originalHtml;
                copyBtn.classList.remove('btn-success');
                copyBtn.classList.add('btn-outline-secondary');
            }, 2000);
        });
    };
    
    // Submit payment
    if (submitPaymentBtn) {
        submitPaymentBtn.addEventListener('click', function() {
            const transactionId = document.querySelector('input[name="transaction_id"]').value;
            const paymentProof = document.querySelector('input[name="payment_proof"]');
            
            if (!transactionId.trim()) {
                alert('Please enter your Transaction ID (TXID)');
                return;
            }
            
            if (paymentProof.files.length > 0) {
                const fileSize = paymentProof.files[0].size / 1024 / 1024; // in MB
                if (fileSize > 2) {
                    alert('Payment proof image must be less than 2MB');
                    return;
                }
            }
            
            // Update form fields
            formTransactionId.value = transactionId;
            
            // Show confirmation
            if (confirm('Are you sure you want to submit this payment? Please verify:\n\n1. You sent the EXACT amount shown\n2. Transaction ID is correct\n3. You have saved a copy of the transaction')) {
                // Submit form
                paymentForm.submit();
            }
        });
    }
    
    // Auto-select first available payment method
    const firstMethod = document.querySelector('input[name="payment_method"]');
    if (firstMethod) {
        firstMethod.checked = true;
        firstMethod.dispatchEvent(new Event('change'));
    }
});




// Auto-refresh exchange rates
let rateRefreshInterval;

function startRateRefresh() {
    // Clear existing interval
    if (rateRefreshInterval) {
        clearInterval(rateRefreshInterval);
    }
    
    // Update rates every 60 seconds
    rateRefreshInterval = setInterval(updateExchangeRates, 60000);
}

function updateExchangeRates() {
    fetch('{{ route("billing.get-rates") }}')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update timestamp
                const updatedAt = new Date(data.updated_at).toLocaleTimeString();
                document.getElementById('rateTimestamp').textContent = 
                    `Last updated: ${updatedAt}`;
                
                // Update BTC rate if changed
                if (data.rates.BTC) {
                    const btcElements = document.querySelectorAll('.btc-rate-display');
                    btcElements.forEach(el => {
                        el.textContent = `1 BTC = $${data.rates.BTC.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})}`;
                    });
                    
                    // Update BTC amount if selected
                    const btcRadio = document.getElementById('btc');
                    if (btcRadio && btcRadio.checked) {
                        const invoiceAmount = {{ $invoice->amount }};
                        const btcAmount = invoiceAmount / data.rates.BTC;
                        document.querySelector('.btc-amount').textContent = 
                            btcAmount.toFixed(8) + ' BTC';
                    }
                }
                
                // Update USDT rate if changed
                if (data.rates.USDT) {
                    const usdtElements = document.querySelectorAll('.usdt-rate-display');
                    usdtElements.forEach(el => {
                        el.textContent = `1 USDT = $${data.rates.USDT.toFixed(4)}`;
                    });
                    
                    // Update USDT amount if selected
                    const usdtRadio = document.querySelector('input[name="payment_method"][value^="USDT"]:checked');
                    if (usdtRadio) {
                        const invoiceAmount = {{ $invoice->amount }};
                        const usdtAmount = invoiceAmount / data.rates.USDT;
                        document.querySelector('.usdt-amount').textContent = 
                            usdtAmount.toFixed(2) + ' USDT';
                    }
                }
                
                // Show success notification
                showRateUpdateNotification('Rates updated successfully');
            }
        })
        .catch(error => {
            console.error('Error updating rates:', error);
            showRateUpdateNotification('Failed to update rates', 'error');
        });
}

function showRateUpdateNotification(message, type = 'success') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `alert alert-${type} alert-dismissible fade show position-fixed bottom-0 end-0 m-3`;
    notification.style.zIndex = '1060';
    notification.innerHTML = `
        <i class="ri-${type === 'success' ? 'check' : 'alert'}-line me-2"></i>
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(notification);
    
    // Auto-remove after 3 seconds
    setTimeout(() => {
        if (notification.parentNode) {
            notification.remove();
        }
    }, 3000);
}

// Start rate refresh when page loads
document.addEventListener('DOMContentLoaded', function() {
    startRateRefresh();
    
    // Add update timestamp display
    const rateContainer = document.querySelector('.countdown-timer');
    if (rateContainer) {
        const timestamp = document.createElement('div');
        timestamp.id = 'rateTimestamp';
        timestamp.className = 'small text-muted mt-1';
        timestamp.textContent = 'Last updated: ' + new Date().toLocaleTimeString();
        rateContainer.appendChild(timestamp);
    }
});
</script>
@endsection