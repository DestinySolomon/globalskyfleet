@extends('layouts.admin')

@section('page-title', 'Edit Wallet - ' . $wallet->label)

@section('content')
<div class="container-fluid">
    <!-- Back Button -->
    <div class="row mb-4">
        <div class="col-12">
            <a href="{{ route('admin.wallets') }}" class="btn btn-outline-secondary">
                <i class="ri-arrow-left-line me-2"></i>Back to Wallets
            </a>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Wallet Details Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0"><i class="ri-wallet-line me-2"></i>Edit Wallet</h5>
                </div>
                <div class="card-body">
                    <!-- Current Wallet Info -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card border">
                                <div class="card-body">
                                    <h6 class="card-title text-muted mb-3">Current Information</h6>
                                    <table class="table table-sm">
                                        <tr>
                                            <th width="120">Crypto Type:</th>
                                            <td>
                                                <span class="badge bg-{{ $wallet->crypto_type === 'BTC' ? 'warning' : 'info' }}">
                                                    {{ $wallet->getCryptoTypeName() }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Wallet Address:</th>
                                            <td>
                                                <code class="small">{{ $wallet->address }}</code>
                                                <button type="button" class="btn btn-sm btn-outline-secondary copy-btn ms-2" 
                                                        data-text="{{ $wallet->address }}">
                                                    <i class="ri-file-copy-line"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Current Status:</th>
                                            <td>
                                                @if($wallet->is_active)
                                                    <span class="badge bg-success">Active</span>
                                                @else
                                                    <span class="badge bg-secondary">Inactive</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Usage Count:</th>
                                            <td>
                                                <span class="badge bg-light text-dark">{{ $wallet->usage_count }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Created:</th>
                                            <td>{{ $wallet->created_at->format('M d, Y H:i') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Created By:</th>
                                            <td>{{ $wallet->creator->name ?? 'System' }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border">
                                <div class="card-body">
                                    <h6 class="card-title text-muted mb-3">Quick Actions</h6>
                                    <div class="d-grid gap-2">
                                        @if($wallet->is_active)
                                            <form action="{{ route('admin.wallets.update', $wallet->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="is_active" value="0">
                                                <button type="submit" class="btn btn-warning w-100">
                                                    <i class="ri-eye-off-line me-2"></i>Deactivate Wallet
                                                </button>
                                            </form>
                                        @else
                                            <form action="{{ route('admin.wallets.update', $wallet->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="is_active" value="1">
                                                <input type="hidden" name="label" value="{{ $wallet->label }}">
                                                <input type="hidden" name="notes" value="{{ $wallet->notes }}">
                                                <button type="submit" class="btn btn-success w-100">
                                                    <i class="ri-eye-line me-2"></i>Activate Wallet
                                                </button>
                                            </form>
                                        @endif
                                        
                                        @if($wallet->canBeDeleted())
                                        <form action="{{ route('admin.wallets.destroy', $wallet->id) }}" method="POST" 
                                              onsubmit="return confirm('Are you sure you want to delete this wallet? This action cannot be undone.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger w-100">
                                                <i class="ri-delete-bin-line me-2"></i>Delete Wallet
                                            </button>
                                        </form>
                                        @else
                                        <button type="button" class="btn btn-secondary w-100" disabled>
                                            <i class="ri-delete-bin-line me-2"></i>Cannot Delete (Used)
                                        </button>
                                        @endif
                                        
                                        <a href="{{ $wallet->getExplorerUrl() }}" target="_blank" class="btn btn-outline-info w-100">
                                            <i class="ri-external-link-line me-2"></i>View on Explorer
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Edit Form -->
                    <div class="card border">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">Edit Wallet Details</h6>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.wallets.update', $wallet->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                
                                <div class="row g-3">
                                    <div class="col-12">
                                        <div class="alert alert-info">
                                            <i class="ri-information-line me-2"></i>
                                            <strong>Note:</strong> Crypto type and wallet address cannot be changed. 
                                            If you need to change the address, create a new wallet instead.
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Label/Name *</label>
                                        <input type="text" name="label" class="form-control" 
                                               value="{{ old('label', $wallet->label) }}" required>
                                        @error('label')
                                            <div class="text-danger small">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Status</label>
                                        <div class="form-check form-switch mt-2">
                                            <input type="checkbox" name="is_active" 
                                                   class="form-check-input" id="is_active"
                                                   {{ old('is_active', $wallet->is_active) ? 'checked' : '' }}>
                                            <label class="form-check-label fw-semibold" for="is_active">
                                                Active (visible to users)
                                            </label>
                                        </div>
                                    </div>
                                    
                                    <div class="col-12">
                                        <label class="form-label fw-semibold">Notes</label>
                                        <textarea name="notes" class="form-control" rows="4" 
                                                  placeholder="Any additional information about this wallet...">{{ old('notes', $wallet->notes) }}</textarea>
                                        @error('notes')
                                            <div class="text-danger small">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-12">
                                        <div class="d-flex justify-content-between">
                                            <a href="{{ route('admin.wallets') }}" class="btn btn-secondary">
                                                <i class="ri-close-line me-2"></i>Cancel
                                            </a>
                                            <button type="submit" class="btn btn-primary px-4">
                                                <i class="ri-save-line me-2"></i>Update Wallet
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
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