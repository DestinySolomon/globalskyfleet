<!-- layouts/partials/main-nav.blade.php -->
<!-- Main Navigation -->
<nav class="navbar navbar-expand-lg main-nav">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-3" href="<?php echo e(route('home')); ?>">
            <img src="<?php echo e(setting('site_logo') ? Storage::url(setting('site_logo')) : asset('images/logo.png')); ?>" alt="<?php echo e(setting('site_name', 'GlobalSkyFleet')); ?> Logo" height="80" style="width: auto; max-width: 120px; object-fit: contain;" <?php echo e(!setting('site_logo') ? 'class="default-logo"' : ''); ?>>
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <i class="ri-menu-line text-white fs-3"></i>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center gap-3 gap-lg-4">
                <?php
                    $currentRoute = Route::currentRouteName();
                ?>
                
                <!-- Home -->
                <li class="nav-item position-relative">
                    <a class="nav-link hover-text-navy px-2 <?php echo e($currentRoute == 'home' ? 'active' : ''); ?>" 
                       href="<?php echo e(route('home')); ?>">
                        Home
                        <!-- Purse Bead Indicator -->
                        <span class="bead-indicator <?php echo e($currentRoute == 'home' ? 'bead-visible' : ''); ?>"></span>
                    </a>
                </li>
                
                <!-- Services -->
                <li class="nav-item position-relative">
                    <a class="nav-link hover-text-navy px-2 <?php echo e(Str::startsWith($currentRoute, 'services') ? 'active' : ''); ?>" 
                       href="<?php echo e(route('services')); ?>">
                        Services
                        <!-- Purse Bead Indicator -->
                        <span class="bead-indicator <?php echo e(Str::startsWith($currentRoute, 'services') ? 'bead-visible' : ''); ?>"></span>
                    </a>
                </li>
                
                <!-- Track Shipment -->
                <li class="nav-item position-relative">
                    <a class="nav-link hover-text-navy px-2 <?php echo e(Str::startsWith($currentRoute, 'tracking') ? 'active' : ''); ?>" 
                       href="<?php echo e(route('tracking')); ?>">
                        Track Shipment
                        <!-- Purse Bead Indicator -->
                        <span class="bead-indicator <?php echo e(Str::startsWith($currentRoute, 'tracking') ? 'bead-visible' : ''); ?>"></span>
                    </a>
                </li>
                
                <!-- Get a Quote -->
                <li class="nav-item position-relative">
                    <a class="nav-link hover-text-navy px-2 <?php echo e(Str::startsWith($currentRoute, 'quote') ? 'active' : ''); ?>" 
                       href="<?php echo e(route('quote')); ?>">
                        Get a Quote
                        <!-- Purse Bead Indicator -->
                        <span class="bead-indicator <?php echo e(Str::startsWith($currentRoute, 'quote') ? 'bead-visible' : ''); ?>"></span>
                    </a>
                </li>
                
                <!-- About Us -->
                <li class="nav-item position-relative">
                    <a class="nav-link hover-text-navy px-2 <?php echo e(Str::startsWith($currentRoute, 'about') ? 'active' : ''); ?>" 
                       href="<?php echo e(route('about')); ?>">
                        About Us
                        <!-- Purse Bead Indicator -->
                        <span class="bead-indicator <?php echo e(Str::startsWith($currentRoute, 'about') ? 'bead-visible' : ''); ?>"></span>
                    </a>
                </li>
                
                <!-- Contact -->
                <li class="nav-item position-relative">
                    <a class="nav-link hover-text-navy px-2 <?php echo e(Str::startsWith($currentRoute, 'contact') ? 'active' : ''); ?>" 
                       href="<?php echo e(route('contact')); ?>">
                        Contact
                        <!-- Purse Bead Indicator -->
                        <span class="bead-indicator <?php echo e(Str::startsWith($currentRoute, 'contact') ? 'bead-visible' : ''); ?>"></span>
                    </a>
                </li>
                
                <!-- ==================== DYNAMIC AUTH BUTTON ==================== -->
                <?php if(auth()->guard()->check()): ?>
                    <!-- Dashboard Button for Logged-in Users -->
                    <li class="nav-item ms-lg-3">
                        <?php if(Auth::user()->isAdminOrSuperAdmin()): ?>
                            <a class="btn btn-outline-white border-2 rounded-pill px-4" href="<?php echo e(route('admin.dashboard')); ?>">
                                <i class="ri-dashboard-line me-2"></i>Dashboard
                            </a>
                        <?php else: ?>
                            <a class="btn btn-outline-white border-2 rounded-pill px-4" href="<?php echo e(route('dashboard')); ?>">
                                <i class="ri-dashboard-line me-2"></i>Dashboard
                            </a>
                        <?php endif; ?>
                    </li>
                    
                    <!-- Optional: User Dropdown (Alternative) -->
                    
                <?php else: ?>
                    <!-- Login Button for Guests -->
                    <li class="nav-item ms-lg-3">
                        <a class="btn btn-outline-white border-2 rounded-pill px-4" href="<?php echo e(route('login')); ?>">
                            <i class="ri-login-box-line me-2"></i>Login
                        </a>
                    </li>
                    
                    <!-- Optional: Register Button (if you want both) -->
                    
                <?php endif; ?>
                <!-- ==================== END DYNAMIC AUTH BUTTON ==================== -->
            </ul>
        </div>
    </div>
</nav><?php /**PATH C:\Users\Dee\Desktop\General\globalskyfleet\resources\views/layouts/partials/main-nav.blade.php ENDPATH**/ ?>