<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Exhibition App') }}</title>

        <!-- Favicon -->
        <link rel="icon" type="image/x-icon" href="/images/sajili-5.png">
        <link rel="icon" type="image/png" sizes="32x32" href="/images/sajili-5.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/images/sajili-5.png">
        <link rel="apple-touch-icon" sizes="180x180" href="/images/sajili-5.png">
        <link rel="shortcut icon" href="/images/sajili-5.png">

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
                background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 20px;
            }
            
            .auth-card {
                background: white;
                border-radius: 16px;
                box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
                overflow: hidden;
                width: 100%;
                max-width: 450px;
                position: relative;
                border: 1px solid #e2e8f0;
            }
            
            .auth-header {
                background: #f8fafc;
                color: #1e293b;
                padding: 32px 32px 24px;
                text-align: center;
                position: relative;
                border-bottom: 1px solid #e2e8f0;
            }
            
            .auth-header::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="1" fill="rgba(148,163,184,0.05)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
                opacity: 0.5;
            }
            
            .auth-header-content {
                position: relative;
                z-index: 1;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
            }
            
            .auth-header-content img {
                display: block;
                margin: 0 auto 16px auto;
                max-width: 100%;
                height: auto;
            }
            
            .app-title {
                font-size: 1.5rem;
                font-weight: 600;
                margin-bottom: 6px;
                color: #1e293b;
            }
            
            .app-subtitle {
                font-size: 0.9rem;
                color: #64748b;
                font-weight: 400;
            }
            
            .auth-body {
                padding: 24px;
                background: white;
            }
            
            .form-group {
                margin-bottom: 12px;
            }
            
            .form-label {
                display: block;
                font-size: 0.875rem;
                font-weight: 500;
                color: #374151;
                margin-bottom: 4px;
            }
            
            .form-input {
                width: 100%;
                padding: 8px 12px;
                border: 1px solid #d1d5db;
                border-radius: 6px;
                font-size: 0.95rem;
                transition: all 0.2s ease;
                background-color: white;
                color: #1f2937;
            }
            
            .form-input:focus {
                outline: none;
                border-color: #3b82f6;
                box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.1);
            }
            
            .form-input.error {
                border-color: #ef4444;
                box-shadow: 0 0 0 2px rgba(239, 68, 68, 0.1);
            }
            
            .error-message {
                color: #dc2626;
                font-size: 0.8rem;
                margin-top: 2px;
                display: flex;
                align-items: center;
                gap: 4px;
            }
            
            .checkbox-group {
                display: flex;
                align-items: center;
                gap: 6px;
                margin-bottom: 12px;
            }
            
            .checkbox-input {
                width: 14px;
                height: 14px;
                accent-color: #3b82f6;
            }
            
            .checkbox-label {
                font-size: 0.8rem;
                color: #6b7280;
            }
            
            .auth-actions {
                display: flex;
                flex-direction: column;
                gap: 8px;
            }
            
            .btn-primary {
                background: #3b82f6;
                color: white;
                padding: 8px 16px;
                border: none;
                border-radius: 6px;
                font-size: 0.95rem;
                font-weight: 500;
                cursor: pointer;
                transition: all 0.2s ease;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 6px;
                width: 100%;
            }
            
            .btn-primary:hover {
                background: #2563eb;
                transform: translateY(-1px);
                box-shadow: 0 3px 8px rgba(59, 130, 246, 0.2);
            }
            
            .btn-primary:active {
                transform: translateY(0);
            }
            
            .forgot-password {
                text-align: center;
                margin-top: 8px;
            }
            
            .forgot-password a {
                color: #6b7280;
                text-decoration: none;
                font-size: 0.8rem;
                transition: color 0.2s ease;
            }
            
            .forgot-password a:hover {
                color: #3b82f6;
                text-decoration: underline;
            }
            
            .auth-footer {
                text-align: center;
                margin-top: 12px;
                padding-top: 12px;
                border-top: 1px solid #e5e7eb;
            }
            
            .auth-footer a {
                color: #3b82f6;
                text-decoration: none;
                font-weight: 500;
                transition: color 0.2s ease;
            }
            
            .auth-footer a:hover {
                color: #2563eb;
            }
            
            .session-status {
                background: #d1fae5;
                color: #065f46;
                padding: 12px 16px;
                border-radius: 8px;
                margin-bottom: 20px;
                font-size: 0.875rem;
                display: flex;
                align-items: center;
                gap: 8px;
                border: 1px solid #a7f3d0;
            }
            
            @media (max-width: 640px) {
                .auth-container {
                    padding: 10px;
                }
                
                .auth-body {
                    padding: 24px 20px;
                }
                
                .auth-header {
                    padding: 24px 20px 20px;
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
                        <img src="/images/sajili-5.png" alt="SAJILI Logo" style="height: 60px; width: auto; margin-bottom: 16px;">
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
