<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title'); ?> - <?php echo e($event->name ?? 'Exhibition'); ?></title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="/images/sajili-5.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/images/sajili-5.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/images/sajili-5.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/images/sajili-5.png">
    <link rel="shortcut icon" href="/images/sajili-5.png">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <?php echo $__env->yieldPushContent('styles'); ?>
    
    <style>
        .progress-steps {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1rem;
            margin-bottom: 2rem;
        }
        
        .progress-steps .badge {
            width: 2rem;
            height: 2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.875rem;
            font-weight: 600;
        }
        
        .progress-steps small {
            font-size: 0.875rem;
            font-weight: 500;
        }
        
        .progress-steps .bi-arrow-right {
            font-size: 1.25rem;
        }
        
        /* Payment step notification styles */
        .payment-notification {
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% {
                transform: translate(-50%, -50%) scale(1);
                opacity: 1;
            }
            50% {
                transform: translate(-50%, -50%) scale(1.1);
                opacity: 0.8;
            }
            100% {
                transform: translate(-50%, -50%) scale(1);
                opacity: 1;
            }
        }
        
        /* Hover effect for payment step with balance */
        .progress-steps a:hover .payment-notification {
            animation: none;
            transform: translate(-50%, -50%) scale(1.2);
        }
    </style>
</head>

