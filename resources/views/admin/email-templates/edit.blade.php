<x-event-layout :event="$event">

    <div class="py-4">
        <div class="container-fluid">
            <!-- Page Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="h4 mb-1 fw-bold">
                        <i class="bi bi-pencil me-2 text-warning"></i>
                        Edit Email Template
                    </h2>
                    <p class="text-muted mb-0">Update "{{ $emailTemplate->name }}" for {{ $event->name }}</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('events.email-templates.show', [$event, $emailTemplate]) }}" class="btn btn-outline-primary">
                        <i class="bi bi-eye me-2"></i>View Template
                    </a>
                    <a href="{{ route('events.email-templates.index', $event) }}" class="btn btn-outline-secondary">
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
                            <form action="{{ route('events.email-templates.update', [$event, $emailTemplate]) }}" method="POST">
                                @csrf
                                @method('PUT')
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="name" class="form-label fw-medium">Template Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                               id="name" name="name" value="{{ old('name', $emailTemplate->name) }}" 
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
                                                <option value="{{ $key }}" {{ old('trigger_type', $emailTemplate->trigger_type) == $key ? 'selected' : '' }}>
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
                                           id="subject" name="subject" value="{{ old('subject', $emailTemplate->subject) }}" 
                                           placeholder="e.g., Welcome to {{ $event->name }}!" required>
                                    @error('subject')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="content" class="form-label fw-medium">Email Content <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('content') is-invalid @enderror" 
                                              id="content" name="content" rows="15" required>{{ old('content', $emailTemplate->content) }}</textarea>
                                    @error('content')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" 
                                               {{ old('is_active', $emailTemplate->is_active) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-medium" for="is_active">
                                            Activate this template
                                        </label>
                                    </div>
                                </div>

                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-warning">
                                        <i class="bi bi-check-circle me-2"></i>Update Template
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
                                    <div class="fw-medium">{{ $emailTemplate->created_at->format('M d, Y') }}</div>
                                </div>
                                <div class="col-6 mb-3">
                                    <div class="text-muted small">Last Updated</div>
                                    <div class="fw-medium">{{ $emailTemplate->updated_at->format('M d, Y') }}</div>
                                </div>
                            </div>
                            <hr>
                            <div class="d-grid">
                                <button type="button" class="btn btn-outline-info btn-sm test-template-btn" 
                                        data-template-id="{{ $emailTemplate->id }}" 
                                        data-event-id="{{ $event->id }}">
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

    @push('styles')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.7.3/tinymce.min.js" referrerpolicy="origin"></script>
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
            // Initialize TinyMCE
            tinymce.init({
                selector: '#content',
                height: 400,
                plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
                toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
                content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; font-size: 14px; }',
                menubar: false,
                branding: false,
                promotion: false,
                setup: function(editor) {
                    // Insert merge field at cursor position
                    window.insertMergeField = function(field) {
                        const placeholder = '{{ ' + field + ' }}';
                        editor.insertContent(placeholder);
                        editor.focus();
                    };
                }
            });

            // Merge field insertion
            document.querySelectorAll('.merge-field-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const field = this.dataset.field;
                    if (window.insertMergeField) {
                        window.insertMergeField(field);
                    }
                });
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
    @endpush

</x-event-layout>
