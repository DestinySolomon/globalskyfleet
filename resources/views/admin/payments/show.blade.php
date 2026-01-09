@extends('layouts.admin')

@section('page-title', 'Crypto Payment Details - #' . $payment->id)

@section('content')
<div class="container-fluid">
    <!-- Back Button -->
    <div class="row mb-4">
        <div class="col-12">
            <a href="{{ route('admin.payments.crypto') }}" class="btn btn-outline-secondary">
                <i class="ri-arrow-left-line me-2"></i>Back to Payments
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Left Column - Payment Details -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                    <h6 class="mb-0"><i class="ri-information-line me-2"></i>Payment Information</h6>
                    <span class="badge {{ $payment->getStatusBadgeClass() }} fs-6">
                        {{ ucfirst($payment->status) }}
                    </span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <dl class="row mb-0">
                                <dt class="col-sm-4">Payment ID:</dt>
                                <dd class="col-sm-8">#{{ $payment->id }}</dd>
                                
                                <dt class="col-sm-4">Crypto Type:</dt>
                                <dd class="col-sm-8">
                                    <span class="badge bg-dark">
                                        {{ $payment->crypto_type }}
                                    </span>
                                </dd>
                                
                                <dt class="col-sm-4">Amount:</dt>
                                <dd class="col-sm-8">
                                    @if($payment->crypto_type == 'BTC')
                                        <strong>{{ number_format($payment->crypto_amount, 8) }} BTC</strong>
                                    @else
                                        <strong>{{ number_format($payment->usdt_amount, 6) }} USDT</strong>
                                    @endif
                                    @if($payment->exchange_rate)
                                        <div class="text-muted small">
                                            ≈ ${{ number_format($payment->usdt_amount, 2) }}
                                            @ Rate: {{ number_format($payment->exchange_rate, 8) }}
                                        </div>
                                    @endif
                                </dd>
                                
                                <dt class="col-sm-4">Confirmations:</dt>
                                <dd class="col-sm-8">
                                    {{ $payment->confirmations }}
                                    @if($payment->confirmations >= 3)
                                        <span class="badge bg-success ms-2">Secure</span>
                                    @endif
                                </dd>
                            </dl>
                        </div>
                        
                        <div class="col-md-6">
                            <dl class="row mb-0">
                                <dt class="col-sm-4">Created:</dt>
                                <dd class="col-sm-8">{{ $payment->created_at->format('M d, Y H:i:s') }}</dd>
                                
                                <dt class="col-sm-4">Paid:</dt>
                                <dd class="col-sm-8">
                                    @if($payment->paid_at)
                                        {{ $payment->paid_at->format('M d, Y H:i:s') }}
                                    @else
                                        <span class="text-muted">Not paid yet</span>
                                    @endif
                                </dd>
                                
                                <dt class="col-sm-4">Confirmed:</dt>
                                <dd class="col-sm-8">
                                    @if($payment->confirmed_at)
                                        {{ $payment->confirmed_at->format('M d, Y H:i:s') }}
                                    @else
                                        <span class="text-muted">Not confirmed</span>
                                    @endif
                                </dd>
                                
                                <dt class="col-sm-4">Expires:</dt>
                                <dd class="col-sm-8">
                                    @if($payment->expires_at)
                                        {{ $payment->expires_at->format('M d, Y H:i:s') }}
                                        @if($payment->isExpired())
                                            <span class="badge bg-danger ms-2">Expired</span>
                                        @endif
                                    @else
                                        <span class="text-muted">No expiry</span>
                                    @endif
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Wallet & Transaction Info -->
            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white border-bottom">
                            <h6 class="mb-0"><i class="ri-wallet-line me-2"></i>Payment Address</h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label small text-muted mb-1">Wallet Address</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" 
                                           value="{{ $payment->payment_address }}" 
                                           id="paymentAddress" readonly>
                                    <button class="btn btn-outline-secondary copy-btn" 
                                            type="button"
                                            data-target="#paymentAddress">
                                        <i class="ri-file-copy-line"></i>
                                    </button>
                                </div>
                            </div>
                            
                            @if($payment->transaction_id)
                            <div class="mb-3">
                                <label class="form-label small text-muted mb-1">Transaction ID/Hash</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" 
                                           value="{{ $payment->transaction_id }}" 
                                           id="transactionId" readonly>
                                    <button class="btn btn-outline-secondary copy-btn" 
                                            type="button"
                                            data-target="#transactionId">
                                        <i class="ri-file-copy-line"></i>
                                    </button>
                                </div>
                            </div>
                            @endif
                            
                            @if($payment->verifier)
                            <div class="mt-3 pt-3 border-top">
                                <label class="form-label small text-muted mb-1">Verified By</label>
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center me-2" 
                                         style="width: 36px; height: 36px; font-size: 14px; font-weight: bold;">
                                        {{ substr($payment->verifier->name, 0, 2) }}
                                    </div>
                                    <div>
                                        <div class="fw-semibold">{{ $payment->verifier->name }}</div>
                                        <small class="text-muted">
                                            {{ $payment->verified_at->format('M d, Y H:i') }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white border-bottom">
                            <h6 class="mb-0"><i class="ri-user-line me-2"></i>User Information</h6>
                        </div>
                        <div class="card-body">
                            @if($payment->user)
                            <div class="d-flex align-items-center mb-4">
                                <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center me-3" 
                                     style="width: 48px; height: 48px; font-size: 16px; font-weight: bold;">
                                    {{ substr($payment->user->name, 0, 2) }}
                                </div>
                                <div>
                                    <div class="fw-semibold fs-5">{{ $payment->user->name }}</div>
                                    <div class="text-muted">{{ $payment->user->email }}</div>
                                    <small class="text-muted">User ID: {{ $payment->user->id }}</small>
                                </div>
                            </div>
                            
                            <div class="row small">
                                <div class="col-6">
                                    <div class="text-muted mb-1">Phone</div>
                                    <div class="fw-semibold">{{ $payment->user->phone ?? 'Not provided' }}</div>
                                </div>
                                <div class="col-6">
                                    <div class="text-muted mb-1">Role</div>
                                    <div>
                                        <span class="badge bg-{{ $payment->user->role == 'admin' ? 'danger' : 'secondary' }}">
                                            {{ ucfirst($payment->user->role) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mt-3 pt-3 border-top">
                                <a href="{{ route('admin.users.show', $payment->user->id) }}" 
                                   class="btn btn-outline-primary btn-sm">
                                    <i class="ri-external-link-line me-1"></i>View User Profile
                                </a>
                            </div>
                            @else
                            <div class="text-center py-4">
                                <i class="ri-user-unfollow-line display-4 text-muted mb-3"></i>
                                <p class="text-muted mb-0">User not found or deleted</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Invoice Information -->
            @if($payment->invoice)
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom">
                    <h6 class="mb-0"><i class="ri-file-text-line me-2"></i>Invoice Information</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <dl class="row mb-0">
                                <dt class="col-sm-4">Invoice #:</dt>
                                <dd class="col-sm-8">{{ $payment->invoice->invoice_number }}</dd>
                                
                                <dt class="col-sm-4">Amount Due:</dt>
                                <dd class="col-sm-8">${{ number_format($payment->invoice->amount, 2) }}</dd>
                                
                                <dt class="col-sm-4">Description:</dt>
                                <dd class="col-sm-8">{{ $payment->invoice->description }}</dd>
                            </dl>
                        </div>
                        <div class="col-md-6">
                            <dl class="row mb-0">
                                <dt class="col-sm-4">Due Date:</dt>
                                <dd class="col-sm-8">{{ $payment->invoice->due_date->format('M d, Y') }}</dd>
                                
                                <dt class="col-sm-4">Status:</dt>
                                <dd class="col-sm-8">
                                    <span class="badge bg-{{ $payment->invoice->status == 'paid' ? 'success' : ($payment->invoice->status == 'pending' ? 'warning' : 'secondary') }}">
                                        {{ ucfirst($payment->invoice->status) }}
                                    </span>
                                </dd>
                                
                                <dt class="col-sm-4">Created:</dt>
                                <dd class="col-sm-8">{{ $payment->invoice->created_at->format('M d, Y') }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Payment Proof -->
            @if($payment->payment_proof)
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                    <h6 class="mb-0"><i class="ri-image-line me-2"></i>Payment Proof</h6>
                    <a href="{{ Storage::url($payment->payment_proof) }}" 
                       target="_blank" 
                       class="btn btn-sm btn-outline-primary">
                        <i class="ri-external-link-line me-1"></i>Open Full Size
                    </a>
                </div>
                <div class="card-body">
                    <div class="text-center">
                        <img src="{{ Storage::url($payment->payment_proof) }}" 
                             alt="Payment Proof" 
                             class="img-fluid rounded border" 
                             style="max-height: 400px;">
                    </div>
                </div>
            </div>
            @endif

            <!-- Admin Notes -->
            @if($payment->admin_notes)
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h6 class="mb-0"><i class="ri-sticky-note-line me-2"></i>Admin Notes</h6>
                </div>
                <div class="card-body">
                    <div class="bg-light p-3 rounded">
                        <p class="mb-0">{{ $payment->admin_notes }}</p>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Right Column - Update Form -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm sticky-top" style="top: 20px;">
                <div class="card-header bg-white border-bottom">
                    <h6 class="mb-0"><i class="ri-edit-line me-2"></i>Update Payment</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.payments.update', $payment->id) }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select" required>
                                <option value="pending" {{ $payment->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="processing" {{ $payment->status == 'processing' ? 'selected' : '' }}>Processing</option>
                                <option value="confirmed" {{ $payment->status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                <option value="completed" {{ $payment->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="failed" {{ $payment->status == 'failed' ? 'selected' : '' }}>Failed</option>
                                <option value="expired" {{ $payment->status == 'expired' ? 'selected' : '' }}>Expired</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Confirmations</label>
                            <input type="number" name="confirmations" 
                                   class="form-control" 
                                   value="{{ $payment->confirmations }}" 
                                   min="0" 
                                   placeholder="Number of blockchain confirmations">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Admin Notes</label>
                            <textarea name="admin_notes" class="form-control" rows="4" 
                                      placeholder="Add notes about this payment...">{{ $payment->admin_notes }}</textarea>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="ri-save-line me-2"></i>Update Payment
                            </button>
                            
                            <a href="{{ route('admin.payments.crypto') }}" class="btn btn-outline-secondary">
                                <i class="ri-close-line me-2"></i>Cancel
                            </a>
                        </div>
                    </form>
                    
                    <!-- Quick Actions -->
                    <div class="mt-4 pt-4 border-top">
                        <h6 class="mb-3">Quick Actions</h6>
                        <div class="d-grid gap-2">
                            @if($payment->status == 'pending' || $payment->status == 'processing')
                            <button type="button" class="btn btn-success" 
                                    onclick="quickUpdate('confirmed')">
                                <i class="ri-check-line me-2"></i>Confirm Payment
                            </button>
                            <button type="button" class="btn btn-danger"
                                    onclick="quickUpdate('failed')">
                                <i class="ri-close-line me-2"></i>Reject Payment
                            </button>
                            @endif
                            
                            @if($payment->status == 'confirmed')
                            <button type="button" class="btn btn-success"
                                    onclick="quickUpdate('completed')">
                                <i class="ri-check-double-line me-2"></i>Mark as Completed
                            </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Copy to clipboard function
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
    
    // Quick status update
    function quickUpdate(status) {
        if (!confirm(`Are you sure you want to mark this payment as ${status}?`)) {
            return;
        }
        
        // Create a hidden form
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("admin.payments.update", $payment->id) }}';
        
        // Add CSRF token
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = '{{ csrf_token() }}';
        form.appendChild(csrfInput);
        
        // Add status input
        const statusInput = document.createElement('input');
        statusInput.type = 'hidden';
        statusInput.name = 'status';
        statusInput.value = status;
        form.appendChild(statusInput);
        
        // Add confirmations input (keep current value)
        const confirmationsInput = document.createElement('input');
        confirmationsInput.type = 'hidden';
        confirmationsInput.name = 'confirmations';
        confirmationsInput.value = '{{ $payment->confirmations }}';
        form.appendChild(confirmationsInput);
        
        // Add admin notes input (keep current value)
        const notesInput = document.createElement('input');
        notesInput.type = 'hidden';
        notesInput.name = 'admin_notes';
        notesInput.value = `{{ $payment->admin_notes }}`;
        form.appendChild(notesInput);
        
        // Submit form
        document.body.appendChild(form);
        form.submit();
    }
</script>
@endpush

<style>
    .sticky-top {
        position: sticky;
        z-index: 1;
    }
    
    .badge {
        font-size: 0.75rem;
        font-weight: 500;
    }
    
    code {
        font-size: 0.875rem;
        background: #f8f9fa;
        padding: 2px 6px;
        border-radius: 4px;
        color: #d63384;
    }
    
    .input-group input:readonly {
        background-color: #f8f9fa;
    }
</style>