

<?php $__env->startSection('title', 'My Profile | GlobalSkyFleet'); ?>
<?php $__env->startSection('page-title', 'My Profile'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <!-- Flash Messages -->
    <?php if(session('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="ri-check-line me-2"></i>
        <?php echo e(session('success')); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>
    
    <?php if(session('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="ri-error-warning-line me-2"></i>
        <?php echo e(session('error')); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>

    <div class="row">
        <!-- Left Column: Profile Info & Stats -->
        <div class="col-lg-4 col-md-5 mb-4">
            <!-- Profile Card -->
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <div class="position-relative d-inline-block mb-3">
                            <div class="profile-picture-wrapper">
                                <img src="<?php echo e($user->profile_picture_url); ?>" 
                                     alt="<?php echo e($user->name); ?>" 
                                     class="profile-picture rounded-circle"
                                     id="profilePicturePreview">
                                <?php if($user->profile_picture): ?>
                                <button type="button" 
                                        class="btn btn-sm btn-danger position-absolute top-0 end-0 rounded-circle"
                                        data-bs-toggle="modal" 
                                        data-bs-target="#deletePictureModal"
                                        style="width: 30px; height: 30px; padding: 0;">
                                    <i class="ri-delete-bin-line"></i>
                                </button>
                                <?php endif; ?>
                            </div>
                        </div>
                        <h4 class="mb-1"><?php echo e($user->name); ?></h4>
                        <p class="text-muted mb-2"><?php echo e($user->email); ?></p>
                        <?php if($user->company): ?>
                        <p class="text-muted mb-3">
                            <i class="ri-building-line me-1"></i><?php echo e($user->company); ?>

                        </p>
                        <?php endif; ?>
                        <span class="badge bg-primary-subtle text-primary">
                            <i class="ri-calendar-line me-1"></i>
                            Member since <?php echo e($user->created_at->format('M Y')); ?>

                        </span>
                    </div>
                    
                    <!-- Bio Section -->
                    <?php if($user->bio): ?>
                    <div class="mb-4">
                        <h6 class="mb-2">About</h6>
                        <p class="text-muted mb-0"><?php echo e($user->bio); ?></p>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Contact Info -->
                    <div class="mb-4">
                        <h6 class="mb-3">Contact Information</h6>
                        <div class="d-flex align-items-center mb-2">
                            <i class="ri-phone-line text-muted me-2"></i>
                            <span><?php echo e($user->phone ?? 'Not provided'); ?></span>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="ri-mail-line text-muted me-2"></i>
                            <span><?php echo e($user->email); ?></span>
                        </div>
                    </div>
                    
                    <!-- Quick Stats -->
                    <div class="stats-section border-top pt-3">
                        <h6 class="mb-3">Account Statistics</h6>
                        <div class="row g-2">
                            <div class="col-6">
                                <div class="stat-item p-2 bg-light rounded">
                                    <div class="text-center">
                                        <div class="stat-value text-primary"><?php echo e($stats['total_shipments']); ?></div>
                                        <small class="text-muted">Total Shipments</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="stat-item p-2 bg-light rounded">
                                    <div class="text-center">
                                        <div class="stat-value text-success"><?php echo e($stats['delivered_shipments']); ?></div>
                                        <small class="text-muted">Delivered</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="stat-item p-2 bg-light rounded">
                                    <div class="text-center">
                                        <div class="stat-value text-warning"><?php echo e($stats['pending_shipments']); ?></div>
                                        <small class="text-muted">Pending</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="stat-item p-2 bg-light rounded">
                                    <div class="text-center">
                                        <div class="stat-value text-info"><?php echo e($stats['total_addresses']); ?></div>
                                        <small class="text-muted">Addresses</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Quick Links -->
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-body p-3">
                    <div class="d-grid gap-2">
                        <a href="<?php echo e(route('shipments.index')); ?>" class="btn btn-outline-primary text-start">
                            <i class="ri-ship-line me-2"></i> My Shipments
                        </a>
                        <a href="<?php echo e(route('addresses.index')); ?>" class="btn btn-outline-primary text-start">
                            <i class="ri-map-pin-line me-2"></i> Address Book
                        </a>
                        <a href="<?php echo e(route('billing.index')); ?>" class="btn btn-outline-primary text-start">
                            <i class="ri-bill-line me-2"></i> Billing & Invoices
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Right Column: Profile Form & Recent Activity -->
        <div class="col-lg-8 col-md-7">
            <!-- Profile Edit Form -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="ri-edit-line me-2"></i>Edit Profile Information
                        </h5>
                        <button class="btn btn-sm btn-outline-primary" type="button" data-bs-toggle="collapse" data-bs-target="#profileForm">
                            <i class="ri-edit-line me-1"></i> Edit
                        </button>
                    </div>
                </div>
                
                <div class="collapse show" id="profileForm">
                    <div class="card-body p-4">
                        <form action="<?php echo e(route('user.profile.update')); ?>" method="POST" enctype="multipart/form-data">
                            <?php echo csrf_field(); ?>
                            
                            <div class="row g-3">
                                <!-- Profile Picture Upload -->
                                <div class="col-12">
                                    <label class="form-label">Profile Picture</label>
                                    <div class="d-flex align-items-center">
                                        <div class="me-3">
                                            <img src="<?php echo e($user->profile_picture_url); ?>" 
                                                 alt="Current profile picture" 
                                                 class="rounded-circle"
                                                 style="width: 80px; height: 80px; object-fit: cover;"
                                                 id="currentPicture">
                                        </div>
                                        <div class="flex-grow-1">
                                            <input type="file" 
                                                   class="form-control <?php $__errorArgs = ['profile_picture'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                                   name="profile_picture" 
                                                   id="profilePictureInput"
                                                   accept="image/*">
                                            <div class="form-text">
                                                Upload a JPG, PNG, or GIF image (max 2MB)
                                            </div>
                                            <?php $__errorArgs = ['profile_picture'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Name -->
                                <div class="col-md-6">
                                    <label class="form-label">Full Name *</label>
                                    <input type="text" 
                                           name="name" 
                                           class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                           value="<?php echo e(old('name', $user->name)); ?>" 
                                           required>
                                    <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                                
                                <!-- Phone -->
                                <div class="col-md-6">
                                    <label class="form-label">Phone Number</label>
                                    <input type="tel" 
                                           name="phone" 
                                           class="form-control <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                           value="<?php echo e(old('phone', $user->phone)); ?>">
                                    <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                                
                                <!-- Company -->
                                <div class="col-12">
                                    <label class="form-label">Company</label>
                                    <input type="text" 
                                           name="company" 
                                           class="form-control <?php $__errorArgs = ['company'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                           value="<?php echo e(old('company', $user->company)); ?>">
                                    <?php $__errorArgs = ['company'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                                
                                <!-- Bio -->
                                <div class="col-12">
                                    <label class="form-label">Bio / About Me</label>
                                    <textarea name="bio" 
                                              class="form-control <?php $__errorArgs = ['bio'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                              rows="3"><?php echo e(old('bio', $user->bio)); ?></textarea>
                                    <div class="form-text">
                                        Tell us a little about yourself (max 1000 characters)
                                    </div>
                                    <?php $__errorArgs = ['bio'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                                
                                <!-- Submit Button -->
                                <div class="col-12 pt-3 border-top">
                                    <div class="d-flex justify-content-end">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="ri-save-line me-2"></i>Save Changes
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Recent Activity -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0">
                        <i class="ri-history-line me-2"></i>Recent Shipments
                    </h5>
                </div>
                <div class="card-body p-0">
                    <?php if($recentShipments->count() > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-4">Tracking #</th>
                                    <th>Destination</th>
                                    <th>Status</th>
                                    <th class="pe-4">Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $recentShipments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $shipment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td class="ps-4">
                                        <a href="<?php echo e(route('shipments.show', $shipment)); ?>" class="text-decoration-none">
                                            <strong><?php echo e($shipment->tracking_number); ?></strong>
                                        </a>
                                    </td>
                                    <td>
                                        <?php if($shipment->recipientAddress): ?>
                                            <?php echo e($shipment->recipientAddress->city); ?>, <?php echo e($shipment->recipientAddress->country_code); ?>

                                        <?php else: ?>
                                            <span class="text-muted">N/A</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php
                                            $statusClass = [
                                                'pending' => 'warning',
                                                'confirmed' => 'info',
                                                'picked_up' => 'primary',
                                                'in_transit' => 'primary',
                                                'out_for_delivery' => 'success',
                                                'delivered' => 'success',
                                                'cancelled' => 'danger',
                                                'returned' => 'secondary',
                                            ][$shipment->status] ?? 'secondary';
                                        ?>
                                        <span class="badge bg-<?php echo e($statusClass); ?>">
                                            <?php echo e(ucfirst(str_replace('_', ' ', $shipment->status))); ?>

                                        </span>
                                    </td>
                                    <td class="pe-4">
                                        <?php echo e($shipment->created_at->format('M d, Y')); ?>

                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                    <?php else: ?>
                    <div class="text-center py-5">
                        <i class="ri-box-line text-muted" style="font-size: 3rem;"></i>
                        <p class="text-muted mt-3 mb-0">No shipments yet</p>
                        <a href="<?php echo e(route('shipments.create')); ?>" class="btn btn-sm btn-primary mt-3">
                            <i class="ri-add-line me-1"></i>Create First Shipment
                        </a>
                    </div>
                    <?php endif; ?>
                </div>
                <?php if($recentShipments->count() > 0): ?>
                <div class="card-footer bg-white border-0">
                    <div class="text-center">
                        <a href="<?php echo e(route('shipments.index')); ?>" class="btn btn-sm btn-outline-primary">
                            <i class="ri-eye-line me-1"></i>View All Shipments
                        </a>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Delete Picture Modal -->
<div class="modal fade" id="deletePictureModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete Profile Picture</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete your profile picture?</p>
                <div class="alert alert-warning mb-0">
                    <i class="ri-alert-line me-2"></i>
                    This action cannot be undone. Your profile will revert to the default avatar.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="<?php echo e(route('user.profile.picture.delete')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="btn btn-danger">
                        <i class="ri-delete-bin-line me-2"></i>Delete Picture
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.profile-picture-wrapper {
    position: relative;
    display: inline-block;
}

.profile-picture {
    width: 120px;
    height: 120px;
    object-fit: cover;
    border: 4px solid white;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.stat-value {
    font-size: 1.5rem;
    font-weight: 700;
    line-height: 1;
}

.stat-item {
    transition: all 0.3s;
}

.stat-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.bg-primary-subtle {
    background-color: rgba(10, 36, 99, 0.1) !important;
}

.bg-success-subtle {
    background-color: rgba(25, 135, 84, 0.1) !important;
}

.bg-warning-subtle {
    background-color: rgba(255, 193, 7, 0.1) !important;
}

.bg-info-subtle {
    background-color: rgba(13, 202, 240, 0.1) !important;
}
</style>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Profile picture preview
    const profilePictureInput = document.getElementById('profilePictureInput');
    const profilePicturePreview = document.getElementById('profilePicturePreview');
    const currentPicture = document.getElementById('currentPicture');
    
    if (profilePictureInput) {
        profilePictureInput.addEventListener('change', function(e) {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    if (profilePicturePreview) {
                        profilePicturePreview.src = e.target.result;
                    }
                    if (currentPicture) {
                        currentPicture.src = e.target.result;
                    }
                }
                
                reader.readAsDataURL(this.files[0]);
            }
        });
    }
    
    // Auto-expand form if there are validation errors
    const hasErrors = document.querySelector('.is-invalid');
    if (hasErrors) {
        const profileForm = document.getElementById('profileForm');
        if (profileForm && profileForm.classList.contains('collapse')) {
            profileForm.classList.remove('collapse');
        }
    }
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Dee\Desktop\General\globalskyfleet\resources\views/user/profile.blade.php ENDPATH**/ ?>