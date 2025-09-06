<x-event-layout :event="$event">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h4 mb-0">
            <i class="bi bi-eye me-2"></i>
            {{ $formBuilder->name }} - {{ $event->title }}
        </h2>
        <div class="d-flex gap-2">
            <a href="{{ route('events.form-builders.index', $event) }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-2"></i>Back to Forms
            </a>
            <a href="{{ route('events.form-builders.edit', [$event, $formBuilder]) }}" class="btn btn-warning">
                <i class="bi bi-pencil me-2"></i>Edit Form
            </a>
            <a href="{{ route('events.form-builders.preview', [$event, $formBuilder]) }}" class="btn btn-info" target="_blank">
                <i class="bi bi-eye-fill me-2"></i>Preview
            </a>
        </div>
    </div>

    <div class="card">
            <!-- Event Header -->
                <div class="position-relative">
                    <div class="bg-gradient-to-br from-blue-400 to-purple-600 d-flex align-items-center justify-content-center position-relative overflow-hidden" style="height: 150px;">
                        @if($event->logo)
                            <img src="{{ Storage::url($event->logo) }}" alt="{{ $event->title }}" class="w-100 h-100 object-fit-cover">
                        @else
                            <i class="bi bi-calendar-event text-white" style="font-size: 3rem;"></i>
                        @endif
                        
                        <div class="position-absolute top-0 end-0 m-3">
                            <span class="badge 
                                @if($event->status === 'active') bg-success
                                @elseif($event->status === 'published') bg-primary
                                @elseif($event->status === 'completed') bg-info
                                @elseif($event->status === 'cancelled') bg-danger
                                @else bg-secondary @endif fs-6">
                                {{ ucfirst($event->status) }}
                            </span>
                        </div>
                    </div>

                    <div class="position-absolute bottom-0 start-0 end-0 bg-dark bg-opacity-75 text-white p-3">
                        <h1 class="h4 mb-1">{{ $event->title }}</h1>
                        <p class="mb-0 opacity-75 small">{{ $event->start_date->format('F d, Y') }} - {{ $event->end_date->format('F d, Y') }}</p>
                    </div>
                </div>
            </div>

            <!-- Event Navigation Menu -->
            <div class="card mb-4">
                <div class="card-body p-3">
                    <ul class="nav nav-tabs nav-fill">
                        <li class="nav-item">
                            <a href="{{ route('events.dashboard', $event) }}" class="nav-link">
                                <i class="bi bi-speedometer2 me-2"></i>
                                Event Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('events.floorplan', $event) }}" class="nav-link">
                                <i class="bi bi-map me-2"></i>
                                Floorplan
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('events.registration', $event) }}" class="nav-link">
                                <i class="bi bi-person-plus me-2"></i>
                                Registration Form
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="row">
                <!-- Form Details -->
                <div class="col-lg-8">
                    <!-- Form Information -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="bi bi-info-circle me-2"></i>Form Information
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Form Name</label>
                                        <p class="mb-0">{{ $formBuilder->name }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Status</label>
                                        <div>
                                            <span class="badge 
                                                @if($formBuilder->status === 'published') bg-success
                                                @elseif($formBuilder->status === 'draft') bg-warning
                                                @else bg-secondary @endif fs-6">
                                                {{ ucfirst($formBuilder->status) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if($formBuilder->description)
                            <div class="mb-3">
                                <label class="form-label fw-bold">Description</label>
                                <p class="mb-0">{{ $formBuilder->description }}</p>
                            </div>
                            @endif

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Submit Button Text</label>
                                        <p class="mb-0">{{ $formBuilder->submit_button_text }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Theme Color</label>
                                        <div class="d-flex align-items-center">
                                            <div class="color-preview me-2" style="width: 20px; height: 20px; background-color: {{ $formBuilder->theme_color }}; border-radius: 4px;"></div>
                                            <span>{{ $formBuilder->theme_color }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Multiple Submissions</label>
                                        <p class="mb-0">
                                            <i class="bi bi-{{ $formBuilder->allow_multiple_submissions ? 'check-circle text-success' : 'x-circle text-danger' }}"></i>
                                            {{ $formBuilder->allow_multiple_submissions ? 'Allowed' : 'Not Allowed' }}
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Login Required</label>
                                        <p class="mb-0">
                                            <i class="bi bi-{{ $formBuilder->require_login ? 'check-circle text-success' : 'x-circle text-danger' }}"></i>
                                            {{ $formBuilder->require_login ? 'Required' : 'Not Required' }}
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Confirmation Email</label>
                                        <p class="mb-0">
                                            <i class="bi bi-{{ $formBuilder->send_confirmation_email ? 'check-circle text-success' : 'x-circle text-danger' }}"></i>
                                            {{ $formBuilder->send_confirmation_email ? 'Enabled' : 'Disabled' }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            @if($formBuilder->confirmation_message)
                            <div class="mb-3">
                                <label class="form-label fw-bold">Confirmation Message</label>
                                <p class="mb-0">{{ $formBuilder->confirmation_message }}</p>
                            </div>
                            @endif

                            @if($formBuilder->redirect_url)
                            <div class="mb-3">
                                <label class="form-label fw-bold">Redirect URL</label>
                                <p class="mb-0">
                                    <a href="{{ $formBuilder->redirect_url }}" target="_blank">{{ $formBuilder->redirect_url }}</a>
                                </p>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Form Fields -->
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="bi bi-list-ul me-2"></i>Form Fields ({{ $formBuilder->fields->count() }})
                            </h5>
                        </div>
                        <div class="card-body">
                            @if($formBuilder->fields->count() > 0)
                                <div class="row">
                                    @foreach($formBuilder->fields as $field)
                                    <div class="col-md-6 mb-3">
                                        <div class="border rounded p-3 bg-light">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <span class="badge bg-primary">{{ ucfirst($field->type) }}</span>
                                                @if($field->required)
                                                    <span class="badge bg-danger">Required</span>
                                                @endif
                                            </div>
                                            <h6 class="mb-2">{{ $field->label }}</h6>
                                            
                                            @if($field->placeholder)
                                                <small class="text-muted d-block mb-1">
                                                    <i class="bi bi-quote me-1"></i>{{ $field->placeholder }}
                                                </small>
                                            @endif
                                            
                                            @if($field->help_text)
                                                <small class="text-muted d-block mb-1">
                                                    <i class="bi bi-info-circle me-1"></i>{{ $field->help_text }}
                                                </small>
                                            @endif
                                            
                                            @if($field->options)
                                                <small class="text-muted d-block">
                                                    <i class="bi bi-list me-1"></i>Options: {{ implode(', ', $field->options) }}
                                                </small>
                                            @endif
                                            
                                            <div class="mt-2">
                                                <span class="badge bg-secondary">{{ ucfirst($field->width) }} Width</span>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center text-muted py-4">
                                    <i class="bi bi-list-ul fs-1 d-block mb-2"></i>
                                    <p class="mb-0">No fields defined for this form</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="col-lg-4">
                    <!-- Quick Stats -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0">
                                <i class="bi bi-graph-up me-2"></i>Quick Stats
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-6">
                                    <div class="border-end">
                                        <h4 class="text-primary mb-1">{{ $formBuilder->submissions->count() }}</h4>
                                        <small class="text-muted">Total Submissions</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <h4 class="text-success mb-1">{{ $formBuilder->submissions->where('status', 'submitted')->count() }}</h4>
                                    <small class="text-muted">Pending Review</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0">
                                <i class="bi bi-gear me-2"></i>Form Actions
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                @if($formBuilder->status === 'draft')
                                    <form action="{{ route('events.form-builders.update', [$event, $formBuilder]) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="published">
                                        <button type="submit" class="btn btn-success w-100">
                                            <i class="bi bi-check-circle me-2"></i>Publish Form
                                        </button>
                                    </form>
                                @elseif($formBuilder->status === 'published')
                                    <form action="{{ route('events.form-builders.update', [$event, $formBuilder]) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="draft">
                                        <button type="submit" class="btn btn-warning w-100">
                                            <i class="bi bi-pause-circle me-2"></i>Unpublish Form
                                        </button>
                                    </form>
                                @endif
                                
                                <a href="{{ route('events.form-builders.preview', [$event, $formBuilder]) }}" class="btn btn-info w-100" target="_blank">
                                    <i class="bi bi-eye-fill me-2"></i>Preview Form
                                </a>
                                
                                <a href="{{ route('events.form-builders.edit', [$event, $formBuilder]) }}" class="btn btn-warning w-100">
                                    <i class="bi bi-pencil me-2"></i>Edit Form
                                </a>
                                
                                <form action="{{ route('events.form-builders.destroy', [$event, $formBuilder]) }}" method="POST" class="d-inline" 
                                      onsubmit="return confirm('Are you sure you want to delete this form? This action cannot be undone.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger w-100">
                                        <i class="bi bi-trash me-2"></i>Delete Form
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Submissions -->
                    @if($formBuilder->submissions->count() > 0)
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">
                                <i class="bi bi-clock-history me-2"></i>Recent Submissions
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="list-group list-group-flush">
                                @foreach($formBuilder->submissions->take(5) as $submission)
                                <div class="list-group-item px-0">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <small class="text-muted d-block">
                                                {{ $submission->created_at->format('M d, Y H:i') }}
                                            </small>
                                            <span class="badge 
                                                @if($submission->status === 'submitted') bg-warning
                                                @elseif($submission->status === 'approved') bg-success
                                                @else bg-danger @endif">
                                                {{ ucfirst($submission->status) }}
                                            </span>
                                        </div>
                                        @if($submission->user)
                                            <small class="text-muted">{{ $submission->user->name }}</small>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @if($formBuilder->submissions->count() > 5)
                                <div class="text-center mt-2">
                                    <small class="text-muted">And {{ $formBuilder->submissions->count() - 5 }} more...</small>
                                </div>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-event-layout>