<body class="bg-light">
    <!-- Header -->
    <header class="bg-white shadow-sm border-bottom">
        <div class="container-fluid py-3">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center" style="max-width: 100%;">
                    <div class="me-3" style="max-width: 100%;">
                        <?php if($event->logo): ?>
                        <img src="<?php echo e(Storage::url($event->logo)); ?>" alt="<?php echo e($event->name); ?>" style="max-width: 100%; height: auto; max-height: 80px;">
                        <?php else: ?>
                        <div class="bg-primary d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            <i class="bi bi-calendar-event text-white fs-4"></i>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="text-end">
                    <h1 class="h5 mb-1 fw-bold text-info"><?php echo e($event->name); ?></h1>
                    <p class="mb-0 text-muted">
                        <i class="bi bi-calendar3 me-1"></i>
                        <?php echo e($event->start_date->format('M d, Y')); ?> - <?php echo e($event->end_date->format('M d, Y')); ?>

                    </p>
                </div>
            </div>
        </div>
    </header>

    <!-- Progress Steps (if provided) -->
    <?php if(isset($showProgress) && $showProgress): ?>
    <div class="py-2 bg-light border-bottom">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <!-- Desktop Progress -->
                    <div class="d-none d-lg-block">
                        <div class="d-flex align-items-center justify-content-center gap-3">
                            <!-- Step 1: Select Space -->
                            <?php if(isset($booking) && $booking->access_token): ?>
                            <!-- Always clickable if access token exists -->
                            <a href="<?php echo e(route('events.public.floorplan-token', ['event' => $event->slug, 'accessToken' => $booking->boothOwner->access_token])); ?>" class="text-decoration-none">
                                <div class="d-flex align-items-center">
                                    <span class="badge <?php echo e($currentStep == 1 ? 'bg-primary' : 'bg-success'); ?> rounded-pill me-2">1</span>
                                    <small class="<?php echo e($currentStep == 1 ? 'text-primary fw-medium' : 'text-success'); ?>">Select Space</small>
                                </div>
                            </a>
                            <?php else: ?>
                            <div class="d-flex align-items-center">
                                <span class="badge bg-secondary rounded-pill me-2">1</span>
                                <small class="    <?php echo e($currentStep == 1  ? 'text-primary fw-medium'  : ($currentStep > 1  ? 'text-info' : 'text-muted')); ?>"> Select Space </small>
                            </div>
                            <?php endif; ?>

                            <i class="bi bi-arrow-right text-muted"></i>

                            <!-- Step 2: Owner Details -->
                            <?php if(isset($booking) && $booking->access_token): ?>
                            <!-- Always clickable if access token exists -->
                            <a href="<?php echo e(route('bookings.owner-form-token', ['eventSlug' => $event->slug, 'accessToken' => $booking->boothOwner->access_token])); ?>" class="text-decoration-none">
                                <div class="d-flex align-items-center">
                                    <span class="badge <?php echo e($currentStep == 2 ? 'bg-primary' : 'bg-success'); ?> rounded-pill me-2">2</span>
                                    <small class="<?php echo e($currentStep == 2 ? 'text-primary fw-medium' : 'text-success'); ?>">Owner Details</small>
                                </div>
                            </a>
                            <?php else: ?>
                            <div class="d-flex align-items-center">
                                <span class="badge bg-secondary rounded-pill me-2">2</span>
                                <small class="<?php echo e($currentStep == 2 ? 'text-primary fw-medium' : ($currentStep > 2  ? 'text-info' : 'text-muted')); ?>">Owner Details</small>
                            </div>
                            <?php endif; ?>

                            <i class="bi bi-arrow-right text-muted"></i>

                            <!-- Step 3: Add Members -->
                            <?php if(isset($booking) && $booking->access_token): ?>
                            <!-- Always clickable if access token exists -->
                            <a href="<?php echo e(route('bookings.member-form', ['eventSlug' => $event->slug, 'accessToken' => $booking->boothOwner->access_token])); ?>" class="text-decoration-none">
                                <div class="d-flex align-items-center">
                                    <span class="badge <?php echo e($currentStep == 3 ? 'bg-primary' : 'bg-success'); ?> rounded-pill me-2">3</span>
                                    <small class="<?php echo e($currentStep == 3 ? 'text-primary fw-medium' : 'text-success'); ?>">Add Members</small>
                                </div>
                            </a>
                            <?php else: ?>
                            <div class="d-flex align-items-center">
                                <span class="badge bg-secondary rounded-pill me-2">3</span>
                                <small class="<?php echo e($currentStep == 3 ? 'text-primary fw-medium' : ($currentStep > 3  ? 'text-info' : 'text-muted')); ?>">Add Exhibitor</small>
                            </div>
                            <?php endif; ?>

                            <i class="bi bi-arrow-right text-muted"></i>

                            <!-- Step 4: Payment -->
                            <?php if(isset($booking) && $booking->access_token): ?>
                            <!-- Always clickable if access token exists -->
                            <a href="<?php echo e(route('bookings.payment', ['eventSlug' => $event->slug, 'accessToken' => $booking->boothOwner->access_token])); ?>" class="text-decoration-none">
                                <div class="d-flex align-items-center position-relative">
                                    <span class="badge <?php echo e($currentStep == 4 ? 'bg-primary' : ($booking->balance > 0 ? 'bg-danger' : 'bg-success')); ?> rounded-pill me-2">4</span>
                                    <small class="<?php echo e($currentStep == 4 ? 'text-primary fw-medium' : ($booking->balance > 0 ? 'text-danger fw-bold' : 'text-success')); ?>">Payment</small>
                                    
                                    <!-- Red notification dot for unpaid balance -->
                                    <?php if($booking->balance > 0): ?>
                                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger payment-notification" style="font-size: 0.6rem;">
                                            <i class="bi bi-exclamation-triangle-fill"></i>
                                        </span>
                                        
                                        <!-- Balance due indicator -->
                                        <div class="position-absolute top-100 start-50 translate-middle-x mt-1">
                                            <small class="text-danger fw-bold" style="font-size: 0.65rem; white-space: nowrap;">
                                                Balance Due!
                                            </small>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </a>
                            <?php else: ?>
                            <div class="d-flex align-items-center">
                                <span class="badge bg-secondary rounded-pill me-2">4</span>
                                <small class="<?php echo e($currentStep == 4 ? 'text-primary fw-medium' : ($currentStep > 4  ? 'text-info' : 'text-muted')); ?>">Payment</small>
                            </div>
                            <?php endif; ?>



                            <!-- Step 5: Receipt -->
                            <?php if(isset($booking) && $booking->access_token && $booking->hasCompletedPayments()): ?>
                            <!-- Only visible if there's a completed payment -->
                            <a href="<?php echo e(route('bookings.receipt', ['eventSlug' => $event->slug, 'accessToken' => $booking->boothOwner->access_token])); ?>" class="text-decoration-none">
                                <div class="d-flex align-items-center">
                                    <span class="badge <?php echo e($currentStep == 5 ? 'bg-primary' : 'bg-success'); ?> rounded-pill me-2">5</span>
                                    <small class="<?php echo e($currentStep == 5 ? 'text-primary fw-medium' : 'text-success'); ?>">Receipt</small>
                                </div>
                            </a>
                            <?php else: ?>
                            <!-- Show as disabled for new users or incomplete payments 
                            <i class="bi bi-arrow-right text-muted"></i>
                            <div class="d-flex align-items-center">
                                <span class="badge bg-secondary rounded-pill me-2">5</span>
                                <small class="text-muted">Receipt</small>
                            </div>
                            -->
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Mobile Progress -->
                    <div class="d-block d-lg-none">
                        <div class="card bg-light border-0">
                            <div class="card-body py-2 px-3">
                                <div class="text-center">
                                    <small class="text-muted fw-medium">Booking Process:</small>
                                </div>
                                <div class="row g-1 mt-1">
                                    <div class="col text-center">
                                        <?php if(isset($booking) && $booking->access_token): ?>
                                        <a href="<?php echo e(route('events.public.floorplan-token', ['event' => $event->slug, 'accessToken' => $booking->boothOwner->access_token])); ?>" class="text-decoration-none">
                                            <div class="d-flex flex-column align-items-center">
                                                <span class="badge <?php echo e((isset($currentStep) && $currentStep >= 1) ? ($currentStep == 1 ? 'bg-primary' : 'bg-success') : 'bg-secondary'); ?> rounded-pill mb-1">1</span>
                                                <small class="<?php echo e((isset($currentStep) && $currentStep >= 1) ? ($currentStep == 1 ? 'text-primary' : 'text-success') : 'text-muted'); ?>" style="font-size: 0.7rem;">Select</small>
                                            </div>
                                        </a>
                                        <?php else: ?>
                                        <div class="d-flex flex-column align-items-center">
                                            <span class="badge <?php echo e((isset($currentStep) && $currentStep >= 1) ? ($currentStep == 1 ? 'bg-primary' : 'bg-success') : 'bg-secondary'); ?> rounded-pill mb-1">1</span>
                                            <small class="<?php echo e((isset($currentStep) && $currentStep == 1) ? 'text-primary' : 'text-muted'); ?>" style="font-size: 0.7rem;">Select</small>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="col text-center">
                                        <?php if(isset($booking) && $booking->access_token): ?>
                                        <a href="<?php echo e(route('bookings.owner-form-token', ['eventSlug' => $event->slug, 'accessToken' => $booking->boothOwner->access_token])); ?>" class="text-decoration-none">
                                            <div class="d-flex flex-column align-items-center">
                                                <span class="badge <?php echo e((isset($currentStep) && $currentStep >= 2) ? ($currentStep == 2 ? 'bg-primary' : 'bg-success') : 'bg-secondary'); ?> rounded-pill mb-1">2</span>
                                                <small class="<?php echo e((isset($currentStep) && $currentStep >= 2) ? ($currentStep == 2 ? 'text-primary' : 'text-success') : 'text-muted'); ?>" style="font-size: 0.7rem;">Details</small>
                                            </div>
                                        </a>
                                        <?php else: ?>
                                        <div class="d-flex flex-column align-items-center">
                                            <span class="badge <?php echo e((isset($currentStep) && $currentStep >= 2) ? ($currentStep == 2 ? 'bg-primary' : 'bg-success') : 'bg-secondary'); ?> rounded-pill mb-1">2</span>
                                            <small class="<?php echo e((isset($currentStep) && $currentStep >= 2) ? ($currentStep == 2 ? 'text-primary' : 'text-muted') : 'text-muted'); ?>" style="font-size: 0.7rem;">Details</small>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="col text-center">
                                        <?php if(isset($booking) && $booking->access_token): ?>
                                        <a href="<?php echo e(route('bookings.member-form', ['eventSlug' => $event->slug, 'accessToken' => $booking->boothOwner->access_token])); ?>" class="text-decoration-none">
                                            <div class="d-flex flex-column align-items-center">
                                                <span class="badge <?php echo e((isset($currentStep) && $currentStep >= 3) ? ($currentStep == 3 ? 'bg-primary' : 'bg-success') : 'bg-secondary'); ?> rounded-pill mb-1">3</span>
                                                <small class="<?php echo e((isset($currentStep) && $currentStep >= 3) ? ($currentStep == 3 ? 'text-primary' : 'text-success') : 'text-muted'); ?>" style="font-size: 0.7rem;">Members</small>
                                            </div>
                                        </a>
                                        <?php else: ?>
                                        <div class="d-flex flex-column align-items-center">
                                            <span class="badge <?php echo e((isset($currentStep) && $currentStep >= 3) ? ($currentStep == 3 ? 'bg-primary' : 'bg-success') : 'bg-secondary'); ?> rounded-pill mb-1">3</span>
                                            <small class="<?php echo e((isset($currentStep) && $currentStep >= 3) ? ($currentStep == 3 ? 'text-primary' : 'text-muted') : 'text-muted'); ?>" style="font-size: 0.7rem;">Members</small>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="col text-center">
                                        <?php if(isset($booking) && $booking->access_token): ?>
                                        <a href="<?php echo e(route('bookings.payment', ['eventSlug' => $event->slug, 'accessToken' => $booking->boothOwner->access_token])); ?>" class="text-decoration-none">
                                            <div class="d-flex flex-column align-items-center position-relative">
                                                <span class="badge <?php echo e((isset($currentStep) && $currentStep >= 4) ? ($currentStep == 4 ? 'bg-primary' : ($booking->balance > 0 ? 'bg-danger' : 'bg-success')) : 'bg-secondary'); ?> rounded-pill mb-1">4</span>
                                                <small class="<?php echo e((isset($currentStep) && $currentStep >= 4) ? ($currentStep == 4 ? 'text-primary' : ($booking->balance > 0 ? 'text-danger fw-bold' : 'text-success')) : 'text-muted'); ?>" style="font-size: 0.7rem;">Payment</small>
                                                
                                                <!-- Red notification dot for unpaid balance -->
                                                <?php if($booking->balance > 0): ?>
                                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger payment-notification" style="font-size: 0.5rem;">
                                                        <i class="bi bi-exclamation-triangle-fill"></i>
                                                    </span>
                                                    
                                                    <!-- Balance due indicator for mobile -->
                                                    <div class="position-absolute top-100 start-50 translate-middle-x mt-1">
                                                        <small class="text-danger fw-bold" style="font-size: 0.6rem; white-space: nowrap;">
                                                            Balance Due!
                                                        </small>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </a>
                                        <?php else: ?>
                                        <div class="d-flex flex-column align-items-center">
                                            <span class="badge <?php echo e((isset($currentStep) && $currentStep >= 4) ? ($currentStep == 4 ? 'bg-primary' : 'bg-success') : 'bg-secondary'); ?> rounded-pill mb-1">4</span>
                                            <small class="<?php echo e((isset($currentStep) && $currentStep >= 4) ? ($currentStep == 4 ? 'text-primary' : 'text-muted') : 'text-muted'); ?>" style="font-size: 0.7rem;">Payment</small>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="col text-center">
                                        <?php if(isset($booking) && $booking->access_token && $booking->hasCompletedPayments()): ?>
                                        <!-- Only visible if there's a completed payment -->
                                        <a href="<?php echo e(route('bookings.receipt', ['eventSlug' => $event->slug, 'accessToken' => $booking->boothOwner->access_token])); ?>" class="text-decoration-none">
                                            <div class="d-flex flex-column align-items-center">
                                                <span class="badge <?php echo e((isset($currentStep) && $currentStep >= 5) ? ($currentStep == 5 ? 'bg-primary' : 'bg-success') : 'bg-secondary'); ?> rounded-pill mb-1">5</span>
                                                <small class="<?php echo e((isset($currentStep) && $currentStep >= 5) ? ($currentStep == 5 ? 'text-primary' : 'text-success') : 'text-muted'); ?>" style="font-size: 0.7rem;">Receipt</small>
                                            </div>
                                        </a>
                                        <?php else: ?>
                                        <!-- 
                                        
                                        Show as disabled for new users or incomplete payments 
                                        <div class="d-flex flex-column align-items-center">
                                            <span class="badge bg-secondary rounded-pill mb-1">5</span>
                                            <small class="text-muted" style="font-size: 0.7rem;">Receipt</small>
                                        </div>
                                        
                                        -->
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Flash Messages -->
        <?php if(session('success') || session('error') || session('warning') || session('info')): ?>
        <div class="py-3">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <?php if(session('success')): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle me-2"></i>
                            <?php echo e(session('success')); ?>

                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        <?php endif; ?>

                        <?php if(session('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            <?php echo e(session('error')); ?>

                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        <?php endif; ?>

                        <?php if(session('warning')): ?>
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            <?php echo e(session('warning')); ?>

                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        <?php endif; ?>

                        <?php if(session('info')): ?>
                        <div class="alert alert-info alert-dismissible fade show" role="alert">
                            <i class="bi bi-info-circle me-2"></i>
                            <?php echo e(session('info')); ?>

                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Main Content -->
        <main>
            <?php echo $__env->yieldContent('content'); ?>
        </main>

        <!-- Public Footer -->
        <footer class="bg-white border-top mt-5">
            <div class="container-fluid py-4">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <?php echo $__env->yieldContent('footer-left'); ?>
                        <?php if(!View::hasSection('footer-left')): ?>
                        <p class="mb-0 text-muted">
                            <i class="bi bi-shield-check me-1"></i>
                            Your information is secure and will only be used for this booking
                        </p>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <?php if(isset($event)): ?>
                        <p class="mb-0 text-muted">
                            <i class="bi bi-calendar3 me-1"></i>
                            <?php echo e($event->start_date->format('M d, Y')); ?> - <?php echo e($event->end_date->format('M d, Y')); ?>

                        </p>
                        <small class="text-muted">Explore & Book Your Space</small>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </footer>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <?php echo $__env->yieldPushContent('scripts'); ?>
</body>

</html><?php /**PATH C:\xampp\htdocs\exhibition-app\resources\views/layouts/public.blade.php ENDPATH**/ ?>