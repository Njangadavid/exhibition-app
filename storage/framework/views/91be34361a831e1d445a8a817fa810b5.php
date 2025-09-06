<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
  <!-- Favicon -->
  <link rel="icon" type="image/x-icon" href="/images/sajili-5.png">
        <link rel="icon" type="image/png" sizes="32x32" href="/images/sajili-5.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/images/sajili-5.png">
        <link rel="apple-touch-icon" sizes="180x180" href="/images/sajili-5.png">
        <link rel="shortcut icon" href="/images/sajili-5.png">

        <title><?php echo e(config('app.name', 'Exhibition App')); ?> - <?php echo e($event->name ?? 'Event'); ?></title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        <?php echo app('Illuminate\Foundation\Vite')(['resources/js/app.js']); ?>
        <?php echo $__env->yieldPushContent('styles'); ?>

    </head>
    <body>
        <div class="min-vh-100 bg-light">
            <?php echo $__env->make('layouts.navigation', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

            <?php if(isset($event)): ?>
            <div class="event-layout-container">
                <!-- Sidebar -->
                <div class="sidebar" id="sidebar">
                <!-- Event Header -->
                <div class="sidebar-header sticky-top">
                    <div class="align-items-center">
                        <div class="me-3">
                            <div class="bg-gradient-to-br from-blue-400 to-purple-600   d-flex align-items-center justify-content-center" style="max-width:100%;max-height:100px;">
                                    <?php if($event->logo): ?>
                                    <img src="<?php echo e(Storage::url($event->logo)); ?>" alt="<?php echo e($event->name); ?>" class="rounded-circle" style="max-width:100%;max-height:100px; object-fit: cover;">
                                    <?php else: ?>
                                    <i class="bi bi-calendar-event text-white fs-5"></i>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <div>
                            <h6 class="mb-1 fw-bold text-truncate" style="max-width: 200px;"><?php echo e($event->name); ?></h6>
                             
                        </div>
                    </div>
                    
                    <!-- Event Info -->
                    <div class="event-info">
                        <div class="d-flex align-items-center text-muted small">
                                        <i class="bi bi-calendar-event me-2"></i>
                            <span><?php echo e($event->start_date->format('M d, Y')); ?> - <?php echo e($event->end_date->format('M d, Y')); ?></span>
                        </div>
                        <div class="d-flex align-items-center text-muted small">
                                        <i class="bi bi-clock me-2"></i>
                            <span><?php echo e($event->duration_in_days); ?> day<?php echo e($event->duration_in_days > 1 ? 's' : ''); ?></span>
                                </div>
                            </div>
                        </div>

                <!-- Sidebar Navigation -->
                <nav class="sidebar-nav">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <?php if(auth()->user()->hasPermission('view_events')): ?>
                            <a href="<?php echo e(route('events.dashboard', $event)); ?>" class="nav-link <?php echo e(request()->routeIs('events.dashboard') ? 'active' : ''); ?>" data-bs-toggle="tooltip" data-bs-placement="right" title="Overview">
                                <i class="bi bi-speedometer2 me-3"></i>
                                <span>Overview</span>
                            </a>
                            <?php endif; ?>
                        </li>
                        <li class="nav-item">
                            <?php if(auth()->user()->hasPermission('manage_floorplans') || auth()->user()->hasPermission('manage_own_floorplans')): ?>
                            <a href="<?php echo e(route('events.floorplan', $event)); ?>" class="nav-link <?php echo e(request()->routeIs('events.floorplan') ? 'active' : ''); ?>" data-bs-toggle="tooltip" data-bs-placement="right" title="Floorplan">
                                <i class="bi bi-grid-3x3-gap me-3"></i>
                                <span>Floorplan</span>
                            </a>
                            <?php endif; ?>
                        </li>
                                    <li class="nav-item">
                            <?php if(auth()->user()->hasPermission('manage_forms') || auth()->user()->hasPermission('manage_own_forms')): ?>
                            <a href="<?php echo e(route('events.form-builders.index', $event)); ?>" class="nav-link <?php echo e(request()->routeIs('events.form-builders.*') ? 'active' : ''); ?>" data-bs-toggle="tooltip" data-bs-placement="right" title="Exhibitor Forms">
                                <i class="bi bi-pencil-square me-3"></i>
                                <span>Exhibitor Forms</span>
                            </a>
                            <?php endif; ?>
                                    </li>
                                    <li class="nav-item">
                            <?php if(auth()->user()->hasPermission('manage_emails') || auth()->user()->hasPermission('manage_own_emails')): ?>
                            <a href="<?php echo e(route('events.email-templates.index', $event)); ?>" class="nav-link <?php echo e(request()->routeIs('events.email-templates.*') ? 'active' : ''); ?>" data-bs-toggle="tooltip" data-bs-placement="right" title="Email Templates">
                                <i class="bi bi-envelope me-3"></i>
                                <span>Email Templates</span>
                            </a>
                            <?php endif; ?>
                                    </li>
                                    <li class="nav-item">
                            <?php if(auth()->user()->hasPermission('manage_email_settings')): ?>
                            <a href="<?php echo e(route('admin.events.email-settings', $event)); ?>" class="nav-link <?php echo e(request()->routeIs('admin.events.email-settings*') ? 'active' : ''); ?>" data-bs-toggle="tooltip" data-bs-placement="right" title="Email Settings">
                                <i class="bi bi-sliders me-3"></i>
                                <span>Email Settings</span>
                            </a>
                            <?php endif; ?>
                                    </li>
                                    <li class="nav-item">
                            <?php if(auth()->user()->hasPermission('manage_payment_methods')): ?>
                            <a href="<?php echo e(route('admin.payment-methods.index', ['event' => $event->id])); ?>" class="nav-link <?php echo e(request()->routeIs('admin.payment-methods.*') ? 'active' : ''); ?>" data-bs-toggle="tooltip" data-bs-placement="right" title="Payment Setup">
                                <i class="bi bi-credit-card me-3"></i>
                                <span>Payment Setup</span>
                            </a>
                            <?php endif; ?>
                                    </li>
                                    <li class="nav-item">
                            <?php if(auth()->user()->hasPermission('view_booking_reports')): ?>
                            <a href="<?php echo e(route('events.reports.bookings', $event)); ?>" class="nav-link <?php echo e(request()->routeIs('events.reports.*') ? 'active' : ''); ?>" data-bs-toggle="tooltip" data-bs-placement="right" title="Reports">
                                <i class="bi bi-bar-chart me-3"></i>
                                <span>Reports</span>
                            </a>
                            <?php endif; ?>
                                    </li>
                                </ul>
                                
                                <!-- Quick Actions -->
                    <div class="sidebar-section">
                        <h6 class="sidebar-section-title">Quick Actions</h6>
                        <ul class="nav flex-column">
                            <li class="nav-item">
                                <?php if(auth()->user()->hasPermission('manage_events') || auth()->user()->hasPermission('manage_own_events')): ?>
                                <a href="<?php echo e(route('events.edit', $event)); ?>" class="nav-link" data-bs-toggle="tooltip" data-bs-placement="right" title="Edit Event">
                                    <i class="bi bi-pencil me-3"></i>
                                    <span>Edit Event</span>
                                </a>
                                <?php endif; ?>
                            </li>
                            <li class="nav-item">
                                <?php if(auth()->user()->hasPermission('view_events')): ?>
                                <a href="<?php echo e(route('events.public.show', $event)); ?>" target="_blank" class="nav-link" data-bs-toggle="tooltip" data-bs-placement="right" title="Public Page">
                                    <i class="bi bi-globe me-3"></i>
                                    <span>Public Page</span>
                                </a>
                                <?php endif; ?>
                            </li>
                            <li class="nav-item">
                                <?php if(auth()->user()->hasPermission('manage_floorplans') || auth()->user()->hasPermission('manage_own_floorplans')): ?>
                                <a href="<?php echo e(route('events.public.floorplan', $event)); ?>" target="_blank" class="nav-link" data-bs-toggle="tooltip" data-bs-placement="right" title="Public Floorplan">
                                    <i class="bi bi-grid-3x3-gap me-3"></i>
                                    <span>Public Floorplan</span>
                                </a>
                                <?php endif; ?>
                            </li>
                            <li class="nav-item">
                                <?php if(auth()->user()->hasPermission('view_events')): ?>
                                <a href="<?php echo e(route('events.index')); ?>" class="nav-link" data-bs-toggle="tooltip" data-bs-placement="right" title="Back to Events">
                                    <i class="bi bi-arrow-left me-3"></i>
                                    <span>Back to Events</span>
                                </a>
                                <?php endif; ?>
                            </li>
                                        </ul>
                                    </div>
                </nav>
            </div>

            <!-- Main Content Area -->
            <div class="main-content">
           

                <!-- Top Bar -->
                <div class="top-bar">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center gap-2">
                            <button class="btn btn-outline-secondary d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebar" aria-controls="sidebar">
                                <i class="bi bi-list"></i>
                            </button>
                            <button class="btn btn-outline-secondary d-none d-lg-inline-flex" type="button" id="sidebarToggle" title="Expand Sidebar" data-bs-toggle="tooltip" style="cursor: pointer;">
                                <i class="bi bi-chevron-left" id="sidebarToggleIcon"></i>
                            </button>
                                    </div>
                        <div class="d-flex gap-2">
                            <a href="<?php echo e(route('events.edit', $event)); ?>" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-pencil me-2"></i>Edit Event
                            </a>
                            <a href="#" class="btn btn-outline-success btn-sm">
                                <i class="bi bi-play-circle me-2"></i>Launch
                            </a>
                                </div>
                            </div>
                        </div>

            <!-- Page Content -->
                <main class="content">
                <?php echo e($slot); ?>

            </main>
            </div>
            </div>
            <?php else: ?>
            <!-- Page Content without sidebar -->
            <main class="w-100">
                <?php echo e($slot); ?>

            </main>
            <?php endif; ?>
        </div>
        <script src="https://cdn.tiny.cloud/1/ymua9kt15x20ok61l6qlh9piih90ojnvq3bkqskll1mrzxgm/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
   
        <!-- Custom Scripts Stack -->
        <?php echo $__env->yieldPushContent('scripts'); ?>

        <!-- Custom Styles -->
        <style>
            /* Event Layout Container */
            .event-layout-container {
                display: flex;
                height: calc(100vh - 70px);
                overflow: hidden;
            }

            /* Sidebar Styles */
            .sidebar {
                width: 280px;
                height: 100%;
                background: #f8f9fa;
                border-right: 1px solid #e9ecef;
                position: relative;
                z-index: 1000;
                overflow-y: auto;
                transition: all 0.3s ease;
                box-shadow: 2px 0 8px rgba(0, 0, 0, 0.05);
                flex-shrink: 0;
            }

            /* Collapsed Sidebar */
            .sidebar.collapsed {
                width: 60px;
            }

            .sidebar.collapsed .sidebar-header {
                padding: 1rem 0rem;
            }

            .sidebar.collapsed .sidebar-header .d-flex {
                flex-direction: column;
                align-items: center;
                text-align: center;
            }

            .sidebar.collapsed .sidebar-header .me-3 {
                margin-right: 0 !important;
                margin-bottom: 0;
            }

            .sidebar.collapsed .sidebar-header .bg-gradient-to-br {
                width: 48px !important;
                height: 48px !important;
                max-width: 48px !important;
                max-height: 48px !important;
            }

            .sidebar.collapsed .sidebar-header .bg-gradient-to-br img {
                width: 48px !important;
                height: 48px !important;
                max-width: 48px !important;
                max-height: 48px !important;
            }

            .sidebar.collapsed .sidebar-header h6,
            .sidebar.collapsed .sidebar-header .badge,
            .sidebar.collapsed .event-info {
                display: none;
            }

            .sidebar.collapsed .sidebar-nav .nav-link {
               
                justify-content: center;
            }

            .sidebar.collapsed .sidebar-nav .nav-link span {
                display: none;
            }

            .sidebar.collapsed .sidebar-nav .nav-link i {
                margin-right: 0 !important;
                font-size: 1.1rem;
            }

            .sidebar.collapsed .sidebar-section {
                display: none;
            }

            .sidebar-header {
                padding: 1.5rem 0rem 1rem 1rem;
                border-bottom: 1px solid #e9ecef;
                background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
                position: sticky;
                top: 0;
                z-index: 10;
            }

            

            .sidebar-nav .nav-link {
                color: #6c757d;
                padding: 0.75rem 1.5rem;
                border-radius: 0;
                transition: all 0.3s ease;
                display: flex;
                align-items: center;
                border-left: 3px solid transparent;
            }

            .sidebar-nav .nav-link:hover {
                color: #495057;
                background-color: #e9ecef;
                border-left-color: #6c757d;
            }

            .sidebar-nav .nav-link.active {
                color: #495057;
                background-color: #e9ecef;
                border-left-color: #495057;
                font-weight: 600;
            }
            
            .sidebar-section {
                margin-top: 2rem;
                padding: 0 1.5rem;
            }

            .sidebar-section-title {
                color: #6c757d;
                font-size: 0.875rem;
                font-weight: 600;
                text-transform: uppercase;
                letter-spacing: 0.5px;
                margin-bottom: 1rem;
                padding-bottom: 0.5rem;
                border-bottom: 1px solid #dee2e6;
            }

            /* Main Content */
            .main-content {
                flex: 1;
                height: 100%;
                background: #f8f9fa;
                display: flex;
                flex-direction: column;
                overflow: hidden;
            }

            .top-bar {
                background: #fff;
                border-bottom: 1px solid #e9ecef;
                padding: 1rem 1.5rem;
                position: sticky;
                top: 0;
                z-index: 100;
            }

            .content {
                padding: 2rem 1.5rem;
                flex: 1;
                overflow-y: auto;
            }

            /* Mobile Responsive */
            @media (max-width: 991.98px) {
                .event-layout-container {
                    flex-direction: column;
                    height: auto;
                    min-height: calc(100vh - 70px);
                }

                .sidebar {
                    position: fixed;
                    left: 0;
                    top: 70px;
                    height: calc(100vh - 70px);
                    transform: translateX(-100%);
                    z-index: 1050;
                }

                .sidebar.show {
                    transform: translateX(0);
                }

                .main-content {
                    width: 100%;
                    height: auto;
                    min-height: calc(100vh - 70px);
                }
            }

            /* Event Info Styles */
            .event-info {
                font-size: 0.875rem;
            }

            /* Scrollbar Styling */
            .sidebar::-webkit-scrollbar {
                width: 4px;
            }

            .sidebar::-webkit-scrollbar-track {
                background: #f1f1f1;
            }

            .sidebar::-webkit-scrollbar-thumb {
                background: #c1c1c1;
                border-radius: 2px;
            }

            .sidebar::-webkit-scrollbar-thumb:hover {
                background: #a8a8a8;
            }
        </style>
        
        <!-- Sidebar JavaScript -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const sidebarToggle = document.getElementById('sidebarToggle');
                const sidebarToggleIcon = document.getElementById('sidebarToggleIcon');
                const sidebar = document.getElementById('sidebar');
                const mobileToggle = document.querySelector('[data-bs-toggle="offcanvas"]');
                
                // Desktop sidebar toggle
                if (sidebarToggle && sidebarToggleIcon && sidebar) {
                    sidebarToggle.addEventListener('click', function(e) {
                        e.preventDefault();
                        
                        sidebar.classList.toggle('collapsed');
                        
                        // Update icon and tooltip
                        if (sidebar.classList.contains('collapsed')) {
                            sidebarToggleIcon.className = 'bi bi-chevron-right';
                            sidebarToggle.title = 'Expand Sidebar';
                        } else {
                            sidebarToggleIcon.className = 'bi bi-chevron-left';
                            sidebarToggle.title = 'Collapse Sidebar';
                        }
                        
                        // Update tooltip (check if bootstrap is available)
                        if (typeof bootstrap !== 'undefined') {
                            const tooltip = bootstrap.Tooltip.getInstance(sidebarToggle);
                            if (tooltip) {
                                tooltip.setContent({ '.tooltip-inner': sidebarToggle.title });
                            }
                        }
                        
                        // Save global state to localStorage
                        const isCollapsed = sidebar.classList.contains('collapsed');
                        localStorage.setItem('sidebarCollapsed', isCollapsed);
                        
                        // Update global state indicator
                        
                        // Show a brief visual feedback
                        const originalText = sidebarToggle.innerHTML;
                        sidebarToggle.innerHTML = isCollapsed ? 
                            '<i class="bi bi-chevron-right"></i> <small class="ms-1">Collapsed</small>' : 
                            '<i class="bi bi-chevron-left"></i> <small class="ms-1">Expanded</small>';
                        
                        setTimeout(() => {
                            sidebarToggle.innerHTML = originalText;
                        }, 1000);
                    });
                }

                // Mobile sidebar toggle
                if (mobileToggle && sidebar) {
                    mobileToggle.addEventListener('click', function() {
                        sidebar.classList.toggle('show');
                    });
                }
                
                // Close sidebar when clicking outside on mobile
                document.addEventListener('click', function(e) {
                    if (window.innerWidth <= 991.98) {
                        if (!sidebar.contains(e.target) && !mobileToggle.contains(e.target)) {
                            sidebar.classList.remove('show');
                        }
                    }
                });
                
                // Handle window resize
                window.addEventListener('resize', function() {
                    if (window.innerWidth > 991.98) {
                        sidebar.classList.remove('show');
                    }
                });

                // Clear localStorage for testing (remove this line after testing)
                // localStorage.removeItem('sidebarCollapsed');
                
                // Load global sidebar state (always use saved preference)
                const savedState = localStorage.getItem('sidebarCollapsed');
                
                // Apply saved state or default to expanded
                if (savedState === 'true') {
                    sidebar.classList.add('collapsed');
                    if (sidebarToggleIcon) {
                        sidebarToggleIcon.className = 'bi bi-chevron-right';
                    }
                    if (sidebarToggle) {
                        sidebarToggle.title = 'Expand Sidebar';
                    }
                } else {
                    // Default to expanded state
                    sidebar.classList.remove('collapsed');
                    if (sidebarToggleIcon) {
                        sidebarToggleIcon.className = 'bi bi-chevron-left';
                    }
                    if (sidebarToggle) {
                        sidebarToggle.title = 'Collapse Sidebar';
                    }
                }
                
                // Update global state indicator
                updateGlobalStateIndicator();

                // Update icon and tooltip based on current state
                if (sidebar && sidebarToggleIcon && sidebarToggle) {
                    if (sidebar.classList.contains('collapsed')) {
                        sidebarToggleIcon.className = 'bi bi-chevron-right';
                        sidebarToggle.title = 'Expand Sidebar';
                    } else {
                        sidebarToggleIcon.className = 'bi bi-chevron-left';
                        sidebarToggle.title = 'Collapse Sidebar';
                    }
                }

                // Initialize tooltips (check if bootstrap is available)
                let tooltipList = [];
                if (typeof bootstrap !== 'undefined') {
                    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                    tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                        return new bootstrap.Tooltip(tooltipTriggerEl);
                    });
                }

                // Function to update tooltip visibility
                function updateTooltips() {
                    const isCollapsed = sidebar.classList.contains('collapsed');
                    if (typeof bootstrap !== 'undefined') {
                        tooltipList.forEach(function(tooltip) {
                            if (isCollapsed) {
                                tooltip.enable();
                            } else {
                                tooltip.disable();
                            }
                        });
                    }
                }

                // Update tooltips on load
                updateTooltips();
                
            });
        </script>
        
        <?php echo $__env->yieldPushContent('scripts'); ?>
    </body>
</html>

<?php /**PATH C:\xampp\htdocs\exhibition-app\resources\views/components/event-layout.blade.php ENDPATH**/ ?>