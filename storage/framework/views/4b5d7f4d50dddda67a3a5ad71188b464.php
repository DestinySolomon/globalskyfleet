

<?php $__env->startSection('page-title', 'Profile Settings'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-4">
            <!-- Profile Card -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Profile Picture</h5>
                </div>
                <div class="card-body text-center">
                    <div class="position-relative d-inline-block mb-3">
                        <?php if(Auth::user()->profile_picture): ?>
                            <img src="<?php echo e(Storage::url('profile-pictures/' . Auth::user()->profile_picture)); ?>" 
                                 alt="<?php echo e(Auth::user()->name); ?>" 
                                 class="rounded-circle" 
                                 style="width: 150px; height: 150px; object-fit: cover;">
                        <?php else: ?>
                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" 
                                 style="width: 150px; height: 150px; font-size: 48px; font-weight: bold;">
                                <?php echo e(Auth::user()->initials); ?>

                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="d-flex justify-content-center gap-2">
                        <form action="<?php echo e(route('admin.profile.picture.update')); ?>" method="POST" enctype="multipart/form-data" class="d-inline">
                            <?php echo csrf_field(); ?>
                            <input type="file" name="profile_picture" id="profilePictureInput" class="d-none" accept="image/*">
                            <button type="button" onclick="document.getElementById('profilePictureInput').click()" 
                                    class="btn btn-primary btn-sm">
                                <i class="ri-upload-line me-1"></i> Change Photo
                            </button>
                        </form>
                        
                        <?php if(Auth::user()->profile_picture): ?>
                            <form action="<?php echo e(route('admin.profile.picture.delete')); ?>" method="POST" class="d-inline">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn btn-outline-danger btn-sm" 
                                        onclick="return confirm('Are you sure you want to remove your profile picture?')">
                                    <i class="ri-delete-bin-line me-1"></i> Remove
                                </button>
                            </form>
                        <?php endif; ?>
                    </div>
                    
                    <small class="text-muted d-block mt-2">Allowed JPG, PNG or GIF. Max size 2MB</small>
                </div>
            </div>

            <!-- Account Info -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Account Information</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label text-muted">Role</label>
                        <div class="fw-semibold">
                            <span class="badge bg-primary"><?php echo e(Auth::user()->role ?? 'Administrator'); ?></span>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label text-muted">Member Since</label>
                        <div class="fw-semibold">
                            <?php echo e(Auth::user()->created_at->format('F d, Y')); ?>

                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label text-muted">Last Login</label>
                        <div class="fw-semibold">
                            <?php echo e(Auth::user()->last_login_at ? Auth::user()->last_login_at->format('M d, Y h:i A') : 'Never'); ?>

                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label text-muted">Last Login IP</label>
                        <div class="fw-semibold">
                            <?php echo e(Auth::user()->last_login_ip ?? 'N/A'); ?>

                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="<?php echo e(route('admin.profile.change-password')); ?>" class="btn btn-outline-primary">
                            <i class="ri-lock-password-line me-2"></i> Change Password
                        </a>
                        <a href="<?php echo e(route('admin.notifications.index')); ?>" class="btn btn-outline-info">
                            <i class="ri-notification-line me-2"></i> Notification Settings
                        </a>
                        <a href="<?php echo e(route('admin.dashboard')); ?>" class="btn btn-outline-secondary">
                            <i class="ri-dashboard-line me-2"></i> Back to Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <!-- Profile Form -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Profile Information</h5>
                </div>
                <div class="card-body">
                    <form action="<?php echo e(route('admin.profile.update')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <!-- REMOVED: <?php echo method_field('PUT'); ?> - Using POST instead -->
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Full Name *</label>
                                <input type="text" class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                       id="name" name="name" value="<?php echo e(old('name', Auth::user()->name)); ?>" required>
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
                            
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email Address *</label>
                                <input type="email" class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                       id="email" name="email" value="<?php echo e(old('email', Auth::user()->email)); ?>" required>
                                <?php $__errorArgs = ['email'];
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
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">Phone Number</label>
                                <input type="tel" class="form-control <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                       id="phone" name="phone" value="<?php echo e(old('phone', Auth::user()->phone)); ?>">
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
                            
                            <div class="col-md-6 mb-3">
                                <label for="company" class="form-label">Company</label>
                                <input type="text" class="form-control <?php $__errorArgs = ['company'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                       id="company" name="company" value="<?php echo e(old('company', Auth::user()->company)); ?>">
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
                        </div>
                        
                        <div class="mb-3">
                            <label for="bio" class="form-label">Bio / About</label>
                            <textarea class="form-control <?php $__errorArgs = ['bio'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                      id="bio" name="bio" rows="3" maxlength="500"><?php echo e(old('bio', Auth::user()->bio)); ?></textarea>
                            <small class="text-muted">Brief description about yourself (max 500 characters)</small>
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
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="language" class="form-label">Language</label>
                                <select class="form-select <?php $__errorArgs = ['language'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="language" name="language">
                                    <option value="en" <?php echo e((Auth::user()->settings['display']['language'] ?? 'en') == 'en' ? 'selected' : ''); ?>>English</option>
                                    <option value="es" <?php echo e((Auth::user()->settings['display']['language'] ?? 'en') == 'es' ? 'selected' : ''); ?>>Spanish</option>
                                    <option value="fr" <?php echo e((Auth::user()->settings['display']['language'] ?? 'en') == 'fr' ? 'selected' : ''); ?>>French</option>
                                </select>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="timezone" class="form-label">Timezone</label>
                                <select class="form-select <?php $__errorArgs = ['timezone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="timezone" name="timezone">
                                    <option value="UTC" <?php echo e((Auth::user()->settings['display']['timezone'] ?? 'UTC') == 'UTC' ? 'selected' : ''); ?>>UTC</option>
                                    <option value="America/New_York" <?php echo e((Auth::user()->settings['display']['timezone'] ?? 'UTC') == 'America/New_York' ? 'selected' : ''); ?>>Eastern Time (ET)</option>
                                    <option value="America/Chicago" <?php echo e((Auth::user()->settings['display']['timezone'] ?? 'UTC') == 'America/Chicago' ? 'selected' : ''); ?>>Central Time (CT)</option>
                                    <option value="America/Denver" <?php echo e((Auth::user()->settings['display']['timezone'] ?? 'UTC') == 'America/Denver' ? 'selected' : ''); ?>>Mountain Time (MT)</option>
                                    <option value="America/Los_Angeles" <?php echo e((Auth::user()->settings['display']['timezone'] ?? 'UTC') == 'America/Los_Angeles' ? 'selected' : ''); ?>>Pacific Time (PT)</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="date_format" class="form-label">Date Format</label>
                                <select class="form-select <?php $__errorArgs = ['date_format'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="date_format" name="date_format">
                                    <option value="m/d/Y" <?php echo e((Auth::user()->settings['display']['date_format'] ?? 'm/d/Y') == 'm/d/Y' ? 'selected' : ''); ?>>MM/DD/YYYY</option>
                                    <option value="d/m/Y" <?php echo e((Auth::user()->settings['display']['date_format'] ?? 'm/d/Y') == 'd/m/Y' ? 'selected' : ''); ?>>DD/MM/YYYY</option>
                                    <option value="Y-m-d" <?php echo e((Auth::user()->settings['display']['date_format'] ?? 'm/d/Y') == 'Y-m-d' ? 'selected' : ''); ?>>YYYY-MM-DD</option>
                                </select>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="time_format" class="form-label">Time Format</label>
                                <select class="form-select <?php $__errorArgs = ['time_format'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="time_format" name="time_format">
                                    <option value="12h" <?php echo e((Auth::user()->settings['display']['time_format'] ?? '12h') == '12h' ? 'selected' : ''); ?>>12-hour (AM/PM)</option>
                                    <option value="24h" <?php echo e((Auth::user()->settings['display']['time_format'] ?? '12h') == '24h' ? 'selected' : ''); ?>>24-hour</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="ri-save-line me-2"></i> Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Notification Preferences -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Notification Preferences</h5>
                </div>
                <div class="card-body">
                    <form action="<?php echo e(route('admin.profile.notification-preferences')); ?>" method="POST" id="notificationPreferencesForm">
                        <?php echo csrf_field(); ?>
                        <?php
                            $preferences = Auth::user()->notification_preferences;
                        ?>
                        
                        <div class="mb-4">
                            <h6 class="mb-3">Email Notifications</h6>
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" id="email_notifications" 
                                       name="email_notifications" <?php echo e($preferences['email_notifications'] ?? true ? 'checked' : ''); ?>>
                                <label class="form-check-label" for="email_notifications">
                                    Enable email notifications
                                </label>
                            </div>
                            
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" id="push_notifications" 
                                       name="push_notifications" <?php echo e($preferences['push_notifications'] ?? true ? 'checked' : ''); ?>>
                                <label class="form-check-label" for="push_notifications">
                                    Enable push notifications
                                </label>
                            </div>
                            
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" id="marketing_emails" 
                                       name="marketing_emails" <?php echo e($preferences['marketing_emails'] ?? false ? 'checked' : ''); ?>>
                                <label class="form-check-label" for="marketing_emails">
                                    Receive marketing emails
                                </label>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <h6 class="mb-3">Notification Types</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" id="shipment_updates" 
                                               name="shipment_updates" <?php echo e($preferences['shipment_updates'] ?? true ? 'checked' : ''); ?>>
                                        <label class="form-check-label" for="shipment_updates">
                                            Shipment updates
                                        </label>
                                    </div>
                                    
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" id="payment_updates" 
                                               name="payment_updates" <?php echo e($preferences['payment_updates'] ?? true ? 'checked' : ''); ?>>
                                        <label class="form-check-label" for="payment_updates">
                                            Payment updates
                                        </label>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" id="document_updates" 
                                               name="document_updates" <?php echo e($preferences['document_updates'] ?? true ? 'checked' : ''); ?>>
                                        <label class="form-check-label" for="document_updates">
                                            Document updates
                                        </label>
                                    </div>
                                    
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" id="system_updates" 
                                               name="system_updates" <?php echo e($preferences['system_updates'] ?? true ? 'checked' : ''); ?>>
                                        <label class="form-check-label" for="system_updates">
                                            System updates
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary" id="savePreferencesBtn">
                                <i class="ri-save-line me-2"></i> Save Preferences
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
    // Profile picture preview and auto-upload
    document.getElementById('profilePictureInput').addEventListener('change', function() {
        if (this.files && this.files[0]) {
            // Show loading
            const button = this.parentElement.querySelector('button');
            const originalHTML = button.innerHTML;
            button.innerHTML = '<i class="ri-loader-4-line me-1"></i> Uploading...';
            button.disabled = true;
            
            // Submit form
            this.form.submit();
        }
    });
    
    // Handle notification preferences form submission
    document.getElementById('notificationPreferencesForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const form = this;
        const button = document.getElementById('savePreferencesBtn');
        const originalHTML = button.innerHTML;
        
        // Show loading
        button.innerHTML = '<i class="ri-loader-4-line me-2"></i> Saving...';
        button.disabled = true;
        
        // Submit via AJAX for better UX
        fetch(form.action, {
            method: 'POST',
            body: new FormData(form),
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Show success message
                button.innerHTML = '<i class="ri-check-line me-2"></i> Saved!';
                button.classList.remove('btn-primary');
                button.classList.add('btn-success');
                
                // Reset button after 2 seconds
                setTimeout(() => {
                    button.innerHTML = originalHTML;
                    button.classList.remove('btn-success');
                    button.classList.add('btn-primary');
                    button.disabled = false;
                }, 2000);
            } else {
                throw new Error('Failed to save preferences');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            button.innerHTML = '<i class="ri-error-warning-line me-2"></i> Error';
            button.classList.remove('btn-primary');
            button.classList.add('btn-danger');
            
            // Reset button after 3 seconds
            setTimeout(() => {
                button.innerHTML = originalHTML;
                button.classList.remove('btn-danger');
                button.classList.add('btn-primary');
                button.disabled = false;
                
                // Fallback to regular form submission if AJAX fails
                form.submit();
            }, 3000);
        });
    });
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Dee\Desktop\General\globalskyfleet\resources\views\admin\profile\edit.blade.php ENDPATH**/ ?>