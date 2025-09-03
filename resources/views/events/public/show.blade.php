<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $event->title }} - Exhibition Booking</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #667eea;
            --secondary-color: #764ba2;
            --success-color: #28a745;
            --info-color: #17a2b8;
            --warning-color: #ffc107;
            --danger-color: #dc3545;
        }
        
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .hero-section {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            padding: 5rem 0;
            position: relative;
            overflow: hidden;
        }
        
        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="75" cy="75" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="50" cy="10" r="0.5" fill="rgba(255,255,255,0.05)"/><circle cx="10" cy="60" r="0.5" fill="rgba(255,255,255,0.05)"/><circle cx="90" cy="40" r="0.5" fill="rgba(255,255,255,0.05)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            opacity: 0.3;
        }
        
        .hero-content {
            position: relative;
            z-index: 2;
        }
        
        .event-logo {
            width: 140px;
            height: 140px;
            object-fit: cover;
            border-radius: 20px;
            border: 4px solid rgba(255,255,255,0.3);
            box-shadow: 0 8px 32px rgba(0,0,0,0.2);
        }
        
        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.9rem;
            margin-bottom: 1rem;
        }
        
        .status-upcoming {
            background: rgba(40, 167, 69, 0.2);
            color: #28a745;
            border: 1px solid rgba(40, 167, 69, 0.3);
        }
        
        .status-active {
            background: rgba(23, 162, 184, 0.2);
            color: #17a2b8;
            border: 1px solid rgba(23, 162, 184, 0.3);
        }
        
        .status-completed {
            background: rgba(108, 117, 125, 0.2);
            color: #6c757d;
            border: 1px solid rgba(108, 117, 125, 0.3);
        }
        
        .feature-card {
            background: white;
            border-radius: 16px;
            padding: 2.5rem 2rem;
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            height: 100%;
            border: 1px solid rgba(255,255,255,0.2);
            position: relative;
            overflow: hidden;
        }
        
        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
        }
        
        .feature-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 16px 48px rgba(0,0,0,0.15);
        }
        
        .feature-icon {
            font-size: 3.5rem;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 1.5rem;
        }
        
        .cta-section {
            background: linear-gradient(135deg, var(--success-color) 0%, #20c997 100%);
            color: white;
            padding: 4rem 0;
            margin: 4rem 0;
            position: relative;
            overflow: hidden;
        }
        
        .cta-section::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: float 6s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translate(0, 0) rotate(0deg); }
            50% { transform: translate(-20px, -20px) rotate(180deg); }
        }
        
        .btn-custom {
            padding: 1.2rem 2.5rem;
            font-size: 1.2rem;
            font-weight: 700;
            border-radius: 12px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            position: relative;
            z-index: 2;
            border: none;
            box-shadow: 0 4px 16px rgba(0,0,0,0.2);
        }
        
        .btn-custom:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 24px rgba(0,0,0,0.3);
        }
        
        .btn-primary-custom {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
        }
        
        .btn-success-custom {
            background: linear-gradient(135deg, var(--success-color), #20c997);
            color: white;
        }
        
        .progress-steps {
            background: white;
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
            margin: 2rem 0;
        }
        
        .step-item {
            display: flex;
            align-items: center;
            padding: 1rem 0;
            border-bottom: 1px solid #e9ecef;
        }
        
        .step-item:last-child {
            border-bottom: none;
        }
        
        .step-number {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            margin-right: 1rem;
            flex-shrink: 0;
        }
        
        .step-content h6 {
            margin: 0;
            color: #333;
            font-weight: 600;
        }
        
        .step-content p {
            margin: 0.25rem 0 0 0;
            color: #666;
            font-size: 0.9rem;
        }
        
        .info-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
            border: 1px solid rgba(255,255,255,0.2);
            overflow: hidden;
        }
        
        .info-card .card-header {
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            border-bottom: 1px solid #dee2e6;
            padding: 1.5rem;
        }
        
        .info-card .card-body {
            padding: 2rem;
        }
        
        .contact-card {
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            border-radius: 16px;
            padding: 2rem;
            text-align: center;
        }
        
        .contact-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin: 0 auto 1rem;
        }
        
        @media (max-width: 768px) {
            .hero-section {
            padding: 3rem 0;
            }
            
            .event-logo {
                width: 100px;
                height: 100px;
                margin-top: 2rem;
            }
            
            .feature-card {
                padding: 2rem 1.5rem;
                margin-bottom: 1.5rem;
        }
        
        .btn-custom {
            padding: 1rem 2rem;
            font-size: 1.1rem;
        }
        }
    </style>
