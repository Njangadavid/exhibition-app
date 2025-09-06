<?php if (isset($component)) { $__componentOriginalb1882f8c14f0a5270b201bcf650aaac1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb1882f8c14f0a5270b201bcf650aaac1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.event-layout','data' => ['event' => $event]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('event-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['event' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($event)]); ?>
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
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-download me-2"></i>Export
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#" onclick="exportToExcel()">
                                <i class="bi bi-file-earmark-excel me-2"></i>Export to Excel
                            </a></li>
                            <li><a class="dropdown-item" href="#" onclick="exportToPDF()">
                                <i class="bi bi-file-earmark-pdf me-2"></i>Export to PDF
                            </a></li>
                        </ul>
                    </div>
                    <button class="btn btn-outline-success" onclick="window.print()">
                        <i class="bi bi-printer me-2"></i>Print Report
                    </button>
                </div>
            </div>

            <!-- Statistics Overview -->
            <div class="row mb-4">
                <!-- Primary Metrics -->
                <div class="col-lg-8">
                    <div class="row g-3">
                        <div class="col-md-6 col-lg-3">
                            <div class="stat-card stat-card-primary">
                                <div class="stat-icon">
                                    <i class="bi bi-people"></i>
                                </div>
                                <div class="stat-content">
                                    <div class="stat-label">Total Exhibitors</div>
                                    <div class="stat-value"><?php echo e($stats['total_booth_owners']); ?></div>
                                    <div class="stat-subtitle">Registered companies</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <div class="stat-card stat-card-success">
                                <div class="stat-icon">
                                    <i class="bi bi-check-circle"></i>
                                </div>
                                <div class="stat-content">
                                    <div class="stat-label">Paid Bookings</div>
                                    <div class="stat-value"><?php echo e($stats['paid_bookings']); ?></div>
                                    <div class="stat-subtitle"><?php echo e($stats['total_booth_owners'] > 0 ? round(($stats['paid_bookings'] / $stats['total_booth_owners']) * 100, 1) : 0); ?>% of total</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <div class="stat-card stat-card-warning">
                                <div class="stat-icon">
                                    <i class="bi bi-clock"></i>
                                </div>
                                <div class="stat-content">
                                    <div class="stat-label">Pending Payments</div>
                                    <div class="stat-value"><?php echo e($stats['pending_payments']); ?></div>
                                    <div class="stat-subtitle">Awaiting payment</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <div class="stat-card stat-card-info">
                                <div class="stat-icon">
                                    <i class="bi bi-currency-dollar"></i>
                                </div>
                                <div class="stat-content">
                                    <div class="stat-label">Paid Revenue</div>
                                    <div class="stat-value">$<?php echo e(number_format($stats['total_revenue'], 0)); ?></div>
                                    <div class="stat-subtitle">Confirmed income</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Revenue Summary -->
                <div class="col-lg-4">
                    <div class="revenue-summary-card">
                        <div class="card-header">
                            <h6 class="mb-0"><i class="bi bi-graph-up me-2"></i>Revenue Summary</h6>
                        </div>
                        <div class="card-body">
                            <div class="revenue-item">
                                <div class="revenue-label">Paid Revenue</div>
                                <div class="revenue-value text-success">$<?php echo e(number_format($stats['total_revenue'], 2)); ?></div>
                            </div>
                            <div class="revenue-item">
                                <div class="revenue-label">Potential Revenue</div>
                                <div class="revenue-value text-muted">$<?php echo e(number_format($stats['total_potential_revenue'] ?? 0, 2)); ?></div>
                            </div>
                            <div class="revenue-divider"></div>
                            <div class="revenue-item">
                                <div class="revenue-label">Collection Rate</div>
                                <div class="revenue-value text-primary">
                                    <?php echo e($stats['total_potential_revenue'] > 0 ? round(($stats['total_revenue'] / $stats['total_potential_revenue']) * 100, 1) : 0); ?>%
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Secondary Metrics -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="stat-card stat-card-secondary stat-card-inline">
                        <div class="stat-content-inline">
                            <div class="stat-icon-inline">
                                <i class="bi bi-person-badge"></i>
                            </div>
                            <div class="stat-info-inline">
                                <div class="stat-label-inline">Total Booth Members</div>
                                <div class="stat-value-inline"><?php echo e($boothOwners->sum(function($boothOwner) { return $boothOwner->boothMembers->count(); })); ?></div>
                                <div class="stat-subtitle-inline">All team members</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card stat-card-success stat-card-inline">
                        <div class="stat-content-inline">
                            <div class="stat-icon-inline">
                                <i class="bi bi-building"></i>
                            </div>
                            <div class="stat-info-inline">
                                <div class="stat-label-inline">Fully Occupied</div>
                                <div class="stat-value-inline"><?php echo e($boothOwners->filter(function($boothOwner) { 
                                    $floorplanItem = $boothOwner->booking->floorplanItem ?? null;
                                    return $floorplanItem && $boothOwner->boothMembers->count() >= ($floorplanItem->max_capacity ?? 5);
                                })->count()); ?></div>
                                <div class="stat-subtitle-inline">Booths at capacity</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card stat-card-warning stat-card-inline">
                        <div class="stat-content-inline">
                            <div class="stat-icon-inline">
                                <i class="bi bi-people-fill"></i>
                            </div>
                            <div class="stat-info-inline">
                                <div class="stat-label-inline">Partially Filled</div>
                                <div class="stat-value-inline"><?php echo e($boothOwners->filter(function($boothOwner) { 
                                    $floorplanItem = $boothOwner->booking->floorplanItem ?? null;
                                    return $floorplanItem && $boothOwner->boothMembers->count() > 0 && $boothOwner->boothMembers->count() < ($floorplanItem->max_capacity ?? 5);
                                })->count()); ?></div>
                                <div class="stat-subtitle-inline">Booths with space</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Enhanced Search and Filter Section -->
            <div class="filter-section mb-4">
                <div class="row g-3">
                    <div class="col-lg-4">
                        <div class="search-box">
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-search"></i>
                                </span>
                                <input type="text" class="form-control" id="searchInput" placeholder="Search by name, company, or email...">
                                <button class="btn btn-outline-secondary" type="button" onclick="clearSearch()">
                                    <i class="bi bi-x"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <div class="filter-controls">
                            <div class="row g-2">
                                <div class="col-md-3">
                                    <select class="form-select form-select-sm" id="statusFilter">
                                        <option value="">All Statuses</option>
                                        <option value="reserved">Reserved</option>
                                        <option value="booked">Booked</option>
                                        <option value="cancelled">Cancelled</option>
                                        <option value="completed">Completed</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <select class="form-select form-select-sm" id="spaceFilter">
                                        <option value="">All Booths</option>
                                        <?php $__currentLoopData = $boothOwners->pluck('booking.floorplanItem.label')->unique()->filter(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($label); ?>"><?php echo e($label); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <select class="form-select form-select-sm" id="membersFilter">
                                        <option value="">All Capacity</option>
                                        <option value="full">Full</option>
                                        <option value="partial">Partially Filled</option>
                                        <option value="empty">Empty</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <select class="form-select form-select-sm" id="paymentFilter">
                                        <option value="">All Payments</option>
                                        <option value="paid">Paid</option>
                                        <option value="pending">Pending</option>
                                        <option value="unpaid">Unpaid</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Active Filters Display -->
                <div class="active-filters mt-3" id="activeFilters" style="display: none;">
                    <div class="d-flex align-items-center gap-2">
                        <span class="text-muted small">Active filters:</span>
                        <div class="filter-tags" id="filterTags"></div>
                        <button class="btn btn-sm btn-outline-secondary" onclick="clearAllFilters()">
                            <i class="bi bi-x-circle me-1"></i>Clear All
                        </button>
                    </div>
                </div>
            </div>

            <!-- Enhanced Bookings Table -->
            <div class="table-container">
                <div class="table-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-1">
                                <i class="bi bi-table me-2"></i>
                                Exhibitor Bookings
                            </h5>
                            <p class="text-muted mb-0 small">
                                <span id="visibleCount"><?php echo e($boothOwners->count()); ?></span> of <?php echo e($boothOwners->count()); ?> exhibitors shown
                            </p>
                        </div>
                        <div class="table-actions">
                            <div class="btn-group btn-group-sm" role="group">
                                <button type="button" class="btn btn-outline-secondary" onclick="toggleView('table')" id="tableViewBtn">
                                    <i class="bi bi-table"></i>
                                </button>
                                <button type="button" class="btn btn-outline-secondary" onclick="toggleView('cards')" id="cardsViewBtn">
                                    <i class="bi bi-grid-3x3-gap"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Table View -->
                <div class="table-view" id="tableView">
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
                                        <i class="bi bi-tag me-2"></i>Booth Name
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
                                <?php $__empty_1 = true; $__currentLoopData = $boothOwners; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $boothOwner): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <?php
                                $booking = $boothOwner->booking;
                                $floorplanItem = $booking->floorplanItem ?? null;
                                $paymentStatus = $booking->payments->where('status', 'completed')->count() > 0 ? 'paid' :
                                ($booking->payments->where('status', 'pending')->count() > 0 ? 'pending' : 'unpaid');
                                $paymentStatusClass = $paymentStatus === 'paid' ? 'success' :
                                ($paymentStatus === 'pending' ? 'warning' : 'secondary');
                                ?>
                                <tr class="booking-row"
                                    data-booking-id="<?php echo e($booking->id); ?>"
                                    data-status="<?php echo e($booking->status ?? 'reserved'); ?>"
                                    data-space="<?php echo e($floorplanItem->label ?? ''); ?>"
                                    data-members="<?php echo e($floorplanItem && $boothOwner->boothMembers->count() >= ($floorplanItem->max_capacity ?? 5) ? 'full' : ($boothOwner->boothMembers->count() > 0 ? 'partial' : 'empty')); ?>"
                                    data-payment="<?php echo e($paymentStatus); ?>"
                                    data-search="<?php echo e(strtolower($boothOwner->form_responses['name'] ?? '')); ?> <?php echo e(strtolower($boothOwner->form_responses['company_name'] ?? '')); ?> <?php echo e(strtolower($boothOwner->form_responses['email'] ?? '')); ?>">
                                    <td class="px-3 py-3">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                                                <i class="bi bi-person text-primary"></i>
                                            </div>
                                            <div>
                                                <div class="fw-semibold"><?php echo e($boothOwner->form_responses['name'] ?? 'N/A'); ?></div>
                                                <small class="text-muted"><?php echo e($boothOwner->form_responses['email'] ?? 'N/A'); ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-3 py-3">
                                        <div class="fw-medium"><?php echo e($boothOwner->form_responses['company_name'] ?? 'N/A'); ?></div>
                                        <small class="text-muted"><?php echo e($boothOwner->form_responses['country'] ?? 'N/A'); ?> - <?php echo e($boothOwner->form_responses['company_address'] ?? 'N/A'); ?></small>
                                    </td>
                                    <td class="px-3 py-3">
                                        <div class="fw-medium"><?php echo e($boothOwner->form_responses['email'] ?? 'N/A'); ?></div>
                                        <small class="text-muted"><?php echo e($boothOwner->form_responses['phone'] ?? 'N/A'); ?></small>
                                    </td>
                                    <td class="px-3 py-3">
                                        <?php if($floorplanItem): ?>
                                        <?php
                                        $bookingStatus = $booking->status ?? 'reserved';
                                        $statusColors = [
                                        'reserved' => ['bg' => 'warning', 'icon' => 'clock'],
                                        'booked' => ['bg' => 'success', 'icon' => 'check-circle'],
                                        'cancelled' => ['bg' => 'danger', 'icon' => 'x-circle'],
                                        'completed' => ['bg' => 'info', 'icon' => 'flag-checkered']
                                        ];
                                        $statusConfig = $statusColors[$bookingStatus] ?? $statusColors['reserved'];
                                        ?>
                                        <div class="d-flex align-items-center gap-2 mb-1">
                                            <span class="badge bg-info bg-opacity-10 text-info border border-info">
                                                <?php echo e($floorplanItem->label); ?>

                                            </span>
                                            <span class="badge bg-<?php echo e($statusConfig['bg']); ?> bg-opacity-10 text-<?php echo e($statusConfig['bg']); ?> border border-<?php echo e($statusConfig['bg']); ?>">
                                                <i class="bi bi-<?php echo e($statusConfig['icon']); ?> me-1"></i><?php echo e(ucfirst($bookingStatus)); ?>

                                            </span>
                                        </div>
                                        <small class="text-muted"><?php echo e($floorplanItem->effective_booth_width_meters); ?>m x <?php echo e($floorplanItem->effective_booth_height_meters); ?>m</small>
                                        <?php else: ?>
                                        <span class="text-muted">N/A</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-3 py-3">
                                        <div class="fw-medium"><?php echo e($boothOwner->form_responses['booth_name'] ?? 'N/A'); ?></div>
                                    </td>
                                    <td class="px-3 py-3">
                                        <?php if($floorplanItem): ?>
                                        <div class="fw-bold text-success">$<?php echo e(number_format($floorplanItem->price, 2)); ?></div>
                                        <?php else: ?>
                                        <span class="text-muted">N/A</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-3 py-3">
                                        <span class="badge bg-<?php echo e($paymentStatusClass); ?> bg-opacity-10 text-<?php echo e($paymentStatusClass); ?> border border-<?php echo e($paymentStatusClass); ?>">
                                            <i class="bi bi-<?php echo e($paymentStatus === 'paid' ? 'check-circle' : ($paymentStatus === 'pending' ? 'clock' : 'x-circle')); ?> me-1"></i>
                                            <?php echo e(ucfirst($paymentStatus)); ?>

                                        </span>
                                    </td>
                                    <td class="px-3 py-3">
                                        <?php if($floorplanItem): ?>
                                        <?php
                                        $memberCount = $boothOwner->boothMembers->count();
                                        $maxCapacity = $floorplanItem->max_capacity ?? 5;
                                        $capacityClass = $memberCount >= $maxCapacity ? 'success' : ($memberCount > 0 ? 'warning' : 'secondary');
                                        ?>
                                        <div class="d-flex align-items-center">
                                            <span class="badge bg-<?php echo e($capacityClass); ?> bg-opacity-10 text-<?php echo e($capacityClass); ?> border border-<?php echo e($capacityClass); ?> me-2">
                                                <i class="bi bi-people me-1"></i>
                                                <?php echo e($memberCount); ?>/<?php echo e($maxCapacity); ?>

                                            </span>
                                        </div>
                                        <small class="text-muted">
                                            <?php if($memberCount >= $maxCapacity): ?>
                                            <i class="bi bi-check-circle text-success me-1"></i>Full
                                            <?php elseif($memberCount > 0): ?>
                                            <i class="bi bi-clock text-warning me-1"></i>Partially filled
                                            <?php else: ?>
                                            <i class="bi bi-dash-circle text-muted me-1"></i>No members
                                            <?php endif; ?>
                                        </small>
                                        <?php else: ?>
                                        <span class="text-muted">N/A</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-3 py-3">
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="<?php echo e(route('events.reports.booth-owner-details', ['event' => $event, 'boothOwner' => $boothOwner])); ?>"
                                                class="btn btn-outline-primary" title="View Details">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <button type="button" class="btn btn-outline-info" onclick="viewBooking(<?php echo e($booking->id); ?>, '<?php echo e($event->slug); ?>', '<?php echo e($booking->access_token); ?>')" title="View Booking">
                                                <i class="bi bi-receipt"></i>
                                            </button>
                                            <button type="button" class="btn btn-outline-danger" onclick="confirmDelete(<?php echo e($booking->id); ?>, '<?php echo e($boothOwner->form_responses['name'] ?? 'Exhibitor'); ?>')" title="Delete Booking">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="8" class="text-center py-5">
                                        <div class="text-muted">
                                            <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                                            <h5>No bookings found</h5>
                                            <p>There are no exhibitor bookings for this event yet.</p>
                                        </div>
                                    </td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <!-- Card View -->
                <div class="card-view" id="cardView" style="display: none;">
                    <div class="row g-3" id="cardContainer">
                        <?php $__empty_1 = true; $__currentLoopData = $boothOwners; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $boothOwner): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php
                        $booking = $boothOwner->booking;
                        $floorplanItem = $booking->floorplanItem ?? null;
                        $paymentStatus = $booking->payments->where('status', 'completed')->count() > 0 ? 'paid' :
                        ($booking->payments->where('status', 'pending')->count() > 0 ? 'pending' : 'unpaid');
                        $paymentStatusClass = $paymentStatus === 'paid' ? 'success' :
                        ($paymentStatus === 'pending' ? 'warning' : 'secondary');
                        ?>
                        <div class="col-lg-6 col-xl-4">
                            <div class="exhibitor-card booking-card"
                                data-booking-id="<?php echo e($booking->id); ?>"
                                data-status="<?php echo e($booking->status ?? 'reserved'); ?>"
                                data-space="<?php echo e($floorplanItem->label ?? ''); ?>"
                                data-members="<?php echo e($floorplanItem && $boothOwner->boothMembers->count() >= ($floorplanItem->max_capacity ?? 5) ? 'full' : ($boothOwner->boothMembers->count() > 0 ? 'partial' : 'empty')); ?>"
                                data-payment="<?php echo e($paymentStatus); ?>"
                                data-search="<?php echo e(strtolower($boothOwner->form_responses['name'] ?? '')); ?> <?php echo e(strtolower($boothOwner->form_responses['company_name'] ?? '')); ?> <?php echo e(strtolower($boothOwner->form_responses['email'] ?? '')); ?>">
                                <div class="card-header">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="mb-1"><?php echo e($boothOwner->form_responses['name'] ?? 'N/A'); ?></h6>
                                            <p class="text-muted mb-0 small"><?php echo e($boothOwner->form_responses['company_name'] ?? 'N/A'); ?></p>
                                        </div>
                                        <span class="badge bg-<?php echo e($paymentStatusClass); ?> bg-opacity-10 text-<?php echo e($paymentStatusClass); ?> border border-<?php echo e($paymentStatusClass); ?>">
                                            <?php echo e(ucfirst($paymentStatus)); ?>

                                        </span>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row g-2 mb-3">
                                        <div class="col-6">
                                            <div class="info-item">
                                                <i class="bi bi-envelope text-muted"></i>
                                                <span class="small"><?php echo e($boothOwner->form_responses['email'] ?? 'N/A'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="info-item">
                                                <i class="bi bi-telephone text-muted"></i>
                                                <span class="small"><?php echo e($boothOwner->form_responses['phone'] ?? 'N/A'); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <?php if($floorplanItem): ?>
                                    <div class="booth-info mb-3">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span class="badge bg-info bg-opacity-10 text-info border border-info">
                                                <?php echo e($floorplanItem->label); ?>

                                            </span>
                                            <span class="fw-bold text-success">$<?php echo e(number_format($floorplanItem->price, 2)); ?></span>
                                        </div>
                                        <div class="mb-2">
                                            <div class="fw-medium small"><?php echo e($boothOwner->form_responses['booth_name'] ?? 'N/A'); ?></div>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <small class="text-muted"><?php echo e($floorplanItem->effective_booth_width_meters); ?>m x <?php echo e($floorplanItem->effective_booth_height_meters); ?>m</small>
                                            <?php
                                            $memberCount = $boothOwner->boothMembers->count();
                                            $maxCapacity = $floorplanItem->max_capacity ?? 5;
                                            $capacityClass = $memberCount >= $maxCapacity ? 'success' : ($memberCount > 0 ? 'warning' : 'secondary');
                                            ?>
                                            <span class="badge bg-<?php echo e($capacityClass); ?> bg-opacity-10 text-<?php echo e($capacityClass); ?> border border-<?php echo e($capacityClass); ?>">
                                                <?php echo e($memberCount); ?>/<?php echo e($maxCapacity); ?> members
                                            </span>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                    
                                    <div class="card-actions">
                                        <div class="btn-group w-100" role="group">
                                            <a href="<?php echo e(route('events.reports.booth-owner-details', ['event' => $event, 'boothOwner' => $boothOwner])); ?>"
                                                class="btn btn-outline-primary btn-sm">
                                                <i class="bi bi-eye me-1"></i>View
                                            </a>
                                            <button type="button" class="btn btn-outline-info btn-sm" onclick="viewBooking(<?php echo e($booking->id); ?>, '<?php echo e($event->slug); ?>', '<?php echo e($booking->access_token); ?>')">
                                                <i class="bi bi-receipt me-1"></i>Booking
                                            </button>
                                            <button type="button" class="btn btn-outline-danger btn-sm" onclick="confirmDelete(<?php echo e($booking->id); ?>, '<?php echo e($boothOwner->form_responses['name'] ?? 'Exhibitor'); ?>')">
                                                <i class="bi bi-trash me-1"></i>Delete
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="col-12">
                            <div class="text-center py-5">
                                <div class="text-muted">
                                    <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                                    <h5>No bookings found</h5>
                                    <p>There are no exhibitor bookings for this event yet.</p>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
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

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">
                        <i class="bi bi-exclamation-triangle text-danger me-2"></i>
                        Confirm Delete
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete the booking for <strong id="deleteExhibitorName"></strong>?</p>
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <strong>Warning:</strong> This action cannot be undone. All booking data, including booth owner information and booth members, will be permanently deleted.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn">
                        <i class="bi bi-trash me-1"></i>Delete Booking
                    </button>
                </div>
            </div>
        </div>
    </div>

    <?php $__env->startPush('scripts'); ?>
    <script>
        // Check if currentView is already declared
        if (typeof currentView === 'undefined') {
            var currentView = 'table';
        }
        
        // Define route URLs
        const deleteBookingRoute = '<?php echo e(route("admin.bookings.destroy", ":id")); ?>';
        
        // Enhanced search and filter functionality
        function applyFilters() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const statusFilter = document.getElementById('statusFilter').value;
            const spaceFilter = document.getElementById('spaceFilter').value;
            const membersFilter = document.getElementById('membersFilter').value;
            const paymentFilter = document.getElementById('paymentFilter').value;

            const rows = document.querySelectorAll('.booking-row, .booking-card');
            let visibleCount = 0;

            rows.forEach(row => {
                const searchText = row.dataset.search;
                const status = row.dataset.status;
                const space = row.dataset.space;
                const members = row.dataset.members;
                const payment = row.dataset.payment;

                const matchesSearch = searchText.includes(searchTerm);
                const matchesStatus = !statusFilter || status === statusFilter;
                const matchesSpace = !spaceFilter || space === spaceFilter;
                const matchesMembers = !membersFilter || members === membersFilter;
                const matchesPayment = !paymentFilter || payment === paymentFilter;

                if (matchesSearch && matchesStatus && matchesSpace && matchesMembers && matchesPayment) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            // Update visible count
            document.getElementById('visibleCount').textContent = visibleCount;
            
            // Update active filters display
            updateActiveFilters();
        }
        
        // Update active filters display
        function updateActiveFilters() {
            const filters = [];
            const searchTerm = document.getElementById('searchInput').value;
            const statusFilter = document.getElementById('statusFilter').value;
            const spaceFilter = document.getElementById('spaceFilter').value;
            const membersFilter = document.getElementById('membersFilter').value;
            const paymentFilter = document.getElementById('paymentFilter').value;
            
            if (searchTerm) filters.push({ type: 'search', label: `Search: "${searchTerm}"` });
            if (statusFilter) filters.push({ type: 'status', label: `Status: ${statusFilter}` });
            if (spaceFilter) filters.push({ type: 'space', label: `Booth: ${spaceFilter}` });
            if (membersFilter) filters.push({ type: 'members', label: `Capacity: ${membersFilter}` });
            if (paymentFilter) filters.push({ type: 'payment', label: `Payment: ${paymentFilter}` });
            
            const activeFiltersDiv = document.getElementById('activeFilters');
            const filterTagsDiv = document.getElementById('filterTags');
            
            if (filters.length > 0) {
                activeFiltersDiv.style.display = 'block';
                filterTagsDiv.innerHTML = filters.map(filter => 
                    `<span class="badge bg-primary bg-opacity-10 text-primary border border-primary me-1">${filter.label}</span>`
                ).join('');
            } else {
                activeFiltersDiv.style.display = 'none';
            }
        }
        
        // Clear search
        function clearSearch() {
            document.getElementById('searchInput').value = '';
            applyFilters();
        }
        
        // Clear all filters
        function clearAllFilters() {
            document.getElementById('searchInput').value = '';
            document.getElementById('statusFilter').value = '';
            document.getElementById('spaceFilter').value = '';
            document.getElementById('membersFilter').value = '';
            document.getElementById('paymentFilter').value = '';
            applyFilters();
        }
        
        // Toggle between table and card view
        function toggleView(view) {
            currentView = view;
            const tableView = document.getElementById('tableView');
            const cardView = document.getElementById('cardView');
            const tableViewBtn = document.getElementById('tableViewBtn');
            const cardsViewBtn = document.getElementById('cardsViewBtn');
            
            if (view === 'table') {
                tableView.style.display = 'block';
                cardView.style.display = 'none';
                tableViewBtn.classList.add('active');
                cardsViewBtn.classList.remove('active');
            } else {
                tableView.style.display = 'none';
                cardView.style.display = 'block';
                tableViewBtn.classList.remove('active');
                cardsViewBtn.classList.add('active');
            }
            
            // Reapply filters to ensure consistency
            applyFilters();
        }

        // Get summary statistics
        function getSummaryStats() {
            const stats = {
                totalExhibitors: <?php echo e($stats['total_booth_owners']); ?>,
                paidBookings: <?php echo e($stats['paid_bookings']); ?>,
                pendingPayments: <?php echo e($stats['pending_payments']); ?>,
                totalRevenue: <?php echo e($stats['total_revenue']); ?>,
                totalPotentialRevenue: <?php echo e($stats['total_potential_revenue'] ?? 0); ?>,
                totalBoothMembers: <?php echo e($boothOwners->sum(function($boothOwner) { return $boothOwner->boothMembers->count(); })); ?>,
                fullyOccupied: <?php echo e($boothOwners->filter(function($boothOwner) { 
                    $floorplanItem = $boothOwner->booking->floorplanItem ?? null;
                    return $floorplanItem && $boothOwner->boothMembers->count() >= ($floorplanItem->max_capacity ?? 5);
                })->count()); ?>,
                partiallyFilled: <?php echo e($boothOwners->filter(function($boothOwner) { 
                    $floorplanItem = $boothOwner->booking->floorplanItem ?? null;
                    return $floorplanItem && $boothOwner->boothMembers->count() > 0 && $boothOwner->boothMembers->count() < ($floorplanItem->max_capacity ?? 5);
                })->count()); ?>,
                eventTitle: '<?php echo e($event->title); ?>',
                eventDates: '<?php echo e($event->start_date->format("F d, Y")); ?> - <?php echo e($event->end_date->format("F d, Y")); ?>',
                reportDate: '<?php echo e(date("F d, Y")); ?>'
            };
            return stats;
        }

        // Export to Excel with summary
        function exportToExcel() {
            const stats = getSummaryStats();
            const table = document.getElementById('bookingsTable');
            const rows = Array.from(table.querySelectorAll('tr'));

            // Create Excel content
            let excelContent = [];
            
            // Add summary section
            excelContent.push(['EXHIBITOR BOOKINGS REPORT']);
            excelContent.push(['Event:', stats.eventTitle]);
            excelContent.push(['Event Dates:', stats.eventDates]);
            excelContent.push(['Report Generated:', stats.reportDate]);
            excelContent.push([]);
            
            // Add summary statistics
            excelContent.push(['SUMMARY STATISTICS']);
            excelContent.push(['Total Exhibitors:', stats.totalExhibitors]);
            excelContent.push(['Paid Bookings:', stats.paidBookings]);
            excelContent.push(['Pending Payments:', stats.pendingPayments]);
            excelContent.push(['Total Revenue:', '$' + stats.totalRevenue.toFixed(2)]);
            excelContent.push(['Potential Revenue:', '$' + stats.totalPotentialRevenue.toFixed(2)]);
            excelContent.push(['Collection Rate:', ((stats.totalRevenue / stats.totalPotentialRevenue) * 100).toFixed(1) + '%']);
            excelContent.push(['Total Booth Members:', stats.totalBoothMembers]);
            excelContent.push(['Fully Occupied Booths:', stats.fullyOccupied]);
            excelContent.push(['Partially Filled Booths:', stats.partiallyFilled]);
            excelContent.push([]);
            
            // Add detailed data
            excelContent.push(['DETAILED EXHIBITOR DATA']);
            
            // Add headers with separated columns
            const headers = [
                'Exhibitor Name',
                'Exhibitor Email', 
                'Company Name',
                'Company Location',
                'Contact Email',
                'Contact Phone',
                'Booth Label',
                'Booth Status',
                'Booth Dimensions',
                'Booth Name',
                'Booth Price',
                'Payment Status',
                'Member Count',
                'Max Capacity',
                'Capacity Status'
            ];
            excelContent.push(headers);

            // Add data rows with separated fields
            rows.slice(1).forEach(row => {
                if (row.style.display !== 'none') {
                    const cells = row.querySelectorAll('td');
                    const rowData = [];
                    
                    // Exhibitor column (Name + Email)
                    const exhibitorCell = cells[0];
                    const exhibitorName = exhibitorCell.querySelector('.fw-semibold')?.textContent.trim() || '';
                    const exhibitorEmail = exhibitorCell.querySelector('small')?.textContent.trim() || '';
                    rowData.push(exhibitorName, exhibitorEmail);
                    
                    // Company column (Name + Location)
                    const companyCell = cells[1];
                    const companyName = companyCell.querySelector('.fw-medium')?.textContent.trim() || '';
                    const companyLocation = companyCell.querySelector('small')?.textContent.trim() || '';
                    rowData.push(companyName, companyLocation);
                    
                    // Contact column (Email + Phone)
                    const contactCell = cells[2];
                    const contactEmail = contactCell.querySelector('.fw-medium')?.textContent.trim() || '';
                    const contactPhone = contactCell.querySelector('small')?.textContent.trim() || '';
                    rowData.push(contactEmail, contactPhone);
                    
                    // Booth column (Label + Status + Dimensions)
                    const boothCell = cells[3];
                    const boothLabel = boothCell.querySelector('.badge')?.textContent.trim() || '';
                    const boothStatus = boothCell.querySelectorAll('.badge')[1]?.textContent.trim() || '';
                    const boothDimensions = boothCell.querySelector('small')?.textContent.trim() || '';
                    rowData.push(boothLabel, boothStatus, boothDimensions);
                    
                    // Booth Name column
                    const boothNameCell = cells[4];
                    const boothName = boothNameCell.textContent.trim() || '';
                    rowData.push(boothName);
                    
                    // Price column
                    const priceCell = cells[5];
                    const price = priceCell.textContent.trim().replace(/[^\d.-]/g, '') || '';
                    rowData.push(price);
                    
                    // Payment Status column
                    const paymentCell = cells[6];
                    const paymentStatus = paymentCell.textContent.trim() || '';
                    rowData.push(paymentStatus);
                    
                    // Booth Members column (Count + Capacity + Status)
                    const membersCell = cells[7];
                    const memberCount = membersCell.querySelector('.badge')?.textContent.trim().split('/')[0] || '';
                    const maxCapacity = membersCell.querySelector('.badge')?.textContent.trim().split('/')[1] || '';
                    const capacityStatus = membersCell.querySelector('small')?.textContent.trim() || '';
                    rowData.push(memberCount, maxCapacity, capacityStatus);
                    
                    excelContent.push(rowData);
                }
            });

            // Convert to CSV format for Excel
            const csvContent = excelContent.map(row => 
                row.map(cell => `"${cell}"`).join(',')
            ).join('\n');

            const blob = new Blob([csvContent], {
                type: 'text/csv;charset=utf-8;'
            });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'exhibitor-bookings-report-<?php echo e($event->slug); ?>-<?php echo e(date("Y-m-d")); ?>.csv';
            a.click();
            window.URL.revokeObjectURL(url);
        }

        // Export to PDF using server-side generation
        function exportToPDF() {
            // Redirect to the PDF route
            window.open('<?php echo e(route("events.reports.bookings.pdf", $event)); ?>', '_blank');
        }

        // Export to CSV (enhanced)
        function exportToCSV() {
            const stats = getSummaryStats();
            const table = document.getElementById('bookingsTable');
            const rows = Array.from(table.querySelectorAll('tr'));

            let csv = [];
            
            // Add summary section
            csv.push(['EXHIBITOR BOOKINGS REPORT']);
            csv.push(['Event', stats.eventTitle]);
            csv.push(['Event Dates', stats.eventDates]);
            csv.push(['Report Generated', stats.reportDate]);
            csv.push([]);
            csv.push(['SUMMARY STATISTICS']);
            csv.push(['Total Exhibitors', stats.totalExhibitors]);
            csv.push(['Paid Bookings', stats.paidBookings]);
            csv.push(['Pending Payments', stats.pendingPayments]);
            csv.push(['Total Revenue', '$' + stats.totalRevenue.toFixed(2)]);
            csv.push(['Potential Revenue', '$' + stats.totalPotentialRevenue.toFixed(2)]);
            csv.push(['Collection Rate', ((stats.totalRevenue / stats.totalPotentialRevenue) * 100).toFixed(1) + '%']);
            csv.push(['Total Booth Members', stats.totalBoothMembers]);
            csv.push(['Fully Occupied Booths', stats.fullyOccupied]);
            csv.push(['Partially Filled Booths', stats.partiallyFilled]);
            csv.push([]);
            csv.push(['DETAILED EXHIBITOR DATA']);

            // Add headers
            const headers = [];
            rows[0].querySelectorAll('th').forEach(th => {
                headers.push(th.textContent.trim().replace(/[^\w\s]/gi, ''));
            });
            csv.push(headers);

            // Add data rows
            rows.slice(1).forEach(row => {
                if (row.style.display !== 'none') {
                    const rowData = [];
                    row.querySelectorAll('td').forEach(td => {
                        rowData.push(td.textContent.trim());
                    });
                    csv.push(rowData);
                }
            });

            const csvContent = csv.map(row => 
                row.map(cell => `"${cell}"`).join(',')
            ).join('\n');
            
            const blob = new Blob([csvContent], {
                type: 'text/csv;charset=utf-8;'
            });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'exhibitor-bookings-report-<?php echo e($event->slug); ?>-<?php echo e(date("Y-m-d")); ?>.csv';
            a.click();
            window.URL.revokeObjectURL(url);
        }



        // View booking details
        function viewBooking(bookingId, eventSlug, accessToken) {
            // Open booking details page in new tab
            window.open(`/event/${eventSlug}/booking/${accessToken}/owner`, '_blank');
        }

        // Send email to exhibitor
        function sendEmail(boothOwnerId) {
            // This would typically open an email composition interface
            alert('Email functionality would be implemented here');
        }

        // Delete booking functionality
        let deleteBookingId = null;

        function confirmDelete(bookingId, exhibitorName) {
            deleteBookingId = bookingId;
            document.getElementById('deleteExhibitorName').textContent = exhibitorName;
            
            // Show the delete modal - handle both Bootstrap 5 and fallback
            const modalElement = document.getElementById('deleteModal');
            if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                const deleteModal = new bootstrap.Modal(modalElement);
                deleteModal.show();
            } else {
                // Fallback for when Bootstrap is not available
                modalElement.style.display = 'block';
                modalElement.classList.add('show');
                document.body.classList.add('modal-open');
                
                // Add backdrop
                const backdrop = document.createElement('div');
                backdrop.className = 'modal-backdrop fade show';
                backdrop.id = 'deleteModalBackdrop';
                document.body.appendChild(backdrop);
            }
        }

        function deleteBooking() {
            if (!deleteBookingId) return;

            // Show loading state
            const deleteBtn = document.getElementById('confirmDeleteBtn');
            const originalText = deleteBtn.innerHTML;
            deleteBtn.innerHTML = '<i class="bi bi-hourglass-split me-1"></i>Deleting...';
            deleteBtn.disabled = true;

            // Send delete request
            const deleteUrl = deleteBookingRoute.replace(':id', deleteBookingId);
            fetch(deleteUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Remove the row/card from the DOM
                    const bookingRow = document.querySelector(`tr[data-booking-id="${deleteBookingId}"], .booking-card[data-booking-id="${deleteBookingId}"]`);
                    if (bookingRow) {
                        bookingRow.remove();
                    }
                    
                    // Update visible count
                    const visibleRows = document.querySelectorAll('.booking-row:not([style*="display: none"]), .booking-card:not([style*="display: none"])');
                    document.getElementById('visibleCount').textContent = visibleRows.length;
                    
                    // Show success message
                    showAlert('Booking deleted successfully', 'success');
                    
                    // Close modal
                    const modalElement = document.getElementById('deleteModal');
                    if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                        const deleteModal = bootstrap.Modal.getInstance(modalElement);
                        if (deleteModal) {
                            deleteModal.hide();
                        }
                    } else {
                        // Fallback modal closing
                        modalElement.style.display = 'none';
                        modalElement.classList.remove('show');
                        document.body.classList.remove('modal-open');
                        
                        // Remove backdrop
                        const backdrop = document.getElementById('deleteModalBackdrop');
                        if (backdrop) {
                            backdrop.remove();
                        }
                    }
                } else {
                    showAlert(data.message || 'Failed to delete booking', 'danger');
                }
            })
            .catch(error => {
                console.error('Fetch error:', error);
                console.error('Error details:', {
                    message: error.message,
                    stack: error.stack
                });
                showAlert('An error occurred while deleting the booking', 'danger');
            })
            .finally(() => {
                // Reset button state
                deleteBtn.innerHTML = originalText;
                deleteBtn.disabled = false;
                deleteBookingId = null;
            });
        }

        function showAlert(message, type) {
            // Create alert element
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
            alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
            alertDiv.innerHTML = `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            `;
            
            document.body.appendChild(alertDiv);
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                if (alertDiv.parentNode) {
                    alertDiv.remove();
                }
            }, 5000);
        }

        // Initialize search on input
        document.getElementById('searchInput').addEventListener('input', applyFilters);
        document.getElementById('statusFilter').addEventListener('change', applyFilters);
        document.getElementById('spaceFilter').addEventListener('change', applyFilters);
        document.getElementById('membersFilter').addEventListener('change', applyFilters);
        document.getElementById('paymentFilter').addEventListener('change', applyFilters);
        
        // Initialize table view as active
        document.getElementById('tableViewBtn').classList.add('active');
        
        // Initialize delete confirmation button
        document.getElementById('confirmDeleteBtn').addEventListener('click', deleteBooking);
        
        // Add event listeners for modal close buttons (fallback)
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('btn-close') || e.target.classList.contains('btn-secondary')) {
                const modal = e.target.closest('.modal');
                if (modal && modal.id === 'deleteModal') {
                    closeModalFallback();
                }
            }
        });
        
        // Close modal when clicking backdrop (fallback)
        document.addEventListener('click', function(e) {
            if (e.target.id === 'deleteModalBackdrop') {
                closeModalFallback();
            }
        });
        
        function closeModalFallback() {
            const modalElement = document.getElementById('deleteModal');
            modalElement.style.display = 'none';
            modalElement.classList.remove('show');
            document.body.classList.remove('modal-open');
            
            const backdrop = document.getElementById('deleteModalBackdrop');
            if (backdrop) {
                backdrop.remove();
            }
        }
    </script>
    <?php $__env->stopPush(); ?>

    <?php $__env->startPush('styles'); ?>
    <style>
        /* Statistics Cards */
        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            border: 1px solid rgba(0,0,0,0.05);
            transition: all 0.3s ease;
            height: 100%;
        }
        
        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 16px rgba(0,0,0,0.15);
        }
        
        .stat-card-primary .stat-icon {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
        }
        
        .stat-card-success .stat-icon {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
        }
        
        .stat-card-warning .stat-icon {
            background: linear-gradient(135deg, #ffc107, #fd7e14);
            color: white;
        }
        
        .stat-card-info .stat-icon {
            background: linear-gradient(135deg, #17a2b8, #6f42c1);
            color: white;
        }
        
        .stat-card-secondary .stat-icon {
            background: linear-gradient(135deg, #6c757d, #495057);
            color: white;
        }
        
        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }
        
        .stat-label {
            font-size: 0.875rem;
            color: #6c757d;
            font-weight: 500;
            margin-bottom: 0.25rem;
        }
        
        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: #212529;
            line-height: 1;
            margin-bottom: 0.25rem;
        }
        
        .stat-subtitle {
            font-size: 0.75rem;
            color: #6c757d;
        }
        
        /* Inline Stat Card Layout */
        .stat-card-inline {
            padding: 1rem 1.5rem;
        }
        
        .stat-content-inline {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .stat-icon-inline {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            flex-shrink: 0;
        }
        
        .stat-card-secondary .stat-icon-inline {
            background: linear-gradient(135deg, #6c757d, #495057);
            color: white;
        }
        
        .stat-card-success .stat-icon-inline {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
        }
        
        .stat-card-warning .stat-icon-inline {
            background: linear-gradient(135deg, #ffc107, #fd7e14);
            color: white;
        }
        
        .stat-info-inline {
            flex: 1;
        }
        
        .stat-label-inline {
            font-size: 0.875rem;
            color: #6c757d;
            font-weight: 500;
            margin-bottom: 0.25rem;
        }
        
        .stat-value-inline {
            font-size: 1.75rem;
            font-weight: 700;
            color: #212529;
            line-height: 1;
            margin-bottom: 0.25rem;
        }
        
        .stat-subtitle-inline {
            font-size: 0.75rem;
            color: #6c757d;
        }
        
        /* Revenue Summary Card */
        .revenue-summary-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            border: 1px solid rgba(0,0,0,0.05);
            height: 100%;
        }
        
        .revenue-summary-card .card-header {
            padding: 1rem 1rem 1rem 1rem;
        }
        
        .revenue-summary-card .card-body {
            padding: 1rem 1rem 1rem 1rem;
        }
        
                .revenue-item {
            display: flex;
            justify-content: between;
            align-items: center;
            padding: 0.25rem 0;
        }
        
        .revenue-label {
            font-size: 0.8rem;
            color: #6c757d;
            flex: 1;
        }
        
        .revenue-value {
            font-weight: 600;
            font-size: 0.9rem;
        }
        
        .revenue-divider {
            height: 1px;
            background: #e9ecef;
            margin: 0.5rem 0;
        }
        
        /* Filter Section */
        .filter-section {
            background: white;
            border-radius: 12px;
            padding: 1rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            border: 1px solid rgba(0,0,0,0.05);
        }
        
        .search-box .input-group-text {
            background: #f8f9fa;
            border-color: #dee2e6;
        }
        
        .filter-controls .form-select-sm {
            font-size: 0.875rem;
        }
        
        .active-filters {
            padding-top: 1rem;
            border-top: 1px solid #e9ecef;
        }
        
        /* Table Container */
        .table-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            border: 1px solid rgba(0,0,0,0.05);
            overflow: hidden;
        }
        
        .table-header {
            padding: 1.5rem;
            border-bottom: 1px solid #e9ecef;
            background: #f8f9fa;
        }
        
        .table-actions .btn.active {
            background-color: #0d6efd;
            color: white;
            border-color: #0d6efd;
        }
        
        /* Table Styles */
        .table th {
            font-weight: 600;
            color: #495057;
            background: #f8f9fa;
            white-space: nowrap;
        }

        .table td {
            white-space: nowrap;
        }

        .booking-row:hover {
            background-color: #f8f9fa;
        }

        /* Make action column sticky and prevent wrapping */
        .table th:last-child,
        .table td:last-child {
            position: sticky;
            right: 0;
            background: white;
            z-index: 10;
            min-width: 120px;
            white-space: nowrap;
        }

        .table th:last-child {
            background: #f8f9fa;
        }

        .booking-row:hover td:last-child {
            background: #f8f9fa;
        }

        /* Ensure table doesn't wrap content */
        .table-responsive {
            overflow-x: auto;
        }

        .table {
            min-width: max-content;
        }

        /* Ensure proper spacing for action buttons */
        .table td:last-child .btn-group {
            white-space: nowrap;
        }

        .table td:last-child .btn {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
        }

        /* Mobile responsiveness for action column */
        @media (max-width: 768px) {
            .table th:last-child,
            .table td:last-child {
                min-width: 100px;
            }
            
            .table td:last-child .btn {
                font-size: 0.7rem;
                padding: 0.2rem 0.4rem;
            }
        }

        /* Card View */
        .exhibitor-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            border: 1px solid rgba(0,0,0,0.05);
            transition: all 0.3s ease;
            height: 100%;
        }
        
        .exhibitor-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 16px rgba(0,0,0,0.15);
        }
        
        .exhibitor-card .card-header {
            background: #f8f9fa;
            border-bottom: 1px solid #e9ecef;
            padding: 1rem;
        }
        
        .exhibitor-card .card-body {
            padding: 1rem;
        }
        
        .info-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .booth-info {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 0.75rem;
        }
        
        .card-actions .btn-group .btn {
            border-radius: 0.375rem !important;
        }
        
        .card-actions .btn-group .btn:first-child {
            border-top-left-radius: 0.375rem !important;
            border-bottom-left-radius: 0.375rem !important;
        }
        
        .card-actions .btn-group .btn:last-child {
            border-top-right-radius: 0.375rem !important;
            border-bottom-right-radius: 0.375rem !important;
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
            .btn,
            .card-header,
            .table-header,
            .filter-section {
                display: none !important;
            }

            .card,
            .table-container {
                border: none !important;
                box-shadow: none !important;
            }
        }
        
        @media (max-width: 768px) {
            .stat-card {
                padding: 1rem;
            }
            
            .stat-value {
                font-size: 1.5rem;
            }
            
            .filter-section {
                padding: 1rem;
            }
            
            .table-header {
                padding: 1rem;
            }
        }
    </style>
    <?php $__env->stopPush(); ?>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalb1882f8c14f0a5270b201bcf650aaac1)): ?>
<?php $attributes = $__attributesOriginalb1882f8c14f0a5270b201bcf650aaac1; ?>
<?php unset($__attributesOriginalb1882f8c14f0a5270b201bcf650aaac1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalb1882f8c14f0a5270b201bcf650aaac1)): ?>
<?php $component = $__componentOriginalb1882f8c14f0a5270b201bcf650aaac1; ?>
<?php unset($__componentOriginalb1882f8c14f0a5270b201bcf650aaac1); ?>
<?php endif; ?><?php /**PATH C:\xampp\htdocs\exhibition-app\resources\views/events/reports/bookings.blade.php ENDPATH**/ ?>