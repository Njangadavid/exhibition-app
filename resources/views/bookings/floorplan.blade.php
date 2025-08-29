@extends('layouts.public')

@section('title', 'Interactive Floorplan')

@php
    $showProgress = true;
    $currentStep = $existingBooking ? $existingBooking->current_booking_progress ?? 1 : 1;
@endphp

@push('styles')
    @vite(['resources/css/app.css'])
    <style>
        :root {
            --available-color: #e5e7eb;
            --available-stroke: #d1d5db;
            --reserved-color: #f59e0b;
            --reserved-stroke: #f59e0b;
            --booked-color: #dc2626;
            --booked-stroke: #dc2626;
        }
        
        /* Enhanced Right Panel Styles */
        #itemInfoPanel {
            transition: right 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: -5px 0 25px rgba(0, 0, 0, 0.15);
        }
        
        /* Enhanced Card Styles */
        #itemInfoPanel .card {
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            border: none;
            overflow: hidden;
        }
        
        /* Enhanced Header Gradient */
        #itemInfoPanel .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
        }
        
        /* Enhanced Info Grid */
        #itemInfoPanel .bg-light {
            transition: all 0.2s ease;
        }
        
        #itemInfoPanel .bg-light:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        
        /* Compact Capacity & Price Cards */
        #itemInfoPanel .row .col-6 .bg-light {
            min-height: 45px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        
        #itemInfoPanel .row .col-6 .bg-light .small {
            line-height: 1.2;
        }
        
        /* Compact Dimension Cards */
        #itemInfoPanel #boothSizeInfo .row .col-6 .bg-white {
            min-height: 35px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        
        #itemInfoPanel #boothSizeInfo .bg-white {
            transition: all 0.2s ease;
        }
        
        #itemInfoPanel #boothSizeInfo .bg-white:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        }
        
        /* Enhanced Company Info */
        #itemInfoPanel .bg-gradient-light {
            transition: all 0.3s ease;
        }
        
        #itemInfoPanel .bg-gradient-light:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }
        
        /* Enhanced Action Button */
        #itemInfoPanel #bookNowBtn {
            transition: all 0.3s ease;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
        }
        
        #itemInfoPanel #bookNowBtn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
        }
        
        /* Responsive adjustments for right panel */
        @media (max-width: 768px) {
            #itemInfoPanel {
                width: 100vw !important;
                right: -100vw !important;
            }
            
            #itemInfoPanel.show {
                right: 0 !important;
            }
        }
        
        /* Compact popup adjustments */
        #itemInfoPanel .card-body {
            max-height: 70vh;
            overflow-y: auto;
        }
        
        #itemInfoPanel .card-body::-webkit-scrollbar {
            width: 4px;
        }
        
        #itemInfoPanel .card-body::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 2px;
        }
        
        #itemInfoPanel .card-body::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 2px;
        }
    </style>
@endpush

@push('scripts')
    @vite(['resources/js/app.js'])
@endpush
@section('content')

    <div class="py-4">
        <div class="container-fluid">
            <!-- Current Booking Status Banner (if user has access token) -->
            @if($existingBooking)
                <div class="row mb-1 justify-content-center">
                    <div class="col-lg-10">
                        <div class="card border-primary">
                            <div class="card-header bg-primary text-white">
                                <h6 class="mb-0">
                                    <i class="bi bi-info-circle me-2"></i>
                                    Current Booking Status
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-md-8">
                                        <div class="d-flex align-items-center mb-2">
                                            <span class="badge bg-primary me-3">Current Space: {{ $existingBooking->floorplanItem->label ?? 'N/A' }}</span>
                                            <span class="badge bg-{{ $existingBooking->status === 'confirmed' ? 'success' : 'warning' }} me-2">
                                                {{ ucfirst($existingBooking->status) }}
                                            </span>
                                            <span class="text-muted small">Progress: Step {{ $existingBooking->current_booking_progress ?? 1 }} of 4</span>
                                        </div>
                                        <div class="text-muted small">
                                            <strong>Owner:</strong> {{ $existingBooking->boothOwner->form_responses['name'] ?? 'N/A' }} | 
                                            <strong>Members:</strong> {{ $existingBooking->boothMembers ? count($existingBooking->boothMembers) : 0 }} | 
                                            <strong>Price:</strong> ${{ number_format($existingBooking->floorplanItem->price ?? 0, 2) }}
                                        </div>
                                    </div>
                                    <div class="col-md-4 text-md-end">
                                        <div class="btn-group" role="group">
                                            <!-- All buttons always available if access token exists -->
                                             
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Floorplan Display Interface -->
            <div class="row justify-content-center">
                <!-- Floorplan Canvas - Full Width -->
                <div class="col-lg-10">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div class="text-muted">
                                    <i class="bi bi-info-circle me-2 text-primary"></i>
                                    <span class="fw-medium">
                                        @if($existingBooking)
                                            Click on items to view details and change your space
                                        @else
                                            Click on items to view details and book your space
                                        @endif
                                    </span>
                                </div>
                                
                                <!-- Color Legend (Bookable Items Only) - Floating Right -->
                                <div class="d-flex align-items-center gap-3 text-sm">
                                    <div class="d-flex align-items-center">
                                        <div class="me-1" style="width: 16px; height: 16px; background-color: #10b981; border: 2px solid #059669; border-radius: 2px;"></div>
                                        <span class="text-muted small">My Booking</span>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <div class="me-1" style="width: 16px; height: 16px; background-color: var(--available-color, #e5e7eb); border: 2px solid var(--available-stroke, #d1d5db); border-radius: 2px;"></div>
                                        <span class="text-muted small">Available</span>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <div class="me-1" style="width: 16px; height: 16px; background-color: var(--reserved-color, #f59e0b); border: 2px solid var(--reserved-stroke, #f59e0b); border-radius: 2px;"></div>
                                        <span class="text-muted small">Reserved</span>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <div class="me-1" style="width: 16px; height: 16px; background-color: var(--booked-color, #dc2626); border: 2px solid var(--booked-stroke, #dc2626); border-radius: 2px;"></div>
                                        <span class="text-muted small">Booked</span>
                                    </div>
                                </div>
                                

                                
                                <!-- Compact Item Info Popup (shows when bookable item is clicked) -->
                                <!-- Enhanced Booth Details Right Panel -->
                                <div id="itemInfoPanel" class="position-fixed" style="display: none; z-index: 1000; right: -350px; top: 0; height: 100vh; transition: right 0.3s ease;">
                                    <!-- Backdrop -->
                                    <div class="position-absolute w-100 h-100" style="background: rgba(0, 0, 0, 0.3); left: -100vw; top: 0;"></div>
                                    
                                    <div class="card shadow-lg border-0 rounded-0 h-100" style="width: 350px; border-radius: 0 !important;">
                                        <!-- Enhanced Header with gradient -->
                                        <div class="card-header text-white py-3 px-4 d-flex justify-content-between align-items-center" 
                                             style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 0 !important;">
                                            <div class="d-flex align-items-center">
                                                <div class="me-2" id="headerIcon">
                                                    <i class="bi bi-shop fs-5"></i>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0 fw-bold" id="itemTypeLabel">Booth Details</h6>
                                                </div>
                                            </div>
                                            <button type="button" class="btn-close btn-close-white opacity-75" id="closeItemInfo" 
                                                    style="filter: brightness(0) invert(1);"></button>
                                        </div>
                                        
                                        <!-- Enhanced Content -->
                                        <div class="card-body p-3">
                                            <!-- Item Name & Status Section -->
                                            <div class="mb-3">
                                                <div class="d-flex align-items-center justify-content-between mb-1">
                                                    <h6 class="fw-bold text-dark mb-0" id="itemName">Booth A1</h6>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <span class="badge rounded-pill px-2 py-1 small" id="itemStatus">Available</span>
                                                    </div>
                                                </div>
                                                <p class="text-muted mb-0 small" id="statusText">Ready to book this space</p>
                                            </div>
                                            
                                            <!-- Enhanced Quick Info Grid -->
                                            <div class="row g-1 mb-2">
                                                <div class="col-6">
                                                    <div class="text-center p-1 bg-light rounded border border-1 border-primary border-opacity-10">
                                                        <div class="text-primary fw-bold small mb-0" id="itemMaxCapacity">5</div>
                                                        <small class="text-muted" style="font-size: 0.7rem;">
                                                            <i class="bi bi-people me-1"></i>Capacity
                                                        </small>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="text-center p-1 bg-light rounded border border-1 border-success border-opacity-10">
                                                        <div class="text-success fw-bold small mb-0" id="itemPrice">$100</div>
                                                        <small class="text-muted" style="font-size: 0.7rem;">
                                                            <i class="bi bi-currency-dollar me-1"></i>Price
                                                        </small>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <!-- Enhanced Booth Size Information -->
                                            <div id="boothSizeInfo" class="mb-3" style="display: none;">
                                                <div class="p-1 bg-light rounded border border-1 border-info border-opacity-20">
                                                    <div class="d-flex align-items-center mb-1">
                                                        <i class="bi bi-rulers text-info me-2 small"></i>
                                                        <small class="fw-bold text-dark">Dimensions</small>
                                                        <small class="text-muted" style="font-size: 0.7rem;">&nbsp;&nbsp;Physical size</small>

                                                    </div>
                                                    
                                                    <div class="row g-1">
                                                        <div class="col-6 text-center">
                                                            <div class="bg-white rounded p-1 border">
                                                                <div class="text-info fw-bold" style="font-size: 0.7rem;" id="boothWidthMeters">3.0m</div>
                                                                <small class="text-muted" style="font-size: 0.65rem;">W</small>
                                                            </div>
                                                        </div>
                                                        <div class="col-6 text-center">
                                                            <div class="bg-white rounded p-1 border">
                                                                <div class="text-info fw-bold" style="font-size: 0.7rem;" id="boothHeightMeters">2.0m</div>
                                                                <small class="text-muted" style="font-size: 0.65rem;">L</small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <!-- Enhanced Company Information -->
                                            <div id="companyInfo" class="mb-3" style="display: none;">
                                                <div class="p-2 bg-light rounded border border-2 border-warning border-opacity-20">
                                                    <div class="d-flex align-items-center mb-2">
                                                        <i class="bi bi-building text-warning me-2 small"></i>
                                                        <small class="fw-bold text-dark">Occupied by</small>
                                                    </div>
                                                    <div id="companyDetails">
                                                        <!-- Compact Company Logo -->
                                                        <div class="text-center mb-2" id="companyLogoContainer">
                                                            <div class="d-inline-flex align-items-center justify-content-center bg-white rounded border shadow-sm" 
                                                                 style="width: 50px; height: 50px;">
                                                                <i class="bi bi-building text-muted opacity-50" style="font-size: 1.2rem;" id="companyLogoIcon"></i>
                                                                <img id="companyLogo" class="d-none rounded" style="width: 45px; height: 45px; object-fit: cover;">
                                                            </div>
                                                        </div>
                                                        <!-- Company Details -->
                                                        <div class="text-center">
                                                            <div class="fw-bold text-dark small mb-1" id="companyName">Company Name</div>
                                                            <div class="d-flex align-items-center justify-content-center mb-1">
                                                                <i class="bi bi-person text-muted me-1 small"></i>
                                                                <span class="text-muted small" id="companyContact">Contact Person</span>
                                                            </div>
                                                            <div class="d-flex align-items-center justify-content-center">
                                                                <i class="bi bi-envelope text-muted me-1 small"></i>
                                                                <span class="text-muted small" id="companyEmail">email@company.com</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <!-- Enhanced Action Button -->
                                            <div id="bookNowGroup" class="mt-3">
                                                <a href="#" id="bookNowBtn" class="btn w-100 fw-bold text-decoration-none py-2 rounded shadow-sm">
                                                    <i class="bi bi-calendar-check me-2"></i>
                                                    Book This Space
                                                </a>
                                            </div>
                                            

                                        </div>
                                    </div>
                                </div>
                                

                            </div>
                            
                            <!-- Mobile Booking Steps (shown on small screens) -->
                            <div class="d-block d-lg-none mb-3">
                                <div class="card bg-light border-0">
                                    <div class="card-body py-2 px-3">
                                        <div class="text-center">
                                            <small class="text-muted fw-medium">Booking Process:</small>
                                        </div>
                                        <div class="row g-1 mt-1">
                                            <div class="col-3 text-center">
                                                <div class="d-flex flex-column align-items-center">
                                                    <span class="badge bg-primary rounded-pill mb-1">1</span>
                                                    <small class="text-muted" style="font-size: 0.7rem;">Select</small>
                                                </div>
                                            </div>
                                            <div class="col-3 text-center">
                                                <div class="d-flex flex-column align-items-center">
                                                    <span class="badge bg-secondary rounded-pill mb-1">2</span>
                                                    <small class="text-muted" style="font-size: 0.7rem;">Details</small>
                                                </div>
                                            </div>
                                            <div class="col-3 text-center">
                                                <div class="d-flex flex-column align-items-center">
                                                    <span class="badge bg-secondary rounded-pill mb-1">3</span>
                                                    <small class="text-muted" style="font-size: 0.7rem;">Members</small>
                                                </div>
                                            </div>
                                            <div class="col-3 text-center">
                                                <div class="d-flex flex-column align-items-center">
                                                    <span class="badge bg-secondary rounded-pill mb-1">4</span>
                                                    <small class="text-muted" style="font-size: 0.7rem;">Payment</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Canvas Area -->
                            <div class="border border-2 border-dashed border-secondary rounded bg-light position-relative canvas-container" style="min-height: 500px;">
                                <canvas id="floorplanCanvas" width="{{ $floorplanDesign->canvas_width ?? 800 }}" height="{{ $floorplanDesign->canvas_height ?? 600 }}" style="border: 1px solid #ccc; cursor: pointer;"></canvas>
                                
                                <!-- Canvas Instructions (shown when empty) -->
                                <div id="canvasInstructions" class="position-absolute text-center">
                                    <i class="bi bi-mouse text-muted" style="font-size: 3rem;"></i>
                                    <h4 class="h6 mt-3 mb-2">Interactive Floorplan</h4>
                                    <p class="text-muted mb-3">Click on items to view details and book</p>
                                    <div class="text-muted small">
                                        <p class="mb-1">• Click on booths to see availability</p>
                                        <p class="mb-1">• View pricing and capacity</p>
                                        <p class="mb-0">• Book your preferred space</p>
                                    </div>
                                </div>
                            </div>
                            

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Change Booth Modal (only for existing bookings) -->
    @if($existingBooking)
    <div class="modal fade" id="changeBoothModal" tabindex="-1" aria-labelledby="changeBoothModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title" id="changeBoothModalLabel">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        Change Your Space
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>Important:</strong> Changing your space will affect your current booking. Please review the options below.
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-primary">Current Space</h6>
                            <div class="card border-primary">
                                <div class="card-body">
                                    <h6 class="card-title">{{ $existingBooking->floorplanItem->label ?? 'N/A' }}</h6>
                                    <p class="card-text small text-muted">
                                        <strong>Type:</strong> {{ ucfirst($existingBooking->floorplanItem->type ?? 'booth') }}<br>
                                        <strong>Capacity:</strong> {{ $existingBooking->floorplanItem->max_capacity ?? 5 }} members<br>
                                        <strong>Price:</strong> ${{ number_format($existingBooking->floorplanItem->price ?? 0, 2) }}
                                    </p>
                                    <div class="d-flex align-items-center">
                                        <span class="badge bg-primary me-2">Current</span>
                                        <small class="text-muted">Step {{ $existingBooking->current_booking_progress ?? 1 }} of 4</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-success">Available Alternatives</h6>
                            <div id="availableAlternatives">
                                <div class="text-center py-3">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                    <p class="mt-2 text-muted">Loading available spaces...</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" onclick="removeCurrentBooking()">
                        <i class="bi bi-trash me-1"></i>Remove Current Booking
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

@endsection

