@extends('layouts.app')

@section('title', 'Login to GlobalSkyFleet | Access Your Shipping Dashboard')

@section('description', 'Login to your GlobalSkyFleet account to manage shipments, track deliveries, request quotes, and access your complete shipping history.')

@section('keywords', 'GlobalSkyFleet login, shipping account, logistics dashboard, track shipments, manage deliveries')

@section('content')
    <!-- Login Section -->
    <section class="login-hero py-5">
        <div class="container">
            <div class="row justify-content-center align-items-center">
                <!-- Left Content (Hidden on Mobile) -->
                <div class="col-lg-6 d-none d-lg-block">
                    <div class="mb-5">
                        <img src="{{ asset('images/logo.png') }}" alt="GlobalSkyFleet Logo" height="64" class="mb-4" onerror="this.onerror=null; this.src='https://public.readdy.ai/ai/img_res/bff3fd19-13f1-4916-8547-1d7e14a0267e.png'">
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
                
                <!-- Login Form -->
                <div class="col-lg-6">
                    <div class="login-card">
                        <div class="text-center mb-5">
                            <h3 class="display-6 fw-bold text-navy mb-3">Sign In</h3>
                            <p class="text-muted">Welcome back! Please login to your account.</p>
                        </div>
                        
                        <form id="loginForm" method="POST" action="{{ route('login.submit') }}">
                            @csrf
                            <div class="mb-4">
                                <label for="email" class="form-label fw-semibold text-navy mb-2">Email Address</label>
                                <input type="email" 
                                       class="form-control form-control-custom" 
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
                                <input type="password" 
                                       class="form-control form-control-custom" 
                                       id="password" 
                                       name="password"
                                       placeholder="••••••••" 
                                       required>
                                @error('password')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="remember" name="remember">
                                    <label class="form-check-label text-muted ms-2" for="remember">
                                        Remember me
                                    </label>
                                </div>
                                <a href="{{ route('password.request') }}" class="text-decoration-none text-skyblue">
                                    Forgot password?
                                </a>
                            </div>
                            
                            <button type="submit" class="btn btn-orange w-100 py-3 fw-semibold fs-5">
                                Sign In
                            </button>
                        </form>
                        
                        <div class="text-center mt-5">
                            <p class="text-muted">
                                Don't have an account? 
                                <a href="{{ route('register') }}" class="text-decoration-none text-skyblue fw-semibold">
                                    Sign Up
                                </a>
                            </p>
                        </div>
                        
                        <div class="mt-5 pt-5 border-top">
                            <p class="text-center text-muted mb-4">Or continue with</p>
                            <div class="row g-3">
                                <div class="col-6">
                                    <a href="{{ route('social.login', 'google') }}" class="social-btn w-100 d-flex align-items-center justify-content-center text-decoration-none">
                                        <i class="ri-google-fill me-2"></i> Google
                                    </a>
                                </div>
                                <div class="col-6">
                                    <a href="{{ route('social.login', 'microsoft') }}" class="social-btn w-100 d-flex align-items-center justify-content-center text-decoration-none">
                                        <i class="ri-microsoft-fill me-2"></i> Microsoft
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const loginForm = document.getElementById('loginForm');
        
        // Form validation
        if (loginForm) {
            loginForm.addEventListener('submit', function(e) {
                const email = document.getElementById('email').value.trim();
                const password = document.getElementById('password').value.trim();
                
                // Basic client-side validation
                if (!email || !password) {
                    e.preventDefault();
                    alert('Please fill in all required fields.');
                    return;
                }
                
                // Email format validation
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(email)) {
                    e.preventDefault();
                    alert('Please enter a valid email address.');
                    return;
                }
                
                // Show loading state
                const submitBtn = loginForm.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Signing in...';
                submitBtn.disabled = true;
                
                // The form will submit normally through Laravel
                // In a real application with AJAX, you would handle the response here
            });
        }
        
        // Social login buttons
        const socialButtons = document.querySelectorAll('.social-btn');
        socialButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                const platform = this.textContent.trim();
                console.log(`Sign in with ${platform} clicked`);
                // In a real application, this would redirect to OAuth provider
            });
        });
        
        // Remember me functionality (client-side)
        const rememberCheckbox = document.getElementById('remember');
        if (rememberCheckbox) {
            // Check if there's a saved email in localStorage
            const savedEmail = localStorage.getItem('globalskyfleet_remember_email');
            if (savedEmail) {
                document.getElementById('email').value = savedEmail;
                rememberCheckbox.checked = true;
            }
            
            rememberCheckbox.addEventListener('change', function() {
                if (this.checked) {
                    const email = document.getElementById('email').value;
                    if (email) {
                        localStorage.setItem('globalskyfleet_remember_email', email);
                    }
                } else {
                    localStorage.removeItem('globalskyfleet_remember_email');
                }
            });
        }
    });
</script>
@endpush