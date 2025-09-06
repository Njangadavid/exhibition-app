<?php if (isset($component)) { $__componentOriginalb1882f8c14f0a5270b201bcf650aaac1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb1882f8c14f0a5270b201bcf650aaac1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.event-layout','data' => ['event' => $event]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('event-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['event' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($event)]); ?>

    <div class="py-4">
        <div class="container-fluid">
                        <!-- Page Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="h4 mb-1 fw-bold">
                        <i class="bi bi-pencil-square me-2 text-success"></i>
                        Participant Forms
                    </h2>
                                            <p class="text-muted mb-0">Create and manage custom registration forms for <?php echo e($event->name); ?></p>
                </div>
                <a href="<?php echo e(route('events.form-builders.create', $event)); ?>" class="btn btn-success">
                    <i class="bi bi-plus-circle me-2"></i>Create New Form
                </a>
            </div>

            <!-- Quick Stats Dashboard -->
            <div class="row mb-4">
                <div class="col-md-6 col-lg-3 mb-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                                        <i class="bi bi-file-earmark-text text-primary fs-2"></i>
                                    </div>
                                </div>
                                <div class="ms-3">
                                    <div class="text-muted small fw-medium">Total Forms</div>
                                    <div class="h3 fw-bold text-dark mb-0"><?php echo e($formBuilders->count()); ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3 mb-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="bg-success bg-opacity-10 rounded-circle p-3">
                                        <i class="bi bi-check-circle text-success fs-2"></i>
                                    </div>
                                </div>
                                <div class="ms-3">
                                    <div class="text-muted small fw-medium">Published Forms</div>
                                    <div class="h3 fw-bold text-success mb-0"><?php echo e($formBuilders->where('status', 'published')->count()); ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3 mb-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="bg-warning bg-opacity-10 rounded-circle p-3">
                                        <i class="bi bi-pencil-square text-warning fs-2"></i>
                                    </div>
                                </div>
                                <div class="ms-3">
                                    <div class="text-muted small fw-medium">Draft Forms</div>
                                    <div class="h3 fw-bold text-warning mb-0"><?php echo e($formBuilders->where('status', 'draft')->count()); ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3 mb-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="bg-secondary bg-opacity-10 rounded-circle p-3">
                                        <i class="bi bi-archive text-secondary fs-2"></i>
                                    </div>
                                </div>
                                <div class="ms-3">
                                    <div class="text-muted small fw-medium">Archived Forms</div>
                                    <div class="h3 fw-bold text-secondary mb-0"><?php echo e($formBuilders->where('status', 'archived')->count()); ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Forms List - Card Layout -->
            <?php if($formBuilders->count() > 0): ?>
                <div class="row g-4">
                    <?php $__currentLoopData = $formBuilders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $formBuilder): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-header bg-transparent border-0 pb-0">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                            <i class="bi bi-file-earmark-text text-primary"></i>
                                        </div>
                                        <div>
                                            <h5 class="card-title mb-1 fw-bold"><?php echo e($formBuilder->name); ?></h5>
                                            <span class="badge 
                                                <?php if($formBuilder->status === 'published'): ?> bg-success
                                                <?php elseif($formBuilder->status === 'draft'): ?> bg-warning
                                                <?php else: ?> bg-secondary <?php endif; ?>">
                                                <?php echo e(ucfirst($formBuilder->status)); ?>

                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="card-body pt-0">
                                <p class="text-muted small mb-3">
                                    <?php echo e($formBuilder->description ?: 'No description provided'); ?>

                                </p>
                                
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="badge bg-info bg-opacity-10 text-info">
                                        <i class="bi bi-list-ul me-1"></i>
                                        <?php echo e($formBuilder->fields->count()); ?> fields
                                    </span>
                                    <small class="text-muted">
                                        <i class="bi bi-calendar3 me-1"></i>
                                        <?php echo e($formBuilder->created_at->format('M d, Y')); ?>

                                    </small>
                                </div>
                            </div>
                            
                            <div class="card-footer bg-transparent border-0 pt-0">
                                <div class="d-grid gap-2">
                                    <div class="btn-group" role="group">
                                        <a href="<?php echo e(route('events.form-builders.show', [$event, $formBuilder])); ?>" 
                                           class="btn btn-outline-primary btn-sm">
                                            <i class="bi bi-eye me-1"></i>View
                                        </a>
                                        <a href="<?php echo e(route('events.form-builders.edit', [$event, $formBuilder])); ?>" 
                                           class="btn btn-outline-warning btn-sm">
                                            <i class="bi bi-pencil me-1"></i>Edit
                                        </a>
                                        <a href="<?php echo e(route('events.form-builders.preview', [$event, $formBuilder])); ?>" 
                                           class="btn btn-outline-info btn-sm" target="_blank">
                                            <i class="bi bi-eye-fill me-1"></i>Preview
                                        </a>
                                    </div>
                                    
                                    <form action="<?php echo e(route('events.form-builders.destroy', [$event, $formBuilder])); ?>" 
                                          method="POST" class="d-grid" 
                                          onsubmit="return confirm('Are you sure you want to delete this form?')">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn btn-outline-danger btn-sm">
                                            <i class="bi bi-trash me-1"></i>Delete Form
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php else: ?>
                <!-- Empty State -->
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="bi bi-file-earmark-text text-muted fs-1 d-block mb-3"></i>
                        <h3 class="h5 mb-2">No Forms Created Yet</h3>
                        <p class="text-muted mb-3">Start building custom registration forms for your event participants.</p>
                        <a href="<?php echo e(route('events.form-builders.create', $event)); ?>" class="btn btn-primary">
                            <i class="bi bi-plus-circle me-2"></i>Create Your First Form
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalb1882f8c14f0a5270b201bcf650aaac1)): ?>
<?php $attributes = $__attributesOriginalb1882f8c14f0a5270b201bcf650aaac1; ?>
<?php unset($__attributesOriginalb1882f8c14f0a5270b201bcf650aaac1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalb1882f8c14f0a5270b201bcf650aaac1)): ?>
<?php $component = $__componentOriginalb1882f8c14f0a5270b201bcf650aaac1; ?>
<?php unset($__componentOriginalb1882f8c14f0a5270b201bcf650aaac1); ?>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\exhibition-app\resources\views/form-builders/index.blade.php ENDPATH**/ ?>