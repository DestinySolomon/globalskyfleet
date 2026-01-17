@extends('layouts.app')

@section('title', 'Register for GlobalSkyFleet | Create Your Shipping Account')

@section('description', 'Create a GlobalSkyFleet account to access premium shipping features, track shipments, request quotes, and manage your logistics needs.')

@section('keywords', 'GlobalSkyFleet register, create account, shipping account, logistics account, sign up')

@section('content')
    <!-- Register Section -->
    <section class="register-hero py-5">
        <div class="container">
            <div class="row justify-content-center align-items-center">
                <!-- Left Content (Hidden on Mobile) -->
                <div class="col-lg-6 d-none d-lg-block">
                    <div class="mb-5">
                        <img src="{{ setting('site_logo') ? Storage::url(setting('site_logo')) : asset('images/logo.png') }}" alt="{{ setting('site_name', 'GlobalSkyFleet') }} Logo" height="64" class="mb-4" onerror="this.onerror=null; this.src='https://public.readdy.ai/ai/img_res/bff3fd19-13f1-4916-8547-1d7e14a0267e.png'">
                        <h2 class="display-5 fw-bold text-navy mb-4">Welcome to GlobalSkyFleet</h2>
                        <p class="text-muted fs-5 lh-lg mb-5">
                            Access your dashboard to manage shipments, track deliveries, and view your complete shipping history.
                        </p>
                        <div class="d-flex flex-column gap-3">
                            <div class="feature-item">
                                <i class="ri-checkbox-circle-fill"></i>
                                <span class="text-dark">Real-time shipment tracking</span>
                            </div>
                            <div class="feature-item">
                                <i class="ri-checkbox-circle-fill"></i>
                                <span class="text-dark">Instant quote requests</span>
                            </div>
                            <div class="feature-item">
                                <i class="ri-checkbox-circle-fill"></i>
                                <span class="text-dark">Download invoices & reports</span>
                            </div>
                            <div class="feature-item">
                                <i class="ri-checkbox-circle-fill"></i>
                                <span class="text-dark">24/7 customer support</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Register Form -->
                <div class="col-lg-6">
                    <div class="register-card">
                        <div class="text-center mb-5">
                            <h3 class="display-6 fw-bold text-navy mb-3">Create Account</h3>
                            <p class="text-muted">Join us to start shipping worldwide.</p>
                        </div>
                        
                        <!-- Display session messages -->
                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif
                        
                        @if(session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif
                        
                        <form id="registerForm" method="POST" action="{{ route('register') }}">
                            @csrf
                            <div class="mb-4">
                                <label for="name" class="form-label fw-semibold text-navy mb-2">Full Name</label>
                                <input type="text" 
                                       class="form-control form-control-custom @error('name') is-invalid @enderror" 
                                       id="name" 
                                       name="name"
                                       value="{{ old('name') }}"
                                       placeholder="John Doe" 
                                       required>
                                @error('name')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-4">
                                <label for="email" class="form-label fw-semibold text-navy mb-2">Email Address</label>
                                <input type="email" 
                                       class="form-control form-control-custom @error('email') is-invalid @enderror" 
                                       id="email" 
                                       name="email"
                                       value="{{ old('email') }}"
                                       placeholder="your.email@example.com" 
                                       required>
                                @error('email')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-4">
                                <label for="password" class="form-label fw-semibold text-navy mb-2">Password</label>
                                <div class="input-group input-group-custom">
                                    <input type="password" 
                                           class="form-control form-control-custom @error('password') is-invalid @enderror" 
                                           id="password" 
                                           name="password"
                                           placeholder="••••••••" 
                                           required>
                                    <span class="input-group-text" id="togglePassword">
                                        <i class="ri-eye-line"></i>
                                    </span>
                                </div>
                                <div id="passwordStrength" class="password-strength"></div>
                                <div id="passwordRequirements" class="password-requirements mt-2">
                                    <div class="password-requirement-item">
                                        <small class="text-muted">Password must contain at least:</small>
                                    </div>
                                    <div class="password-requirement-item">
                                        <small class="text-muted">• 8 characters</small>
                                    </div>
                                    <div class="password-requirement-item">
                                        <small class="text-muted">• 1 uppercase letter</small>
                                    </div>
                                    <div class="password-requirement-item">
                                        <small class="text-muted">• 1 lowercase letter</small>
                                    </div>
                                    <div class="password-requirement-item">
                                        <small class="text-muted">• 1 number</small>
                                    </div>
                                </div>
                                @error('password')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-4">
                                <label for="password_confirmation" class="form-label fw-semibold text-navy mb-2">Confirm Password</label>
                                <div class="input-group input-group-custom">
                                    <input type="password" 
                                           class="form-control form-control-custom" 
                                           id="password_confirmation" 
                                           name="password_confirmation"
                                           placeholder="••••••••" 
                                           required>
                                    <span class="input-group-text" id="toggleConfirmPassword">
                                        <i class="ri-eye-line"></i>
                                    </span>
                                </div>
                                <div id="passwordMatch" class="password-match"></div>
                            </div>
                            
                            <div class="mb-4">
                                <label for="phone" class="form-label fw-semibold text-navy mb-2">Phone Number (Optional)</label>
                                <input type="tel" 
                                       class="form-control form-control-custom @error('phone') is-invalid @enderror" 
                                       id="phone" 
                                       name="phone"
                                       value="{{ old('phone') }}"
                                       placeholder="+1 (555) 123-4567">
                                @error('phone')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="form-check mb-4">
                                <input class="form-check-input @error('terms') is-invalid @enderror" type="checkbox" id="terms" name="terms" {{ old('terms') ? 'checked' : '' }} required>
                                <label class="form-check-label text-muted" for="terms">
                                    I agree to the <a href="{{ route('terms') }}" class="text-skyblue text-decoration-none">Terms of Service</a> and <a href="{{ route('privacy') }}" class="text-skyblue text-decoration-none">Privacy Policy</a>
                                </label>
                                @error('terms')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <button type="submit" class="btn btn-orange w-100 py-3 fw-semibold fs-5">
                                Create Account
                            </button>
                        </form>
                        
                        <div class="text-center mt-5">
                            <p class="text-muted">
                                Already have an account? 
                                <a href="{{ route('login') }}" class="text-skyblue fw-semibold text-decoration-none">Sign In</a>
                            </p>
                        </div>
                        
                        <!-- Removed social login section completely -->
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Success Modal -->
    @if(session('success'))
        <div class="modal fade show" id="successModal" tabindex="-1" style="display: block; padding-right: 17px;">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0">
                    <div class="modal-body text-center p-5">
                        <div class="contact-icon mb-4" style="width: 60px; height: 60px; background-color: rgba(16, 185, 129, 0.2);">
                            <i class="ri-check-line text-success fs-3"></i>
                        </div>
                        <h3 class="h3 fw-bold text-navy mb-3">Account Created Successfully!</h3>
                        <p class="text-muted mb-4">
                            Welcome to GlobalSkyFleet! Your account has been created. Please check your email to verify your account.
                        </p>
                        <a href="{{ route('login') }}" class="btn btn-orange rounded-pill px-4">
                            Go to Login
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
    @endif
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Password visibility toggle
        const togglePassword = document.getElementById('togglePassword');
        const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
        const password = document.getElementById('password');
        const confirmPassword = document.getElementById('password_confirmation');
        const passwordStrength = document.getElementById('passwordStrength');
        const passwordMatch = document.getElementById('passwordMatch');
        const registerForm = document.getElementById('registerForm');
        
        // Toggle password visibility
        if (togglePassword && password) {
            togglePassword.addEventListener('click', function() {
                const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                password.setAttribute('type', type);
                this.innerHTML = type === 'password' ? '<i class="ri-eye-line"></i>' : '<i class="ri-eye-off-line"></i>';
            });
        }
        
        // Toggle confirm password visibility
        if (toggleConfirmPassword && confirmPassword) {
            toggleConfirmPassword.addEventListener('click', function() {
                const type = confirmPassword.getAttribute('type') === 'password' ? 'text' : 'password';
                confirmPassword.setAttribute('type', type);
                this.innerHTML = type === 'password' ? '<i class="ri-eye-line"></i>' : '<i class="ri-eye-off-line"></i>';
            });
        }
        
        // Password strength checker
        function checkPasswordStrength(password) {
            let strength = 0;
            
            // Length check
            if (password.length >= 8) strength++;
            
            // Lowercase check
            if (/[a-z]/.test(password)) strength++;
            
            // Uppercase check
            if (/[A-Z]/.test(password)) strength++;
            
            // Number check
            if (/[0-9]/.test(password)) strength++;
            
            // Special character check (optional bonus)
            if (/[^A-Za-z0-9]/.test(password)) strength++;
            
            return strength;
        }
        
        // Update password strength indicator
        if (password && passwordStrength) {
            password.addEventListener('input', function() {
                const strength = checkPasswordStrength(this.value);
                
                // Reset classes
                passwordStrength.className = 'password-strength';
                
                if (this.value.length === 0) {
                    return;
                } else if (strength <= 2) {
                    passwordStrength.classList.add('weak');
                } else if (strength <= 4) {
                    passwordStrength.classList.add('fair');
                } else {
                    passwordStrength.classList.add('good');
                }
                
                // Check password match
                checkPasswordMatch();
            });
        }
        
        // Password match checker
        function checkPasswordMatch() {
            if (!password || !confirmPassword || !passwordMatch) return;
            
            const passwordValue = password.value;
            const confirmPasswordValue = confirmPassword.value;
            
            if (confirmPasswordValue.length === 0) {
                passwordMatch.className = 'password-match';
                return;
            }
            
            if (passwordValue === confirmPasswordValue && passwordValue.length > 0) {
                passwordMatch.textContent = '✓ Passwords match';
                passwordMatch.className = 'password-match match';
            } else {
                passwordMatch.textContent = '✗ Passwords do not match';
                passwordMatch.className = 'password-match mismatch';
            }
        }
        
        // Check password match on input
        if (confirmPassword) {
            confirmPassword.addEventListener('input', checkPasswordMatch);
        }
        
        // Form validation
        if (registerForm) {
            registerForm.addEventListener('submit', function(e) {
                const passwordValue = password ? password.value : '';
                const confirmPasswordValue = confirmPassword ? confirmPassword.value : '';
                const termsChecked = document.getElementById('terms') ? document.getElementById('terms').checked : false;
                
                // Check password match
                if (passwordValue !== confirmPasswordValue) {
                    e.preventDefault();
                    alert('Passwords do not match. Please check and try again.');
                    return;
                }
                
                // Check password strength
                const strength = checkPasswordStrength(passwordValue);
                if (strength < 3) {
                    e.preventDefault();
                    alert('Password is too weak. Please use a stronger password with at least 8 characters including uppercase, lowercase, and numbers.');
                    return;
                }
                
                // Check terms agreement
                if (!termsChecked) {
                    e.preventDefault();
                    alert('Please agree to the Terms of Service and Privacy Policy.');
                    return;
                }
                
                // Show loading state
                const submitBtn = registerForm.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Creating Account...';
                submitBtn.disabled = true;
            });
        }
        
        // If success modal exists, show it
        const successModal = document.getElementById('successModal');
        if (successModal) {
            const modal = new bootstrap.Modal(successModal);
            modal.show();
            
            // Close modal when clicking backdrop
            successModal.addEventListener('hidden.bs.modal', function () {
                document.querySelector('.modal-backdrop').remove();
            });
        }
    });
</script>
@endpush