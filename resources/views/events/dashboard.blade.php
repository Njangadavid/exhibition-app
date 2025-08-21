<x-app-layout>
    <x-slot name="header">
        <!-- Event Info Header -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="d-flex align-items-center">
                <div class="me-3">
                    <div class="bg-gradient-to-br from-blue-400 to-purple-600 rounded d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                        @if($event->logo)
                            <img src="{{ Storage::url($event->logo) }}" alt="{{ $event->title }}" class="rounded" style="width: 40px; height: 40px; object-fit: cover;">
                        @else
                            <i class="bi bi-calendar-event text-white"></i>
                        @endif
                    </div>
                </div>
                <div>
                    <h2 class="h5 mb-0">{{ $event->title }}</h2>
                    <small class="text-muted">{{ $event->start_date->format('M d, Y') }} - {{ $event->end_date->format('M d, Y') }}</small>
                </div>
            </div>
            <a href="{{ route('events.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i>
                Back to Events
            </a>
        </div>

        <!-- Event Navigation Menu -->
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a href="{{ route('events.dashboard', $event) }}" class="nav-link active">
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
    </x-slot>

    <div class="py-4">
        <div class="container-fluid">


            <!-- Event Dashboard Content -->
            <div class="row">
                <!-- Event Overview -->
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h3 class="h5 mb-3">
                                <i class="bi bi-info-circle me-2 text-primary"></i>
                                Event Overview
                            </h3>
                            
                            <div class="d-flex flex-column gap-3">
                                <div>
                                    <div class="text-muted small mb-1">Status</div>
                                    <span class="badge 
                                        @if($event->status === 'active') bg-success
                                        @elseif($event->status === 'published') bg-primary
                                        @elseif($event->status === 'completed') bg-info
                                        @elseif($event->status === 'cancelled') bg-danger
                                        @else bg-secondary @endif">
                                        {{ ucfirst($event->status) }}
                                    </span>
                                </div>
                                
                                <div>
                                    <div class="text-muted small mb-1">Duration</div>
                                    <div class="fw-medium">{{ $event->duration_in_days }} days</div>
                                </div>
                                
                                <div>
                                    <div class="text-muted small mb-1">Created</div>
                                    <div class="fw-medium">{{ $event->created_at->format('M d, Y') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions and Statistics Side by Side -->
                <div class="col-md-6">
                    <div class="d-flex flex-column gap-4">
                        <!-- Quick Actions -->
                        <div class="card">
                            <div class="card-body">
                                <h3 class="h5 mb-3">
                                    <i class="bi bi-tools me-2 text-success"></i>
                                    Quick Actions
                                </h3>
                                
                                <div class="d-grid gap-2">
                                    <a href="{{ route('events.edit', $event) }}" class="btn btn-outline-secondary">
                                        <i class="bi bi-pencil me-2"></i>
                                        Edit Event
                                    </a>
                                    <a href="{{ route('events.show', $event) }}" class="btn btn-primary">
                                        <i class="bi bi-eye me-2"></i>
                                        View Details
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Event Statistics -->
                        <div class="card">
                            <div class="card-body">
                                <h3 class="h5 mb-3">
                                    <i class="bi bi-graph-up me-2 text-info"></i>
                                    Statistics
                                </h3>
                                
                                <div class="d-flex flex-column gap-3">
                                    <div class="text-center p-3 bg-primary bg-opacity-10 rounded">
                                        <div class="h3 fw-bold text-primary">0</div>
                                        <div class="small text-primary">Registrations</div>
                                    </div>
                                    <div class="text-center p-3 bg-info bg-opacity-10 rounded">
                                        <div class="h3 fw-bold text-info">0</div>
                                        <div class="small text-info">Floor Plans</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
