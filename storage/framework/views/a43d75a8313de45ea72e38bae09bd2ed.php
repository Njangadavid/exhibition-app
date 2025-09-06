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
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="h4 mb-1 fw-bold">
                        <i class="bi bi-credit-card me-2 text-primary"></i>
                        Payment Methods
                    </h2>
                    <p class="text-muted mb-0">Configure payment methods for <?php echo e($event->title); ?></p>
                </div>
                <a href="<?php echo e(route('admin.payment-methods.create', ['event' => $event->id])); ?>" class="btn btn-primary">
                    <i class="bi bi-plus me-2"></i>Add Payment Method
                </a>
            </div>

            <!-- Payment Methods List -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0">
                    <h3 class="h6 mb-0 fw-bold">Configured Payment Methods</h3>
                </div>
                <div class="card-body">
                    <?php if($paymentMethods->count() > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Method</th>
                                        <th>Type</th>
                                        <th>Status</th>
                                        <th>Default</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $paymentMethods; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $method): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <?php if($method->icon): ?>
                                                        <i class="<?php echo e($method->icon); ?> me-2 fs-4" style="color: <?php echo e($method->color ?? '#6c757d'); ?>;"></i>
                                                    <?php else: ?>
                                                        <i class="bi bi-credit-card me-2 fs-4 text-muted"></i>
                                                    <?php endif; ?>
                                                    <div>
                                                        <div class="fw-medium"><?php echo e($method->name); ?></div>
                                                        <?php if($method->description): ?>
                                                            <small class="text-muted"><?php echo e($method->description); ?></small>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-light text-dark">
                                                    <?php echo e(ucfirst(str_replace('_', ' ', $method->type))); ?>

                                                </span>
                                            </td>
                                            <td>
                                                <?php if($method->is_active): ?>
                                                    <span class="badge bg-success">Active</span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary">Inactive</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if($method->is_default): ?>
                                                    <span class="badge bg-primary">Default</span>
                                                <?php else: ?>
                                                    <span class="text-muted">-</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="<?php echo e(route('admin.payment-methods.edit', $method)); ?>" class="btn btn-outline-primary">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    <?php if(!$method->is_default): ?>
                                                        <form action="<?php echo e(route('admin.payment-methods.set-default', $method)); ?>" method="POST" class="d-inline">
                                                            <?php echo csrf_field(); ?>
                                                            <?php echo method_field('PATCH'); ?>
                                                            <button type="submit" class="btn btn-outline-success" title="Set as Default">
                                                                <i class="bi bi-star"></i>
                                                            </button>
                                                        </form>
                                                    <?php endif; ?>
                                                    <form action="<?php echo e(route('admin.payment-methods.toggle-status', $method)); ?>" method="POST" class="d-inline">
                                                        <?php echo csrf_field(); ?>
                                                        <?php echo method_field('PATCH'); ?>
                                                        <button type="submit" class="btn btn-outline-<?php echo e($method->is_active ? 'warning' : 'success'); ?>" title="<?php echo e($method->is_active ? 'Deactivate' : 'Activate'); ?>">
                                                            <i class="bi bi-<?php echo e($method->is_active ? 'pause' : 'play'); ?>"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <div class="mb-3">
                                <i class="bi bi-credit-card text-muted" style="font-size: 3rem;"></i>
                            </div>
                            <h5 class="text-muted mb-2">No Payment Methods Configured</h5>
                            <p class="text-muted mb-4">Get started by adding your first payment method to accept bookings.</p>
                            <a href="<?php echo e(route('admin.payment-methods.create', ['event' => $event->id])); ?>" class="btn btn-primary">
                                <i class="bi bi-plus me-2"></i>Add Payment Method
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Quick Setup Guide -->
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-header bg-transparent border-0">
                    <h3 class="h6 mb-0 fw-bold">
                        <i class="bi bi-info-circle me-2 text-info"></i>
                        Quick Setup Guide
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="fw-bold mb-3">Popular Payment Methods</h6>
                            <ul class="list-unstyled">
                                <li class="mb-2">
                                    <i class="bi bi-check-circle text-success me-2"></i>
                                    <strong>Paystack:</strong> Popular in Nigeria and West Africa
                                </li>
                                <li class="mb-2">
                                    <i class="bi bi-check-circle text-success me-2"></i>
                                    <strong>Stripe:</strong> Global payment processing
                                </li>
                                <li class="mb-2">
                                    <i class="bi bi-check-circle text-success me-2"></i>
                                    <strong>Bank Transfer:</strong> Direct bank payments
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6 class="fw-bold mb-3">Configuration Tips</h6>
                            <ul class="list-unstyled">
                                <li class="mb-2">
                                    <i class="bi bi-lightbulb text-warning me-2"></i>
                                    Set a default payment method for automatic selection
                                </li>
                                <li class="mb-2">
                                    <i class="bi bi-lightbulb text-warning me-2"></i>
                                    Test your payment setup before going live
                                </li>
                                <li class="mb-2">
                                    <i class="bi bi-lightbulb text-warning me-2"></i>
                                    Keep your API keys secure and never share them
                                </li>
                            </ul>
                        </div>
                    </div>
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
<?php /**PATH C:\xampp\htdocs\exhibition-app\resources\views/admin/payment-methods/index.blade.php ENDPATH**/ ?>