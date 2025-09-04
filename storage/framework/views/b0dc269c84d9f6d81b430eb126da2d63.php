<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
     <?php $__env->slot('header', null, []); ?> 
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 mb-0">
                <i class="bi bi-calendar-event me-2"></i>
                <?php echo e(__('Event Management')); ?>

            </h2>
            <a href="<?php echo e(route('events.create')); ?>" class="btn btn-primary">
                <i class="bi bi-plus me-2"></i>
                Create New Event
            </a>
        </div>
     <?php $__env->endSlot(); ?>

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
                                    <div class="h3 fw-bold text-dark mb-0"><?php echo e(App\Models\Event::count()); ?></div>
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
                                    <div class="h3 fw-bold text-dark mb-0"><?php echo e(App\Models\Event::active()->count()); ?></div>
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
                                    <div class="h3 fw-bold text-dark mb-0"><?php echo e(App\Models\Event::upcoming()->count()); ?></div>
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
                                    <div class="h3 fw-bold text-dark mb-0"><?php echo e(App\Models\Event::completed()->count()); ?></div>
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
                            <a href="<?php echo e(route('events.index')); ?>" class="badge bg-primary text-decoration-none px-3 py-2">
                                <i class="bi bi-grid me-1"></i>All Events
                            </a>
                            <a href="<?php echo e(route('events.index')); ?>?status=active" class="badge bg-success text-decoration-none px-3 py-2">
                                <i class="bi bi-play-circle me-1"></i>Active
                            </a>
                            <a href="<?php echo e(route('events.index')); ?>?status=upcoming" class="badge bg-info text-decoration-none px-3 py-2">
                                <i class="bi bi-clock me-1"></i>Upcoming
                            </a>
                            <a href="<?php echo e(route('events.index')); ?>?status=completed" class="badge bg-warning text-decoration-none px-3 py-2">
                                <i class="bi bi-check-circle me-1"></i>Completed
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Events Grid -->
            <?php if($events->count() > 0): ?>
                <div class="row g-4">
                    <?php $__currentLoopData = $events; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col-lg-6 col-xl-4 event-card" 
                             data-event-slug="<?php echo e($event->slug); ?>"
                             data-status="<?php echo e($event->status); ?>"
                             data-title="<?php echo e(strtolower($event->name)); ?>"
                             data-description="<?php echo e(strtolower($event->description)); ?>"
                             data-date="<?php echo e($event->start_date->format('Y-m-d')); ?>">
                            <div class="card border-0 shadow-sm h-100 event-item">
                                <!-- Event Logo - Full Width -->
                                <?php if($event->logo): ?>
                                    <div class="event-logo-container">
                                        <img src="<?php echo e(Storage::url($event->logo)); ?>" alt="<?php echo e($event->name); ?>" class="event-logo">
                                    </div>
                                <?php else: ?>
                                    <div class="event-logo-placeholder">
                                        <i class="bi bi-calendar-event"></i>
                                    </div>
                                <?php endif; ?>
                                
                                <!-- Event Header with Status -->
                                <div class="card-header bg-transparent border-0 pb-0">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <span class="badge 
                                                <?php if($event->status === 'active'): ?> bg-success
                                                <?php elseif($event->status === 'published'): ?> bg-primary
                                                <?php elseif($event->status === 'completed'): ?> bg-info
                                                <?php elseif($event->status === 'cancelled'): ?> bg-danger
                                                <?php else: ?> bg-secondary <?php endif; ?> px-3 py-2">
                                                <i class="bi 
                                                    <?php if($event->status === 'active'): ?> bi-play-circle
                                                    <?php elseif($event->status === 'published'): ?> bi-globe
                                                    <?php elseif($event->status === 'completed'): ?> bi-check-circle
                                                    <?php elseif($event->status === 'cancelled'): ?> bi-x-circle
                                                    <?php else: ?> bi-dash-circle <?php endif; ?> me-1"></i>
                                                <?php echo e(ucfirst($event->status)); ?>

                                            </span>
                                        </div>
                                        
                                        <!-- Event Menu -->
                                        <div class="dropdown">
                                            <button class="btn btn-link text-muted p-0" type="button" data-bs-toggle="dropdown">
                                                <i class="bi bi-three-dots-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li><a class="dropdown-item" href="<?php echo e(route('events.show', $event)); ?>">
                                                    <i class="bi bi-eye me-2"></i>View Details
                                                </a></li>
                                                <li><a class="dropdown-item" href="<?php echo e(route('events.edit', $event)); ?>">
                                                    <i class="bi bi-pencil me-2"></i>Edit Event
                                                </a></li>
                                                <li><a class="dropdown-item" href="<?php echo e(route('events.dashboard', $event)); ?>">
                                                    <i class="bi bi-speedometer2 me-2"></i>Dashboard
                                                </a></li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li><a class="dropdown-item" href="<?php echo e(route('events.public.show', $event)); ?>" target="_blank">
                                                    <i class="bi bi-globe me-2"></i>Public Page
                                                </a></li>
                                                <li><a class="dropdown-item" href="<?php echo e(route('events.public.floorplan', $event)); ?>" target="_blank">
                                                    <i class="bi bi-map me-2"></i>Floorplan
                                                </a></li>
                                                <li><a class="dropdown-item" href="<?php echo e(route('admin.payment-methods.index', ['event' => $event->id])); ?>">
                                                    <i class="bi bi-credit-card me-2"></i>Payment Methods
                                                </a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <!-- Event Content -->
                                <div class="card-body pt-2">
                                    <h5 class="card-title fw-bold mb-2 text-truncate" title="<?php echo e($event->name); ?>">
                                        <a href="<?php echo e(route('events.dashboard', $event)); ?>" class="text-decoration-none text-dark hover-text-primary" style="transition: color 0.2s ease;">
                                            <?php echo e($event->name); ?>

                                        </a>
                                    </h5>
                                    
                                    <p class="text-muted small mb-3 line-clamp-2" title="<?php echo e($event->description); ?>">
                                        <?php echo e(Str::limit($event->description, 120)); ?>

                                    </p>

                                    <!-- Event Timeline -->
                                    <div class="mb-3">
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="bi bi-calendar-event text-primary me-2"></i>
                                            <span class="small fw-medium"><?php echo e($event->start_date->format('M d, Y')); ?></span>
                                            <span class="text-muted mx-2">•</span>
                                            <span class="text-muted small"><?php echo e($event->duration_in_days); ?> day<?php echo e($event->duration_in_days > 1 ? 's' : ''); ?></span>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-clock text-info me-2"></i>
                                            <span class="small"><?php echo e($event->start_date->format('g:i A')); ?> - <?php echo e($event->end_date->format('g:i A')); ?></span>
                                        </div>
                                    </div>

                                    <!-- Progress Indicator for Active Events -->
                                    <?php if($event->status === 'active'): ?>
                                        <?php
                                            $totalDays = $event->start_date->diffInDays($event->end_date) + 1;
                                            $elapsedDays = $event->start_date->diffInDays(now()) + 1;
                                            $progress = min(100, max(0, ($elapsedDays / $totalDays) * 100));
                                        ?>
                                        <div class="mb-3">
                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                <span class="small text-muted">Event Progress</span>
                                                <span class="small fw-medium"><?php echo e(round($progress)); ?>%</span>
                                            </div>
                                            <div class="progress" style="height: 6px;">
                                                <div class="progress-bar bg-success" style="width: <?php echo e($progress); ?>%"></div>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <!-- Action Buttons -->
                                <div class="card-footer bg-transparent border-0 pt-0">
                                    <div class="d-grid gap-2">
                                        <a href="<?php echo e(route('events.dashboard', $event)); ?>" class="btn btn-primary btn-sm">
                                            <i class="bi bi-speedometer2 me-2"></i>Manage Event
                                        </a>
                                        <div class="d-flex gap-1">
                                            <a href="<?php echo e(route('events.public.show', $event)); ?>" class="btn btn-outline-success btn-sm flex-fill" target="_blank" title="View Public Page">
                                                <i class="bi bi-globe"></i>
                                            </a>
                                            <a href="<?php echo e(route('events.public.floorplan', $event)); ?>" class="btn btn-outline-info btn-sm flex-fill" target="_blank" title="View Floorplan">
                                                <i class="bi bi-map"></i>
                                            </a>
                                            <a href="<?php echo e(route('events.edit', $event)); ?>" class="btn btn-outline-secondary btn-sm flex-fill" title="Edit Event">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <button type="button" class="btn btn-outline-danger btn-sm flex-fill" 
                                                    onclick="confirmDeleteEvent('<?php echo e($event->slug); ?>', '<?php echo e(addslashes($event->name)); ?>')" 
                                                    title="Delete Event">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

                <!-- Enhanced Pagination -->
                <?php if($events->hasPages()): ?>
                    <div class="mt-5">
                        <nav aria-label="Events pagination">
                            <?php echo e($events->links()); ?>

                        </nav>
                    </div>
                <?php endif; ?>
            <?php else: ?>
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
                            <a href="<?php echo e(route('events.create')); ?>" class="btn btn-primary btn-lg">
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
            <?php endif; ?>
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

        /* Event Logo Styles */
        .event-logo-container {
            width: 100%;
            height: 50px;
            overflow: hidden;
            border-radius: 0.375rem 0.375rem 0 0;
        }

        .event-logo {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
        }

        .event-logo-placeholder {
            width: 100%;
            height: 50px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 0.375rem 0.375rem 0 0;
        }

        .event-logo-placeholder i {
            font-size: 1.5rem;
            color: white;
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

        // Delete Event Functionality
        let deleteEventSlug = null;
        const deleteEventRoute = '<?php echo e(route("events.delete", ":slug")); ?>';

        function confirmDeleteEvent(eventSlug, eventName) {
            deleteEventSlug = eventSlug;
            
            // Show confirmation modal using vanilla JavaScript
            document.getElementById('eventNameToDelete').textContent = eventName;
            const modal = document.getElementById('deleteEventModal');
            modal.style.display = 'block';
            modal.classList.add('show');
            document.body.classList.add('modal-open');
            
            // Add backdrop
            const backdrop = document.createElement('div');
            backdrop.className = 'modal-backdrop fade show';
            backdrop.id = 'deleteEventModalBackdrop';
            document.body.appendChild(backdrop);
        }

        function deleteEvent() {
            if (!deleteEventSlug) return;

            const deleteBtn = document.getElementById('confirmDeleteEventBtn');
            const originalText = deleteBtn.innerHTML;
            deleteBtn.innerHTML = '<i class="bi bi-hourglass-split me-1"></i>Deleting...';
            deleteBtn.disabled = true;

            // Send delete request
            const deleteUrl = deleteEventRoute.replace(':slug', deleteEventSlug);
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
                    // Remove the event card from the DOM
                    const eventCard = document.querySelector(`[data-event-slug="${deleteEventSlug}"]`);
                    if (eventCard) {
                        eventCard.remove();
                    }
                    
                    // Show success message
                    showAlert('Event deleted successfully!', 'success');
                    
                    // Close modal
                    closeModal();
                } else {
                    showAlert(data.message || 'Failed to delete event', 'danger');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('An error occurred while deleting the event', 'danger');
            })
            .finally(() => {
                deleteBtn.innerHTML = originalText;
                deleteBtn.disabled = false;
                deleteEventSlug = null;
            });
        }

        function closeModal() {
            const modal = document.getElementById('deleteEventModal');
            modal.style.display = 'none';
            modal.classList.remove('show');
            document.body.classList.remove('modal-open');
            
            // Remove backdrop
            const backdrop = document.getElementById('deleteEventModalBackdrop');
            if (backdrop) {
                backdrop.remove();
            }
        }

        function showAlert(message, type) {
            // Create alert element
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
            alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
            alertDiv.innerHTML = `
                ${message}
                <button type="button" class="btn-close" onclick="this.parentElement.remove()"></button>
            `;
            
            document.body.appendChild(alertDiv);
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                if (alertDiv.parentNode) {
                    alertDiv.remove();
                }
            }, 5000);
        }
    </script>

    <!-- Delete Event Confirmation Modal -->
    <div class="modal fade" id="deleteEventModal" tabindex="-1" aria-labelledby="deleteEventModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteEventModalLabel">
                        <i class="bi bi-exclamation-triangle text-warning me-2"></i>
                        Confirm Event Deletion
                    </h5>
                    <button type="button" class="btn-close" onclick="closeModal()" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete the event <strong id="eventNameToDelete"></strong>?</p>
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <strong>Warning:</strong> This action cannot be undone. All associated data including bookings, floorplans, and reports will be permanently deleted.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeModal()">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteEventBtn" onclick="deleteEvent()">
                        <i class="bi bi-trash me-1"></i>Delete Event
                    </button>
                </div>
            </div>
        </div>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\exhibition-app\resources\views/events/index.blade.php ENDPATH**/ ?>