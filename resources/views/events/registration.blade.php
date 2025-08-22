<x-event-layout :event="$event">

    <div class="py-4">
        <div class="container-fluid">
            <!-- Page Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="h4 mb-1 fw-bold">
                        <i class="bi bi-people me-2 text-info"></i>
                        Exhibitors & Visitors
                    </h2>
                    <p class="text-muted mb-0">Manage participant registrations and forms for {{ $event->title }}</p>
                </div>
            </div>

            <!-- Form Builder Actions -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="h5 mb-2">Form Builder</h4>
                            <p class="text-muted mb-0">Create and manage custom registration forms for your event participants.</p>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('events.form-builders.index', $event) }}" class="btn btn-primary">
                                <i class="bi bi-list me-2"></i>View Forms
                            </a>
                            <a href="{{ route('events.form-builders.create', $event) }}" class="btn btn-success">
                                <i class="bi bi-plus-circle me-2"></i>Create New Form
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card text-center">
                        <div class="card-body">
                            <i class="bi bi-file-earmark-text text-primary fs-1 d-block mb-2"></i>
                            <h5 class="card-title">{{ $event->formBuilders->count() }}</h5>
                            <p class="card-text text-muted">Total Forms</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-center">
                        <div class="card-body">
                            <i class="bi bi-check-circle text-success fs-1 d-block mb-2"></i>
                            <h5 class="card-title">{{ $event->formBuilders->where('status', 'published')->count() }}</h5>
                            <p class="card-text text-muted">Published Forms</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-center">
                        <div class="card-body">
                            <i class="bi bi-pencil-square text-warning fs-1 d-block mb-2"></i>
                            <h5 class="card-title">{{ $event->formBuilders->where('status', 'draft')->count() }}</h5>
                            <p class="card-text text-muted">Draft Forms</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Forms -->
            @if($event->formBuilders->count() > 0)
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Recent Forms</h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        @foreach($event->formBuilders->take(5) as $formBuilder)
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1">{{ $formBuilder->name }}</h6>
                                <small class="text-muted">{{ $formBuilder->description ?: 'No description' }}</small>
                            </div>
                            <div class="d-flex gap-2">
                                <span class="badge 
                                    @if($formBuilder->status === 'published') bg-success
                                    @elseif($formBuilder->status === 'draft') bg-warning
                                    @else bg-secondary @endif">
                                    {{ ucfirst($formBuilder->status) }}
                                </span>
                                <a href="{{ route('events.form-builders.show', [$event, $formBuilder]) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye me-1"></i>View
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
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
</x-app-layout>
