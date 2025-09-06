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
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h4 mb-0">
            <i class="bi bi-eye me-2"></i>
            <?php echo e($formBuilder->name); ?> - <?php echo e($event->title); ?>

        </h2>
        <div class="d-flex gap-2">
            <a href="<?php echo e(route('events.form-builders.index', $event)); ?>" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-2"></i>Back to Forms
            </a>
            <a href="<?php echo e(route('events.form-builders.edit', [$event, $formBuilder])); ?>" class="btn btn-warning">
                <i class="bi bi-pencil me-2"></i>Edit Form
            </a>
            <a href="<?php echo e(route('events.form-builders.preview', [$event, $formBuilder])); ?>" class="btn btn-info" target="_blank">
                <i class="bi bi-eye-fill me-2"></i>Preview
            </a>
        </div>
    </div>

    <div class="card">
            <!-- Event Header -->
                <div class="position-relative">
                    <div class="bg-gradient-to-br from-blue-400 to-purple-600 d-flex align-items-center justify-content-center position-relative overflow-hidden" style="height: 150px;">
                        <?php if($event->logo): ?>
                            <img src="<?php echo e(Storage::url($event->logo)); ?>" alt="<?php echo e($event->title); ?>" class="w-100 h-100 object-fit-cover">
                        <?php else: ?>
                            <i class="bi bi-calendar-event text-white" style="font-size: 3rem;"></i>
                        <?php endif; ?>
                        
                        <div class="position-absolute top-0 end-0 m-3">
                            <span class="badge 
                                <?php if($event->status === 'active'): ?> bg-success
                                <?php elseif($event->status === 'published'): ?> bg-primary
                                <?php elseif($event->status === 'completed'): ?> bg-info
                                <?php elseif($event->status === 'cancelled'): ?> bg-danger
                                <?php else: ?> bg-secondary <?php endif; ?> fs-6">
                                <?php echo e(ucfirst($event->status)); ?>

                            </span>
                        </div>
                    </div>

                    <div class="position-absolute bottom-0 start-0 end-0 bg-dark bg-opacity-75 text-white p-3">
                        <h1 class="h4 mb-1"><?php echo e($event->title); ?></h1>
                        <p class="mb-0 opacity-75 small"><?php echo e($event->start_date->format('F d, Y')); ?> - <?php echo e($event->end_date->format('F d, Y')); ?></p>
                    </div>
                </div>
            </div>

            <!-- Event Navigation Menu -->
            <div class="card mb-4">
                <div class="card-body p-3">
                    <ul class="nav nav-tabs nav-fill">
                        <li class="nav-item">
                            <a href="<?php echo e(route('events.dashboard', $event)); ?>" class="nav-link">
                                <i class="bi bi-speedometer2 me-2"></i>
                                Event Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo e(route('events.floorplan', $event)); ?>" class="nav-link">
                                <i class="bi bi-map me-2"></i>
                                Floorplan
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo e(route('events.registration', $event)); ?>" class="nav-link">
                                <i class="bi bi-person-plus me-2"></i>
                                Registration Form
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="row">
                <!-- Form Details -->
                <div class="col-lg-8">
                    <!-- Form Information -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="bi bi-info-circle me-2"></i>Form Information
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Form Name</label>
                                        <p class="mb-0"><?php echo e($formBuilder->name); ?></p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Status</label>
                                        <div>
                                            <span class="badge 
                                                <?php if($formBuilder->status === 'published'): ?> bg-success
                                                <?php elseif($formBuilder->status === 'draft'): ?> bg-warning
                                                <?php else: ?> bg-secondary <?php endif; ?> fs-6">
                                                <?php echo e(ucfirst($formBuilder->status)); ?>

                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <?php if($formBuilder->description): ?>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Description</label>
                                <p class="mb-0"><?php echo e($formBuilder->description); ?></p>
                            </div>
                            <?php endif; ?>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Submit Button Text</label>
                                        <p class="mb-0"><?php echo e($formBuilder->submit_button_text); ?></p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Theme Color</label>
                                        <div class="d-flex align-items-center">
                                            <div class="color-preview me-2" style="width: 20px; height: 20px; background-color: <?php echo e($formBuilder->theme_color); ?>; border-radius: 4px;"></div>
                                            <span><?php echo e($formBuilder->theme_color); ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Multiple Submissions</label>
                                        <p class="mb-0">
                                            <i class="bi bi-<?php echo e($formBuilder->allow_multiple_submissions ? 'check-circle text-success' : 'x-circle text-danger'); ?>"></i>
                                            <?php echo e($formBuilder->allow_multiple_submissions ? 'Allowed' : 'Not Allowed'); ?>

                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Login Required</label>
                                        <p class="mb-0">
                                            <i class="bi bi-<?php echo e($formBuilder->require_login ? 'check-circle text-success' : 'x-circle text-danger'); ?>"></i>
                                            <?php echo e($formBuilder->require_login ? 'Required' : 'Not Required'); ?>

                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Confirmation Email</label>
                                        <p class="mb-0">
                                            <i class="bi bi-<?php echo e($formBuilder->send_confirmation_email ? 'check-circle text-success' : 'x-circle text-danger'); ?>"></i>
                                            <?php echo e($formBuilder->send_confirmation_email ? 'Enabled' : 'Disabled'); ?>

                                        </p>
                                    </div>
                                </div>
                            </div>

                            <?php if($formBuilder->confirmation_message): ?>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Confirmation Message</label>
                                <p class="mb-0"><?php echo e($formBuilder->confirmation_message); ?></p>
                            </div>
                            <?php endif; ?>

                            <?php if($formBuilder->redirect_url): ?>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Redirect URL</label>
                                <p class="mb-0">
                                    <a href="<?php echo e($formBuilder->redirect_url); ?>" target="_blank"><?php echo e($formBuilder->redirect_url); ?></a>
                                </p>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Form Fields -->
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="bi bi-list-ul me-2"></i>Form Fields (<?php echo e($formBuilder->fields->count()); ?>)
                            </h5>
                        </div>
                        <div class="card-body">
                            <?php if($formBuilder->fields->count() > 0): ?>
                                <div class="row">
                                    <?php $__currentLoopData = $formBuilder->fields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="col-md-6 mb-3">
                                        <div class="border rounded p-3 bg-light">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <span class="badge bg-primary"><?php echo e(ucfirst($field->type)); ?></span>
                                                <?php if($field->required): ?>
                                                    <span class="badge bg-danger">Required</span>
                                                <?php endif; ?>
                                            </div>
                                            <h6 class="mb-2"><?php echo e($field->label); ?></h6>
                                            
                                            <?php if($field->placeholder): ?>
                                                <small class="text-muted d-block mb-1">
                                                    <i class="bi bi-quote me-1"></i><?php echo e($field->placeholder); ?>

                                                </small>
                                            <?php endif; ?>
                                            
                                            <?php if($field->help_text): ?>
                                                <small class="text-muted d-block mb-1">
                                                    <i class="bi bi-info-circle me-1"></i><?php echo e($field->help_text); ?>

                                                </small>
                                            <?php endif; ?>
                                            
                                            <?php if($field->options): ?>
                                                <small class="text-muted d-block">
                                                    <i class="bi bi-list me-1"></i>Options: <?php echo e(implode(', ', $field->options)); ?>

                                                </small>
                                            <?php endif; ?>
                                            
                                            <div class="mt-2">
                                                <span class="badge bg-secondary"><?php echo e(ucfirst($field->width)); ?> Width</span>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            <?php else: ?>
                                <div class="text-center text-muted py-4">
                                    <i class="bi bi-list-ul fs-1 d-block mb-2"></i>
                                    <p class="mb-0">No fields defined for this form</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="col-lg-4">
                    <!-- Quick Stats -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0">
                                <i class="bi bi-graph-up me-2"></i>Quick Stats
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-6">
                                    <div class="border-end">
                                        <h4 class="text-primary mb-1"><?php echo e($formBuilder->submissions->count()); ?></h4>
                                        <small class="text-muted">Total Submissions</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <h4 class="text-success mb-1"><?php echo e($formBuilder->submissions->where('status', 'submitted')->count()); ?></h4>
                                    <small class="text-muted">Pending Review</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0">
                                <i class="bi bi-gear me-2"></i>Form Actions
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <?php if($formBuilder->status === 'draft'): ?>
                                    <form action="<?php echo e(route('events.form-builders.update', [$event, $formBuilder])); ?>" method="POST" class="d-inline">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('PUT'); ?>
                                        <input type="hidden" name="status" value="published">
                                        <button type="submit" class="btn btn-success w-100">
                                            <i class="bi bi-check-circle me-2"></i>Publish Form
                                        </button>
                                    </form>
                                <?php elseif($formBuilder->status === 'published'): ?>
                                    <form action="<?php echo e(route('events.form-builders.update', [$event, $formBuilder])); ?>" method="POST" class="d-inline">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('PUT'); ?>
                                        <input type="hidden" name="status" value="draft">
                                        <button type="submit" class="btn btn-warning w-100">
                                            <i class="bi bi-pause-circle me-2"></i>Unpublish Form
                                        </button>
                                    </form>
                                <?php endif; ?>
                                
                                <a href="<?php echo e(route('events.form-builders.preview', [$event, $formBuilder])); ?>" class="btn btn-info w-100" target="_blank">
                                    <i class="bi bi-eye-fill me-2"></i>Preview Form
                                </a>
                                
                                <a href="<?php echo e(route('events.form-builders.edit', [$event, $formBuilder])); ?>" class="btn btn-warning w-100">
                                    <i class="bi bi-pencil me-2"></i>Edit Form
                                </a>
                                
                                <form action="<?php echo e(route('events.form-builders.destroy', [$event, $formBuilder])); ?>" method="POST" class="d-inline" 
                                      onsubmit="return confirm('Are you sure you want to delete this form? This action cannot be undone.')">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-danger w-100">
                                        <i class="bi bi-trash me-2"></i>Delete Form
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Submissions -->
                    <?php if($formBuilder->submissions->count() > 0): ?>
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">
                                <i class="bi bi-clock-history me-2"></i>Recent Submissions
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="list-group list-group-flush">
                                <?php $__currentLoopData = $formBuilder->submissions->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $submission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="list-group-item px-0">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <small class="text-muted d-block">
                                                <?php echo e($submission->created_at->format('M d, Y H:i')); ?>

                                            </small>
                                            <span class="badge 
                                                <?php if($submission->status === 'submitted'): ?> bg-warning
                                                <?php elseif($submission->status === 'approved'): ?> bg-success
                                                <?php else: ?> bg-danger <?php endif; ?>">
                                                <?php echo e(ucfirst($submission->status)); ?>

                                            </span>
                                        </div>
                                        <?php if($submission->user): ?>
                                            <small class="text-muted"><?php echo e($submission->user->name); ?></small>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                            <?php if($formBuilder->submissions->count() > 5): ?>
                                <div class="text-center mt-2">
                                    <small class="text-muted">And <?php echo e($formBuilder->submissions->count() - 5); ?> more...</small>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
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
<?php /**PATH C:\xampp\htdocs\exhibition-app\resources\views/form-builders/show.blade.php ENDPATH**/ ?>