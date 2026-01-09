<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Dashboard - {{ config('app.name', 'GlobalSkyFleet') }}</title>
    
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

        
    </style>
</head>
<body>
    <!-- Sidebar Overlay for Mobile -->
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>
    
    <!-- Sidebar -->
    <aside class="admin-sidebar" id="sidebar">
        <div class="sidebar-header border-bottom p-3">
            <div class="d-flex align-items-center">
                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" 
                     style="width: 40px; height: 40px; font-weight: bold; font-size: 18px;">
                    GS
                </div>
                <div class="ms-3">
                    <h6 class="mb-0 fw-bold text-primary">GlobalSkyFleet</h6>
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
                <a href="{{ route('home') }}" 
                   class="d-flex align-items-center py-2 px-3 rounded text-decoration-none mb-2 text-dark hover-bg-light">
                    <i class="ri-home-line me-3"></i>
                    <span>Public Homepage</span>
                </a>
                
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
                <!-- Notification Bell -->
                <div class="dropdown">
                    <button class="btn btn-light rounded-circle position-relative p-2" 
                            type="button" data-bs-toggle="dropdown" 
                            style="width: 44px; height: 44px;">
                        <i class="ri-notification-3-line fs-5"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" 
                              style="font-size: 10px; padding: 2px 4px;">
                            {{ $notificationCount ?? 0 }}
                        </span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end p-2" style="min-width: 300px;">
                        <li>
                            <h6 class="dropdown-header">Notifications</h6>
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center py-2" href="#">
                                <div class="rounded-circle bg-primary bg-opacity-10 text-primary p-2 me-3">
                                    <i class="ri-file-text-line"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="fw-semibold">New Document Uploaded</div>
                                    <small class="text-muted">User uploaded ID proof</small>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center py-2" href="{{ route('admin.payments.crypto') }}">
                                <div class="rounded-circle bg-success bg-opacity-10 text-success p-2 me-3">
                                    <i class="ri-currency-line"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="fw-semibold">Crypto Payment Pending</div>
                                    <small class="text-muted">BTC payment requires verification</small>
                                </div>
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <a class="dropdown-item text-center text-primary" href="#">
                                <i class="ri-refresh-line me-2"></i>Mark all as read
                            </a>
                        </li>
                    </ul>
                </div>
                
                <!-- User Dropdown -->
                <div class="dropdown">
                    <button class="btn btn-light rounded-circle p-0 d-flex align-items-center justify-content-center" 
                            type="button" style="width: 44px; height: 44px;" data-bs-toggle="dropdown">
                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center w-100 h-100 fw-semibold">
                            {{ Auth::user()->initials }}
                        </div>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <div class="dropdown-item">
                                <small class="text-muted">Signed in as</small>
                                <div class="fw-semibold">{{ Auth::user()->email }}</div>
                            </div>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item" href="{{ route('home') }}">
                                <i class="ri-home-line me-2"></i>Public Homepage
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item">
                                    <i class="ri-logout-box-line me-2"></i>Logout
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
    
    <script>
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
    </script>
    
    @stack('scripts')
</body>
</html>