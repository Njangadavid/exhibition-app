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
                        <i class="bi bi-envelope me-2 text-primary"></i>
                        Email Templates
                    </h2>
                    <p class="text-muted mb-0">Manage automated email communications for <?php echo e($event->name); ?></p>
                </div>
                <a href="<?php echo e(route('events.email-templates.create', $event)); ?>" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-2"></i>Create Template
                </a>
            </div>

            <!-- Quick Stats -->
            <div class="row mb-4">
                <div class="col-md-6 col-lg-3 mb-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                                        <i class="bi bi-envelope text-primary fs-2"></i>
                                    </div>
                                </div>
                                <div class="ms-3">
                                    <div class="text-muted small fw-medium">Total Templates</div>
                                    <div class="h3 fw-bold text-dark mb-0"><?php echo e($templates->count()); ?></div>
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
                                    <div class="text-muted small fw-medium">Active Templates</div>
                                    <div class="h3 fw-bold text-success mb-0"><?php echo e($templates->where('is_active', true)->count()); ?></div>
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
                                        <i class="bi bi-pause-circle text-warning fs-2"></i>
                                    </div>
                                </div>
                                <div class="ms-3">
                                    <div class="text-muted small fw-medium">Inactive Templates</div>
                                    <div class="h3 fw-bold text-warning mb-0"><?php echo e($templates->where('is_active', false)->count()); ?></div>
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
                                    <div class="bg-info bg-opacity-10 rounded-circle p-3">
                                        <i class="bi bi-lightning text-info fs-2"></i>
                                    </div>
                                </div>
                                <div class="ms-3">
                                    <div class="text-muted small fw-medium">Trigger Types</div>
                                    <div class="h3 fw-bold text-info mb-0"><?php echo e($templates->pluck('trigger_type')->unique()->count()); ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Templates List -->
            <?php if($templates->count() > 0): ?>
                <div class="row g-4">
                    <?php $__currentLoopData = $templates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $template): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-header bg-transparent border-0 pb-0">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                            <i class="bi bi-envelope text-primary"></i>
                                        </div>
                                        <div>
                                            <h5 class="card-title mb-1 fw-bold"><?php echo e($template->name); ?></h5>
                                            <span class="badge 
                                                <?php if($template->is_active): ?> bg-success
                                                <?php else: ?> bg-secondary <?php endif; ?>">
                                                <?php echo e($template->is_active ? 'Active' : 'Inactive'); ?>

                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="card-body pt-0">
                                <p class="text-muted small mb-3">
                                    <strong>Subject:</strong> <?php echo e($template->subject); ?>

                                </p>
                                
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="badge bg-info bg-opacity-10 text-info">
                                        <i class="bi bi-lightning me-1"></i>
                                        <?php echo e($template->trigger_type_display_name); ?>

                                    </span>
                                    <small class="text-muted">
                                        <i class="bi bi-calendar3 me-1"></i>
                                        <?php echo e($template->created_at->format('M d, Y')); ?>

                                    </small>
                                </div>

                                <?php if(!empty($template->conditions)): ?>
                                <div class="mb-3">
                                    <small class="text-muted fw-medium">Conditions:</small>
                                    <div class="mt-1">
                                        <?php $__currentLoopData = $template->conditions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $condition): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <span class="badge bg-light text-dark me-1 mb-1">
                                                <?php echo e($condition['field']); ?> <?php echo e($condition['operator']); ?> <?php echo e($condition['value']); ?>

                                            </span>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="card-footer bg-transparent border-0 pt-0">
                                <div class="d-grid gap-2">
                                    <div class="btn-group" role="group">
                                        <a href="<?php echo e(route('events.email-templates.show', [$event, $template])); ?>" 
                                           class="btn btn-outline-primary btn-sm">
                                            <i class="bi bi-eye me-1"></i>View
                                        </a>
                                        <a href="<?php echo e(route('events.email-templates.edit', [$event, $template])); ?>" 
                                           class="btn btn-outline-warning btn-sm">
                                            <i class="bi bi-pencil me-1"></i>Edit
                                        </a>
                                        <button type="button" 
                                                class="btn btn-outline-info btn-sm test-template-btn"
                                                data-template-id="<?php echo e($template->id); ?>"
                                                data-event-id="<?php echo e($event->id); ?>">
                                            <i class="bi bi-play-circle me-1"></i>Test
                                        </button>
                                    </div>
                                    
                                    <div class="d-flex gap-2">
                                        <form action="<?php echo e(route('events.email-templates.clone', [$event, $template])); ?>" 
                                              method="POST" class="flex-fill">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit" class="btn btn-outline-secondary btn-sm w-100">
                                                <i class="bi bi-files me-1"></i>Clone
                                            </button>
                                        </form>
                                        
                                        <form action="<?php echo e(route('events.email-templates.toggle-status', [$event, $template])); ?>" 
                                              method="POST" class="flex-fill">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('PATCH'); ?>
                                            <button type="submit" class="btn btn-outline-<?php echo e($template->is_active ? 'warning' : 'success'); ?> btn-sm w-100">
                                                <i class="bi bi-<?php echo e($template->is_active ? 'pause' : 'play'); ?> me-1"></i>
                                                <?php echo e($template->is_active ? 'Deactivate' : 'Activate'); ?>

                                            </button>
                                        </form>
                                    </div>
                                    
                                    <form action="<?php echo e(route('events.email-templates.destroy', [$event, $template])); ?>" 
                                          method="POST" class="d-grid" 
                                          onsubmit="return confirm('Are you sure you want to delete this template?')">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn btn-outline-danger btn-sm">
                                            <i class="bi bi-trash me-1"></i>Delete
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
                        <i class="bi bi-envelope text-muted fs-1 d-block mb-3"></i>
                        <h3 class="h5 mb-2">No Email Templates Created Yet</h3>
                        <p class="text-muted mb-3">Start building automated email communications for your event participants.</p>
                        <a href="<?php echo e(route('events.email-templates.create', $event)); ?>" class="btn btn-primary">
                            <i class="bi bi-plus-circle me-2"></i>Create Your First Template
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Test Template Modal -->
    <div class="modal fade" id="testTemplateModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Test Email Template</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="testResults">
                        <div class="text-center">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="mt-2">Testing template...</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <?php $__env->startPush('scripts'); ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Test template functionality
            document.querySelectorAll('.test-template-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const templateId = this.dataset.templateId;
                    const eventId = this.dataset.eventId;
                    
                    // Show modal
                    const modal = new bootstrap.Modal(document.getElementById('testTemplateModal'));
                    modal.show();
                    
                    // Test template
                    fetch(`/events/${eventId}/email-templates/${templateId}/test`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        const resultsDiv = document.getElementById('testResults');
                        
                        if (data.success) {
                            resultsDiv.innerHTML = `
                                <div class="alert alert-success">
                                    <h6><i class="bi bi-check-circle me-2"></i>Template Test Successful!</h6>
                                    <hr>
                                    <div class="mb-3">
                                        <strong>Subject:</strong><br>
                                        <code class="bg-light p-2 rounded d-block mt-1">${data.subject}</code>
                                    </div>
                                    <div>
                                        <strong>Content Preview:</strong><br>
                                        <div class="bg-light p-3 rounded mt-1" style="max-height: 300px; overflow-y: auto;">
                                            ${data.content}
                                        </div>
                                    </div>
                                </div>
                            `;
                        } else {
                            resultsDiv.innerHTML = `
                                <div class="alert alert-danger">
                                    <h6><i class="bi bi-exclamation-triangle me-2"></i>Template Test Failed</h6>
                                    <p class="mb-0">${data.error}</p>
                                </div>
                            `;
                        }
                    })
                    .catch(error => {
                        document.getElementById('testResults').innerHTML = `
                            <div class="alert alert-danger">
                                <h6><i class="bi bi-exclamation-triangle me-2"></i>Error</h6>
                                <p class="mb-0">Failed to test template: ${error.message}</p>
                            </div>
                        `;
                    });
                });
            });
        });
    </script>
    <?php $__env->stopPush(); ?>
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

<?php /**PATH C:\xampp\htdocs\exhibition-app\resources\views/admin/email-templates/index.blade.php ENDPATH**/ ?>