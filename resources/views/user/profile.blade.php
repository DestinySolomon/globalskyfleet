@extends('layouts.dashboard')

@section('title', 'My Profile | GlobalSkyFleet')
@section('page-title', 'My Profile')

@section('content')
<div class="container-fluid">
    <!-- Flash Messages -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="ri-check-line me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif
    
    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="ri-error-warning-line me-2"></i>
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="row">
        <!-- Left Column: Profile Info & Stats -->
        <div class="col-lg-4 col-md-5 mb-4">
            <!-- Profile Card -->
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <div class="position-relative d-inline-block mb-3">
                            <div class="profile-picture-wrapper">
                                <img src="{{ $user->profile_picture_url }}" 
                                     alt="{{ $user->name }}" 
                                     class="profile-picture rounded-circle"
                                     id="profilePicturePreview">
                                @if($user->profile_picture)
                                <button type="button" 
                                        class="btn btn-sm btn-danger position-absolute top-0 end-0 rounded-circle"
                                        data-bs-toggle="modal" 
                                        data-bs-target="#deletePictureModal"
                                        style="width: 30px; height: 30px; padding: 0;">
                                    <i class="ri-delete-bin-line"></i>
                                </button>
                                @endif
                            </div>
                        </div>
                        <h4 class="mb-1">{{ $user->name }}</h4>
                        <p class="text-muted mb-2">{{ $user->email }}</p>
                        @if($user->company)
                        <p class="text-muted mb-3">
                            <i class="ri-building-line me-1"></i>{{ $user->company }}
                        </p>
                        @endif
                        <span class="badge bg-primary-subtle text-primary">
                            <i class="ri-calendar-line me-1"></i>
                            Member since {{ $user->created_at->format('M Y') }}
                        </span>
                    </div>
                    
                    <!-- Bio Section -->
                    @if($user->bio)
                    <div class="mb-4">
                        <h6 class="mb-2">About</h6>
                        <p class="text-muted mb-0">{{ $user->bio }}</p>
                    </div>
                    @endif
                    
                    <!-- Contact Info -->
                    <div class="mb-4">
                        <h6 class="mb-3">Contact Information</h6>
                        <div class="d-flex align-items-center mb-2">
                            <i class="ri-phone-line text-muted me-2"></i>
                            <span>{{ $user->phone ?? 'Not provided' }}</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="ri-mail-line text-muted me-2"></i>
                            <span>{{ $user->email }}</span>
                        </div>
                    </div>
                    
                    <!-- Quick Stats -->
                    <div class="stats-section border-top pt-3">
                        <h6 class="mb-3">Account Statistics</h6>
                        <div class="row g-2">
                            <div class="col-6">
                                <div class="stat-item p-2 bg-light rounded">
                                    <div class="text-center">
                                        <div class="stat-value text-primary">{{ $stats['total_shipments'] }}</div>
                                        <small class="text-muted">Total Shipments</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="stat-item p-2 bg-light rounded">
                                    <div class="text-center">
                                        <div class="stat-value text-success">{{ $stats['delivered_shipments'] }}</div>
                                        <small class="text-muted">Delivered</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="stat-item p-2 bg-light rounded">
                                    <div class="text-center">
                                        <div class="stat-value text-warning">{{ $stats['pending_shipments'] }}</div>
                                        <small class="text-muted">Pending</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="stat-item p-2 bg-light rounded">
                                    <div class="text-center">
                                        <div class="stat-value text-info">{{ $stats['total_addresses'] }}</div>
                                        <small class="text-muted">Addresses</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Quick Links -->
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-body p-3">
                    <div class="d-grid gap-2">
                        <a href="{{ route('shipments.index') }}" class="btn btn-outline-primary text-start">
                            <i class="ri-ship-line me-2"></i> My Shipments
                        </a>
                        <a href="{{ route('addresses.index') }}" class="btn btn-outline-primary text-start">
                            <i class="ri-map-pin-line me-2"></i> Address Book
                        </a>
                        <a href="{{ route('billing.index') }}" class="btn btn-outline-primary text-start">
                            <i class="ri-bill-line me-2"></i> Billing & Invoices
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Right Column: Profile Form & Recent Activity -->
        <div class="col-lg-8 col-md-7">
            <!-- Profile Edit Form -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="ri-edit-line me-2"></i>Edit Profile Information
                        </h5>
                        <button class="btn btn-sm btn-outline-primary" type="button" data-bs-toggle="collapse" data-bs-target="#profileForm">
                            <i class="ri-edit-line me-1"></i> Edit
                        </button>
                    </div>
                </div>
                
                <div class="collapse show" id="profileForm">
                    <div class="card-body p-4">
                        <form action="{{ route('user.profile.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            
                            <div class="row g-3">
                                <!-- Profile Picture Upload -->
                                <div class="col-12">
                                    <label class="form-label">Profile Picture</label>
                                    <div class="d-flex align-items-center">
                                        <div class="me-3">
                                            <img src="{{ $user->profile_picture_url }}" 
                                                 alt="Current profile picture" 
                                                 class="rounded-circle"
                                                 style="width: 80px; height: 80px; object-fit: cover;"
                                                 id="currentPicture">
                                        </div>
                                        <div class="flex-grow-1">
                                            <input type="file" 
                                                   class="form-control @error('profile_picture') is-invalid @enderror" 
                                                   name="profile_picture" 
                                                   id="profilePictureInput"
                                                   accept="image/*">
                                            <div class="form-text">
                                                Upload a JPG, PNG, or GIF image (max 2MB)
                                            </div>
                                            @error('profile_picture')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Name -->
                                <div class="col-md-6">
                                    <label class="form-label">Full Name *</label>
                                    <input type="text" 
                                           name="name" 
                                           class="form-control @error('name') is-invalid @enderror" 
                                           value="{{ old('name', $user->name) }}" 
                                           required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <!-- Phone -->
                                <div class="col-md-6">
                                    <label class="form-label">Phone Number</label>
                                    <input type="tel" 
                                           name="phone" 
                                           class="form-control @error('phone') is-invalid @enderror" 
                                           value="{{ old('phone', $user->phone) }}">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <!-- Company -->
                                <div class="col-12">
                                    <label class="form-label">Company</label>
                                    <input type="text" 
                                           name="company" 
                                           class="form-control @error('company') is-invalid @enderror" 
                                           value="{{ old('company', $user->company) }}">
                                    @error('company')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <!-- Bio -->
                                <div class="col-12">
                                    <label class="form-label">Bio / About Me</label>
                                    <textarea name="bio" 
                                              class="form-control @error('bio') is-invalid @enderror" 
                                              rows="3">{{ old('bio', $user->bio) }}</textarea>
                                    <div class="form-text">
                                        Tell us a little about yourself (max 1000 characters)
                                    </div>
                                    @error('bio')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <!-- Submit Button -->
                                <div class="col-12 pt-3 border-top">
                                    <div class="d-flex justify-content-end">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="ri-save-line me-2"></i>Save Changes
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Recent Activity -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0">
                        <i class="ri-history-line me-2"></i>Recent Shipments
                    </h5>
                </div>
                <div class="card-body p-0">
                    @if($recentShipments->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-4">Tracking #</th>
                                    <th>Destination</th>
                                    <th>Status</th>
                                    <th class="pe-4">Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentShipments as $shipment)
                                <tr>
                                    <td class="ps-4">
                                        <a href="{{ route('shipments.show', $shipment) }}" class="text-decoration-none">
                                            <strong>{{ $shipment->tracking_number }}</strong>
                                        </a>
                                    </td>
                                    <td>
                                        @if($shipment->recipientAddress)
                                            {{ $shipment->recipientAddress->city }}, {{ $shipment->recipientAddress->country_code }}
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $statusClass = [
                                                'pending' => 'warning',
                                                'confirmed' => 'info',
                                                'picked_up' => 'primary',
                                                'in_transit' => 'primary',
                                                'out_for_delivery' => 'success',
                                                'delivered' => 'success',
                                                'cancelled' => 'danger',
                                                'returned' => 'secondary',
                                            ][$shipment->status] ?? 'secondary';
                                        @endphp
                                        <span class="badge bg-{{ $statusClass }}">
                                            {{ ucfirst(str_replace('_', ' ', $shipment->status)) }}
                                        </span>
                                    </td>
                                    <td class="pe-4">
                                        {{ $shipment->created_at->format('M d, Y') }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-5">
                        <i class="ri-box-line text-muted" style="font-size: 3rem;"></i>
                        <p class="text-muted mt-3 mb-0">No shipments yet</p>
                        <a href="{{ route('shipments.create') }}" class="btn btn-sm btn-primary mt-3">
                            <i class="ri-add-line me-1"></i>Create First Shipment
                        </a>
                    </div>
                    @endif
                </div>
                @if($recentShipments->count() > 0)
                <div class="card-footer bg-white border-0">
                    <div class="text-center">
                        <a href="{{ route('shipments.index') }}" class="btn btn-sm btn-outline-primary">
                            <i class="ri-eye-line me-1"></i>View All Shipments
                        </a>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Delete Picture Modal -->
<div class="modal fade" id="deletePictureModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete Profile Picture</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete your profile picture?</p>
                <div class="alert alert-warning mb-0">
                    <i class="ri-alert-line me-2"></i>
                    This action cannot be undone. Your profile will revert to the default avatar.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('user.profile.picture.delete') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-danger">
                        <i class="ri-delete-bin-line me-2"></i>Delete Picture
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.profile-picture-wrapper {
    position: relative;
    display: inline-block;
}

.profile-picture {
    width: 120px;
    height: 120px;
    object-fit: cover;
    border: 4px solid white;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.stat-value {
    font-size: 1.5rem;
    font-weight: 700;
    line-height: 1;
}

.stat-item {
    transition: all 0.3s;
}

.stat-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.bg-primary-subtle {
    background-color: rgba(10, 36, 99, 0.1) !important;
}

.bg-success-subtle {
    background-color: rgba(25, 135, 84, 0.1) !important;
}

.bg-warning-subtle {
    background-color: rgba(255, 193, 7, 0.1) !important;
}

.bg-info-subtle {
    background-color: rgba(13, 202, 240, 0.1) !important;
}
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Profile picture preview
    const profilePictureInput = document.getElementById('profilePictureInput');
    const profilePicturePreview = document.getElementById('profilePicturePreview');
    const currentPicture = document.getElementById('currentPicture');
    
    if (profilePictureInput) {
        profilePictureInput.addEventListener('change', function(e) {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    if (profilePicturePreview) {
                        profilePicturePreview.src = e.target.result;
                    }
                    if (currentPicture) {
                        currentPicture.src = e.target.result;
                    }
                }
                
                reader.readAsDataURL(this.files[0]);
            }
        });
    }
    
    // Auto-expand form if there are validation errors
    const hasErrors = document.querySelector('.is-invalid');
    if (hasErrors) {
        const profileForm = document.getElementById('profileForm');
        if (profileForm && profileForm.classList.contains('collapse')) {
            profileForm.classList.remove('collapse');
        }
    }
});
</script>
@endpush
@endsection