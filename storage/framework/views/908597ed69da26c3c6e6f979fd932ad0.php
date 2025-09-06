<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="<?php echo e(route('admin.users.index')); ?>">User Management</a></li>
                        <li class="breadcrumb-item active">User Details</li>
                    </ol>
                </div>
                <h4 class="page-title">User Details: <?php echo e($user->name); ?></h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">User Information</h5>
                        <div class="d-flex gap-2">
                            <a href="<?php echo e(route('admin.users.edit', $user)); ?>" class="btn btn-primary">
                                <i class="bi bi-pencil me-1"></i>Edit User
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Full Name</label>
                                <p class="form-control-plaintext"><?php echo e($user->name); ?></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Email Address</label>
                                <p class="form-control-plaintext"><?php echo e($user->email); ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">User ID</label>
                                <p class="form-control-plaintext"><?php echo e($user->id); ?></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Status</label>
                                <p class="form-control-plaintext">
                                    <?php if($user->is_active): ?>
                                        <span class="badge bg-success">Active</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Inactive</span>
                                    <?php endif; ?>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Email Verified</label>
                                <p class="form-control-plaintext">
                                    <?php if($user->email_verified_at): ?>
                                        <span class="badge bg-success">Yes</span>
                                        <small class="text-muted">(<?php echo e($user->email_verified_at->format('M d, Y H:i')); ?>)</small>
                                    <?php else: ?>
                                        <span class="badge bg-warning">No</span>
                                    <?php endif; ?>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Last Login</label>
                                <p class="form-control-plaintext">
                                    <?php if($user->last_login_at ?? false): ?>
                                        <?php echo e($user->last_login_at->format('M d, Y H:i')); ?>

                                    <?php else: ?>
                                        <span class="text-muted">Never</span>
                                    <?php endif; ?>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Created</label>
                                <p class="form-control-plaintext"><?php echo e($user->created_at->format('M d, Y H:i')); ?></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Last Updated</label>
                                <p class="form-control-plaintext"><?php echo e($user->updated_at->format('M d, Y H:i')); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Roles and Permissions -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">Roles & Permissions</h5>
                </div>
                <div class="card-body">
                    <?php if($user->roles->count() > 0): ?>
                        <?php $__currentLoopData = $user->roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="mb-4">
                            <div class="d-flex align-items-center mb-2">
                                <span class="badge bg-<?php echo e($role->name === 'admin' ? 'danger' : ($role->name === 'organizer' ? 'warning' : 'secondary')); ?> me-2 fs-6">
                                    <?php echo e($role->display_name); ?>

                                </span>
                                <small class="text-muted"><?php echo e($role->description); ?></small>
                            </div>
                            
                            <?php if($role->permissions->count() > 0): ?>
                                <div class="row">
                                    <?php $__currentLoopData = $role->permissions->groupBy('category'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category => $permissions): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="col-md-6 mb-3">
                                        <h6 class="text-muted"><?php echo e(ucwords(str_replace('_', ' ', $category))); ?></h6>
                                        <ul class="list-unstyled">
                                            <?php $__currentLoopData = $permissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $permission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <li class="small text-muted">• <?php echo e($permission->display_name); ?></li>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </ul>
                                    </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            <?php else: ?>
                                <p class="text-muted small">No permissions assigned to this role.</p>
                            <?php endif; ?>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <i class="bi bi-shield-x fs-1 text-muted d-block mb-3"></i>
                            <h5 class="text-muted">No Roles Assigned</h5>
                            <p class="text-muted">This user has no roles assigned and cannot access any protected features.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Quick Actions -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="<?php echo e(route('admin.users.edit', $user)); ?>" class="btn btn-primary">
                            <i class="bi bi-pencil me-1"></i>Edit User
                        </a>
                        
                        <?php if($user->id !== auth()->id()): ?>
                            <form method="POST" action="<?php echo e(route('admin.users.toggle-status', $user)); ?>" class="d-inline">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('PATCH'); ?>
                                <button type="submit" 
                                        class="btn btn-<?php echo e($user->is_active ? 'warning' : 'success'); ?> w-100"
                                        onclick="return confirm('Are you sure you want to <?php echo e($user->is_active ? 'deactivate' : 'activate'); ?> this user?')">
                                    <i class="bi bi-<?php echo e($user->is_active ? 'pause' : 'play'); ?> me-1"></i>
                                    <?php echo e($user->is_active ? 'Deactivate' : 'Activate'); ?> User
                                </button>
                            </form>
                            
                            <form method="POST" action="<?php echo e(route('admin.users.destroy', $user)); ?>" class="d-inline">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" 
                                        class="btn btn-danger w-100"
                                        onclick="return confirm('Are you sure you want to delete this user? This action cannot be undone.')">
                                    <i class="bi bi-trash me-1"></i>Delete User
                                </button>
                            </form>
                        <?php endif; ?>
                        
                        <a href="<?php echo e(route('admin.users.index')); ?>" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-1"></i>Back to Users
                        </a>
                    </div>
                </div>
            </div>

            <!-- User Statistics -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">Statistics</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="border-end">
                                <h4 class="text-primary mb-1"><?php echo e($user->roles->count()); ?></h4>
                                <small class="text-muted">Roles</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <h4 class="text-success mb-1"><?php echo e($user->permissions()->count()); ?></h4>
                            <small class="text-muted">Permissions</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\exhibition-app\resources\views/admin/users/show.blade.php ENDPATH**/ ?>