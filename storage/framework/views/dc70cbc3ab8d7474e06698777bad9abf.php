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
            <?php echo e(__('Edit Event')); ?>: <?php echo e($event->name); ?>

        </h2>
        <div class="d-flex gap-2">
            <a href="<?php echo e(route('events.show', $event)); ?>" class="btn btn-secondary">
                <i class="bi bi-eye me-2"></i>
                View Event
            </a>
            <a href="<?php echo e(route('events.index')); ?>" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>
                Back to Events
            </a>
        </div>
    </div>

    <div class="card">
                <div class="card-body">
                    <?php if($errors->any()): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li><?php echo e($error); ?></li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="<?php echo e(route('events.update', $event)); ?>" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>

                        <!-- Title -->
                        <div class="mb-3">
                            <label for="name" class="form-label"><?php echo e(__('Event Name')); ?></label>
                            <input type="text" class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   id="name" name="name" 
                                   value="<?php echo e(old('name', $event->name)); ?>" 
                                   placeholder="<?php echo e(__('Enter event name')); ?>" required>
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

                        <!-- Description -->
                        <div class="mb-3">
                            <label for="description" class="form-label">
                                <i class="bi bi-text-paragraph me-2"></i>
                                Event Description *
                            </label>
                            <textarea 
                                id="description" 
                                name="description" 
                                rows="4"
                                class="form-control"
                                placeholder="Describe your event in detail"
                                required
                            ><?php echo e(old('description', $event->description)); ?></textarea>
                            <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <!-- Current Logo Display -->
                        <?php if($event->logo): ?>
                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="bi bi-image me-2"></i>
                                    Current Logo
                                </label>
                                <div class="d-flex align-items-center gap-3">
                                    <img src="<?php echo e(Storage::url($event->logo)); ?>" alt="Current logo" class="rounded border" style="width: 96px; height: 96px; object-fit: cover;">
                                    <div class="text-muted small">
                                        Current event logo
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- Logo Upload -->
                        <div class="mb-3">
                            <label for="logo" class="form-label">
                                <i class="bi bi-image me-2"></i>
                                <?php echo e($event->logo ? 'Update Logo' : 'Event Logo'); ?>

                            </label>
                            <div class="d-flex align-items-center gap-3">
                                <input 
                                    type="file" 
                                    id="logo" 
                                    name="logo" 
                                    accept="image/*"
                                    class="form-control"
                                />
                                <div class="text-muted small">
                                    PNG, JPG, GIF up to 2MB
                                    <?php if($event->logo): ?>
                                        <br>Leave empty to keep current logo
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php $__errorArgs = ['logo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <!-- Status -->
                        <div class="mb-3">
                            <label for="status" class="form-label">
                                <i class="bi bi-flag me-2"></i>
                                Event Status *
                            </label>
                            <select 
                                id="status" 
                                name="status"
                                class="form-select"
                                required
                            >
                                <?php $__currentLoopData = $statusOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($value); ?>" <?php echo e(old('status', $event->status) == $value ? 'selected' : ''); ?>>
                                        <?php echo e($label); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <!-- Date Range -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="start_date" class="form-label">
                                    <i class="bi bi-calendar-event me-2"></i>
                                    Start Date & Time *
                                </label>
                                <input 
                                    type="datetime-local" 
                                    id="start_date" 
                                    name="start_date" 
                                    value="<?php echo e(old('start_date', $event->start_date->format('Y-m-d\TH:i'))); ?>"
                                    class="form-control"
                                    required
                                />
                                <?php $__errorArgs = ['start_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="end_date" class="form-label">
                                    <i class="bi bi-calendar-check me-2"></i>
                                    End Date & Time *
                                </label>
                                <input 
                                    type="datetime-local" 
                                    id="end_date" 
                                    name="end_date" 
                                    value="<?php echo e(old('end_date', $event->end_date->format('Y-m-d\TH:i'))); ?>"
                                    class="form-control"
                                    required
                                />
                                <?php $__errorArgs = ['end_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>

                        <!-- Assigned Users -->
                        <div class="mb-4">
                            <label class="form-label">
                                <i class="bi bi-people me-2"></i>
                                Assign Users to Event
                            </label>
                            <?php if(auth()->user()->hasRole('admin')): ?>
                                <p class="text-muted small mb-3">As an administrator, you can manage all user assignments including the event owner.</p>
                            <?php else: ?>
                                <p class="text-muted small mb-3">Select which users can view and manage this event. The event owner cannot be removed.</p>
                            <?php endif; ?>
                            
                            <div class="row">
                                <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="col-md-6 col-lg-4 mb-2">
                                    <div class="form-check">
                                        <?php if($user->id === $event->owner_id): ?>
                                            <?php if(auth()->user()->hasRole('admin')): ?>
                                                <!-- Event Owner - Admin can manage -->
                                                <input 
                                                    class="form-check-input" 
                                                    type="checkbox" 
                                                    name="assigned_users[]" 
                                                    value="<?php echo e($user->id); ?>" 
                                                    id="user_<?php echo e($user->id); ?>"
                                                    <?php echo e(in_array($user->id, old('assigned_users', $event->users->pluck('id')->toArray())) ? 'checked' : ''); ?>

                                                />
                                                <label class="form-check-label" for="user_<?php echo e($user->id); ?>">
                                                    <div class="d-flex align-items-center">
                                                        <div class="bg-warning bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                                                            <i class="bi bi-crown text-warning"></i>
                                                        </div>
                                                        <div>
                                                            <div class="fw-medium"><?php echo e($user->name); ?> <span class="badge bg-warning ms-1">Owner (Admin can remove)</span></div>
                                                            <small class="text-muted"><?php echo e($user->email); ?></small>
                                                        </div>
                                                    </div>
                                                </label>
                                            <?php else: ?>
                                                <!-- Event Owner - Non-admin cannot manage -->
                                                <input 
                                                    class="form-check-input" 
                                                    type="checkbox" 
                                                    checked 
                                                    disabled
                                                />
                                                <label class="form-check-label">
                                                    <div class="d-flex align-items-center">
                                                        <div class="bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                                                            <i class="bi bi-crown text-success"></i>
                                                        </div>
                                                        <div>
                                                            <div class="fw-medium"><?php echo e($user->name); ?> <span class="badge bg-success ms-1">Owner</span></div>
                                                            <small class="text-muted"><?php echo e($user->email); ?></small>
                                                        </div>
                                                    </div>
                                                </label>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <!-- Regular User -->
                                            <input 
                                                class="form-check-input" 
                                                type="checkbox" 
                                                name="assigned_users[]" 
                                                value="<?php echo e($user->id); ?>" 
                                                id="user_<?php echo e($user->id); ?>"
                                                <?php echo e(in_array($user->id, old('assigned_users', $event->users->pluck('id')->toArray())) ? 'checked' : ''); ?>

                                            />
                                            <label class="form-check-label" for="user_<?php echo e($user->id); ?>">
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                                                        <i class="bi bi-person text-primary"></i>
                                                    </div>
                                                    <div>
                                                        <div class="fw-medium"><?php echo e($user->name); ?></div>
                                                        <small class="text-muted"><?php echo e($user->email); ?></small>
                                                    </div>
                                                </div>
                                            </label>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                            
                            <?php $__errorArgs = ['assigned_users'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>


                        <!-- Form Actions -->
                        <div class="d-flex justify-content-end gap-3 pt-4 border-top">
                            <a href="<?php echo e(route('events.show', $event)); ?>" class="btn btn-outline-secondary">
                                <i class="bi bi-x-circle me-2"></i>
                                Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-2"></i>
                                Update Event
                            </button>
                        </div>
                    </form>
                    <?php if(auth()->user()->hasRole('admin')): ?>
        <div class="card mt-4" id="ownership-transfer">
            <div class="card-header bg-warning bg-opacity-10">
                <h6 class="mb-0">
                    <i class="bi bi-shield-check me-2"></i>
                    Transfer Event Ownership
                </h6>
            </div>
            <div class="card-body">
                <p class="text-muted small mb-3">As an administrator, you can transfer ownership of this event to another user.</p>
                
                <form method="POST" action="<?php echo e(route('events.transfer-ownership', $event)); ?>">
                    <?php echo csrf_field(); ?>
                    <div class="row">
                        <div class="col-md-8">
                            <select name="new_owner_id" class="form-select" required>
                                <option value="">Select new owner...</option>
                                <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php if($user->id !== $event->owner_id): ?>
                                    <option value="<?php echo e($user->id); ?>"><?php echo e($user->name); ?> (<?php echo e($user->email); ?>)</option>
                                    <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-warning" onclick="return confirm('Are you sure you want to transfer ownership? This action cannot be undone.')">
                                <i class="bi bi-arrow-right-circle me-2"></i>
                                Transfer Ownership
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Ownership Transfer (Admin Only) - Outside main form -->
        
    </div>

    <?php $__env->startPush('scripts'); ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form[action*="events.update"]');
        const submitBtn = form.querySelector('button[type="submit"]');
        
        submitBtn.addEventListener('click', function(e) {
            console.log('Submit button clicked');
            
            // Check all required fields
            const requiredFields = form.querySelectorAll('[required]');
            let hasErrors = false;
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    console.log('Empty required field:', field.name);
                    field.classList.add('is-invalid');
                    hasErrors = true;
                } else {
                    field.classList.remove('is-invalid');
                }
            });
            
            if (hasErrors) {
                e.preventDefault();
                alert('Please fill in all required fields');
                return false;
            }
            
            console.log('Form validation passed, submitting...');
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
<?php /**PATH C:\xampp\htdocs\exhibition-app\resources\views/events/edit.blade.php ENDPATH**/ ?>