<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - {{ $event->name ?? 'Exhibition' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    @stack('styles')
</head>
<body class="bg-light">
    <!-- Header -->
    <header class="bg-white shadow-sm border-bottom">
        <div class="container-fluid py-3">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        <div class="bg-primary rounded d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            @if($event->logo)
                                <img src="{{ Storage::url($event->logo) }}" alt="{{ $event->title }}" class="rounded" style="width: 50px; height: 50px; object-fit: cover;">
                            @else
                                <i class="bi bi-calendar-event text-white fs-4"></i>
                            @endif
                        </div>
                    </div>
                    <div>
                        <h1 class="h4 mb-1 fw-bold text-primary">{{ $event->title }}</h1>
                        <p class="mb-0 text-muted">
                            <i class="bi bi-calendar3 me-1"></i>
                            {{ $event->start_date->format('M d, Y') }} - {{ $event->end_date->format('M d, Y') }}
                        </p>
                    </div>
                </div>
                <div class="text-end">
                    <span class="badge bg-success fs-6 px-3 py-2">Interactive Floorplan</span>
                </div>
            </div>
        </div>
    </header>

    <!-- Progress Steps (if provided) -->
    @isset($showProgress)
        <div class="py-2 bg-light border-bottom">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <!-- Desktop Progress -->
                        <div class="d-none d-lg-block">
                            <div class="d-flex align-items-center justify-content-center gap-3">
                                    <!-- Step 1: Select Space -->
                                 @if(isset($booking) && $booking->access_token)
                                     <!-- Always clickable if access token exists -->
                                     <a href="{{ route('events.public.floorplan-token', ['event' => $event->slug, 'accessToken' => $booking->access_token]) }}" class="text-decoration-none">
                                         <div class="d-flex align-items-center">
                                             <span class="badge {{ $currentStep == 1 ? 'bg-primary' : 'bg-success' }} rounded-pill me-2">1</span>
                                             <small class="{{ $currentStep == 1 ? 'text-primary fw-medium' : 'text-success' }}">Select Space</small>
                                         </div>
                                     </a>
                                 @else
                                     <div class="d-flex align-items-center">
                                         <span class="badge bg-secondary rounded-pill me-2">1</span>
                                         <small class="text-muted">Select Space</small>
                                     </div>
                                 @endif
                                
                                <i class="bi bi-arrow-right text-muted"></i>
                                
                                <!-- Step 2: Owner Details -->
                                 @if(isset($booking) && $booking->access_token)
                                     <!-- Always clickable if access token exists -->
                                     <a href="{{ route('bookings.owner-form-token', ['eventSlug' => $event->slug, 'accessToken' => $booking->access_token]) }}" class="text-decoration-none">
                                         <div class="d-flex align-items-center">
                                             <span class="badge {{ $currentStep == 2 ? 'bg-primary' : 'bg-success' }} rounded-pill me-2">2</span>
                                             <small class="{{ $currentStep == 2 ? 'text-primary fw-medium' : 'text-success' }}">Owner Details</small>
                                         </div>
                                     </a>
                                 @else
                                     <div class="d-flex align-items-center">
                                         <span class="badge bg-secondary rounded-pill me-2">2</span>
                                         <small class="text-muted">Owner Details</small>
                                     </div>
                                 @endif
                                
                                <i class="bi bi-arrow-right text-muted"></i>
                                
                                                                 <!-- Step 3: Add Members -->
                                 @if(isset($booking) && $booking->access_token)
                                     <!-- Always clickable if access token exists -->
                                     <a href="{{ route('bookings.member-form', ['eventSlug' => $event->slug, 'accessToken' => $booking->access_token]) }}" class="text-decoration-none">
                                         <div class="d-flex align-items-center">
                                             <span class="badge {{ $currentStep == 3 ? 'bg-primary' : 'bg-success' }} rounded-pill me-2">3</span>
                                             <small class="{{ $currentStep == 3 ? 'text-primary fw-medium' : 'text-success' }}">Add Members</small>
                                         </div>
                                     </a>
                                 @else
                                     <div class="d-flex align-items-center">
                                         <span class="badge bg-secondary rounded-pill me-2">3</span>
                                         <small class="text-muted">Add Members</small>
                                     </div>
                                 @endif
                                
                                <i class="bi bi-arrow-right text-muted"></i>
                                
                                                                 <!-- Step 4: Payment -->
                                 @if(isset($booking) && $booking->access_token)
                                     <!-- Always clickable if access token exists -->
                                     <a href="{{ route('bookings.payment', ['eventSlug' => $event->slug, 'accessToken' => $booking->access_token]) }}" class="text-decoration-none">
                                         <div class="d-flex align-items-center">
                                             <span class="badge {{ $currentStep == 4 ? 'bg-primary' : 'bg-success' }} rounded-pill me-2">4</span>
                                             <small class="{{ $currentStep == 4 ? 'text-primary fw-medium' : 'text-success' }}">Payment</small>
                                         </div>
                                     </a>
                                 @else
                                     <div class="d-flex align-items-center">
                                         <span class="badge bg-secondary rounded-pill me-2">4</span>
                                         <small class="text-muted">Payment</small>
                                     </div>
                                 @endif
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
                                        <div class="col-3 text-center">
                                            @if(isset($booking) && $booking->access_token)
                                                <a href="{{ route('events.public.floorplan-token', ['event' => $event->slug, 'accessToken' => $booking->access_token]) }}" class="text-decoration-none">
                                                    <div class="d-flex flex-column align-items-center">
                                                        <span class="badge {{ (isset($currentStep) && $currentStep >= 1) ? ($currentStep == 1 ? 'bg-primary' : 'bg-success') : 'bg-secondary' }} rounded-pill mb-1">1</span>
                                                        <small class="{{ (isset($currentStep) && $currentStep >= 1) ? ($currentStep == 1 ? 'text-primary' : 'text-success') : 'text-muted' }}" style="font-size: 0.7rem;">Select</small>
                                                    </div>
                                                </a>
                                            @else
                                                <div class="d-flex flex-column align-items-center">
                                                    <span class="badge {{ (isset($currentStep) && $currentStep >= 1) ? ($currentStep == 1 ? 'bg-primary' : 'bg-success') : 'bg-secondary' }} rounded-pill mb-1">1</span>
                                                    <small class="{{ (isset($currentStep) && $currentStep == 1) ? 'text-primary' : 'text-muted' }}" style="font-size: 0.7rem;">Select</small>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="col-3 text-center">
                                            <div class="d-flex flex-column align-items-center">
                                                <span class="badge {{ (isset($currentStep) && $currentStep >= 2) ? ($currentStep == 2 ? 'bg-primary' : 'bg-success') : 'bg-secondary' }} rounded-pill mb-1">2</span>
                                                <small class="{{ (isset($currentStep) && $currentStep >= 2) ? ($currentStep == 2 ? 'text-primary' : 'text-success') : 'text-muted' }}" style="font-size: 0.7rem;">Details</small>
                                            </div>
                                        </div>
                                        <div class="col-3 text-center">
                                            <div class="d-flex flex-column align-items-center">
                                                <span class="badge {{ (isset($currentStep) && $currentStep >= 3) ? ($currentStep == 3 ? 'bg-primary' : 'bg-success') : 'bg-secondary' }} rounded-pill mb-1">3</span>
                                                <small class="{{ (isset($currentStep) && $currentStep >= 3) ? ($currentStep == 3 ? 'text-primary' : 'text-success') : 'text-muted' }}" style="font-size: 0.7rem;">Members</small>
                                            </div>
                                        </div>
                                        <div class="col-3 text-center">
                                            <div class="d-flex flex-column align-items-center">
                                                <span class="badge {{ (isset($currentStep) && $currentStep >= 4) ? 'bg-primary' : 'bg-secondary' }} rounded-pill mb-1">4</span>
                                                <small class="{{ (isset($currentStep) && $currentStep >= 4) ? 'text-primary' : 'text-muted' }}" style="font-size: 0.7rem;">Payment</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endisset

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Public Footer -->
    <footer class="bg-white border-top mt-5">
        <div class="container-fluid py-4">
            <div class="row align-items-center">
                <div class="col-md-6">
                    @yield('footer-left')
                    @if(!View::hasSection('footer-left'))
                        <p class="mb-0 text-muted">
                            <i class="bi bi-shield-check me-1"></i>
                            Your information is secure and will only be used for this booking
                        </p>
                    @endif
                </div>
                <div class="col-md-6 text-md-end">
                    @if(isset($event))
                        <p class="mb-0 text-muted">
                            <i class="bi bi-calendar3 me-1"></i>
                            {{ $event->start_date->format('M d, Y') }} - {{ $event->end_date->format('M d, Y') }}
                        </p>
                        <small class="text-muted">Explore & Book Your Space</small>
                    @endif
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
