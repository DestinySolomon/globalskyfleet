<!-- layouts/partials/main-nav.blade.php -->
<!-- Main Navigation -->
<nav class="navbar navbar-expand-lg main-nav">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-3" href="{{ route('home') }}">
            <img src="{{ setting('site_logo') ? Storage::url(setting('site_logo')) : asset('images/logo.png') }}" alt="{{ setting('site_name', 'GlobalSkyFleet') }} Logo" height="80" style="width: auto; max-width: 120px; object-fit: contain;" {{ !setting('site_logo') ? 'class="default-logo"' : '' }}>
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <i class="ri-menu-line text-white fs-3"></i>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center gap-3 gap-lg-4">
                @php
                    $currentRoute = Route::currentRouteName();
                @endphp
                
                <!-- Home -->
                <li class="nav-item position-relative">
                    <a class="nav-link hover-text-navy px-2 {{ $currentRoute == 'home' ? 'active' : '' }}" 
                       href="{{ route('home') }}">
                        Home
                        <!-- Purse Bead Indicator -->
                        <span class="bead-indicator {{ $currentRoute == 'home' ? 'bead-visible' : '' }}"></span>
                    </a>
                </li>
                
                <!-- Services -->
                <li class="nav-item position-relative">
                    <a class="nav-link hover-text-navy px-2 {{ Str::startsWith($currentRoute, 'services') ? 'active' : '' }}" 
                       href="{{ route('services') }}">
                        Services
                        <!-- Purse Bead Indicator -->
                        <span class="bead-indicator {{ Str::startsWith($currentRoute, 'services') ? 'bead-visible' : '' }}"></span>
                    </a>
                </li>
                
                <!-- Track Shipment -->
                <li class="nav-item position-relative">
                    <a class="nav-link hover-text-navy px-2 {{ Str::startsWith($currentRoute, 'tracking') ? 'active' : '' }}" 
                       href="{{ route('tracking') }}">
                        Track Shipment
                        <!-- Purse Bead Indicator -->
                        <span class="bead-indicator {{ Str::startsWith($currentRoute, 'tracking') ? 'bead-visible' : '' }}"></span>
                    </a>
                </li>
                
                <!-- Get a Quote -->
                <li class="nav-item position-relative">
                    <a class="nav-link hover-text-navy px-2 {{ Str::startsWith($currentRoute, 'quote') ? 'active' : '' }}" 
                       href="{{ route('quote') }}">
                        Get a Quote
                        <!-- Purse Bead Indicator -->
                        <span class="bead-indicator {{ Str::startsWith($currentRoute, 'quote') ? 'bead-visible' : '' }}"></span>
                    </a>
                </li>
                
                <!-- About Us -->
                <li class="nav-item position-relative">
                    <a class="nav-link hover-text-navy px-2 {{ Str::startsWith($currentRoute, 'about') ? 'active' : '' }}" 
                       href="{{ route('about') }}">
                        About Us
                        <!-- Purse Bead Indicator -->
                        <span class="bead-indicator {{ Str::startsWith($currentRoute, 'about') ? 'bead-visible' : '' }}"></span>
                    </a>
                </li>
                
                <!-- Contact -->
                <li class="nav-item position-relative">
                    <a class="nav-link hover-text-navy px-2 {{ Str::startsWith($currentRoute, 'contact') ? 'active' : '' }}" 
                       href="{{ route('contact') }}">
                        Contact
                        <!-- Purse Bead Indicator -->
                        <span class="bead-indicator {{ Str::startsWith($currentRoute, 'contact') ? 'bead-visible' : '' }}"></span>
                    </a>
                </li>
                
                <!-- ==================== DYNAMIC AUTH BUTTON ==================== -->
                @auth
                    <!-- Dashboard Button for Logged-in Users -->
                    <li class="nav-item ms-lg-3">
                        @if(Auth::user()->isAdminOrSuperAdmin())
                            <a class="btn btn-outline-white border-2 rounded-pill px-4" href="{{ route('admin.dashboard') }}">
                                <i class="ri-dashboard-line me-2"></i>Dashboard
                            </a>
                        @else
                            <a class="btn btn-outline-white border-2 rounded-pill px-4" href="{{ route('dashboard') }}">
                                <i class="ri-dashboard-line me-2"></i>Dashboard
                            </a>
                        @endif
                    </li>
                    
                    <!-- Optional: User Dropdown (Alternative) -->
                    {{-- 
                    <li class="nav-item dropdown ms-lg-3">
                        <a class="btn btn-outline-white border-2 rounded-pill px-4 dropdown-toggle d-flex align-items-center gap-2" 
                           href="#" 
                           role="button" 
                           data-bs-toggle="dropdown" 
                           aria-expanded="false">
                            <i class="ri-user-line"></i>
                            {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                @if(Auth::user()->isAdminOrSuperAdmin())
                                    <a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                                        <i class="ri-dashboard-line me-2"></i>Admin Dashboard
                                    </a>
                                @else
                                    <a class="dropdown-item" href="{{ route('dashboard') }}">
                                        <i class="ri-dashboard-line me-2"></i>Dashboard
                                    </a>
                                @endif
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('user.profile') }}">
                                    <i class="ri-user-line me-2"></i>My Profile
                                </a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="ri-logout-box-line me-2"></i>Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                    --}}
                @else
                    <!-- Login Button for Guests -->
                    <li class="nav-item ms-lg-3">
                        <a class="btn btn-outline-white border-2 rounded-pill px-4" href="{{ route('login') }}">
                            <i class="ri-login-box-line me-2"></i>Login
                        </a>
                    </li>
                    
                    <!-- Optional: Register Button (if you want both) -->
                    {{--
                    <li class="nav-item">
                        <a class="btn btn-white border-2 rounded-pill px-4 text-navy" href="{{ route('register') }}">
                            Sign Up
                        </a>
                    </li>
                    --}}
                @endauth
                <!-- ==================== END DYNAMIC AUTH BUTTON ==================== -->
            </ul>
        </div>
    </div>
</nav>