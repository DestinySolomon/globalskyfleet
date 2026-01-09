<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'GlobalSkyFleet'))</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- Icons -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Styles -->
    <style>
        :root {
            --sidebar-width: 260px;
            --sidebar-collapsed-width: 70px;
            --topbar-height: 70px;
            --primary-color: #1e40af;
            --secondary-color: #f97316;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --navy: #0a2463;
            --navy-light: #1a3375;
            --light-color: #f8fafc;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Figtree', sans-serif;
            background-color: #f5f7fb;
            overflow-x: hidden;
        }
        
        .dashboard-wrapper {
            display: flex;
            min-height: 100vh;
        }
        
        /* Sidebar Styles */
        .sidebar {
            width: var(--sidebar-width);
            background: var(--navy);
            border-right: 1px solid rgba(255,255,255,0.1);
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
            z-index: 1050;
            transition: all 0.3s ease;
            box-shadow: 2px 0 15px rgba(0,0,0,0.1);
        }
        
        .sidebar.collapsed {
            transform: translateX(-100%);
        }
        
        .sidebar-header {
            padding: 1.5rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            display: flex;
            align-items: center;
            gap: 0.75rem;
            height: var(--topbar-height);
        }
        
        .sidebar-logo {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #ffffff, #e2e8f0);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--navy);
            font-weight: bold;
            font-size: 1.25rem;
        }
        
        .sidebar-brand {
            font-size: 1.25rem;
            font-weight: 700;
            color: white;
        }
        
        .sidebar-menu {
            padding: 1.5rem 0;
            overflow-y: auto;
            max-height: calc(100vh - var(--topbar-height));
        }
        
        .menu-item {
            padding: 0.75rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            transition: all 0.2s;
            margin: 0.25rem 0.75rem;
            border-radius: 8px;
        }
        
        .menu-item:hover {
            background-color: var(--navy-light);
            color: white;
        }
        
        .menu-item.active {
            background-color: var(--navy-light);
            color: white;
            font-weight: 500;
        }
        
        .menu-item i {
            font-size: 1.25rem;
        }
        
        .menu-label {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: rgba(255,255,255,0.5);
            padding: 0.5rem 1.5rem;
            margin-top: 1rem;
        }
        
        /* Main Content */
        .main-content {
            flex: 1;
            margin-left: var(--sidebar-width);
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
        }
        
        .main-content.expanded {
            margin-left: 0;
        }
        
        /* Topbar */
        .topbar {
            height: var(--topbar-height);
            background: white;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 1.5rem;
            position: sticky;
            top: 0;
            z-index: 1040;
        }
        
        .topbar-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .sidebar-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 1.5rem;
            color: #64748b;
            cursor: pointer;
            padding: 0.5rem;
            margin-right: 0.5rem;
        }
        
        .page-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: #1e293b;
            margin: 0;
        }
        
        .topbar-right {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .notification-btn {
            position: relative;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f8fafc;
            border: 1px solid #e5e7eb;
            color: #64748b;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .notification-btn:hover {
            background: #f1f5f9;
            color: #1e40af;
        }
        
        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: var(--danger-color);
            color: white;
            font-size: 0.75rem;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg, #1e40af, #3b82f6);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }
        
        .dropdown-toggle {
            background: none;
            border: none;
            padding: 0;
        }
        
        .dropdown-toggle::after {
            display: none;
        }
        
        /* Content Area */
        .content-area {
            padding: 1.5rem;
            flex: 1;
        }
        
        /* Dropdowns */
        .dropdown-menu {
            border: none;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            border-radius: 12px;
            padding: 0.5rem;
            min-width: 200px;
        }
        
        .dropdown-item {
            padding: 0.75rem 1rem;
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            color: #475569;
            transition: all 0.2s;
        }
        
        .dropdown-item:hover {
            background-color: #f1f5f9;
            color: #1e40af;
        }
        
        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            border: 1px solid #e5e7eb;
            transition: all 0.3s;
        }
        
        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        
        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }
        
        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: #1e293b;
            line-height: 1;
        }
        
        .stat-label {
            color: #64748b;
            font-size: 0.875rem;
            margin-top: 0.5rem;
        }
        
        /* Shipments Table */
        .shipments-table {
            background: white;
            border-radius: 12px;
            border: 1px solid #e5e7eb;
            overflow: hidden;
        }

        .user-avatar {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: linear-gradient(135deg, #1e40af, #3b82f6);
    color: white;  /* This should make text visible, but might not be working */
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
}

        /* Avatar image styles */
        .user-avatar-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
            display: block;
        }
        
        .table-header {
            padding: 1.5rem;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        
        .table {
            width: 100%;
            border-collapse: collapse;
            min-width: 800px;
        }
        
        .table th {
            padding: 1rem 1.5rem;
            text-align: left;
            font-weight: 600;
            color: #475569;
            background: #f8fafc;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .table td {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #e5e7eb;
            color: #475569;
        }
        
        .table tr:hover {
            background: #f8fafc;
        }
        
        /* Status Badges */
        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
        }
        
        .status-pending { background: #fef3c7; color: #92400e; }
        .status-processing { background: #dbeafe; color: #1e40af; }
        .status-in-transit { background: #d1fae5; color: #065f46; }
        .status-delivered { background: #dcfce7; color: #166534; }
        .status-out-for-delivery { background: #fef3c7; color: #92400e; }
        .status-cancelled { background: #fee2e2; color: #991b1b; }
        
        /* Progress Bar */
        .progress {
            height: 6px;
            background: #e5e7eb;
            border-radius: 3px;
            overflow: hidden;
        }
        
        .progress-bar {
            height: 100%;
            background: linear-gradient(90deg, #3b82f6, #1e40af);
            border-radius: 3px;
        }
        
        /* Mobile Overlay */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.5);
            z-index: 1040;
        }
        
        .sidebar-overlay.active {
            display: block;
        }
        
        /* Responsive */
        @media (max-width: 1200px) {
            .content-area {
                padding: 1.25rem;
            }
        }
        
        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0 !important;
                width: 100%;
            }
            
            .sidebar-toggle {
                display: block;
            }
            
            .sidebar-overlay {
                display: none;
            }
            
            .sidebar-overlay.active {
                display: block;
            }
            
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 1rem;
            }
        }
        
        @media (max-width: 768px) {
            .content-area {
                padding: 1rem;
            }
            
            .topbar {
                padding: 0 1rem;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .table th, .table td {
                padding: 0.75rem;
                font-size: 0.875rem;
            }
            
            .status-badge {
                font-size: 0.75rem;
                padding: 0.2rem 0.5rem;
            }
            
            .table-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }
            
            .table-header a {
                align-self: flex-end;
            }
            
            .page-title {
                font-size: 1.25rem;
            }
            
            .topbar-right {
                gap: 0.75rem;
            }
        }
        
        @media (max-width: 576px) {
            .content-area {
                padding: 0.75rem;
            }
            
            .topbar {
                padding: 0 0.75rem;
                height: 60px;
            }
            
            .sidebar-toggle {
                font-size: 1.25rem;
                margin-right: 0.25rem;
            }
            
            .notification-btn, .user-avatar {
                width: 35px;
                height: 35px;
            }
            
            .user-avatar {
                font-size: 0.875rem;
            }
            
            .dropdown-menu {
                min-width: 180px;
                font-size: 0.875rem;
            }
            
            .stat-card {
                padding: 1.25rem 1rem;
            }
            
            .stat-value {
                font-size: 1.75rem;
            }
            
            .stat-icon {
                width: 40px;
                height: 40px;
                font-size: 1.25rem;
            }
            
            .table-responsive {
                margin: -0.75rem;
                width: calc(100% + 1.5rem);
            }
            
            .btn {
                padding: 0.375rem 0.75rem;
                font-size: 0.875rem;
            }
        }
        
        @media (max-width: 400px) {
            .topbar {
                padding: 0 0.5rem;
            }
            
            .page-title {
                font-size: 1.1rem;
            }
            
            .notification-btn, .user-avatar {
                width: 32px;
                height: 32px;
            }
            
            .notification-btn i, .user-avatar {
                font-size: 0.875rem;
            }
            
            .content-area {
                padding: 0.5rem;
            }
        }
        
        /* Fix for logout button in sidebar */
        .menu-item button {
            background: transparent;
            border: none;
            color: inherit;
            font: inherit;
            width: 100%;
            text-align: left;
            padding: 0;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }


        /* Enhanced Active State */
.menu-item.active {
    background-color: var(--navy-light);
    color: white;
    font-weight: 500;
    position: relative;
}

.menu-item.active::before {
    content: '';
    position: absolute;
    left: 0;
    top: 50%;
    transform: translateY(-50%);
    width: 4px;
    height: 60%;
    background: white;
    border-radius: 0 2px 2px 0;
}

.menu-item.active i {
    color: white;
}
        
        /* Ensure proper spacing for the entire app */
        .container-fluid {
            padding-left: 0;
            padding-right: 0;
        }



        /* ==================== FLOATING HOME BUTTON ==================== */
.floating-home-btn {
    position: fixed;
    bottom: 30px;
    right: 30px;
    width: 56px;
    height: 56px;
    background: linear-gradient(135deg, #1e40af, #3b82f6);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
    text-decoration: none;
    box-shadow: 0 6px 20px rgba(30, 64, 175, 0.3);
    transition: all 0.3s ease;
    z-index: 1050;
    animation: floatAnimation 3s ease-in-out infinite;
}

.floating-home-btn:hover {
    background: linear-gradient(135deg, #1e3a8a, #2563eb);
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(30, 64, 175, 0.4);
    animation-play-state: paused;
}

.floating-home-tooltip {
    position: absolute;
    top: 50%;
    right: calc(100% + 12px);
    transform: translateY(-50%);
    background: rgba(0, 0, 0, 0.85);
    color: white;
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 0.875rem;
    font-weight: 500;
    white-space: nowrap;
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s ease;
    pointer-events: none;
}

.floating-home-tooltip::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 100%;
    transform: translateY(-50%);
    border-width: 6px;
    border-style: solid;
    border-color: transparent transparent transparent rgba(0, 0, 0, 0.85);
}

.floating-home-btn:hover .floating-home-tooltip {
    opacity: 1;
    visibility: visible;
    transform: translateY(-50%) translateX(0);
}

/* Floating animation */
@keyframes floatAnimation {
    0%, 100% {
        transform: translateY(0);
    }
    50% {
        transform: translateY(-8px);
    }
}

/* Mobile responsive */
@media (max-width: 768px) {
    .floating-home-btn {
        bottom: 20px;
        right: 20px;
        width: 50px;
        height: 50px;
        font-size: 1.25rem;
    }
    
    .floating-home-tooltip {
        display: none;
    }
}

/* For very small screens */
@media (max-width: 576px) {
    .floating-home-btn {
        bottom: 70px; /* Move up to avoid mobile browser UI */
        right: 15px;
    }
}


/* ==================== LOADING OVERLAY ==================== */
.loading-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, #0a2463 0%, #1a3375 100%);
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    visibility: hidden;
    transition: opacity 0.3s ease, visibility 0.3s ease;
}

.loading-overlay.active {
    opacity: 1;
    visibility: visible;
}

.loading-container {
    text-align: center;
    max-width: 400px;
    padding: 2rem;
    position: relative;
}

/* Plane Animation */
.plane-container {
    position: relative;
    width: 200px;
    height: 200px;
    margin: 0 auto 2rem;
    perspective: 1000px;
}

.plane-icon {
    font-size: 4rem;
    color: #ffffff;
    position: absolute;
    top: 50%;
    left: -50px;
    transform: translateY(-50%) rotate(45deg);
    animation: planeTakeoff 2s ease-in-out infinite;
    filter: drop-shadow(0 0 10px rgba(255, 255, 255, 0.5));
    z-index: 2;
}

.plane-trail {
    position: absolute;
    top: 50%;
    left: 0;
    width: 200px;
    height: 2px;
    background: linear-gradient(90deg, 
        rgba(255, 255, 255, 0) 0%,
        rgba(255, 255, 255, 0.8) 30%,
        rgba(255, 255, 255, 0.4) 70%,
        rgba(255, 255, 255, 0) 100%
    );
    transform: translateY(-50%);
    animation: trailFade 2s ease-in-out infinite;
}

.plane-clouds {
    position: absolute;
    width: 100%;
    height: 100%;
}

.cloud {
    position: absolute;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 50%;
    animation: cloudFloat 3s ease-in-out infinite;
}

.cloud-1 {
    width: 40px;
    height: 20px;
    top: 30%;
    left: 20%;
    animation-delay: 0s;
}

.cloud-2 {
    width: 60px;
    height: 30px;
    top: 50%;
    left: 60%;
    animation-delay: 0.5s;
}

.cloud-3 {
    width: 30px;
    height: 15px;
    top: 70%;
    left: 40%;
    animation-delay: 1s;
}

/* Loading Text */
.loading-text {
    color: white;
    margin-bottom: 2rem;
}

.loading-dots {
    font-size: 1.5rem;
    font-weight: 600;
    display: block;
    margin-bottom: 0.5rem;
}

.loading-dots .dot {
    animation: dotPulse 1.5s infinite;
}

.loading-dots .dot:nth-child(2) { animation-delay: 0.2s; }
.loading-dots .dot:nth-child(3) { animation-delay: 0.4s; }
.loading-dots .dot:nth-child(4) { animation-delay: 0.6s; }

.loading-subtext {
    color: rgba(255, 255, 255, 0.8);
    font-size: 0.9rem;
    margin: 0;
}

/* Progress Bar */
.loading-progress {
    width: 100%;
    height: 4px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 2px;
    overflow: hidden;
    margin-top: 1rem;
}

.progress-bar {
    height: 100%;
    width: 0%;
    background: linear-gradient(90deg, #3b82f6, #8b5cf6);
    border-radius: 2px;
    animation: progressLoad 2s ease-in-out infinite;
}

/* Animations */
@keyframes planeTakeoff {
    0% {
        left: -50px;
        transform: translateY(-50%) rotate(45deg) scale(0.8);
        opacity: 0;
    }
    20% {
        opacity: 1;
        transform: translateY(-50%) rotate(45deg) scale(1);
    }
    80% {
        left: 250px;
        transform: translateY(-50%) rotate(45deg) scale(1);
        opacity: 1;
    }
    100% {
        left: 250px;
        transform: translateY(-50%) rotate(45deg) scale(0.8);
        opacity: 0;
    }
}

@keyframes trailFade {
    0%, 100% {
        opacity: 0;
        transform: translateY(-50%) scaleX(0.5);
    }
    20%, 80% {
        opacity: 0.5;
        transform: translateY(-50%) scaleX(1);
    }
}

@keyframes cloudFloat {
    0%, 100% {
        transform: translateY(0) translateX(0);
        opacity: 0.3;
    }
    50% {
        transform: translateY(-10px) translateX(10px);
        opacity: 0.6;
    }
}

@keyframes dotPulse {
    0%, 100% {
        opacity: 0.3;
    }
    50% {
        opacity: 1;
    }
}

@keyframes progressLoad {
    0% {
        width: 0%;
        left: 0;
    }
    50% {
        width: 100%;
        left: 0;
    }
    100% {
        width: 0%;
        left: 100%;
    }
}

/* For faster animations on form submissions */
.loading-overlay.fast .plane-icon {
    animation-duration: 1s;
}

.loading-overlay.fast .progress-bar {
    animation-duration: 1s;
}
    </style>
</head>
<body>
      @include('partials.loading-overlay')

    <div class="dashboard-wrapper">
        <!-- Sidebar Overlay for Mobile -->
        <div class="sidebar-overlay" id="sidebarOverlay"></div>
        
        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-menu">
    <div class="menu-label">Main</div>
    <a href="{{ route('dashboard') }}" class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
        <i class="ri-dashboard-line"></i>
        <span class="menu-text">Dashboard</span>
    </a>
    
    <a href="{{ route('shipments.index') }}" class="menu-item {{ request()->routeIs('shipments.*') ? 'active' : '' }}">
        <i class="ri-ship-line"></i>
        <span class="menu-text">My Shipments</span>
    </a>
    
    <a href="{{ route('addresses.index') }}" class="menu-item {{ request()->routeIs('addresses.*') ? 'active' : '' }}">
        <i class="ri-map-pin-line"></i>
        <span class="menu-text">Address Book</span>
    </a>
    
   
    
    <div class="menu-label">Tools</div>
   <a href="{{ route('dashboard.tracking') }}" class="menu-item {{ request()->routeIs('dashboard.tracking') ? 'active' : '' }}">
    <i class="ri-search-line"></i>
    <span class="menu-text">Track Shipment</span>
</a>
    
    <a href="{{ route('quote') }}" class="menu-item {{ request()->routeIs('quote*') ? 'active' : '' }}">
        <i class="ri-calculator-line"></i>
        <span class="menu-text">Get Quote</span>
    </a>
    
    <a href="{{ route('documents.index') }}" class="menu-item {{ request()->routeIs('documents.*') ? 'active' : '' }}">
    <i class="ri-file-text-line"></i>
    <span class="menu-text">Documents</span>
</a>
    
   <a href="{{ route('billing.index') }}" class="menu-item {{ request()->routeIs('billing.*') ? 'active' : '' }}">
    <i class="ri-bill-line"></i>
    <span class="menu-text">Billing</span>
</a>
    
    <form action="{{ route('logout') }}" method="POST" class="menu-item">
        @csrf
        <button type="submit">
            <i class="ri-logout-box-line"></i>
            <span class="menu-text">Logout</span>
        </button>
    </form>
</div>
        </aside>

        <!-- Main Content -->
        <main class="main-content" id="mainContent">
            <!-- Topbar -->
            <div class="topbar">
                <div class="topbar-left">
                    <button class="sidebar-toggle" id="sidebarToggle">
                        <i class="ri-menu-line"></i>
                    </button>
                    <div class="page-title">@yield('page-title', 'Dashboard')</div>
                </div>
                
                <div class="topbar-right">
                   <div class="notification-btn position-relative" id="notificationBell">
                       <i class="ri-notification-3-line"></i>
                        <span class="notification-badge" style="display: none;">0</span>
                </div>
                    
                    {{-- user Icon --}}
                    <div class="dropdown">
                        <button class="user-avatar dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            @if(Auth::user()->profile_picture)
                                <img src="{{ Auth::user()->profile_picture_url }}" alt="{{ Auth::user()->name }}'s avatar" class="user-avatar-img">
                            @else
                                {{ Auth::user()->initials ?? substr(Auth::user()->name, 0, 2) }}
                            @endif
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="{{ route('user.profile') }}">
                                    <i class="ri-user-line"></i>
                                    My Profile
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('user.account') }}">
                                    <i class="ri-settings-3-line"></i>
                                    Account Settings
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('user.security') }}">
                                    <i class="ri-shield-keyhole-line"></i>
                                    Privacy & Security
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger" style="width: 100%; text-align: left; background: none; border: none;">
                                        <i class="ri-logout-box-line"></i>
                                        Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Content Area -->
            <div class="content-area">
                @yield('content')
            </div>
        </main>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebarOverlay = document.getElementById('sidebarOverlay');
            
            // Toggle sidebar
            function toggleSidebar() {
                sidebar.classList.toggle('show');
                sidebarOverlay.classList.toggle('active');
                document.body.style.overflow = sidebar.classList.contains('show') ? 'hidden' : '';
            }
            
            // Close sidebar
            function closeSidebar() {
                sidebar.classList.remove('show');
                sidebarOverlay.classList.remove('active');
                document.body.style.overflow = '';
            }
            
            // Event Listeners
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', toggleSidebar);
            }
            
            if (sidebarOverlay) {
                sidebarOverlay.addEventListener('click', closeSidebar);
            }
            
            // Close sidebar when clicking a menu item on mobile
            const menuItems = document.querySelectorAll('.menu-item');
            menuItems.forEach(item => {
                item.addEventListener('click', function() {
                    if (window.innerWidth < 992) {
                        closeSidebar();
                    }
                });
            });
            
            // Handle window resize
            function handleResize() {
                if (window.innerWidth >= 992) {
                    closeSidebar();
                }
            }
            
            window.addEventListener('resize', handleResize);
            
            // Close sidebar on ESC key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && sidebar.classList.contains('show')) {
                    closeSidebar();
                }
            });
        });



            // Floating Home Button Effects
    document.addEventListener('DOMContentLoaded', function() {
        const floatingBtn = document.getElementById('floatingHomeBtn');
        
        if (floatingBtn) {
            // Hide/show on scroll
            let lastScrollTop = 0;
            const scrollThreshold = 100; // Show after scrolling 100px
            
            window.addEventListener('scroll', function() {
                const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
                
                if (scrollTop > scrollThreshold) {
                    // User has scrolled down - make button more visible
                    floatingBtn.style.opacity = '1';
                } else {
                    // At top of page - slightly transparent
                    floatingBtn.style.opacity = '0.8';
                }
                
                lastScrollTop = scrollTop;
            });
            
            // Click animation
            floatingBtn.addEventListener('click', function(e) {
                // Add a click effect
                this.style.transform = 'scale(0.9)';
                setTimeout(() => {
                    this.style.transform = 'scale(1)';
                }, 200);
            });
        }
    });
    </script>
    
    @stack('scripts')
    
    <!-- Add the notification script -->
<script src="{{ asset('js/notifications.js') }}"></script>
<!-- Page Loader Script -->
<script src="{{ asset('js/page-loader.js') }}"></script>
  @include('partials.floating-home')
</body>
</html>