<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title', 'GlobalSkyFleet - Fast, Secure, Worldwide Delivery | International Courier Services'); ?></title>
    <meta name="description" content="<?php echo $__env->yieldContent('description', 'GlobalSkyFleet provides fast, secure international courier and logistics services to 220+ countries. Track shipments in real-time with air freight, sea freight, and express delivery solutions. Get instant quotes for worldwide shipping.'); ?>">
    <meta name="keywords" content="<?php echo $__env->yieldContent('keywords', 'international courier, worldwide delivery, air freight, sea freight, express delivery, logistics services, shipment tracking, global shipping'); ?>">
    
    <!-- CSRF Token -->
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    
    <!-- Favicon -->
    <?php if(setting('site_favicon')): ?>
        <link rel="icon" type="image/x-icon" href="<?php echo e(Storage::url(setting('site_favicon'))); ?>">
    <?php else: ?>
        <link rel="icon" type="image/x-icon" href="<?php echo e(asset('favicon.ico')); ?>">
    <?php endif; ?>
    
    <!-- Open Graph -->
    <meta property="og:title" content="<?php echo $__env->yieldContent('og:title', 'GlobalSkyFleet - Fast, Secure, Worldwide Delivery'); ?>">
    <meta property="og:description" content="<?php echo $__env->yieldContent('og:description', 'International courier and logistics services to 220+ countries with real-time tracking and 24/7 support.'); ?>">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo e(url()->current()); ?>">
    
    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo $__env->yieldContent('twitter:title', 'GlobalSkyFleet - Fast, Secure, Worldwide Delivery'); ?>">
    <meta name="twitter:description" content="<?php echo $__env->yieldContent('twitter:description', 'International courier and logistics services to 220+ countries with real-time tracking and 24/7 support.'); ?>">
    
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
    
    
     <link rel="stylesheet" href="https://unpkg.com/aos@2.3.0/dist/aos.css">

    <!-- Custom CSS -->
    <link href="<?php echo e(asset('css/style.css')); ?>" rel="stylesheet">

        <!-- Pusher JS CDN -->
<script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    
    <!-- Additional Styles -->
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body>

    
      <!-- Loading Overlay -->
    <?php echo $__env->make('partials.loading-overlay', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <!-- Top Navigation (Sub Navbar) -->
    <?php echo $__env->make('layouts.partials.top-nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    
    <!-- Main Navigation -->
    <?php echo $__env->make('layouts.partials.main-nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <!-- Main Content -->
    <?php echo $__env->yieldContent('content'); ?>

    <!-- Live Chat Button -->
<?php echo $__env->make('partials.live-chat-simple', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <!-- Footer -->
    <?php echo $__env->make('layouts.partials.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>


      <!-- Back to Top Button -->
    <?php echo $__env->make('partials.back-to-top', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JS -->
    <script src="<?php echo e(asset('js/main.js')); ?>"></script>
    
    <!-- Additional Scripts -->
    <?php echo $__env->yieldPushContent('scripts'); ?>



<script>
    // Initialize Pusher globally
    window.PUSHER_APP_KEY = '<?php echo e(env("PUSHER_APP_KEY")); ?>';
    window.PUSHER_APP_CLUSTER = '<?php echo e(env("PUSHER_APP_CLUSTER")); ?>';


        AOS.init({
            duration: 1200,
        })
</script>  

<script src="https://unpkg.com/aos@2.3.0/dist/aos.js"></script>
</body>
</html><?php /**PATH C:\Users\Dee\Desktop\General\globalskyfleet\resources\views/layouts/app.blade.php ENDPATH**/ ?>