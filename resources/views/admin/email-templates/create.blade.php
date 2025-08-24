<x-event-layout :event="$event">

    <div class="py-4">
        <div class="container-fluid">
            <!-- Page Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="h4 mb-1 fw-bold">
                        <i class="bi bi-plus-circle me-2 text-primary"></i>
                        Create Email Template
                    </h2>
                    <p class="text-muted mb-0">Design automated email communications for {{ $event->name }}</p>
                </div>
                <a href="{{ route('events.email-templates.index', $event) }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Back to Templates
                </a>
            </div>

            <div class="row">
                <div class="col-lg-8">
                    <!-- Template Form -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white">
                            <h5 class="card-title mb-0">Template Details</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('events.email-templates.store', $event) }}" method="POST">
                                @csrf

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="name" class="form-label fw-medium">Template Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                            id="name" name="name" value="{{ old('name') }}"
                                            placeholder="e.g., Welcome Email for New Owners" required>
                                        @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="trigger_type" class="form-label fw-medium">Trigger Type <span class="text-danger">*</span></label>
                                        <select class="form-select @error('trigger_type') is-invalid @enderror"
                                            id="trigger_type" name="trigger_type" required>
                                            <option value="">Select Trigger Type</option>
                                            @foreach($triggerTypes as $key => $label)
                                            <option value="{{ $key }}" {{ old('trigger_type') == $key ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                            @endforeach
                                        </select>
                                        @error('trigger_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="subject" class="form-label fw-medium">Email Subject <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('subject') is-invalid @enderror"
                                        id="subject" name="subject" value="{{ old('subject') }}"
                                        placeholder="e.g., Welcome to {{ $event->name }}!" required>
                                    @error('subject')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="content" class="form-label fw-medium">Email Content <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('content') is-invalid @enderror"
                                        id="content" name="content" rows="15" required>{{ old('content') }}</textarea>
                                    <div class="form-text text-muted">Use the merge field buttons to insert dynamic content. Images will use full URLs.</div>
                                    @error('content')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-medium">Email Conditions</label>
                                    <div class="card border">
                                        <div class="card-body">
                                            <p class="text-muted small mb-3">Set conditions for when this email should be sent (optional)</p>

                                            <div id="conditions-container">
                                                <!-- Conditions will be added here -->
                                            </div>

                                            <button type="button" class="btn btn-outline-primary btn-sm" onclick="addCondition()">
                                                <i class="bi bi-plus-circle me-2"></i>Add Condition
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active"
                                            {{ old('is_active', true) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-medium" for="is_active">
                                            Activate this template immediately
                                        </label>
                                    </div>
                                </div>

                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-check-circle me-2"></i>Create Template
                                    </button>
                                    <a href="{{ route('events.email-templates.index', $event) }}" class="btn btn-outline-secondary">
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

                            @foreach($mergeFields as $category => $fields)
                            <div class="mb-3">
                                <h6 class="fw-medium text-capitalize mb-2">{{ $category }}</h6>
                                <div class="d-flex flex-wrap gap-1">
                                    @foreach($fields as $key => $label)
                                    <button type="button"
                                        class="btn btn-outline-info btn-sm merge-field-btn"
                                        data-field="{{ $category }}.{{ $key }}">
                                        {{ $label }}
                                    </button>
                                    @endforeach
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Template Tips -->
                    <div class="card border-0 shadow-sm mt-3">
                        <div class="card-header bg-white">
                            <h6 class="card-title mb-0">
                                <i class="bi bi-lightbulb me-2 text-warning"></i>
                                Template Tips
                            </h6>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled small text-muted mb-0">
                                <li class="mb-2">
                                    <i class="bi bi-check-circle-fill text-success me-2"></i>
                                    Use merge fields to personalize emails
                                </li>
                                <li class="mb-2">
                                    <i class="bi bi-check-circle-fill text-success me-2"></i>
                                    Keep subject lines clear and engaging
                                </li>
                                <li class="mb-2">
                                    <i class="bi bi-check-circle-fill text-success me-2"></i>
                                    Test your template before activating
                                </li>
                                <li class="mb-0">
                                    <i class="bi bi-check-circle-fill text-success me-2"></i>
                                    Use HTML formatting for better presentation
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        .merge-field-btn {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
        }

        .tox-tinymce {
            border-radius: 0.375rem;
        }
    </style>
    @endpush

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM loaded, checking for TinyMCE...');

            // Check if TinyMCE is available
            if (typeof tinymce === 'undefined') {
                console.error('TinyMCE is not loaded!');
                alert('TinyMCE editor failed to load. Please refresh the page.');
                return;
            }

            console.log('TinyMCE found, initializing...');

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
                urlconverter_callback: (url, node, on_save, name) => url, // Prevent URL encoding
                // Merge field insertion
                setup: function(editor) {
                    console.log('TinyMCE editor setup complete');
                    window.insertMergeField = function(field) {
                        console.log('Inserting merge field:', field);
                        const placeholder = '@{{ ' + field + ' }}';
                        console.log('Placeholder:', placeholder);
                        editor.insertContent(placeholder);
                        editor.focus();
                    };
                },
                init_instance_callback: function(editor) {
                    console.log('TinyMCE editor initialized successfully');
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

            // Trigger type change handler
            document.getElementById('trigger_type').addEventListener('change', function() {
                // You can add dynamic logic here to show/hide certain merge fields
                // based on the selected trigger type
            });

            // Form submission debugging
            document.querySelector('form').addEventListener('submit', function(e) {
                console.log('Form submission started');

                // Log form data
                const formData = new FormData(this);
                console.log('Form data:');
                for (let [key, value] of formData.entries()) {
                    console.log(key + ': ' + value);
                }

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

                // Collect conditions data
                const conditions = [];
                document.querySelectorAll('.condition-row').forEach(row => {
                    const field = row.querySelector('.condition-field').value;
                    const operator = row.querySelector('.condition-operator').value;
                    const value = row.querySelector('.condition-value').value;

                    if (field && operator && value) {
                        conditions.push({
                            field,
                            operator,
                            value
                        });
                    }
                });

                // Add hidden input for conditions
                let conditionsInput = document.getElementById('conditions-input');
                if (!conditionsInput) {
                    conditionsInput = document.createElement('input');
                    conditionsInput.type = 'hidden';
                    conditionsInput.name = 'conditions';
                    conditionsInput.id = 'conditions-input';
                    document.querySelector('form').appendChild(conditionsInput);
                }
                conditionsInput.value = JSON.stringify(conditions);

                console.log('Form submission proceeding...');
            });

            // Condition management functions
            window.addCondition = function() {
                const container = document.getElementById('conditions-container');
                const conditionId = Date.now();

                const conditionHtml = `
                    <div class="condition-row row mb-2" data-id="${conditionId}">
                        <div class="col-md-4">
                            <select class="form-select condition-field" required>
                                <option value="">Select Field</option>
                                <option value="booth_type">Booth Type</option>
                                <option value="payment_amount">Payment Amount</option>
                                <option value="booth_price">Booth Price</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select condition-operator" required>
                                <option value="">Operator</option>
                                <option value="equals">Equals</option>
                                <option value="not_equals">Not Equals</option>
                                <option value="greater_than">Greater Than</option>
                                <option value="less_than">Less Than</option>
                                <option value="contains">Contains</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <input type="text" class="form-control condition-value" placeholder="Value" required>
                        </div>
                        <div class="col-md-1">
                            <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeCondition(${conditionId})">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>
                `;

                container.insertAdjacentHTML('beforeend', conditionHtml);
            };

            window.removeCondition = function(conditionId) {
                const conditionRow = document.querySelector(`[data-id="${conditionId}"]`);
                if (conditionRow) {
                    conditionRow.remove();
                }
            };
        });
    </script>
    @endpush

</x-event-layout>