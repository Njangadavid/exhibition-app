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
                        <i class="bi bi-pencil me-2 text-warning"></i>
                        Edit Email Template
                    </h2>
                    <p class="text-muted mb-0">Update "<?php echo e($emailTemplate->name); ?>" for <?php echo e($event->name); ?></p>
                </div>
                <div class="d-flex gap-2">
                    <a href="<?php echo e(route('events.email-templates.show', [$event, $emailTemplate])); ?>" class="btn btn-outline-primary">
                        <i class="bi bi-eye me-2"></i>View Template
                    </a>
                    <a href="<?php echo e(route('events.email-templates.index', $event)); ?>" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-2"></i>Back to Templates
                    </a>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-8">
                    <!-- Template Form -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white">
                            <h5 class="card-title mb-0">Template Details</h5>
                        </div>
                        <div class="card-body">
                            <form action="<?php echo e(route('events.email-templates.update', [$event, $emailTemplate])); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('PUT'); ?>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="name" class="form-label fw-medium">Template Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            id="name" name="name" value="<?php echo e(old('name', $emailTemplate->name)); ?>"
                                            placeholder="e.g., Welcome Email for New Owners" required>
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

                                    <div class="col-md-6 mb-3">
                                        <label for="trigger_type" class="form-label fw-medium">Trigger Type <span class="text-danger">*</span></label>
                                        <select class="form-select <?php $__errorArgs = ['trigger_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            id="trigger_type" name="trigger_type" required>
                                            <option value="">Select Trigger Type</option>
                                            <?php $__currentLoopData = $triggerTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($key); ?>" <?php echo e(old('trigger_type', $emailTemplate->trigger_type) == $key ? 'selected' : ''); ?>>
                                                <?php echo e($label); ?>

                                            </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                        <?php $__errorArgs = ['trigger_type'];
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

                                <div class="mb-3">
                                    <label for="subject" class="form-label fw-medium">Email Subject <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control <?php $__errorArgs = ['subject'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        id="subject" name="subject" value="<?php echo e(old('subject', $emailTemplate->subject)); ?>"
                                        placeholder="e.g., Welcome to <?php echo e($event->name); ?>!" required>
                                    <?php $__errorArgs = ['subject'];
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

                                <div class="mb-3">
                                    <label for="content" class="form-label fw-medium">Email Content <span class="text-danger">*</span></label>
                                    <textarea class="form-control <?php $__errorArgs = ['content'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        id="content" name="content" rows="15" required><?php echo e(old('content', $emailTemplate->content)); ?></textarea>
                                    <div class="form-text text-muted">Use the merge field buttons to insert dynamic content. Images will use full URLs.</div>
                                    <?php $__errorArgs = ['content'];
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

                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active"
                                            <?php echo e(old('is_active', $emailTemplate->is_active) ? 'checked' : ''); ?>>
                                        <label class="form-check-label fw-medium" for="is_active">
                                            Activate this template
                                        </label>
                                    </div>
                                </div>

                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-warning">
                                        <i class="bi bi-check-circle me-2"></i>Update Template
                                    </button>
                                    <a href="<?php echo e(route('events.email-templates.index', $event)); ?>" class="btn btn-outline-secondary">
                                        Cancel
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <!-- Merge Fields Panel -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white">
                            <h6 class="card-title mb-0">
                                <i class="bi bi-tags me-2 text-info"></i>
                                Available Merge Fields
                            </h6>
                        </div>
                        <div class="card-body">
                            <p class="text-muted small mb-3">Click any field to insert it into your template</p>

                            <?php $__currentLoopData = $mergeFields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category => $fields): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="mb-3">
                                <h6 class="fw-medium text-capitalize mb-2"><?php echo e($category); ?></h6>
                                <div class="d-flex flex-wrap gap-1">
                                    <?php $__currentLoopData = $fields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <button type="button"
                                        class="btn btn-outline-info btn-sm merge-field-btn"
                                        data-field="<?php echo e($category); ?>.<?php echo e($key); ?>">
                                        <?php echo e($label); ?>

                                    </button>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>

                    <!-- Template Info -->
                    <div class="card border-0 shadow-sm mt-3">
                        <div class="card-header bg-white">
                            <h6 class="card-title mb-0">
                                <i class="bi bi-info-circle me-2 text-primary"></i>
                                Template Information
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-6 mb-3">
                                    <div class="text-muted small">Created</div>
                                    <div class="fw-medium"><?php echo e($emailTemplate->created_at->format('M d, Y')); ?></div>
                                </div>
                                <div class="col-6 mb-3">
                                    <div class="text-muted small">Last Updated</div>
                                    <div class="fw-medium"><?php echo e($emailTemplate->updated_at->format('M d, Y')); ?></div>
                                </div>
                            </div>
                            <hr>
                            <div class="d-grid">
                                <button type="button" class="btn btn-outline-info btn-sm test-template-btn"
                                    data-template-id="<?php echo e($emailTemplate->id); ?>"
                                    data-event-id="<?php echo e($event->id); ?>">
                                    <i class="bi bi-play-circle me-2"></i>Test Template
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Template Tips -->
                    <div class="card border-0 shadow-sm mt-3">
                        <div class="card-header bg-white">
                            <h6 class="card-title mb-0">
                                <i class="bi bi-lightbulb me-2 text-warning"></i>
                                Editing Tips
                            </h6>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled small text-muted mb-0">
                                <li class="mb-2">
                                    <i class="bi bi-check-circle-fill text-success me-2"></i>
                                    Test changes before activating
                                </li>
                                <li class="mb-2">
                                    <i class="bi bi-check-circle-fill text-success me-2"></i>
                                    Keep existing merge fields intact
                                </li>
                                <li class="mb-2">
                                    <i class="bi bi-check-circle-fill text-success me-2"></i>
                                    Update subject if content changes significantly
                                </li>
                                <li class="mb-0">
                                    <i class="bi bi-check-circle-fill text-success me-2"></i>
                                    Consider creating a copy for major changes
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
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

    <?php $__env->startPush('styles'); ?>
    <style>
        .merge-field-btn {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
        }

        .tox-tinymce {
            border-radius: 0.375rem;
        }
    </style>
    <?php $__env->stopPush(); ?>

    <?php $__env->startPush('scripts'); ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize TinyMCE
            tinymce.init({
                selector: '#content',
                height: 400,
                plugins: 'code anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
                toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
                content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; font-size: 14px; }',
                menubar: true,
                branding: false,
                promotion: false,
                // Image path configuration - keep full paths
                relative_urls: false,
                remove_script_host: false,
                convert_urls: false,
                urlconverter_callback: (url, node, on_save, name) => url,
                // Merge field insertion
                setup: function(editor) {
                    window.insertMergeField = function(field) {
                        console.log('Inserting merge field:', field);
                        const placeholder = '{{ ' + field + ' }}';
                        console.log('Placeholder:', placeholder);
                        editor.insertContent(placeholder);
                        editor.focus();
                    };
                }
            });

            // Merge field insertion
            document.querySelectorAll('.merge-field-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const field = this.dataset.field;
                    console.log('Merge field button clicked:', field);

                    if (window.insertMergeField) {
                        console.log('Calling insertMergeField function');
                        window.insertMergeField(field);
                    } else {
                        console.error('insertMergeField function not found!');
                    }
                });
            });

            // Form submission debugging
            document.querySelector('form').addEventListener('submit', function(e) {
                console.log('Form submission started');

                // Check if TinyMCE is initialized
                if (typeof tinymce !== 'undefined' && tinymce.get('content')) {
                    const content = tinymce.get('content').getContent();
                    console.log('TinyMCE content:', content);

                    // Validate content is not empty
                    if (!content || content.trim() === '' || content === '<p><br></p>') {
                        e.preventDefault();
                        alert('Please enter some content for your email template.');
                        tinymce.get('content').focus();
                        return false;
                    }
                } else {
                    console.log('TinyMCE not initialized yet');
                }

                console.log('Form submission proceeding...');
            });

            // Test template functionality
            document.querySelector('.test-template-btn').addEventListener('click', function() {
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
<?php endif; ?><?php /**PATH C:\xampp\htdocs\exhibition-app\resources\views/admin/email-templates/edit.blade.php ENDPATH**/ ?>