@push('styles')
<style>
    .hidden {
        display: none !important;
    }
    
    /* Canvas styling - responsive */
    #floorplanCanvas {
        max-width: 100%;
        height: auto;
        display: block;
        margin: 0 auto;
        /* Ensure canvas scales proportionally without stretching */
        object-fit: contain;
    }
    
    /* Ensure canvas container is responsive */
    .canvas-container {
        overflow: auto;
        max-width: 100%;
    }
    
    @media (max-width: 768px) {
        .canvas-container {
            padding: 10px;
        }
    }
    
    /* Item info panel styling */
    #itemInfoPanel {
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
            const canvas = document.getElementById('floorplanCanvas');
            const ctx = canvas.getContext('2d');
            let shapes = [];
            
            // Initialize canvas
            function initCanvas() {
                // Set canvas size from database or default
                @if($floorplanDesign)
                    const canvasWidth = {{ $floorplanDesign->canvas_width }};
                    const canvasHeight = {{ $floorplanDesign->canvas_height }};
                @else
                    const canvasWidth = 800;
                    const canvasHeight = 600;
                @endif
                
                // Set canvas resolution (internal dimensions)
                canvas.width = canvasWidth;
                canvas.height = canvasHeight;
                
                // Don't set style dimensions - let CSS handle responsive scaling
                // This prevents stretching and maintains aspect ratio
                 
                 // Improve text rendering quality
                 const ctx = canvas.getContext('2d');
                 ctx.textRenderingOptimization = 'optimizeQuality';
                 ctx.imageSmoothingEnabled = true;
                 ctx.imageSmoothingQuality = 'high';
                
                // Apply global floorplan colors from database
                @if($floorplanDesign)
                    // Set background color
                    if (canvas.style) {
                        canvas.style.backgroundColor = '{{ $floorplanDesign->bg_color ?? "#ffffff" }}';
                    }
                    
                    // Store global colors for use in drawing
                    window.globalFloorplanColors = {
                        bgColor: '{{ $floorplanDesign->bg_color ?? "#ffffff" }}',
                        fillColor: '{{ $floorplanDesign->fill_color ?? "#e5e7eb" }}',
                        strokeColor: '{{ $floorplanDesign->stroke_color ?? "#374151" }}',
                        textColor: '{{ $floorplanDesign->text_color ?? "#111827" }}',
                        borderWidth: {{ $floorplanDesign->border_width ?? 2 }},
                        fontFamily: '{{ $floorplanDesign->font_family ?? "Arial" }}',
                        fontSize: {{ $floorplanDesign->font_size ?? 12 }}
                    };

                @endif
                

            }
            
            // Load floorplan defaults and update CSS variables
            function loadFloorplanDefaults() {
                // Get floorplan defaults from the page data
                const floorplanData = @json($event->floorplanDesign ?? null);
                if (floorplanData) {
                    window.floorplanDefaults = {
                        // Basic styling defaults
                        fill_color: floorplanData.fill_color || '#e5e7eb',
                        stroke_color: floorplanData.stroke_color || '#d1d5db',
                        border_width: floorplanData.border_width || 2,
                        text_color: floorplanData.text_color || '#111827',
                        font_family: floorplanData.font_family || 'Arial',
                        font_size: floorplanData.font_size || 12,
                        
                        // Label customization defaults
                        default_label_font_size: floorplanData.default_label_font_size || 12,
                        default_label_background_color: floorplanData.default_label_background_color || '#ffffff',
                        default_label_color: floorplanData.default_label_color || '#111827',
                        default_label_position: floorplanData.default_label_position || 'top',
                        
                        // Booth size defaults (physical dimensions)
                        default_booth_width_meters: floorplanData.default_booth_width_meters || 3.0,
                        default_booth_height_meters: floorplanData.default_booth_height_meters || 2.0,
                        
                        // Booking status colors (global, not overridable)
                        default_booked_color: floorplanData.default_booked_color || '#dc2626',
                        default_reserved_color: floorplanData.default_reserved_color || '#f59e0b',
                        
                        // Additional useful defaults
                        bg_color: floorplanData.bg_color || '#ffffff',
                        canvas_width: floorplanData.canvas_width || 800,
                        canvas_height: floorplanData.canvas_height || 600
                    };
                    console.log(window.floorplanDefaults);
                    
                    // Update CSS variables for the legend
                    document.documentElement.style.setProperty('--available-color', window.floorplanDefaults.fill_color);
                    document.documentElement.style.setProperty('--available-stroke', window.floorplanDefaults.stroke_color);
                    document.documentElement.style.setProperty('--reserved-color', window.floorplanDefaults.default_reserved_color);
                    document.documentElement.style.setProperty('--reserved-stroke', window.floorplanDefaults.default_reserved_color);
                    document.documentElement.style.setProperty('--booked-color', window.floorplanDefaults.default_booked_color);
                    document.documentElement.style.setProperty('--booked-stroke', window.floorplanDefaults.default_booked_color);
                    
                    // Update canvas background color if available
                    if (window.floorplanDefaults.bg_color) {
                        const canvas = document.getElementById('floorplanCanvas');
                        if (canvas) {
                            canvas.style.backgroundColor = window.floorplanDefaults.bg_color;
                        }
                    }
                }
            }
            
            // Load floorplan data
            function loadFloorplan() {
                @if($floorplanDesign)
                    const floorplanData = @json($floorplanDesign);
                    
                    if (floorplanData && floorplanData.items && floorplanData.items.length > 0) {
                        shapes = floorplanData.items;
                        
                        // Load and merge booking data
                        loadAndMergeBookingData();
                        
                        redrawCanvas();
                        document.getElementById('canvasInstructions').classList.add('hidden');
                    } else {
                        document.getElementById('canvasInstructions').classList.remove('hidden');
                    }
                @else
                    document.getElementById('canvasInstructions').classList.remove('hidden');
                @endif
            }
            
            // Load booking data and merge with floorplan items
            function loadAndMergeBookingData() {
                // The backend now provides owner_details directly in the floorplan items
                // So we just need to log the data and redraw the canvas
                console.log('Shapes with owner details from backend:', shapes);
                
                // Log any additional booking data that might be needed
                const eventBookings = @json($event->bookings ?? []);
                console.log('Event bookings loaded:', eventBookings);
                
                // Redraw canvas with the data provided by backend
                redrawCanvas();
            }
            
            // Redraw canvas with all shapes
            function redrawCanvas() {
                // Clear canvas and fill with global background color
                if (window.globalFloorplanColors && window.globalFloorplanColors.bgColor) {
                    ctx.fillStyle = window.globalFloorplanColors.bgColor;
                    ctx.fillRect(0, 0, canvas.width, canvas.height);
                } else {
                    ctx.clearRect(0, 0, canvas.width, canvas.height);
                }
                
                shapes.forEach(shape => {
                    drawShape(shape);
                });
            }
            
            // Draw shape based on type
            function drawShape(shape) {
                ctx.save();
                
                // Convert string values to numbers (database stores as strings)
                shape.x = parseFloat(shape.x) || 0;
                shape.y = parseFloat(shape.y) || 0;
                shape.width = parseFloat(shape.width) || 40;
                shape.height = parseFloat(shape.height) || 40;
                shape.rotation = parseFloat(shape.rotation) || 0;
                shape.radius = parseFloat(shape.radius) || 25;
                shape.size = parseFloat(shape.size) || 50;
                shape.borderWidth = parseInt(shape.borderWidth) || 2;
                
                // Set fill and stroke based on booking status with property inheritance
                let fillColor, strokeColor;
                
                // Only apply status-based colors to bookable items
                if (shape.bookable && shape.booking_status) {
                    // First check if it's my own current booking (regardless of status)
                    if (shape.is_current_booking) {
                        // My own booking - Green (Success/Ownership) regardless of status
                        fillColor = '#10b981';
                        strokeColor = '#059669';
                    } else if (shape.booking_status === 'booked') {
                        // Booked/Confirmed by others - Use floorplan default booked color
                        fillColor = window.floorplanDefaults?.default_booked_color || '#dc2626';
                        strokeColor = window.floorplanDefaults?.default_booked_color || '#dc2626';
                    } else if (shape.booking_status === 'reserved') {
                        // Reserved by others - Use floorplan default reserved color
                        fillColor = window.floorplanDefaults?.default_reserved_color || '#f59e0b';
                        strokeColor = window.floorplanDefaults?.default_reserved_color || '#f59e0b';
                    } else if (shape.booking_status === 'available') {
                        // Available - Use floorplan default fill color (inheritance)
                        fillColor = shape.fill_color || window.floorplanDefaults?.fill_color || '#e5e7eb';
                        strokeColor = shape.stroke_color || window.floorplanDefaults?.stroke_color || '#d1d5db';
                    } else {
                        // Fallback to inheritance system
                        fillColor = shape.fill_color || window.floorplanDefaults?.fill_color || '#e5e7eb';
                        strokeColor = shape.stroke_color || window.floorplanDefaults?.stroke_color || '#d1d5db';
                    }
                } else {
                    // Non-bookable items use inheritance system
                    fillColor = shape.fill_color || window.floorplanDefaults?.fill_color || '#e5e7eb';
                    strokeColor = shape.stroke_color || window.floorplanDefaults?.stroke_color || '#d1d5db';
                }
                
                // Apply all inherited properties for consistent styling
                ctx.fillStyle = fillColor;
                ctx.strokeStyle = strokeColor;
                ctx.lineWidth = shape.border_width || window.floorplanDefaults?.border_width || 2;
                ctx.font = `${shape.font_size || window.floorplanDefaults?.font_size || 12}px ${shape.font_family || window.floorplanDefaults?.font_family || 'Arial'}`;
                
                // Apply rotation if exists
                if (shape.rotation && shape.rotation !== 0) {
                    const centerX = shape.x + shape.width / 2;
                    const centerY = shape.y + shape.height / 2;
                    ctx.translate(centerX, centerY);
                    ctx.rotate(shape.rotation * Math.PI / 180);
                    ctx.translate(-centerX, -centerY);
                }
                
                // Draw based on type
                switch(shape.type) {
                    case 'rectangle':
                        ctx.fillRect(shape.x, shape.y, shape.width, shape.height);
                        ctx.strokeRect(shape.x, shape.y, shape.width, shape.height);
                        break;
                        
                    case 'circle':
                        const centerX = shape.x + (shape.radius || 25);
                        const centerY = shape.y + (shape.radius || 25);
                        ctx.beginPath();
                        ctx.arc(centerX, centerY, shape.radius, 0, 2 * Math.PI);
                        ctx.fill();
                        ctx.stroke();
                        break;
                        
                    case 'triangle':
                        const size = shape.size || 50;
                        ctx.beginPath();
                        ctx.moveTo(shape.x + size/2, shape.y);
                        ctx.lineTo(shape.x, shape.y + size);
                        ctx.lineTo(shape.x + size, shape.y + size);
                        ctx.closePath();
                        ctx.fill();
                        ctx.stroke();
                        break;
                        
                    case 'pentagon':
                        const pentagonSize = shape.size || 50;
                        ctx.beginPath();
                        for (let i = 0; i < 5; i++) {
                            const angle = (i * 2 * Math.PI) / 5 - Math.PI / 2;
                            const x = shape.x + pentagonSize/2 + (pentagonSize/2) * Math.cos(angle);
                            const y = shape.y + pentagonSize/2 + (pentagonSize/2) * Math.sin(angle);
                            if (i === 0) ctx.moveTo(x, y);
                            else ctx.lineTo(x, y);
                        }
                        ctx.closePath();
                        ctx.fill();
                        ctx.stroke();
                        break;
                        
                    case 'hexagon':
                        const hexagonSize = shape.size || 50;
                        ctx.beginPath();
                        for (let i = 0; i < 6; i++) {
                            const angle = (i * 2 * Math.PI) / 6;
                            const x = shape.x + hexagonSize/2 + (hexagonSize/2) * Math.cos(angle);
                            const y = shape.y + hexagonSize/2 + (hexagonSize/2) * Math.sin(angle);
                            if (i === 0) ctx.moveTo(x, y);
                            else ctx.lineTo(x, y);
                        }
                        ctx.closePath();
                        ctx.fill();
                        ctx.stroke();
                        break;
                        
                    case 'booth':
                        drawBooth(shape, fillColor, strokeColor);
                        break;
                        
                    case 'table':
                        drawTable(shape, fillColor, strokeColor);
                        break;
                        
                    case 'chair':
                        drawChair(shape, fillColor, strokeColor);
                        break;
                        
                    case 'desk':
                        drawDesk(shape, fillColor, strokeColor);
                        break;
                        
                    case 'counter':
                        drawCounter(shape, fillColor, strokeColor);
                        break;
                        
                    case 'stage':
                        drawStage(shape, fillColor, strokeColor);
                        break;
                        
                    case 'screen':
                        drawScreen(shape, fillColor, strokeColor);
                        break;
                        
                    case 'projector':
                        drawProjector(shape, fillColor, strokeColor);
                        break;
                        
                    case 'banner':
                        drawBanner(shape, fillColor, strokeColor);
                        break;
                        
                    case 'kiosk':
                        drawKiosk(shape, fillColor, strokeColor);
                        break;
                        
                    case 'person':
                        drawPerson(shape, fillColor, strokeColor);
                        break;
                        
                    case 'group':
                        drawGroup(shape, fillColor, strokeColor);
                        break;
                        
                    case 'entrance':
                        drawEntrance(shape);
                        break;
                        
                    case 'exit':
                        drawExit(shape);
                        break;
                        
                    case 'elevator':
                        drawElevator(shape);
                        break;
                        
                    case 'stairs':
                        drawStairs(shape);
                        break;
                        
                    case 'restroom':
                        drawRestroom(shape);
                        break;
                        
                    case 'security':
                        drawSecurity(shape);
                        break;
                        
                    case 'firstaid':
                        drawFirstAid(shape);
                        break;
                        
                    case 'fire':
                        drawFireSafety(shape);
                        break;
                        
                    case 'power':
                        drawPowerOutlet(shape);
                        break;
                        
                    case 'tree':
                        drawTree(shape);
                        break;
                        
                    case 'road':
                        drawRoad(shape);
                        break;
                        
                    case 'building':
                        drawBuilding(shape);
                        break;
                        
                    case 'parking':
                        drawParking(shape);
                        break;
                        
                    case 'fountain':
                        drawFountain(shape);
                        break;
                        
                    case 'garden':
                        drawGarden(shape);
                        break;
                    case 'text':
                        const centerTX = shape.x + (shape.width || 120) / 2;
                        const centerTY = shape.y + (shape.height || 30) / 2;
                        
                        // Debug: Log text item properties
                        if (shape.type === 'text') {
                            console.log('Text item properties:', {
                                fill_color: shape.fill_color,
                                text_color: shape.text_color,
                                stroke_color: shape.stroke_color,
                                text_content: shape.text_content,
                                text: shape.text,
                                font_size: shape.font_size,
                                font_family: shape.font_family
                            });
                        }
                        
                        // Apply rotation if shape has rotation
                        if (shape.rotation && shape.rotation !== 0) {
                            ctx.save();
                            ctx.translate(centerTX, centerTY);
                            ctx.rotate(shape.rotation * Math.PI / 180);
                            ctx.translate(-centerTX, -centerTY);
                        }
                        
                        // Draw text background/fill if fill color is specified
                        if (shape.fill_color || window.floorplanDefaults?.fill_color) {
                            const fillColor = shape.fill_color || window.floorplanDefaults?.fill_color;
                            ctx.fillStyle = fillColor;
                            ctx.fillRect(shape.x, shape.y, shape.width || 120, shape.height || 30);
                        }
                        
                        // Set text properties - use inheritance
                        ctx.fillStyle = shape.text_color || window.floorplanDefaults?.text_color || '#111827';
                        ctx.font = `${shape.font_size || window.floorplanDefaults?.font_size || 16}px ${shape.font_family || window.floorplanDefaults?.font_family || 'Arial'}`;
                        ctx.textAlign = 'center';
                        ctx.textBaseline = 'middle';
                        
                        // Draw text at center of the shape
                        ctx.fillText(shape.text_content || shape.text || 'TEXT', centerTX, centerTY);
                        
                        // Draw text boundary rectangle for visual feedback - use inheritance
                        ctx.strokeStyle = shape.stroke_color || window.floorplanDefaults?.stroke_color || 'rgba(0,0,0,0.1)';
                        ctx.lineWidth = shape.border_width || window.floorplanDefaults?.border_width || 1;
                        ctx.strokeRect(shape.x, shape.y, shape.width || 120, shape.height || 30);
                        
                        // Restore canvas context if rotation was applied
                        if (shape.rotation && shape.rotation !== 0) {
                            ctx.restore();
                        }
                        break;
                            
                    default:
                        // Default rectangle for unknown types
                        ctx.fillRect(shape.x, shape.y, shape.width || 40, shape.height || 40);
                        ctx.strokeRect(shape.x, shape.y, shape.width || 40, shape.height || 40);
                        break;
                }
                
                // Draw label if exists
                if (shape.label) {
                    drawItemLabel(shape);
                }
                
                ctx.restore();
            }
            
            // Draw item label
            function drawItemLabel(shape) {
                if (shape.label) {
                    let width, height;
                    
                    if (shape.type === 'circle') {
                        width = (shape.radius || 30) * 2;
                        height = (shape.radius || 30) * 2;
                    } else if (shape.type === 'triangle' || shape.type === 'pentagon' || shape.type === 'hexagon') {
                        width = shape.size || 40;
                        height = shape.size || 40;
                    } else if (shape.type === 'arrow' || shape.type === 'line') {
                        width = shape.width || 80;
                        height = shape.type === 'line' ? Math.max(shape.height || 2, 8) : shape.height || 20;
                    } else {
                        width = shape.width || shape.size || 40;
                        height = shape.height || shape.size || 40;
                    }
                    
                    const centerX = shape.type === 'circle' ? (shape.x + (shape.radius || width/2)) : (shape.x + width / 2);
                    const centerY = shape.type === 'circle' ? (shape.y + (shape.radius || height/2)) : (shape.y + height / 2);

                    // Measure text to size background - use inherited font properties
                    const labelFontSize = shape.label_font_size || window.floorplanDefaults?.default_label_font_size || 10;
                    const labelFontFamily = window.floorplanDefaults?.font_family || 'Arial';
                    ctx.font = `bold ${labelFontSize}px ${labelFontFamily}`;
                    ctx.textAlign = 'center';
                    ctx.textBaseline = 'middle';
                    const textMetrics = ctx.measureText(shape.label);
                    const textW = Math.ceil(textMetrics.width);
                    const padX = 6;
                    const padY = 4;
                    const boxW = Math.max(24, textW + padX * 2);
                    const boxH = 16;

                                         // Determine position
                     let boxX = centerX - boxW / 2;
                     let boxY = centerY - boxH / 2;
                     const offset = 6; // small gap from item edge
                     const position = shape.label_position || window.floorplanDefaults?.default_label_position || 'top';

                     if (position === 'top') {
                         boxX = centerX - boxW / 2;
                         boxY = shape.y - boxH - offset;
                     } else if (position === 'bottom') {
                         boxX = centerX - boxW / 2;
                         boxY = shape.y + height + offset;
                     } else if (position === 'left') {
                         boxX = shape.x - boxW - offset;
                         boxY = centerY - boxH / 2;
                     } else if (position === 'right') {
                         boxX = shape.x + width + offset;
                         boxY = centerY - boxH / 2;
                     } else if (position === 'center') {
                         boxX = centerX - boxW / 2;
                         boxY = centerY - boxH / 2;
                     }

                     // Round coordinates to prevent blurring
                     boxX = Math.round(boxX);
                     boxY = Math.round(boxY);
                     const textX = Math.round(boxX + boxW / 2);
                     const textY = Math.round(boxY + boxH / 2);

                     // Draw label background and border - use inherited colors
                     const labelBgColor = shape.label_background_color || window.floorplanDefaults?.default_label_background_color || 'rgba(0, 123, 255, 0.9)';
                     const labelBorderColor = shape.stroke_color || window.floorplanDefaults?.stroke_color || '#007bff';
                     ctx.fillStyle = labelBgColor;
                     ctx.fillRect(boxX, boxY, boxW, boxH);
                     ctx.strokeStyle = labelBorderColor;
                     ctx.lineWidth = 1;
                     ctx.strokeRect(boxX, boxY, boxW, boxH);

                     // Draw text with improved rendering - use inherited text color and font
                     const labelTextColor = shape.label_color || window.floorplanDefaults?.default_label_color || '#ffffff';
                     ctx.fillStyle = labelTextColor;
                     ctx.font = `bold ${labelFontSize}px ${labelFontFamily}`; // Use inherited font properties
                     ctx.textAlign = 'center';
                     ctx.textBaseline = 'middle';
                     ctx.fillText(shape.label, textX, textY);

                    // reset alignments for other drawings
                    ctx.textAlign = 'start';
                    ctx.textBaseline = 'alphabetic';
                }
            }
            
            // Check if point is inside shape for click detection
            function isPointInShape(x, y, shape) {
                // Shape values should already be converted to numbers in drawShape
                switch(shape.type) {
                    case 'rectangle':
                        return x >= shape.x && x <= shape.x + shape.width && 
                               y >= shape.y && y <= shape.y + shape.height;
                        
                    case 'circle':
                        const centerX = shape.x + shape.radius;
                        const centerY = shape.y + shape.radius;
                        const distance = Math.sqrt((x - centerX) ** 2 + (y - centerY) ** 2);
                        return distance <= shape.radius;
                        
                    case 'triangle':
                        return x >= shape.x && x <= shape.x + shape.size && 
                               y >= shape.y && y <= shape.y + shape.size;
                        
                    case 'pentagon':
                    case 'hexagon':
                        return x >= shape.x && x <= shape.x + shape.size && 
                               y >= shape.y && y <= shape.y + shape.size;
                        
                    default:
                        return x >= shape.x && x <= shape.x + shape.width && 
                               y >= shape.y && y <= shape.y + shape.height;
                }
            }
            
            // Canvas click event for item selection
            canvas.addEventListener('click', function(e) {
                const rect = canvas.getBoundingClientRect();
                
                // Calculate the scaling factor to account for responsive sizing
                const scaleX = canvas.width / rect.width;
                const scaleY = canvas.height / rect.height;
                
                // Adjust click coordinates for canvas scaling
                const x = (e.clientX - rect.left) * scaleX;
                const y = (e.clientY - rect.top) * scaleY;
                
                // Check if click is on any shape
                let clickedShape = null;
                for (let i = shapes.length - 1; i >= 0; i--) {
                    if (isPointInShape(x, y, shapes[i])) {
                        clickedShape = shapes[i];
                        break;
                    }
                }
                
                if (clickedShape) {
                    // Only show popup for bookable items
                    if (clickedShape.bookable) {
                        showItemInfo(clickedShape, e);
                    } else {
                        hideItemInfo();
                    }
                } else {
                    hideItemInfo();
                }
            });
            
            // Show item info panel for bookable items
            function showItemInfo(shape, event) {
                const panel = document.getElementById('itemInfoPanel');
                
                // Update item type label based on shape type
                const itemTypeLabel = document.getElementById('itemTypeLabel');
                const typeLabels = {
                    'booth': 'Booth Details',
                    'table': 'Table Details',
                    'desk': 'Desk Details', 
                    'counter': 'Counter Details',
                    'stage': 'Stage Details',
                    'rectangle': 'Space Details',
                    'circle': 'Space Details',
                    'triangle': 'Space Details',
                    'pentagon': 'Space Details',
                    'hexagon': 'Space Details'
                };
                
                itemTypeLabel.textContent = typeLabels[shape.type] || 'Space Details';
                
                // Populate panel with shape data
                document.getElementById('itemName').textContent = shape.item_name || shape.label || shape.type;
                document.getElementById('itemMaxCapacity').textContent = shape.max_capacity || 5;
                document.getElementById('itemPrice').textContent = `$${shape.price || 100}`;
                
                // Show/hide and populate booth size information
                const boothSizeInfo = document.getElementById('boothSizeInfo');
                if (shape.type === 'booth') {
                    boothSizeInfo.style.display = 'block';
                    // Use item-specific booth size or inherit from floorplan defaults
                    const boothWidth = shape.booth_width_meters || window.floorplanDefaults?.default_booth_width_meters || 3.0;
                    const boothHeight = shape.booth_height_meters || window.floorplanDefaults?.default_booth_height_meters || 2.0;
                    document.getElementById('boothWidthMeters').textContent = `${boothWidth}m`;
                    document.getElementById('boothHeightMeters').textContent = `${boothHeight}m`;
                } else {
                    boothSizeInfo.style.display = 'none';
                }
                
                // Set status based on booking status
                const statusElement = document.getElementById('itemStatus');
                const statusText = document.getElementById('statusText');
                const bookNowGroup = document.getElementById('bookNowGroup');
                const bookNowBtn = document.getElementById('bookNowBtn');
                
                // Show/hide company info section
                const companyInfo = document.getElementById('companyInfo');
                
                // Reset button state for each popup
                bookNowBtn.style.display = 'block';
                bookNowBtn.className = 'btn btn-primary w-100 fw-bold text-decoration-none';
                bookNowBtn.innerHTML = '<i class="bi bi-bookmark-plus me-2"></i>Book Now';
                bookNowBtn.href = '#';
                statusText.textContent = 'Ready to book';
                
                if (shape.booking_status === 'available') {
                    statusElement.textContent = 'Available';
                    statusElement.className = 'badge bg-success';
                    statusText.textContent = 'Ready to book';
                    bookNowGroup.style.display = 'block';
                    companyInfo.style.display = 'none';
                    
                    // Check if user has current booking
                    @if($existingBooking)
                    if ({{ $existingBooking->floorplan_item_id ?? 0 }} === shape.id) {
                        // This is the user's current space
                        statusElement.textContent = 'Your Current Space';
                        statusElement.className = 'badge bg-primary';
                        statusText.textContent = 'Manage your booking';
                        bookNowBtn.innerHTML = '<i class="bi bi-eye me-2"></i>View Details';
                        bookNowBtn.href = '{{ $existingBooking ? route("bookings.owner-form-token", ["eventSlug" => $event->slug, "accessToken" => $existingBooking->access_token]) : "#" }}';
                        bookNowBtn.className = 'btn btn-outline-primary w-100 fw-bold text-decoration-none';
                    } else {
                        // Check if this is an upgrade (more expensive booth)
                        const currentPrice = {{ $existingBooking->floorplanItem->price ?? 0 }};
                        const newPrice = shape.price || 0;
                        
                        if (newPrice > currentPrice) {
                            // Show upgrade option
                            bookNowBtn.innerHTML = '<i class="bi bi-arrow-up-circle me-2"></i>Upgrade to This Space';
                            bookNowBtn.href = `/event/{{ $event->slug }}/booking/{{ $existingBooking->access_token }}/change-space/${shape.id}`;
                            bookNowBtn.className = 'btn btn-success w-100 fw-bold text-decoration-none';
                            
                            // Add price difference info
                            const priceDiff = newPrice - currentPrice;
                            statusText.innerHTML = `Upgrade available! Additional cost: $${priceDiff.toFixed(2)}`;
                        } else {
                            // Hide button for same price or cheaper booths
                            bookNowBtn.style.display = 'none';
                            statusText.textContent = 'This space is not an upgrade option';
                        }
                    }
                    @else
                    // Set the booking URL for new users
                    const eventSlug = '{{ $event->slug }}';
                    bookNowBtn.href = `/event/${eventSlug}/book/${shape.id}`;
                    @endif
                } else if (shape.booking_status === 'reserved') {
                    statusElement.textContent = 'Reserved';
                    statusElement.className = 'badge bg-warning';
                    statusText.textContent = 'This booth is not available for booking';
                    bookNowGroup.style.display = 'none';
                    companyInfo.style.display = 'block';
                    
                    // Debug: Log reserved booth data
                    console.log('Reserved booth data:', shape);
                    
                    // Show company information for reserved booths
                    showCompanyInfo(shape);
                } else if (shape.booking_status === 'booked') {
                    statusElement.textContent = 'Booked';
                    statusElement.className = 'badge bg-secondary';
                    statusText.textContent = 'This booth is not available for booking';
                    bookNowGroup.style.display = 'none';
                    companyInfo.style.display = 'block';
                    
                    // Debug: Log booked booth data
                    console.log('Booked booth data:', shape);
                    
                    // Show company information for booked booths
                    showCompanyInfo(shape);
                } else {
                    statusElement.textContent = 'Unavailable';
                    statusElement.className = 'badge bg-danger';
                    statusText.textContent = 'This booth is not available for booking';
                    bookNowGroup.style.display = 'none';
                    companyInfo.style.display = 'none';
                }
                
                // Show panel with smooth slide-in animation
                panel.style.display = 'block';
                
                // Trigger slide-in animation after a brief delay
                setTimeout(() => {
                    panel.style.right = '0px';
                }, 10);
            }
            
            // Show company information for reserved/booked booths
            function showCompanyInfo(shape) {
                const companyName = document.getElementById('companyName');
                const companyContact = document.getElementById('companyContact');
                const companyEmail = document.getElementById('companyEmail');
                const companyLogo = document.getElementById('companyLogo');
                const companyLogoIcon = document.getElementById('companyLogoIcon');
                
                // Debug: Log the shape data to see what we're working with
                console.log('Company info shape data:', shape);
                console.log('Owner details:', shape.owner_details);
                
                // Get company details from the shape data
                if (shape.owner_details) {
                    companyName.textContent = shape.owner_details.company_name || 'Company not specified';
                    companyContact.textContent = shape.owner_details.name || 'Contact not specified';
                    companyEmail.textContent = shape.owner_details.email || 'Email not specified';
                    
                    // Handle company logo - use the same approach as owner form
                    const logoPath = shape.owner_details.company_logo;
                    
                    console.log('Logo path from owner_details:', logoPath);
                    
                    if (logoPath) {
                        // Use the same Storage::url() approach as the owner form
                        // The path is relative to storage/app/public/ (e.g., "company-logos/filename.png")
                        // This matches the Laravel Storage::url() behavior exactly like in owner-form.blade.php
                        const logoUrl = `/storage/${logoPath}`;
                        
                        companyLogo.src = logoUrl;
                        companyLogo.classList.remove('d-none');
                        companyLogoIcon.classList.add('d-none');
                        console.log('Logo displayed:', logoUrl);
                        console.log('Original path:', logoPath, 'Constructed URL:', logoUrl);
                    } else {
                        companyLogo.classList.add('d-none');
                        companyLogoIcon.classList.remove('d-none');
                        console.log('No logo found, showing fallback icon');
                    }
                } else {
                    companyName.textContent = 'Company not specified';
                    companyContact.textContent = 'Contact not specified';
                    companyEmail.textContent = 'Email not specified';
                    companyLogo.classList.add('d-none');
                    companyLogoIcon.classList.remove('d-none');
                    console.log('No owner details found');
                }
            }
            

            
            // Position popup intelligently to avoid going off-screen
            function positionPopup(event, panel) {
                const rect = canvas.getBoundingClientRect();
                const clickX = event.clientX;
                const clickY = event.clientY;
                
                // Get panel dimensions (updated for compact popup)
                const panelWidth = 320;
                const panelHeight = 280; // Reduced height for compact content
                
                // On mobile devices (small screens), center the popup
                if (window.innerWidth < 768) {
                    const left = (window.innerWidth - panelWidth) / 2;
                    const top = Math.max(20, clickY - panelHeight / 2);
                    
                    panel.style.left = Math.max(10, left) + 'px';
                    panel.style.top = Math.min(window.innerHeight - panelHeight - 20, top) + 'px';
                    return;
                }
                
                // Desktop positioning with intelligent space usage
                let left = clickX + 20; // 20px offset from click
                let top = clickY - panelHeight / 2; // Center vertically on click
                
                // Adjust if going off right edge
                if (left + panelWidth > window.innerWidth) {
                    left = clickX - panelWidth - 20;
                }
                
                // Intelligent vertical positioning - prefer space above if available
                const spaceAbove = clickY - 20;
                const spaceBelow = window.innerHeight - clickY - 20;
                
                if (spaceAbove >= panelHeight) {
                    // Position above click point if there's enough space
                    top = clickY - panelHeight - 10;
                } else if (spaceBelow >= panelHeight) {
                    // Position below click point if there's enough space below
                    top = clickY + 10;
                } else {
                    // Center on click point if neither above nor below has enough space
                    top = clickY - panelHeight / 2;
                }
                
                // Ensure popup stays within viewport bounds
                if (top < 20) {
                    top = 20;
                }
                if (top + panelHeight > window.innerHeight - 20) {
                    top = window.innerHeight - panelHeight - 20;
                }
                
                panel.style.left = left + 'px';
                panel.style.top = top + 'px';
            }
            
            // Hide item info panel with smooth slide-out animation
            function hideItemInfo() {
                const panel = document.getElementById('itemInfoPanel');
                
                // Slide out to the right
                panel.style.right = '-350px';
                
                // Wait for animation to complete before hiding
                setTimeout(() => {
                    panel.style.display = 'none';
                }, 300);
            }
            
            // Close item info panel
            document.getElementById('closeItemInfo').addEventListener('click', function() {
                hideItemInfo();
            });
            
            // Hide popup when clicking backdrop or outside
            document.addEventListener('click', function(e) {
                const panel = document.getElementById('itemInfoPanel');
                const canvas = document.getElementById('floorplanCanvas');
                const backdrop = panel.querySelector('.position-absolute');
                
                // Close if clicking backdrop or outside panel
                if ((backdrop && backdrop.contains(e.target)) || (!panel.contains(e.target) && !canvas.contains(e.target))) {
                    hideItemInfo();
                }
            });
            
            // Make popup draggable
            let isDragging = false;
            let dragOffsetX = 0;
            let dragOffsetY = 0;
            
            // Add drag functionality to popup header
            const popupHeader = document.querySelector('#itemInfoPanel .card-header');
            if (popupHeader) {
                popupHeader.style.cursor = 'move';
                popupHeader.style.userSelect = 'none'; // Prevent text selection while dragging
                popupHeader.title = 'Drag to move popup';
                
                // Add visual feedback for draggable area
                popupHeader.style.position = 'relative';
                
                popupHeader.addEventListener('mousedown', startDrag);
                
                // Add touch support for mobile devices
                popupHeader.addEventListener('touchstart', startTouchDrag);
            }
            
            function startDrag(e) {
                if (e.target.closest('.btn-close')) return; // Don't drag when clicking close button
                
                isDragging = true;
                const panel = document.getElementById('itemInfoPanel');
                const rect = panel.getBoundingClientRect();
                
                dragOffsetX = e.clientX - rect.left;
                dragOffsetY = e.clientY - rect.top;
                
                document.addEventListener('mousemove', drag);
                document.addEventListener('mouseup', stopDrag);
                
                e.preventDefault();
            }
            
            function startTouchDrag(e) {
                if (e.target.closest('.btn-close')) return; // Don't drag when touching close button
                
                isDragging = true;
                const panel = document.getElementById('itemInfoPanel');
                const rect = panel.getBoundingClientRect();
                const touch = e.touches[0];
                
                dragOffsetX = touch.clientX - rect.left;
                dragOffsetY = touch.clientY - rect.top;
                
                document.addEventListener('touchmove', touchDrag);
                document.addEventListener('touchend', stopDrag);
                
                e.preventDefault();
            }
            
            function drag(e) {
                if (!isDragging) return;
                
                const panel = document.getElementById('itemInfoPanel');
                const newX = e.clientX - dragOffsetX;
                const newY = e.clientY - dragOffsetY;
                
                // Keep popup within viewport bounds
                const maxX = window.innerWidth - panel.offsetWidth;
                const maxY = window.innerHeight - panel.offsetHeight;
                
                panel.style.left = Math.max(0, Math.min(newX, maxX)) + 'px';
                panel.style.top = Math.max(0, Math.min(newY, maxY)) + 'px';
            }
            
            function touchDrag(e) {
                if (!isDragging) return;
                
                const panel = document.getElementById('itemInfoPanel');
                const touch = e.touches[0];
                const newX = touch.clientX - dragOffsetX;
                const newY = touch.clientY - dragOffsetY;
                
                // Keep popup within viewport bounds
                const maxX = window.innerWidth - panel.offsetWidth;
                const maxY = window.innerHeight - panel.offsetHeight;
                
                panel.style.left = Math.max(0, Math.min(newX, maxX)) + 'px';
                panel.style.top = Math.max(0, Math.min(newY, maxY)) + 'px';
                
                e.preventDefault();
            }
            
            function stopDrag() {
                isDragging = false;
                document.removeEventListener('mousemove', drag);
                document.removeEventListener('mouseup', stopDrag);
                document.removeEventListener('touchmove', touchDrag);
                document.removeEventListener('touchend', stopDrag);
            }
            
            // Book Now button is now a link, no click handler needed
            
            // Drawing functions for different item types
            function drawBooth(shape, fillColor, strokeColor) {
                const width = shape.width || 80;
                const height = shape.height || 80;
                const centerX = shape.x + width/2;
                const centerY = shape.y + height/2;
                
                // Apply rotation if shape has rotation
                if (shape.rotation && shape.rotation !== 0) {
                    ctx.save();
                    ctx.translate(centerX, centerY);
                    ctx.rotate(shape.rotation * Math.PI / 180);
                    ctx.translate(-centerX, -centerY);
                }
                
                // Main booth floor/base - use passed colors for status indication
                ctx.fillStyle = fillColor;
                ctx.fillRect(shape.x, shape.y, width, height);
                ctx.strokeStyle = strokeColor;
                ctx.lineWidth = 2;
                ctx.strokeRect(shape.x, shape.y, width, height);
                
                // Back wall panels
                ctx.fillStyle = fillColor; // Light lavender panels
                const backPanelHeight = height * 0.8;
                ctx.fillRect(shape.x + 5, shape.y + 5, width - 10, backPanelHeight);
                ctx.strokeStyle = strokeColor;
                ctx.lineWidth = 1;
                ctx.strokeRect(shape.x + 5, shape.y + 5, width - 10, backPanelHeight);
                
                // Side panels
                const sidePanelWidth = width * 0.15;
                ctx.fillStyle = '#D8BFD8'; // Thistle color
                // Left side panel
                ctx.fillRect(shape.x, shape.y + height * 0.3, sidePanelWidth, height * 0.5);
                ctx.strokeRect(shape.x, shape.y + height * 0.3, sidePanelWidth, height * 0.5);
                // Right side panel
                ctx.fillRect(shape.x + width - sidePanelWidth, shape.y + height * 0.3, sidePanelWidth, height * 0.5);
                ctx.strokeRect(shape.x + width - sidePanelWidth, shape.y + height * 0.3, sidePanelWidth, height * 0.5);
                
                // Display counter/table in front
                ctx.fillStyle = '#8B4513'; // Brown wood
                const counterWidth = width * 0.6;
                const counterHeight = height * 0.15;
                const counterX = shape.x + (width - counterWidth) / 2;
                const counterY = shape.y + height - counterHeight - 5;
                ctx.fillRect(counterX, counterY, counterWidth, counterHeight);
                ctx.strokeStyle = '#654321';
                ctx.strokeRect(counterX, counterY, counterWidth, counterHeight);
                
                // Counter legs
                ctx.fillStyle = '#654321';
                const legWidth = 3;
                ctx.fillRect(counterX + 5, counterY + counterHeight, legWidth, 8);
                ctx.fillRect(counterX + counterWidth - 8, counterY + counterHeight, legWidth, 8);
                
                
                // Booth branding/logo area (center of back wall)
                ctx.fillStyle =  shape.fill_color || window.floorplanDefaults?.fill_color || '#FFD700'; // Gold background for logo
                const logoWidth = width * 0.5;
                const logoHeight = height * 0.18;
                const logoX = shape.x + (width - logoWidth) / 2;
                const logoY = shape.y + height * 0.1;
                ctx.fillRect(logoX, logoY, logoWidth, logoHeight);
                ctx.strokeStyle = shape.stroke_color || window.floorplanDefaults?.stroke_color || '#FF8C00';
                ctx.lineWidth = 2;
                ctx.strokeRect(logoX, logoY, logoWidth, logoHeight);
                
                // Company name placeholder - use inheritance for font properties
                ctx.fillStyle = shape.text_color || window.floorplanDefaults?.text_color || '#0000FF';
                ctx.font = `${shape.font_size || window.floorplanDefaults?.font_size || 8}px ${shape.font_family || window.floorplanDefaults?.font_family || 'Arial'}`;
                ctx.textAlign = 'center';
                ctx.textBaseline = 'middle';
                const textX = Math.round(logoX + logoWidth/2);
                const textY = Math.round(logoY + logoHeight/2 + 3);
                ctx.fillText('BOOTH', textX, textY);
                ctx.textAlign = 'start';

                
                ctx.textBaseline = 'alphabetic';
                
                // Promotional materials/brochures on counter
                ctx.fillStyle = '#FFF';
                for (let i = 0; i < 3; i++) {
                    const brochureX = counterX + 10 + i * 15;
                    const brochureY = counterY - 8;
                    ctx.fillRect(brochureX, brochureY, 8, 6);
                    ctx.strokeStyle = '#CCC';
                    ctx.lineWidth = 0.5;
                    ctx.strokeRect(brochureX, brochureY, 8, 6);
                }
                
                // Entrance carpet
                ctx.fillStyle = '#DC143C'; // Red carpet
                const carpetWidth = width * 0.8;
                const carpetHeight = height * 0.1;
                ctx.fillRect(shape.x + (width - carpetWidth) / 2, shape.y + height - carpetHeight, carpetWidth, carpetHeight);
                
                // Booth lighting (ceiling spotlights representation)
                ctx.fillStyle = '#FFFF99'; // Light yellow
                for (let i = 0; i < 3; i++) {
                    const lightX = shape.x + width * 0.2 + i * width * 0.3;
                    const lightY = shape.y + 2;
                    ctx.beginPath();
                    ctx.arc(lightX, lightY, 3, 0, 2 * Math.PI);
                    ctx.fill();
                }
                
                // Restore canvas context if rotation was applied
                if (shape.rotation && shape.rotation !== 0) {
                    ctx.restore();
                }
            }
            
            function drawTable(shape, fillColor, strokeColor) {
                const width = shape.width || 80;
                const height = shape.height || 60;
                
                // Table top - use passed colors for status indication
                ctx.fillStyle = fillColor;
                ctx.fillRect(shape.x, shape.y, width, height);
                ctx.strokeStyle = strokeColor;
                ctx.lineWidth = shape.border_width || window.floorplanDefaults?.border_width || 2;
                ctx.strokeRect(shape.x, shape.y, width, height);
                
                // Table legs
                ctx.fillStyle = fillColor;
                const legWidth = 4;
                const legHeight = 15;
                ctx.fillRect(shape.x + 5, shape.y + height, legWidth, legHeight);
                ctx.fillRect(shape.x + width - 9, shape.y + height, legWidth, legHeight);
                ctx.fillRect(shape.x + 5, shape.y + height, legWidth, legHeight);
                ctx.fillRect(shape.x + width - 9, shape.y + height, legWidth, legHeight);
            }
            
            function drawChair(shape, fillColor, strokeColor) {
                const width = shape.width || 25;
                const height = shape.height || 25;
                const centerX = shape.x + width/2;
                const centerY = shape.y + height/2;
                
                // Apply rotation if shape has rotation
                if (shape.rotation && shape.rotation !== 0) {
                    ctx.save();
                    ctx.translate(centerX, centerY);
                    ctx.rotate(shape.rotation * Math.PI / 180);
                    ctx.translate(-centerX, -centerY);
                }
                
                // Backrest (upper part) - use passed colors for status indication
                ctx.fillStyle = fillColor;
                ctx.fillRect(shape.x, shape.y, width, height * 0.4);
                ctx.strokeStyle = strokeColor;
                ctx.lineWidth = shape.border_width || window.floorplanDefaults?.border_width || 1;
                ctx.strokeRect(shape.x, shape.y, width, height * 0.4);
                
                // Seat (lower part) - use passed colors for status indication
                ctx.fillStyle = fillColor;
                ctx.fillRect(shape.x, shape.y + height * 0.4, width, height * 0.6);
                ctx.strokeRect(shape.x, shape.y + height * 0.4, width, height * 0.6);
                
                // Chair legs (4 legs)
                ctx.fillStyle = '#654321';
                const legWidth = 2;
                const legExtension = 3;
                
                // Front legs
                ctx.fillRect(shape.x + 2, shape.y + height, legWidth, legExtension);
                ctx.fillRect(shape.x + width - 4, shape.y + height, legWidth, legExtension);
                
                // Back legs
                ctx.fillRect(shape.x + 2, shape.y + height * 0.4, legWidth, legExtension + height * 0.6);
                ctx.fillRect(shape.x + width - 4, shape.y + height * 0.4, legWidth, legExtension + height * 0.6);
                
                // Chair back vertical slats for detail
                ctx.strokeStyle = '#4A4A4A';
                ctx.lineWidth = shape.border_width || window.floorplanDefaults?.border_width || 1;
                for (let i = 1; i < 4; i++) {
                    const x = shape.x + (width / 4) * i;
                    ctx.beginPath();
                    ctx.moveTo(x, shape.y + 2);
                    ctx.lineTo(x, shape.y + height * 0.4 - 2);
                    ctx.stroke();
                }
                
                // Restore canvas context if rotation was applied
                if (shape.rotation && shape.rotation !== 0) {
                    ctx.restore();
                }
            }
            
            function drawDesk(shape, fillColor, strokeColor) {
                const width = shape.width || 100;
                const height = shape.height || 60;
                const centerX = shape.x + width/2;
                const centerY = shape.y + height/2;
                
                // Apply rotation if shape has rotation
                if (shape.rotation && shape.rotation !== 0) {
                    ctx.save();
                    ctx.translate(centerX, centerY);
                    ctx.rotate(shape.rotation * Math.PI / 180);
                    ctx.translate(-centerX, -centerY);
                }
                
                // Main desk surface - use passed colors for status indication
                ctx.fillStyle = fillColor;
                ctx.fillRect(shape.x, shape.y, width, height);
                ctx.strokeStyle = strokeColor;
                ctx.lineWidth = shape.border_width || window.floorplanDefaults?.border_width || 2;
                ctx.strokeRect(shape.x, shape.y, width, height);
                
                // Desk edge highlight (3D effect)
                ctx.fillStyle = '#A0522D';
                ctx.fillRect(shape.x, shape.y, width, 3);
                ctx.fillRect(shape.x, shape.y, 3, height);
                
                // Monitor/screen area
                ctx.fillStyle = strokeColor;
                const monitorWidth = width * 0.3;
                const monitorHeight = height * 0.35;
                const monitorX = shape.x + width * 0.1;
                const monitorY = shape.y + height * 0.1;
                ctx.fillRect(monitorX, monitorY, monitorWidth, monitorHeight);
                
                // Monitor screen (blue glow)
                ctx.fillStyle = fillColor;
                ctx.fillRect(monitorX + 2, monitorY + 2, monitorWidth - 4, monitorHeight - 4);
                
                // Monitor stand
                ctx.fillStyle = '#696969';
                const standWidth = monitorWidth * 0.3;
                const standHeight = height * 0.15;
                ctx.fillRect(monitorX + (monitorWidth - standWidth)/2, monitorY + monitorHeight, standWidth, standHeight);
                
                // Keyboard area
                ctx.fillStyle = '#F5F5F5';
                const keyboardWidth = width * 0.4;
                const keyboardHeight = height * 0.2;
                const keyboardX = shape.x + width * 0.5;
                const keyboardY = shape.y + height * 0.6;
                ctx.fillRect(keyboardX, keyboardY, keyboardWidth, keyboardHeight);
                ctx.strokeStyle = '#D3D3D3';
                ctx.lineWidth = shape.border_width || window.floorplanDefaults?.border_width || 1;
                ctx.strokeRect(keyboardX, keyboardY, keyboardWidth, keyboardHeight);
                
                // Keyboard keys (small rectangles)
                ctx.fillStyle = '#E0E0E0';
                for (let row = 0; row < 3; row++) {
                    for (let col = 0; col < 8; col++) {
                        const keyX = keyboardX + 5 + col * (keyboardWidth - 10) / 8;
                        const keyY = keyboardY + 3 + row * (keyboardHeight - 6) / 3;
                        const keyW = (keyboardWidth - 15) / 8;
                        const keyH = (keyboardHeight - 9) / 3;
                        ctx.fillRect(keyX, keyY, keyW, keyH);
                    }
                }
                
                // Mouse area
                ctx.fillStyle = '#808080';
                const mouseX = keyboardX + keyboardWidth + 5;
                const mouseY = keyboardY + keyboardHeight * 0.3;
                ctx.beginPath();
                ctx.ellipse(mouseX, mouseY, 8, 12, 0, 0, 2 * Math.PI);
                ctx.fill();
                
                // Desk drawers/storage
                ctx.fillStyle = '#654321';
                ctx.strokeStyle = '#4A4A4A';
                ctx.lineWidth = shape.border_width || window.floorplanDefaults?.border_width || 1;
                
                // Right side drawers
                const drawerWidth = width * 0.25;
                const drawerHeight = height * 0.15;
                for (let i = 0; i < 2; i++) {
                    const drawerX = shape.x + width - drawerWidth - 5;
                    const drawerY = shape.y + height * 0.2 + i * (drawerHeight + 5);
                    ctx.fillRect(drawerX, drawerY, drawerWidth, drawerHeight);
                    ctx.strokeRect(drawerX, drawerY, drawerWidth, drawerHeight);
                    
                    // Drawer handle
                    ctx.fillStyle = '#C0C0C0';
                    ctx.fillRect(drawerX + drawerWidth - 8, drawerY + drawerHeight/2 - 2, 4, 4);
                    ctx.fillStyle = '#654321';
                }
                
                // Desk legs
                ctx.fillStyle = '#654321';
                const legWidth = 6;
                const legHeight = 10;
                
                // 4 legs
                ctx.fillRect(shape.x + 5, shape.y + height + 2, legWidth, legHeight);
                ctx.fillRect(shape.x + width - 11, shape.y + height + 2, legWidth, legHeight);
                ctx.fillRect(shape.x + 5, shape.y + height + 2, legWidth, legHeight);
                ctx.fillRect(shape.x + width - 11, shape.y + height + 2, legWidth, legHeight);
                
                // Restore canvas context if rotation was applied
                if (shape.rotation && shape.rotation !== 0) {
                    ctx.restore();
                }
            }
            
               function drawCounter(shape, fillColor, strokeColor) {
                const width = shape.width || 120;
                const height = shape.height || 30;
                const centerX = shape.x + width/2;
                const centerY = shape.y + height/2;
                
                // Apply rotation if shape has rotation
                if (shape.rotation && shape.rotation !== 0) {
                    ctx.save();
                    ctx.translate(centerX, centerY);
                    ctx.rotate(shape.rotation * Math.PI / 180);
                    ctx.translate(-centerX, -centerY);
                }
                
                // Counter base/cabinet - use passed colors for status indication
                ctx.fillStyle = fillColor;
                ctx.fillRect(shape.x, shape.y, width, height);
                ctx.strokeStyle = strokeColor;
                ctx.lineWidth = shape.border_width || window.floorplanDefaults?.border_width || 2;
                ctx.strokeRect(shape.x, shape.y, width, height);
                
                // Counter top surface (lighter wood)
                ctx.fillStyle = '#D2B48C'; // Tan wood top
                const topHeight = height * 0.2;
                ctx.fillRect(shape.x, shape.y, width, topHeight);
                ctx.strokeStyle = '#A0522D';
                ctx.lineWidth = shape.border_width || window.floorplanDefaults?.border_width || 1;
                ctx.strokeRect(shape.x, shape.y, width, topHeight);
                
                // Counter edge highlight (3D effect)
                ctx.fillStyle = '#F5DEB3'; // Wheat color highlight
                ctx.fillRect(shape.x, shape.y, width, 2);
                ctx.fillRect(shape.x, shape.y, 2, topHeight);
                
                // Cabinet doors/panels
                const panelWidth = width / 3;
                ctx.fillStyle = '#654321';
                ctx.strokeStyle = '#4A4A4A';
                ctx.lineWidth = shape.border_width || window.floorplanDefaults?.border_width || 1;
                
                for (let i = 0; i < 3; i++) {
                    const panelX = shape.x + i * panelWidth;
                    const panelY = shape.y + topHeight + 2;
                    const panelH = height - topHeight - 4;
                    
                    // Panel background
                    ctx.fillRect(panelX + 2, panelY, panelWidth - 4, panelH);
                    ctx.strokeRect(panelX + 2, panelY, panelWidth - 4, panelH);
                    
                    // Panel inset detail
                                    ctx.strokeStyle = '#8B4513';
                ctx.lineWidth = shape.border_width || window.floorplanDefaults?.border_width || 1;
                ctx.strokeRect(panelX + 6, panelY + 4, panelWidth - 12, panelH - 8);
                    
                    // Door handle
                    ctx.fillStyle = '#C0C0C0'; // Silver handle
                    const handleX = panelX + panelWidth - 8;
                    const handleY = panelY + panelH / 2 - 2;
                    ctx.fillRect(handleX, handleY, 3, 4);
                    
                    // Handle shadow
                    ctx.fillStyle = '#808080';
                    ctx.fillRect(handleX + 1, handleY + 1, 2, 3);
                }
                
                // Counter equipment/accessories
                
                // Cash register area (left side)
                ctx.fillStyle = '#2F4F4F'; // Dark slate gray
                const registerWidth = width * 0.25;
                const registerHeight = height * 0.4;
                const registerX = shape.x + 5;
                const registerY = shape.y - registerHeight + topHeight;
                ctx.fillRect(registerX, registerY, registerWidth, registerHeight);
                ctx.strokeStyle = '#000';
                ctx.lineWidth = shape.border_width || window.floorplanDefaults?.border_width || 1;
                ctx.strokeRect(registerX, registerY, registerWidth, registerHeight);
                
                // Cash register screen
                ctx.fillStyle = '#00FF00'; // Green LCD display
                ctx.fillRect(registerX + 3, registerY + 3, registerWidth - 6, registerHeight * 0.3);
                
                // Cash register buttons
                ctx.fillStyle = '#F5F5F5';
                for (let row = 0; row < 2; row++) {
                    for (let col = 0; col < 3; col++) {
                        const btnX = registerX + 3 + col * 6;
                        const btnY = registerY + registerHeight * 0.4 + row * 4;
                        ctx.fillRect(btnX, btnY, 4, 3);
                        ctx.strokeStyle = '#D3D3D3';
                        ctx.lineWidth = (shape.border_width || window.floorplanDefaults?.border_width || 1) * 0.5;
                        ctx.strokeRect(btnX, btnY, 4, 3);
                    }
                }
                
                // Display items/products (center area)
                ctx.fillStyle = '#FFE4B5'; // Moccasin color for products
                for (let i = 0; i < 3; i++) {
                    const itemX = shape.x + width * 0.4 + i * 15;
                    const itemY = shape.y - 8;
                    const itemW = 12;
                    const itemH = 6;
                    ctx.fillRect(itemX, itemY, itemW, itemH);
                    ctx.strokeStyle = '#DEB887';
                    ctx.lineWidth = 0.5;
                    ctx.strokeRect(itemX, itemY, itemW, itemH);
                }
                
                // Information/menu display (right side)
                ctx.fillStyle = '#F0F8FF'; // Alice blue
                const menuWidth = width * 0.2;
                const menuHeight = height * 0.6;
                const menuX = shape.x + width - menuWidth - 5;
                const menuY = shape.y - menuHeight + topHeight;
                ctx.fillRect(menuX, menuY, menuWidth, menuHeight);
                ctx.strokeStyle = '#B0C4DE';
                ctx.lineWidth = 1;
                ctx.strokeRect(menuX, menuY, menuWidth, menuHeight);
                
                // Menu lines (text representation)
                ctx.strokeStyle = '#000';
                ctx.lineWidth = 0.5;
                for (let i = 0; i < 5; i++) {
                    const lineY = menuY + 5 + i * 4;
                    ctx.beginPath();
                    ctx.moveTo(menuX + 2, lineY);
                    ctx.lineTo(menuX + menuWidth - 2, lineY);
                    ctx.stroke();
                }
                
                // Counter support legs/base
                ctx.fillStyle = '#654321';
                const legWidth = 4;
                const legHeight = 6;
                
                // Support legs
                ctx.fillRect(shape.x + 10, shape.y + height, legWidth, legHeight);
                ctx.fillRect(shape.x + width/2 - legWidth/2, shape.y + height, legWidth, legHeight);
                ctx.fillRect(shape.x + width - 14, shape.y + height, legWidth, legHeight);
                
                // Toe kick (bottom recess)
                ctx.fillStyle = '#4A4A4A';
                ctx.fillRect(shape.x + 5, shape.y + height - 3, width - 10, 3);
                
                // Restore canvas context if rotation was applied
                if (shape.rotation && shape.rotation !== 0) {
                    ctx.restore();
                }
            }
            
            function drawStage(shape, fillColor, strokeColor) {
                const width = shape.width || 120;
                const height = shape.height || 60;
                const rotationCenterX = shape.x + width/2;
                const rotationCenterY = shape.y + height/2;
                
                // Apply rotation if shape has rotation
                if (shape.rotation && shape.rotation !== 0) {
                    ctx.save();
                    ctx.translate(rotationCenterX, rotationCenterY);
                    ctx.rotate(shape.rotation * Math.PI / 180);
                    ctx.translate(-rotationCenterX, -rotationCenterY);
                }
                
                // Stage platform/floor (raised surface) - use passed colors for status indication
                ctx.fillStyle = fillColor;
                ctx.fillRect(shape.x, shape.y, width, height);
                ctx.strokeStyle = strokeColor;
                ctx.lineWidth = shape.border_width || window.floorplanDefaults?.border_width || 3;
                ctx.strokeRect(shape.x, shape.y, width, height);
                
                // Stage edge highlight (3D effect)
                ctx.fillStyle = '#A0522D'; // Saddle brown highlight
                ctx.fillRect(shape.x, shape.y, width, 4);
                ctx.fillRect(shape.x, shape.y, 4, height);
                
                // Stage curtains (theatrical red velvet)
                ctx.fillStyle = '#8B0000'; // Dark red velvet
                const curtainWidth = 12;
                const curtainHeight = height * 0.9;
                
                // Left side curtain
                ctx.fillRect(shape.x, shape.y, curtainWidth, curtainHeight);
                ctx.strokeStyle = '#4B0082'; // Indigo border
                ctx.lineWidth = shape.border_width || window.floorplanDefaults?.border_width || 2;
                ctx.strokeRect(shape.x, shape.y, curtainWidth, curtainHeight);
                
                // Right side curtain
                ctx.fillRect(shape.x + width - curtainWidth, shape.y, curtainWidth, curtainHeight);
                ctx.strokeRect(shape.x + width - curtainWidth, shape.y, curtainWidth, curtainHeight);
                
                // Curtain folds/pleats
                ctx.strokeStyle = '#4B0082';
                ctx.lineWidth = shape.border_width || window.floorplanDefaults?.border_width || 1;
                for (let i = 0; i < 3; i++) {
                    const foldX = shape.x + 2 + i * 3;
                    ctx.beginPath();
                    ctx.moveTo(foldX, shape.y);
                    ctx.lineTo(foldX, shape.y + curtainHeight);
                    ctx.stroke();
                    
                    const rightFoldX = shape.x + width - 2 - i * 3;
                    ctx.beginPath();
                    ctx.moveTo(rightFoldX, shape.y);
                    ctx.lineTo(rightFoldX, shape.y + curtainHeight);
                    ctx.stroke();
                }
                
                // Stage floor pattern (wooden planks)
                ctx.strokeStyle = '#FFD700'; // Golden lines
                ctx.lineWidth = shape.border_width || window.floorplanDefaults?.border_width || 2;
                for (let i = 0; i < 4; i++) {
                    const y = shape.y + height - 15 - i * 12;
                    ctx.beginPath();
                    ctx.moveTo(shape.x + 15, y);
                    ctx.lineTo(shape.x + width - 15, y);
                    ctx.stroke();
                }
                
                // Stage steps/access (front)
                ctx.fillStyle = '#654321'; // Brown steps
                const stepHeight = 8;
                const stepWidth = width * 0.6;
                const stepX = shape.x + (width - stepWidth) / 2;
                
                for (let i = 0; i < 2; i++) {
                    const stepY = shape.y + height + i * stepHeight;
                    const currentStepWidth = stepWidth + i * 10;
                    const currentStepX = stepX - i * 5;
                    ctx.fillRect(currentStepX, stepY, currentStepWidth, stepHeight);
                    ctx.strokeStyle = '#8B4513';
                    ctx.lineWidth = shape.border_width || window.floorplanDefaults?.border_width || 1;
                    ctx.strokeRect(currentStepX, stepY, currentStepWidth, stepHeight);
                }
                
                // Stage lighting (ceiling spotlights)
                ctx.fillStyle = '#FFFF99'; // Light yellow
                for (let i = 0; i < 4; i++) {
                    const lightX = shape.x + width * 0.15 + i * width * 0.25;
                    const lightY = shape.y - 5;
                    ctx.beginPath();
                    ctx.arc(lightX, lightY, 4, 0, 2 * Math.PI);
                    ctx.fill();
                    
                    // Light beam effect
                    ctx.strokeStyle = '#FFFF99';
                    ctx.lineWidth = shape.border_width || window.floorplanDefaults?.border_width || 1;
                    ctx.globalAlpha = 0.3;
                    ctx.beginPath();
                    ctx.moveTo(lightX, lightY);
                    ctx.lineTo(lightX, shape.y + height * 0.3);
                    ctx.stroke();
                    ctx.globalAlpha = 1.0;
                }
                
                // Center stage mark (X marking the spot)
                ctx.strokeStyle = '#FFD700';
                ctx.lineWidth = 2;
                const centerX = shape.x + width / 2;
                const centerY = shape.y + height / 2;
                const markSize = 8;
                ctx.beginPath();
                ctx.moveTo(centerX - markSize, centerY - markSize);
                ctx.lineTo(centerX + markSize, centerY + markSize);
                ctx.moveTo(centerX + markSize, centerY - markSize);
                ctx.lineTo(centerX - markSize, centerY + markSize);
                ctx.stroke();
                
                // Stage backdrop (optional)
                ctx.fillStyle = '#2F4F4F'; // Dark slate gray
                ctx.fillRect(shape.x + 15, shape.y + 5, width - 30, height * 0.3);
                ctx.strokeStyle = '#000';
                ctx.lineWidth = 1;
                ctx.strokeRect(shape.x + 15, shape.y + 5, width - 30, height * 0.3);
                
                // Restore canvas context if rotation was applied
                if (shape.rotation && shape.rotation !== 0) {
                    ctx.restore();
                }
            }
            
            function drawScreen(shape, fillColor, strokeColor) {
                const width = shape.width || 80;
                const height = shape.height || 60;
                const centerX = shape.x + width/2;
                const centerY = shape.y + height/2;
                
                // Apply rotation if shape has rotation
                if (shape.rotation && shape.rotation !== 0) {
                    ctx.save();
                    ctx.translate(centerX, centerY);
                    ctx.rotate(shape.rotation * Math.PI / 180);
                    ctx.translate(-centerX, -centerY);
                }
                
                // Screen bezel/frame (outer black frame) - use passed colors for status indication
                ctx.fillStyle = fillColor;
                ctx.fillRect(shape.x, shape.y, width, height);
                ctx.strokeStyle = strokeColor;
                ctx.lineWidth = 3;
                ctx.strokeRect(shape.x, shape.y, width, height);
                
                // Screen display area (inner screen)
                const screenPadding = 6;
                const screenWidth = width - screenPadding * 2;
                const screenHeight = height - screenPadding * 2;
                const screenX = shape.x + screenPadding;
                const screenY = shape.y + screenPadding;
                
                // Active screen (blue glow when on)
                ctx.fillStyle = '#1E90FF'; // Dodger blue screen
                ctx.fillRect(screenX, screenY, screenWidth, screenHeight);
                
                // Screen reflection/glass effect
                ctx.fillStyle = '#87CEEB'; // Sky blue reflection
                ctx.fillRect(screenX, screenY, screenWidth, screenHeight * 0.3);
                
                // Screen content simulation (windows/interface)
                ctx.fillStyle = '#FFF'; // White windows
                
                // Top menu bar
                ctx.fillRect(screenX + 2, screenY + 2, screenWidth - 4, 6);
                
                // Application windows
                const windowWidth = screenWidth * 0.4;
                const windowHeight = screenHeight * 0.3;
                
                // Left window
                ctx.fillRect(screenX + 4, screenY + 12, windowWidth, windowHeight);
                ctx.strokeStyle = '#C0C0C0';
                ctx.lineWidth = 1;
                ctx.strokeRect(screenX + 4, screenY + 12, windowWidth, windowHeight);
                
                // Right window
                ctx.fillRect(screenX + screenWidth - windowWidth - 4, screenY + 12, windowWidth, windowHeight);
                ctx.strokeRect(screenX + screenWidth - windowWidth - 4, screenY + 12, windowWidth, windowHeight);
                
                // Text lines in windows
                ctx.strokeStyle = '#808080';
                ctx.lineWidth = 0.5;
                for (let i = 0; i < 3; i++) {
                    const lineY = screenY + 16 + i * 3;
                    // Left window lines
                    ctx.beginPath();
                    ctx.moveTo(screenX + 6, lineY);
                    ctx.lineTo(screenX + 6 + windowWidth - 4, lineY);
                    ctx.stroke();
                    
                    // Right window lines
                    ctx.beginPath();
                    ctx.moveTo(screenX + screenWidth - windowWidth - 2, lineY);
                    ctx.lineTo(screenX + screenWidth - 6, lineY);
                    ctx.stroke();
                }
                
                // Screen stand/base
                ctx.fillStyle = '#696969'; // Dim gray stand
                const standWidth = width * 0.3;
                const standHeight = height * 0.2;
                const standX = shape.x + (width - standWidth) / 2;
                const standY = shape.y + height;
                
                // Stand neck
                ctx.fillRect(standX + standWidth * 0.4, standY, standWidth * 0.2, standHeight);
                
                // Stand base
                ctx.fillRect(standX, standY + standHeight * 0.7, standWidth, standHeight * 0.3);
                ctx.strokeStyle = '#4A4A4A';
                ctx.lineWidth = 1;
                ctx.strokeRect(standX, standY + standHeight * 0.7, standWidth, standHeight * 0.3);
                
                // Screen brand/logo (optional)
                ctx.fillStyle = '#FFF';
                ctx.font = '6px Arial';
                ctx.textAlign = 'center';
                ctx.textBaseline = 'middle';
                const displayTextX = Math.round(shape.x + width/2);
                const displayTextY = Math.round(shape.y + height - 3);
                ctx.fillText('DISPLAY', displayTextX, displayTextY);
                ctx.textAlign = 'start';
                ctx.textBaseline = 'alphabetic';
                
                // Power LED indicator
                ctx.fillStyle = '#00FF00'; // Green power LED
                ctx.beginPath();
                ctx.arc(shape.x + width - 8, shape.y + height - 8, 2, 0, 2 * Math.PI);
                ctx.fill();
                
                // Screen glare effect (diagonal highlight)
                ctx.strokeStyle = '#FFF';
                ctx.lineWidth = 1;
                ctx.globalAlpha = 0.3;
                ctx.beginPath();
                ctx.moveTo(screenX + screenWidth * 0.7, screenY);
                ctx.lineTo(screenX + screenWidth, screenY + screenHeight * 0.3);
                ctx.stroke();
                ctx.globalAlpha = 1.0;
                
                // Control buttons (on the bezel)
                ctx.fillStyle = '#404040';
                const buttonSize = 3;
                for (let i = 0; i < 3; i++) {
                    const buttonX = shape.x + width - 15 + i * 5;
                    const buttonY = shape.y + height - 15;
                    ctx.fillRect(buttonX, buttonY, buttonSize, buttonSize);
                    ctx.strokeStyle = '#606060';
                    ctx.lineWidth = 0.5;
                    ctx.strokeRect(buttonX, buttonY, buttonSize, buttonSize);
                }
                
                // Restore canvas context if rotation was applied
                if (shape.rotation && shape.rotation !== 0) {
                    ctx.restore();
                }
            }
            
            function drawProjector(shape) {
                const width = shape.width || 30;
                const height = shape.height || 15;
                const centerX = shape.x + width/2;
                const centerY = shape.y + height/2;
                
                // Apply rotation if shape has rotation
                if (shape.rotation && shape.rotation !== 0) {
                    ctx.save();
                    ctx.translate(centerX, centerY);
                    ctx.rotate(shape.rotation * Math.PI / 180);
                    ctx.translate(-centerX, -centerY);
                }
                
                // Main projector body (dark gray/black)
                ctx.fillStyle = '#2F2F2F'; // Dark gray body
                ctx.fillRect(shape.x, shape.y, width, height);
                ctx.strokeStyle = '#000';
                ctx.lineWidth = 2;
                ctx.strokeRect(shape.x, shape.y, width, height);
                
                // Projector top (slightly lighter)
                ctx.fillStyle = '#696969'; // Dim gray top
                ctx.fillRect(shape.x, shape.y, width, 3);
                
                // Front lens housing (circular)
                ctx.fillStyle = '#1C1C1C'; // Almost black lens housing
                const lensRadius = height * 0.4;
                const lensX = shape.x + width - lensRadius - 2;
                const lensY = shape.y + height / 2;
                ctx.beginPath();
                ctx.arc(lensX, lensY, lensRadius, 0, 2 * Math.PI);
                ctx.fill();
                ctx.strokeStyle = '#4A4A4A';
                ctx.lineWidth = 1;
                ctx.stroke();
                
                // Lens (black with blue reflection)
                ctx.fillStyle = '#000'; // Black lens
                ctx.beginPath();
                ctx.arc(lensX, lensY, lensRadius - 1, 0, 2 * Math.PI);
                ctx.fill();
                
                // Lens reflection/coating
                ctx.fillStyle = '#1E90FF'; // Blue lens coating
                ctx.globalAlpha = 0.3;
                ctx.beginPath();
                ctx.arc(lensX, lensY, lensRadius - 2, 0, 2 * Math.PI);
                ctx.fill();
                ctx.globalAlpha = 1.0;
                
                // Lens highlight
                ctx.fillStyle = '#FFF';
                ctx.globalAlpha = 0.6;
                ctx.beginPath();
                ctx.arc(lensX - 2, lensY - 2, 2, 0, 2 * Math.PI);
                ctx.fill();
                ctx.globalAlpha = 1.0;
                
                // Ventilation grilles (top)
                ctx.strokeStyle = '#4A4A4A';
                ctx.lineWidth = 1;
                for (let i = 0; i < 4; i++) {
                    const grillX = shape.x + 3 + i * 5;
                    ctx.beginPath();
                    ctx.moveTo(grillX, shape.y + 1);
                    ctx.lineTo(grillX + 3, shape.y + 1);
                    ctx.stroke();
                }
                
                // Control panel/buttons (top surface)
                ctx.fillStyle = '#404040'; // Dark buttons
                const buttonWidth = 2;
                const buttonHeight = 1.5;
                
                // Power button
                ctx.fillRect(shape.x + 4, shape.y + height - 4, buttonWidth, buttonHeight);
                
                // Menu buttons
                ctx.fillRect(shape.x + 8, shape.y + height - 4, buttonWidth, buttonHeight);
                ctx.fillRect(shape.x + 12, shape.y + height - 4, buttonWidth, buttonHeight);
                
                // Power LED (green when on)
                ctx.fillStyle = '#00FF00'; // Green LED
                ctx.beginPath();
                ctx.arc(shape.x + 3, shape.y + height - 2, 1, 0, 2 * Math.PI);
                ctx.fill();
                
                // Brand/model label
                ctx.fillStyle = '#C0C0C0';
                ctx.font = '4px Arial';
                ctx.textAlign = 'center';
                ctx.textBaseline = 'middle';
                const projectorTextX = Math.round(shape.x + width/2);
                const projectorTextY = Math.round(shape.y + height/2 - 2);
                ctx.fillText('PROJECTOR', projectorTextX, projectorTextY);
                ctx.textAlign = 'start';
                ctx.textBaseline = 'alphabetic';
                
                // Heat exhaust vents (side)
                ctx.strokeStyle = '#4A4A4A';
                ctx.lineWidth = 0.5;
                for (let i = 0; i < 3; i++) {
                    const ventY = shape.y + 4 + i * 2;
                    ctx.beginPath();
                    ctx.moveTo(shape.x + 1, ventY);
                    ctx.lineTo(shape.x + 6, ventY);
                    ctx.stroke();
                }
                
                // Projection beam (optional light effect)
                ctx.strokeStyle = '#FFFF99'; // Light yellow beam
                ctx.lineWidth = 2;
                ctx.globalAlpha = 0.3;
                ctx.beginPath();
                ctx.moveTo(lensX, lensY);
                ctx.lineTo(lensX + 40, lensY - 15); // Beam spreading upward
                ctx.moveTo(lensX, lensY);
                ctx.lineTo(lensX + 40, lensY + 15); // Beam spreading downward
                ctx.stroke();
                ctx.globalAlpha = 1.0;
                
                // Mounting/adjustment feet
                ctx.fillStyle = '#808080'; // Gray feet
                const footSize = 1.5;
                // Front feet
                ctx.fillRect(shape.x + width - 4, shape.y + height, footSize, 2);
                ctx.fillRect(shape.x + width - 4, shape.y + height, footSize, 2);
                // Back feet
                ctx.fillRect(shape.x + 2, shape.y + height, footSize, 2);
                ctx.fillRect(shape.x + 2, shape.y + height, footSize, 2);
                
                // Focus ring (around lens)
                ctx.strokeStyle = '#808080';
                ctx.lineWidth = 1;
                ctx.beginPath();
                ctx.arc(lensX, lensY, lensRadius + 1, 0, 2 * Math.PI);
                ctx.stroke();
                
                // Restore canvas context if rotation was applied
                if (shape.rotation && shape.rotation !== 0) {
                    ctx.restore();
                }
            }
            
            function drawBanner(shape) {
                const width = shape.width || 60;
                const height = shape.height || 100;
                const centerX = shape.x + width/2;
                const centerY = shape.y + height/2;
                
                // Apply rotation if shape has rotation
                if (shape.rotation && shape.rotation !== 0) {
                    ctx.save();
                    ctx.translate(centerX, centerY);
                    ctx.rotate(shape.rotation * Math.PI / 180);
                    ctx.translate(-centerX, -centerY);
                }
                
                // Banner pole/support
                ctx.fillStyle = '#8B4513'; // Brown pole
                const poleWidth = 4;
                const poleHeight = height + 10;
                const poleX = shape.x - poleWidth;
                const poleY = shape.y - 5;
                ctx.fillRect(poleX, poleY, poleWidth, poleHeight);
                ctx.strokeStyle = '#654321';
                ctx.lineWidth = 1;
                ctx.strokeRect(poleX, poleY, poleWidth, poleHeight);
                
                // Pole base/stand
                ctx.fillStyle = '#696969'; // Gray base
                const baseWidth = poleWidth * 3;
                const baseHeight = 6;
                ctx.fillRect(poleX - (baseWidth - poleWidth)/2, poleY + poleHeight - baseHeight, baseWidth, baseHeight);
                
                // Banner fabric/material
                ctx.fillStyle = '#FF6347'; // Tomato red banner
                ctx.fillRect(shape.x, shape.y, width, height);
                ctx.strokeStyle = '#DC143C'; // Crimson border
                ctx.lineWidth = 2;
                ctx.strokeRect(shape.x, shape.y, width, height);
                
                // Banner grommets/eyelets (attachment points)
                ctx.fillStyle = '#C0C0C0'; // Silver grommets
                const grommetRadius = 2;
                // Top grommets
                ctx.beginPath();
                ctx.arc(shape.x + 5, shape.y + 5, grommetRadius, 0, 2 * Math.PI);
                ctx.fill();
                ctx.beginPath();
                ctx.arc(shape.x + width - 5, shape.y + 5, grommetRadius, 0, 2 * Math.PI);
                ctx.fill();
                // Bottom grommets
                ctx.beginPath();
                ctx.arc(shape.x + 5, shape.y + height - 5, grommetRadius, 0, 2 * Math.PI);
                ctx.fill();
                ctx.beginPath();
                ctx.arc(shape.x + width - 5, shape.y + height - 5, grommetRadius, 0, 2 * Math.PI);
                ctx.fill();
                
                // Banner header/title area
                ctx.fillStyle = '#B22222'; // Fire brick header
                const headerHeight = height * 0.2;
                ctx.fillRect(shape.x, shape.y, width, headerHeight);
                
                // Banner title text
                ctx.fillStyle = '#FFF'; // White text
                ctx.font = 'bold 8px Arial';
                ctx.textAlign = 'center';
                ctx.textBaseline = 'middle';
                const bannerTextX = Math.round(shape.x + width/2);
                const bannerTextY = Math.round(shape.y + headerHeight/2 + 3);
                ctx.fillText('EXHIBITION', bannerTextX, bannerTextY);
                ctx.textAlign = 'start';
                ctx.textBaseline = 'alphabetic';
                
                // Banner main content area
                ctx.fillStyle = '#FFF'; // White content background
                const contentHeight = height * 0.5;
                const contentY = shape.y + headerHeight + 5;
                ctx.fillRect(shape.x + 5, contentY, width - 10, contentHeight);
                ctx.strokeStyle = '#DDD';
                ctx.lineWidth = 1;
                ctx.strokeRect(shape.x + 5, contentY, width - 10, contentHeight);
                
                // Content placeholder (logo/image area)
                ctx.fillStyle = '#E0E0E0'; // Light gray placeholder
                const logoSize = Math.min(width - 20, contentHeight - 20);
                const logoX = shape.x + (width - logoSize) / 2;
                const logoY = contentY + 10;
                ctx.fillRect(logoX, logoY, logoSize, logoSize * 0.6);
                
                // Logo placeholder text
                ctx.fillStyle = '#888';
                ctx.font = '6px Arial';
                ctx.textAlign = 'center';
                ctx.textBaseline = 'middle';
                const logoTextX = Math.round(logoX + logoSize/2);
                const logoTextY = Math.round(logoY + logoSize * 0.3 + 2);
                ctx.fillText('LOGO', logoTextX, logoTextY);
                ctx.textAlign = 'start';
                ctx.textBaseline = 'alphabetic';
                
                // Banner footer/contact info
                ctx.fillStyle = '#8B0000'; // Dark red footer
                const footerHeight = height * 0.15;
                const footerY = shape.y + height - footerHeight;
                ctx.fillRect(shape.x, footerY, width, footerHeight);
                
                // Footer text
                ctx.fillStyle = '#FFF';
                ctx.font = '5px Arial';
                ctx.textAlign = 'center';
                ctx.textBaseline = 'middle';
                const footerTextX = Math.round(shape.x + width/2);
                const footerTextY = Math.round(footerY + footerHeight/2 + 2);
                ctx.fillText('2024', footerTextX, footerTextY);
                ctx.textAlign = 'start';
                ctx.textBaseline = 'alphabetic';
                
                // Banner fabric texture (subtle lines)
                ctx.strokeStyle = '#DC143C';
                ctx.lineWidth = 0.5;
                ctx.globalAlpha = 0.3;
                for (let i = 0; i < height; i += 8) {
                    ctx.beginPath();
                    ctx.moveTo(shape.x, shape.y + i);
                    ctx.lineTo(shape.x + width, shape.y + i);
                    ctx.stroke();
                }
                ctx.globalAlpha = 1.0;
                
                // Banner shadow (to show it's hanging/raised)
                ctx.fillStyle = '#000';
                ctx.globalAlpha = 0.2;
                ctx.fillRect(shape.x + 2, shape.y + 2, width, height);
                ctx.globalAlpha = 1.0;
                
                // Attachment cables/ropes
                ctx.strokeStyle = '#8B4513';
                ctx.lineWidth = 1;
                // Top attachments
                ctx.beginPath();
                ctx.moveTo(poleX + poleWidth/2, poleY + 10);
                ctx.lineTo(shape.x + 5, shape.y + 5);
                ctx.stroke();
                ctx.beginPath();
                ctx.moveTo(poleX + poleWidth/2, poleY + 10);
                ctx.lineTo(shape.x + width - 5, shape.y + 5);
                ctx.stroke();
                
                // Wind effect (slight curve to show movement)
                ctx.strokeStyle = '#FF6347';
                ctx.lineWidth = 1;
                ctx.globalAlpha = 0.5;
                ctx.beginPath();
                ctx.moveTo(shape.x + width, shape.y + height/4);
                ctx.quadraticCurveTo(shape.x + width + 3, shape.y + height/2, shape.x + width, shape.y + 3*height/4);
                ctx.stroke();
                ctx.globalAlpha = 1.0;
                
                // Restore canvas context if rotation was applied
                if (shape.rotation && shape.rotation !== 0) {
                    ctx.restore();
                }
            }
            
            function drawKiosk(shape) {
                const width = shape.width || 40;
                const height = shape.height || 80;
                const centerX = shape.x + width/2;
                const centerY = shape.y + height/2;
                
                // Apply rotation if shape has rotation
                if (shape.rotation && shape.rotation !== 0) {
                    ctx.save();
                    ctx.translate(centerX, centerY);
                    ctx.rotate(shape.rotation * Math.PI / 180);
                    ctx.translate(-centerX, -centerY);
                }
                
                // Main kiosk body (metallic silver)
                ctx.fillStyle = '#C0C0C0'; // Silver body
                ctx.fillRect(shape.x, shape.y, width, height);
                ctx.strokeStyle = '#808080';
                ctx.lineWidth = 2;
                ctx.strokeRect(shape.x, shape.y, width, height);
                
                // Kiosk base (wider for stability)
                ctx.fillStyle = '#A9A9A9'; // Dark gray base
                const baseWidth = width * 1.2;
                const baseHeight = height * 0.15;
                const baseX = shape.x - (baseWidth - width) / 2;
                const baseY = shape.y + height - baseHeight;
                ctx.fillRect(baseX, baseY, baseWidth, baseHeight);
                ctx.strokeRect(baseX, baseY, baseWidth, baseHeight);
                
                // Touch screen display
                ctx.fillStyle = '#000'; // Black screen border
                const screenWidth = width * 0.8;
                const screenHeight = height * 0.4;
                const screenX = shape.x + (width - screenWidth) / 2;
                const screenY = shape.y + height * 0.1;
                ctx.fillRect(screenX, screenY, screenWidth, screenHeight);
                
                // Active screen content
                ctx.fillStyle = '#1E90FF'; // Blue screen background
                const contentWidth = screenWidth * 0.9;
                const contentHeight = screenHeight * 0.9;
                const contentX = screenX + (screenWidth - contentWidth) / 2;
                const contentY = screenY + (screenHeight - contentHeight) / 2;
                ctx.fillRect(contentX, contentY, contentWidth, contentHeight);
                
                // Screen interface elements
                ctx.fillStyle = '#FFF'; // White interface elements
                
                // Top menu bar
                ctx.fillRect(contentX, contentY, contentWidth, contentHeight * 0.15);
                
                // Menu buttons/options
                const buttonWidth = contentWidth * 0.8;
                const buttonHeight = contentHeight * 0.12;
                for (let i = 0; i < 4; i++) {
                    const buttonX = contentX + (contentWidth - buttonWidth) / 2;
                    const buttonY = contentY + contentHeight * 0.25 + i * (buttonHeight + 3);
                    ctx.fillRect(buttonX, buttonY, buttonWidth, buttonHeight);
                    ctx.strokeStyle = '#C0C0C0';
                    ctx.lineWidth = 1;
                    ctx.strokeRect(buttonX, buttonY, buttonWidth, buttonHeight);
                }
                
                // Screen reflection
                ctx.fillStyle = '#FFF';
                ctx.globalAlpha = 0.2;
                ctx.fillRect(screenX, screenY, screenWidth * 0.3, screenHeight * 0.6);
                ctx.globalAlpha = 1.0;
                
                // Keypad/input area
                ctx.fillStyle = '#2F2F2F'; // Dark keypad area
                const keypadWidth = width * 0.7;
                const keypadHeight = height * 0.2;
                const keypadX = shape.x + (width - keypadWidth) / 2;
                const keypadY = shape.y + height * 0.6;
                ctx.fillRect(keypadX, keypadY, keypadWidth, keypadHeight);
                ctx.strokeStyle = '#000';
                ctx.lineWidth = 1;
                ctx.strokeRect(keypadX, keypadY, keypadWidth, keypadHeight);
                
                // Keypad buttons (3x4 grid)
                ctx.fillStyle = '#4A4A4A'; // Dark gray buttons
                const buttonSize = 3;
                const buttonSpacing = 1;
                for (let row = 0; row < 4; row++) {
                    for (let col = 0; col < 3; col++) {
                        const btnX = keypadX + 3 + col * (buttonSize + buttonSpacing);
                        const btnY = keypadY + 3 + row * (buttonSize + buttonSpacing);
                        ctx.fillRect(btnX, btnY, buttonSize, buttonSize);
                        ctx.strokeStyle = '#666';
                        ctx.lineWidth = 0.5;
                        ctx.strokeRect(btnX, btnY, buttonSize, buttonSize);
                    }
                }
                
                // Card reader slot
                ctx.fillStyle = '#000'; // Black slot
                const slotWidth = width * 0.6;
                const slotHeight = 2;
                const slotX = shape.x + (width - slotWidth) / 2;
                const slotY = shape.y + height * 0.85;
                ctx.fillRect(slotX, slotY, slotWidth, slotHeight);
                
                // Card reader label
                ctx.fillStyle = '#000';
                ctx.font = '4px Arial';
                ctx.textAlign = 'center';
                ctx.fillText('INSERT CARD', shape.x + width/2, slotY - 2);
                ctx.textAlign = 'start';
                
                // Printer/receipt slot
                ctx.fillStyle = '#333'; // Dark gray printer area
                const printerWidth = width * 0.5;
                const printerHeight = 3;
                const printerX = shape.x + (width - printerWidth) / 2;
                const printerY = shape.y + height * 0.92;
                ctx.fillRect(printerX, printerY, printerWidth, printerHeight);
                ctx.strokeRect(printerX, printerY, printerWidth, printerHeight);
                
                // Status LED indicators
                ctx.fillStyle = '#00FF00'; // Green LED (operational)
                ctx.beginPath();
                ctx.arc(shape.x + width - 5, shape.y + 5, 1.5, 0, 2 * Math.PI);
                ctx.fill();
                
                // Power LED
                ctx.fillStyle = '#0000FF'; // Blue LED (power)
                ctx.beginPath();
                ctx.arc(shape.x + width - 5, shape.y + 10, 1.5, 0, 2 * Math.PI);
                ctx.fill();
                
                // Kiosk branding/logo area
                ctx.fillStyle = '#4169E1'; // Royal blue branding area
                const logoWidth = width * 0.8;
                const logoHeight = height * 0.08;
                const logoX = shape.x + (width - logoWidth) / 2;
                const logoY = shape.y + height * 0.02;
                ctx.fillRect(logoX, logoY, logoWidth, logoHeight);
                
                // Brand text
                ctx.fillStyle = '#FFF';
                ctx.font = '5px Arial';
                ctx.textAlign = 'center';
                ctx.textBaseline = 'middle';
                const kioskTextX = Math.round(shape.x + width/2);
                const kioskTextY = Math.round(logoY + logoHeight/2 + 2);
                ctx.fillText('INFO KIOSK', kioskTextX, kioskTextY);
                ctx.textAlign = 'start';
                ctx.textBaseline = 'alphabetic';
                
                // Side ventilation grilles
                ctx.strokeStyle = '#808080';
                ctx.lineWidth = 0.5;
                for (let i = 0; i < 8; i++) {
                    const ventY = shape.y + 15 + i * 8;
                    ctx.beginPath();
                    ctx.moveTo(shape.x + 1, ventY);
                    ctx.lineTo(shape.x + 4, ventY);
                    ctx.stroke();
                    
                    ctx.beginPath();
                    ctx.moveTo(shape.x + width - 4, ventY);
                    ctx.lineTo(shape.x + width - 1, ventY);
                    ctx.stroke();
                }
                
                // Restore canvas context if rotation was applied
                if (shape.rotation && shape.rotation !== 0) {
                    ctx.restore();
                }
            }
            
             function drawPerson(shape) {
                const width = shape.width || 20;
                const height = shape.height || 25;
                const centerX = shape.x + width/2;
                const centerY = shape.y + height/2;
                
                // Apply rotation if shape has rotation
                if (shape.rotation && shape.rotation !== 0) {
                    ctx.save();
                    ctx.translate(centerX, centerY);
                    ctx.rotate(shape.rotation * Math.PI / 180);
                    ctx.translate(-centerX, -centerY);
                }
                
                // Head
                ctx.fillStyle = '#FFDBAC';
                ctx.beginPath();
                ctx.arc(shape.x + width/2, shape.y + height/5, width/6, 0, 2 * Math.PI);
                ctx.fill();
                ctx.strokeStyle = '#000';
                ctx.lineWidth = 1;
                ctx.stroke();
                
                // Body
                ctx.fillStyle = '#4169E1';
                ctx.fillRect(shape.x + width/3, shape.y + height/3, width/3, height/2);
                ctx.strokeRect(shape.x + width/3, shape.y + height/3, width/3, height/2);
                
                // Arms
                ctx.strokeStyle = '#FFDBAC';
                ctx.lineWidth = 2;
                ctx.beginPath();
                ctx.moveTo(shape.x + width/3, shape.y + height/2);
                ctx.lineTo(shape.x + width/6, shape.y + 2*height/3);
                ctx.moveTo(shape.x + 2*width/3, shape.y + height/2);
                ctx.lineTo(shape.x + 5*width/6, shape.y + 2*height/3);
                ctx.stroke();
                
                // Legs
                ctx.strokeStyle = '#000';
                ctx.lineWidth = 2;
                ctx.beginPath();
                ctx.moveTo(shape.x + 2*width/5, shape.y + 5*height/6);
                ctx.lineTo(shape.x + width/3, shape.y + height);
                ctx.moveTo(shape.x + 3*width/5, shape.y + 5*height/6);
                ctx.lineTo(shape.x + 2*width/3, shape.y + height);
                ctx.stroke();
                
                // Restore canvas context if rotation was applied
                if (shape.rotation && shape.rotation !== 0) {
                    ctx.restore();
                }
            }
            
            function drawGroup(shape) {
                const width = shape.width || 60;
                const height = shape.height || 25;
                const centerX = shape.x + width/2;
                const centerY = shape.y + height/2;
                
                // Apply rotation if shape has rotation
                if (shape.rotation && shape.rotation !== 0) {
                    ctx.save();
                    ctx.translate(centerX, centerY);
                    ctx.rotate(shape.rotation * Math.PI / 180);
                    ctx.translate(-centerX, -centerY);
                }
                
                for (let i = 0; i < 3; i++) {
                    const offset = i * 15;
                    const personShape = { x: shape.x + offset, y: shape.y, size: 15 };
                    drawPerson(personShape);
                }
                
                // Restore canvas context if rotation was applied
                if (shape.rotation && shape.rotation !== 0) {
                    ctx.restore();
                }
            }
            
            function drawEntrance(shape) {
                const width = shape.width || 60;
                const height = shape.height || 20;
                const centerX = shape.x + width/2;
                const centerY = shape.y + height/2;
                
                // Apply rotation if shape has rotation
                if (shape.rotation && shape.rotation !== 0) {
                    ctx.save();
                    ctx.translate(centerX, centerY);
                    ctx.rotate(shape.rotation * Math.PI / 180);
                    ctx.translate(-centerX, -centerY);
                }
                
                // Entrance threshold/floor
                ctx.fillStyle = '#D3D3D3'; // Light gray floor
                ctx.fillRect(shape.x, shape.y, width, height);
                ctx.strokeStyle = '#A9A9A9';
                ctx.lineWidth = 2;
                ctx.strokeRect(shape.x, shape.y, width, height);
                
                // Door frames (double doors)
                ctx.fillStyle = '#8B4513'; // Brown door frames
                const frameWidth = 3;
                const doorWidth = (width - frameWidth * 3) / 2;
                
                // Left door frame
                ctx.fillRect(shape.x, shape.y, frameWidth, height);
                // Center frame (between doors)
                ctx.fillRect(shape.x + frameWidth + doorWidth, shape.y, frameWidth, height);
                // Right door frame
                ctx.fillRect(shape.x + width - frameWidth, shape.y, frameWidth, height);
                
                // Door panels
                ctx.fillStyle = '#DEB887'; // Burlywood doors
                
                // Left door
                const leftDoorX = shape.x + frameWidth;
                ctx.fillRect(leftDoorX, shape.y + 2, doorWidth, height - 4);
                ctx.strokeStyle = '#8B4513';
                ctx.lineWidth = 1;
                ctx.strokeRect(leftDoorX, shape.y + 2, doorWidth, height - 4);
                
                // Right door
                const rightDoorX = shape.x + frameWidth * 2 + doorWidth;
                ctx.fillRect(rightDoorX, shape.y + 2, doorWidth, height - 4);
                ctx.strokeRect(rightDoorX, shape.y + 2, doorWidth, height - 4);
                
                // Door handles
                ctx.fillStyle = '#C0C0C0'; // Silver handles
                const handleSize = 2;
                // Left door handle (right side)
                ctx.fillRect(leftDoorX + doorWidth - 4, shape.y + height/2 - 1, handleSize, handleSize);
                // Right door handle (left side)
                ctx.fillRect(rightDoorX + 2, shape.y + height/2 - 1, handleSize, handleSize);
                
                // Door glass panels (upper portion)
                ctx.fillStyle = '#E6F3FF'; // Light blue glass
                const glassHeight = height * 0.4;
                ctx.fillRect(leftDoorX + 2, shape.y + 3, doorWidth - 4, glassHeight);
                ctx.fillRect(rightDoorX + 2, shape.y + 3, doorWidth - 4, glassHeight);
                
                // Glass reflections
                ctx.fillStyle = '#FFF';
                ctx.globalAlpha = 0.3;
                ctx.fillRect(leftDoorX + 2, shape.y + 3, (doorWidth - 4) * 0.3, glassHeight);
                ctx.fillRect(rightDoorX + 2, shape.y + 3, (doorWidth - 4) * 0.3, glassHeight);
                ctx.globalAlpha = 1.0;
                
                // Welcome mat/carpet
                ctx.fillStyle = '#8B0000'; // Dark red carpet
                const matWidth = width * 0.8;
                const matHeight = 6;
                const matX = shape.x + (width - matWidth) / 2;
                const matY = shape.y + height;
                ctx.fillRect(matX, matY, matWidth, matHeight);
                ctx.strokeStyle = '#654321';
                ctx.lineWidth = 1;
                ctx.strokeRect(matX, matY, matWidth, matHeight);
                
                // Entrance signage above
                ctx.fillStyle = '#228B22'; // Forest green sign
                const signWidth = width * 0.6;
                const signHeight = 8;
                const signX = shape.x + (width - signWidth) / 2;
                const signY = shape.y - signHeight - 2;
                ctx.fillRect(signX, signY, signWidth, signHeight);
                ctx.strokeStyle = '#006400';
                ctx.lineWidth = 1;
                ctx.strokeRect(signX, signY, signWidth, signHeight);
                
                // Sign text
                ctx.fillStyle = '#FFF';
                ctx.font = 'bold 6px Arial';
                ctx.textAlign = 'center';
                ctx.textBaseline = 'middle';
                const entranceTextX = Math.round(signX + signWidth/2);
                const entranceTextY = Math.round(signY + signHeight/2 + 2);
                ctx.fillText('ENTRANCE', entranceTextX, entranceTextY);
                ctx.textAlign = 'start';
                ctx.textBaseline = 'alphabetic';
                
                // Directional arrow pointing inward
                ctx.fillStyle = '#00FF00'; // Green arrow
                const arrowSize = 8;
                const arrowX = shape.x + width/2;
                const arrowY = shape.y + height/2;
                
                // Arrow pointing inward (down)
                ctx.beginPath();
                ctx.moveTo(arrowX, arrowY - arrowSize/2);
                ctx.lineTo(arrowX - arrowSize/2, arrowY + arrowSize/2);
                ctx.lineTo(arrowX + arrowSize/2, arrowY + arrowSize/2);
                ctx.closePath();
                ctx.fill();
                
                // Arrow outline
                ctx.strokeStyle = '#006400';
                ctx.lineWidth = 1;
                ctx.stroke();
                
                // Side pillars/columns
                ctx.fillStyle = '#696969'; // Dim gray pillars
                const pillarWidth = 4;
                const pillarHeight = height + 10;
                
                // Left pillar
                ctx.fillRect(shape.x - pillarWidth - 2, shape.y - 5, pillarWidth, pillarHeight);
                ctx.strokeStyle = '#4A4A4A';
                ctx.lineWidth = 1;
                ctx.strokeRect(shape.x - pillarWidth - 2, shape.y - 5, pillarWidth, pillarHeight);
                
                // Right pillar
                ctx.fillRect(shape.x + width + 2, shape.y - 5, pillarWidth, pillarHeight);
                ctx.strokeRect(shape.x + width + 2, shape.y - 5, pillarWidth, pillarHeight);
                
                // Entrance lighting
                ctx.fillStyle = '#FFFF99'; // Light yellow bulbs
                ctx.beginPath();
                ctx.arc(shape.x + width * 0.25, shape.y - 2, 2, 0, 2 * Math.PI);
                ctx.fill();
                ctx.beginPath();
                ctx.arc(shape.x + width * 0.75, shape.y - 2, 2, 0, 2 * Math.PI);
                ctx.fill();
                
                // Light glow effect
                ctx.strokeStyle = '#FFFF99';
                ctx.lineWidth = 1;
                ctx.globalAlpha = 0.3;
                ctx.beginPath();
                ctx.arc(shape.x + width * 0.25, shape.y - 2, 6, 0, 2 * Math.PI);
                ctx.stroke();
                ctx.beginPath();
                ctx.arc(shape.x + width * 0.75, shape.y - 2, 6, 0, 2 * Math.PI);
                ctx.stroke();
                ctx.globalAlpha = 1.0;
                
                // Accessibility symbol
                ctx.fillStyle = '#0000FF'; // Blue accessibility symbol
                ctx.font = 'bold 6px Arial';
                ctx.textAlign = 'center';
                ctx.fillText('♿', shape.x + width - 8, signY + signHeight/2 + 2);
                ctx.textAlign = 'start';
                
                // Restore canvas context if rotation was applied
                if (shape.rotation && shape.rotation !== 0) {
                    ctx.restore();
                }
            }
            
            function drawExit(shape) {
                const width = shape.width || 60;
                const height = shape.height || 20;
                const centerX = shape.x + width/2;
                const centerY = shape.y + height/2;
                
                // Apply rotation if shape has rotation
                if (shape.rotation && shape.rotation !== 0) {
                    ctx.save();
                    ctx.translate(centerX, centerY);
                    ctx.rotate(shape.rotation * Math.PI / 180);
                    ctx.translate(-centerX, -centerY);
                }
                
                // Exit threshold/floor
                ctx.fillStyle = '#D3D3D3'; // Light gray floor
                ctx.fillRect(shape.x, shape.y, width, height);
                ctx.strokeStyle = '#A9A9A9';
                ctx.lineWidth = 2;
                ctx.strokeRect(shape.x, shape.y, width, height);
                
                // Emergency exit door (push bar style)
                ctx.fillStyle = '#CD5C5C'; // Indian red door
                const doorWidth = width * 0.8;
                const doorHeight = height - 4;
                const doorX = shape.x + (width - doorWidth) / 2;
                const doorY = shape.y + 2;
                ctx.fillRect(doorX, doorY, doorWidth, doorHeight);
                ctx.strokeStyle = '#8B0000';
                ctx.lineWidth = 2;
                ctx.strokeRect(doorX, doorY, doorWidth, doorHeight);
                
                // Door frame
                ctx.fillStyle = '#696969'; // Dim gray frame
                const frameWidth = 2;
                // Top frame
                ctx.fillRect(shape.x, shape.y, width, frameWidth);
                // Left frame
                ctx.fillRect(shape.x, shape.y, frameWidth, height);
                // Right frame
                ctx.fillRect(shape.x + width - frameWidth, shape.y, frameWidth, height);
                // Bottom frame
                ctx.fillRect(shape.x, shape.y + height - frameWidth, width, frameWidth);
                
                // Push bar (panic bar)
                ctx.fillStyle = '#C0C0C0'; // Silver push bar
                const barWidth = doorWidth * 0.8;
                const barHeight = 3;
                const barX = doorX + (doorWidth - barWidth) / 2;
                const barY = doorY + doorHeight / 2 - barHeight / 2;
                ctx.fillRect(barX, barY, barWidth, barHeight);
                ctx.strokeStyle = '#808080';
                ctx.lineWidth = 1;
                ctx.strokeRect(barX, barY, barWidth, barHeight);
                
                // Push bar ends
                ctx.fillStyle = '#A9A9A9';
                ctx.fillRect(barX - 2, barY - 1, 2, barHeight + 2);
                ctx.fillRect(barX + barWidth, barY - 1, 2, barHeight + 2);
                
                // Emergency exit sign above
                ctx.fillStyle = '#FF0000'; // Red emergency sign
                const signWidth = width * 0.7;
                const signHeight = 10;
                const signX = shape.x + (width - signWidth) / 2;
                const signY = shape.y - signHeight - 3;
                ctx.fillRect(signX, signY, signWidth, signHeight);
                ctx.strokeStyle = '#8B0000';
                ctx.lineWidth = 1;
                ctx.strokeRect(signX, signY, signWidth, signHeight);
                
                // EXIT text
                ctx.fillStyle = '#FFF';
                ctx.font = 'bold 7px Arial';
                ctx.textAlign = 'center';
                ctx.textBaseline = 'middle';
                const exitTextX = Math.round(signX + signWidth/2);
                const exitTextY = Math.round(signY + signHeight/2 + 3);
                ctx.fillText('EXIT', exitTextX, exitTextY);
                ctx.textAlign = 'start';
                ctx.textBaseline = 'alphabetic';
                
                // Running person icon
                ctx.fillStyle = '#FFF';
                ctx.font = 'bold 8px Arial';
                ctx.textAlign = 'center';
                ctx.fillText('🏃', signX + signWidth * 0.2, signY + signHeight/2 + 3);
                ctx.textAlign = 'start';
                
                // Directional arrow pointing outward
                ctx.fillStyle = '#FFFF00'; // Yellow arrow
                const arrowSize = 6;
                const arrowX = shape.x + width/2;
                const arrowY = shape.y + height/2;
                
                // Arrow pointing outward (up)
                ctx.beginPath();
                ctx.moveTo(arrowX, arrowY + arrowSize/2);
                ctx.lineTo(arrowX - arrowSize/2, arrowY - arrowSize/2);
                ctx.lineTo(arrowX + arrowSize/2, arrowY - arrowSize/2);
                ctx.closePath();
                ctx.fill();
                
                // Arrow outline
                ctx.strokeStyle = '#FF8C00';
                ctx.lineWidth = 1;
                ctx.stroke();
                
                // Emergency lighting (illuminated exit)
                ctx.fillStyle = '#FFFF99'; // Light yellow bulbs
                ctx.beginPath();
                ctx.arc(signX + 3, signY - 2, 2, 0, 2 * Math.PI);
                ctx.fill();
                ctx.beginPath();
                ctx.arc(signX + signWidth - 3, signY - 2, 2, 0, 2 * Math.PI);
                ctx.fill();
                
                // Light glow effect
                ctx.strokeStyle = '#FFFF99';
                ctx.lineWidth = 1;
                ctx.globalAlpha = 0.4;
                ctx.beginPath();
                ctx.arc(signX + 3, signY - 2, 8, 0, 2 * Math.PI);
                ctx.stroke();
                ctx.beginPath();
                ctx.arc(signX + signWidth - 3, signY - 2, 8, 0, 2 * Math.PI);
                ctx.stroke();
                ctx.globalAlpha = 1.0;
                
                // Door hinges
                ctx.fillStyle = '#808080'; // Gray hinges
                const hingeWidth = 2;
                const hingeHeight = 4;
                // Top hinge
                ctx.fillRect(doorX - 1, doorY + 2, hingeWidth, hingeHeight);
                // Bottom hinge
                ctx.fillRect(doorX - 1, doorY + doorHeight - 6, hingeWidth, hingeHeight);
                
                // Floor safety stripe
                ctx.fillStyle = '#FFFF00'; // Yellow safety stripe
                const stripeHeight = 2;
                ctx.fillRect(shape.x, shape.y + height, width, stripeHeight);
                
                // Caution stripes (diagonal)
                ctx.strokeStyle = '#FF0000';
                ctx.lineWidth = 1;
                for (let i = 0; i < width; i += 8) {
                    ctx.beginPath();
                    ctx.moveTo(shape.x + i, shape.y + height);
                    ctx.lineTo(shape.x + i + 4, shape.y + height + stripeHeight);
                    ctx.stroke();
                }
                
                // Emergency exit sensor (above door)
                ctx.fillStyle = '#000'; // Black sensor
                const sensorWidth = 4;
                const sensorHeight = 2;
                const sensorX = shape.x + (width - sensorWidth) / 2;
                const sensorY = shape.y - 2;
                ctx.fillRect(sensorX, sensorY, sensorWidth, sensorHeight);
                
                // Sensor LED (green - operational)
                ctx.fillStyle = '#00FF00';
                ctx.beginPath();
                ctx.arc(sensorX + sensorWidth/2, sensorY + sensorHeight/2, 0.5, 0, 2 * Math.PI);
                ctx.fill();
                
                // Door closer mechanism
                ctx.fillStyle = '#4A4A4A'; // Dark gray closer
                const closerWidth = 8;
                const closerHeight = 2;
                const closerX = doorX + doorWidth - closerWidth;
                const closerY = doorY - 3;
                ctx.fillRect(closerX, closerY, closerWidth, closerHeight);
                
                // Fire safety symbol
                ctx.fillStyle = '#FF0000';
                ctx.font = 'bold 6px Arial';
                ctx.textAlign = 'center';
                ctx.fillText('🔥', signX + signWidth * 0.8, signY + signHeight/2 + 3);
                ctx.textAlign = 'start';
                
                // Restore canvas context if rotation was applied
                if (shape.rotation && shape.rotation !== 0) {
                    ctx.restore();
                }
            }
            
            function drawElevator(shape) {
                const width = shape.width || 40;
                const height = shape.height || 40;
                const centerX = shape.x + width/2;
                const centerY = shape.y + height/2;
                
                // Apply rotation if shape has rotation
                if (shape.rotation && shape.rotation !== 0) {
                    ctx.save();
                    ctx.translate(centerX, centerY);
                    ctx.rotate(shape.rotation * Math.PI / 180);
                    ctx.translate(-centerX, -centerY);
                }
                
                // Elevator shaft/background
                ctx.fillStyle = '#2F4F4F'; // Dark slate gray shaft
                ctx.fillRect(shape.x, shape.y, width, height);
                ctx.strokeStyle = '#000';
                ctx.lineWidth = 2;
                ctx.strokeRect(shape.x, shape.y, width, height);
                
                // Elevator car
                ctx.fillStyle = '#C0C0C0'; // Silver car
                const carWidth = width * 0.85;
                const carHeight = height * 0.8;
                const carX = shape.x + (width - carWidth) / 2;
                const carY = shape.y + (height - carHeight) / 2;
                ctx.fillRect(carX, carY, carWidth, carHeight);
                ctx.strokeStyle = '#696969';
                ctx.lineWidth = 2;
                ctx.strokeRect(carX, carY, carWidth, carHeight);
                
                // Elevator door (double doors)
                ctx.fillStyle = '#F5F5F5'; // White doors
                const doorWidth = carWidth / 2;
                const doorHeight = carHeight;
                // Left door
                ctx.fillRect(carX, carY, doorWidth, doorHeight);
                ctx.strokeStyle = '#C0C0C0';
                ctx.lineWidth = 1;
                ctx.strokeRect(carX, carY, doorWidth, doorHeight);
                // Right door
                ctx.fillRect(carX + doorWidth, carY, doorWidth, doorHeight);
                ctx.strokeRect(carX + doorWidth, carY, doorWidth, doorHeight);
                
                // Door handles
                ctx.fillStyle = '#FFD700'; // Gold handles
                const handleWidth = 2;
                const handleHeight = 8;
                const handleX = carX + doorWidth - 3;
                const handleY = carY + doorHeight / 2 - handleHeight / 2;
                // Left door handle
                ctx.fillRect(handleX, handleY, handleWidth, handleHeight);
                // Right door handle
                ctx.fillRect(handleX + doorWidth + 1, handleY, handleWidth, handleHeight);
                
                // Door panels (decorative)
                ctx.strokeStyle = '#D3D3D3';
                ctx.lineWidth = 1;
                // Left door panel lines
                ctx.beginPath();
                ctx.moveTo(carX + 5, carY + 5);
                ctx.lineTo(carX + doorWidth - 5, carY + 5);
                ctx.moveTo(carX + 5, carY + doorHeight / 2);
                ctx.lineTo(carX + doorWidth - 5, carY + doorHeight / 2);
                ctx.moveTo(carX + 5, carY + doorHeight - 5);
                ctx.lineTo(carX + doorWidth - 5, carY + doorHeight - 5);
                ctx.stroke();
                // Right door panel lines
                ctx.beginPath();
                ctx.moveTo(carX + doorWidth + 5, carY + 5);
                ctx.lineTo(carX + carWidth - 5, carY + 5);
                ctx.moveTo(carX + doorWidth + 5, carY + doorHeight / 2);
                ctx.lineTo(carX + carWidth - 5, carY + doorHeight / 2);
                ctx.moveTo(carX + doorWidth + 5, carY + doorHeight - 5);
                ctx.lineTo(carX + carWidth - 5, carY + doorHeight - 5);
                ctx.stroke();
                
                // Elevator button panel
                ctx.fillStyle = '#000'; // Black panel
                const panelWidth = 12;
                const panelHeight = 16;
                const panelX = carX + carWidth - panelWidth - 2;
                const panelY = carY + 2;
                ctx.fillRect(panelX, panelY, panelWidth, panelHeight);
                ctx.strokeStyle = '#C0C0C0';
                ctx.lineWidth = 1;
                ctx.strokeRect(panelX, panelY, panelWidth, panelHeight);
                
                // Floor buttons
                ctx.fillStyle = '#FF0000'; // Red button
                ctx.beginPath();
                ctx.arc(panelX + panelWidth/2, panelY + 4, 1.5, 0, 2 * Math.PI);
                ctx.fill();
                ctx.fillStyle = '#00FF00'; // Green button
                ctx.beginPath();
                ctx.arc(panelX + panelWidth/2, panelY + 8, 1.5, 0, 2 * Math.PI);
                ctx.fill();
                ctx.fillStyle = '#0000FF'; // Blue button
                ctx.beginPath();
                ctx.arc(panelX + panelWidth/2, panelY + 12, 1.5, 0, 2 * Math.PI);
                ctx.fill();
                
                // Floor indicator display
                ctx.fillStyle = '#00FF00'; // Green LED display
                ctx.fillRect(panelX - 8, panelY + 2, 6, 4);
                ctx.strokeStyle = '#000';
                ctx.lineWidth = 1;
                ctx.strokeRect(panelX - 8, panelY + 2, 6, 4);
                
                // Floor number (simulated)
                ctx.fillStyle = '#000';
                ctx.font = 'bold 4px Arial';
                ctx.textAlign = 'center';
                ctx.fillText('1', panelX - 5, panelY + 5);
                ctx.textAlign = 'start';
                
                // Emergency button
                ctx.fillStyle = '#FF0000';
                ctx.beginPath();
                ctx.arc(panelX - 5, panelY + 12, 2, 0, 2 * Math.PI);
                ctx.fill();
                ctx.strokeStyle = '#8B0000';
                ctx.lineWidth = 1;
                ctx.stroke();
                
                // Emergency button text
                ctx.fillStyle = '#FFF';
                ctx.font = 'bold 3px Arial';
                ctx.textAlign = 'center';
                ctx.fillText('SOS', panelX - 5, panelY + 14);
                ctx.textAlign = 'start';
                
                // Elevator call buttons (outside)
                ctx.fillStyle = '#000'; // Black call panel
                const callPanelWidth = 8;
                const callPanelHeight = 12;
                const callPanelX = shape.x + 2;
                const callPanelY = shape.y + 2;
                ctx.fillRect(callPanelX, callPanelY, callPanelWidth, callPanelHeight);
                ctx.strokeStyle = '#C0C0C0';
                ctx.lineWidth = 1;
                ctx.strokeRect(callPanelX, callPanelY, callPanelWidth, callPanelHeight);
                
                // Up button
                ctx.fillStyle = '#00FF00';
                ctx.beginPath();
                ctx.arc(callPanelX + callPanelWidth/2, callPanelY + 3, 1.5, 0, 2 * Math.PI);
                ctx.fill();
                // Up arrow
                ctx.fillStyle = '#000';
                ctx.beginPath();
                ctx.moveTo(callPanelX + callPanelWidth/2, callPanelY + 1);
                ctx.lineTo(callPanelX + callPanelWidth/2 - 1, callPanelY + 3);
                ctx.lineTo(callPanelX + callPanelWidth/2 + 1, callPanelY + 3);
                ctx.closePath();
                ctx.fill();
                
                // Down button
                ctx.fillStyle = '#FF0000';
                ctx.beginPath();
                ctx.arc(callPanelX + callPanelWidth/2, callPanelY + 9, 1.5, 0, 2 * Math.PI);
                ctx.fill();
                // Down arrow
                ctx.fillStyle = '#000';
                ctx.beginPath();
                ctx.moveTo(callPanelX + callPanelWidth/2, callPanelY + 11);
                ctx.lineTo(callPanelX + callPanelWidth/2 - 1, callPanelY + 9);
                ctx.lineTo(callPanelX + callPanelWidth/2 + 1, callPanelY + 9);
                ctx.closePath();
                ctx.fill();
                
                // Elevator logo/branding
                ctx.fillStyle = '#000';
                ctx.font = 'bold 5px Arial';
                ctx.textAlign = 'center';
                ctx.fillText('ELEV', carX + carWidth/2, carY + carHeight + 8);
                ctx.textAlign = 'start';
                
                // Safety features
                ctx.fillStyle = '#FFFF00'; // Yellow safety strip
                ctx.fillRect(carX, carY + carHeight - 2, carWidth, 2);
                
                // Door sensors (infrared)
                ctx.fillStyle = '#00FFFF'; // Cyan sensor light
                ctx.beginPath();
                ctx.arc(carX - 1, carY + 5, 1, 0, 2 * Math.PI);
                ctx.fill();
                ctx.beginPath();
                ctx.arc(carX - 1, carY + carHeight - 5, 1, 0, 2 * Math.PI);
                ctx.fill();
                
                // Weight sensor indicator
                ctx.fillStyle = '#00FF00'; // Green weight sensor
                ctx.beginPath();
                ctx.arc(carX + carWidth + 1, carY + carHeight - 5, 1, 0, 2 * Math.PI);
                ctx.fill();
                
                // Restore canvas context if rotation was applied
                if (shape.rotation && shape.rotation !== 0) {
                    ctx.restore();
                }
            }
            
            function drawStairs(shape) {
                const width = shape.width || 50;
                const height = shape.height || 50;
                const centerX = shape.x + width/2;
                const centerY = shape.y + height/2;
                
                // Apply rotation if shape has rotation
                if (shape.rotation && shape.rotation !== 0) {
                    ctx.save();
                    ctx.translate(centerX, centerY);
                    ctx.rotate(shape.rotation * Math.PI / 180);
                    ctx.translate(-centerX, -centerY);
                }
                
                // Stairs background
                ctx.fillStyle = '#F0F0F0'; // Very light gray background
                ctx.fillRect(shape.x, shape.y, width, height);
                ctx.strokeStyle = '#000';
                ctx.lineWidth = 2;
                ctx.strokeRect(shape.x, shape.y, width, height);
                
                // Simple, clear step design
                const stepCount = 6;
                const stepHeight = height / stepCount;
                
                // Draw steps from bottom to top (much simpler and clearer)
                for (let i = 0; i < stepCount; i++) {
                    const stepY = shape.y + height - (i + 1) * stepHeight;
                    
                    // Step tread (horizontal surface)
                    ctx.fillStyle = '#E8E8E8'; // Light gray tread
                    ctx.fillRect(shape.x, stepY, width, stepHeight);
                    
                    // Step riser (vertical part) - darker for contrast
                    ctx.fillStyle = '#D0D0D0'; // Darker gray for depth
                    ctx.fillRect(shape.x, stepY + stepHeight * 0.8, width, stepHeight * 0.2);
                    
                    // Step edge (black line for definition)
                    ctx.strokeStyle = '#000';
                    ctx.lineWidth = 1;
                    ctx.beginPath();
                    ctx.moveTo(shape.x, stepY + stepHeight);
                    ctx.lineTo(shape.x + width, stepY + stepHeight);
                    ctx.stroke();
                    
                    // Step side edge (for 3D effect)
                    ctx.strokeStyle = '#999';
                    ctx.beginPath();
                    ctx.moveTo(shape.x, stepY);
                    ctx.lineTo(shape.x, stepY + stepHeight);
                    ctx.moveTo(shape.x + width, stepY);
                    ctx.lineTo(shape.x + width, stepY + stepHeight);
                    ctx.stroke();
                }
                
                // Simple handrails (just lines)
                ctx.strokeStyle = '#666'; // Dark gray handrails
                ctx.lineWidth = 2;
                
                // Left handrail
                ctx.beginPath();
                ctx.moveTo(shape.x + 3, shape.y + height);
                ctx.lineTo(shape.x + 3, shape.y + 5);
                ctx.stroke();
                
                // Right handrail
                ctx.beginPath();
                ctx.moveTo(shape.x + width - 3, shape.y + height);
                ctx.lineTo(shape.x + width - 3, shape.y + 5);
                ctx.stroke();
                
                // Direction arrow (simple and clear)
                ctx.fillStyle = '#FF0000'; // Red arrow
                const arrowSize = 12;
                const arrowX = shape.x + width/2;
                const arrowY = shape.y + height/2;
                
                // Simple up arrow
                ctx.beginPath();
                ctx.moveTo(arrowX, arrowY - arrowSize/2);
                ctx.lineTo(arrowX - arrowSize/3, arrowY + arrowSize/2);
                ctx.lineTo(arrowX + arrowSize/3, arrowY + arrowSize/2);
                ctx.closePath();
                ctx.fill();
                
                // Arrow outline for clarity
                ctx.strokeStyle = '#000';
                ctx.lineWidth = 1;
                ctx.stroke();
                
                // Simple "STAIRS" label
                ctx.fillStyle = '#000';
                ctx.font = 'bold 6px Arial';
                ctx.textAlign = 'center';
                ctx.fillText('STAIRS', shape.x + width/2, shape.y + height + 10);
                ctx.textAlign = 'start';
                
                // Simple step numbering (only on a few steps for clarity)
                ctx.fillStyle = '#666';
                ctx.font = '8px Arial';
                ctx.textAlign = 'center';
                for (let i = 0; i < 3; i++) {
                    const stepY = shape.y + height - (i + 1) * stepHeight;
                    ctx.fillText((i + 1).toString(), shape.x + width/2, stepY + stepHeight/2 + 2);
                }
                ctx.textAlign = 'start';
                
                // Safety yellow edge strips (simple)
                ctx.fillStyle = '#FFFF00'; // Yellow safety strips
                for (let i = 0; i < stepCount; i++) {
                    const stepY = shape.y + height - (i + 1) * stepHeight;
                    ctx.fillRect(shape.x + 2, stepY + stepHeight - 2, width - 4, 2);
                }
                
                // Restore canvas context if rotation was applied
                if (shape.rotation && shape.rotation !== 0) {
                    ctx.restore();
                }
            }
            
            function drawRestroom(shape) {
                const width = shape.width || 40;
                const height = shape.height || 40;
                const centerX = shape.x + width/2;
                const centerY = shape.y + height/2;
                
                // Apply rotation if shape has rotation
                if (shape.rotation && shape.rotation !== 0) {
                    ctx.save();
                    ctx.translate(centerX, centerY);
                    ctx.rotate(shape.rotation * Math.PI / 180);
                    ctx.translate(-centerX, -centerY);
                }
                
                ctx.fillStyle = '#E6E6FA';
                ctx.fillRect(shape.x, shape.y, width, height);
                ctx.strokeRect(shape.x, shape.y, width, height);
                
                // WC symbol
                ctx.fillStyle = '#000';
                ctx.font = '16px Arial';
                ctx.textAlign = 'center';
                ctx.textBaseline = 'middle';
                const wcTextX = Math.round(shape.x + width/2);
                const wcTextY = Math.round(shape.y + height/2 + 6);
                ctx.fillText('WC', wcTextX, wcTextY);
                ctx.textAlign = 'start';
                ctx.textBaseline = 'alphabetic';
                
                // Door indicator
                ctx.strokeStyle = '#000';
                ctx.lineWidth = 2;
                ctx.beginPath();
                ctx.arc(shape.x + width/2, shape.y + height - 5, 8, 0, Math.PI);
                ctx.stroke();
                
                // Restore canvas context if rotation was applied
                if (shape.rotation && shape.rotation !== 0) {
                    ctx.restore();
                }
            }
            
            function drawSecurity(shape) {
                const width = shape.width || 30;
                const height = shape.height || 30;
                const centerX = shape.x + width/2;
                const centerY = shape.y + height/2;
                
                // Apply rotation if shape has rotation
                if (shape.rotation && shape.rotation !== 0) {
                    ctx.save();
                    ctx.translate(centerX, centerY);
                    ctx.rotate(shape.rotation * Math.PI / 180);
                    ctx.translate(-centerX, -centerY);
                }
                
                ctx.fillStyle = '#FFD700';
                ctx.fillRect(shape.x, shape.y, width, height);
                ctx.strokeRect(shape.x, shape.y, width, height);
                
                // Shield symbol
                ctx.fillStyle = '#000';
                ctx.beginPath();
                ctx.arc(shape.x + width/2, shape.y + height/2, 8, 0, 2 * Math.PI);
                ctx.stroke();
                
                // Security badge
                ctx.fillStyle = '#000';
                ctx.font = '8px Arial';
                ctx.textAlign = 'center';
                ctx.fillText('SEC', shape.x + width/2, shape.y + height/2 + 3);
                ctx.textAlign = 'start';
                
                // Restore canvas context if rotation was applied
                if (shape.rotation && shape.rotation !== 0) {
                    ctx.restore();
                }
            }
            
            function drawFirstAid(shape) {
                const width = shape.width || 30;
                const height = shape.height || 30;
                const rotationCenterX = shape.x + width/2;
                const rotationCenterY = shape.y + height/2;
                
                // Apply rotation if shape has rotation
                if (shape.rotation && shape.rotation !== 0) {
                    ctx.save();
                    ctx.translate(rotationCenterX, rotationCenterY);
                    ctx.rotate(shape.rotation * Math.PI / 180);
                    ctx.translate(-rotationCenterX, -rotationCenterY);
                }
                
                // First aid cabinet/station background
                ctx.fillStyle = '#FFFFFF'; // White cabinet
                ctx.fillRect(shape.x, shape.y, width, height);
                ctx.strokeStyle = '#FF0000'; // Red border
                ctx.lineWidth = 3;
                ctx.strokeRect(shape.x, shape.y, width, height);
                
                // Red cross background circle
                ctx.fillStyle = '#FF0000'; // Red cross background
                const crossSize = Math.min(width, height) * 0.7;
                const centerX = shape.x + width / 2;
                const centerY = shape.y + height / 2;
                ctx.beginPath();
                ctx.arc(centerX, centerY, crossSize / 2, 0, 2 * Math.PI);
                ctx.fill();
                
                // White cross symbol
                ctx.fillStyle = '#FFFFFF'; // White cross
                const crossWidth = crossSize * 0.6;
                const crossThickness = crossWidth * 0.25;
                
                // Horizontal bar of cross
                ctx.fillRect(
                    centerX - crossWidth / 2, 
                    centerY - crossThickness / 2, 
                    crossWidth, 
                    crossThickness
                );
                
                // Vertical bar of cross
                ctx.fillRect(
                    centerX - crossThickness / 2, 
                    centerY - crossWidth / 2, 
                    crossThickness, 
                    crossWidth
                );
                
                // Cabinet door handle
                ctx.fillStyle = '#C0C0C0'; // Silver handle
                const handleWidth = 2;
                const handleHeight = 8;
                const handleX = shape.x + width - 4;
                const handleY = centerY - handleHeight / 2;
                ctx.fillRect(handleX, handleY, handleWidth, handleHeight);
                
                // Cabinet hinge
                ctx.fillStyle = '#808080'; // Gray hinge
                const hingeWidth = 2;
                const hingeHeight = 6;
                ctx.fillRect(shape.x + 1, shape.y + 3, hingeWidth, hingeHeight);
                ctx.fillRect(shape.x + 1, shape.y + height - 9, hingeWidth, hingeHeight);
                
                // First aid label
                ctx.fillStyle = '#000';
                ctx.font = 'bold 5px Arial';
                ctx.textAlign = 'center';
                ctx.fillText('FIRST', centerX, shape.y + height + 8);
                ctx.fillText('AID', centerX, shape.y + height + 16);
                ctx.textAlign = 'start';
                
                // Emergency contact information
                ctx.fillStyle = '#FF0000';
                ctx.font = 'bold 4px Arial';
                ctx.textAlign = 'center';
                ctx.fillText('911', centerX, shape.y - 5);
                ctx.textAlign = 'start';
                
                // Cabinet lock/latch
                ctx.fillStyle = '#FFD700'; // Gold lock
                const lockSize = 3;
                ctx.fillRect(handleX - 2, handleY + handleHeight + 2, lockSize, lockSize);
                ctx.strokeStyle = '#B8860B';
                ctx.lineWidth = 1;
                ctx.strokeRect(handleX - 2, handleY + handleHeight + 2, lockSize, lockSize);
                
                // Medical supplies indicators (small icons)
                ctx.fillStyle = '#FF0000';
                ctx.font = 'bold 6px Arial';
                ctx.textAlign = 'center';
                
                // Bandage symbol (top left corner)
                ctx.fillStyle = '#FFF';
                ctx.fillRect(shape.x + 2, shape.y + 2, 6, 2);
                ctx.strokeStyle = '#FF0000';
                ctx.lineWidth = 1;
                ctx.strokeRect(shape.x + 2, shape.y + 2, 6, 2);
                
                // Thermometer symbol (top right corner)
                ctx.fillStyle = '#C0C0C0';
                ctx.fillRect(shape.x + width - 5, shape.y + 2, 2, 8);
                ctx.fillStyle = '#FF0000';
                ctx.beginPath();
                ctx.arc(shape.x + width - 4, shape.y + 10, 2, 0, 2 * Math.PI);
                ctx.fill();
                
                // Pills symbol (bottom left corner)
                ctx.fillStyle = '#0000FF';
                ctx.beginPath();
                ctx.arc(shape.x + 4, shape.y + height - 4, 1.5, 0, 2 * Math.PI);
                ctx.fill();
                ctx.fillStyle = '#FF0000';
                ctx.beginPath();
                ctx.arc(shape.x + 7, shape.y + height - 6, 1.5, 0, 2 * Math.PI);
                ctx.fill();
                
                // Scissors symbol (bottom right corner)
                ctx.strokeStyle = '#C0C0C0';
                ctx.lineWidth = 1;
                ctx.beginPath();
                ctx.moveTo(shape.x + width - 6, shape.y + height - 8);
                ctx.lineTo(shape.x + width - 3, shape.y + height - 5);
                ctx.moveTo(shape.x + width - 6, shape.y + height - 5);
                ctx.lineTo(shape.x + width - 3, shape.y + height - 8);
                ctx.stroke();
                
                // Emergency beacon light
                ctx.fillStyle = '#00FF00'; // Green operational light
                ctx.beginPath();
                ctx.arc(shape.x + width / 2, shape.y - 2, 1.5, 0, 2 * Math.PI);
                ctx.fill();
                
                // Light glow effect
                ctx.strokeStyle = '#00FF00';
                ctx.lineWidth = 1;
                ctx.globalAlpha = 0.3;
                ctx.beginPath();
                ctx.arc(shape.x + width / 2, shape.y - 2, 4, 0, 2 * Math.PI);
                ctx.stroke();
                ctx.globalAlpha = 1.0;
                
                // Safety inspection sticker
                ctx.fillStyle = '#FFFF00'; // Yellow inspection sticker
                ctx.fillRect(shape.x + width - 8, shape.y + height - 12, 6, 4);
                ctx.strokeStyle = '#FFD700';
                ctx.lineWidth = 1;
                ctx.strokeRect(shape.x + width - 8, shape.y + height - 12, 6, 4);
                
                // Inspection date
                ctx.fillStyle = '#000';
                ctx.font = 'bold 3px Arial';
                ctx.textAlign = 'center';
                ctx.fillText('OK', shape.x + width - 5, shape.y + height - 9);
                ctx.textAlign = 'start';
                
                // Emergency instructions sign
                ctx.fillStyle = '#FFF';
                ctx.fillRect(shape.x - 8, shape.y + height / 2 - 6, 6, 12);
                ctx.strokeStyle = '#000';
                ctx.lineWidth = 1;
                ctx.strokeRect(shape.x - 8, shape.y + height / 2 - 6, 6, 12);
                
                // Instructions text
                ctx.fillStyle = '#000';
                ctx.font = 'bold 2px Arial';
                ctx.textAlign = 'center';
                ctx.fillText('1', shape.x - 5, shape.y + height / 2 - 3);
                ctx.fillText('2', shape.x - 5, shape.y + height / 2);
                ctx.fillText('3', shape.x - 5, shape.y + height / 2 + 3);
                ctx.textAlign = 'start';
                
                // AED symbol (if applicable)
                ctx.fillStyle = '#0000FF';
                ctx.font = 'bold 4px Arial';
                ctx.textAlign = 'center';
                ctx.fillText('AED', centerX, shape.y + height + 24);
                ctx.textAlign = 'start';
                
                // Restore canvas context if rotation was applied
                if (shape.rotation && shape.rotation !== 0) {
                    ctx.restore();
                }
            }
            
            function drawFireSafety(shape) {
                const width = shape.width || 30;
                const height = shape.height || 30;
                const centerX = shape.x + width/2;
                const centerY = shape.y + height/2;
                
                // Apply rotation if shape has rotation
                if (shape.rotation && shape.rotation !== 0) {
                    ctx.save();
                    ctx.translate(centerX, centerY);
                    ctx.rotate(shape.rotation * Math.PI / 180);
                    ctx.translate(-centerX, -centerY);
                }
                
                // Simple fire extinguisher body
                ctx.fillStyle = '#FF0000'; // Red extinguisher
                const extWidth = width * 0.6;
                const extHeight = height * 0.8;
                const extX = shape.x + (width - extWidth) / 2;
                const extY = shape.y + (height - extHeight) / 2;
                ctx.fillRect(extX, extY, extWidth, extHeight);
                ctx.strokeStyle = '#8B0000'; // Dark red outline
                ctx.lineWidth = 2;
                ctx.strokeRect(extX, extY, extWidth, extHeight);
                
                // Extinguisher top/head
                ctx.fillStyle = '#B22222'; // Darker red top
                const topHeight = extHeight * 0.2;
                ctx.fillRect(extX, extY, extWidth, topHeight);
                ctx.strokeStyle = '#8B0000';
                ctx.lineWidth = 1;
                ctx.strokeRect(extX, extY, extWidth, topHeight);
                
                // Simple pressure gauge
                ctx.fillStyle = '#FFF'; // White gauge
                const gaugeRadius = extWidth * 0.15;
                const gaugeX = extX + extWidth/2;
                const gaugeY = extY + topHeight/2;
                ctx.beginPath();
                ctx.arc(gaugeX, gaugeY, gaugeRadius, 0, 2 * Math.PI);
                ctx.fill();
                ctx.strokeStyle = '#000';
                ctx.lineWidth = 1;
                ctx.stroke();
                
                // Handle
                ctx.fillStyle = '#000'; // Black handle
                const handleWidth = extWidth * 0.7;
                const handleHeight = 3;
                const handleX = extX + (extWidth - handleWidth) / 2;
                const handleY = extY + extHeight * 0.3;
                ctx.fillRect(handleX, handleY, handleWidth, handleHeight);
                
                // Simple hose
                ctx.fillStyle = '#000'; // Black hose
                const hoseWidth = 2;
                const hoseLength = extWidth * 0.4;
                const hoseX = extX + extWidth;
                const hoseY = extY + extHeight * 0.5;
                ctx.fillRect(hoseX, hoseY, hoseLength, hoseWidth);
                
                // Nozzle
                ctx.fillStyle = '#C0C0C0'; // Silver nozzle
                ctx.fillRect(hoseX + hoseLength, hoseY - 1, 3, 4);
                
                // Simple label
                ctx.fillStyle = '#FFF';
                ctx.font = 'bold 6px Arial';
                ctx.textAlign = 'center';
                ctx.fillText('FIRE', shape.x + width/2, shape.y + height + 10);
                ctx.textAlign = 'start';
                
                // Simple inspection tag
                ctx.fillStyle = '#FFFF00'; // Yellow tag
                const tagSize = 4;
                ctx.fillRect(extX - 2, extY + extHeight - tagSize, tagSize, tagSize);
                ctx.strokeStyle = '#000';
                ctx.lineWidth = 1;
                ctx.strokeRect(extX - 2, extY + extHeight - tagSize, tagSize, tagSize);
                
                // Restore canvas context if rotation was applied
                if (shape.rotation && shape.rotation !== 0) {
                    ctx.restore();
                }
            }
            
            function drawPowerOutlet(shape) {
                const width = shape.width || 25;
                const height = shape.height || 25;
                const centerX = shape.x + width/2;
                const centerY = shape.y + height/2;
                
                // Apply rotation if shape has rotation
                if (shape.rotation && shape.rotation !== 0) {
                    ctx.save();
                    ctx.translate(centerX, centerY);
                    ctx.rotate(shape.rotation * Math.PI / 180);
                    ctx.translate(-centerX, -centerY);
                }
                
                // Outlet plate/faceplate background
                ctx.fillStyle = '#F8F8FF'; // Ghost white faceplate
                ctx.fillRect(shape.x, shape.y, width, height);
                ctx.strokeStyle = '#C0C0C0'; // Silver border
                ctx.lineWidth = 1;
                ctx.strokeRect(shape.x, shape.y, width, height);
                
                // Outlet recessed area
                ctx.fillStyle = '#F0F0F0'; // Light gray recessed area
                const outletWidth = width * 0.8;
                const outletHeight = height * 0.6;
                const outletX = shape.x + (width - outletWidth) / 2;
                const outletY = shape.y + (height - outletHeight) / 2;
                ctx.fillRect(outletX, outletY, outletWidth, outletHeight);
                ctx.strokeStyle = '#999';
                ctx.lineWidth = 1;
                ctx.strokeRect(outletX, outletY, outletWidth, outletHeight);
                
                // Power outlet slots (standard 3-prong)
                ctx.fillStyle = '#000'; // Black slots
                
                // Left slot (hot - smaller)
                const leftSlotWidth = outletWidth * 0.15;
                const leftSlotHeight = outletHeight * 0.3;
                const leftSlotX = outletX + outletWidth * 0.25 - leftSlotWidth / 2;
                const leftSlotY = outletY + outletHeight * 0.35;
                ctx.fillRect(leftSlotX, leftSlotY, leftSlotWidth, leftSlotHeight);
                
                // Right slot (neutral - larger)
                const rightSlotWidth = outletWidth * 0.2;
                const rightSlotHeight = outletHeight * 0.3;
                const rightSlotX = outletX + outletWidth * 0.75 - rightSlotWidth / 2;
                const rightSlotY = outletY + outletHeight * 0.35;
                ctx.fillRect(rightSlotX, rightSlotY, rightSlotWidth, rightSlotHeight);
                
                // Ground slot (bottom center - round)
                const groundRadius = outletWidth * 0.08;
                const groundX = outletX + outletWidth / 2;
                const groundY = outletY + outletHeight * 0.8;
                ctx.beginPath();
                ctx.arc(groundX, groundY, groundRadius, 0, 2 * Math.PI);
                ctx.fill();
                
                // Mounting screws
                ctx.fillStyle = '#C0C0C0'; // Silver screws
                const screwRadius = width * 0.04;
                
                // Top screw
                ctx.beginPath();
                ctx.arc(shape.x + width/2, shape.y + height * 0.1, screwRadius, 0, 2 * Math.PI);
                ctx.fill();
                
                // Bottom screw
                ctx.beginPath();
                ctx.arc(shape.x + width/2, shape.y + height * 0.9, screwRadius, 0, 2 * Math.PI);
                ctx.fill();
                
                // Screw slots (Phillips head)
                ctx.strokeStyle = '#999';
                ctx.lineWidth = 0.5;
                // Top screw slot
                const topScrewX = shape.x + width/2;
                const topScrewY = shape.y + height * 0.1;
                ctx.beginPath();
                ctx.moveTo(topScrewX - screwRadius * 0.6, topScrewY);
                ctx.lineTo(topScrewX + screwRadius * 0.6, topScrewY);
                ctx.moveTo(topScrewX, topScrewY - screwRadius * 0.6);
                ctx.lineTo(topScrewX, topScrewY + screwRadius * 0.6);
                ctx.stroke();
                
                // Bottom screw slot
                const bottomScrewX = shape.x + width/2;
                const bottomScrewY = shape.y + height * 0.9;
                ctx.beginPath();
                ctx.moveTo(bottomScrewX - screwRadius * 0.6, bottomScrewY);
                ctx.lineTo(bottomScrewX + screwRadius * 0.6, bottomScrewY);
                ctx.moveTo(bottomScrewX, bottomScrewY - screwRadius * 0.6);
                ctx.lineTo(bottomScrewX, bottomScrewY + screwRadius * 0.6);
                ctx.stroke();
                
                // GFCI indicator (if applicable)
                ctx.fillStyle = '#FF0000'; // Red indicator
                const gfciX = outletX + outletWidth - 3;
                const gfciY = outletY + 2;
                ctx.beginPath();
                ctx.arc(gfciX, gfciY, 1, 0, 2 * Math.PI);
                ctx.fill();
                
                // Voltage rating label
                ctx.fillStyle = '#000';
                ctx.font = 'bold 3px Arial';
                ctx.textAlign = 'center';
                ctx.fillText('120V', shape.x + width/2, shape.y + height + 8);
                ctx.textAlign = 'start';
                
                // Ground symbol
                ctx.fillStyle = '#00AA00'; // Green ground
                ctx.font = 'bold 4px Arial';
                ctx.textAlign = 'center';
                ctx.fillText('⏚', shape.x + width/2, shape.y - 3);
                ctx.textAlign = 'start';
                
                // Safety shutters (modern outlets)
                ctx.fillStyle = '#E0E0E0'; // Light gray shutters
                const shutterWidth = leftSlotWidth * 0.8;
                const shutterHeight = 1;
                
                // Left shutter
                ctx.fillRect(leftSlotX + (leftSlotWidth - shutterWidth)/2, leftSlotY + leftSlotHeight/2, shutterWidth, shutterHeight);
                
                // Right shutter
                ctx.fillRect(rightSlotX + (rightSlotWidth - shutterWidth)/2, rightSlotY + rightSlotHeight/2, shutterWidth, shutterHeight);
                
                // Outlet brand/UL listing mark
                ctx.fillStyle = '#666';
                ctx.font = 'bold 2px Arial';
                ctx.textAlign = 'center';
                ctx.fillText('UL', outletX + 3, outletY + outletHeight - 2);
                ctx.textAlign = 'start';
                
                // Power indicator LED (if smart outlet)
                ctx.fillStyle = '#00FF00'; // Green power LED
                ctx.beginPath();
                ctx.arc(outletX + outletWidth - 2, outletY + outletHeight - 2, 0.5, 0, 2 * Math.PI);
                ctx.fill();
                
                // Cord/plug shadow (if something is plugged in)
                ctx.fillStyle = '#D3D3D3'; // Light gray shadow
                ctx.globalAlpha = 0.5;
                const plugWidth = outletWidth * 0.6;
                const plugHeight = 4;
                const plugX = outletX + (outletWidth - plugWidth) / 2;
                const plugY = outletY + outletHeight + 1;
                ctx.fillRect(plugX, plugY, plugWidth, plugHeight);
                ctx.globalAlpha = 1.0;
                
                // Outlet protection cover (when not in use)
                ctx.fillStyle = '#FFF'; // White safety cover
                ctx.globalAlpha = 0.8;
                const coverWidth = outletWidth * 0.3;
                const coverHeight = outletHeight * 0.2;
                const coverX = outletX + outletWidth * 0.1;
                const coverY = outletY + outletHeight * 0.7;
                ctx.fillRect(coverX, coverY, coverWidth, coverHeight);
                ctx.strokeStyle = '#CCC';
                ctx.lineWidth = 0.5;
                ctx.strokeRect(coverX, coverY, coverWidth, coverHeight);
                ctx.globalAlpha = 1.0;
                
                // Electrical box outline (behind the faceplate)
                ctx.strokeStyle = '#999';
                ctx.lineWidth = 1;
                ctx.globalAlpha = 0.3;
                ctx.strokeRect(shape.x - 2, shape.y - 2, width + 4, height + 4);
                ctx.globalAlpha = 1.0;
                
                // Wire nuts (visible in electrical box)
                ctx.fillStyle = '#FFD700'; // Yellow wire nut
                ctx.beginPath();
                ctx.arc(shape.x - 1, shape.y + height/3, 1, 0, 2 * Math.PI);
                ctx.fill();
                
                ctx.fillStyle = '#FF0000'; // Red wire nut
                ctx.beginPath();
                ctx.arc(shape.x - 1, shape.y + 2*height/3, 1, 0, 2 * Math.PI);
                ctx.fill();
                
                // Circuit breaker reference
                ctx.fillStyle = '#000';
                ctx.font = 'bold 2px Arial';
                ctx.textAlign = 'center';
                ctx.fillText('15A', shape.x + width + 8, shape.y + height/2);
                ctx.textAlign = 'start';
                
                // Restore canvas context if rotation was applied
                if (shape.rotation && shape.rotation !== 0) {
                    ctx.restore();
                }
            }
            
            // Outdoor element drawing functions
            function drawTree(shape) {
                const width = shape.width || 30;
                const height = shape.height || 40;
                const centerX = shape.x + width/2;
                const centerY = shape.y + height/2;
                
                // Apply rotation if shape has rotation
                if (shape.rotation && shape.rotation !== 0) {
                    ctx.save();
                    ctx.translate(centerX, centerY);
                    ctx.rotate(shape.rotation * Math.PI / 180);
                    ctx.translate(-centerX, -centerY);
                }
                
                // Tree trunk
                ctx.fillStyle = '#8B4513'; // Brown trunk
                const trunkWidth = width * 0.3;
                const trunkHeight = height * 0.4;
                const trunkX = shape.x + (width - trunkWidth) / 2;
                const trunkY = shape.y + height - trunkHeight;
                ctx.fillRect(trunkX, trunkY, trunkWidth, trunkHeight);
                ctx.strokeStyle = '#654321';
                ctx.lineWidth = 1;
                ctx.strokeRect(trunkX, trunkY, trunkWidth, trunkHeight);
                
                // Tree foliage (multiple circles for natural look)
                ctx.fillStyle = '#228B22'; // Forest green
                const foliageRadius = width * 0.4;
                const foliageX = shape.x + width/2;
                const foliageY = shape.y + height * 0.3;
                
                // Main foliage
                ctx.beginPath();
                ctx.arc(foliageX, foliageY, foliageRadius, 0, 2 * Math.PI);
                ctx.fill();
                
                // Additional foliage layers for depth
                ctx.fillStyle = '#32CD32'; // Lime green
                ctx.beginPath();
                ctx.arc(foliageX - foliageRadius * 0.3, foliageY - foliageRadius * 0.2, foliageRadius * 0.6, 0, 2 * Math.PI);
                ctx.fill();
                
                ctx.beginPath();
                ctx.arc(foliageX + foliageRadius * 0.3, foliageY - foliageRadius * 0.1, foliageRadius * 0.5, 0, 2 * Math.PI);
                ctx.fill();
                
                // Tree shadow
                ctx.fillStyle = 'rgba(0, 0, 0, 0.2)';
                ctx.beginPath();
                ctx.ellipse(shape.x + width/2 + 2, shape.y + height + 2, width * 0.4, height * 0.1, 0, 0, 2 * Math.PI);
                ctx.fill();
                
                // Restore canvas context if rotation was applied
                if (shape.rotation && shape.rotation !== 0) {
                    ctx.restore();
                }
            }
            
            function drawRoad(shape) {
                const width = shape.width || 40;
                const height = shape.height || 20;
                const centerX = shape.x + width/2;
                const centerY = shape.y + height/2;
                
                // Apply rotation if shape has rotation
                if (shape.rotation && shape.rotation !== 0) {
                    ctx.save();
                    ctx.translate(centerX, centerY);
                    ctx.rotate(shape.rotation * Math.PI / 180);
                    ctx.translate(-centerX, -centerY);
                }
                
                // Road surface
                ctx.fillStyle = '#696969'; // Dark gray asphalt
                ctx.fillRect(shape.x, shape.y, width, height);
                ctx.strokeStyle = '#000';
                ctx.lineWidth = 1;
                ctx.strokeRect(shape.x, shape.y, width, height);
                
                // Road markings (center line)
                ctx.strokeStyle = '#FFF'; // White lines
                ctx.lineWidth = 2;
                ctx.setLineDash([5, 5]); // Dashed center line
                ctx.beginPath();
                ctx.moveTo(shape.x, shape.y + height/2);
                ctx.lineTo(shape.x + width, shape.y + height/2);
                ctx.stroke();
                ctx.setLineDash([]); // Reset line dash
                
                // Road edge lines
                ctx.lineWidth = 1;
                ctx.beginPath();
                ctx.moveTo(shape.x, shape.y + 2);
                ctx.lineTo(shape.x + width, shape.y + 2);
                ctx.stroke();
                
                ctx.beginPath();
                ctx.moveTo(shape.x, shape.y + height - 2);
                ctx.lineTo(shape.x + width, shape.y + height - 2);
                ctx.stroke();
                
                // Road texture (small dots for asphalt)
                ctx.fillStyle = '#555';
                for (let i = 0; i < width; i += 3) {
                    for (let j = 0; j < height; j += 3) {
                        if (Math.random() > 0.7) {
                            ctx.fillRect(shape.x + i, shape.y + j, 1, 1);
                        }
                    }
                }
                
                // Restore canvas context if rotation was applied
                if (shape.rotation && shape.rotation !== 0) {
                    ctx.restore();
                }
            }
            
            function drawBuilding(shape) {
                const width = shape.width || 35;
                const height = shape.height || 45;
                const centerX = shape.x + width/2;
                const centerY = shape.y + height/2;
                
                // Apply rotation if shape has rotation
                if (shape.rotation && shape.rotation !== 0) {
                    ctx.save();
                    ctx.translate(centerX, centerY);
                    ctx.rotate(shape.rotation * Math.PI / 180);
                    ctx.translate(-centerX, -centerY);
                }
                
                // Building base
                ctx.fillStyle = '#C0C0C0'; // Silver/gray building
                ctx.fillRect(shape.x, shape.y, width, height);
                ctx.strokeStyle = '#696969';
                ctx.lineWidth = 2;
                ctx.strokeRect(shape.x, shape.y, width, height);
                
                // Building roof
                ctx.fillStyle = '#8B4513'; // Brown roof
                const roofHeight = height * 0.15;
                ctx.fillRect(shape.x - 2, shape.y, width + 4, roofHeight);
                ctx.strokeStyle = '#654321';
                ctx.lineWidth = 1;
                ctx.strokeRect(shape.x - 2, shape.y, width + 4, roofHeight);
                
                // Windows
                ctx.fillStyle = '#87CEEB'; // Sky blue windows
                const windowWidth = width * 0.2;
                const windowHeight = height * 0.15;
                const windowSpacing = width * 0.15;
                
                // First floor windows
                for (let i = 0; i < 2; i++) {
                    const windowX = shape.x + windowSpacing + (i * (windowWidth + windowSpacing));
                    const windowY = shape.y + height * 0.3;
                    ctx.fillRect(windowX, windowY, windowWidth, windowHeight);
                    ctx.strokeStyle = '#000';
                    ctx.lineWidth = 1;
                    ctx.strokeRect(windowX, windowY, windowWidth, windowHeight);
                    
                    // Window frames
                    ctx.strokeStyle = '#FFF';
                    ctx.lineWidth = 1;
                    ctx.beginPath();
                    ctx.moveTo(windowX + windowWidth/2, windowY);
                    ctx.lineTo(windowX + windowWidth/2, windowY + windowHeight);
                    ctx.stroke();
                }
                
                // Second floor windows
                for (let i = 0; i < 2; i++) {
                    const windowX = shape.x + windowSpacing + (i * (windowWidth + windowSpacing));
                    const windowY = shape.y + height * 0.6;
                    ctx.fillRect(windowX, windowY, windowWidth, windowHeight);
                    ctx.strokeStyle = '#000';
                    ctx.lineWidth = 1;
                    ctx.strokeRect(windowX, windowY, windowWidth, windowHeight);
                    
                    // Window frames
                    ctx.strokeStyle = '#FFF';
                    ctx.lineWidth = 1;
                    ctx.beginPath();
                    ctx.moveTo(windowX + windowWidth/2, windowY);
                    ctx.lineTo(windowX + windowWidth/2, windowY + windowHeight);
                    ctx.stroke();
                }
                
                // Main entrance door
                ctx.fillStyle = '#8B4513'; // Brown door
                const doorWidth = width * 0.3;
                const doorHeight = height * 0.25;
                const doorX = shape.x + (width - doorWidth) / 2;
                const doorY = shape.y + height - doorHeight;
                ctx.fillRect(doorX, doorY, doorWidth, doorHeight);
                ctx.strokeStyle = '#654321';
                ctx.lineWidth = 2;
                ctx.strokeRect(doorX, doorY, doorWidth, doorHeight);
                
                // Door handle
                ctx.fillStyle = '#FFD700'; // Gold handle
                const handleSize = 2;
                const handleX = doorX + doorWidth - 3;
                const handleY = doorY + doorHeight/2;
                ctx.beginPath();
                ctx.arc(handleX, handleY, handleSize, 0, 2 * Math.PI);
                ctx.fill();
                
                // Building shadow
                ctx.fillStyle = 'rgba(110, 105, 105, 0.3)';
                ctx.fillRect(shape.x + 2, shape.y + height + 2, width, height * 0.1);
                
                // Restore canvas context if rotation was applied
                if (shape.rotation && shape.rotation !== 0) {
                    ctx.restore();
                }
            }
            
            function drawParking(shape) {
                const width = shape.width || 40;
                const height = shape.height || 30;
                const centerX = shape.x + width/2;
                const centerY = shape.y + height/2;
                
                // Apply rotation if shape has rotation
                if (shape.rotation && shape.rotation !== 0) {
                    ctx.save();
                    ctx.translate(centerX, centerY);
                    ctx.rotate(shape.rotation * Math.PI / 180);
                    ctx.translate(-centerX, -centerY);
                }
                
                // Parking lot surface
                ctx.fillStyle = '#696969'; // Dark gray asphalt
                ctx.fillRect(shape.x, shape.y, width, height);
                ctx.strokeStyle = '#000';
                ctx.lineWidth = 1;
                ctx.strokeRect(shape.x, shape.y, width, height);
                
                // Parking space lines
                ctx.strokeStyle = '#FFF'; // White lines
                ctx.lineWidth = 2;
                const spaceWidth = width / 3;
                
                // Vertical dividing lines
                for (let i = 1; i < 3; i++) {
                    ctx.beginPath();
                    ctx.moveTo(shape.x + i * spaceWidth, shape.y);
                    ctx.lineTo(shape.x + i * spaceWidth, shape.y + height);
                    ctx.stroke();
                }
                
                // Parking space numbers
                ctx.fillStyle = '#FFF';
                ctx.font = 'bold 8px Arial';
                ctx.textAlign = 'center';
                for (let i = 0; i < 3; i++) {
                    const spaceX = shape.x + i * spaceWidth + spaceWidth/2;
                    const spaceY = shape.y + height/2 + 3;
                    ctx.fillText((i + 1).toString(), spaceX, spaceY);
                }
                ctx.textAlign = 'start';
                
                // Parking sign
                ctx.fillStyle = '#000080'; // Navy blue sign
                const signWidth = 8;
                const signHeight = 6;
                const signX = shape.x + width/2 - signWidth/2;
                const signY = shape.y - signHeight - 2;
                ctx.fillRect(signX, signY, signWidth, signHeight);
                ctx.strokeStyle = '#FFF';
                ctx.lineWidth = 1;
                ctx.strokeRect(signX, signY, signWidth, signHeight);
                
                // P letter on sign
                ctx.fillStyle = '#FFF';
                ctx.font = 'bold 6px Arial';
                ctx.textAlign = 'center';
                ctx.fillText('P', signX + signWidth/2, signY + signHeight/2 + 2);
                ctx.textAlign = 'start';
                
                // Restore canvas context if rotation was applied
                if (shape.rotation && shape.rotation !== 0) {
                    ctx.restore();
                }
            }
            
            function drawFountain(shape) {
                const width = shape.width || 30;
                const height = shape.height || 35;
                const centerX = shape.x + width/2;
                const centerY = shape.y + height/2;
                
                // Apply rotation if shape has rotation
                if (shape.rotation && shape.rotation !== 0) {
                    ctx.save();
                    ctx.translate(centerX, centerY);
                    ctx.rotate(shape.rotation * Math.PI / 180);
                    ctx.translate(-centerX, -centerY);
                }
                
                // Fountain base
                ctx.fillStyle = '#C0C0C0'; // Silver base
                ctx.fillRect(shape.x, shape.y, width, height);
                ctx.strokeStyle = '#696969';
                ctx.lineWidth = 2;
                ctx.strokeRect(shape.x, shape.y, width, height);
                
                // Fountain center column
                ctx.fillStyle = '#FFF'; // White marble
                const columnWidth = width * 0.4;
                const columnHeight = height * 0.6;
                const columnX = shape.x + (width - columnWidth) / 2;
                const columnY = shape.y + height * 0.2;
                ctx.fillRect(columnX, columnY, columnWidth, columnHeight);
                ctx.strokeStyle = '#DDD';
                ctx.lineWidth = 1;
                ctx.strokeRect(columnX, columnY, columnWidth, columnHeight);
                
                // Fountain top bowl
                ctx.fillStyle = '#87CEEB'; // Light blue water
                const bowlWidth = width * 0.6;
                const bowlHeight = height * 0.15;
                const bowlX = shape.x + (width - bowlWidth) / 2;
                const bowlY = shape.y + height * 0.15;
                ctx.beginPath();
                ctx.arc(bowlX + bowlWidth/2, bowlY + bowlHeight/2, bowlWidth/2, 0, 2 * Math.PI);
                ctx.fill();
                ctx.strokeStyle = '#4682B4';
                ctx.lineWidth = 1;
                ctx.stroke();
                
                // Water spout
                ctx.fillStyle = '#87CEEB';
                const spoutWidth = 2;
                const spoutHeight = height * 0.1;
                const spoutX = shape.x + width/2 - spoutWidth/2;
                const spoutY = shape.y + height * 0.25;
                ctx.fillRect(spoutX, spoutY, spoutWidth, spoutHeight);
                
                // Water droplets
                ctx.fillStyle = '#87CEEB';
                for (let i = 0; i < 3; i++) {
                    const dropX = spoutX + (i - 1) * 3;
                    const dropY = spoutY + spoutHeight + 2;
                    ctx.beginPath();
                    ctx.arc(dropX, dropY, 1, 0, 2 * Math.PI);
                    ctx.fill();
                }
                
                // Fountain label
                ctx.fillStyle = '#000';
                ctx.font = 'bold 6px Arial';
                ctx.textAlign = 'center';
                ctx.fillText('FOUNTAIN', shape.x + width/2, shape.y + height + 10);
                ctx.textAlign = 'start';
                
                // Restore canvas context if rotation was applied
                if (shape.rotation && shape.rotation !== 0) {
                    ctx.restore();
                }
            }
            
            function drawGarden(shape) {
                const width = shape.width || 35;
                const height = shape.height || 30;
                const centerX = shape.x + width/2;
                const centerY = shape.y + height/2;
                
                // Apply rotation if shape has rotation
                if (shape.rotation && shape.rotation !== 0) {
                    ctx.save();
                    ctx.translate(centerX, centerY);
                    ctx.rotate(shape.rotation * Math.PI / 180);
                    ctx.translate(-centerX, -centerY);
                }
                
                // Garden soil base
                ctx.fillStyle = '#8B4513'; // Brown soil
                ctx.fillRect(shape.x, shape.y, width, height);
                ctx.strokeStyle = '#654321';
                ctx.lineWidth = 1;
                ctx.strokeRect(shape.x, shape.y, width, height);
                
                // Garden border
                ctx.strokeStyle = '#228B22'; // Green border
                ctx.lineWidth = 2;
                ctx.strokeRect(shape.x, shape.y, width, height);
                
                // Flowers (various colors)
                const flowerColors = ['#FF0000', '#FF69B4', '#FFD700', '#FF4500', '#9370DB'];
                const flowerCount = 8;
                
                for (let i = 0; i < 8; i++) {
                    const flowerX = shape.x + 5 + (i % 3) * (width / 3);
                    const flowerY = shape.y + 5 + Math.floor(i / 3) * (height / 3);
                    
                    // Flower petals
                    ctx.fillStyle = flowerColors[i % flowerColors.length];
                    const petalRadius = 2;
                    ctx.beginPath();
                    ctx.arc(flowerX, flowerY, petalRadius, 0, 2 * Math.PI);
                    ctx.fill();
                    
                    // Flower center
                    ctx.fillStyle = '#FFD700'; // Gold center
                    ctx.beginPath();
                    ctx.arc(flowerX, flowerY, 1, 0, 2 * Math.PI);
                    ctx.fill();
                }
                
                // Small trees/bushes
                ctx.fillStyle = '#228B22'; // Green bushes
                for (let i = 0; i < 3; i++) {
                    const bushX = shape.x + 8 + i * (width / 4);
                    const bushY = shape.y + height - 8;
                    const bushRadius = 3;
                    ctx.beginPath();
                    ctx.arc(bushX, bushY, bushRadius, 0, 2 * Math.PI);
                    ctx.fill();
                }
                
                // Garden path
                ctx.fillStyle = '#DEB887'; // Tan path
                const pathWidth = 3;
                const pathX = shape.x + width/2 - pathWidth/2;
                ctx.fillRect(pathX, shape.y, pathWidth, height);
                
                // Garden label
                ctx.fillStyle = '#000';
                ctx.font = 'bold 6px Arial';
                ctx.textAlign = 'center';
                ctx.fillText('GARDEN', shape.x + width/2, shape.y + height + 10);
                ctx.textAlign = 'start';
                
                // Restore canvas context if rotation was applied
                if (shape.rotation && shape.rotation !== 0) {
                    ctx.restore();
                }
            }
            
            // Initialize
            initCanvas();
            loadFloorplanDefaults();
            loadFloorplan();
        });

        // Functions for change booth modal
        function showChangeBoothModal() {
            const modal = new bootstrap.Modal(document.getElementById('changeBoothModal'));
            modal.show();
            loadAvailableAlternatives();
        }

        function loadAvailableAlternatives() {
            const container = document.getElementById('availableAlternatives');
            
            // Get available items from the current shapes data
            const availableItems = shapes.filter(shape => 
                shape.booking_status === 'available' && 
                shape.id !== {{ $existingBooking->floorplan_item_id ?? 0 }}
            );

            if (availableItems.length === 0) {
                container.innerHTML = `
                    <div class="text-center py-3">
                        <i class="bi bi-info-circle text-muted" style="font-size: 2rem;"></i>
                        <p class="mt-2 text-muted">No alternative spaces available at the moment.</p>
                    </div>
                `;
                return;
            }

            let alternativesHtml = '';
            availableItems.slice(0, 5).forEach(item => { // Show max 5 alternatives
                alternativesHtml += `
                    <div class="card border-success mb-2">
                        <div class="card-body p-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-title mb-1">${item.item_name || item.label || item.type}</h6>
                                    <small class="text-muted">
                                        <strong>Type:</strong> ${item.type} | 
                                        <strong>Capacity:</strong> ${item.max_capacity || 5} | 
                                        <strong>Price:</strong> $${item.price || 100}
                                    </small>
                                </div>
                                <button type="button" class="btn btn-success btn-sm" onclick="changeToNewSpace(${item.id})">
                                    <i class="bi bi-arrow-right me-1"></i>Select
                                </button>
                            </div>
                        </div>
                    </div>
                `;
            });

            container.innerHTML = alternativesHtml;
        }

        function changeToNewSpace(itemId) {
            if (confirm('Are you sure you want to change to this new space? This will remove your current booking and start over.')) {
                // Redirect to book the new space
                window.location.href = `/event/{{ $event->slug }}/book/${itemId}`;
            }
        }

        function removeCurrentBooking() {
            if (confirm('Are you sure you want to remove your current booking? This action cannot be undone.')) {
                // Show loading state
                const removeBtn = document.querySelector('button[onclick="removeCurrentBooking()"]');
                const originalText = removeBtn.innerHTML;
                removeBtn.innerHTML = '<i class="bi bi-hourglass-split me-1"></i>Removing...';
                removeBtn.disabled = true;

                // Make AJAX call to remove booking
                fetch(`/event/{{ $event->slug }}/booking/{{ $existingBooking->access_token ?? '' }}/remove`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Redirect to floorplan to start over
                        window.location.href = '{{ route("events.public.floorplan", $event->slug) }}';
                    } else {
                        alert(data.message || 'Failed to remove booking. Please try again.');
                        // Restore button
                        removeBtn.innerHTML = originalText;
                        removeBtn.disabled = false;
                    }
                })
                .catch(error => {
                    console.error('Error removing booking:', error);
                    alert('An error occurred while removing the booking. Please try again.');
                    // Restore button
                    removeBtn.innerHTML = originalText;
                    removeBtn.disabled = false;
                });
            }
        }
    </script>
@endpush

