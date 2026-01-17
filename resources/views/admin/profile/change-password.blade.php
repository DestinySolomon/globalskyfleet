@extends('layouts.admin')

@section('page-title', 'Change Password')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Change Password</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.profile.password.update') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="current_password" class="form-label">Current Password *</label>
                            <div class="input-group">
                                <input type="password" class="form-control @error('current_password') is-invalid @enderror" 
                                       id="current_password" name="current_password" required>
                                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('current_password')">
                                    <i class="ri-eye-line"></i>
                                </button>
                                @error('current_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="new_password" class="form-label">New Password *</label>
                            <div class="input-group">
                                <input type="password" class="form-control @error('new_password') is-invalid @enderror" 
                                       id="new_password" name="new_password" required>
                                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('new_password')">
                                    <i class="ri-eye-line"></i>
                                </button>
                                @error('new_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <small class="text-muted">Password must be at least 8 characters with letters, numbers, and symbols</small>
                        </div>
                        
                        <div class="mb-4">
                            <label for="new_password_confirmation" class="form-label">Confirm New Password *</label>
                            <div class="input-group">
                                <input type="password" class="form-control @error('new_password_confirmation') is-invalid @enderror" 
                                       id="new_password_confirmation" name="new_password_confirmation" required>
                                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('new_password_confirmation')">
                                    <i class="ri-eye-line"></i>
                                </button>
                                @error('new_password_confirmation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <!-- Password Requirements -->
                        <div class="alert alert-info mb-4">
                            <h6 class="alert-heading mb-2">Password Requirements:</h6>
                            <ul class="mb-0 ps-3">
                                <li>Minimum 8 characters</li>
                                <li>At least one uppercase letter</li>
                                <li>At least one lowercase letter</li>
                                <li>At least one number</li>
                                <li>At least one special character</li>
                            </ul>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.profile.edit') }}" class="btn btn-outline-secondary">
                                <i class="ri-arrow-left-line me-2"></i> Back to Profile
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="ri-lock-line me-2"></i> Update Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Password Last Changed -->
            <div class="card mt-4">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="ri-history-line fs-4 text-muted"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0">Password History</h6>
                            <small class="text-muted">
                                @if(Auth::user()->password_changed_at)
                                    Last changed: {{ Auth::user()->password_changed_at->diffForHumans() }}
                                @else
                                    Password never changed
                                @endif
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function togglePassword(inputId) {
        const input = document.getElementById(inputId);
        const button = input.nextElementSibling;
        const icon = button.querySelector('i');
        
        if (input.type === 'password') {
            input.type = 'text';
            icon.className = 'ri-eye-off-line';
        } else {
            input.type = 'password';
            icon.className = 'ri-eye-line';
        }
    }
</script>
@endpush
@endsection