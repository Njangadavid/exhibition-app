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
                        <li class="breadcrumb-item active">Role Management</li>
                    </ol>
                </div>
                <h4 class="page-title">Role & Permission Management</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <span class="badge bg-<?php echo e($role->name === 'admin' ? 'danger' : ($role->name === 'organizer' ? 'warning' : 'secondary')); ?> me-2">
                                <?php echo e($role->display_name); ?>

                            </span>
                        </h5>
                        <small class="text-muted"><?php echo e($role->users->count()); ?> users</small>
                    </div>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-3"><?php echo e($role->description); ?></p>
                    
                    <form method="POST" action="<?php echo e(route('admin.roles.permissions', $role)); ?>">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PATCH'); ?>
                        
                        <div class="row">
                            <?php $__currentLoopData = $permissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category => $categoryPermissions): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="col-md-6 mb-3">
                                <h6 class="text-muted border-bottom pb-1"><?php echo e(ucwords(str_replace('_', ' ', $category))); ?></h6>
                                <?php $__currentLoopData = $categoryPermissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $permission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" 
                                           id="permission_<?php echo e($role->id); ?>_<?php echo e($permission->id); ?>" 
                                           name="permissions[]" 
                                           value="<?php echo e($permission->id); ?>"
                                           <?php echo e($role->permissions->contains($permission) ? 'checked' : ''); ?>>
                                    <label class="form-check-label" for="permission_<?php echo e($role->id); ?>_<?php echo e($permission->id); ?>">
                                        <?php echo e($permission->display_name); ?>

                                        <?php if($permission->description): ?>
                                            <br><small class="text-muted"><?php echo e($permission->description); ?></small>
                                        <?php endif; ?>
                                    </label>
                                </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                        
                        <div class="d-flex justify-content-end mt-3">
                            <button type="submit" class="btn btn-primary btn-sm">
                                <i class="bi bi-check-circle me-1"></i>Update Permissions
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <!-- Permission Categories Overview -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Permission Categories Overview</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <?php $__currentLoopData = $permissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category => $categoryPermissions): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col-lg-3 col-md-6 mb-4">
                            <div class="card border">
                                <div class="card-body text-center">
                                    <div class="mb-2">
                                        <?php switch($category):
                                            case ('user_management'): ?>
                                                <i class="bi bi-people fs-1 text-primary"></i>
                                                <?php break; ?>
                                            <?php case ('event_management'): ?>
                                                <i class="bi bi-calendar-event fs-1 text-success"></i>
                                                <?php break; ?>
                                            <?php case ('floorplan_management'): ?>
                                                <i class="bi bi-grid-3x3-gap fs-1 text-info"></i>
                                                <?php break; ?>
                                            <?php case ('booking_management'): ?>
                                                <i class="bi bi-bookmark-check fs-1 text-warning"></i>
                                                <?php break; ?>
                                            <?php case ('payment_management'): ?>
                                                <i class="bi bi-credit-card fs-1 text-danger"></i>
                                                <?php break; ?>
                                            <?php case ('form_management'): ?>
                                                <i class="bi bi-file-text fs-1 text-secondary"></i>
                                                <?php break; ?>
                                            <?php case ('email_management'): ?>
                                                <i class="bi bi-envelope fs-1 text-dark"></i>
                                                <?php break; ?>
                                            <?php case ('system_administration'): ?>
                                                <i class="bi bi-gear fs-1 text-primary"></i>
                                                <?php break; ?>
                                            <?php default: ?>
                                                <i class="bi bi-shield-check fs-1 text-muted"></i>
                                        <?php endswitch; ?>
                                    </div>
                                    <h6 class="card-title"><?php echo e(ucwords(str_replace('_', ' ', $category))); ?></h6>
                                    <p class="text-muted small"><?php echo e($categoryPermissions->count()); ?> permissions</p>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Add some interactivity to the role management
document.addEventListener('DOMContentLoaded', function() {
    // Add confirmation for permission updates
    const forms = document.querySelectorAll('form[action*="roles.permissions"]');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const roleName = this.closest('.card').querySelector('.badge').textContent.trim();
            if (!confirm(`Are you sure you want to update permissions for the "${roleName}" role?`)) {
                e.preventDefault();
            }
        });
    });
});
</script>
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
<?php /**PATH C:\xampp\htdocs\exhibition-app\resources\views/admin/users/roles.blade.php ENDPATH**/ ?>