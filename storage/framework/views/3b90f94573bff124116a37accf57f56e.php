<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <!-- Add user ID for private channels -->
    <meta name="user-id" content="<?php echo e(Auth::id()); ?>">
    
    <title>Admin Dashboard - <?php echo e(config('app.name', 'GlobalSkyFleet')); ?></title>
    
    <!-- Favicon -->
    <?php if(setting('site_favicon')): ?>
        <link rel="icon" type="image/x-icon" href="<?php echo e(Storage::url(setting('site_favicon'))); ?>">
    <?php else: ?>
        <link rel="icon" type="image/x-icon" href="<?php echo e(asset('favicon.ico')); ?>">
    <?php endif; ?>
    
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
    
    <!-- Pusher & Echo CDN -->
    <script src="https://js.pusher.com/7.2/pusher.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.11.3/dist/echo.iife.js"></script>
</head>
<body>


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
                <?php if(setting('site_logo')): ?>
                    <img src="<?php echo e(Storage::url(setting('site_logo'))); ?>" alt="<?php echo e(setting('site_name', 'GlobalSkyFleet')); ?>" style="height: 60px; width: auto; max-width: 100px; object-fit: contain;">
                <?php else: ?>
                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" 
                         style="width: 60px; height: 60px; font-weight: bold; font-size: 24px;">
                        GS
                    </div>
                <?php endif; ?>
                <div class="ms-3">
                    <h6 class="mb-0 fw-bold text-primary"><?php echo e(setting('site_name', 'GlobalSkyFleet')); ?></h6>
                    <small class="text-muted">Admin Panel</small>
                </div>
            </div>
        </div>
        
        <div class="sidebar-menu p-3" style="height: calc(100vh - 80px); overflow-y: auto;">
            <div class="mb-4">
                <small class="text-muted text-uppercase fw-bold d-block mb-2">Dashboard</small>
                <a href="<?php echo e(route('admin.dashboard')); ?>" 
                   class="d-flex align-items-center py-2 px-3 rounded text-decoration-none mb-2 
                          <?php echo e(request()->routeIs('admin.dashboard') ? 'bg-primary text-white' : 'text-dark hover-bg-light'); ?>">
                    <i class="ri-dashboard-line me-3"></i>
                    <span>Dashboard</span>
                </a>
            </div>
            
            <div class="mb-4">
                <small class="text-muted text-uppercase fw-bold d-block mb-2">Shipments</small>
                <a href="<?php echo e(route('admin.shipments')); ?>" 
                   class="d-flex align-items-center py-2 px-3 rounded text-decoration-none mb-2 
                          <?php echo e(request()->routeIs('admin.shipments*') ? 'bg-primary text-white' : 'text-dark hover-bg-light'); ?>">
                    <i class="ri-ship-line me-3"></i>
                    <span>All Shipments</span>
                </a>
            </div>
            
            <div class="mb-4">
                <small class="text-muted text-uppercase fw-bold d-block mb-2">Users</small>
                <a href="<?php echo e(route('admin.users')); ?>" 
                   class="d-flex align-items-center py-2 px-3 rounded text-decoration-none mb-2 
                          <?php echo e(request()->routeIs('admin.users*') ? 'bg-primary text-white' : 'text-dark hover-bg-light'); ?>">
                    <i class="ri-user-line me-3"></i>
                    <span>User Management</span>
                </a>
            </div>
            
           <!-- CONTACT MESSAGES SECTION -->
<div class="mb-4">
    <small class="text-muted text-uppercase fw-bold d-block mb-2">Communications</small>
    <a href="<?php echo e(route('admin.contact-messages.index')); ?>" 
       class="d-flex align-items-center py-2 px-3 rounded text-decoration-none mb-2 
              <?php echo e(request()->routeIs('admin.contact-messages*') ? 'bg-primary text-white' : 'text-dark hover-bg-light'); ?>">
        <i class="ri-mail-line me-3"></i>
        <span>Contact Messages</span>
        <?php
            // This will now work for both admin and super_admin
            $unreadContactCount = \App\Models\ContactMessage::where('status', 'unread')->count();
        ?>
        <?php if($unreadContactCount > 0): ?>
            <span class="ms-auto badge bg-danger rounded-pill"><?php echo e($unreadContactCount); ?></span>
        <?php endif; ?>
    </a>




    <!-- ADD THIS LIVE CHAT LINK -->
