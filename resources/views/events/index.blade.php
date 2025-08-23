<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 mb-0">
                <i class="bi bi-calendar-event me-2"></i>
                {{ __('Event Management') }}
            </h2>
            <a href="{{ route('events.create') }}" class="btn btn-primary">
                <i class="bi bi-plus me-2"></i>
                Create New Event
            </a>
        </div>
    </x-slot>

    <div class="py-4">
        <div class="container-fluid">
            <!-- Enhanced Stats Dashboard -->
            <div class="row mb-4">
                <div class="col-md-6 col-lg-3 mb-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                                        <i class="bi bi-calendar-event text-primary fs-2"></i>
                                    </div>
                                </div>
                                <div class="ms-3">
                                    <div class="text-muted small fw-medium">Total Events</div>
                                    <div class="h3 fw-bold text-dark mb-0">{{ App\Models\Event::count() }}</div>
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
                                        <i class="bi bi-play-circle text-success fs-2"></i>
                                    </div>
                                </div>
                                <div class="ms-3">
                                    <div class="text-muted small fw-medium">Active Now</div>
                                    <div class="h3 fw-bold text-dark mb-0">{{ App\Models\Event::active()->count() }}</div>
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
                                    <div class="bg-info bg-opacity-10 rounded-circle p-3">
                                        <i class="bi bi-clock text-info fs-2"></i>
                                    </div>
                                </div>
                                <div class="ms-3">
                                    <div class="text-muted small fw-medium">Upcoming</div>
                                    <div class="h3 fw-bold text-dark mb-0">{{ App\Models\Event::upcoming()->count() }}</div>
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
                                        <i class="bi bi-check-circle text-warning fs-2"></i>
                                    </div>
                                </div>
                                <div class="ms-3">
                                    <div class="text-muted small fw-medium">Completed</div>
                                    <div class="h3 fw-bold text-dark mb-0">{{ App\Models\Event::completed()->count() }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Search and Filter Section -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <div class="row g-3">
                        <!-- Search Bar -->
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="bi bi-search text-muted"></i>
                                </span>
                                <input type="text" class="form-control border-start-0" id="searchEvents" placeholder="Search events by name, description...">
                            </div>
                        </div>
                        
                        <!-- Status Filter -->
                        <div class="col-md-3">
                            <select class="form-select" id="statusFilter">
                                <option value="">All Statuses</option>
                                <option value="draft">Draft</option>
                                <option value="published">Published</option>
                                <option value="active">Active</option>
                                <option value="completed">Completed</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>
                        
                        <!-- Date Filter -->
                        <div class="col-md-3">
                            <select class="form-select" id="dateFilter">
                                <option value="">All Dates</option>
                                <option value="today">Today</option>
                                <option value="week">This Week</option>
                                <option value="month">This Month</option>
                                <option value="future">Future Events</option>
                                <option value="past">Past Events</option>
                            </select>
                        </div>
                    </div>
                    
                    <!-- Quick Action Filters -->
                    <div class="mt-3 pt-3 border-top">
                        <div class="d-flex flex-wrap align-items-center gap-2">
                            <span class="small fw-medium text-muted">Quick Access:</span>
                            <a href="{{ route('events.index') }}" class="badge bg-primary text-decoration-none px-3 py-2">
                                <i class="bi bi-grid me-1"></i>All Events
                            </a>
                            <a href="{{ route('events.index') }}?status=active" class="badge bg-success text-decoration-none px-3 py-2">
                                <i class="bi bi-play-circle me-1"></i>Active
                            </a>
                            <a href="{{ route('events.index') }}?status=upcoming" class="badge bg-info text-decoration-none px-3 py-2">
                                <i class="bi bi-clock me-1"></i>Upcoming
                            </a>
                            <a href="{{ route('events.index') }}?status=completed" class="badge bg-warning text-decoration-none px-3 py-2">
                                <i class="bi bi-check-circle me-1"></i>Completed
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Events Grid -->
            @if($events->count() > 0)
                <div class="row g-4">
                    @foreach($events as $event)
                        <div class="col-lg-6 col-xl-4 event-card" 
                             data-status="{{ $event->status }}"
                             data-title="{{ strtolower($event->name) }}"
                             data-description="{{ strtolower($event->description) }}"
                             data-date="{{ $event->start_date->format('Y-m-d') }}">
                            <div class="card border-0 shadow-sm h-100 event-item">
                                <!-- Event Header with Status -->
                                <div class="card-header bg-transparent border-0 pb-0">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-gradient-to-br from-blue-400 to-purple-600 rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                                                @if($event->logo)
                                                    <img src="{{ Storage::url($event->logo) }}" alt="{{ $event->name }}" class="rounded-circle" style="width: 48px; height: 48px; object-fit: cover;">
                                                @else
                                                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                                                        <i class="bi bi-calendar-event text-white"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div>
                                                <span class="badge 
                                                    @if($event->status === 'active') bg-success
                                                    @elseif($event->status === 'published') bg-primary
                                                    @elseif($event->status === 'completed') bg-info
                                                    @elseif($event->status === 'cancelled') bg-danger
                                                    @else bg-secondary @endif px-3 py-2">
                                                    <i class="bi 
                                                        @if($event->status === 'active') bi-play-circle
                                                        @elseif($event->status === 'published') bi-globe
                                                        @elseif($event->status === 'completed') bi-check-circle
                                                        @elseif($event->status === 'cancelled') bi-x-circle
                                                        @else bi-dash-circle @endif me-1"></i>
                                                    {{ ucfirst($event->status) }}
                                                </span>
                                            </div>
                                        </div>
                                        
                                        <!-- Event Menu -->
                                        <div class="dropdown">
                                            <button class="btn btn-link text-muted p-0" type="button" data-bs-toggle="dropdown">
                                                <i class="bi bi-three-dots-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li><a class="dropdown-item" href="{{ route('events.show', $event) }}">
                                                    <i class="bi bi-eye me-2"></i>View Details
                                                </a></li>
                                                <li><a class="dropdown-item" href="{{ route('events.edit', $event) }}">
                                                    <i class="bi bi-pencil me-2"></i>Edit Event
                                                </a></li>
                                                <li><a class="dropdown-item" href="{{ route('events.dashboard', $event) }}">
                                                    <i class="bi bi-speedometer2 me-2"></i>Dashboard
                                                </a></li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li><a class="dropdown-item" href="{{ route('events.public.show', $event) }}" target="_blank">
                                                    <i class="bi bi-globe me-2"></i>Public Page
                                                </a></li>
                                                <li><a class="dropdown-item" href="{{ route('events.public.floorplan', $event) }}" target="_blank">
                                                    <i class="bi bi-map me-2"></i>Floorplan
                                                </a></li>
                                                <li><a class="dropdown-item" href="{{ route('admin.payment-methods.index', ['event' => $event->id]) }}">
                                                    <i class="bi bi-credit-card me-2"></i>Payment Methods
                                                </a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <!-- Event Content -->
                                <div class="card-body pt-2">
                                    <h5 class="card-title fw-bold mb-2 text-truncate" title="{{ $event->name }}">
                                        <a href="{{ route('events.dashboard', $event) }}" class="text-decoration-none text-dark hover-text-primary" style="transition: color 0.2s ease;">
                                            {{ $event->name }}
                                        </a>
                                    </h5>
                                    
                                    <p class="text-muted small mb-3 line-clamp-2" title="{{ $event->description }}">
                                        {{ Str::limit($event->description, 120) }}
                                    </p>

                                    <!-- Event Timeline -->
                                    <div class="mb-3">
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="bi bi-calendar-event text-primary me-2"></i>
                                            <span class="small fw-medium">{{ $event->start_date->format('M d, Y') }}</span>
                                            <span class="text-muted mx-2">•</span>
                                            <span class="text-muted small">{{ $event->duration_in_days }} day{{ $event->duration_in_days > 1 ? 's' : '' }}</span>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-clock text-info me-2"></i>
                                            <span class="small">{{ $event->start_date->format('g:i A') }} - {{ $event->end_date->format('g:i A') }}</span>
                                        </div>
                                    </div>

                                    <!-- Progress Indicator for Active Events -->
                                    @if($event->status === 'active')
                                        @php
                                            $totalDays = $event->start_date->diffInDays($event->end_date) + 1;
                                            $elapsedDays = $event->start_date->diffInDays(now()) + 1;
                                            $progress = min(100, max(0, ($elapsedDays / $totalDays) * 100));
                                        @endphp
                                        <div class="mb-3">
                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                <span class="small text-muted">Event Progress</span>
                                                <span class="small fw-medium">{{ round($progress) }}%</span>
                                            </div>
                                            <div class="progress" style="height: 6px;">
                                                <div class="progress-bar bg-success" style="width: {{ $progress }}%"></div>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <!-- Action Buttons -->
                                <div class="card-footer bg-transparent border-0 pt-0">
                                    <div class="d-grid gap-2">
                                        <a href="{{ route('events.dashboard', $event) }}" class="btn btn-primary btn-sm">
                                            <i class="bi bi-speedometer2 me-2"></i>Manage Event
                                        </a>
                                        <div class="d-flex gap-1">
                                            <a href="{{ route('events.public.show', $event) }}" class="btn btn-outline-success btn-sm flex-fill" target="_blank" title="View Public Page">
                                                <i class="bi bi-globe"></i>
                                            </a>
                                            <a href="{{ route('events.public.floorplan', $event) }}" class="btn btn-outline-info btn-sm flex-fill" target="_blank" title="View Floorplan">
                                                <i class="bi bi-map"></i>
                                            </a>
                                            <a href="{{ route('events.edit', $event) }}" class="btn btn-outline-secondary btn-sm flex-fill" title="Edit Event">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Enhanced Pagination -->
                @if($events->hasPages())
                    <div class="mt-5">
                        <nav aria-label="Events pagination">
                            {{ $events->links() }}
                        </nav>
                    </div>
                @endif
            @else
                <!-- Enhanced Empty State -->
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-5">
                        <div class="mb-4">
                            <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 120px; height: 120px;">
                                <i class="bi bi-calendar-x text-muted" style="font-size: 3rem;"></i>
                            </div>
                        </div>
                        <h3 class="h4 mb-3">No events found</h3>
                        <p class="text-muted mb-4 fs-6">Get started by creating your first event to manage exhibitions, conferences, or any gathering.</p>
                        <div class="d-flex justify-content-center gap-3">
                            <a href="{{ route('events.create') }}" class="btn btn-primary btn-lg">
                                <i class="bi bi-plus-circle me-2"></i>
                                Create Your First Event
                            </a>
                            <button class="btn btn-outline-secondary btn-lg" onclick="document.getElementById('searchEvents').focus()">
                                <i class="bi bi-search me-2"></i>
                                Search Events
                            </button>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Custom Styles -->
    <style>
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        .event-card {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        
        .event-card:hover {
            transform: translateY(-2px);
        }
        
        .event-item {
            transition: all 0.2s ease;
        }
        
        .event-item:hover {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
        }
        
        .progress {
            background-color: #e9ecef;
        }
        
        .progress-bar {
            transition: width 0.6s ease;
        }
        
        .badge {
            font-size: 0.75rem;
        }
        
        .card-header {
            background-color: transparent;
        }
        
        .dropdown-toggle::after {
            display: none;
        }
        
        .btn-sm {
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;
        }
        
        .flex-fill {
            flex: 1 1 auto;
        }
    </style>

    <!-- Search and Filter JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchEvents');
            const statusFilter = document.getElementById('statusFilter');
            const dateFilter = document.getElementById('dateFilter');
            const eventCards = document.querySelectorAll('.event-card');

            function filterEvents() {
                const searchTerm = searchInput.value.toLowerCase();
                const statusValue = statusFilter.value;
                const dateValue = dateFilter.value;

                eventCards.forEach(card => {
                    let showCard = true;

                    // Search filter
                    if (searchTerm) {
                        const title = card.dataset.title;
                        const description = card.dataset.description;
                        if (!title.includes(searchTerm) && !description.includes(searchTerm)) {
                            showCard = false;
                        }
                    }

                    // Status filter
                    if (statusValue && card.dataset.status !== statusValue) {
                        showCard = false;
                    }

                    // Date filter
                    if (dateValue) {
                        const eventDate = new Date(card.dataset.date);
                        const today = new Date();
                        today.setHours(0, 0, 0, 0);

                        switch(dateValue) {
                            case 'today':
                                if (eventDate.getTime() !== today.getTime()) showCard = false;
                                break;
                            case 'week':
                                const weekAgo = new Date(today.getTime() - 7 * 24 * 60 * 60 * 1000);
                                if (eventDate < weekAgo || eventDate > today) showCard = false;
                                break;
                            case 'month':
                                const monthAgo = new Date(today.getFullYear(), today.getMonth(), 1);
                                if (eventDate < monthAgo || eventDate > today) showCard = false;
                                break;
                            case 'future':
                                if (eventDate <= today) showCard = false;
                                break;
                            case 'past':
                                if (eventDate >= today) showCard = false;
                                break;
                        }
                    }

                    // Show/hide card
                    card.style.display = showCard ? 'block' : 'none';
                });

                // Update empty state
                updateEmptyState();
            }

            function updateEmptyState() {
                const visibleCards = document.querySelectorAll('.event-card[style="display: block"], .event-card:not([style*="display"])');
                const emptyState = document.querySelector('.card .card-body.text-center');
                
                if (visibleCards.length === 0 && emptyState) {
                    emptyState.style.display = 'block';
                } else if (visibleCards.length > 0 && emptyState) {
                    emptyState.style.display = 'none';
                }
            }

            // Event listeners
            searchInput.addEventListener('input', filterEvents);
            statusFilter.addEventListener('change', filterEvents);
            dateFilter.addEventListener('change', filterEvents);

            // Initialize filters from URL params
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('status')) {
                statusFilter.value = urlParams.get('status');
                filterEvents();
            }
        });
    </script>
</x-app-layout>
