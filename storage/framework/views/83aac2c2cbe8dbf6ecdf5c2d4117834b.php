<?php if(auth()->guard()->check()): ?>
    <?php if(request()->routeIs('dashboard*') || request()->routeIs('shipments*') || request()->routeIs('addresses*') || request()->routeIs('documents*') || request()->routeIs('billing*') || request()->routeIs('user.*')): ?>
    <!-- Floating Home Button - Only shows on dashboard pages -->
    <a href="<?php echo e(route('home')); ?>" 
       class="floating-home-btn" 
       id="floatingHomeBtn" 
       title="Return to Homepage" 
       aria-label="Return to homepage">
        <i class="ri-home-4-line"></i>
        <span class="floating-home-tooltip">Home</span>
    </a>
    <?php endif; ?>
<?php endif; ?><?php /**PATH C:\Users\Dee\Desktop\General\globalskyfleet\resources\views/partials/floating-home.blade.php ENDPATH**/ ?>