<x-event-layout :event="$event">

    <div class="py-4">
        <div class="container-fluid">


            <!-- Quick Stats Dashboard -->
            <div class="row mb-4">
                <div class="col-md-6 col-lg-3 mb-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                                        <i class="bi bi-people text-primary fs-2"></i>
                                    </div>
                                </div>
                                <div class="ms-3">
                                    <div class="text-muted small fw-medium">Total Registrations</div>
                                    <div class="h3 fw-bold text-dark mb-0">{{ $event->bookings()->count() }}</div>
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
                                        <i class="bi bi-grid-3x3-gap text-success fs-2"></i>
                                    </div>
                                </div>
                                <div class="ms-3">
                                    <div class="text-muted small fw-medium">Exhibition Spaces</div>
                                    <div class="h3 fw-bold text-dark mb-0">{{ $event->floorplanDesign ? $event->floorplanDesign->items()->where('bookable', true)->count() : 0 }}</div>
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
                                    <div class="text-muted small fw-medium">Booked Booths</div>
                                    <div class="h3 fw-bold text-success mb-0">{{ $event->booked_booths_count }}</div>
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
                                        <i class="bi bi-clock text-warning fs-2"></i>
                                    </div>
                                </div>
                                <div class="ms-3">
                                    <div class="text-muted small fw-medium">Reserved Booths</div>
                                    <div class="h3 fw-bold text-warning mb-0">{{ $event->reserved_booths_count }}</div>
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
                                        <i class="bi bi-grid-3x3-gap text-info fs-2"></i>
                                    </div>
                                </div>
                                <div class="ms-3">
                                    <div class="text-muted small fw-medium">Available Booths</div>
                                    <div class="h3 fw-bold text-info mb-0">{{ $event->available_booths_count }}</div>
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
                                        <i class="bi bi-credit-card text-warning fs-2"></i>
                                    </div>
                                </div>
                                <div class="ms-3">
                                    <div class="text-muted small fw-medium">Revenue Collected</div>
                                    <div class="h3 fw-bold text-dark mb-0">
                                        ${{ number_format($event->total_revenue, 2) }}
                                    </div>
                                    <div class="text-muted small mt-1">
                                        {{ $event->payment_rate }}% payment rate
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Stats Row -->
            <div class="row mb-4">
                <div class="col-md-6 mb-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="bg-secondary bg-opacity-10 rounded-circle p-3">
                                        <i class="bi bi-building text-secondary fs-2"></i>
                                    </div>
                                </div>
                                <div class="ms-3">
                                    <div class="text-muted small fw-medium">Total Exhibition Spaces</div>
                                    <div class="h3 fw-bold text-dark mb-0">{{ $event->floorplanDesign ? $event->floorplanDesign->items()->where('bookable', true)->count() : 0 }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="bg-warning bg-opacity-10 rounded-circle p-3">
                                        <i class="bi bi-cash-stack text-warning fs-2"></i>
                                    </div>
                                </div>
                                <div class="ms-3">
                                    <div class="text-muted small fw-medium">Revenue Collected</div>
                                    <div class="h3 fw-bold text-dark mb-0">
                                        ${{ number_format($event->total_revenue, 2) }}
                                    </div>
                                    <div class="text-muted small mt-1">
                                        {{ $event->payment_rate }}% payment rate
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Dashboard Content -->
            <div class="row">
                <!-- Quick Actions -->
                <div class="col-lg-8 mb-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-transparent border-0">
                            <h3 class="h5 mb-0 fw-bold">
                                <i class="bi bi-lightning me-2 text-warning"></i>
                                Quick Actions
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <a href="{{ route('events.floorplan', $event) }}" class="card border-0 shadow-sm h-100 text-decoration-none">
                                        <div class="card-body text-center p-4">
                                            <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                                <i class="bi bi-grid-3x3-gap text-primary fs-2"></i>
                                            </div>
                                            <h5 class="card-title text-dark">Exhibition Layout</h5>
                                            <p class="card-text text-muted small">Design your exhibition space with booths, stages, and interactive areas.</p>
                                        </div>
                                    </a>
                                </div>

                                <div class="col-md-6">
                                    <a href="{{ route('events.form-builders.index', $event) }}" class="card border-0 shadow-sm h-100 text-decoration-none">
                                        <div class="card-body text-center p-4">
                                            <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                                <i class="bi bi-pencil-square text-success fs-2"></i>
                                            </div>
                                            <h5 class="card-title text-dark">Participant Forms</h5>
                                            <p class="card-text text-muted small">Create custom registration forms for exhibitors and visitors.</p>
                                        </div>
                                    </a>
                                </div>

                                <div class="col-md-6">
                                    <a href="{{ route('admin.payment-methods.index', ['event' => $event->id]) }}" class="card border-0 shadow-sm h-100 text-decoration-none">
                                        <div class="card-body text-center p-4">
                                            <div class="bg-warning bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                                <i class="bi bi-credit-card text-warning fs-2"></i>
                                            </div>
                                            <h5 class="card-title text-dark">Payment Setup</h5>
                                            <p class="card-text text-muted small">Configure payment methods like Paystack for your event bookings.</p>
                                        </div>
                                    </a>
                                </div>

                                <div class="col-md-6">
                                    <a href="{{ route('events.registration', $event) }}" class="card border-0 shadow-sm h-100 text-decoration-none">
                                        <div class="card-body text-center p-4">
                                            <div class="bg-info bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                                <i class="bi bi-people text-info fs-2"></i>
                                            </div>
                                            <h5 class="card-title text-dark">Exhibitors & Visitors</h5>
                                            <p class="card-text text-muted small">View and manage exhibitor registrations and visitor bookings.</p>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Event Details Sidebar -->
                <div class="col-lg-4 mb-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-transparent border-0">
                            <h3 class="h5 mb-0 fw-bold">
                                <i class="bi bi-info-circle me-2 text-primary"></i>
                                Event Details
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="d-flex flex-column gap-3">
                                <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded">
                                    <span class="text-muted">Event Status</span>
                                    <span class="badge 
                                        @if($event->status === 'active') bg-success
                                        @elseif($event->status === 'published') bg-primary
                                        @elseif($event->status === 'completed') bg-info
                                        @elseif($event->status === 'cancelled') bg-danger
                                        @else bg-secondary @endif">
                                        {{ ucfirst($event->status) }}
                                    </span>
                                </div>

                                <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded">
                                    <span class="text-muted">Start Date</span>
                                    <span class="fw-medium">{{ $event->start_date->format('M d, Y') }}</span>
                                </div>

                                <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded">
                                    <span class="text-muted">End Date</span>
                                    <span class="fw-medium">{{ $event->end_date->format('M d, Y') }}</span>
                                </div>

                                <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded">
                                    <span class="text-muted">Duration</span>
                                    <span class="fw-medium">{{ $event->duration_in_days }} days</span>
                                </div>

                                <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded">
                                    <span class="text-muted">Created</span>
                                    <span class="fw-medium">{{ $event->created_at->format('M d, Y') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-event-layout>