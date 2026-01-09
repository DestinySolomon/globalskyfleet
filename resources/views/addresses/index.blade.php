@extends('layouts.dashboard')

@section('title', 'Address Book | GlobalSkyFleet')
@section('page-title', 'Address Book')

@section('content')
<!-- Quick Stats -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Total Addresses</h6>
                        <h3 class="mb-0">{{ $addresses->total() }}</h3>
                    </div>
                    <div class="bg-primary bg-opacity-10 p-3 rounded">
                        <i class="ri-map-pin-line text-primary" style="font-size: 1.5rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Shipping Addresses</h6>
                        <h3 class="mb-0">{{ Auth::user()->addresses()->where('type', 'shipping')->count() }}</h3>
                    </div>
                    <div class="bg-success bg-opacity-10 p-3 rounded">
                        <i class="ri-truck-line text-success" style="font-size: 1.5rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Default Addresses</h6>
                        <h3 class="mb-0">{{ Auth::user()->addresses()->where('is_default', true)->count() }}</h3>
                    </div>
                    <div class="bg-warning bg-opacity-10 p-3 rounded">
                        <i class="ri-star-line text-warning" style="font-size: 1.5rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Addresses List -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-semibold">
            <i class="ri-map-pin-line me-2"></i>My Addresses
        </h5>
        <a href="{{ route('addresses.create') }}" class="btn btn-primary btn-sm">
            <i class="ri-add-line me-2"></i>Add New Address
        </a>
    </div>
    
    <div class="card-body p-0">
        @if($addresses->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">Address Name</th>
                        <th>Type</th>
                        <th>Address</th>
                        <th>Contact</th>
                        <th>Status</th>
                        <th class="text-center pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($addresses as $address)
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <div class="bg-light p-2 rounded">
                                        @if($address->type === 'shipping')
                                            <i class="ri-truck-line text-primary"></i>
                                        @elseif($address->type === 'billing')
                                            <i class="ri-bill-line text-success"></i>
                                        @elseif($address->type === 'home')
                                            <i class="ri-home-line text-warning"></i>
                                        @else
                                            <i class="ri-building-line text-info"></i>
                                        @endif
                                    </div>
                                </div>
                                <div>
                                    <strong class="d-block">{{ $address->contact_name }}</strong>
                                    <small class="text-muted">
                                        @if($address->company)
                                            {{ $address->company }}
                                        @else
                                            {{ ucfirst($address->type) }} Address
                                        @endif
                                    </small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge 
                                @if($address->type === 'shipping') bg-primary
                                @elseif($address->type === 'billing') bg-success
                                @elseif($address->type === 'home') bg-warning
                                @else bg-info @endif">
                                {{ ucfirst($address->type) }}
                            </span>
                        </td>
                        <td>
                            <div class="text-truncate" style="max-width: 250px;">
                                {{ $address->address_line1 }}<br>
                                @if($address->address_line2)
                                    {{ $address->address_line2 }}<br>
                                @endif
                                {{ $address->city }}, {{ $address->state }} {{ $address->postal_code }}<br>
                                <small class="text-muted">{{ $address->country_code }}</small>
                            </div>
                        </td>
                        <td>
                            <div>
                                <div class="mb-1">
                                    <i class="ri-user-line me-1 text-muted"></i>
                                    {{ $address->contact_name }}
                                </div>
                                <div>
                                    <i class="ri-phone-line me-1 text-muted"></i>
                                    {{ $address->contact_phone }}
                                </div>
                            </div>
                        </td>
                        <td>
                            @if($address->is_default)
                            <span class="badge bg-warning">
                                <i class="ri-star-fill me-1"></i>Default
                            </span>
                            @else
                            <form action="{{ route('addresses.set-default', $address) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-secondary">
                                    <i class="ri-star-line me-1"></i>Set Default
                                </button>
                            </form>
                            @endif
                        </td>
                        <td class="pe-4">
                            <div class="d-flex justify-content-center gap-2">
                                <!-- View Button -->
                                <a href="{{ route('addresses.show', $address) }}" class="btn btn-sm btn-outline-primary" 
                                   title="View Address Details">
                                    <i class="ri-eye-line"></i>
                                </a>
                                
                                <!-- Edit Button -->
                                <a href="{{ route('addresses.edit', $address) }}" class="btn btn-sm btn-outline-success"
                                   title="Edit Address">
                                    <i class="ri-edit-line"></i>
                                </a>
                                
                                <!-- Delete Button -->
                                <form action="{{ route('addresses.destroy', $address) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" 
                                            onclick="return confirm('Are you sure you want to delete this address?')"
                                            title="Delete Address">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-5">
            <div class="mb-4">
                <i class="ri-map-pin-line text-muted" style="font-size: 3rem;"></i>
            </div>
            <h5 class="text-muted mb-3">No addresses saved yet</h5>
            <p class="text-muted mb-4">Add your first address to get started with shipments</p>
            <a href="{{ route('addresses.create') }}" class="btn btn-primary">
                <i class="ri-add-line me-2"></i>Add First Address
            </a>
        </div>
        @endif
    </div>
    
    @if($addresses->hasPages())
    <div class="card-footer bg-white border-0">
        <div class="d-flex justify-content-between align-items-center">
            <div class="text-muted small">
                Showing {{ $addresses->firstItem() }} - {{ $addresses->lastItem() }} of {{ $addresses->total() }} addresses
            </div>
            {{ $addresses->links() }}
        </div>
    </div>
    @endif
</div>

<!-- Help Information -->
<div class="row mt-4">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h6 class="fw-semibold mb-3">
                    <i class="ri-information-line me-2"></i>Why Save Addresses?
                </h6>
                <ul class="list-unstyled mb-0">
                    <li class="mb-2">
                        <i class="ri-checkbox-circle-line text-success me-2"></i>
                        Faster checkout for shipments
                    </li>
                    <li class="mb-2">
                        <i class="ri-checkbox-circle-line text-success me-2"></i>
                        Consistent address information
                    </li>
                    <li class="mb-2">
                        <i class="ri-checkbox-circle-line text-success me-2"></i>
                        Set default addresses for quick selection
                    </li>
                    <li>
                        <i class="ri-checkbox-circle-line text-success me-2"></i>
                        Track shipments to saved addresses
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h6 class="fw-semibold mb-3">
                    <i class="ri-question-line me-2"></i>Need Help?
                </h6>
                <p class="text-muted small mb-3">For address-related questions:</p>
                <div class="d-flex gap-2">
                    <a href="{{ route('contact') }}" class="btn btn-outline-primary btn-sm">
                        <i class="ri-customer-service-line me-1"></i>Contact Support
                    </a>
                    <a href="#" class="btn btn-outline-secondary btn-sm">
                        <i class="ri-file-text-line me-1"></i>Address Guidelines
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Set default address confirmation
    document.querySelectorAll('form[action*="set-default"]').forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!confirm('Set this as your default address for ' + 
                this.closest('tr').querySelector('.badge').textContent + '?')) {
                e.preventDefault();
            }
        });
    });
    
    // Delete confirmation with more context
    document.querySelectorAll('form[action*="destroy"]').forEach(form => {
        form.addEventListener('submit', function(e) {
            const addressName = this.closest('tr').querySelector('strong').textContent;
            const addressLine = this.closest('tr').querySelector('.text-truncate').textContent.trim().split('\n')[0];
            
            if (!confirm(`Are you sure you want to delete "${addressName}"?\nAddress: ${addressLine}`)) {
                e.preventDefault();
            }
        });
    });
});
</script>
@endsection