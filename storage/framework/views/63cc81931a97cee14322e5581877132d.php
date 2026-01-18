

<?php $__env->startSection('page-title', 'User Management'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="row g-3">
                <div class="col-md-3 col-sm-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-1">Total Users</h6>
                                    <h3 class="mb-0"><?php echo e($totalUsers ?? 0); ?></h3>
                                </div>
                                <div class="rounded-circle bg-primary bg-opacity-10 p-3">
                                    <i class="ri-user-line text-primary fs-4"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3 col-sm-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-1">Admins</h6>
                                    <h3 class="mb-0"><?php echo e($adminUsers ?? 0); ?></h3>
                                </div>
                                <div class="rounded-circle bg-danger bg-opacity-10 p-3">
                                    <i class="ri-shield-user-line text-danger fs-4"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3 col-sm-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-1">Regular Users</h6>
                                    <h3 class="mb-0"><?php echo e($regularUsers ?? 0); ?></h3>
                                </div>
                                <div class="rounded-circle bg-success bg-opacity-10 p-3">
                                    <i class="ri-user-3-line text-success fs-4"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3 col-sm-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-1">Active Today</h6>
                                    <h3 class="mb-0"><?php echo e($activeToday ?? 0); ?></h3>
                                </div>
                                <div class="rounded-circle bg-info bg-opacity-10 p-3">
                                    <i class="ri-user-star-line text-info fs-4"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <form action="<?php echo e(route('admin.users')); ?>" method="GET" class="row g-3">
                        <div class="col-md-4">
                            <input type="text" name="search" class="form-control" 
                                   placeholder="Search by name, email, or phone..." 
                                   value="<?php echo e(request('search')); ?>">
                        </div>
                        <div class="col-md-3">
                            <select name="role" class="form-select">
                                <option value="">All Roles</option>
                                <option value="user" <?php echo e(request('role') == 'user' ? 'selected' : ''); ?>>Regular User</option>
                                <option value="admin" <?php echo e(request('role') == 'admin' ? 'selected' : ''); ?>>Admin</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select name="status" class="form-select">
                                <option value="">All Status</option>
                                <option value="active" <?php echo e(request('status') == 'active' ? 'selected' : ''); ?>>Active</option>
                                <option value="inactive" <?php echo e(request('status') == 'inactive' ? 'selected' : ''); ?>>Inactive</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100">Filter</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Users List -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="ri-user-line me-2"></i>All Users</h5>
                    <span class="badge bg-primary"><?php echo e($users->total()); ?> users</span>
                </div>
                <div class="card-body p-0">
                    <?php if($users->isEmpty()): ?>
                    <div class="text-center py-5">
                        <i class="ri-user-line display-1 text-muted opacity-25"></i>
                        <h5 class="mt-3 text-muted">No Users Found</h5>
                    </div>
                    <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th width="50">ID</th>
                                    <th>User</th>
                                    <th width="150">Contact</th>
                                    <th width="120">Role</th>
                                    <th width="120">Status</th>
                                    <th width="150">Joined</th>
                                    <th width="120">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td>#<?php echo e($user->id); ?></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center me-3" 
                                                 style="width: 40px; height: 40px; font-weight: bold;">
                                                <?php echo e($user->initials); ?>

                                            </div>
                                            <div>
                                                <strong class="d-block"><?php echo e($user->name); ?></strong>
                                                <small class="text-muted"><?php echo e($user->email); ?></small>
                                                <?php if($user->company): ?>
                                                <br><small class="text-muted"><?php echo e($user->company); ?></small>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <?php if($user->phone): ?>
                                            <small><?php echo e($user->phone); ?></small>
                                        <?php else: ?>
                                            <span class="text-muted">No phone</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if($user->role === 'admin'): ?>
                                            <span class="badge bg-danger">Admin</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">User</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if($user->email_verified_at): ?>
                                            <span class="badge bg-success">Verified</span>
                                        <?php else: ?>
                                            <span class="badge bg-warning">Unverified</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <small><?php echo e($user->created_at->format('M d, Y')); ?></small>
                                        <br>
                                        <small class="text-muted"><?php echo e($user->created_at->format('H:i')); ?></small>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="<?php echo e(route('admin.users.show', $user->id)); ?>" 
                                               class="btn btn-outline-primary" title="View Details">
                                                <i class="ri-eye-line"></i>
                                            </a>
                                            <button type="button" class="btn btn-outline-info" 
                                                    data-bs-toggle="modal" data-bs-target="#roleModal<?php echo e($user->id); ?>"
                                                    title="Change Role">
                                                <i class="ri-user-settings-line"></i>
                                            </button>
                                            <?php if(auth()->id() !== $user->id): ?>
                                            <form action="<?php echo e(route('admin.users.destroy', $user->id)); ?>" 
                                                  method="POST" class="d-inline"
                                                  onsubmit="return confirm('Are you sure you want to delete this user? This will also delete all their shipments, documents, and payments.');">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button type="submit" class="btn btn-outline-danger" 
                                                        title="Delete User">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                            </form>
                                            <?php else: ?>
                                            <button class="btn btn-outline-secondary" disabled 
                                                    title="Cannot delete yourself">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Role Change Modal -->
                                <div class="modal fade" id="roleModal<?php echo e($user->id); ?>" tabindex="-1">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Change User Role</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form action="<?php echo e(route('admin.users.update-role', $user->id)); ?>" method="POST">
                                                <?php echo csrf_field(); ?>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label fw-semibold">User</label>
                                                        <input type="text" class="form-control bg-light" 
                                                               value="<?php echo e($user->name); ?> (<?php echo e($user->email); ?>)" readonly>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label fw-semibold">Current Role</label>
                                                        <input type="text" class="form-control bg-light" 
                                                               value="<?php echo e(ucfirst($user->role)); ?>" readonly>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label fw-semibold">New Role *</label>
                                                        <select name="role" class="form-select" required>
                                                            <option value="user" <?php echo e($user->role == 'user' ? 'selected' : ''); ?>>Regular User</option>
                                                            <option value="admin" <?php echo e($user->role == 'admin' ? 'selected' : ''); ?>>Admin</option>
                                                            <?php if(auth()->user()->email == 'superadmin@globalskyfleet.com'): ?>
                                                            <option value="superadmin" <?php echo e($user->role == 'superadmin' ? 'selected' : ''); ?>>Super Admin</option>
                                                            <?php endif; ?>
                                                        </select>
                                                        <small class="text-muted">Admins have full access to admin panel</small>
                                                    </div>
                                                    <div class="alert alert-warning">
                                                        <i class="ri-alert-line"></i>
                                                        <strong>Warning:</strong> Changing role to Admin will give this user full access to the admin dashboard.
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-primary">Update Role</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <?php if($users->hasPages()): ?>
                    <div class="p-3 border-top">
                        <?php echo e($users->links()); ?>

                    </div>
                    <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Dee\Desktop\General\globalskyfleet\resources\views\admin\users\index.blade.php ENDPATH**/ ?>