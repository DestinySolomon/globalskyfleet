<!-- Main Navigation -->
<nav class="navbar navbar-expand-lg main-nav">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-3" href="{{ route('home') }}">
            <img src="{{ asset('images/logo.png') }}" alt="GlobalSkyFleet Logo" height="48">
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <i class="ri-menu-line text-white fs-3"></i>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center gap-3 gap-lg-4">
                <li class="nav-item">
                    <a class="nav-link hover-text-navy {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link hover-text-navy {{ request()->routeIs('services*') ? 'active' : '' }}" href="{{ route('services') }}">Services</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link hover-text-navy {{ request()->routeIs('tracking*') ? 'active' : '' }}" href="{{ route('tracking') }}">Track Shipment</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link hover-text-navy {{ request()->routeIs('quote*') ? 'active' : '' }}" href="{{ route('quote') }}">Get a Quote</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link hover-text-navy {{ request()->routeIs('about*') ? 'active' : '' }}" href="{{ route('about') }}">About Us</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link hover-text-navy {{ request()->routeIs('contact*') ? 'active' : '' }}" href="{{ route('contact') }}">Contact</a>
                </li>
                <li class="nav-item ms-lg-3">
                    <a class="btn btn-outline-white border-2 rounded-pill px-4" href="{{ route('login') }}">Login</a>
                </li>
            </ul>
        </div>
    </div>
</nav>