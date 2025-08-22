<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $event->title }}</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    
    <style>
        body {
            background-color: #f8f9fa;
        }
        
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 4rem 0;
            margin-bottom: 3rem;
        }
        
        .event-logo {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 50%;
            border: 4px solid rgba(255,255,255,0.3);
        }
        
        .feature-card {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: transform 0.2s ease-in-out;
            height: 100%;
        }
        
        .feature-card:hover {
            transform: translateY(-5px);
        }
        
        .feature-icon {
            font-size: 3rem;
            color: #667eea;
            margin-bottom: 1rem;
        }
        
        .cta-section {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            padding: 3rem 0;
            margin: 3rem 0;
        }
        
        .btn-custom {
            padding: 1rem 2rem;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 8px;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
        }
        
        .btn-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        }
    </style>
</head>
<body>
    <!-- Hero Section -->
    <div class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="display-4 fw-bold mb-3">{{ $event->title }}</h1>
                    <p class="lead mb-4">{{ $event->description }}</p>
                    <div class="d-flex align-items-center mb-3">
                        <i class="bi bi-calendar-event me-3 fs-4"></i>
                        <span class="fs-5">{{ $event->start_date->format('F d, Y') }} - {{ $event->end_date->format('F d, Y') }}</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <i class="bi bi-clock me-3 fs-4"></i>
                        <span class="fs-5">{{ $event->start_date->format('g:i A') }} - {{ $event->end_date->format('g:i A') }}</span>
                    </div>
                </div>
                <div class="col-md-4 text-center">
                    @if($event->logo)
                        <img src="{{ Storage::url($event->logo) }}" alt="{{ $event->title }}" class="event-logo">
                    @else
                        <div class="event-logo bg-white bg-opacity-20 d-flex align-items-center justify-content-center">
                            <i class="bi bi-calendar-event text-white" style="font-size: 3rem;"></i>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Event Features -->
    <div class="container">
        <div class="row g-4 mb-5">
            <div class="col-md-4">
                <div class="feature-card text-center">
                    <div class="feature-icon">
                        <i class="bi bi-map"></i>
                    </div>
                    <h4>Interactive Floorplan</h4>
                    <p class="text-muted">Explore our exhibition layout and find the perfect spot for your booth or table.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card text-center">
                    <div class="feature-icon">
                        <i class="bi bi-people"></i>
                    </div>
                    <h4>Booth Management</h4>
                    <p class="text-muted">Easy booking system with team management and member registration.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card text-center">
                    <div class="feature-icon">
                        <i class="bi bi-credit-card"></i>
                    </div>
                    <h4>Secure Payment</h4>
                    <p class="text-muted">Safe and secure payment processing with instant confirmation.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Call to Action Section -->
    <div class="cta-section">
        <div class="container text-center">
            <h2 class="display-5 fw-bold mb-4">Ready to Book Your Space?</h2>
            <p class="lead mb-4">Explore our floorplan and secure your spot at this amazing event!</p>
            <a href="{{ route('events.public.floorplan', $event) }}" class="btn-custom btn btn-light">
                <i class="bi bi-map me-2"></i>View Floorplan & Book Now
            </a>
        </div>
    </div>

    <!-- Event Details -->
    <div class="container mb-5">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0"><i class="bi bi-info-circle me-2"></i>Event Details</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6><i class="bi bi-calendar-event me-2"></i>Event Dates</h6>
                                <p class="mb-3">{{ $event->start_date->format('F d, Y') }} - {{ $event->end_date->format('F d, Y') }}</p>
                                
                                <h6><i class="bi bi-clock me-2"></i>Event Times</h6>
                                <p class="mb-3">{{ $event->start_date->format('g:i A') }} - {{ $event->end_date->format('g:i A') }}</p>
                                
                                <h6><i class="bi bi-geo-alt me-2"></i>Event Status</h6>
                                <span class="badge bg-{{ $event->getStatusColorAttribute() }} fs-6">{{ ucfirst($event->status) }}</span>
                            </div>
                            <div class="col-md-6">
                                <h6><i class="bi bi-calendar-range me-2"></i>Duration</h6>
                                <p class="mb-3">{{ $event->duration_in_days }} day(s)</p>
                                
                                <h6><i class="bi bi-clock-history me-2"></i>Time Until Event</h6>
                                <p class="mb-3">
                                    @if($event->isUpcoming())
                                        <span class="text-success">Coming soon!</span>
                                    @elseif($event->isActive())
                                        <span class="text-primary">Happening now!</span>
                                    @elseif($event->isCompleted())
                                        <span class="text-muted">Event completed</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-question-circle me-2"></i>Need Help?</h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">Have questions about booking or the event? We're here to help!</p>
                        <div class="d-grid gap-2">
                            <a href="mailto:support@example.com" class="btn btn-outline-primary">
                                <i class="bi bi-envelope me-2"></i>Contact Support
                            </a>
                            <a href="tel:+1234567890" class="btn btn-outline-secondary">
                                <i class="bi bi-telephone me-2"></i>Call Us
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-light py-4 mt-5">
        <div class="container text-center">
            <p class="mb-0">&copy; {{ date('Y') }} {{ $event->title }}. All rights reserved.</p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
