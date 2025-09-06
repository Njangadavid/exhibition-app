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
     <?php $__env->slot('header', null, []); ?> 
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Email Settings - <?php echo e($event->name); ?>

            </h2>
            <div>
                <a href="<?php echo e(route('events.index')); ?>" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Back to Events
                </a>
            </div>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <?php if(session('success')): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle"></i> <?php echo e(session('success')); ?>

                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <?php if(session('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-triangle"></i> <?php echo e(session('error')); ?>

                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <div class="row">
                        <div class="col-lg-8">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">
                                        <i class="bi bi-envelope-gear"></i> SMTP Configuration
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <form method="POST" action="<?php echo e(route('admin.events.email-settings.store', $event)); ?>">
                                        <?php echo csrf_field(); ?>
                                        
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="smtp_host" class="form-label">
                                                        SMTP Host <span class="text-danger">*</span>
                                                    </label>
                                                    <input type="text" 
                                                           class="form-control <?php $__errorArgs = ['smtp_host'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                                           id="smtp_host" 
                                                           name="smtp_host" 
                                                           value="<?php echo e(old('smtp_host', $emailSettings->smtp_host ?? '')); ?>"
                                                           placeholder="smtp.gmail.com">
                                                    <?php $__errorArgs = ['smtp_host'];
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
                                            
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="smtp_port" class="form-label">
                                                        SMTP Port <span class="text-danger">*</span>
                                                    </label>
                                                    <input type="number" 
                                                           class="form-control <?php $__errorArgs = ['smtp_port'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                                           id="smtp_port" 
                                                           name="smtp_port" 
                                                           value="<?php echo e(old('smtp_port', $emailSettings->smtp_port ?? 587)); ?>"
                                                           min="1" 
                                                           max="65535">
                                                    <?php $__errorArgs = ['smtp_port'];
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
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="smtp_username" class="form-label">
                                                        SMTP Username <span class="text-danger">*</span>
                                                    </label>
                                                    <input type="text" 
                                                           class="form-control <?php $__errorArgs = ['smtp_username'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                                           id="smtp_username" 
                                                           name="smtp_username" 
                                                           value="<?php echo e(old('smtp_username', $emailSettings->smtp_username ?? '')); ?>"
                                                           placeholder="your-email@gmail.com">
                                                    <?php $__errorArgs = ['smtp_username'];
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
                                            
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="smtp_password" class="form-label">
                                                        SMTP Password 
                                                        <?php if(!$emailSettings): ?>
                                                            <span class="text-danger">*</span>
                                                        <?php endif; ?>
                                                    </label>
                                                    <input type="password" 
                                                           class="form-control <?php $__errorArgs = ['smtp_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                                           id="smtp_password" 
                                                           name="smtp_password" 
                                                           placeholder="<?php echo e($emailSettings ? 'Leave blank to keep current password' : 'Enter your email password'); ?>">
                                                    <?php $__errorArgs = ['smtp_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                    <div class="form-text">
                                                        <i class="bi bi-shield-lock"></i> 
                                                        <?php if($emailSettings): ?>
                                                            Password is encrypted and stored securely. Leave blank to keep current password.
                                                        <?php else: ?>
                                                            Password will be encrypted and stored securely.
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="smtp_encryption" class="form-label">Encryption</label>
                                                    <select class="form-select <?php $__errorArgs = ['smtp_encryption'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                                            id="smtp_encryption" 
                                                            name="smtp_encryption">
                                                        <option value="">None</option>
                                                        <option value="tls" <?php echo e(old('smtp_encryption', $emailSettings->smtp_encryption ?? 'tls') == 'tls' ? 'selected' : ''); ?>>TLS</option>
                                                        <option value="ssl" <?php echo e(old('smtp_encryption', $emailSettings->smtp_encryption ?? 'tls') == 'ssl' ? 'selected' : ''); ?>>SSL</option>
                                                    </select>
                                                    <?php $__errorArgs = ['smtp_encryption'];
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
                                            
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <div class="form-check mt-4">
                                                        <input class="form-check-input" 
                                                               type="checkbox" 
                                                               id="is_active" 
                                                               name="is_active" 
                                                               value="1"
                                                               <?php echo e(old('is_active', $emailSettings->is_active ?? true) ? 'checked' : ''); ?>>
                                                        <label class="form-check-label" for="is_active">
                                                            Active
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <hr>

                                        <h6 class="mb-3">
                                            <i class="bi bi-person-badge"></i> Send As Settings
                                        </h6>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="send_as_email" class="form-label">
                                                        Send As Email <span class="text-danger">*</span>
                                                    </label>
                                                    <input type="email" 
                                                           class="form-control <?php $__errorArgs = ['send_as_email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                                           id="send_as_email" 
                                                           name="send_as_email" 
                                                           value="<?php echo e(old('send_as_email', $emailSettings->send_as_email ?? '')); ?>"
                                                           placeholder="noreply@yourcompany.com">
                                                    <?php $__errorArgs = ['send_as_email'];
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
                                            
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="send_as_name" class="form-label">Send As Name</label>
                                                    <input type="text" 
                                                           class="form-control <?php $__errorArgs = ['send_as_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                                           id="send_as_name" 
                                                           name="send_as_name" 
                                                           value="<?php echo e(old('send_as_name', $emailSettings->send_as_name ?? '')); ?>"
                                                           placeholder="Your Company Name">
                                                    <?php $__errorArgs = ['send_as_name'];
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
                                        </div>

                                        <div class="d-flex justify-content-between">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="bi bi-save"></i> Save Settings
                                            </button>
                                            
                                            <?php if($emailSettings): ?>
                                                <button type="button" 
                                                        class="btn btn-danger" 
                                                        onclick="confirmDelete()">
                                                    <i class="bi bi-trash"></i> Delete Settings
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">
                                        <i class="bi bi-gear"></i> Test Configuration
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <?php if($emailSettings && $emailSettings->isConfigured()): ?>
                                        <div class="alert alert-success">
                                            <i class="bi bi-check-circle"></i> Email settings are configured
                                        </div>
                                        
                                        <form id="testEmailForm">
                                            <?php echo csrf_field(); ?>
                                            <div class="mb-3">
                                                <label for="test_email" class="form-label">Test Email Address</label>
                                                <input type="email" 
                                                       class="form-control" 
                                                       id="test_email" 
                                                       name="test_email" 
                                                       placeholder="test@example.com"
                                                       required>
                                            </div>
                                            
                                            <button type="submit" class="btn btn-outline-primary w-100" id="testButton">
                                                <i class="bi bi-send"></i> Send Test Email
                                            </button>
                                        </form>
                                        
                                        <div id="testResult" class="mt-3" style="display: none;"></div>
                                    <?php else: ?>
                                        <div class="alert alert-warning">
                                            <i class="bi bi-exclamation-triangle"></i> Please configure email settings first
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="card mt-3">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">
                                        <i class="bi bi-info-circle"></i> Help
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <h6>Common SMTP Settings:</h6>
                                    <ul class="list-unstyled">
                                        <li><strong>Gmail:</strong> smtp.gmail.com:587 (TLS)</li>
                                        <li><strong>Outlook:</strong> smtp-mail.outlook.com:587 (TLS)</li>
                                        <li><strong>Yahoo:</strong> smtp.mail.yahoo.com:587 (TLS)</li>
                                    </ul>
                                    
                                    <div class="alert alert-info mt-3">
                                        <small>
                                            <i class="bi bi-lightbulb"></i> 
                                            <strong>Note:</strong> For Gmail, you may need to enable "Less secure app access" or use an App Password.
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php $__env->startPush('scripts'); ?>
    <script>
        // Test email functionality
        document.getElementById('testEmailForm')?.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const testButton = document.getElementById('testButton');
            const testResult = document.getElementById('testResult');
            const formData = new FormData(this);
            
            testButton.disabled = true;
            testButton.innerHTML = '<i class="bi bi-hourglass-split"></i> Sending...';
            
            fetch('<?php echo e(route("admin.events.email-settings.test", $event)); ?>', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => {
                console.log('Response status:', response.status);
                if (!response.ok) {
                    throw new Error('HTTP ' + response.status + ': ' + response.statusText);
                }
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data);
                testResult.style.display = 'block';
                
                if (data.success) {
                    testResult.innerHTML = '<div class="alert alert-success"><i class="bi bi-check-circle"></i> ' + data.message + '</div>';
                } else {
                    testResult.innerHTML = '<div class="alert alert-danger"><i class="bi bi-exclamation-triangle"></i> ' + data.message + '</div>';
                }
            })
            .catch(error => {
                console.error('Test email error:', error);
                testResult.style.display = 'block';
                testResult.innerHTML = '<div class="alert alert-danger"><i class="bi bi-exclamation-triangle"></i> Test failed: ' + error.message + '</div>';
            })
            .finally(() => {
                testButton.disabled = false;
                testButton.innerHTML = '<i class="bi bi-send"></i> Send Test Email';
            });
        });

        // Delete confirmation
        function confirmDelete() {
            if (confirm('Are you sure you want to delete the email settings for this event? This action cannot be undone.')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '<?php echo e(route("admin.events.email-settings.destroy", $event)); ?>';
                
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '<?php echo e(csrf_token()); ?>';
                
                const methodField = document.createElement('input');
                methodField.type = 'hidden';
                methodField.name = '_method';
                methodField.value = 'DELETE';
                
                form.appendChild(csrfToken);
                form.appendChild(methodField);
                document.body.appendChild(form);
                form.submit();
            }
        }
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
<?php /**PATH C:\xampp\htdocs\exhibition-app\resources\views/admin/events/email-settings.blade.php ENDPATH**/ ?>