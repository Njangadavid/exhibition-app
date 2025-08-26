<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Exhibition App') }} - {{ $event->name ?? 'Event' }}</title>

        <!-- Favicon -->
        <link rel="icon" type="image/x-icon" href="/images/sajili-5.png">
        <link rel="icon" type="image/png" sizes="32x32" href="/images/sajili-5.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/images/sajili-5.png">
        <link rel="apple-touch-icon" sizes="180x180" href="/images/sajili-5.png">
        <link rel="shortcut icon" href="/images/sajili-5.png">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @stack('styles')

    </head>
    <body>
        <div class="min-vh-100 bg-light">
            @include('layouts.navigation')

            <!-- Event Header with Logo and Info -->
            @if(isset($event))
            <header class="bg-white shadow-sm">
                <div class="container-fluid">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="d-flex align-items-center">
                            <div class="me-4">
                                <div class="bg-gradient-to-br from-blue-400 to-purple-600 rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 64px; height: 64px;">
                                    @if($event->logo)
                                        <img src="{{ Storage::url($event->logo) }}" alt="{{ $event->name }}" class="rounded-circle" style="width: 64px; height: 64px; object-fit: cover;">
                                    @else
                                        <i class="bi bi-calendar-event text-white fs-4"></i>
                                    @endif
                                </div>
                            </div>
                            <div class="ms-3">
                                <h1 class="h3 mb-1 fw-bold">{{ $event->name }}</h1>
                                <div class="d-flex align-items-center gap-3 text-muted">
                                    <span class="d-flex align-items-center">
                                        <i class="bi bi-calendar-event me-2"></i>
                                        {{ $event->start_date->format('M d, Y') }} - {{ $event->end_date->format('M d, Y') }}
                                    </span>
                                    <span class="d-flex align-items-center">
                                        <i class="bi bi-clock me-2"></i>
                                        {{ $event->duration_in_days }} day{{ $event->duration_in_days > 1 ? 's' : '' }}
                                    </span>
                                    <span class="badge 
                                        @if($event->status === 'active') bg-success
                                        @elseif($event->status === 'published') bg-primary
                                        @elseif($event->status === 'completed') bg-info
                                        @elseif($event->status === 'cancelled') bg-danger
                                        @else bg-secondary @endif px-3 py-2">
                                        <i class="bi 
                                            @if($event->status === 'active') bi-play-circle
                                            @elseif($event->status === 'published') bi-globe
                                            @elseif($event->status === 'completed') bi-check-circle
                                            @elseif($event->status === 'cancelled') bi-x-circle
                                            @else bi-dash-circle @endif me-2"></i>
                                        {{ ucfirst($event->status) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('events.edit', $event) }}" class="btn btn-outline-primary">
                                <i class="bi bi-pencil me-2"></i>Edit Exhibition
                            </a>
                            <a href="#" class="btn btn-outline-success">
                                <i class="bi bi-play-circle me-2"></i>Launch Event
                            </a>
                            <a href="{{ route('events.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-2"></i>Back to Events
                            </a>
                        </div>
                    </div>

                    <!-- Event Navigation Menu -->
                    <nav class="navbar navbar-expand-lg navbar-light bg-white rounded-3 shadow-sm border">
                        <div class="container-fluid">
                            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#eventNavbar">
                                <span class="navbar-toggler-icon"></span>
                            </button>
                            
                            <div class="collapse navbar-collapse" id="eventNavbar">
                                <ul class="navbar-nav me-auto">
                                    <li class="nav-item">
                                        <a href="{{ route('events.dashboard', $event) }}" class="nav-link fw-medium px-3 py-3 {{ request()->routeIs('events.dashboard') ? 'active' : '' }}">
                                            <i class="bi bi-speedometer2 me-2"></i>
                                            Overview
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('events.floorplan', $event) }}" class="nav-link fw-medium px-3 py-3 {{ request()->routeIs('events.floorplan') ? 'active' : '' }}">
                                            <i class="bi bi-grid-3x3-gap me-2"></i>
                                            Floorplan
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('events.form-builders.index', $event) }}" class="nav-link fw-medium px-3 py-3 {{ request()->routeIs('events.form-builders.*') ? 'active' : '' }}">
                                            <i class="bi bi-pencil-square me-2"></i>
                                            Exhibitor Forms
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('events.email-templates.index', $event) }}" class="nav-link fw-medium px-3 py-3 {{ request()->routeIs('events.email-templates.*') ? 'active' : '' }}">
                                            <i class="bi bi-envelope me-2"></i>
                                            Email Templates
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('admin.payment-methods.index', ['event' => $event->id]) }}" class="nav-link fw-medium px-3 py-3 {{ request()->routeIs('admin.payment-methods.*') ? 'active' : '' }}">
                                            <i class="bi bi-credit-card me-2"></i>
                                            Payment Setup
                                        </a>
                                    </li>
                                    
                                      
                                </ul>
                                
                                <!-- Quick Actions -->
                                <div class="navbar-nav">
                                    <div class="nav-item dropdown">
                                        <a class="nav-link dropdown-toggle fw-medium px-3 py-3" href="#" role="button" data-bs-toggle="dropdown">
                                            <i class="bi bi-gear me-2"></i>Settings
                                        </a>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li><a class="dropdown-item" href="{{ route('events.edit', $event) }}">
                                                <i class="bi bi-pencil me-2"></i>Edit Event
                                            </a></li>
                                            <li><a class="dropdown-item" href="#">
                                                <i class="bi bi-bell me-2"></i>Notifications
                                            </a></li> 
                                        </ul>
                                    </div>
                                    
                                    <div class="nav-item dropdown">
                                        <a class="nav-link dropdown-toggle fw-medium px-3 py-3" href="#" role="button" data-bs-toggle="dropdown">
                                            <i class="bi bi-eye me-2"></i>Preview
                                        </a>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li><a class="dropdown-item" href="{{ route('events.public.show', $event) }}" target="_blank">
                                                <i class="bi bi-globe me-2"></i>Public Event Page
                                            </a></li>
                                            <li><a class="dropdown-item" href="{{ route('events.public.floorplan', $event) }}" target="_blank">
                                                <i class="bi bi-grid-3x3-gap me-2"></i>Public Floorplan
                                            </a></li>
                                             
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </nav>
                </div>
            </header>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
        <script src="https://cdn.tiny.cloud/1/ymua9kt15x20ok61l6qlh9piih90ojnvq3bkqskll1mrzxgm/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
   
        <!-- Custom Scripts Stack -->
        @stack('scripts')

        <!-- Custom Styles -->
        <style>
            .navbar-nav .nav-link {
                color: #6c757d;
                transition: all 0.3s ease;
                border-radius: 0.5rem;
                margin: 0 0.25rem;
            }
            
            .navbar-nav .nav-link:hover {
                color: #0d6efd;
                background-color: #f8f9fa;
            }
            
            .navbar-nav .nav-link.active {
                color: #0d6efd;
                background-color: #e7f1ff;
                font-weight: 600;
            }
            
            .navbar {
                border: 1px solid #dee2e6;
            }
            
            .dropdown-menu {
                border: none;
                box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
                border-radius: 0.5rem;
            }
            
            .dropdown-item:hover {
                background-color: #f8f9fa;
            }
        </style>
    </body>
</html>
