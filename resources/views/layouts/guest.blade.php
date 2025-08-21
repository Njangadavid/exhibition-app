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
            .material-icons {
                font-family: 'Material Icons';
                font-weight: normal;
                font-style: normal;
                font-size: 24px;
                line-height: 1;
                letter-spacing: normal;
                text-transform: none;
                display: inline-block;
                white-space: nowrap;
                word-wrap: normal;
                direction: ltr;
                -webkit-font-feature-settings: 'liga';
                -webkit-font-smoothing: antialiased;
            }
            
            .auth-container {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 20px;
            }
            
            .auth-card {
                background: rgba(255, 255, 255, 0.95);
                backdrop-filter: blur(10px);
                border-radius: 20px;
                box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
                overflow: hidden;
                width: 100%;
                max-width: 450px;
                position: relative;
            }
            
            .auth-header {
                background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
                color: white;
                padding: 30px;
                text-align: center;
                position: relative;
                overflow: hidden;
            }
            
            .auth-header::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="1" fill="rgba(255,255,255,0.1)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
                opacity: 0.3;
            }
            
            .auth-header-content {
                position: relative;
                z-index: 1;
            }
            
            .app-logo {
                font-size: 2.5rem;
                margin-bottom: 10px;
            }
            
            .app-title {
                font-size: 1.5rem;
                font-weight: 600;
                margin-bottom: 5px;
            }
            
            .app-subtitle {
                font-size: 0.9rem;
                opacity: 0.9;
            }
            
            .auth-body {
                padding: 40px;
            }
            
            .form-group {
                margin-bottom: 24px;
            }
            
            .form-label {
                display: block;
                font-size: 0.875rem;
                font-weight: 500;
                color: #374151;
                margin-bottom: 8px;
            }
            
            .form-input {
                width: 100%;
                padding: 12px 16px;
                border: 2px solid #e5e7eb;
                border-radius: 12px;
                font-size: 1rem;
                transition: all 0.3s ease;
                background-color: white;
            }
            
            .form-input:focus {
                outline: none;
                border-color: #4f46e5;
                box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
            }
            
            .form-input.error {
                border-color: #ef4444;
            }
            
            .error-message {
                color: #ef4444;
                font-size: 0.875rem;
                margin-top: 6px;
                display: flex;
                align-items: center;
                gap: 4px;
            }
            
            .checkbox-group {
                display: flex;
                align-items: center;
                gap: 8px;
                margin-bottom: 24px;
            }
            
            .checkbox-input {
                width: 18px;
                height: 18px;
                accent-color: #4f46e5;
            }
            
            .checkbox-label {
                font-size: 0.875rem;
                color: #6b7280;
            }
            
            .auth-actions {
                display: flex;
                flex-direction: column;
                gap: 16px;
            }
            
            .btn-primary {
                background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
                color: white;
                padding: 14px 24px;
                border: none;
                border-radius: 12px;
                font-size: 1rem;
                font-weight: 600;
                cursor: pointer;
                transition: all 0.3s ease;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 8px;
                width: 100%;
            }
            
            .btn-primary:hover {
                transform: translateY(-2px);
                box-shadow: 0 10px 25px rgba(79, 70, 229, 0.3);
            }
            
            .btn-primary:active {
                transform: translateY(0);
            }
            
            .forgot-password {
                text-align: center;
                margin-top: 16px;
            }
            
            .forgot-password a {
                color: #4f46e5;
                text-decoration: none;
                font-size: 0.875rem;
                transition: color 0.3s ease;
            }
            
            .forgot-password a:hover {
                color: #7c3aed;
                text-decoration: underline;
            }
            
            .auth-footer {
                text-align: center;
                margin-top: 24px;
                padding-top: 24px;
                border-top: 1px solid #e5e7eb;
            }
            
            .auth-footer a {
                color: #4f46e5;
                text-decoration: none;
                font-weight: 500;
                transition: color 0.3s ease;
            }
            
            .auth-footer a:hover {
                color: #7c3aed;
            }
            
            .session-status {
                background: #10b981;
                color: white;
                padding: 12px 16px;
                border-radius: 12px;
                margin-bottom: 24px;
                font-size: 0.875rem;
                display: flex;
                align-items: center;
                gap: 8px;
            }
            
            @media (max-width: 640px) {
                .auth-container {
                    padding: 10px;
                }
                
                .auth-body {
                    padding: 30px 20px;
                }
                
                .auth-header {
                    padding: 20px;
                }
                
                .app-logo {
                    font-size: 2rem;
                }
                
                .app-title {
                    font-size: 1.25rem;
                }
            }
        </style>
    </head>
    <body class="font-sans antialiased">
        <div class="auth-container">
            <div class="auth-card">
                <div class="auth-header">
                    <div class="auth-header-content">
                        <div class="app-logo">🎨</div>
                        <h1 class="app-title">{{ config('app.name', 'Exhibition App') }}</h1>
                        <p class="app-subtitle">
                            @if(request()->routeIs('login'))
                                Sign in to your account
                            @elseif(request()->routeIs('register'))
                                Create your account
                            @else
                                Welcome to Exhibition App
                            @endif
                        </p>
                    </div>
                </div>
                
                <div class="auth-body">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </body>
</html>