</head>
<body>
    <!-- Hero Section -->
    <div class="hero-section">
        <div class="container">
            <div class="row align-items-center hero-content">
                <div class="col-md-8">
                    <!-- Event Status Badge -->
                    <div class="status-badge status-{{ $event->status }}">
                        <i class="bi bi-{{ $event->status === 'upcoming' ? 'clock' : ($event->status === 'active' ? 'play-circle' : 'check-circle') }} me-2"></i>
                        @if($event->isUpcoming())
                            Event Starting Soon
                        @elseif($event->isActive())
                            Event Live Now
                        @elseif($event->isCompleted())
                            Event Completed
                        @endif
                    </div>
                    
                    <h1 class="display-4 fw-bold mb-3">{{ $event->title }}</h1>
                    <p class="lead mb-4">{{ $event->description }}</p>
                    
                    <!-- Event Details -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                        <i class="bi bi-calendar-event me-3 fs-4"></i>
                                <div>
                                    <div class="fw-semibold">Event Dates</div>
                                    <div class="text-white-50">{{ $event->start_date->format('F d, Y') }} - {{ $event->end_date->format('F d, Y') }}</div>
                                </div>
                            </div>
                    </div>
                        <div class="col-md-6">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-clock me-3 fs-4"></i>
                                <div>
                                    <div class="fw-semibold">Event Times</div>
                                    <div class="text-white-50">{{ $event->start_date->format('g:i A') }} - {{ $event->end_date->format('g:i A') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Quick Action Button -->
                    <div class="d-flex gap-3">
                        <a href="{{ route('events.public.floorplan', $event) }}" class="btn-custom btn-success-custom">
                            <i class="bi bi-map"></i>
                            View Floorplan & Book Now
                        </a>
                        @if($event->isUpcoming())
                        <button class="btn-custom btn-primary-custom" onclick="scrollToFeatures()">
                            <i class="bi bi-info-circle"></i>
                            Learn More
                        </button>
                        @endif
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

    <!-- How It Works Section -->
    <div class="container" id="features">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold mb-3">How It Works</h2>
            <p class="lead text-muted">Get started in just a few simple steps</p>
        </div>
        
        <div class="progress-steps">
            <div class="step-item">
                <div class="step-number">1</div>
                <div class="step-content">
                    <h6>Explore the Floorplan</h6>
                    <p>Browse our interactive floorplan to see available booths and their locations</p>
                </div>
            </div>
            <div class="step-item">
                <div class="step-number">2</div>
                <div class="step-content">
                    <h6>Select Your Booth</h6>
                    <p>Click on any available booth to view details and pricing information</p>
                </div>
            </div>
            <div class="step-item">
                <div class="step-number">3</div>
                <div class="step-content">
                    <h6>Complete Registration</h6>
                    <p>Fill out your company details and team member information</p>
                </div>
            </div>
            <div class="step-item">
                <div class="step-number">4</div>
                <div class="step-content">
                    <h6>Secure Payment</h6>
                    <p>Make a secure payment and receive instant confirmation of your booking</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Event Features -->
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold mb-3">Why Choose Our Platform?</h2>
            <p class="lead text-muted">Experience the best in exhibition management</p>
        </div>
        
        <div class="row g-4 mb-5">
            <div class="col-md-4">
                <div class="feature-card text-center">
                    <div class="feature-icon">
                        <i class="bi bi-geo-alt"></i>
                    </div>
                    <h4>Interactive Floorplan</h4>
                    <p class="text-muted">Explore our detailed exhibition layout with real-time availability and interactive booth selection.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card text-center">
                    <div class="feature-icon">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <h4>Team Management</h4>
                    <p class="text-muted">Easily manage your booth team with member registration, QR codes, and contact management.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card text-center">
                    <div class="feature-icon">
                        <i class="bi bi-shield-check"></i>
                    </div>
                    <h4>Secure Payments</h4>
                    <p class="text-muted">Safe and secure payment processing with instant confirmation and receipt generation.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Call to Action Section -->
    <div class="cta-section">
        <div class="container text-center">
            <h2 class="display-5 fw-bold mb-4">Ready to Book Your Space?</h2>
            <p class="lead mb-4">Join hundreds of exhibitors at this amazing event. Secure your booth today!</p>
            <div class="d-flex justify-content-center gap-3 flex-wrap">
            <a href="{{ route('events.public.floorplan', $event) }}" class="btn-custom btn btn-light">
                    <i class="bi bi-map"></i>
                    View Floorplan & Book Now
            </a>
                <button class="btn-custom btn btn-outline-light" onclick="scrollToContact()">
                    <i class="bi bi-question-circle"></i>
                    Need Help?
                </button>
            </div>
        </div>
    </div>

    <!-- Event Details -->
    <div class="container mb-5" id="contact">
        <div class="row">
            <div class="col-md-8">
                <div class="info-card">
                    <div class="card-header">
                        <h4 class="mb-0"><i class="bi bi-info-circle me-2"></i>Event Information</h4>
                    </div>
                    <div class="card-body">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="d-flex align-items-start">
                                    <div class="flex-shrink-0">
                                        <div class="contact-icon" style="width: 40px; height: 40px; font-size: 1rem;">
                                            <i class="bi bi-calendar-event"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-1">Event Dates</h6>
                                        <p class="mb-0 text-muted">{{ $event->start_date->format('F d, Y') }} - {{ $event->end_date->format('F d, Y') }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-start">
                                    <div class="flex-shrink-0">
                                        <div class="contact-icon" style="width: 40px; height: 40px; font-size: 1rem;">
                                            <i class="bi bi-clock"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-1">Event Times</h6>
                                        <p class="mb-0 text-muted">{{ $event->start_date->format('g:i A') }} - {{ $event->end_date->format('g:i A') }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-start">
                                    <div class="flex-shrink-0">
                                        <div class="contact-icon" style="width: 40px; height: 40px; font-size: 1rem;">
                                            <i class="bi bi-calendar-range"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-1">Duration</h6>
                                        <p class="mb-0 text-muted">{{ $event->duration_in_days }} day(s)</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-start">
                                    <div class="flex-shrink-0">
                                        <div class="contact-icon" style="width: 40px; height: 40px; font-size: 1rem;">
                                            <i class="bi bi-{{ $event->status === 'upcoming' ? 'clock' : ($event->status === 'active' ? 'play-circle' : 'check-circle') }}"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-1">Event Status</h6>
                                        <p class="mb-0">
                                    @if($event->isUpcoming())
                                                <span class="badge bg-success">Coming Soon</span>
                                    @elseif($event->isActive())
                                                <span class="badge bg-primary">Live Now</span>
                                    @elseif($event->isCompleted())
                                                <span class="badge bg-secondary">Completed</span>
                                    @endif
                                </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="contact-card">
                    <div class="contact-icon">
                        <i class="bi bi-headset"></i>
                    </div>
                    <h5 class="mb-3">Need Help?</h5>
                    <p class="text-muted mb-4">Have questions about booking or the event? Our support team is here to help you every step of the way.</p>
                    <div class="d-grid gap-3">
                            <a href="mailto:support@example.com" class="btn btn-outline-primary">
                            <i class="bi bi-envelope me-2"></i>Email Support
                            </a>
                            <a href="tel:+1234567890" class="btn btn-outline-secondary">
                                <i class="bi bi-telephone me-2"></i>Call Us
                            </a>
                        <a href="{{ route('events.public.floorplan', $event) }}" class="btn btn-primary">
                            <i class="bi bi-map me-2"></i>Start Booking
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-light py-5 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5 class="mb-3">{{ $event->title }}</h5>
                    <p class="text-light-emphasis mb-3">{{ $event->description }}</p>
                    <div class="d-flex gap-3">
                        <a href="{{ route('events.public.floorplan', $event) }}" class="btn btn-outline-light btn-sm">
                            <i class="bi bi-map me-1"></i>View Floorplan
                        </a>
                        <a href="mailto:support@example.com" class="btn btn-outline-light btn-sm">
                            <i class="bi bi-envelope me-1"></i>Contact
                        </a>
                    </div>
                </div>
                <div class="col-md-6 text-md-end">
                    <h6 class="mb-3">Event Information</h6>
                    <p class="mb-1"><i class="bi bi-calendar-event me-2"></i>{{ $event->start_date->format('F d, Y') }} - {{ $event->end_date->format('F d, Y') }}</p>
                    <p class="mb-1"><i class="bi bi-clock me-2"></i>{{ $event->start_date->format('g:i A') }} - {{ $event->end_date->format('g:i A') }}</p>
                    <p class="mb-0"><i class="bi bi-calendar-range me-2"></i>{{ $event->duration_in_days }} day(s)</p>
                </div>
            </div>
            <hr class="my-4">
            <div class="text-center">
            <p class="mb-0">&copy; {{ date('Y') }} {{ $event->title }}. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Smooth scrolling functions
        function scrollToFeatures() {
            document.getElementById('features').scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
        
        function scrollToContact() {
            document.getElementById('contact').scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
        
        // Add scroll effect to hero section
        window.addEventListener('scroll', function() {
            const hero = document.querySelector('.hero-section');
            const scrolled = window.pageYOffset;
            const parallax = scrolled * 0.5;
            hero.style.transform = `translateY(${parallax}px)`;
        });
        
        // Add animation to feature cards on scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };
        
        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);
        
        // Observe feature cards
        document.querySelectorAll('.feature-card').forEach(card => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(30px)';
            card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            observer.observe(card);
        });
        
        // Observe step items
        document.querySelectorAll('.step-item').forEach((item, index) => {
            item.style.opacity = '0';
            item.style.transform = 'translateX(-30px)';
            item.style.transition = `opacity 0.6s ease ${index * 0.1}s, transform 0.6s ease ${index * 0.1}s`;
            observer.observe(item);
        });
    </script>
</body>
</html>
