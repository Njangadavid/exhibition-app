<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

        <title><?php echo e(config('app.name', 'Exhibition App')); ?></title>

        <!-- Favicon -->
        <link rel="icon" type="image/x-icon" href="/images/sajili-5.png">
        <link rel="icon" type="image/png" sizes="32x32" href="/images/sajili-5.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/images/sajili-5.png">
        <link rel="apple-touch-icon" sizes="180x180" href="/images/sajili-5.png">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        
        <!-- Bootstrap Icons -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
        
        <!-- Scripts -->
        <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
        
        <style>
            :root {
                --primary-color: #2563eb;
                --primary-dark: #1d4ed8;
                --secondary-color: #64748b;
                --accent-color: #f59e0b;
                --success-color: #10b981;
                --warning-color: #f59e0b;
                --error-color: #ef4444;
                --text-primary: #1e293b;
                --text-secondary: #64748b;
                --text-light: #94a3b8;
                --bg-primary: #ffffff;
                --bg-secondary: #f8fafc;
                --bg-accent: #f1f5f9;
                --border-color: #e2e8f0;
                --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
                --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
                --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
                --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
            }
            
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }
            
            body {
                font-family: 'Figtree', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
                line-height: 1.6;
                color: var(--text-primary);
                background-color: var(--bg-primary);
            }
            
            .header {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                background: rgba(255, 255, 255, 0.95);
                backdrop-filter: blur(10px);
                border-bottom: 1px solid var(--border-color);
                z-index: 1000;
                transition: all 0.3s ease;
            }
            
            .header.scrolled {
                background: rgba(255, 255, 255, 0.98);
                box-shadow: var(--shadow-md);
            }
            
            .header-content {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 1rem 2rem;
                max-width: 1200px;
                margin: 0 auto;
            }
            
            .logo {
                display: flex;
                align-items: center;
                gap: 12px;
                text-decoration: none;
                color: var(--text-primary);
                font-size: 1.5rem;
                font-weight: 700;
                transition: all 0.3s ease;
            }
            
            .logo img {
                height: 40px;
                width: auto;
                transition: transform 0.3s ease;
            }
            
            .logo:hover img {
                transform: scale(1.05);
            }
            
            .logo:hover {
                color: var(--primary-color);
                transform: translateY(-1px);
            }
            
            .nav-buttons {
                display: flex;
                gap: 1rem;
                align-items: center;
            }
            
            .btn {
                display: inline-flex;
                align-items: center;
                gap: 0.5rem;
                padding: 0.75rem 1.5rem;
                border-radius: 0.5rem;
                font-weight: 500;
                text-decoration: none;
                transition: all 0.3s ease;
                border: none;
                cursor: pointer;
                font-size: 0.875rem;
            }
            
            .btn-primary {
                background: var(--text-secondary);
                color: white;
                border: 1px solid var(--border-color);
                box-shadow: var(--shadow-sm);
            }
            
            .btn-primary:hover {
                background: var(--text-primary);
                transform: translateY(-1px);
                box-shadow: var(--shadow-md);
            }
            
            .btn-outline {
                background: white;
                color: var(--primary-color);
                border: 2px solid white;
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
                font-weight: 600;
                transition: all 0.3s ease;
            }
            
            .btn-outline:hover {
                background: transparent;
                color: white;
                border-color: white;
                transform: translateY(-2px);
                box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
            }
            
            .btn-secondary {
                background: var(--bg-secondary);
                color: var(--text-secondary);
                border: 1px solid var(--border-color);
            }
            
            .btn-secondary:hover {
                background: var(--bg-accent);
                transform: translateY(-1px);
            }
            
            .hero-section {
                background: linear-gradient(135deg, var(--primary-color) 0%, #1e40af 50%, #1e3a8a 100%);
                color: white;
                padding: 120px 20px 80px;
                text-align: center;
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
                background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="1" fill="rgba(255,255,255,0.1)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
                opacity: 0.1;
            }
            
            .hero-content {
                position: relative;
                z-index: 1;
                max-width: 900px;
                margin: 0 auto;
            }
            
            .hero-title {
                font-size: clamp(2.5rem, 5vw, 4rem);
                font-weight: 700;
                margin-bottom: 1.5rem;
                line-height: 1.2;
            }
            
            .hero-subtitle {
                font-size: clamp(1.125rem, 2.5vw, 1.375rem);
                margin-bottom: 2.5rem;
                opacity: 0.95;
                font-weight: 400;
                max-width: 600px;
                margin-left: auto;
                margin-right: auto;
            }
            
            .cta-buttons {
                display: flex;
                gap: 1rem;
                justify-content: center;
                flex-wrap: wrap;
            }
            
            .features-section {
                padding: 80px 20px;
                background: var(--bg-secondary);
            }
            
            .section-header {
                text-align: center;
                margin-bottom: 4rem;
            }
            
            .section-title {
                font-size: clamp(2rem, 4vw, 2.5rem);
                font-weight: 700;
                color: var(--text-primary);
                margin-bottom: 1rem;
            }
            
            .section-subtitle {
                font-size: 1.125rem;
                color: var(--text-secondary);
                max-width: 600px;
                margin: 0 auto;
            }
            
            .features-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
                gap: 2rem;
                max-width: 1200px;
                margin: 0 auto;
            }
            
            .feature-card {
                background: var(--bg-primary);
                border-radius: 1rem;
                padding: 2.5rem 2rem;
                box-shadow: var(--shadow-sm);
                transition: all 0.3s ease;
                border: 1px solid var(--border-color);
                text-align: center;
            }
            
            .feature-card:hover {
                transform: translateY(-8px);
                box-shadow: var(--shadow-xl);
                border-color: var(--primary-color);
            }
            
            .feature-icon {
                font-size: 3rem;
                margin-bottom: 1.5rem;
                display: block;
            }
            
            .feature-title {
                font-size: 1.375rem;
                font-weight: 600;
                margin-bottom: 1rem;
                color: var(--text-primary);
            }
            
            .feature-description {
                color: var(--text-secondary);
                line-height: 1.7;
                font-size: 0.95rem;
            }
            
            .stats-section {
                padding: 80px 20px;
                background: var(--bg-primary);
            }
            
            .stats-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
                gap: 2rem;
                max-width: 800px;
                margin: 0 auto;
            }
            
            .stat-item {
                text-align: center;
                padding: 2rem 1rem;
            }
            
            .stat-number {
                font-size: 3rem;
                font-weight: 700;
                color: var(--primary-color);
                margin-bottom: 0.5rem;
            }
            
            .stat-label {
                color: var(--text-secondary);
                font-weight: 500;
            }
            
            .cta-section {
                padding: 80px 20px;
                background: linear-gradient(135deg, var(--bg-accent) 0%, var(--bg-secondary) 100%);
                text-align: center;
            }
            
            .cta-content {
                max-width: 600px;
                margin: 0 auto;
            }
            
            .cta-title {
                font-size: 2rem;
                font-weight: 700;
                color: var(--text-primary);
                margin-bottom: 1rem;
            }
            
            .cta-description {
                color: var(--text-secondary);
                margin-bottom: 2rem;
                font-size: 1.125rem;
            }
            
            .footer {
                background: var(--text-primary);
                color: white;
                padding: 3rem 20px 2rem;
                text-align: center;
            }
            
            .footer-content {
                max-width: 1200px;
                margin: 0 auto;
            }
            
            .footer-links {
                display: flex;
                justify-content: center;
                gap: 2rem;
                margin-bottom: 2rem;
                flex-wrap: wrap;
            }
            
            .footer-link {
                color: var(--text-light);
                text-decoration: none;
                transition: color 0.3s ease;
            }
            
            .footer-link:hover {
                color: white;
            }
            
            .footer-bottom {
                border-top: 1px solid var(--text-secondary);
                padding-top: 2rem;
                color: var(--text-light);
            }
            
            @media (max-width: 768px) {
                .header-content {
                    padding: 1rem;
                }
                
                .hero-section {
                    padding: 100px 20px 60px;
                }
                
                .cta-buttons {
                    flex-direction: column;
                    align-items: center;
                }
                
                .features-grid {
                    grid-template-columns: 1fr;
                }
                
                .stats-grid {
                    grid-template-columns: repeat(2, 1fr);
                }
                
                .footer-links {
                    flex-direction: column;
                    gap: 1rem;
                }
            }
        </style>
    </head>
    <body>
        <header class="header" id="header">
            <div class="header-content">
                <a href="/" class="logo">
                    <img src="/images/sajili-5.png" alt="ExhibitHub" style="height: 40px; width: auto;">
                    ExhibitHub
                </a>
                <div class="nav-buttons">
                    <?php if(Route::has('login')): ?>
                        <?php if(auth()->guard()->check()): ?>
                            <a href="<?php echo e(url('/dashboard')); ?>" class="btn btn-primary">
                                <i class="bi bi-speedometer2"></i>
                                Dashboard
                            </a>
                        <?php else: ?>
                            <a href="<?php echo e(route('login')); ?>" class="btn btn-primary">
                                <i class="bi bi-box-arrow-in-right"></i>
                                Sign In
                            </a>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </header>

        <section class="hero-section">
            <div class="hero-content">
                <h1 class="hero-title">Transform Your Exhibitions</h1>
                <p class="hero-subtitle">Professional exhibition management platform for artists, galleries, and event organizers. Create stunning showcases, manage bookings, and connect with audiences worldwide.</p>
                <div class="cta-buttons">
                    <?php if(auth()->guard()->check()): ?>
                        <a href="<?php echo e(url('/dashboard')); ?>" class="btn btn-outline">
                            <i class="bi bi-speedometer2"></i>
                            Go to Dashboard
                        </a>
                    <?php else: ?>
                        <a href="<?php echo e(route('login')); ?>" class="btn btn-outline btn-light">
                            <i class="bi bi-rocket-takeoff"></i>
                            Get Started
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </section>

        <section class="features-section">
            <div class="section-header">
                <h2 class="section-title">Why Choose Exhibition App?</h2>
                <p class="section-subtitle">Built for modern exhibition management with powerful features that streamline your workflow</p>
            </div>
            <div class="features-grid">
                <div class="feature-card">
                    <i class="bi bi-grid-3x3-gap-fill feature-icon" style="color: var(--primary-color);"></i>
                    <h3 class="feature-title">Smart Booth Management</h3>
                    <p class="feature-description">Easily manage booth assignments, track availability, and handle complex floorplan layouts with our intuitive interface.</p>
                </div>
                
                <div class="feature-card">
                    <i class="bi bi-people-fill feature-icon" style="color: var(--success-color);"></i>
                    <h3 class="feature-title">Member Registration</h3>
                    <p class="feature-description">Streamlined member management with custom forms, QR codes for check-ins, and automated email communications.</p>
                </div>
                
                <div class="feature-card">
                    <i class="bi bi-credit-card-fill feature-icon" style="color: var(--accent-color);"></i>
                    <h3 class="feature-title">Payment Processing</h3>
                    <p class="feature-description">Secure payment handling with Paystack integration, automated receipts, and comprehensive financial tracking.</p>
                </div>
                
                <div class="feature-card">
                    <i class="bi bi-envelope-fill feature-icon" style="color: var(--warning-color);"></i>
                    <h3 class="feature-title">Email Automation</h3>
                    <p class="feature-description">Automated email workflows for registrations, confirmations, and updates with customizable templates.</p>
                </div>
                
                <div class="feature-card">
                    <i class="bi bi-phone-fill feature-icon" style="color: var(--primary-color);"></i>
                    <h3 class="feature-title">Mobile Responsive</h3>
                    <p class="feature-description">Fully responsive design that works perfectly on all devices, from desktop to mobile phones.</p>
                </div>
                
                <div class="feature-card">
                    <i class="bi bi-shield-check feature-icon" style="color: var(--success-color);"></i>
                    <h3 class="feature-title">Enterprise Security</h3>
                    <p class="feature-description">Built with modern security standards, secure access tokens, and robust data protection measures.</p>
                </div>
            </div>
        </section>

        <section class="stats-section">
            <div class="section-header">
                <h2 class="section-title">Trusted by Exhibition Professionals</h2>
                <p class="section-subtitle">Join thousands of organizers who trust our platform</p>
            </div>
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-number">500+</div>
                    <div class="stat-label">Exhibitions Managed</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">10K+</div>
                    <div class="stat-label">Booths Booked</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">50K+</div>
                    <div class="stat-label">Members Registered</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">99.9%</div>
                    <div class="stat-label">Uptime</div>
                </div>
            </div>
        </section>

        <section class="cta-section">
            <div class="cta-content">
                <h2 class="cta-title">Ready to Get Started?</h2>
                <p class="cta-description">Join the community of exhibition professionals and transform how you manage your events.</p>
                <?php if(auth()->guard()->check()): ?>
                    <a href="<?php echo e(url('/dashboard')); ?>" class="btn btn-primary">
                        <i class="bi bi-speedometer2"></i>
                        Access Dashboard
                    </a>
                <?php else: ?>
                    <a href="<?php echo e(route('login')); ?>" class="btn btn-primary">
                        <i class="bi bi-box-arrow-in-right"></i>
                        Sign In Now
                    </a>
                <?php endif; ?>
            </div>
        </section>

        <footer class="footer">
            <div class="footer-content">
                <div class="footer-links">
                    <a href="#" class="footer-link">About Us</a>
                    <a href="#" class="footer-link">Features</a>
                    <a href="#" class="footer-link">Support</a>
                    <a href="#" class="footer-link">Contact</a>
                </div>
                <div class="footer-bottom">
                    <p>&copy; <?php echo e(date('Y')); ?> Exhibition App. All rights reserved.</p>
                </div>
            </div>
        </footer>

        <script>
            // Header scroll effect
            window.addEventListener('scroll', function() {
                const header = document.getElementById('header');
                if (window.scrollY > 50) {
                    header.classList.add('scrolled');
                } else {
                    header.classList.remove('scrolled');
                }
            });
            
            // Smooth scrolling for navigation
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });
        </script>
    </body>
</html>
<?php /**PATH C:\xampp\htdocs\exhibition-app\resources\views/welcome.blade.php ENDPATH**/ ?>