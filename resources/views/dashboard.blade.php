<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 mb-0">
                <i class="bi bi-speedometer2 me-2"></i>
                {{ __('Exhibition Dashboard') }}
        </h2>
            <a href="{{ route('events.create') }}" class="btn btn-primary">
                <i class="bi bi-plus me-2"></i>
                Create Event
            </a>
        </div>
    </x-slot>

    <div class="py-4">
        <div class="container-fluid">
            <!-- Stats Cards -->
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

            <!-- Recent Events -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="h5 mb-0">
                        <i class="bi bi-calendar-event me-2"></i>
                        Recent Events
                    </h3>
                    <a href="{{ route('events.index') }}" class="text-decoration-none">
                        View All Events
                    </a>
                </div>
                <div class="list-group list-group-flush">
                    @forelse(App\Models\Event::latest()->take(3)->get() as $event)
                        <a href="{{ route('events.show', $event) }}" class="list-group-item list-group-item-action">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <div class="bg-gradient-to-br from-blue-400 to-purple-600 rounded d-flex align-items-center justify-content-center" style="width: 64px; height: 64px;">
                                            @if($event->logo)
                                                <img src="{{ Storage::url($event->logo) }}" alt="{{ $event->title }}" class="rounded" style="width: 64px; height: 64px; object-fit: cover;">
                                            @else
                                                <i class="bi bi-calendar-event text-white fs-3"></i>
                                            @endif
                                        </div>
                                    </div>
                                    <div>
                                        <h5 class="mb-1">{{ $event->title }}</h5>
                                        <p class="mb-1 text-muted small">{{ Str::limit($event->description, 100) }}</p>
                                        <small class="text-muted">
                                            {{ $event->start_date->format('M d, Y') }} - {{ $event->end_date->format('M d, Y') }}
                                        </small>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <span class="badge 
                                        @if($event->status === 'active') bg-success
                                        @elseif($event->status === 'published') bg-primary
                                        @elseif($event->status === 'completed') bg-info
                                        @elseif($event->status === 'cancelled') bg-danger
                                        @else bg-secondary @endif">
                                        {{ ucfirst($event->status) }}
                                    </span>
                                    <p class="text-muted small mt-1 mb-0">{{ $event->duration_in_days }} days</p>
                                    <div class="mt-2 opacity-75">
                                        <small class="text-primary fw-medium">Click to view details →</small>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="list-group-item text-center py-4">
                            <i class="bi bi-calendar-x text-muted fs-1 d-block mb-2"></i>
                            <p class="text-muted mb-0">No events found. Create your first event to get started!</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Quick Actions and Recent Activity -->
            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h3 class="h5 mb-3">
                                <i class="bi bi-plus-circle me-2"></i>
                                Quick Actions
                            </h3>
                            <div class="d-grid gap-2">
                                <a href="{{ route('events.create') }}" class="btn btn-primary">
                                    <i class="bi bi-plus me-2"></i>
                                    Create New Event
                                </a>
                                <a href="{{ route('events.index') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-list me-2"></i>
                                    View All Events
                                </a>
                                <a href="{{ route('events.index') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-graph-up me-2"></i>
                                    Events Analytics
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h3 class="h5 mb-3">
                                <i class="bi bi-bell me-2"></i>
                                Recent Activity
                            </h3>
                            <div class="d-flex flex-column gap-3">
                                <div class="d-flex align-items-start">
                                    <div class="flex-shrink-0">
                                        <div class="bg-primary rounded-circle" style="width: 8px; height: 8px; margin-top: 8px;"></div>
                                    </div>
                                    <div class="ms-3">
                                        <p class="mb-1 small">Dashboard updated with real event data</p>
                                        <small class="text-muted">Just now</small>
                                    </div>
                                </div>
                                <div class="d-flex align-items-start">
                                    <div class="flex-shrink-0">
                                        <div class="bg-success rounded-circle" style="width: 8px; height: 8px; margin-top: 8px;"></div>
                                    </div>
                                    <div class="ms-3">
                                        <p class="mb-1 small">Event model and database created</p>
                                        <small class="text-muted">Recently</small>
                                    </div>
                                </div>
                                <div class="d-flex align-items-start">
                                    <div class="flex-shrink-0">
                                        <div class="bg-info rounded-circle" style="width: 8px; height: 8px; margin-top: 8px;"></div>
                                    </div>
                                    <div class="ms-3">
                                        <p class="mb-1 small">Sample events seeded successfully</p>
                                        <small class="text-muted">Recently</small>
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