<a href="<?php echo e(route('chat.admin.chat')); ?>" 
   class="d-flex align-items-center py-2 px-3 rounded text-decoration-none mb-2 
          <?php echo e(request()->routeIs('chat.admin.chat*') ? 'bg-primary text-white' : 'text-dark hover-bg-light'); ?>">
    <i class="ri-chat-3-line me-3"></i>
    <span>Live Chat Support</span>
</a>
</div>


            
            <div class="mb-4">
                <small class="text-muted text-uppercase fw-bold d-block mb-2">Documents</small>
                <a href="<?php echo e(route('admin.documents')); ?>" 
                   class="d-flex align-items-center py-2 px-3 rounded text-decoration-none mb-2 
                          <?php echo e(request()->routeIs('admin.documents*') ? 'bg-primary text-white' : 'text-dark hover-bg-light'); ?>">
                    <i class="ri-file-text-line me-3"></i>
                    <span>Document Management</span>
                </a>
            </div>
            
            <div class="mb-4">
                <small class="text-muted text-uppercase fw-bold d-block mb-2">Payments</small>
                <a href="<?php echo e(route('admin.payments.crypto')); ?>" 
                   class="d-flex align-items-center py-2 px-3 rounded text-decoration-none mb-2 
                          <?php echo e(request()->routeIs('admin.payments.crypto') ? 'bg-primary text-white' : 'text-dark hover-bg-light'); ?>">
                    <i class="ri-currency-line me-3"></i>
                    <span>Crypto Payments</span>
                </a>
                
                <a href="<?php echo e(route('admin.wallets')); ?>" 
                   class="d-flex align-items-center py-2 px-3 rounded text-decoration-none mb-2 
                          <?php echo e(request()->routeIs('admin.wallets*') ? 'bg-primary text-white' : 'text-dark hover-bg-light'); ?>">
                    <i class="ri-wallet-line me-3"></i>
                    <span>Wallet Management</span>
                </a>
            </div>
            
            <div class="mb-4">
                <small class="text-muted text-uppercase fw-bold d-block mb-2">Analytics</small>
                <a href="<?php echo e(route('admin.analytics')); ?>" 
                   class="d-flex align-items-center py-2 px-3 rounded text-decoration-none mb-2 
                          <?php echo e(request()->routeIs('admin.analytics*') ? 'bg-primary text-white' : 'text-dark hover-bg-light'); ?>">
                    <i class="ri-line-chart-line me-3"></i>
                    <span>Analytics & Reports</span>
                </a>
            </div>

            <div class="mb-4">
                <small class="text-muted text-uppercase fw-bold d-block mb-2">Settings</small>
                <a href="<?php echo e(route('admin.settings.index')); ?>" 
                   class="d-flex align-items-center py-2 px-3 rounded text-decoration-none mb-2 
                          <?php echo e(request()->routeIs('admin.settings*') ? 'bg-primary text-white' : 'text-dark hover-bg-light'); ?>">
                    <i class="ri-settings-3-line me-3"></i>
                    <span>Settings</span>
                </a>
            </div>
            
            <div class="mb-4">
                <small class="text-muted text-uppercase fw-bold d-block mb-2">Navigation</small>
                <!-- Removed Public Homepage link from sidebar as it's now in user dropdown -->
                
                <form action="<?php echo e(route('logout')); ?>" method="POST" class="d-inline w-100">
                    <?php echo csrf_field(); ?>
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
            <h4 class="mb-0 d-none d-md-block"><?php echo $__env->yieldContent('page-title', 'Admin Dashboard'); ?></h4>
            <h5 class="mb-0 d-md-none"><?php echo $__env->yieldContent('page-title', 'Dashboard'); ?></h5>
            
            <div class="d-flex align-items-center gap-3">
                <!-- Notification Bell -->
                <div class="dropdown">
                    <button class="btn btn-light rounded-circle position-relative p-2 notification-btn" 
                            type="button" data-bs-toggle="dropdown" 
                            style="width: 44px; height: 44px;"
                            id="notificationDropdown"
                            data-notifications-url="<?php echo e(route('admin.notifications.index')); ?>"
                            data-mark-read-url="<?php echo e(route('admin.notifications.mark-read', ['notification' => ':id'])); ?>"
                            data-mark-all-read-url="<?php echo e(route('admin.notifications.mark-all-read')); ?>">
                        <i class="ri-notification-3-line fs-5"></i>
                        <?php if($unreadNotificationsCount > 0): ?>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger notification-pulse" 
                                  style="font-size: 10px; padding: 2px 6px;"
                                  id="notificationBadge">
                                <?php echo e($unreadNotificationsCount); ?>

                            </span>
                        <?php endif; ?>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end notification-dropdown-menu p-0" 
                        aria-labelledby="notificationDropdown">
                        <li class="p-3 border-bottom">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="mb-0 fw-bold">Notifications</h6>
                                <?php if($unreadNotificationsCount > 0): ?>
                                    <button class="btn btn-sm btn-outline-primary" id="markAllAsRead">
                                        Mark all as read
                                    </button>
                                <?php endif; ?>
                            </div>
                        </li>
                        <li>
                            <div class="notification-list" style="max-height: 300px; overflow-y: auto;">
                                <?php $__empty_1 = true; $__currentLoopData = $recentNotifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <a href="<?php echo e($notification->data['url'] ?? '#'); ?>" 
                                       class="dropdown-item notification-item py-3 px-3 border-bottom <?php echo e($notification->unread() ? 'unread' : ''); ?>"
                                       data-notification-id="<?php echo e($notification->id); ?>">
                                        <div class="d-flex align-items-start">
                                            <div class="rounded-circle bg-primary bg-opacity-10 text-primary p-2 me-3">
                                                <i class="ri-<?php echo e($notification->data['icon'] ?? 'notification-line'); ?>"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <div class="fw-semibold mb-1"><?php echo e($notification->data['title'] ?? 'Notification'); ?></div>
                                                <small class="text-muted"><?php echo e($notification->data['message'] ?? ''); ?></small>
                                                <div class="text-end mt-1">
                                                    <small class="text-muted">
                                                        <?php echo e($notification->created_at->diffForHumans()); ?>

                                                    </small>
                                                </div>
                                            </div>
                                            <?php if($notification->unread()): ?>
                                                <div class="ms-2">
                                                    <span class="badge bg-primary rounded-pill" style="font-size: 8px;">New</span>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </a>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <div class="dropdown-item text-center py-4">
                                        <i class="ri-notification-off-line fs-1 text-muted mb-2"></i>
                                        <p class="text-muted mb-0">No notifications</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </li>
                        <li class="border-top">
                            <a class="dropdown-item text-center py-2" href="<?php echo e(route('admin.notifications.index')); ?>">
                                <i class="ri-eye-line me-2"></i>View all notifications
                            </a>
                        </li>
                    </ul>
                </div>
                
                <!-- User Dropdown -->
                <div class="dropdown">
                    <button class="btn btn-light rounded-circle p-0 d-flex align-items-center justify-content-center border-0" 
                            type="button" style="width: 44px; height: 44px;" 
                            data-bs-toggle="dropdown" aria-expanded="false">
                        <?php if(Auth::user()->profile_picture): ?>
                            <img src="<?php echo e(Storage::url('profile-pictures/' . Auth::user()->profile_picture)); ?>" 
                                 alt="<?php echo e(Auth::user()->name); ?>" 
                                 class="rounded-circle w-100 h-100 object-fit-cover">
                        <?php else: ?>
                            <div class="user-avatar rounded-circle">
                                <?php echo e(Auth::user()->initials); ?>

                            </div>
                        <?php endif; ?>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end user-dropdown-menu">
                        <li class="user-dropdown-header">
                            <div class="d-flex align-items-center">
                                <?php if(Auth::user()->profile_picture): ?>
                                    <img src="<?php echo e(Storage::url('profile-pictures/' . Auth::user()->profile_picture)); ?>" 
                                         alt="<?php echo e(Auth::user()->name); ?>" 
                                         class="rounded-circle me-3" 
                                         style="width: 50px; height: 50px; object-fit: cover;">
                                <?php else: ?>
                                    <div class="user-avatar rounded-circle me-3">
                                        <?php echo e(Auth::user()->initials); ?>

                                    </div>
                                <?php endif; ?>
                                <div>
                                    <h6 class="mb-0"><?php echo e(Auth::user()->name); ?></h6>
                                    <small class="text-white-80"><?php echo e(Auth::user()->email); ?></small>
                                    <div class="mt-1">
                                        <span class="badge bg-light text-primary">
                                            <?php echo e(Auth::user()->role->name ?? 'Administrator'); ?>

                                        </span>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="p-3">
                            <div class="row g-2">
                                <div class="col-6">
                                    <a href="<?php echo e(route('admin.profile.edit')); ?>" class="text-decoration-none">
                                        <div class="text-center p-2 rounded hover-bg-light">
                                            <i class="ri-user-line fs-4 text-primary mb-2"></i>
                                            <div class="small">Profile</div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-6">
                                    <a href="<?php echo e(route('admin.settings.index')); ?>" class="text-decoration-none">
                                        <div class="text-center p-2 rounded hover-bg-light">
                                            <i class="ri-settings-3-line fs-4 text-primary mb-2"></i>
                                            <div class="small">Settings</div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-6">
                                    <a href="<?php echo e(route('home')); ?>" class="text-decoration-none">
                                        <div class="text-center p-2 rounded hover-bg-light">
                                            <i class="ri-home-3-line fs-4 text-primary mb-2"></i>
                                            <div class="small">Website</div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-6">
                                    <a href="<?php echo e(route('admin.notifications.index')); ?>" class="text-decoration-none">
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
                            <a class="dropdown-item d-flex align-items-center py-2" href="<?php echo e(route('admin.profile.edit')); ?>">
                                <i class="ri-user-settings-line me-2"></i>
                                <span>Edit Profile</span>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center py-2" href="<?php echo e(route('admin.profile.change-password')); ?>">
                                <i class="ri-lock-password-line me-2"></i>
                                <span>Change Password</span>
                            </a>
                        </li>
                        <li><hr class="dropdown-divider my-0"></li>
                        <li>
                            <form action="<?php echo e(route('logout')); ?>" method="POST" class="m-0">
                                <?php echo csrf_field(); ?>
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
            <?php if(session('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php echo e(session('success')); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <?php if(session('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php echo e(session('error')); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <?php echo $__env->yieldContent('content'); ?>
        </div>
    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JavaScript for Echo -->
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
        },900); // Small delay to ensure everything is ready
    });
    
    // Optional: Show loading overlay during AJAX requests
    const originalFetch = window.fetch;
    window.fetch = function(...args) {
        // Show loading for AJAX requests that take more than 300ms
        let timeout = setTimeout(() => {
            if (!loadingOverlay.classList.contains('hidden')) return;
            loadingOverlay.classList.remove('hidden');
            loadingOverlay.style.display = 'flex';
        }, 300);
        
        return originalFetch.apply(this, args).then(response => {
            clearTimeout(timeout);
            loadingOverlay.classList.add('hidden');
            setTimeout(() => {
                loadingOverlay.style.display = 'none';
            }, 300);
            return response;
        }).catch(error => {
            clearTimeout(timeout);
            loadingOverlay.classList.add('hidden');
            setTimeout(() => {
                loadingOverlay.style.display = 'none';
            }, 300);
            throw error;
        });
    };
    
    // Your existing initialization code...
    initializeRealtimeNotifications();
    // ... rest of your code
});
        // Initialize Pusher and Echo
        document.addEventListener('DOMContentLoaded', function() {
            initializeRealtimeNotifications();
            
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
            
            // Original notification functionality
            const notificationDropdown = document.getElementById('notificationDropdown');
            const notificationBadge = document.getElementById('notificationBadge');
            const markAllAsReadBtn = document.getElementById('markAllAsRead');
            
            // Mark notification as read when clicked
            document.querySelectorAll('.notification-item').forEach(item => {
                item.addEventListener('click', function() {
                    const notificationId = this.dataset.notificationId;
                    if (notificationId) {
                        markNotificationAsRead(notificationId);
                    }
                });
            });
            
            // Mark all notifications as read
            if (markAllAsReadBtn) {
                markAllAsReadBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    fetch('<?php echo e(route("admin.notifications.mark-all-read")); ?>', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Remove "unread" class from all notifications
                            document.querySelectorAll('.notification-item.unread').forEach(item => {
                                item.classList.remove('unread');
                                const badge = item.querySelector('.badge.bg-primary');
                                if (badge) badge.remove();
                            });
                            
                            // Update badge count
                            if (notificationBadge) {
                                notificationBadge.remove();
                            }
                            
                            // Hide mark all as read button
                            markAllAsReadBtn.remove();
                        }
                    })
                    .catch(error => console.error('Error:', error));
                });
            }
            
            function markNotificationAsRead(notificationId) {
                const url = '<?php echo e(route("admin.notifications.mark-read", ["notification" => ":id"])); ?>'.replace(':id', notificationId);
                
                fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Remove "unread" class
                        const notificationItem = document.querySelector(`[data-notification-id="${notificationId}"]`);
                        if (notificationItem) {
                            notificationItem.classList.remove('unread');
                            const badge = notificationItem.querySelector('.badge.bg-primary');
                            if (badge) badge.remove();
                            
                            // Update badge count
                            if (notificationBadge) {
                                const currentCount = parseInt(notificationBadge.textContent);
                                if (currentCount > 1) {
                                    notificationBadge.textContent = currentCount - 1;
                                } else {
                                    notificationBadge.remove();
                                    if (markAllAsReadBtn) markAllAsReadBtn.remove();
                                }
                            }
                        }
                    }
                })
                .catch(error => console.error('Error:', error));
            }
            
            // Poll for new notifications every 30 seconds
            setInterval(function() {
                fetch('<?php echo e(route("admin.notifications.count")); ?>')
                    .then(response => response.json())
                    .then(data => {
                        if (data.unread_count > 0) {
                            updateNotificationBadge(data.unread_count);
                        }
                    })
                    .catch(error => console.error('Error fetching notification count:', error));
            }, 30000);
            
            function updateNotificationBadge(count) {
                if (!notificationBadge && count > 0) {
                    const badge = document.createElement('span');
                    badge.className = 'position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger notification-pulse';
                    badge.style.cssText = 'font-size: 10px; padding: 2px 6px;';
                    badge.id = 'notificationBadge';
                    badge.textContent = count;
                    notificationDropdown.appendChild(badge);
                } else if (notificationBadge) {
                    if (count > 0) {
                        notificationBadge.textContent = count;
                    } else {
                        notificationBadge.remove();
                    }
                }
            }
        });
        
        function initializeRealtimeNotifications() {
            // Get user ID from meta tag
            const userId = document.querySelector('meta[name="user-id"]').content;
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
            
            if (!userId) {
                console.warn('User ID not found. Real-time notifications disabled.');
                return;
            }
            
            // Initialize Pusher
            Pusher.logToConsole = <?php echo e(app()->environment('local') ? 'true' : 'false'); ?>;
            
            // Initialize Echo
            window.Echo = new Echo({
                broadcaster: 'pusher',
                key: '<?php echo e(env("PUSHER_APP_KEY", "your-pusher-key")); ?>',
                cluster: '<?php echo e(env("PUSHER_APP_CLUSTER", "mt1")); ?>',
                forceTLS: true,
                authEndpoint: '/broadcasting/auth',
                auth: {
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                }
            });
            
            // Listen for private notifications
            window.Echo.private('App.Models.User.' + userId)
                .notification((notification) => {
                    console.log('New real-time notification:', notification);
                    
                    // Update notification badge
                    updateNotificationBadgeRealtime();
                    
                    // Show browser notification
                    showBrowserNotification(notification);
                    
                    // Play notification sound
                    playNotificationSound();
                    
                    // Add to dropdown
                    addNotificationToDropdown(notification);
                });
            
            // Listen for global admin notifications
            window.Echo.private('admin.global')
                .listen('.admin.notification', (data) => {
                    console.log('Global admin notification:', data);
                    // Handle global admin notifications
                });
        }
        
        function updateNotificationBadgeRealtime() {
            const badge = document.getElementById('notificationBadge');
            if (badge) {
                const currentCount = parseInt(badge.textContent) || 0;
                badge.textContent = currentCount + 1;
                badge.classList.add('notification-pulse');
            } else {
                // Create badge if it doesn't exist
                const notificationBtn = document.querySelector('.notification-btn');
                if (notificationBtn) {
                    const newBadge = document.createElement('span');
                    newBadge.id = 'notificationBadge';
                    newBadge.className = 'position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger notification-pulse';
                    newBadge.style.cssText = 'font-size: 10px; padding: 2px 6px;';
                    newBadge.textContent = '1';
                    notificationBtn.appendChild(newBadge);
                    
                    // Add mark all as read button if not present
                    const dropdownMenu = document.querySelector('.notification-dropdown-menu');
                    if (dropdownMenu && !document.getElementById('markAllAsRead')) {
                        const header = dropdownMenu.querySelector('.p-3.border-bottom');
                        if (header) {
                            const markAllBtn = document.createElement('button');
                            markAllBtn.id = 'markAllAsRead';
                            markAllBtn.className = 'btn btn-sm btn-outline-primary';
                            markAllBtn.textContent = 'Mark all as read';
                            markAllBtn.addEventListener('click', function(e) {
                                e.preventDefault();
                                e.stopPropagation();
                                markAllNotificationsAsRead();
                            });
                            header.querySelector('.d-flex').appendChild(markAllBtn);
                        }
                    }
                }
            }
        }
        
        function showBrowserNotification(notification) {
            // Check if browser supports notifications
            if (!("Notification" in window)) {
                console.log("This browser does not support desktop notification");
                return;
            }
            
            // Check if permission is already granted
            if (Notification.permission === "granted") {
                createNotification(notification);
            }
            // Otherwise, ask for permission
            else if (Notification.permission !== "denied") {
                Notification.requestPermission().then(permission => {
                    if (permission === "granted") {
                        createNotification(notification);
                    }
                });
            }
        }
        
        function createNotification(notification) {
            const title = notification.data?.title || 'New Notification';
            const body = notification.data?.message || 'You have a new notification';
            const icon = '/favicon.ico'; // Your favicon path
            
            const notificationObj = new Notification(title, {
                body: body,
                icon: icon,
                tag: 'notification-' + notification.id
            });
            
            // Close notification after 5 seconds
            setTimeout(() => {
                notificationObj.close();
            }, 5000);
            
            // Click handler for notification
            notificationObj.onclick = function() {
                window.focus();
                this.close();
                
                // Navigate to notification URL if available
                if (notification.data?.url && notification.data.url !== '#') {
                    window.location.href = notification.data.url;
                }
            };
        }
        
        function playNotificationSound() {
            // Create audio element
            const audio = new Audio('/notification.mp3'); // Add this sound file to public folder
            
            // Try to play sound
            audio.play().catch(error => {
                console.log('Audio play failed:', error);
                // Fallback to beep sound
                playBeepSound();
            });
        }
        
        function playBeepSound() {
            // Create a simple beep sound using Web Audio API
            try {
                const audioContext = new (window.AudioContext || window.webkitAudioContext)();
                const oscillator = audioContext.createOscillator();
                const gainNode = audioContext.createGain();
                
                oscillator.connect(gainNode);
                gainNode.connect(audioContext.destination);
                
                oscillator.frequency.value = 800;
                oscillator.type = 'sine';
                gainNode.gain.value = 0.1;
                
                oscillator.start();
                setTimeout(() => {
                    oscillator.stop();
                }, 100);
            } catch (e) {
                console.log('Web Audio API not supported');
            }
        }
        
        function addNotificationToDropdown(notification) {
            const dropdownList = document.querySelector('.notification-dropdown-menu .notification-list');
            if (!dropdownList) return;
            
            // Create notification element
            const notificationElement = createNotificationElement(notification);
            
            // Add to top of list
            dropdownList.insertBefore(notificationElement, dropdownList.firstChild);
            
            // Limit to 10 notifications
            const notifications = dropdownList.querySelectorAll('.notification-item');
            if (notifications.length > 10) {
                notifications[notifications.length - 1].remove();
            }
        }
        
        function createNotificationElement(notification) {
            const data = notification.data || {};
            const icon = data.icon || 'ri-notification-line';
            const bgColor = getPriorityColor(data.priority || 'normal');
            const timeAgo = 'Just now';
            
            const element = document.createElement('a');
            element.href = data.url || '#';
            element.className = 'dropdown-item notification-item py-3 px-3 border-bottom unread';
            element.setAttribute('data-notification-id', notification.id);
            element.onclick = function(e) {
                e.preventDefault();
                markNotificationAsReadRealtime(notification.id, data.url);
            };
            
            element.innerHTML = `
                <div class="d-flex align-items-start">
                    <div class="rounded-circle bg-${bgColor}-subtle text-${bgColor} p-2 me-3">
                        <i class="${icon}"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="fw-semibold mb-1">${data.title || 'Notification'}</div>
                        <small class="text-muted">${data.message || ''}</small>
                        <div class="text-end mt-1">
                            <small class="text-muted">${timeAgo}</small>
                        </div>
                    </div>
                    <div class="ms-2">
                        <span class="badge bg-${bgColor} rounded-pill" style="font-size: 8px;">New</span>
                    </div>
                </div>
            `;
            
            return element;
        }
        
        function getPriorityColor(priority) {
            switch(priority) {
                case 'low': return 'info';
                case 'normal': return 'primary';
                case 'high': return 'warning';
                case 'urgent': return 'danger';
                default: return 'primary';
            }
        }
        
        function markNotificationAsReadRealtime(notificationId, redirectUrl = null) {
            fetch(`/admin/notifications/${notificationId}/mark-read`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Remove "unread" class
                    const notificationItem = document.querySelector(`[data-notification-id="${notificationId}"]`);
                    if (notificationItem) {
                        notificationItem.classList.remove('unread');
                        const badge = notificationItem.querySelector('.badge.bg-primary, .badge.bg-warning, .badge.bg-danger, .badge.bg-info');
                        if (badge) badge.remove();
                    }
                    
                    // Update badge count
                    const notificationBadge = document.getElementById('notificationBadge');
                    if (notificationBadge) {
                        const currentCount = parseInt(notificationBadge.textContent);
                        if (currentCount > 1) {
                            notificationBadge.textContent = currentCount - 1;
                        } else {
                            notificationBadge.remove();
                        }
                    }
                    
                    // Redirect if URL provided
                    if (redirectUrl && redirectUrl !== '#') {
                        window.location.href = redirectUrl;
                    }
                }
            })
            .catch(error => console.error('Error marking notification as read:', error));
        }
        
        function markAllNotificationsAsRead() {
            fetch('/admin/notifications/mark-all-read', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Remove all "unread" classes and badges
                    document.querySelectorAll('.notification-item.unread').forEach(item => {
                        item.classList.remove('unread');
                        const badge = item.querySelector('.badge');
                        if (badge) badge.remove();
                    });
                    
                    // Remove notification badge
                    const notificationBadge = document.getElementById('notificationBadge');
                    if (notificationBadge) {
                        notificationBadge.remove();
                    }
                    
                    // Remove mark all as read button
                    const markAllBtn = document.getElementById('markAllAsRead');
                    if (markAllBtn) {
                        markAllBtn.remove();
                    }
                }
            })
            .catch(error => console.error('Error marking all notifications as read:', error));
        }
    </script>
    
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html><?php /**PATH C:\Users\Dee\Desktop\General\globalskyfleet\resources\views/layouts/admin.blade.php ENDPATH**/ ?>