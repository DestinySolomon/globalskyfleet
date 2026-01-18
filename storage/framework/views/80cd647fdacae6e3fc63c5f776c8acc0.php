<!-- Footer -->
<footer class="bg-navy text-white py-5">
    <div class="container py-4">
        <div class="row g-4">
            <div class="col-lg-3 col-md-6">
                <img src="<?php echo e(setting('site_logo') ? Storage::url(setting('site_logo')) : asset('images/logo.png')); ?>" alt="<?php echo e(setting('site_name', 'GlobalSkyFleet')); ?> Logo" height="60" style="width: auto; max-width: 120px; object-fit: contain;" class="mb-3">
                <p class="text-white opacity-80 small mb-4">
                    Connecting 220+ countries with precision logistics and real-time tracking. Fast, secure, worldwide delivery.
                </p>
                <div class="d-flex gap-3">
                    <a href="#" class="footer-social text-white">
                        <i class="ri-facebook-fill"></i>
                    </a>
                    <a href="#" class="footer-social text-white">
                        <i class="ri-twitter-x-fill"></i>
                    </a>
                    <a href="#" class="footer-social text-white">
                        <i class="ri-linkedin-fill"></i>
                    </a>
                    <a href="#" class="footer-social text-white">
                        <i class="ri-instagram-fill"></i>
                    </a>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6">
                <h4 class="h5 fw-semibold mb-4">Quick Links</h4>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="<?php echo e(route('home')); ?>" class="text-white opacity-80 hover-opacity-100 text-decoration-none small">Home</a></li>
                    <li class="mb-2"><a href="<?php echo e(route('services')); ?>" class="text-white opacity-80 hover-opacity-100 text-decoration-none small">Services</a></li>
                    <li class="mb-2"><a href="<?php echo e(route('tracking')); ?>" class="text-white opacity-80 hover-opacity-100 text-decoration-none small">Track Shipment</a></li>
                    <li class="mb-2"><a href="<?php echo e(route('quote')); ?>" class="text-white opacity-80 hover-opacity-100 text-decoration-none small">Get a Quote</a></li>
                    <li class="mb-2"><a href="<?php echo e(route('about')); ?>" class="text-white opacity-80 hover-opacity-100 text-decoration-none small">About Us</a></li>
                    <li><a href="<?php echo e(route('contact')); ?>" class="text-white opacity-80 hover-opacity-100 text-decoration-none small">Contact</a></li>
                </ul>
            </div>
            
            <div class="col-lg-3 col-md-6">
                <h4 class="h5 fw-semibold mb-4">Our Services</h4>
                <ul class="list-unstyled">
                    <li class="mb-2"><span class="text-white opacity-80 small">Air Freight</span></li>
                    <li class="mb-2"><span class="text-white opacity-80 small">Sea Freight</span></li>
                    <li class="mb-2"><span class="text-white opacity-80 small">Road Courier</span></li>
                    <li class="mb-2"><span class="text-white opacity-80 small">Express Delivery</span></li>
                    <li class="mb-2"><span class="text-white opacity-80 small">Warehousing</span></li>
                    <li><span class="text-white opacity-80 small">Door-to-Door</span></li>
                </ul>
            </div>
            
            <div class="col-lg-3 col-md-6">
                <h4 class="h5 fw-semibold mb-4">Stay Updated</h4>
                <p class="text-white opacity-80 small mb-3">
                    Subscribe to our newsletter for the latest updates and offers.
                </p>
                <form action="<?php echo e(route('newsletter.subscribe')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="mb-3">
                        <input type="email" name="email" class="form-control newsletter-input rounded-3 py-2 px-3 small" placeholder="Your email address" required>
                    </div>
                    <button type="submit" class="btn btn-orange w-100 rounded-3 py-2 small fw-semibold">
                        Subscribe
                    </button>
                </form>
            </div>
        </div>
        
        <hr class="my-4 opacity-10">
        
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
            <div class="mb-3 mb-md-0">
                <p class="text-white opacity-60 small mb-2">
                    © <?php echo e(date('Y')); ?> GlobalSkyFleet. All rights reserved.
                </p>
                <p class="text-white opacity-60 small mb-0">
                    Site by <a href="https://github.com/DestinySolomon" target="_blank" rel="noopener noreferrer" class="text-white opacity-60 hover-opacity-100 text-decoration-none">Dee</a>
                </p>
            </div>
            <div class="d-flex gap-4">
                <a href="<?php echo e(route('terms')); ?>" class="text-white opacity-60 hover-opacity-100 text-decoration-none small">Terms of Service</a>
                <a href="<?php echo e(route('privacy')); ?>" class="text-white opacity-60 hover-opacity-100 text-decoration-none small">Privacy Policy</a>
            </div>
        </div>
    </div>
</footer><?php /**PATH C:\Users\Dee\Desktop\General\globalskyfleet\resources\views/layouts/partials/footer.blade.php ENDPATH**/ ?>