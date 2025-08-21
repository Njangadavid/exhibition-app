<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 mb-0">
                <i class="bi bi-calendar-event me-2"></i>
                {{ __('All Events') }}
            </h2>
            <a href="{{ route('events.create') }}" class="btn btn-primary">
                <i class="bi bi-plus me-2"></i>
                Create Event
            </a>
        </div>
    </x-slot>

    <div class="py-4">
        <div class="container-fluid">
            <!-- Stats Summary -->
            <div class="row mb-4">
                <div class="col-md-6 col-lg-3 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <i class="bi bi-calendar-event text-primary fs-1"></i>
                                </div>
                                <div class="ms-3">
                                    <div class="text-muted small">Total Events</div>
                                    <div class="h3 fw-bold text-dark">{{ App\Models\Event::count() }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <i class="bi bi-play-circle text-success fs-1"></i>
                                </div>
                                <div class="ms-3">
                                    <div class="text-muted small">Active Events</div>
                                    <div class="h3 fw-bold text-dark">{{ App\Models\Event::active()->count() }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <i class="bi bi-clock text-info fs-1"></i>
                                </div>
                                <div class="ms-3">
                                    <div class="text-muted small">Upcoming Events</div>
                                    <div class="h3 fw-bold text-dark">{{ App\Models\Event::upcoming()->count() }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <i class="bi bi-check-circle text-warning fs-1"></i>
                                </div>
                                <div class="ms-3">
                                    <div class="text-muted small">Completed Events</div>
                                    <div class="h3 fw-bold text-dark">{{ App\Models\Event::completed()->count() }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Events Section Header -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h3 class="h5 mb-0">
                            <i class="bi bi-calendar-event me-2 text-primary"></i>
                            Your Events
                        </h3>
                        <div class="d-flex align-items-center">
                            <span class="text-muted small">
                                Showing {{ $events->firstItem() ?? 0 }}-{{ $events->lastItem() ?? 0 }} of {{ $events->total() }} events
                            </span>
                        </div>
                    </div>
                    
                    <!-- Quick Filters -->
                    <div class="d-flex flex-wrap align-items-center gap-2">
                        <span class="small fw-medium text-muted">Quick Filter:</span>
                        <a href="{{ route('events.index') }}" class="badge bg-primary text-decoration-none">
                            All Events
                        </a>
                        <a href="{{ route('events.index') }}?status=active" class="badge bg-secondary text-decoration-none">
                            Active
                        </a>
                        <a href="{{ route('events.index') }}?status=upcoming" class="badge bg-secondary text-decoration-none">
                            Upcoming
                        </a>
                        <a href="{{ route('events.index') }}?status=completed" class="badge bg-secondary text-decoration-none">
                            Completed
                        </a>
                    </div>
                </div>
            </div>

            <!-- Events List -->
            @if($events->count() > 0)
                <div class="card">
                    <div class="list-group list-group-flush">
                        @foreach($events as $event)
                            <a href="{{ route('events.dashboard', $event) }}" class="list-group-item list-group-item-action">
                                <div class="d-flex align-items-center">
                                    <!-- Event Logo/Image -->
                                    <div class="flex-shrink-0 me-3">
                                        <div class="position-relative">
                                            <div class="bg-gradient-to-br from-blue-400 to-purple-600 rounded d-flex align-items-center justify-content-center" style="width: 64px; height: 64px;">
                                                @if($event->logo)
                                                    <img src="{{ Storage::url($event->logo) }}" alt="{{ $event->title }}" class="rounded" style="width: 64px; height: 64px; object-fit: cover;">
                                                @else
                                                    <i class="bi bi-calendar-event text-white fs-4"></i>
                                                @endif
                                            </div>
                                            
                                            <!-- Status Badge -->
                                            <div class="position-absolute top-0 end-0 translate-middle">
                                                <span class="badge 
                                                    @if($event->status === 'active') bg-success
                                                    @elseif($event->status === 'published') bg-primary
                                                    @elseif($event->status === 'completed') bg-info
                                                    @elseif($event->status === 'cancelled') bg-danger
                                                    @else bg-secondary @endif">
                                                    {{ ucfirst($event->status) }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Event Content -->
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <h5 class="mb-1 fw-bold">{{ $event->title }}</h5>
                                            <div class="d-flex align-items-center ms-3">
                                                <span class="text-muted small me-2">
                                                    {{ $event->start_date->format('M d, Y') }}
                                                </span>
                                                <span class="text-muted me-2">•</span>
                                                <span class="text-muted small">
                                                    {{ $event->duration_in_days }} days
                                                </span>
                                            </div>
                                        </div>
                                        
                                        <p class="text-muted mb-3 small">
                                            {{ Str::limit($event->description, 150) }}
                                        </p>

                                        <!-- Event Meta Info -->
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="d-flex align-items-center gap-4 text-muted small">
                                                <div class="d-flex align-items-center">
                                                    <i class="bi bi-clock me-2 text-primary"></i>
                                                    <span>{{ $event->start_date->format('g:i A') }} - {{ $event->end_date->format('g:i A') }}</span>
                                                </div>
                                                <div class="d-flex align-items-center">
                                                    <i class="bi bi-calendar me-2 text-info"></i>
                                                    <span>{{ $event->end_date->format('M d, Y') }}</span>
                                                </div>
                                            </div>
                                            <div class="opacity-75">
                                                <span class="text-primary small fw-medium">Click to open dashboard →</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>

                <!-- Pagination -->
                @if($events->hasPages())
                    <div class="mt-4">
                        {{ $events->links() }}
                    </div>
                @endif
            @else
                <!-- Empty State -->
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="bi bi-calendar-x text-muted fs-1 d-block mb-3"></i>
                        <h3 class="h5 mb-2">No events found</h3>
                        <p class="text-muted mb-4">Get started by creating your first event.</p>
                        <a href="{{ route('events.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus me-2"></i>
                            Create Your First Event
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
