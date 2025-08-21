<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 mb-0">
                <i class="bi bi-file-earmark-text me-2"></i>
                {{ $event->title }} - Form Builders
            </h2>
            <div class="d-flex gap-2">
                <a href="{{ route('events.registration', $event) }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Back to Registration
                </a>
                <a href="{{ route('events.form-builders.create', $event) }}" class="btn btn-success">
                    <i class="bi bi-plus-circle me-2"></i>Create New Form
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-4">
        <div class="container-fluid">
            <!-- Event Header -->
            <div class="card mb-4">
                <div class="position-relative">
                    <!-- Event Logo/Image -->
                    <div class="bg-gradient-to-br from-blue-400 to-purple-600 d-flex align-items-center justify-content-center position-relative overflow-hidden" style="height: 200px;">
                        @if($event->logo)
                            <img src="{{ Storage::url($event->logo) }}" alt="{{ $event->title }}" class="w-100 h-100 object-fit-cover">
                        @else
                            <i class="bi bi-calendar-event text-white" style="font-size: 4rem;"></i>
                        @endif
                        
                        <!-- Status Badge -->
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

                    <!-- Event Title Overlay -->
                    <div class="position-absolute bottom-0 start-0 end-0 bg-dark bg-opacity-75 text-white p-4">
                        <h1 class="h2 mb-2">{{ $event->title }}</h1>
                        <p class="mb-0 opacity-75">{{ $event->start_date->format('F d, Y') }} - {{ $event->end_date->format('F d, Y') }}</p>
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

            <!-- Stats Summary -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <i class="bi bi-file-earmark-text text-primary fs-1 d-block mb-2"></i>
                            <h5 class="card-title">{{ $formBuilders->count() }}</h5>
                            <p class="card-text text-muted">Total Forms</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <i class="bi bi-check-circle text-success fs-1 d-block mb-2"></i>
                            <h5 class="card-title">{{ $formBuilders->where('status', 'published')->count() }}</h5>
                            <p class="card-text text-muted">Published</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <i class="bi bi-pencil-square text-warning fs-1 d-block mb-2"></i>
                            <h5 class="card-title">{{ $formBuilders->where('status', 'draft')->count() }}</h5>
                            <p class="card-text text-muted">Draft</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <i class="bi bi-archive text-secondary fs-1 d-block mb-2"></i>
                            <h5 class="card-title">{{ $formBuilders->where('status', 'archived')->count() }}</h5>
                            <p class="card-text text-muted">Archived</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Forms List -->
            @if($formBuilders->count() > 0)
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">All Forms</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Form Name</th>
                                        <th>Description</th>
                                        <th>Status</th>
                                        <th>Fields</th>
                                        <th>Created</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($formBuilders as $formBuilder)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-file-earmark-text text-primary me-2"></i>
                                                <strong>{{ $formBuilder->name }}</strong>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="text-muted">{{ $formBuilder->description ?: 'No description' }}</span>
                                        </td>
                                        <td>
                                            <span class="badge 
                                                @if($formBuilder->status === 'published') bg-success
                                                @elseif($formBuilder->status === 'draft') bg-warning
                                                @else bg-secondary @endif">
                                                {{ ucfirst($formBuilder->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">{{ $formBuilder->fields->count() }} fields</span>
                                        </td>
                                        <td>
                                            <small class="text-muted">{{ $formBuilder->created_at->format('M d, Y') }}</small>
                                        </td>
                                        <td>
                                            <div class="d-flex gap-1">
                                                <a href="{{ route('events.form-builders.show', [$event, $formBuilder]) }}" 
                                                   class="btn btn-sm btn-outline-primary" title="View">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="{{ route('events.form-builders.edit', [$event, $formBuilder]) }}" 
                                                   class="btn btn-sm btn-outline-warning" title="Edit">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <a href="{{ route('events.form-builders.preview', [$event, $formBuilder]) }}" 
                                                   class="btn btn-sm btn-outline-info" title="Preview" target="_blank">
                                                    <i class="bi bi-eye-fill"></i>
                                                </a>
                                                <form action="{{ route('events.form-builders.destroy', [$event, $formBuilder]) }}" 
                                                      method="POST" class="d-inline" 
                                                      onsubmit="return confirm('Are you sure you want to delete this form?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
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
