<x-event-layout :event="$event">

    <div class="py-4">
        <div class="container-fluid">
                        <!-- Page Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="h4 mb-1 fw-bold">
                        <i class="bi bi-pencil-square me-2 text-success"></i>
                        Participant Forms
                    </h2>
                                            <p class="text-muted mb-0">Create and manage custom registration forms for {{ $event->name }}</p>
                </div>
                <a href="{{ route('events.form-builders.create', $event) }}" class="btn btn-success">
                    <i class="bi bi-plus-circle me-2"></i>Create New Form
                </a>
            </div>

            <!-- Quick Stats Dashboard -->
            <div class="row mb-4">
                <div class="col-md-6 col-lg-3 mb-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                                        <i class="bi bi-file-earmark-text text-primary fs-2"></i>
                                    </div>
                                </div>
                                <div class="ms-3">
                                    <div class="text-muted small fw-medium">Total Forms</div>
                                    <div class="h3 fw-bold text-dark mb-0">{{ $formBuilders->count() }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3 mb-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="bg-success bg-opacity-10 rounded-circle p-3">
                                        <i class="bi bi-check-circle text-success fs-2"></i>
                                    </div>
                                </div>
                                <div class="ms-3">
                                    <div class="text-muted small fw-medium">Published Forms</div>
                                    <div class="h3 fw-bold text-success mb-0">{{ $formBuilders->where('status', 'published')->count() }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3 mb-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="bg-warning bg-opacity-10 rounded-circle p-3">
                                        <i class="bi bi-pencil-square text-warning fs-2"></i>
                                    </div>
                                </div>
                                <div class="ms-3">
                                    <div class="text-muted small fw-medium">Draft Forms</div>
                                    <div class="h3 fw-bold text-warning mb-0">{{ $formBuilders->where('status', 'draft')->count() }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3 mb-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="bg-secondary bg-opacity-10 rounded-circle p-3">
                                        <i class="bi bi-archive text-secondary fs-2"></i>
                                    </div>
                                </div>
                                <div class="ms-3">
                                    <div class="text-muted small fw-medium">Archived Forms</div>
                                    <div class="h3 fw-bold text-secondary mb-0">{{ $formBuilders->where('status', 'archived')->count() }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Forms List - Card Layout -->
            @if($formBuilders->count() > 0)
                <div class="row g-4">
                    @foreach($formBuilders as $formBuilder)
                    <div class="col-md-6 col-lg-4">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-header bg-transparent border-0 pb-0">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                            <i class="bi bi-file-earmark-text text-primary"></i>
                                        </div>
                                        <div>
                                            <h5 class="card-title mb-1 fw-bold">{{ $formBuilder->name }}</h5>
                                            <span class="badge 
                                                @if($formBuilder->status === 'published') bg-success
                                                @elseif($formBuilder->status === 'draft') bg-warning
                                                @else bg-secondary @endif">
                                                {{ ucfirst($formBuilder->status) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="card-body pt-0">
                                <p class="text-muted small mb-3">
                                    {{ $formBuilder->description ?: 'No description provided' }}
                                </p>
                                
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="badge bg-info bg-opacity-10 text-info">
                                        <i class="bi bi-list-ul me-1"></i>
                                        {{ $formBuilder->fields->count() }} fields
                                    </span>
                                    <small class="text-muted">
                                        <i class="bi bi-calendar3 me-1"></i>
                                        {{ $formBuilder->created_at->format('M d, Y') }}
                                    </small>
                                </div>
                            </div>
                            
                            <div class="card-footer bg-transparent border-0 pt-0">
                                <div class="d-grid gap-2">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('events.form-builders.show', [$event, $formBuilder]) }}" 
                                           class="btn btn-outline-primary btn-sm">
                                            <i class="bi bi-eye me-1"></i>View
                                        </a>
                                        <a href="{{ route('events.form-builders.edit', [$event, $formBuilder]) }}" 
                                           class="btn btn-outline-warning btn-sm">
                                            <i class="bi bi-pencil me-1"></i>Edit
                                        </a>
                                        <a href="{{ route('events.form-builders.preview', [$event, $formBuilder]) }}" 
                                           class="btn btn-outline-info btn-sm" target="_blank">
                                            <i class="bi bi-eye-fill me-1"></i>Preview
                                        </a>
                                    </div>
                                    
                                    <form action="{{ route('events.form-builders.destroy', [$event, $formBuilder]) }}" 
                                          method="POST" class="d-grid" 
                                          onsubmit="return confirm('Are you sure you want to delete this form?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm">
                                            <i class="bi bi-trash me-1"></i>Delete Form
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <!-- Empty State -->
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="bi bi-file-earmark-text text-muted fs-1 d-block mb-3"></i>
                        <h3 class="h5 mb-2">No Forms Created Yet</h3>
                        <p class="text-muted mb-3">Start building custom registration forms for your event participants.</p>
                        <a href="{{ route('events.form-builders.create', $event) }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle me-2"></i>Create Your First Form
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-event-layout>
