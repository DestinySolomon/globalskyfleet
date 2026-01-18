

<?php $__env->startSection('page-title', 'Help Center'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <!-- Hero Section -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="card bg-gradient-primary text-white">
                <div class="card-body p-5">
                    <div class="row align-items-center">
                        <div class="col-lg-8">
                            <h1 class="display-6 fw-bold mb-3">Help Center</h1>
                            <p class="lead mb-4">Get help and learn how to use the admin dashboard effectively</p>
                            <div class="input-group input-group-lg">
                                <input type="text" class="form-control" placeholder="Search for help articles..." id="helpSearch">
                                <button class="btn btn-light" type="button">
                                    <i class="ri-search-line"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-lg-4 text-center">
                            <i class="ri-question-line" style="font-size: 120px; opacity: 0.8;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Topics Grid -->
    <div class="row mb-5">
        <div class="col-12 mb-4">
            <h4 class="mb-3">Browse Topics</h4>
            <p class="text-muted">Select a topic to learn more</p>
        </div>
        
        <?php $__currentLoopData = $topics; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $topic): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col-lg-4 col-md-6 mb-4">
                <a href="<?php echo e(route('admin.help.show', $topic['id'])); ?>" class="text-decoration-none">
                    <div class="card h-100 border-hover shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="mb-3">
                                <div class="rounded-circle bg-<?php echo e($topic['color']); ?>-subtle text-<?php echo e($topic['color']); ?> p-3 d-inline-flex">
                                    <i class="<?php echo e($topic['icon']); ?> fs-2"></i>
                                </div>
                            </div>
                            <h5 class="mb-2"><?php echo e($topic['title']); ?></h5>
                            <p class="text-muted mb-0"><?php echo e($topic['description']); ?></p>
                        </div>
                        <div class="card-footer bg-transparent text-center">
                            <small class="text-<?php echo e($topic['color']); ?> fw-semibold">
                                Learn More <i class="ri-arrow-right-line"></i>
                            </small>
                        </div>
                    </div>
                </a>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <!-- FAQ Section -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Frequently Asked Questions</h5>
                </div>
                <div class="card-body">
                    <div class="accordion" id="faqAccordion">
                        <?php $__currentLoopData = $faqs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $faq): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button <?php echo e($index > 0 ? 'collapsed' : ''); ?>" 
                                            type="button" data-bs-toggle="collapse" 
                                            data-bs-target="#faq<?php echo e($index); ?>">
                                        <?php echo e($faq['question']); ?>

                                    </button>
                                </h2>
                                <div id="faq<?php echo e($index); ?>" 
                                     class="accordion-collapse collapse <?php echo e($index == 0 ? 'show' : ''); ?>" 
                                     data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        <?php echo e($faq['answer']); ?>

                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Quick Support -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Quick Support</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="mailto:support@globalskyfleet.com" class="btn btn-outline-primary">
                            <i class="ri-mail-line me-2"></i> Email Support
                        </a>
                        <a href="tel:+1234567890" class="btn btn-outline-success">
                            <i class="ri-phone-line me-2"></i> Call Support
                        </a>
                        <a href="#" class="btn btn-outline-info">
                            <i class="ri-chat-3-line me-2"></i> Live Chat
                        </a>
                    </div>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Quick Links</h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <a href="<?php echo e(route('admin.dashboard')); ?>" class="list-group-item list-group-item-action">
                            <i class="ri-dashboard-line me-2"></i> Dashboard
                        </a>
                        <a href="<?php echo e(route('admin.shipments')); ?>" class="list-group-item list-group-item-action">
                            <i class="ri-ship-line me-2"></i> Shipments
                        </a>
                        <a href="<?php echo e(route('admin.users')); ?>" class="list-group-item list-group-item-action">
                            <i class="ri-user-line me-2"></i> Users
                        </a>
                        <a href="<?php echo e(route('admin.payments.crypto')); ?>" class="list-group-item list-group-item-action">
                            <i class="ri-currency-line me-2"></i> Crypto Payments
                        </a>
                        <a href="<?php echo e(route('admin.settings.index')); ?>" class="list-group-item list-group-item-action">
                            <i class="ri-settings-3-line me-2"></i> Settings
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Contact Section -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="card bg-light">
                <div class="card-body text-center p-5">
                    <h3 class="mb-3">Still Need Help?</h3>
                    <p class="text-muted mb-4">Our support team is here to help you 24/7</p>
                    <div class="d-flex justify-content-center gap-3">
                        <div class="text-center">
                            <div class="bg-white rounded-circle p-3 mb-2 d-inline-flex">
                                <i class="ri-mail-line fs-3 text-primary"></i>
                            </div>
                            <h6 class="mb-1">Email</h6>
                            <small class="text-muted">support@globalskyfleet.com</small>
                        </div>
                        <div class="text-center">
                            <div class="bg-white rounded-circle p-3 mb-2 d-inline-flex">
                                <i class="ri-phone-line fs-3 text-success"></i>
                            </div>
                            <h6 class="mb-1">Phone</h6>
                            <small class="text-muted">+1 (234) 567-890</small>
                        </div>
                        <div class="text-center">
                            <div class="bg-white rounded-circle p-3 mb-2 d-inline-flex">
                                <i class="ri-chat-3-line fs-3 text-info"></i>
                            </div>
                            <h6 class="mb-1">Live Chat</h6>
                            <small class="text-muted">Available 24/7</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
    // Simple search functionality
    document.getElementById('helpSearch').addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        const cards = document.querySelectorAll('.card.border-hover');
        
        cards.forEach(card => {
            const title = card.querySelector('h5').textContent.toLowerCase();
            const description = card.querySelector('p').textContent.toLowerCase();
            
            if (title.includes(searchTerm) || description.includes(searchTerm)) {
                card.parentElement.style.display = 'block';
            } else {
                card.parentElement.style.display = 'none';
            }
        });
    });
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Dee\Desktop\General\globalskyfleet\resources\views\admin\help\index.blade.php ENDPATH**/ ?>