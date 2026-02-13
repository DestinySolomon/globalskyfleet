<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Add user ID for private channels -->
    <meta name="user-id" content="{{ Auth::id() }}">
    
    <title>Admin Dashboard - {{ config('app.name', 'GlobalSkyFleet') }}</title>
    
    <!-- Favicon -->
    @if(setting('site_favicon'))
        <link rel="icon" type="image/x-icon" href="{{ Storage::url(setting('site_favicon')) }}">
    @else
        <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    @endif
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Remix Icon -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.1.0/fonts/remixicon.css" rel="stylesheet">
    
    <!-- Custom Admin CSS -->
    <style>
        :root {
            --sidebar-width: 250px;
            --primary-color: #0a2463;
            --secondary-color: #f97316;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
            background-color: #f8fafc;
            overflow-x: hidden;
        }
        
        /* Sidebar */
        .admin-sidebar {
            width: var(--sidebar-width);
            background: white;
            border-right: 1px solid #e5e7eb;
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
            z-index: 1000;
            transition: transform 0.3s ease;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        
        .admin-main {
            margin-left: var(--sidebar-width);
            transition: margin-left 0.3s ease;
        }

        /* Add this CSS rule for sidebar menu hover effect */
.sidebar-menu a:not(.bg-primary):hover {
    background-color: #f1f5f9;
    transform: translateX(3px);
    transition: all 0.2s ease;
}

/* Ensure the active menu item doesn't get the hover effect */
.sidebar-menu a.bg-primary:hover {
    background-color: var(--primary-color) !important;
    transform: none;
}
        
        /* Mobile responsive */
        @media (max-width: 992px) {
            .admin-sidebar {
                transform: translateX(-100%);
            }
            
            .admin-sidebar.show {
                transform: translateX(0);
            }
            
            .admin-main {
                margin-left: 0;
            }
            
            .sidebar-overlay {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0,0,0,0.5);
                z-index: 999;
                display: none;
            }
            
            .sidebar-overlay.show {
                display: block;
            }
        }
        
        /* Stats cards responsive */
        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr) !important;
            }
        }
        
        @media (max-width: 576px) {
            .stats-grid {
                grid-template-columns: 1fr !important;
            }
            
            .table-responsive {
                font-size: 14px;
            }
            
            .btn-group-sm .btn {
                padding: 0.25rem 0.5rem;
                font-size: 0.875rem;
            }
        }
        
        /* Table responsive */
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        
        /* Ensure all tables are responsive */
        table {
            min-width: 768px; /* Minimum width before scrolling */
        }
        
        @media (max-width: 768px) {
            table {
                min-width: 100%;
            }
            
            .card-body {
                padding: 1rem;
            }
            
            .card-header {
                padding: 0.75rem 1rem;
            }
        }

        /* Enhanced User Dropdown */
        .user-dropdown-menu {
            width: 280px;
            padding: 0;
            border: 1px solid #e5e7eb;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        
        .user-dropdown-header {
            padding: 1rem;
            background: linear-gradient(135deg, var(--primary-color), #1e40af);
            color: white;
            border-radius: 0.375rem 0.375rem 0 0;
        }
        
        .notification-dropdown-menu {
            width: 350px;
            max-height: 400px;
            overflow-y: auto;
        }
        
        .notification-item.unread {
            background-color: rgba(10, 36, 99, 0.05);
            border-left: 3px solid var(--primary-color);
        }
        
        .notification-item:hover {
            background-color: #f8fafc;
        }
        
        .notification-badge {
            font-size: 10px;
            padding: 2px 6px;
        }
        
        .hover-bg-light:hover {
            background-color: #f8fafc;
        }
        
        /* User Avatar */
        .user-avatar {
            width: 44px;
            height: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            background: linear-gradient(135deg, var(--primary-color), #1e40af);
            color: white;
            font-size: 16px;
        }
        
        .user-avatar-sm {
            width: 36px;
            height: 36px;
            font-size: 14px;
        }
        
        /* Animation for notifications */
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }
        
        .notification-pulse {
            animation: pulse 2s infinite;
        }
        
        /* Priority colors for notifications */
        .bg-primary-subtle {
            background-color: rgba(10, 36, 99, 0.1) !important;
        }
        
        .bg-warning-subtle {
            background-color: rgba(245, 158, 11, 0.1) !important;
        }
        
        .bg-danger-subtle {
            background-color: rgba(239, 68, 68, 0.1) !important;
        }
        
        .bg-info-subtle {
            background-color: rgba(59, 130, 246, 0.1) !important;
        }
        
        .text-primary {
            color: var(--primary-color) !important;
        }
        
        .text-warning {
            color: var(--warning-color) !important;
        }
        
        .text-danger {
            color: var(--danger-color) !important;
        }
        
        .text-info {
            color: #3b82f6 !important;
        }


/* Loading Overlay Styles */
.loading-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, #0a2463 0%, #1e40af 100%);
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
}

.loading-container {
    text-align: center;
    color: white;
    max-width: 100%;
    width: 100%;
    height: 100%;
    position: relative;
}

/* Flying Plane Animation */
.plane-container {
    position: absolute;
    left: 0;
    top: 50%;
    transform: translateY(-50%);
    width: 100%;
    height: 100px;
    pointer-events: none;
}

.plane-icon {
    font-size: 60px;
    color: white;
    position: absolute;
    left: -100px; /* Start off-screen left */
    transform: translateY(-50%) rotate(90deg); /* Rotate 90deg to face right */
    animation: flyAcross 3s cubic-bezier(0.4, 0, 0.2, 1) forwards;
    filter: drop-shadow(0 0 10px rgba(255, 255, 255, 0.3));
}

.plane-trail {
    position: absolute;
    left: -100px; /* Start with the plane */
    top: 50%;
    transform: translateY(-50%);
    width: 100px;
    height: 3px;
    background: linear-gradient(90deg, 
        transparent 0%,
        rgba(255, 255, 255, 0.8) 10%,
        rgba(255, 255, 255, 0.4) 40%,
        rgba(255, 255, 255, 0.1) 70%,
        transparent 100%);
    border-radius: 50%;
    animation: trailAcross 3s cubic-bezier(0.4, 0, 0.2, 1) forwards;
    opacity: 0.8;
}

.plane-clouds {
    position: absolute;
    width: 100%;
    height: 100%;
    top: 0;
    left: 0;
    pointer-events: none;
}

.cloud {
    position: absolute;
    background: rgba(255, 255, 255, 0.15);
    border-radius: 50%;
    opacity: 0;
    animation: cloudFade 3s ease-out forwards;
}

.cloud-1 {
    width: 80px;
    height: 40px;
    top: 30%;
    left: 30%;
    animation-delay: 0.5s;
}

.cloud-2 {
    width: 120px;
    height: 60px;
    bottom: 40%;
    left: 50%;
    animation-delay: 1s;
}

.cloud-3 {
    width: 60px;
    height: 30px;
    top: 60%;
    left: 70%;
    animation-delay: 1.5s;
}

/* Loading Text - Appears after plane flies */
.loading-text {
    position: absolute;
    bottom: 30%;
    left: 0;
    width: 100%;
    text-align: center;
    opacity: 0;
    animation: fadeInText 0.5s ease-out 3s forwards;
}

.loading-dots {
    display: block;
    font-size: 28px;
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.dot {
    opacity: 0;
    animation: dotPulse 1.4s infinite;
}

.dot:nth-child(2) {
    animation-delay: 0.2s;
}

.dot:nth-child(3) {
    animation-delay: 0.4s;
}

.dot:nth-child(4) {
    animation-delay: 0.6s;
}

.loading-subtext {
    color: rgba(255, 255, 255, 0.8);
    font-size: 16px;
    margin-top: 0.5rem;
}

/* Progress Bar */
.loading-progress {
    position: absolute;
    bottom: 20%;
    left: 50%;
    transform: translateX(-50%);
    width: 300px;
    height: 4px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 2px;
    overflow: hidden;
    opacity: 0;
    animation: fadeInProgress 0.5s ease-out 3.2s forwards;
}

.progress-bar {
    height: 100%;
    width: 0%;
    background: linear-gradient(90deg, #f97316, #fb923c);
    animation: progressLoad 2s ease-in-out 3.2s infinite;
    border-radius: 2px;
}

/* Animations */
@keyframes flyAcross {
    0% {
        left: -100px;
        transform: translateY(-50%) rotate(90deg); /* Facing right */
        opacity: 1;
    }
    80% {
        left: calc(100% - 50px);
        opacity: 1;
        transform: translateY(-50%) rotate(90deg); /* Still facing right */
    }
    100% {
        left: calc(100% + 100px);
        opacity: 0;
        transform: translateY(-50%) rotate(90deg); /* Exit still facing right */
    }
}

@keyframes trailAcross {
    0% {
        left: -150px;
        opacity: 0;
        width: 0;
    }
    20% {
        opacity: 0.8;
        width: 100px;
    }
    80% {
        left: calc(100% - 50px);
        opacity: 0.8;
        width: 100px;
    }
    100% {
        left: calc(100% + 50px);
        opacity: 0;
        width: 0;
    }
}

@keyframes cloudFade {
    0% {
        opacity: 0;
        transform: scale(0.8);
    }
    20% {
        opacity: 0.4;
        transform: scale(1);
    }
    80% {
        opacity: 0.4;
        transform: scale(1);
    }
    100% {
        opacity: 0;
        transform: scale(1.2);
    }
}

@keyframes fadeInText {
    0% {
        opacity: 0;
        transform: translateY(20px);
    }
    100% {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes fadeInProgress {
    0% {
        opacity: 0;
        transform: translateX(-50%) translateY(20px);
    }
    100% {
        opacity: 1;
        transform: translateX(-50%) translateY(0);
    }
}

@keyframes dotPulse {
    0%, 100% {
        opacity: 0;
    }
    50% {
        opacity: 1;
    }
}

@keyframes progressLoad {
    0% {
        width: 0%;
        transform: translateX(-100%);
    }
    50% {
        width: 100%;
        transform: translateX(0%);
    }
    100% {
        width: 0%;
        transform: translateX(100%);
    }
}

/* Hide overlay when loading is complete */
.loading-overlay.hidden {
    opacity: 0;
    pointer-events: none;
    transition: opacity 0.5s ease;
}

.loading-overlay.hidden .plane-icon,
.loading-overlay.hidden .plane-trail,
.loading-overlay.hidden .loading-text,
.loading-overlay.hidden .loading-progress {
    animation: none;
}
    </style>
    
    <!-- Pusher & Echo CDN (Optional - remove if not using real-time) -->
    <script src="https://js.pusher.com/7.2/pusher.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.11.3/dist/echo.iife.js"></script>
</head>
<body>

<!-- Inline script to define toggleSidebar immediately -->
<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        if (sidebar) sidebar.classList.toggle('show');
        if (overlay) overlay.classList.toggle('show');
    }
    function closeSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        if (sidebar) sidebar.classList.remove('show');
        if (overlay) overlay.classList.remove('show');
    }
    window.toggleSidebar = toggleSidebar;
    window.closeSidebar = closeSidebar;
</script>

<!-- Loading Overlay with Plane Animation -->
<div class="loading-overlay" id="loadingOverlay">
    <div class="loading-container">
        <!-- Plane Icon with Animation -->
        <div class="plane-container">
            <i class="ri-plane-line plane-icon"></i>
            <div class="plane-trail"></div>
            <div class="plane-clouds">
                <div class="cloud cloud-1"></div>
                <div class="cloud cloud-2"></div>
                <div class="cloud cloud-3"></div>
            </div>
        </div>
        
        <!-- Loading Text -->
        <div class="loading-text">
            <span class="loading-dots">
                <span>Loading</span>
                <span class="dot">.</span>
                <span class="dot">.</span>
                <span class="dot">.</span>
            </span>
            <p class="loading-subtext">GlobalSkyFleet is preparing your shipment</p>
        </div>
        
        <!-- Progress Bar (Optional) -->
        <div class="loading-progress">
            <div class="progress-bar"></div>
        </div>
    </div>
</div>

    <!-- Sidebar Overlay for Mobile -->
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>
    
    <!-- Sidebar -->
    <aside class="admin-sidebar" id="sidebar">
        <div class="sidebar-header border-bottom p-3">
            <div class="d-flex align-items-center">
                @if(setting('site_logo'))
                    <img src="{{ Storage::url(setting('site_logo')) }}" alt="{{ setting('site_name', 'GlobalSkyFleet') }}" style="height: 60px; width: auto; max-width: 100px; object-fit: contain;">
                @else
                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" 
                         style="width: 60px; height: 60px; font-weight: bold; font-size: 24px;">
                        GS
                    </div>
                @endif
                <div class="ms-3">
                    <h6 class="mb-0 fw-bold text-primary">{{ setting('site_name', 'GlobalSkyFleet') }}</h6>
                    <small class="text-muted">Admin Panel</small>
                </div>
            </div>
        </div>
        
        <div class="sidebar-menu p-3" style="height: calc(100vh - 80px); overflow-y: auto;">
            <div class="mb-4">
                <small class="text-muted text-uppercase fw-bold d-block mb-2">Dashboard</small>
                <a href="{{ route('admin.dashboard') }}" 
                   class="d-flex align-items-center py-2 px-3 rounded text-decoration-none mb-2 
                          {{ request()->routeIs('admin.dashboard') ? 'bg-primary text-white' : 'text-dark hover-bg-light' }}">
                    <i class="ri-dashboard-line me-3"></i>
                    <span>Dashboard</span>
                </a>
            </div>
            
            <div class="mb-4">
                <small class="text-muted text-uppercase fw-bold d-block mb-2">Shipments</small>
                <a href="{{ route('admin.shipments') }}" 
                   class="d-flex align-items-center py-2 px-3 rounded text-decoration-none mb-2 
                          {{ request()->routeIs('admin.shipments*') ? 'bg-primary text-white' : 'text-dark hover-bg-light' }}">
                    <i class="ri-ship-line me-3"></i>
                    <span>All Shipments</span>
                </a>
            </div>
            
            <div class="mb-4">
                <small class="text-muted text-uppercase fw-bold d-block mb-2">Users</small>
                <a href="{{ route('admin.users') }}" 
                   class="d-flex align-items-center py-2 px-3 rounded text-decoration-none mb-2 
                          {{ request()->routeIs('admin.users*') ? 'bg-primary text-white' : 'text-dark hover-bg-light' }}">
                    <i class="ri-user-line me-3"></i>
                    <span>User Management</span>
                </a>
            </div>
            
           <!-- CONTACT MESSAGES SECTION -->
<div class="mb-4">
    <small class="text-muted text-uppercase fw-bold d-block mb-2">Communications</small>
    <a href="{{ route('admin.contact-messages.index') }}" 
       class="d-flex align-items-center py-2 px-3 rounded text-decoration-none mb-2 
              {{ request()->routeIs('admin.contact-messages*') ? 'bg-primary text-white' : 'text-dark hover-bg-light' }}">
        <i class="ri-mail-line me-3"></i>
        <span>Contact Messages</span>
        @php
            // This will now work for both admin and super_admin
            $unreadContactCount = \App\Models\ContactMessage::where('status', 'unread')->count();
        @endphp
        @if($unreadContactCount > 0)
            <span class="ms-auto badge bg-danger rounded-pill">{{ $unreadContactCount }}</span>
        @endif
    </a>




<!-- In your admin layout sidebar -->
 <a href="{{ route('admin.chat.index') }}" 
   class="d-flex align-items-center py-2 px-3 rounded text-decoration-none mb-2 
          {{ request()->routeIs('admin.chat.*') ? 'bg-primary text-white' : 'text-dark hover-bg-light' }}">
    <i class="ri-chat-3-line me-3"></i>
    <span>Live Chat Support</span>

</a>
</div> 


            
            <div class="mb-4">
                <small class="text-muted text-uppercase fw-bold d-block mb-2">Documents</small>
                <a href="{{ route('admin.documents') }}" 
                   class="d-flex align-items-center py-2 px-3 rounded text-decoration-none mb-2 
                          {{ request()->routeIs('admin.documents*') ? 'bg-primary text-white' : 'text-dark hover-bg-light' }}">
                    <i class="ri-file-text-line me-3"></i>
                    <span>Document Management</span>
                </a>
            </div>
            
            <div class="mb-4">
                <small class="text-muted text-uppercase fw-bold d-block mb-2">Payments</small>
                <a href="{{ route('admin.payments.crypto') }}" 
                   class="d-flex align-items-center py-2 px-3 rounded text-decoration-none mb-2 
                          {{ request()->routeIs('admin.payments.crypto') ? 'bg-primary text-white' : 'text-dark hover-bg-light' }}">
                    <i class="ri-currency-line me-3"></i>
                    <span>Crypto Payments</span>
                </a>
                
                <a href="{{ route('admin.wallets') }}" 
                   class="d-flex align-items-center py-2 px-3 rounded text-decoration-none mb-2 
                          {{ request()->routeIs('admin.wallets*') ? 'bg-primary text-white' : 'text-dark hover-bg-light' }}">
                    <i class="ri-wallet-line me-3"></i>
                    <span>Wallet Management</span>
                </a>
            </div>
            
            <div class="mb-4">
                <small class="text-muted text-uppercase fw-bold d-block mb-2">Analytics</small>
                <a href="{{ route('admin.analytics') }}" 
                   class="d-flex align-items-center py-2 px-3 rounded text-decoration-none mb-2 
                          {{ request()->routeIs('admin.analytics*') ? 'bg-primary text-white' : 'text-dark hover-bg-light' }}">
                    <i class="ri-line-chart-line me-3"></i>
                    <span>Analytics & Reports</span>
                </a>
            </div>

            <div class="mb-4">
                <small class="text-muted text-uppercase fw-bold d-block mb-2">Settings</small>
                <a href="{{ route('admin.settings.index') }}" 
                   class="d-flex align-items-center py-2 px-3 rounded text-decoration-none mb-2 
                          {{ request()->routeIs('admin.settings*') ? 'bg-primary text-white' : 'text-dark hover-bg-light' }}">
                    <i class="ri-settings-3-line me-3"></i>
                    <span>Settings</span>
                </a>
            </div>
            
            <div class="mb-4">
                <small class="text-muted text-uppercase fw-bold d-block mb-2">Navigation</small>
                <!-- Removed Public Homepage link from sidebar as it's now in user dropdown -->
                
                <form action="{{ route('logout') }}" method="POST" class="d-inline w-100">
                    @csrf
                    <button type="submit" 
                            class="d-flex align-items-center py-2 px-3 rounded text-decoration-none w-100 
                                   border-0 bg-transparent text-dark hover-bg-light">
                        <i class="ri-logout-box-line me-3"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="admin-main">

<!-- Topbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
    <div class="container-fluid">
        <button class="navbar-toggler d-lg-none me-2" type="button" onclick="toggleSidebar()">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="d-flex justify-content-between w-100 align-items-center">
            <h4 class="mb-0 d-none d-md-block">@yield('page-title', 'Admin Dashboard')</h4>
            <h5 class="mb-0 d-md-none">@yield('page-title', 'Dashboard')</h5>
            
            <div class="d-flex align-items-center gap-3">
                <!-- SIMPLIFIED NOTIFICATION BELL -->
                @include('partials.notification-bell')
                
                <!-- User Dropdown -->
                <div class="dropdown">
                    <button class="btn btn-light rounded-circle p-0 d-flex align-items-center justify-content-center border-0" 
                            type="button" style="width: 44px; height: 44px;" 
                            data-bs-toggle="dropdown" aria-expanded="false">
                        @if(Auth::user()->profile_picture)
                            <img src="{{ Storage::url('profile-pictures/' . Auth::user()->profile_picture) }}" 
                                 alt="{{ Auth::user()->name }}" 
                                 class="rounded-circle w-100 h-100 object-fit-cover">
                        @else
                            <div class="user-avatar rounded-circle">
                                {{ Auth::user()->initials }}
                            </div>
                        @endif
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end user-dropdown-menu">
                        <li class="user-dropdown-header">
                            <div class="d-flex align-items-center">
                                @if(Auth::user()->profile_picture)
                                    <img src="{{ Storage::url('profile-pictures/' . Auth::user()->profile_picture) }}" 
                                         alt="{{ Auth::user()->name }}" 
                                         class="rounded-circle me-3" 
                                         style="width: 50px; height: 50px; object-fit: cover;">
                                @else
                                    <div class="user-avatar rounded-circle me-3">
                                        {{ Auth::user()->initials }}
                                    </div>
                                @endif
                                <div>
                                    <h6 class="mb-0">{{ Auth::user()->name }}</h6>
                                    <small class="text-white-80">{{ Auth::user()->email }}</small>
                                    <div class="mt-1">
                                        <span class="badge bg-light text-primary">
                                            {{ Auth::user()->role->name ?? 'Administrator' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="p-3">
                            <div class="row g-2">
                                <div class="col-6">
                                    <a href="{{ route('admin.profile.edit') }}" class="text-decoration-none">
                                        <div class="text-center p-2 rounded hover-bg-light">
                                            <i class="ri-user-line fs-4 text-primary mb-2"></i>
                                            <div class="small">Profile</div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-6">
                                    <a href="{{ route('admin.settings.index') }}" class="text-decoration-none">
                                        <div class="text-center p-2 rounded hover-bg-light">
                                            <i class="ri-settings-3-line fs-4 text-primary mb-2"></i>
                                            <div class="small">Settings</div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-6">
                                    <a href="{{ route('home') }}" class="text-decoration-none">
                                        <div class="text-center p-2 rounded hover-bg-light">
                                            <i class="ri-home-3-line fs-4 text-primary mb-2"></i>
                                            <div class="small">Website</div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-6">
                                    <a href="{{ route('admin.notifications.index') }}" class="text-decoration-none">
                                        <div class="text-center p-2 rounded hover-bg-light">
                                            <i class="ri-notification-3-line fs-4 text-primary mb-2"></i>
                                            <div class="small">Notifications</div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </li>
                        <li><hr class="dropdown-divider my-0"></li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center py-2" href="{{ route('admin.profile.edit') }}">
                                <i class="ri-user-settings-line me-2"></i>
                                <span>Edit Profile</span>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center py-2" href="{{ route('admin.profile.change-password') }}">
                                <i class="ri-lock-password-line me-2"></i>
                                <span>Change Password</span>
                            </a>
                        </li>
                        <li><hr class="dropdown-divider my-0"></li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST" class="m-0">
                                @csrf
                                <button type="submit" class="dropdown-item d-flex align-items-center py-2 text-danger">
                                    <i class="ri-logout-box-r-line me-2"></i>
                                    <span>Logout</span>
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</nav>

        <!-- Content Area -->
        <div class="content-area p-3 p-md-4">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            @yield('content')
        </div>
    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JavaScript for Notifications -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Show loading overlay initially
            const loadingOverlay = document.getElementById('loadingOverlay');
            
            // Hide loading overlay when page is fully loaded
            window.addEventListener('load', function() {
                setTimeout(function() {
                    loadingOverlay.classList.add('hidden');
                    setTimeout(function() {
                        loadingOverlay.style.display = 'none';
                    }, 700);
                }, 900); // Small delay to ensure everything is ready
            });
            
            // Mobile sidebar toggle
            function toggleSidebar() {
                const sidebar = document.getElementById('sidebar');
                const overlay = document.getElementById('sidebarOverlay');
                sidebar.classList.toggle('show');
                overlay.classList.toggle('show');
            }
            
            function closeSidebar() {
                const sidebar = document.getElementById('sidebar');
                const overlay = document.getElementById('sidebarOverlay');
                sidebar.classList.remove('show');
                overlay.classList.remove('show');
            }
            
            // Auto-close sidebar on mobile when clicking a link
            document.querySelectorAll('.admin-sidebar a').forEach(link => {
                link.addEventListener('click', function() {
                    if (window.innerWidth < 992) {
                        closeSidebar();
                    }
                });
            });
            
            // Close sidebar when window is resized above mobile breakpoint
            window.addEventListener('resize', function() {
                if (window.innerWidth >= 992) {
                    closeSidebar();
                }
            });
            
            // Expose functions to global scope for onclick attributes
            window.toggleSidebar = toggleSidebar;
            window.closeSidebar = closeSidebar;
            
            // Simple notification polling (update badge every 30 seconds)
            function updateNotificationBadge() {
                fetch('{{ route("admin.notifications.count") }}')
                    .then(response => response.json())
                    .then(data => {
                        const badge = document.getElementById('notificationBadge');
                        const bell = document.getElementById('notificationBell');
                        
                        if (data.count > 0) {
                            if (!badge) {
                                // Create badge if it doesn't exist
                                const newBadge = document.createElement('span');
                                newBadge.id = 'notificationBadge';
                                newBadge.className = 'position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger';
                                newBadge.style.cssText = 'font-size: 10px; padding: 2px 6px;';
                                newBadge.textContent = data.count;
                                bell.appendChild(newBadge);
                            } else {
                                // Update existing badge
                                badge.textContent = data.count;
                            }
                        } else if (badge) {
                            // Remove badge if count is 0
                            badge.remove();
                        }
                    })
                    .catch(error => console.error('Error fetching notification count:', error));
            }
            
            // Update badge on page load
            updateNotificationBadge();
            
            // Poll for new notifications every 30 seconds
            setInterval(updateNotificationBadge, 30000);
            
            // Handle mark all as read button
            const markAllBtn = document.getElementById('markAllAsReadBtn');
            if (markAllBtn) {
                markAllBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    fetch('{{ route("admin.notifications.read.all") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Remove badge
                            const badge = document.getElementById('notificationBadge');
                            if (badge) badge.remove();
                            
                            // Remove unread styles from notifications
                            document.querySelectorAll('.notification-item.unread').forEach(item => {
                                item.classList.remove('unread');
                            });
                            
                            // Hide mark all as read button
                            markAllBtn.remove();
                        }
                    })
                    .catch(error => console.error('Error:', error));
                });
            }
        });
    </script>
    
    <!-- Auto-detect and save user timezone -->
    <script>
    console.log('⏰ TIMEZONE SCRIPT LOADED');
    
    document.addEventListener('DOMContentLoaded', function() {
        console.log('⏰ DOMContentLoaded fired');
        
        // Get the user's timezone from browser
        const userTimezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
        console.log('🌍 Detected browser timezone:', userTimezone);
        
        // Check if we need to save it (only if user is authenticated)
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
        
        if (!csrfToken) {
            console.log('⚠️ Not authenticated, skipping timezone detection');
            return;
        }
        
        if (!userTimezone) {
            console.error('❌ Could not detect user timezone');
            return;
        }
        
        // Try to get current timezone setting
        fetch('/api/timezone', {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            console.log('📍 Current saved timezone:', data.timezone);
            console.log('🔍 Detected timezone:', userTimezone);
            
            // If no timezone is set or it's the default, update it
            if (!data.timezone || data.timezone === 'Europe/Berlin' || data.timezone === 'UTC') {
                console.log('💾 Saving detected timezone...');
                
                // Save the detected timezone
                fetch('/api/timezone/detect', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({ timezone: userTimezone })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        console.log('✅ Timezone saved successfully:', data.timezone);
                        console.log('🕐 Current time in your timezone:', data.current_time);
                        // Reload page to apply new timezone
                        setTimeout(() => location.reload(), 500);
                    } else {
                        console.error('❌ Error saving timezone:', data.message);
                    }
                })
                .catch(error => {
                    console.error('❌ Error saving timezone:', error);
                });
            } else if (data.timezone === userTimezone) {
                console.log('✅ Timezone already correct:', data.timezone);
            } else {
                console.log('⚠️ Timezone mismatch - saved:', data.timezone, 'detected:', userTimezone);
            }
        })
        .catch(error => {
            console.error('❌ Error checking timezone:', error);
        });
    });
    </script>
    
    @stack('scripts')
</body>
</html>