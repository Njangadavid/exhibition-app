<x-event-layout :event="$event">

    <div class="py-4">
        <div class="container-fluid">
            <!-- Page Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="h4 mb-1 fw-bold">
                        <i class="bi bi-envelope me-2 text-primary"></i>
                        {{ $emailTemplate->name }}
                    </h2>
                    <p class="text-muted mb-0">Email template for {{ $event->name }}</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('events.email-templates.edit', [$event, $emailTemplate]) }}" class="btn btn-warning">
                        <i class="bi bi-pencil me-2"></i>Edit Template
                    </a>
                    <a href="{{ route('events.email-templates.index', $event) }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-2"></i>Back to Templates
                    </a>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-8">
                    <!-- Template Preview -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-eye me-2 text-info"></i>
                                Template Preview
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label fw-medium">Subject:</label>
                                <div class="p-3 bg-light rounded border">
                                    {{ $emailTemplate->subject }}
                                </div>
                            </div>
                            
                            <div>
                                <label class="form-label fw-medium">Content:</label>
                                <div class="p-3 bg-light rounded border" style="min-height: 300px;">
                                    {!! $emailTemplate->content !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Test Results -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-play-circle me-2 text-success"></i>
                                Test Template
                            </h5>
                        </div>
                        <div class="card-body">
                            <p class="text-muted mb-3">Test how your template looks with sample data to ensure merge fields work correctly.</p>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-medium">Sample Data:</label>
                                    <div class="p-3 bg-light rounded border" style="max-height: 200px; overflow-y: auto;">
                                        <pre class="mb-0 small">{{ json_encode($sampleData, JSON_PRETTY_PRINT) }}</pre>
                                    </div>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-medium">Processed Result:</label>
                                    <div id="processedResult" class="p-3 bg-light rounded border" style="min-height: 200px;">
                                        <div class="text-center text-muted">
                                            <i class="bi bi-arrow-right fs-4 d-block mb-2"></i>
                                            Click "Test Template" to see processed result
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="d-grid">
                                <button type="button" class="btn btn-success test-template-btn" 
                                        data-template-id="{{ $emailTemplate->id }}" 
                                        data-event-id="{{ $event->id }}">
                                    <i class="bi bi-play-circle me-2"></i>Test Template
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <!-- Template Information -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white">
                            <h6 class="card-title mb-0">
                                <i class="bi bi-info-circle me-2 text-primary"></i>
                                Template Information
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label small text-muted">Status</label>
                                <div>
                                    <span class="badge 
                                        @if($emailTemplate->is_active) bg-success
                                        @else bg-secondary @endif">
                                        {{ $emailTemplate->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label small text-muted">Trigger Type</label>
                                <div>
                                    <span class="badge bg-info bg-opacity-10 text-info">
                                        <i class="bi bi-lightning me-1"></i>
                                        {{ $emailTemplate->trigger_type_display_name }}
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label small text-muted">Created</label>
                                <div class="fw-medium">{{ $emailTemplate->created_at->format('M d, Y \a\t g:i A') }}</div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label small text-muted">Last Updated</label>
                                <div class="fw-medium">{{ $emailTemplate->updated_at->format('M d, Y \a\t g:i A') }}</div>
                            </div>
                            
                            @if(!empty($emailTemplate->conditions))
                            <div class="mb-3">
                                <label class="form-label small text-muted">Conditions</label>
                                <div>
                                    @foreach($emailTemplate->conditions as $condition)
                                        <span class="badge bg-light text-dark me-1 mb-1">
                                            {{ $condition['field'] }} {{ $condition['operator'] }} {{ $condition['value'] }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Available Merge Fields -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white">
                            <h6 class="card-title mb-0">
                                <i class="bi bi-tags me-2 text-info"></i>
                                Available Merge Fields
                            </h6>
                        </div>
                        <div class="card-body">
                            @foreach($mergeFields as $category => $fields)
                                <div class="mb-3">
                                    <h6 class="fw-medium text-capitalize mb-2">{{ $category }}</h6>
                                    <div class="d-flex flex-wrap gap-1">
                                        @foreach($fields as $key => $label)
                                            <span class="badge bg-info bg-opacity-10 text-info">
                                                {{ $label }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white">
                            <h6 class="card-title mb-0">
                                <i class="bi bi-lightning me-2 text-warning"></i>
                                Quick Actions
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a href="{{ route('events.email-templates.edit', [$event, $emailTemplate]) }}" 
                                   class="btn btn-outline-warning btn-sm">
                                    <i class="bi bi-pencil me-2"></i>Edit Template
                                </a>
                                
                                <form action="{{ route('events.email-templates.clone', [$event, $emailTemplate]) }}" 
                                      method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-secondary btn-sm w-100">
                                        <i class="bi bi-files me-2"></i>Clone Template
                                    </button>
                                </form>
                                
                                <form action="{{ route('events.email-templates.toggle-status', [$event, $emailTemplate]) }}" 
                                      method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-outline-{{ $emailTemplate->is_active ? 'warning' : 'success' }} btn-sm w-100">
                                        <i class="bi bi-{{ $emailTemplate->is_active ? 'pause' : 'play' }} me-2"></i>
                                        {{ $emailTemplate->is_active ? 'Deactivate' : 'Activate' }}
                                    </button>
                                </form>
                                
                                <form action="{{ route('events.email-templates.destroy', [$event, $emailTemplate]) }}" 
                                      method="POST" 
                                      onsubmit="return confirm('Are you sure you want to delete this template? This action cannot be undone.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm w-100">
                                        <i class="bi bi-trash me-2"></i>Delete Template
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Test template functionality
            document.querySelector('.test-template-btn').addEventListener('click', function() {
                const templateId = this.dataset.templateId;
                const eventId = this.dataset.eventId;
                const button = this;
                const originalText = button.innerHTML;
                
                // Show loading state
                button.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Testing...';
                button.disabled = true;
                
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
                    const resultsDiv = document.getElementById('processedResult');
                    
                    if (data.success) {
                        resultsDiv.innerHTML = `
                            <div class="mb-3">
                                <strong class="text-success">Subject:</strong><br>
                                <div class="mt-1 p-2 bg-white rounded border">${data.subject}</div>
                            </div>
                            <div>
                                <strong class="text-success">Content:</strong><br>
                                <div class="mt-1 p-2 bg-white rounded border" style="max-height: 150px; overflow-y: auto;">
                                    ${data.content}
                                </div>
                            </div>
                        `;
                    } else {
                        resultsDiv.innerHTML = `
                            <div class="alert alert-danger mb-0">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                <strong>Test Failed:</strong> ${data.error}
                            </div>
                        `;
                    }
                })
                .catch(error => {
                    document.getElementById('processedResult').innerHTML = `
                        <div class="alert alert-danger mb-0">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            <strong>Error:</strong> Failed to test template: ${error.message}
                        </div>
                    `;
                })
                .finally(() => {
                    // Restore button state
                    button.innerHTML = originalText;
                    button.disabled = false;
                });
            });
        });
    </script>
    @endpush

</x-event-layout>

