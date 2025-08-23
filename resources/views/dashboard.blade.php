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
                        <a href="{{ route('events.dashboard', $event) }}" class="list-group-item list-group-item-action">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <div class="bg-gradient-to-br from-blue-400 to-purple-600 rounded d-flex align-items-center justify-content-center" style="width: 64px; height: 64px;">
                                            @if($event->logo)
                                                <img src="{{ Storage::url($event->logo) }}" alt="{{ $event->name }}" class="rounded" style="width: 64px; height: 64px; object-fit: cover;">
                                            @else
                                                <i class="bi bi-calendar-event text-white fs-3"></i>
                                            @endif
                                        </div>
                                    </div>
                                    <div>
                                        <h5 class="mb-1">{{ $event->name }}</h5>
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
                                <i class="bi bi-lightning me-2"></i>
                                Quick Actions
                            </h3>
                            
                            <!-- Primary Actions -->
                            <div class="d-grid gap-2 mb-3">
                                <a href="{{ route('events.create') }}" class="btn btn-primary">
                                    <i class="bi bi-plus-circle me-2"></i>
                                    Create New Event
                                </a>
                            </div>
                            
                            <!-- Secondary Actions -->
                            <div class="d-grid gap-2">
                                <a href="{{ route('events.index') }}" class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-calendar-event me-2"></i>
                                    Manage Events
                                </a>
                                <a href="{{ route('admin.payment-methods.index') }}" class="btn btn-outline-secondary btn-sm">
                                    <i class="bi bi-credit-card me-2"></i>
                                    Payment Methods
                                </a>
                            </div>
                            
                            <!-- Quick Stats -->
                            <div class="mt-3 pt-3 border-top">
                                <h6 class="text-muted mb-2">
                                    <i class="bi bi-bar-chart me-2"></i>Quick Overview
                                </h6>
                                <div class="d-flex flex-column gap-2">
                                    <div class="d-flex justify-content-between align-items-center bg-light rounded p-2">
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-calendar-check text-primary me-2"></i>
                                            <small class="text-muted">Active Events</small>
                                        </div>
                                        <span class="badge bg-primary fs-6">{{ App\Models\Event::where('status', 'active')->count() }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center bg-light rounded p-2">
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-bookmark-star text-success me-2"></i>
                                            <small class="text-muted">Total Bookings</small>
                                        </div>
                                        <span class="badge bg-success fs-6">{{ App\Models\Booking::count() }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center bg-light rounded p-2">
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-credit-card text-info me-2"></i>
                                            <small class="text-muted">Payment Methods</small>
                                        </div>
                                        <span class="badge bg-info fs-6">{{ App\Models\PaymentMethod::count() }}</span>
                                    </div>
                                </div>
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
                                @php
                                    // Get recent activities
                                    $recentActivities = collect();
                                    
                                    // Recent events (last 7 days)
                                    $recentEvents = App\Models\Event::where('created_at', '>=', now()->subDays(7))
                                        ->latest()
                                        ->take(2)
                                        ->get()
                                        ->map(function($event) {
                                            return [
                                                'type' => 'event_created',
                                                'message' => "New event '{$event->name}' created",
                                                'time' => $event->created_at,
                                                'color' => 'primary',
                                                'icon' => 'bi-calendar-plus',
                                                'link' => route('events.dashboard', $event)
                                            ];
                                        });
                                    
                                    // Recent bookings (last 7 days)
                                    $recentBookings = App\Models\Booking::with(['event', 'floorplanItem'])
                                        ->where('created_at', '>=', now()->subDays(7))
                                        ->latest()
                                        ->take(2)
                                        ->get()
                                        ->map(function($booking) {
                                            return [
                                                'type' => 'booking_created',
                                                'message' => "New booking for '{$booking->event->name}'",
                                                'time' => $booking->created_at,
                                                'color' => 'success',
                                                'icon' => 'bi-bookmark-check',
                                                'link' => route('events.dashboard', $booking->event)
                                            ];
                                        });
                                    
                                    // Recent payments (last 7 days)
                                    $recentPayments = App\Models\Payment::with(['booking.event'])
                                        ->where('created_at', '>=', now()->subDays(7))
                                        ->where('status', 'completed')
                                        ->latest()
                                        ->take(2)
                                        ->get()
                                        ->map(function($payment) {
                                            return [
                                                'type' => 'payment_completed',
                                                'message' => "Payment received for '{$payment->booking->event->name}'",
                                                'time' => $payment->created_at,
                                                'color' => 'info',
                                                'icon' => 'bi-credit-card',
                                                'link' => route('events.dashboard', $payment->booking->event)
                                            ];
                                        });
                                    
                                    // Combine and sort all activities by time
                                    $recentActivities = $recentEvents
                                        ->concat($recentBookings)
                                        ->concat($recentPayments)
                                        ->sortByDesc('time')
                                        ->take(5);
                                @endphp
                                
                                @forelse($recentActivities as $activity)
                                    <div class="d-flex align-items-start">
                                        <div class="flex-shrink-0">
                                            <div class="bg-{{ $activity['color'] }} rounded-circle" style="width: 8px; height: 8px; margin-top: 8px;"></div>
                                        </div>
                                        <div class="ms-3 flex-grow-1">
                                            <a href="{{ $activity['link'] }}" class="text-decoration-none">
                                                <p class="mb-1 small text-dark">
                                                    <i class="bi {{ $activity['icon'] }} me-1"></i>
                                                    {{ $activity['message'] }}
                                                </p>
                                            </a>
                                            <small class="text-muted">
                                                @if($activity['time']->diffInHours(now()) < 1)
                                                    {{ $activity['time']->diffInMinutes(now()) }} minutes ago
                                                @elseif($activity['time']->diffInDays(now()) < 1)
                                                    {{ $activity['time']->diffInHours(now()) }} hours ago
                                                @else
                                                    {{ $activity['time']->diffInDays(now()) }} days ago
                                                @endif
                                            </small>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-3">
                                        <i class="bi bi-activity text-muted fs-4 d-block mb-2"></i>
                                        <p class="text-muted small mb-0">No recent activity</p>
                                        <small class="text-muted">Activities will appear here as they happen</small>
                                    </div>
                                @endforelse
                                
                                @if($recentActivities->count() > 0)
                                    <div class="text-center mt-2">
                                        <small class="text-muted">
                                            <i class="bi bi-clock me-1"></i>
                                            Showing last 7 days of activity
                                        </small>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
