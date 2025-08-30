<x-event-layout :event="$event">
    <div class="py-4">
        <div class="container-fluid">
            <!-- Page Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="h3 mb-1">
                        <i class="bi bi-bar-chart me-2 text-primary"></i>
                        Bookings Report
                    </h2>
                    <p class="text-muted mb-0">Comprehensive overview of all booth bookings and exhibitor information</p>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-primary" onclick="exportToCSV()">
                        <i class="bi bi-download me-2"></i>Export CSV
                    </button>
                    <button class="btn btn-outline-success" onclick="window.print()">
                        <i class="bi bi-printer me-2"></i>Print Report
                    </button>
                </div>
            </div>

            <!-- Statistics Cards -->
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
                                    <div class="text-muted small fw-medium">Total Exhibitors</div>
                                    <div class="h3 fw-bold text-dark mb-0">{{ $stats['total_booth_owners'] }}</div>
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
                                    <div class="text-muted small fw-medium">Paid Bookings</div>
                                    <div class="h3 fw-bold text-dark mb-0">{{ $stats['paid_bookings'] }}</div>
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
                                    <div class="text-muted small fw-medium">Pending Payments</div>
                                    <div class="h3 fw-bold text-dark mb-0">{{ $stats['pending_payments'] }}</div>
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
                                         <i class="bi bi-currency-dollar text-info fs-2"></i>
                                     </div>
                                 </div>
                                 <div class="ms-3">
                                     <div class="text-muted small fw-medium">Paid Revenue</div>
                                     <div class="h3 fw-bold text-dark mb-0">${{ number_format($stats['total_revenue'], 2) }}</div>
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
                                         <i class="bi bi-calculator text-secondary fs-2"></i>
                                     </div>
                                 </div>
                                 <div class="ms-3">
                                     <div class="text-muted small fw-medium">Total Potential</div>
                                     <div class="h3 fw-bold text-dark mb-0">${{ number_format($stats['total_potential_revenue'] ?? 0, 2) }}</div>
                                 </div>
                             </div>
                         </div>
                     </div>
                 </div>
            </div>

            <!-- Additional Statistics Row -->
            <div class="row mb-4">
                <div class="col-md-6 col-lg-3 mb-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="bg-purple bg-opacity-10 rounded-circle p-3">
                                        <i class="bi bi-person-badge text-purple fs-2"></i>
                                    </div>
                                </div>
                                <div class="ms-3">
                                    <div class="text-muted small fw-medium">Total Booth Members</div>
                                    <div class="h3 fw-bold text-dark mb-0">{{ $boothOwners->sum(function($boothOwner) { return $boothOwner->boothMembers->count(); }) }}</div>
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
                                    <div class="bg-teal bg-opacity-10 rounded-circle p-3">
                                        <i class="bi bi-building text-teal fs-2"></i>
                                    </div>
                                </div>
                                <div class="ms-3">
                                    <div class="text-muted small fw-medium">Fully Occupied Booths</div>
                                    <div class="h3 fw-bold text-dark mb-0">{{ $boothOwners->filter(function($boothOwner) { 
                                        $floorplanItem = $boothOwner->booking->floorplanItem ?? null;
                                        return $floorplanItem && $boothOwner->boothMembers->count() >= ($floorplanItem->max_capacity ?? 5);
                                    })->count() }}</div>
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
                                    <div class="bg-orange bg-opacity-10 rounded-circle p-3">
                                        <i class="bi bi-people-fill text-orange fs-2"></i>
                                    </div>
                                </div>
                                <div class="ms-3">
                                    <div class="text-muted small fw-medium">Partially Filled</div>
                                    <div class="h3 fw-bold text-dark mb-0">{{ $boothOwners->filter(function($boothOwner) { 
                                        $floorplanItem = $boothOwner->booking->floorplanItem ?? null;
                                        return $floorplanItem && $boothOwner->boothMembers->count() > 0 && $boothOwner->boothMembers->count() < ($floorplanItem->max_capacity ?? 5);
                                    })->count() }}</div>
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
                                        <i class="bi bi-dash-circle text-secondary fs-2"></i>
                                    </div>
                                </div>
                                <div class="ms-3">
                                    <div class="text-muted small fw-medium">Empty Booths</div>
                                    <div class="h3 fw-bold text-dark mb-0">{{ $boothOwners->filter(function($boothOwner) { 
                                        return $boothOwner->boothMembers->count() === 0;
                                    })->count() }}</div>
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
                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="bi bi-search text-muted"></i>
                                </span>
                                <input type="text" class="form-control border-start-0" id="searchInput" placeholder="Search exhibitors...">
                            </div>
                        </div>
                                                 <div class="col-md-3">
                             <select class="form-select" id="statusFilter">
                                 <option value="">All Booking Statuses</option>
                                 <option value="reserved">Reserved</option>
                                 <option value="booked">Booked</option>
                                 <option value="cancelled">Cancelled</option>
                                 <option value="completed">Completed</option>
                             </select>
                         </div>
                                                 <div class="col-md-3">
                             <select class="form-select" id="spaceFilter">
                                 <option value="">All Booths</option>
                                 @foreach($boothOwners->pluck('booking.floorplanItem.label')->unique()->filter() as $label)
                                     <option value="{{ $label }}">{{ $label }}</option>
                                 @endforeach
                             </select>
                         </div>
                        <div class="col-md-2">
                            <select class="form-select" id="membersFilter">
                                <option value="">All Member Status</option>
                                <option value="full">Full</option>
                                <option value="partial">Partially Filled</option>
                                <option value="empty">Empty</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-primary w-100" onclick="applyFilters()">
                                <i class="bi bi-funnel me-2"></i>Filter
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bookings Table -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">
                        <i class="bi bi-table me-2"></i>
                        Exhibitor Bookings
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0" id="bookingsTable">
                            <thead class="table-light">
                                <tr>
                                    <th class="border-0 px-3 py-3">
                                        <i class="bi bi-person me-2"></i>Exhibitor
                                    </th>
                                    <th class="border-0 px-3 py-3">
                                        <i class="bi bi-building me-2"></i>Company
                                    </th>
                                    <th class="border-0 px-3 py-3">
                                        <i class="bi bi-envelope me-2"></i>Contact
                                    </th>
                                                                         <th class="border-0 px-3 py-3">
                                         <i class="bi bi-grid-3x3-gap me-2"></i>Booth
                                     </th>
                                    <th class="border-0 px-3 py-3">
                                        <i class="bi bi-currency-dollar me-2"></i>Price
                                    </th>
                                    <th class="border-0 px-3 py-3">
                                        <i class="bi bi-credit-card me-2"></i>Payment Status
                                    </th>
                                    <th class="border-0 px-3 py-3">
                                        <i class="bi bi-people me-2"></i>Booth Members
                                    </th>
                                    <th class="border-0 px-3 py-3">
                                        <i class="bi bi-gear me-2"></i>Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($boothOwners as $boothOwner)
                                    @php
                                        $booking = $boothOwner->booking;
                                        $floorplanItem = $booking->floorplanItem ?? null;
                                        $paymentStatus = $booking->payments->where('status', 'completed')->count() > 0 ? 'paid' : 
                                                       ($booking->payments->where('status', 'pending')->count() > 0 ? 'pending' : 'unpaid');
                                        $paymentStatusClass = $paymentStatus === 'paid' ? 'success' : 
                                                            ($paymentStatus === 'pending' ? 'warning' : 'secondary');
                                    @endphp
                                                                         <tr class="booking-row" 
                                         data-status="{{ $booking->status ?? 'reserved' }}"
                                         data-space="{{ $floorplanItem->label ?? '' }}"
                                         data-members="{{ $floorplanItem && $boothOwner->boothMembers->count() >= ($floorplanItem->max_capacity ?? 5) ? 'full' : ($boothOwner->boothMembers->count() > 0 ? 'partial' : 'empty') }}"
                                         data-search="{{ strtolower($boothOwner->form_responses['name'] ?? '') }} {{ strtolower($boothOwner->form_responses['company_name'] ?? '') }}">
                                        <td class="px-3 py-3">
                                            <div class="d-flex align-items-center">
                                                <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                                                    <i class="bi bi-person text-primary"></i>
                                                </div>
                                                <div>
                                                    <div class="fw-semibold">{{ $boothOwner->form_responses['name'] ?? 'N/A' }}</div>
                                                    <small class="text-muted">{{ $boothOwner->form_responses['email'] ?? 'N/A' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-3 py-3">
                                            <div class="fw-medium">{{ $boothOwner->form_responses['company_name'] ?? 'N/A' }}</div>
                                            <small class="text-muted">{{ $boothOwner->form_responses['country'] ?? 'N/A' }} - {{ $boothOwner->form_responses['company_address'] ?? 'N/A' }}</small>
                                        </td>
                                        <td class="px-3 py-3">
                                            <div class="fw-medium">{{ $boothOwner->form_responses['email'] ?? 'N/A' }}</div>
                                            <small class="text-muted">{{ $boothOwner->form_responses['phone'] ?? 'N/A' }}</small>
                                        </td>
                                                                                 <td class="px-3 py-3">
                                             @if($floorplanItem)
                                                 @php
                                                     $bookingStatus = $booking->status ?? 'reserved';
                                                     $statusColors = [
                                                         'reserved' => ['bg' => 'warning', 'icon' => 'clock'],
                                                         'booked' => ['bg' => 'success', 'icon' => 'check-circle'],
                                                         'cancelled' => ['bg' => 'danger', 'icon' => 'x-circle'],
                                                         'completed' => ['bg' => 'info', 'icon' => 'flag-checkered']
                                                     ];
                                                     $statusConfig = $statusColors[$bookingStatus] ?? $statusColors['reserved'];
                                                 @endphp
                                                 <div class="d-flex align-items-center gap-2 mb-1">
                                                     <span class="badge bg-info bg-opacity-10 text-info border border-info">
                                                         {{ $floorplanItem->label }}
                                                     </span>
                                                     <span class="badge bg-{{ $statusConfig['bg'] }} bg-opacity-10 text-{{ $statusConfig['bg'] }} border border-{{ $statusConfig['bg'] }}">
                                                         <i class="bi bi-{{ $statusConfig['icon'] }} me-1"></i>{{ ucfirst($bookingStatus) }}
                                                     </span>
                                                 </div>
                                                  <small class="text-muted">{{ $floorplanItem->effective_booth_width_meters }}m x {{ $floorplanItem->effective_booth_height_meters }}m</small>
                                             @else
                                                 <span class="text-muted">N/A</span>
                                             @endif
                                         </td>
                                        <td class="px-3 py-3">
                                            @if($floorplanItem)
                                                <div class="fw-bold text-success">${{ number_format($floorplanItem->price, 2) }}</div>
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td class="px-3 py-3">
                                            <span class="badge bg-{{ $paymentStatusClass }} bg-opacity-10 text-{{ $paymentStatusClass }} border border-{{ $paymentStatusClass }}">
                                                <i class="bi bi-{{ $paymentStatus === 'paid' ? 'check-circle' : ($paymentStatus === 'pending' ? 'clock' : 'x-circle') }} me-1"></i>
                                                {{ ucfirst($paymentStatus) }}
                                            </span>
                                        </td>
                                        <td class="px-3 py-3">
                                            @if($floorplanItem)
                                                @php
                                                    $memberCount = $boothOwner->boothMembers->count();
                                                    $maxCapacity = $floorplanItem->max_capacity ?? 5;
                                                    $capacityClass = $memberCount >= $maxCapacity ? 'success' : ($memberCount > 0 ? 'warning' : 'secondary');
                                                @endphp
                                                <div class="d-flex align-items-center">
                                                    <span class="badge bg-{{ $capacityClass }} bg-opacity-10 text-{{ $capacityClass }} border border-{{ $capacityClass }} me-2">
                                                        <i class="bi bi-people me-1"></i>
                                                        {{ $memberCount }}/{{ $maxCapacity }}
                                                    </span>
                                                </div>
                                                <small class="text-muted">
                                                    @if($memberCount >= $maxCapacity)
                                                        <i class="bi bi-check-circle text-success me-1"></i>Full
                                                    @elseif($memberCount > 0)
                                                        <i class="bi bi-clock text-warning me-1"></i>Partially filled
                                                    @else
                                                        <i class="bi bi-dash-circle text-muted me-1"></i>No members
                                                    @endif
                                                </small>
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                                                                 </td>
                                         <td class="px-3 py-3">
                                            <div class="btn-group btn-group-sm" role="group">
                                                                                                 <a href="{{ route('events.reports.booth-owner-details', ['event' => $event, 'boothOwner' => $boothOwner]) }}" 
                                                    class="btn btn-outline-primary" title="View Details">
                                                     <i class="bi bi-eye"></i>
                                                 </a>
                                                <button type="button" class="btn btn-outline-info" onclick="viewBooking({{ $booking->id }})" title="View Booking">
                                                    <i class="bi bi-receipt"></i>
                                                </button>
                                                <button type="button" class="btn btn-outline-success" onclick="sendEmail({{ $boothOwner->id }})" title="Send Email">
                                                    <i class="bi bi-envelope"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                                                         <tr>
                                         <td colspan="8" class="text-center py-5">
                                             <div class="text-muted">
                                                 <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                                                 <h5>No bookings found</h5>
                                                 <p>There are no exhibitor bookings for this event yet.</p>
                                             </div>
                                         </td>
                                     </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Details Modal -->
    <div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="detailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailsModalLabel">Exhibitor Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="detailsModalBody">
                    <!-- Content will be loaded here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Search and filter functionality
        function applyFilters() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const statusFilter = document.getElementById('statusFilter').value;
            const spaceFilter = document.getElementById('spaceFilter').value;
            const membersFilter = document.getElementById('membersFilter').value;
            
            const rows = document.querySelectorAll('.booking-row');
            
            rows.forEach(row => {
                const searchText = row.dataset.search;
                const status = row.dataset.status;
                const space = row.dataset.space;
                const members = row.dataset.members;
                
                const matchesSearch = searchText.includes(searchTerm);
                const matchesStatus = !statusFilter || status === statusFilter;
                const matchesSpace = !spaceFilter || space === spaceFilter;
                const matchesMembers = !membersFilter || members === membersFilter;
                
                if (matchesSearch && matchesStatus && matchesSpace && matchesMembers) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        // Export to CSV
        function exportToCSV() {
            const table = document.getElementById('bookingsTable');
            const rows = Array.from(table.querySelectorAll('tr'));
            
            let csv = [];
            
            // Add headers
            const headers = [];
            rows[0].querySelectorAll('th').forEach(th => {
                headers.push(th.textContent.trim());
            });
            csv.push(headers.join(','));
            
            // Add data rows
            rows.slice(1).forEach(row => {
                if (row.style.display !== 'none') { // Only export visible rows
                    const rowData = [];
                    row.querySelectorAll('td').forEach(td => {
                        rowData.push(`"${td.textContent.trim()}"`);
                    });
                    csv.push(rowData.join(','));
                }
            });
            
            const csvContent = csv.join('\n');
            const blob = new Blob([csvContent], { type: 'text/csv' });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'bookings-report-{{ $event->slug }}-{{ date("Y-m-d") }}.csv';
            a.click();
            window.URL.revokeObjectURL(url);
        }



        // View booking details
        function viewBooking(bookingId) {
            // This would typically redirect to a booking details page
            window.open(`/bookings/${bookingId}`, '_blank');
        }

        // Send email to exhibitor
        function sendEmail(boothOwnerId) {
            // This would typically open an email composition interface
            alert('Email functionality would be implemented here');
        }

        // Initialize search on input
        document.getElementById('searchInput').addEventListener('input', applyFilters);
        document.getElementById('statusFilter').addEventListener('change', applyFilters);
        document.getElementById('spaceFilter').addEventListener('change', applyFilters);
        document.getElementById('membersFilter').addEventListener('change', applyFilters);
    </script>
    @endpush

    @push('styles')
    <style>
        .table th {
            font-weight: 600;
            color: #495057;
        }
        
        .booking-row:hover {
            background-color: #f8f9fa;
        }
        
        .badge {
            font-size: 0.75rem;
        }
        
        .btn-group .btn {
            border-radius: 0.375rem !important;
        }
        
        .btn-group .btn:first-child {
            border-top-left-radius: 0.375rem !important;
            border-bottom-left-radius: 0.375rem !important;
        }
        
        .btn-group .btn:last-child {
            border-top-right-radius: 0.375rem !important;
            border-bottom-right-radius: 0.375rem !important;
        }
        
        @media print {
            .btn, .card-header {
                display: none !important;
            }
            
            .card {
                border: none !important;
                box-shadow: none !important;
            }
        }
    </style>
    @endpush
</x-event-layout>
