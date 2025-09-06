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
    <?php $__env->startPush('styles'); ?>
        <style>
            /* Recent Events Styling */
            .recent-event-item {
                transition: all 0.2s ease;
                border-radius: 0;
                width: 100%;
            }
            
            .recent-event-item:hover {
                background-color: #f8f9fa;
                transform: translateX(2px);
            }
            
            .recent-event-item:last-child .border-bottom {
                border-bottom: none !important;
            }
            
            /* Ensure proper width constraints */
            .recent-event-item .d-flex {
                width: 100%;
                max-width: 100%;
                align-items: center;
            }
            
            /* Prevent image container from expanding */
            .recent-event-item .d-flex > .event-image-container {
                flex: 0 0 120px;
            }
            
            /* Event Image Container */
            .event-image-container {
                width: 120px !important;
                height: 80px !important;
                min-width: 120px !important;
                min-height: 80px !important;
                max-width: 120px !important;
                max-height: 120px !important;
                border-radius: 8px;
                overflow: hidden;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                display: flex;
                align-items: center;
                justify-content: center;
                box-shadow: 0 2px 8px rgba(0,0,0,0.15);
                flex-shrink: 0;
                flex-grow: 0;
            }
            
            .event-image {
                width: 100% !important;
                height: 100% !important;
                object-fit: cover !important;
                object-position: center;
                transition: transform 0.2s ease;
                max-width: 120px !important;
                max-height: 120px !important;
            }
            
            .recent-event-item:hover .event-image {
                transform: scale(1.05);
            }
            
            .event-image-placeholder {
                width: 100%;
                height: 100%;
                display: flex;
                align-items: center;
                justify-content: center;
                color: white;
                font-size: 1.5rem;
            }
            
            /* Line clamp for description */
            .line-clamp-2 {
                display: -webkit-box;
                -webkit-line-clamp: 2;
                -webkit-box-orient: vertical;
                overflow: hidden;
            }
            
            /* Responsive adjustments */
            @media (max-width: 768px) {
                .event-image-container {
                    width: 80px !important;
                    height: 60px !important;
                    min-width: 80px !important;
                    min-height: 60px !important;
                    max-width: 80px !important;
                    max-height: 100px !important;
                }
                
                .recent-event-item .d-flex > .event-image-container {
                    flex: 0 0 80px;
                }
                
                .event-image {
                    max-width: 80px !important;
                    max-height: 100px !important;
                }
                
                .recent-event-item:hover {
                    transform: none;
                }
                
                .recent-event-item .p-3 {
                    padding: 0.75rem !important;
                }
            }
        </style>
    <?php $__env->stopPush(); ?>
    
     <?php $__env->slot('header', null, []); ?> 
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 mb-0">
                <i class="bi bi-speedometer2 me-2"></i>
                <?php echo e(__('Exhibition Dashboard')); ?>

        </h2>
            <?php if(auth()->user()->hasPermission('create_events')): ?>
                <a href="<?php echo e(route('events.create')); ?>" class="btn btn-primary">
                    <i class="bi bi-plus me-2"></i>
                    Create Event
                </a>
            <?php endif; ?>
        </div>
     <?php $__env->endSlot(); ?>

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
                                    <div class="h3 fw-bold text-dark"><?php echo e(App\Models\Event::count()); ?></div>
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
                                    <div class="h3 fw-bold text-dark"><?php echo e(App\Models\Event::active()->count()); ?></div>
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
                                    <div class="h3 fw-bold text-dark"><?php echo e(App\Models\Event::upcoming()->count()); ?></div>
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
                                    <div class="h3 fw-bold text-dark"><?php echo e(App\Models\Event::completed()->count()); ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Events -->
            <div class="card mb-4 border-0 shadow-sm">
                <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center">
                    <h3 class="h5 mb-0 fw-bold">
                        <i class="bi bi-calendar-event me-2 text-primary"></i>
                        Recent Events
                    </h3>
                    <a href="<?php echo e(route('events.index')); ?>" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-arrow-right me-1"></i>View All Events
                    </a>
                </div>
                <div class="card-body p-0">
                    <?php $__empty_1 = true; $__currentLoopData = App\Models\Event::latest()->take(3)->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <a href="<?php echo e(route('events.dashboard', $event)); ?>" class="d-block text-decoration-none recent-event-item">
                            <div class="p-3 border-bottom border-light">
                                <div class="d-flex align-items-center gap-3">
                                    <!-- Event Image -->
                                    <div class="event-image-container">
                                        <?php if($event->logo): ?>
                                            <img src="<?php echo e(Storage::url($event->logo)); ?>" 
                                                 alt="<?php echo e($event->name); ?>" 
                                                 class="event-image" >
                                        <?php else: ?>
                                            <div class="event-image-placeholder">
                                                <i class="bi bi-calendar-event"></i>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <!-- Event Details -->
                                    <div class="flex-grow-1 min-w-0">
                                        <h5 class="mb-1 fw-bold text-dark text-truncate"><?php echo e($event->name); ?></h5>
                                        <p class="text-muted small mb-1 line-clamp-2"><?php echo e(Str::limit($event->description, 80)); ?></p>
                                        <div class="d-flex align-items-center gap-3">
                                            <small class="text-muted">
                                                <i class="bi bi-calendar3 me-1"></i>
                                                <?php echo e($event->start_date->format('M d, Y')); ?>

                                            </small>
                                            <small class="text-muted">
                                                <i class="bi bi-clock me-1"></i>
                                                <?php echo e($event->duration_in_days); ?> day<?php echo e($event->duration_in_days > 1 ? 's' : ''); ?>

                                            </small>
                                        </div>
                                    </div>
                                    
                                    <!-- Status and Actions -->
                                    <div class="text-end flex-shrink-0">
                                        <span class="badge 
                                            <?php if($event->status === 'active'): ?> bg-success
                                            <?php elseif($event->status === 'published'): ?> bg-primary
                                            <?php elseif($event->status === 'completed'): ?> bg-info
                                            <?php elseif($event->status === 'cancelled'): ?> bg-danger
                                            <?php else: ?> bg-secondary <?php endif; ?> px-2 py-1">
                                            <?php echo e(ucfirst($event->status)); ?>

                                        </span>
                                        <div class="mt-1">
                                            <small class="text-primary fw-medium">
                                                <i class="bi bi-arrow-right me-1"></i>Manage
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="text-center py-5">
                            <div class="mb-3">
                                <i class="bi bi-calendar-x text-muted" style="font-size: 3rem;"></i>
                            </div>
                            <h5 class="text-muted mb-2">No events found</h5>
                            <p class="text-muted mb-3">Create your first event to get started!</p>
                            <?php if(auth()->user()->hasPermission('create_events')): ?>
                                <a href="<?php echo e(route('events.create')); ?>" class="btn btn-primary">
                                    <i class="bi bi-plus me-2"></i>Create Event
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
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
                                <a href="<?php echo e(route('events.create')); ?>" class="btn btn-primary">
                                    <i class="bi bi-plus-circle me-2"></i>
                                    Create New Event
                                </a>
                            </div>
                            
                            <!-- Secondary Actions -->
                            <div class="d-grid gap-2">
                                <a href="<?php echo e(route('events.index')); ?>" class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-calendar-event me-2"></i>
                                    Manage Events
                                </a>
                                <a href="<?php echo e(route('admin.payment-methods.index')); ?>" class="btn btn-outline-secondary btn-sm">
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
                                        <span class="badge bg-primary fs-6"><?php echo e(App\Models\Event::where('status', 'active')->count()); ?></span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center bg-light rounded p-2">
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-bookmark-star text-success me-2"></i>
                                            <small class="text-muted">Total Bookings</small>
                                        </div>
                                        <span class="badge bg-success fs-6"><?php echo e(App\Models\Booking::count()); ?></span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center bg-light rounded p-2">
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-credit-card text-info me-2"></i>
                                            <small class="text-muted">Payment Methods</small>
                                        </div>
                                        <span class="badge bg-info fs-6"><?php echo e(App\Models\PaymentMethod::count()); ?></span>
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
                                <?php
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
                                ?>
                                
                                <?php $__empty_1 = true; $__currentLoopData = $recentActivities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <div class="d-flex align-items-start">
                                        <div class="flex-shrink-0">
                                            <div class="bg-<?php echo e($activity['color']); ?> rounded-circle" style="width: 8px; height: 8px; margin-top: 8px;"></div>
                                        </div>
                                        <div class="ms-3 flex-grow-1">
                                            <a href="<?php echo e($activity['link']); ?>" class="text-decoration-none">
                                                <p class="mb-1 small text-dark">
                                                    <i class="bi <?php echo e($activity['icon']); ?> me-1"></i>
                                                    <?php echo e($activity['message']); ?>

                                                </p>
                                            </a>
                                            <small class="text-muted">
                                                <?php if($activity['time']->diffInHours(now()) < 1): ?>
                                                    <?php echo e($activity['time']->diffInMinutes(now())); ?> minutes ago
                                                <?php elseif($activity['time']->diffInDays(now()) < 1): ?>
                                                    <?php echo e($activity['time']->diffInHours(now())); ?> hours ago
                                                <?php else: ?>
                                                    <?php echo e($activity['time']->diffInDays(now())); ?> days ago
                                                <?php endif; ?>
                                            </small>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <div class="text-center py-3">
                                        <i class="bi bi-activity text-muted fs-4 d-block mb-2"></i>
                                        <p class="text-muted small mb-0">No recent activity</p>
                                        <small class="text-muted">Activities will appear here as they happen</small>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if($recentActivities->count() > 0): ?>
                                    <div class="text-center mt-2">
                                        <small class="text-muted">
                                            <i class="bi bi-clock me-1"></i>
                                            Showing last 7 days of activity
                                        </small>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
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
<?php /**PATH C:\xampp\htdocs\exhibition-app\resources\views/dashboard.blade.php ENDPATH**/ ?>