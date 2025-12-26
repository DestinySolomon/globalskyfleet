<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'GlobalSkyFleet - Fast, Secure, Worldwide Delivery | International Courier Services')</title>
    <meta name="description" content="@yield('description', 'GlobalSkyFleet provides fast, secure international courier and logistics services to 220+ countries. Track shipments in real-time with air freight, sea freight, and express delivery solutions. Get instant quotes for worldwide shipping.')">
    <meta name="keywords" content="@yield('keywords', 'international courier, worldwide delivery, air freight, sea freight, express delivery, logistics services, shipment tracking, global shipping')">
    
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Open Graph -->
    <meta property="og:title" content="@yield('og:title', 'GlobalSkyFleet - Fast, Secure, Worldwide Delivery')">
    <meta property="og:description" content="@yield('og:description', 'International courier and logistics services to 220+ countries with real-time tracking and 24/7 support.')">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    
    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('twitter:title', 'GlobalSkyFleet - Fast, Secure, Worldwide Delivery')">
    <meta name="twitter:description" content="@yield('twitter:description', 'International courier and logistics services to 220+ countries with real-time tracking and 24/7 support.')">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Remix Icon -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.1.0/fonts/remixicon.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    
    <!-- Additional Styles -->
    @stack('styles')
</head>
<body>
    <!-- Top Navigation (Sub Navbar) -->
    @include('layouts.partials.top-nav')
    
    <!-- Main Navigation -->
    @include('layouts.partials.main-nav')

    <!-- Main Content -->
    @yield('content')

    <!-- Footer -->
    @include('layouts.partials.footer')

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JS -->
    <script src="{{ asset('js/main.js') }}"></script>
    
    <!-- Additional Scripts -->
    @stack('scripts')
</body>
</html>