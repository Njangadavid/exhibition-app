<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Exhibition App') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        
        <!-- Material Icons -->
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        
        <!-- Material Web Components -->
        <script type="module" src="https://unpkg.com/@material/web@1.0.0/index.js"></script>

        <!-- Scripts -->
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        
            <style>
            :root {
                --md-sys-color-primary: #6750a4;
                --md-sys-color-on-primary: #ffffff;
                --md-sys-color-primary-container: #eaddff;
                --md-sys-color-on-primary-container: #21005d;
                --md-sys-color-secondary: #625b71;
                --md-sys-color-on-secondary: #ffffff;
                --md-sys-color-secondary-container: #e8def8;
                --md-sys-color-on-secondary-container: #1d192b;
                --md-sys-color-surface: #fffbfe;
                --md-sys-color-on-surface: #1c1b1f;
                --md-sys-color-surface-variant: #e7e0ec;
                --md-sys-color-on-surface-variant: #49454f;
                --md-sys-color-outline: #79747e;
                --md-sys-color-outline-variant: #cac4d0;
                --md-sys-color-error: #ba1a1a;
                --md-sys-color-on-error: #ffffff;
                --md-sys-color-error-container: #ffdad6;
                --md-sys-color-on-error-container: #410002;
            }
            
            .dark {
                --md-sys-color-primary: #d0bcff;
                --md-sys-color-on-primary: #381e72;
                --md-sys-color-primary-container: #4f378b;
                --md-sys-color-on-primary-container: #eaddff;
                --md-sys-color-secondary: #ccc2dc;
                --md-sys-color-on-secondary: #332d41;
                --md-sys-color-secondary-container: #4a4458;
                --md-sys-color-on-secondary-container: #e8def8;
                --md-sys-color-surface: #1c1b1f;
                --md-sys-color-on-surface: #e6e1e5;
                --md-sys-color-surface-variant: #49454f;
                --md-sys-color-on-surface-variant: #cac4d0;
                --md-sys-color-outline: #938f99;
                --md-sys-color-outline-variant: #49454f;
                --md-sys-color-error: #ffb4ab;
                --md-sys-color-on-error: #690005;
                --md-sys-color-error-container: #93000a;
                --md-sys-color-on-error-container: #ffdad6;
            }
            
            body {
                margin: 0;
                padding: 0;
                font-family: 'Figtree', sans-serif;
                background-color: var(--md-sys-color-surface);
                color: var(--md-sys-color-on-surface);
                transition: background-color 0.3s ease, color 0.3s ease;
            }
            
            .hero-section {
                background: linear-gradient(135deg, var(--md-sys-color-primary) 0%, var(--md-sys-color-secondary) 100%);
                color: var(--md-sys-color-on-primary);
                padding: 80px 20px;
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
                opacity: 0.3;
            }
            
            .hero-content {
                position: relative;
                z-index: 1;
                max-width: 800px;
                margin: 0 auto;
            }
            
            .hero-title {
                font-size: 3.5rem;
                font-weight: 700;
                margin-bottom: 1rem;
                text-shadow: 0 2px 4px rgba(0,0,0,0.3);
            }
            
            .hero-subtitle {
                font-size: 1.25rem;
                margin-bottom: 2rem;
                opacity: 0.9;
                font-weight: 400;
            }
            
            .cta-buttons {
                display: flex;
                gap: 1rem;
                justify-content: center;
                flex-wrap: wrap;
            }
            
            .features-section {
                padding: 80px 20px;
                background-color: var(--md-sys-color-surface-variant);
            }
            
            .features-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
                gap: 2rem;
                max-width: 1200px;
                margin: 0 auto;
            }
            
            .feature-card {
                background-color: var(--md-sys-color-surface);
                border-radius: 16px;
                padding: 2rem;
                box-shadow: 0 4px 20px rgba(0,0,0,0.1);
                transition: transform 0.3s ease, box-shadow 0.3s ease;
                border: 1px solid var(--md-sys-color-outline-variant);
            }
            
            .feature-card:hover {
                transform: translateY(-8px);
                box-shadow: 0 8px 40px rgba(0,0,0,0.15);
            }
            
            .feature-icon {
                font-size: 3rem;
                color: var(--md-sys-color-primary);
                margin-bottom: 1rem;
            }
            
            .feature-title {
                font-size: 1.5rem;
                font-weight: 600;
                margin-bottom: 1rem;
                color: var(--md-sys-color-on-surface);
            }
            
            .feature-description {
                color: var(--md-sys-color-on-surface-variant);
                line-height: 1.6;
            }
            
            .header {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                background-color: var(--md-sys-color-surface);
                border-bottom: 1px solid var(--md-sys-color-outline-variant);
                z-index: 1000;
                backdrop-filter: blur(10px);
                background-color: rgba(255, 251, 254, 0.8);
            }
            
            .dark .header {
                background-color: rgba(28, 27, 31, 0.8);
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
                font-size: 1.5rem;
                font-weight: 700;
                color: var(--md-sys-color-primary);
                text-decoration: none;
            }
            
            .nav-buttons {
                display: flex;
                gap: 1rem;
            }
            
            .theme-toggle {
                background: none;
                border: none;
                color: var(--md-sys-color-on-surface);
                cursor: pointer;
                padding: 0.5rem;
                border-radius: 50%;
                transition: background-color 0.3s ease;
            }
            
            .theme-toggle:hover {
                background-color: var(--md-sys-color-outline-variant);
            }
            
            @media (max-width: 768px) {
                .hero-title {
                    font-size: 2.5rem;
                }
                
                .cta-buttons {
                    flex-direction: column;
                    align-items: center;
                }
                
                .features-grid {
                    grid-template-columns: 1fr;
                }
                
                .header-content {
                    padding: 1rem;
                }
            }
            </style>
    </head>
    <body>
        <header class="header">
            <div class="header-content">
                <a href="/" class="logo">🎨 Exhibition App</a>
                <div class="nav-buttons">
                    <button class="theme-toggle" onclick="toggleTheme()">
                        <span class="material-icons" id="theme-icon">dark_mode</span>
                    </button>
            @if (Route::has('login'))
                    @auth
                            <a href="{{ url('/dashboard') }}" class="md-filled-button">
                            Dashboard
                        </a>
                    @else
                            <a href="{{ route('login') }}" class="md-filled-button">
                            Log in
                        </a>
                        @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="md-outlined-button">
                                Register
                            </a>
                        @endif
                    @endauth
            @endif
                </div>
            </div>
        </header>

        <section class="hero-section">
            <div class="hero-content">
                <h1 class="hero-title">Welcome to Exhibition App</h1>
                <p class="hero-subtitle">Discover, showcase, and manage amazing exhibitions with our powerful platform</p>
                <div class="cta-buttons">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="md-filled-button">
                            <span class="material-icons">dashboard</span>
                            Go to Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="md-filled-button">
                            <span class="material-icons">login</span>
                            Get Started
                        </a>
                        <a href="{{ route('register') }}" class="md-outlined-button">
                            <span class="material-icons">person_add</span>
                            Create Account
                        </a>
                    @endauth
                </div>
            </div>
        </section>

        <section class="features-section">
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">🖼️</div>
                    <h3 class="feature-title">Exhibition Management</h3>
                    <p class="feature-description">Create and manage exhibitions with ease. Organize artwork, set schedules, and track visitor engagement.</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">👥</div>
                    <h3 class="feature-title">Artist Profiles</h3>
                    <p class="feature-description">Showcase artist portfolios, manage submissions, and connect creators with art enthusiasts worldwide.</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">📊</div>
                    <h3 class="feature-title">Analytics & Insights</h3>
                    <p class="feature-description">Track visitor statistics, analyze engagement patterns, and optimize your exhibitions for better results.</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">🌐</div>
                    <h3 class="feature-title">Global Reach</h3>
                    <p class="feature-description">Share your exhibitions with audiences worldwide through our integrated digital platform.</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">🔒</div>
                    <h3 class="feature-title">Secure & Reliable</h3>
                    <p class="feature-description">Built with modern security standards to protect your data and ensure smooth operation.</p>
        </div>

                <div class="feature-card">
                    <div class="feature-icon">📱</div>
                    <h3 class="feature-title">Mobile Friendly</h3>
                    <p class="feature-description">Access and manage your exhibitions from any device with our responsive design.</p>
                </div>
            </div>
        </section>

        <script>
            // Theme toggle functionality
            function toggleTheme() {
                const body = document.body;
                const themeIcon = document.getElementById('theme-icon');
                
                if (body.classList.contains('dark')) {
                    body.classList.remove('dark');
                    themeIcon.textContent = 'dark_mode';
                    localStorage.setItem('theme', 'light');
                } else {
                    body.classList.add('dark');
                    themeIcon.textContent = 'light_mode';
                    localStorage.setItem('theme', 'dark');
                }
            }
            
            // Check for saved theme preference
            const savedTheme = localStorage.getItem('theme');
            if (savedTheme === 'dark' || (!savedTheme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.body.classList.add('dark');
                document.getElementById('theme-icon').textContent = 'light_mode';
            }
            
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
