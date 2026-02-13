@extends('layouts.app')

@section('title', 'Reset Password | GlobalSkyFleet')

@section('content')
    <section class="login-hero py-5" style="padding-top: 100px !important;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6">
                    <div class="login-card">
                        <div class="text-center mb-5">
                            <h3 class="display-6 fw-bold text-navy mb-3">Set New Password</h3>
                            <p class="text-muted">Enter your new password below.</p>
                        </div>

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                @foreach ($errors->all() as $error)
                                    <p class="mb-0">{{ $error }}</p>
                                @endforeach
                            </div>
                        @endif

                        <form method="POST" action="{{ route('password.update') }}">
                            @csrf
                            <input type="hidden" name="token" value="{{ $token }}">
                            
                            <div class="mb-4">
                                <label for="email" class="form-label fw-semibold text-navy mb-2">Email Address</label>
                                <input type="email" 
                                       class="form-control form-control-custom" 
                                       id="email" 
                                       name="email"
                                       value="{{ $email ?? old('email') }}"
                                       placeholder="your.email@example.com" 
                                       required
                                       readonly>
                            </div>

                            <div class="mb-4">
                                <label for="password" class="form-label fw-semibold text-navy mb-2">New Password</label>
                                <input type="password" 
                                       class="form-control form-control-custom @error('password') is-invalid @enderror" 
                                       id="password" 
                                       name="password"
                                       placeholder="Enter new password (min. 8 characters)" 
                                       required>
                                <div class="form-text">Password must be at least 8 characters long.</div>
                                @error('password')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="password_confirmation" class="form-label fw-semibold text-navy mb-2">Confirm New Password</label>
                                <input type="password" 
                                       class="form-control form-control-custom" 
                                       id="password_confirmation" 
                                       name="password_confirmation"
                                       placeholder="Confirm your new password" 
                                       required>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-orange w-100 py-3 fw-semibold fs-5">
                                    Reset Password
                                </button>
                                
                                <a href="{{ route('login') }}" class="btn btn-outline-navy w-100 py-3">
                                    Back to Login
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form');
        const password = document.getElementById('password');
        const confirmPassword = document.getElementById('password_confirmation');
        
        if (form) {
            form.addEventListener('submit', function(e) {
                // Password validation
                if (password.value.length < 8) {
                    e.preventDefault();
                    alert('Password must be at least 8 characters long.');
                    return;
                }
                
                if (password.value !== confirmPassword.value) {
                    e.preventDefault();
                    alert('Passwords do not match.');
                    return;
                }
                
                const submitBtn = form.querySelector('button[type="submit"]');
                if (submitBtn) {
                    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Resetting...';
                    submitBtn.disabled = true;
                }
            });
        }
        
        // Real-time password matching
        if (password && confirmPassword) {
            const checkPasswords = () => {
                if (password.value && confirmPassword.value) {
                    if (password.value === confirmPassword.value) {
                        confirmPassword.classList.remove('is-invalid');
                        confirmPassword.classList.add('is-valid');
                    } else {
                        confirmPassword.classList.remove('is-valid');
                        confirmPassword.classList.add('is-invalid');
                    }
                }
            };
            
            password.addEventListener('input', checkPasswords);
            confirmPassword.addEventListener('input', checkPasswords);
        }
    });
</script>
@endpush