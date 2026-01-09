@extends('layouts.admin')

@section('page-title', 'Wallet Management')

@section('content')
<div class="container-fluid">
    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="row g-3">
                <div class="col-md-3 col-sm-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-1">Total Wallets</h6>
                                    <h3 class="mb-0">{{ $totalWallets }}</h3>
                                </div>
                                <div class="rounded-circle bg-primary bg-opacity-10 p-3">
                                    <i class="ri-wallet-line text-primary fs-4"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3 col-sm-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-1">Active Wallets</h6>
                                    <h3 class="mb-0">{{ $activeWallets }}</h3>
                                </div>
                                <div class="rounded-circle bg-success bg-opacity-10 p-3">
                                    <i class="ri-checkbox-circle-line text-success fs-4"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3 col-sm-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-1">Bitcoin</h6>
                                    <h3 class="mb-0">{{ $btcWallets }}</h3>
                                </div>
                                <div class="rounded-circle bg-warning bg-opacity-10 p-3">
                                    <i class="ri-bit-coin-line text-warning fs-4"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3 col-sm-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-1">USDT</h6>
                                    <h3 class="mb-0">{{ $usdtWallets }}</h3>
                                </div>
                                <div class="rounded-circle bg-info bg-opacity-10 p-3">
                                    <i class="ri-currency-line text-info fs-4"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Wallet Form -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0"><i class="ri-add-circle-line me-2"></i>Add New Wallet</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.wallets.store') }}" method="POST">
                        @csrf
                        
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Crypto Type *</label>
                                <select name="crypto_type" class="form-select" required>
                                    <option value="">Select Type</option>
                                    <option value="BTC">Bitcoin (BTC)</option>
                                    <option value="USDT_ERC20">USDT (ERC20)</option>
                                    <option value="USDT_TRC20">USDT (TRC20)</option>
                                </select>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Wallet Address *</label>
                                <input type="text" name="address" class="form-control" 
                                       placeholder="Enter complete wallet address" required>
                                <small class="text-muted">Must be a valid {{ config('app.name') }} wallet address</small>
                            </div>
                            
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Label/Name *</label>
                                <input type="text" name="label" class="form-control" 
                                       placeholder="e.g., Main Bitcoin Wallet" required>
                            </div>
                            
                            <div class="col-12">
                                <label class="form-label fw-semibold">Notes (Optional)</label>
                                <textarea name="notes" class="form-control" rows="2" 
                                          placeholder="Any additional information about this wallet..."></textarea>
                            </div>
                            
                            <div class="col-12">
                                <div class="form-check">
                                    <input type="checkbox" name="is_active" class="form-check-input" id="is_active" checked>
                                    <label class="form-check-label fw-semibold" for="is_active">
                                        Active (visible to users for payments)
                                    </label>
                                </div>
                            </div>
                            
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="ri-save-line me-2"></i>Add Wallet
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Wallets List -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0"><i class="ri-wallet-line me-2"></i>Managed Wallets</h5>
                </div>
                <div class="card-body p-0">
                    @if($wallets->isEmpty())
                    <div class="text-center py-5">
                        <i class="ri-wallet-line display-1 text-muted opacity-25"></i>
                        <h5 class="mt-3 text-muted">No Wallets Added Yet</h5>
                        <p class="text-muted">Add your first wallet above to start accepting crypto payments</p>
                    </div>
                    @else
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th width="150">Type</th>
                                    <th>Label</th>
                                    <th>Address</th>
                                    <th width="100">Status</th>
                                    <th width="100">Usage</th>
                                    <th width="100">Created</th>
                                    <th width="120">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($wallets as $wallet)
                                <tr>
                                    <td>
                                        <span class="badge bg-{{ $wallet->crypto_type === 'BTC' ? 'warning' : 'info' }}">
                                            {{ $wallet->crypto_type }}
                                        </span>
                                    </td>
                                    <td>
                                        <strong>{{ $wallet->label }}</strong>
                                        @if($wallet->notes)
                                        <br><small class="text-muted">{{ Str::limit($wallet->notes, 40) }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <code class="small text-truncate me-2" style="max-width: 150px;">
                                                {{ $wallet->getShortAddress() }}
                                            </code>
                                            <button type="button" class="btn btn-sm btn-outline-secondary copy-btn" 
                                                    data-text="{{ $wallet->address }}">
                                                <i class="ri-file-copy-line"></i>
                                            </button>
                                        </div>
                                    </td>
                                    <td>
                                        @if($wallet->is_active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-secondary">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark">{{ $wallet->usage_count }}</span>
                                    </td>
                                    <td>
                                        <small>{{ $wallet->created_at->format('M d, Y') }}</small>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('admin.wallets.edit', $wallet->id) }}" 
                                               class="btn btn-outline-primary" title="Edit">
                                                <i class="ri-edit-line"></i>
                                            </a>
                                            @if($wallet->canBeDeleted())
                                            <form action="{{ route('admin.wallets.destroy', $wallet->id) }}" 
                                                  method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger" 
                                                        onclick="return confirm('Are you sure you want to delete this wallet?');">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                            </form>
                                            @else
                                            <button type="button" class="btn btn-outline-secondary" 
                                                    title="Cannot delete - wallet has been used">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    @if($wallets->hasPages())
                    <div class="p-3 border-top">
                        {{ $wallets->links() }}
                    </div>
                    @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Copy wallet address to clipboard
    document.querySelectorAll('.copy-btn').forEach(button => {
        button.addEventListener('click', function() {
            const address = this.getAttribute('data-text');
            navigator.clipboard.writeText(address).then(() => {
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
    });
</script>
@endpush