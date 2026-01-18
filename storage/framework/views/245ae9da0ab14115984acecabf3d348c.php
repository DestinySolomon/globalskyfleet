<!-- Top Navigation (Sub Navbar) -->
<div class="top-nav d-none d-lg-block">
    <div class="container">
        <div class="d-flex justify-content-center align-items-center">
            <div class="d-flex gap-4">
                <?php
                    $currentRoute = Route::currentRouteName();
                ?>
                
                <a href="<?php echo e(route('services.air-freight')); ?>" 
                   class="text-white text-decoration-none opacity-90 hover-opacity-100 <?php echo e($currentRoute == 'services.air-freight' ? 'active fw-bold opacity-100' : ''); ?>">
                    Air Freight
                </a>
                <a href="<?php echo e(route('services.sea-freight')); ?>" 
                   class="text-white text-decoration-none opacity-90 hover-opacity-100 <?php echo e($currentRoute == 'services.sea-freight' ? 'active fw-bold opacity-100' : ''); ?>">
                    Sea Freight
                </a>
                <a href="<?php echo e(route('services.road-courier')); ?>" 
                   class="text-white text-decoration-none opacity-90 hover-opacity-100 <?php echo e($currentRoute == 'services.road-courier' ? 'active fw-bold opacity-100' : ''); ?>">
                    Road Courier
                </a>
                <a href="<?php echo e(route('services.express-delivery')); ?>" 
                   class="text-white text-decoration-none opacity-90 hover-opacity-100 <?php echo e($currentRoute == 'services.express-delivery' ? 'active fw-bold opacity-100' : ''); ?>">
                    Express Delivery
                </a>
                <a href="<?php echo e(route('services.warehousing')); ?>" 
                   class="text-white text-decoration-none opacity-90 hover-opacity-100 <?php echo e($currentRoute == 'services.warehousing' ? 'active fw-bold opacity-100' : ''); ?>">
                    Warehousing
                </a>
            </div>
        </div>
    </div>
</div><?php /**PATH C:\Users\Dee\Desktop\General\globalskyfleet\resources\views/layouts/partials/top-nav.blade.php ENDPATH**/ ?>