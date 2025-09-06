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
            <i class="bi bi-pencil me-2"></i>
            Edit Form - <?php echo e($formBuilder->name); ?>

        </h2>
        <div class="d-flex gap-2">
            <a href="<?php echo e(route('events.form-builders.show', [$event, $formBuilder])); ?>" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-2"></i>Back to Form
            </a>
        </div>
    </div>

    <div class="card mb-4">
            <!-- Event Header -->
                <div class="position-relative">
                    <div class="bg-gradient-to-br from-blue-400 to-purple-600 d-flex align-items-center justify-content-center position-relative overflow-hidden" style="height: 150px;">
                        <?php if($event->logo): ?>
                            <img src="<?php echo e(Storage::url($event->logo)); ?>" alt="<?php echo e($event->name); ?>" class="w-100 h-100 object-fit-cover">
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
                        <h1 class="h4 mb-1"><?php echo e($event->name); ?></h1>
                        <p class="mb-0 opacity-75 small"><?php echo e($event->start_date->format('F d, Y')); ?> - <?php echo e($event->end_date->format('F d, Y')); ?></p>
                    </div>
                </div>
            </div>

            <form action="<?php echo e(route('events.form-builders.update', [$event, $formBuilder])); ?>" method="POST" id="formBuilderForm">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>
                
                <div class="row">
                    <!-- Form Settings -->
                    <div class="col-lg-4">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="bi bi-gear me-2"></i>Form Settings
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Form Name *</label>
                                    <input type="text" class="form-control" id="name" name="name" required 
                                           value="<?php echo e($formBuilder->name); ?>" placeholder="e.g., Participant Registration">
                                </div>

                                <div class="mb-3">
                                    <label for="type" class="form-label">Form Type *</label>
                                    <select class="form-select" id="type" name="type" required>
                                        <option value="">Select Form Type</option>
                                        <option value="member_registration" <?php echo e($formBuilder->type === 'member_registration' ? 'selected' : ''); ?>>Member Registration</option>
                                        <option value="exhibitor_registration" <?php echo e($formBuilder->type === 'exhibitor_registration' ? 'selected' : ''); ?>>Exhibitor Registration</option>
                                        <option value="speaker_registration" <?php echo e($formBuilder->type === 'speaker_registration' ? 'selected' : ''); ?>>Speaker Registration</option>
                                        <option value="delegate_registration" <?php echo e($formBuilder->type === 'delegate_registration' ? 'selected' : ''); ?>>Delegate Registration</option>
                                        <option value="general" <?php echo e($formBuilder->type === 'general' ? 'selected' : ''); ?>>General Form</option>
                                    </select>
                                    <div class="form-text">Choose the purpose of this form</div>
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control" id="description" name="description" rows="3"
                                              placeholder="Describe the purpose of this form"><?php echo e($formBuilder->description); ?></textarea>
                                </div>

                                <div class="mb-3">
                                    <label for="status" class="form-label">Status</label>
                                    <select class="form-select" id="status" name="status" required>
                                        <option value="draft" <?php echo e($formBuilder->status === 'draft' ? 'selected' : ''); ?>>Draft</option>
                                        <option value="published" <?php echo e($formBuilder->status === 'published' ? 'selected' : ''); ?>>Published</option>
                                        <option value="archived" <?php echo e($formBuilder->status === 'archived' ? 'selected' : ''); ?>>Archived</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="submit_button_text" class="form-label">Submit Button Text</label>
                                    <input type="text" class="form-control" id="submit_button_text" name="submit_button_text" 
                                           value="<?php echo e($formBuilder->submit_button_text); ?>" placeholder="Submit Registration">
                                </div>

                                <div class="mb-3">
                                    <label for="theme_color" class="form-label">Theme Color</label>
                                    <input type="color" class="form-control form-control-color" id="theme_color" name="theme_color" 
                                           value="<?php echo e($formBuilder->theme_color); ?>" title="Choose theme color">
                                </div>

                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="allow_multiple_submissions" 
                                               name="allow_multiple_submissions" value="1" <?php echo e($formBuilder->allow_multiple_submissions ? 'checked' : ''); ?>>
                                        <label class="form-check-label" for="allow_multiple_submissions">
                                            Allow multiple submissions per person
                                        </label>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="require_login" 
                                               name="require_login" value="1" <?php echo e($formBuilder->require_login ? 'checked' : ''); ?>>
                                        <label class="form-check-label" for="require_login">
                                            Require user login to submit
                                        </label>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="send_confirmation_email" 
                                               name="send_confirmation_email" value="1" <?php echo e($formBuilder->send_confirmation_email ? 'checked' : ''); ?>>
                                        <label class="form-check-label" for="send_confirmation_email">
                                            Send confirmation email
                                        </label>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="confirmation_message" class="form-label">Confirmation Message</label>
                                    <textarea class="form-control" id="confirmation_message" name="confirmation_message" rows="3"
                                              placeholder="Thank you for your registration!"><?php echo e($formBuilder->confirmation_message); ?></textarea>
                                </div>

                                <div class="mb-3">
                                    <label for="redirect_url" class="form-label">Redirect URL (Optional)</label>
                                    <input type="url" class="form-control" id="redirect_url" name="redirect_url" 
                                           value="<?php echo e($formBuilder->redirect_url); ?>" placeholder="https://example.com/thank-you">
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="card">
                            <div class="card-body">
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-check-circle me-2"></i>Update Form Settings
                                    </button>
                                    <a href="<?php echo e(route('events.form-builders.design', [$event, $formBuilder])); ?>" class="btn btn-outline-primary">
                                        <i class="bi bi-pencil-square me-2"></i>Design Form
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Preview -->
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">
                                    <i class="bi bi-eye me-2"></i>Form Preview
                                </h5>
                                <div class="d-flex gap-2">
                                    <a href="<?php echo e(route('events.form-builders.design', [$event, $formBuilder])); ?>" class="btn btn-outline-primary btn-sm">
                                        <i class="bi bi-pencil-square me-2"></i>Edit Form
                                    </a>
                                    <a href="<?php echo e(route('events.form-builders.preview', [$event, $formBuilder])); ?>" class="btn btn-outline-info btn-sm" target="_blank">
                                        <i class="bi bi-eye-fill me-2"></i>Full Preview
                                    </a>
                                </div>
                            </div>
                            <div class="card-body">
                                <?php if($formBuilder->fields->count() > 0): ?>
                                    <div class="form-preview" style="max-width: 800px; margin: 0 auto;">
                                        <form class="needs-validation" novalidate>
                                            <?php
                                                $currentSection = null;
                                                $sectionFields = collect();
                                            ?>
                                            
                                            <?php $__currentLoopData = $formBuilder->fields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php if($field->type === 'section'): ?>
                                                    <?php if($currentSection && $sectionFields->count() > 0): ?>
                                                        <!-- Render previous section -->
                                                        <div class="card mb-4">
                                                            <div class="card-header bg-light">
                                                                <h6 class="mb-0"><?php echo e($currentSection->label); ?></h6>
                                                            </div>
                                                            <div class="card-body">
                                                                <div class="row">
                                                                    <?php $__currentLoopData = $sectionFields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sectionField): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                        <?php echo $__env->make('form-builders.partials.field-preview', ['field' => $sectionField], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <?php $sectionFields = collect(); ?>
                                                    <?php endif; ?>
                                                    <?php $currentSection = $field; ?>
                                                <?php else: ?>
                                                    <?php if($currentSection): ?>
                                                        <?php $sectionFields->push($field); ?>
                                                    <?php else: ?>
                                                        <!-- Fields outside sections -->
                                                        <div class="row">
                                                            <?php echo $__env->make('form-builders.partials.field-preview', ['field' => $field], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                                                        </div>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            
                                            <?php if($currentSection && $sectionFields->count() > 0): ?>
                                                <!-- Render last section -->
                                                <div class="card mb-4">
                                                    <div class="card-header bg-light">
                                                        <h6 class="mb-0"><?php echo e($currentSection->label); ?></h6>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <?php $__currentLoopData = $sectionFields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sectionField): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <?php echo $__env->make('form-builders.partials.field-preview', ['field' => $sectionField], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                            
                                            <!-- Submit Button -->
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                                        <button type="submit" class="btn btn-primary" disabled>
                                                            <?php echo e($formBuilder->submit_button_text ?: 'Submit Registration'); ?>

                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                <?php else: ?>
                                    <div class="text-center text-muted py-5">
                                        <i class="bi bi-file-earmark-text fs-1 d-block mb-3"></i>
                                        <h6>No Form Fields Yet</h6>
                                        <p class="mb-3">Click "Edit Form" to add fields and customize your form layout</p>
                                        <a href="<?php echo e(route('events.form-builders.design', [$event, $formBuilder])); ?>" class="btn btn-primary">
                                            <i class="bi bi-pencil-square me-2"></i>Add Form Fields
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
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
<?php /**PATH C:\xampp\htdocs\exhibition-app\resources\views/form-builders/edit.blade.php ENDPATH**/ ?>