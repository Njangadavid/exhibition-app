<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 mb-0">
                <i class="bi bi-calendar-event me-2"></i>
                {{ $event->title }}
            </h2>
            <div class="d-flex gap-2">
                <a href="{{ route('events.edit', $event) }}" class="btn btn-primary">
                    <i class="bi bi-pencil me-2"></i>
                    Edit Event
                </a>
                <a href="{{ route('events.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left me-2"></i>
                    Back to Events
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-4">
        <div class="container">
            <!-- Event Header -->
            <div class="card mb-4">
                <div class="position-relative">
                    <!-- Event Logo/Image -->
                    <div class="bg-gradient-to-br from-blue-400 to-purple-600 d-flex align-items-center justify-content-center position-relative overflow-hidden" style="height: 256px;">
                        @if($event->logo)
                            <img src="{{ Storage::url($event->logo) }}" alt="{{ $event->title }}" class="w-100 h-100 object-fit-cover">
                        @else
                            <i class="bi bi-calendar-event text-white" style="font-size: 6rem;"></i>
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
                    <div class="position-absolute bottom-0 start-0 end-0 bg-dark bg-opacity-75 p-4">
                        <h1 class="h2 mb-0 text-white">{{ $event->title }}</h1>
                    </div>
                </div>

                <!-- Event Meta Information -->
                <div class="card-body">
                    <div class="row">
                        <!-- Start Date -->
                        <div class="col-md-4 mb-3">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-calendar-event text-primary me-3 fs-4"></i>
                                <div>
                                    <div class="text-muted small">Start Date</div>
                                    <div class="h5 fw-bold mb-1">
                                        {{ $event->start_date->format('F d, Y') }}
                                    </div>
                                    <div class="text-muted small">
                                        {{ $event->start_date->format('g:i A') }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- End Date -->
                        <div class="col-md-4 mb-3">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-calendar-check text-success me-3 fs-4"></i>
                                <div>
                                    <div class="text-muted small">End Date</div>
                                    <div class="h5 fw-bold mb-1">
                                        {{ $event->end_date->format('F d, Y') }}
                                    </div>
                                    <div class="text-muted small">
                                        {{ $event->end_date->format('g:i A') }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Duration -->
                        <div class="col-md-4 mb-3">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-calendar text-info me-3 fs-4"></i>
                                <div>
                                    <div class="text-muted small">Duration</div>
                                    <div class="h5 fw-bold mb-1">
                                        {{ $event->duration_in_days }} days
                                    </div>
                                    <div class="text-muted small">
                                        @if($event->duration_in_days == 1)
                                            Single day event
                                        @else
                                            Multi-day event
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Event Details -->
            <div class="row">
                <!-- Main Content -->
                <div class="col-lg-8 mb-4">
                    <!-- Description -->
                    <div class="card mb-4">
                        <div class="card-body">
                            <h3 class="h5 mb-3">
                                <i class="bi bi-text-paragraph me-2"></i>
                                Event Description
                            </h3>
                            <div class="text-muted">
                                <p class="mb-0">
                                    {{ $event->description }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Event Status Details -->
                    <div class="card">
                        <div class="card-body">
                            <h3 class="h5 mb-3">
                                <i class="bi bi-info-circle me-2"></i>
                                Event Status Information
                            </h3>
                            <div class="d-flex flex-column gap-3">
                                <div class="d-flex align-items-center justify-content-between p-3 bg-light rounded">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-flag text-muted me-3"></i>
                                        <span class="text-muted">Current Status</span>
                                    </div>
                                    <span class="fw-medium">
                                        {{ ucfirst($event->status) }}
                                    </span>
                                </div>

                                @if($event->isActive())
                                    <div class="d-flex align-items-center p-3 bg-success bg-opacity-10 rounded">
                                        <i class="bi bi-play-circle text-success me-3"></i>
                                        <span class="text-success">This event is currently active and running!</span>
                                    </div>
                                @elseif($event->isUpcoming())
                                    <div class="d-flex align-items-center p-3 bg-primary bg-opacity-10 rounded">
                                        <i class="bi bi-clock text-primary me-3"></i>
                                        <span class="text-primary">This event is coming up soon!</span>
                                    </div>
                                @elseif($event->isCompleted())
                                    <div class="d-flex align-items-center p-3 bg-info bg-opacity-10 rounded">
                                        <i class="bi bi-check-circle text-info me-3"></i>
                                        <span class="text-info">This event has been completed.</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="col-lg-4">
                    <!-- Quick Actions -->
                    <div class="card mb-4">
                        <div class="card-body">
                            <h3 class="h5 mb-3">
                                <i class="bi bi-tools me-2"></i>
                                Quick Actions
                            </h3>
                            <div class="d-grid gap-2">
                                <a href="{{ route('events.edit', $event) }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-pencil me-2"></i>
                                    Edit Event
                                </a>
                                
                                <form method="POST" action="{{ route('events.destroy', $event) }}" onsubmit="return confirm('Are you sure you want to delete this event? This action cannot be undone.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger w-100">
                                        <i class="bi bi-trash me-2"></i>
                                        Delete Event
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Event Statistics -->
                    <div class="card">
                        <div class="card-body">
                            <h3 class="h5 mb-3">
                                <i class="bi bi-graph-up me-2"></i>
                                Event Statistics
                            </h3>
                            <div class="d-flex flex-column gap-3">
                                <div class="d-flex justify-content-between">
                                    <span class="text-muted">Created</span>
                                    <span>{{ $event->created_at->format('M d, Y') }}</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span class="text-muted">Last Updated</span>
                                    <span>{{ $event->updated_at->format('M d, Y') }}</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span class="text-muted">Days Until Start</span>
                                    <span>
                                        @if($event->start_date->isFuture())
                                            {{ $event->start_date->diffInDays(now()) }} days
                                        @else
                                            Started
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
