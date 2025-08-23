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
                                    @error('content')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
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

            // Trigger type change handler
            document.getElementById('trigger_type').addEventListener('change', function() {
                // You can add dynamic logic here to show/hide certain merge fields
                // based on the selected trigger type
            });
        });
    </script>
    @endpush

</x-event-layout>
