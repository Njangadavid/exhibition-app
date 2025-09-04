@extends('layouts.public')

@section('title', 'Owner Registration')

@push('styles')
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/19.2.16/css/intlTelInput.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/css/select2.min.css">
<style>
    .iti {
        width: 100%;
    }

    .iti__flag-container {
        cursor: pointer;
    }

    .iti__country-list {
        z-index: 1050;
        max-height: 200px;
        overflow-y: auto;
    }

    .phone-input-wrapper {
        position: relative;
    }

    .phone-input-wrapper .form-control {
        padding-left: 90px;
    }

    .iti__country {
        padding: 8px 10px;
        cursor: pointer;
    }

    .iti__country:hover {
        background-color: #f8f9fa;
    }

    .iti__country.iti__active {
        background-color: #e9ecef;
    }

    .iti__dial-code {
        color: #6c757d;
    }

    .iti__flag {
        margin-right: 8px;
    }

    /* Phone input validation styles */
    .phone-input-wrapper .form-control.is-valid {
        border-color: #198754;
        padding-right: calc(1.5em + 0.75rem);
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 8 8'%3e%3cpath fill='%23198754' d='m2.3 6.73.94-.94 2.89 2.89 2.89-2.89.94.94L5.12 9.62z'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right calc(0.375em + 0.1875rem) center;
        background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
    }

    .phone-input-wrapper .form-control.is-invalid {
        border-color: #dc3545;
        padding-right: calc(1.5em + 0.75rem);
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' xmlns:xlink='http://www.w3.org/1999/xlink'%3e%3cdefs%3e%3cpath id='a' d='M6 0C2.69 0 0 2.69 0 6s2.69 6 6 6 6-2.69 6-6-2.69-6-6-6zm0 10.5c-2.49 0-4.5-2.01-4.5-4.5S3.51 1.5 6 1.5s4.5 2.01 4.5 4.5-2.01 4.5-4.5 4.5z'/%3e%3c/defs%3e%3cuse xlink:href='%23a' fill='%23dc3545'/%3e%3cpath d='M6 3.5c.28 0 .5.22.5.5v3c0 .28-.22.5-.5.5s-.5-.22-.5-.5V4c0-.28.22-.5.5-.5z' fill='%23dc3545'/%3e%3cpath d='M6 8.5c.28 0 .5-.22.5-.5s-.22-.5-.5-.5-.5.22-.5.5.22.5.5.5z' fill='%23dc3545'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right calc(0.375em + 0.1875rem) center;
        background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
    }

    /* Form improvements */
    .card-header {
        border-bottom: 2px solid rgba(0, 0, 0, 0.1);
    }

    .form-control-sm,
    .form-select-sm {
        font-size: 0.875rem;
        padding: 0.375rem 0.75rem;
    }

    .form-label.small {
        font-size: 0.875rem;
        font-weight: 500;
    }

    .card-body.py-3 {
        padding-top: 1rem !important;
        padding-bottom: 1rem !important;
    }

    .row.g-3 {
        --bs-gutter-x: 1rem;
        --bs-gutter-y: 0.75rem;
    }

    /* Hover effects for cards */
    .card:hover {
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        transition: box-shadow 0.15s ease-in-out;
    }

    /* Compact spacing */
    .mb-2 {
        margin-bottom: 0.5rem !important;
    }

    .mb-4 {
        margin-bottom: 1.5rem !important;
    }

    /* Form section headers */
    .card-header h6 {
        font-size: 1rem;
        font-weight: 600;
    }

    .card-header small {
        font-size: 0.8rem;
        opacity: 0.8;
    }

    /* Country flag styling */
    .form-select option {
        padding: 8px 12px;
        font-size: 0.875rem;
    }

    .form-select option:first-child {
        font-weight: 500;
        color: #6c757d;
    }

    /* Badge styling */
    .badge.bg-warning.text-dark {
        font-size: 0.7rem;
        font-weight: 600;
        padding: 0.25rem 0.5rem;
    }

    /* Required field styling */
    .text-danger {
        color: #dc3545 !important;
        font-weight: bold;
    }

    /* Select2 customization */
    .select2-container--default .select2-selection--single {
        height: 38px;
        border: 1px solid #ced4da;
        border-radius: 0.375rem;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 36px;
        padding-left: 12px;
        padding-right: 20px;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 36px;
    }

    .select2-container--default .select2-search--dropdown .select2-search__field {
        border: 1px solid #ced4da;
        border-radius: 0.375rem;
        padding: 8px 12px;
    }

    .select2-dropdown {
        border: 1px solid #ced4da;
        border-radius: 0.375rem;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }

    .select2-results__option {
        padding: 8px 12px;
        font-size: 0.875rem;
    }

    .select2-results__option--highlighted[aria-selected] {
        background-color: #0d6efd;
        color: white;
    }

    .select2-container--default .select2-results__option[aria-selected=true] {
        background-color: #e9ecef;
    }
</style>
@endpush

@php
    $showProgress = true;
    $currentStep = 2;
@endphp

@section('content')
<div class="py-4">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">

                    <!-- Selected Item Info -->
                    <div class="card mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">
                                <i class="bi bi-shop me-2"></i>
                                Selected Space: {{ $item->label ?? $item->item_name ?? 'Booth' }}
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="bi bi-people text-primary me-2"></i>
                                        <span class="fw-medium">Capacity:</span>
                                        <span class="ms-2">{{ $item->max_capacity ?? 'N/A' }} people</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="bi bi-currency-dollar text-success me-2"></i>
                                        <span class="fw-medium">Price:</span>
                                        <span class="ms-2">${{ $item->price ?? 'N/A' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Owner Registration Form -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="bi bi-person-circle me-2"></i>
                                @if($isEditing)
                                    Edit Owner Information
                                @else
                                    Owner Information
                                @endif
                            </h5>
                            <small class="text-muted">
                                @if($isEditing)
                                    Update your contact and company details
                                @else
                                    Please provide your contact and company details
                                @endif
                            </small>
                        </div>
                        <div class="card-body">
                            @if($isEditing && $boothOwner)
                                <div class="alert alert-info mb-4">
                                    <i class="bi bi-info-circle me-2"></i>
                                    <strong>Welcome back!</strong> We found your existing booking for this space. 
                                    You can update any of your details below. Your current information has been pre-filled for your convenience.
                                </div>
                            @endif
                            
                            <form action="{{ $isEditing ? route('bookings.process-owner-token', ['eventSlug' => $event->slug, 'accessToken' => $boothOwner->access_token]) : route('bookings.process-owner', ['eventSlug' => $event->slug, 'itemId' => $item->id]) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                
                            <!-- Personal Information -->
                            <div class="card mb-4">
                                <div class="card-header bg-primary text-white py-2">
                                    <h6 class="mb-0">
                                        <i class="bi bi-person me-2"></i>Personal Information
                                    </h6>
                                </div>
                                <div class="card-body py-3">
                                    <div class="row g-3">
                                    <div class="col-md-6">
                                            <div class="mb-2">
                                                <label for="owner_name" class="form-label small mb-1">Full Name <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control form-control-sm @error('name') is-invalid @enderror"
                                                    id="owner_name" name="name"
                                                    value="{{ old('name', $boothOwner->form_responses['name'] ?? '') }}" required>
                                                @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                            <div class="mb-2">
                                                <label for="owner_email" class="form-label small mb-1">Email Address <span class="text-danger">*</span></label>
                                                <input type="email" class="form-control form-control-sm @error('email') is-invalid @enderror"
                                                    id="owner_email" name="email"
                                                    value="{{ old('email', $boothOwner->form_responses['email'] ?? '') }}" required>
                                                @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                        <div class="col-md-6">
                                            <div class="mb-2">
                                                <label for="owner_phone" class="form-label small mb-1">Phone Number <span class="text-danger">*</span></label>
                                                <div class="phone-input-wrapper">
                                                    <input type="tel" class="form-control form-control-sm @error('phone') is-invalid @enderror"
                                                        id="owner_phone" name="phone"
                                                        value="{{ old('phone', $boothOwner->form_responses['phone'] ?? '') }}" required>
                                </div>
                                                <input type="hidden" id="owner_phone_country" name="phone_country" value="{{ old('phone_country', $boothOwner->form_responses['phone_country'] ?? '') }}">
                                                <div class="form-text small">Select country and enter phone number</div>
                                                @error('phone')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                            <div class="mb-2">
                                                <label for="company_name" class="form-label small mb-1">Company Name <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control form-control-sm @error('company_name') is-invalid @enderror"
                                                   id="company_name" name="company_name" 
                                                   value="{{ old('company_name', $boothOwner->form_responses['company_name'] ?? '') }}" required>
                                            @error('company_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            </div>
                                        </div>
                                        </div>
                                    </div>
                                </div>

                            <!-- Company Details -->
                            <div class="card mb-4">
                                <div class="card-header bg-info text-white py-2">
                                    <h6 class="mb-0">
                                        <i class="bi bi-building me-2"></i>Company Details <span class="badge bg-warning text-dark ms-2">Required</span>
                                    </h6>
                                </div>
                                <div class="card-body py-3">
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <div class="mb-2">
                                                <label for="company_address" class="form-label small mb-1">
                                                    <i class="bi bi-geo-alt me-1"></i>Company Address
                                                </label>
                                                <textarea class="form-control form-control-sm @error('company_address') is-invalid @enderror"
                                                    id="company_address" name="company_address" rows="2"
                                                    placeholder="Enter your company address">{{ old('company_address', $boothOwner->form_responses['company_address'] ?? '') }}</textarea>
                                            @error('company_address')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                        <div class="col-md-4">
                                            <div class="mb-2">
                                                <label for="company_website" class="form-label small mb-1">
                                                    <i class="bi bi-globe me-1"></i>Company Website
                                                </label>
                                                <input type="url" class="form-control form-control-sm @error('company_website') is-invalid @enderror"
                                                   id="company_website" name="company_website" 
                                                   value="{{ old('company_website',$boothOwner->form_responses['company_website'] ?? '') }}" 
                                                   placeholder="https://example.com">
                                            @error('company_website')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-2">
                                                <label for="country" class="form-label small mb-1">
                                                    <i class="bi bi-flag me-1"></i>Country <span class="text-danger">*</span>
                                                </label>
                                                <select class="form-select form-select-sm @error('country') is-invalid @enderror"
                                                    id="country" name="country" required data-search="true">
                                                    <option value="">Select Country</option>
                                                    @foreach(config('countries') as $code => $name)
                                                    <option value="{{ $code }}" {{ old('country', $boothOwner->form_responses['country'] ?? '') == $code ? 'selected' : '' }}>
                                                        {{ mb_chr(ord($code[0]) + 127397) . mb_chr(ord($code[1]) + 127397) }} {{ $name }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                                @error('country')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        </div>
                                    </div>
                                </div>

                            <!-- Logo & Booth Name -->
                            <div class="card mb-4">
                                <div class="card-header bg-warning text-dark py-2">
                                    <h6 class="mb-0">
                                        <i class="bi bi-images me-2"></i>Logo & Booth Name
                                        @if(!($boothOwner && isset($boothOwner->form_responses['company_logo']) && $boothOwner->form_responses['company_logo']) || !($boothOwner && isset($boothOwner->form_responses['booth_name']) && $boothOwner->form_responses['booth_name']))
                                        <span class="badge bg-warning text-dark ms-2">Required</span>
                                        @endif
                                    </h6>
                                </div>
                                <div class="card-body py-3">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="mb-2">
                                                <label for="company_logo" class="form-label small mb-1">
                                                    <i class="bi bi-image me-1"></i>Company Logo
                                                    @if(!($boothOwner && isset($boothOwner->form_responses['company_logo']) && $boothOwner->form_responses['company_logo']))
                                                    <span class="text-danger">*</span>
                                                    @endif
                                                </label>
                                            @if($boothOwner && isset($boothOwner->form_responses['company_logo']) && $boothOwner->form_responses['company_logo'])
                                                <div class="mb-2">
                                                    <small class="text-muted">Current logo:</small>
                                                    <img src="{{ Storage::url($boothOwner->form_responses['company_logo']) }}" 
                                                         alt="Current Company Logo" class="ms-2" style="max-height: 40px; max-width: 100px;">
                                                </div>
                                            @endif
                                                <input type="file" class="form-control form-control-sm @error('company_logo') is-invalid @enderror"
                                                    id="company_logo" name="company_logo" accept="image/*"
                                                    data-has-existing="{{ $boothOwner && isset($boothOwner->form_responses['company_logo']) && $boothOwner->form_responses['company_logo'] ? 'true' : 'false' }}">
                                                <div class="form-text small">
                                                @if($boothOwner && isset($boothOwner->form_responses['company_logo']))
                                                    Upload new logo to replace current
                                                @else
                                                    PNG, JPG, SVG - Max 2MB
                                                @endif
                                            </div>
                                            @error('company_logo')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-2">
                                                <label for="booth_name" class="form-label small mb-1">
                                                    <i class="bi bi-tag me-1"></i>Booth Name
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <input type="text" class="form-control form-control-sm @error('booth_name') is-invalid @enderror"
                                                    id="booth_name" name="booth_name" maxlength="25"
                                                    value="{{ old('booth_name', $boothOwner->form_responses['booth_name'] ?? '') }}"
                                                    placeholder="Enter booth name (max 25 characters)">
                                                <div class="d-flex justify-content-between align-items-center mt-1">
                                                    <div class="form-text small text-muted">
                                                        Display name for your booth
                                                    </div>
                                                    <small class="text-muted">
                                                        <span id="booth_name_count">0</span>/25
                                                    </small>
                                                </div>
                                                @error('booth_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Social Media Handles -->
                            <div class="card mb-4">
                                <div class="card-header bg-light py-2">
                                    <h6 class="mb-0">
                                        <i class="bi bi-share me-2"></i>Social Media & Online Presence
                                    </h6>
                                    <small class="text-muted">Connect your social media accounts (optional)</small>
                                </div>
                                <div class="card-body py-3">
                                    <div class="row g-3">
                                    <div class="col-md-6">
                                            <div class="mb-2">
                                                <label for="social_facebook" class="form-label small mb-1">
                                                    <i class="bi bi-facebook text-primary me-1"></i>Facebook
                                            </label>
                                                <input type="url" class="form-control form-control-sm @error('social_facebook') is-invalid @enderror"
                                                   id="social_facebook" name="social_facebook" 
                                                   value="{{ old('social_facebook', $boothOwner->form_responses['social_facebook'] ?? '') }}" 
                                                   placeholder="https://facebook.com/yourcompany">
                                            @error('social_facebook')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                            <div class="mb-2">
                                                <label for="social_twitter" class="form-label small mb-1">
                                                    <i class="bi bi-twitter text-info me-1"></i>Twitter/X
                                            </label>
                                                <input type="url" class="form-control form-control-sm @error('social_twitter') is-invalid @enderror"
                                                   id="social_twitter" name="social_twitter" 
                                                   value="{{ old('social_twitter', $boothOwner->form_responses['social_twitter'] ?? '') }}" 
                                                   placeholder="https://twitter.com/yourcompany">
                                            @error('social_twitter')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                            <div class="mb-2">
                                                <label for="social_linkedin" class="form-label small mb-1">
                                                    <i class="bi bi-linkedin text-primary me-1"></i>LinkedIn
                                            </label>
                                                <input type="url" class="form-control form-control-sm @error('social_linkedin') is-invalid @enderror"
                                                   id="social_linkedin" name="social_linkedin" 
                                                   value="{{ old('social_linkedin', $boothOwner->form_responses['social_linkedin'] ?? '') }}" 
                                                   placeholder="https://linkedin.com/company/yourcompany">
                                            @error('social_linkedin')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                            <div class="mb-2">
                                                <label for="social_instagram" class="form-label small mb-1">
                                                    <i class="bi bi-instagram text-danger me-1"></i>Instagram
                                            </label>
                                                <input type="url" class="form-control form-control-sm @error('social_instagram') is-invalid @enderror"
                                                   id="social_instagram" name="social_instagram" 
                                                   value="{{ old('social_instagram', $boothOwner->form_responses['social_instagram'] ?? '') }}" 
                                                   placeholder="https://instagram.com/yourcompany">
                                            @error('social_instagram')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            </div>
                                        </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Email Preference (only show when editing) -->
                                @if($isEditing)
                            <div class="card mb-4">
                                <div class="card-body py-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="resend_email" name="resend_email" value="1">
                                        <label class="form-check-label" for="resend_email">
                                            <i class="bi bi-envelope me-2"></i>
                                            Send confirmation email with updated details
                                        </label>
                                    </div>
                                    </div>
                                </div>
                                @endif

                                <div class="d-flex justify-content-between align-items-center">
                                    <a href="{{ $isEditing ? route('events.public.floorplan-token', ['event' => $event->slug, 'accessToken' => $boothOwner->access_token]) : route('events.public.floorplan', $event->slug) }}" class="btn btn-outline-secondary">
                                        <i class="bi bi-arrow-left me-2"></i>
                                        Back to Floorplan
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        @if($isEditing)
                                            Update & Continue
                                        @else
                                            Submit & Continue
                                        @endif
                                        <i class="bi bi-arrow-right ms-2"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/19.2.16/js/intlTelInput.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/js/select2.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Select2 for country dropdown
        $('#country').select2({
            placeholder: 'Select Country',
            allowClear: true,
            width: '100%',
            language: {
                noResults: function() {
                    return "No countries found";
                },
                searching: function() {
                    return "Searching...";
                }
            }
        });

        // Initialize international telephone input
        let phoneInput;
        try {
            phoneInput = window.intlTelInput(document.querySelector("#owner_phone"), {
                preferredCountries: ['KE', 'US', 'GB', 'CA', 'AU', 'NG', 'ZA', 'EG', 'IN'], // Kenya first, then common countries
                separateDialCode: true,
                utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/19.2.16/js/utils.js",
                initialCountry: 'auto',
                geoIpLookup: function(callback) {
                    // Try to detect user's country, fallback to Kenya
                    fetch('https://ipapi.co/json/')
                        .then(res => res.json())
                        .then(data => callback(data.country_code))
                        .catch(() => callback('KE'));
                },
                formatOnDisplay: true,
                autoHideDialCode: false,
                autoPlaceholder: 'aggressive',
                nationalMode: false,
                allowDropdown: true,
                showSelectedDialCode: true
            });

            // Wait for utils to load before proceeding
            if (phoneInput.promise) {
                phoneInput.promise.then(function() {
                    // Utils loaded successfully
                }).catch(function(error) {
                    // Utils failed to load, but phone input will still work
                });
            }
        } catch (error) {
            phoneInput = null;
        }

        // Update hidden country field when country changes
        // Use the proper intl-tel-input event binding
        if (phoneInput && typeof phoneInput.on === 'function') {
            phoneInput.on('countrychange', function() {
                const countryData = phoneInput.getSelectedCountryData();
                document.getElementById('owner_phone_country').value = countryData.iso2;

                // Update placeholder with new country format
                const input = document.getElementById('owner_phone');
                if (input.value === '') {
                    input.placeholder = phoneInput.getPlaceholder();
                }

                // If there's an existing phone number, reformat it for the new country
                if (input.value && input.value.trim() !== '') {
                    // Remove any existing country code and reformat
                    const cleanNumber = input.value.replace(/^\+?\d{1,4}/, '').trim();
                    if (cleanNumber) {
                        input.value = '+' + countryData.dialCode + cleanNumber;
                    }
                }

                // Country changed successfully
            });
        } else if (phoneInput && typeof phoneInput.addEventListener === 'function') {
            // Alternative event binding method
            phoneInput.addEventListener('countrychange', function() {
                const countryData = phoneInput.getSelectedCountryData();
                document.getElementById('owner_phone_country').value = countryData.iso2;
            });
        } else {
            // Fallback: listen for changes on the input itself and detect country changes
            const phoneInputElement = document.getElementById('owner_phone');
            let lastCountry = phoneInput ? phoneInput.getSelectedCountryData()?.iso2 : 'KE';
            
            phoneInputElement.addEventListener('input', function() {
                if (phoneInput) {
                    const currentCountry = phoneInput.getSelectedCountryData()?.iso2;
                    if (currentCountry && currentCountry !== lastCountry) {
                        lastCountry = currentCountry;
                        document.getElementById('owner_phone_country').value = currentCountry;
                    }
                }
            });
        }

        // Set initial country if we have a saved value
        if (phoneInput) {
            const savedCountry = document.getElementById('owner_phone_country').value;
            if (savedCountry) {
                phoneInput.setCountry(savedCountry);
            } else {
                // Try to detect country from existing phone number
                const existingPhone = document.getElementById('owner_phone').value;
                if (existingPhone) {
                    try {
                        const countryCode = phoneInput.getCountryCode();
                        if (countryCode) {
                            document.getElementById('owner_phone_country').value = countryCode;
                        }
                    } catch (e) {
                        // If we can't detect, default to Kenya
                        phoneInput.setCountry('KE');
                        document.getElementById('owner_phone_country').value = 'KE';
                    }
                }
            }
        }

        // Format phone number on blur and provide real-time validation
        document.getElementById('owner_phone').addEventListener('blur', function() {
            if (phoneInput && phoneInput.isValidNumber()) {
                // Get the number in E164 format (includes + and country code)
                const formattedNumber = phoneInput.getNumber(intlTelInputUtils.numberFormat.E164);
                this.value = formattedNumber;

                // Remove error styling
                this.classList.remove('is-invalid');
                const errorDiv = this.parentNode.querySelector('.invalid-feedback');
                if (errorDiv) {
                    errorDiv.remove();
                }

                // Add success styling briefly
                this.classList.add('is-valid');
                setTimeout(() => {
                    this.classList.remove('is-valid');
                }, 2000);
            } else if (this.value.trim() !== '') {
                // Show error for invalid number
                this.classList.add('is-invalid');
                this.classList.remove('is-valid');

                // Create or update error message
                let errorDiv = this.parentNode.querySelector('.invalid-feedback');
                if (!errorDiv) {
                    errorDiv = document.createElement('div');
                    errorDiv.className = 'invalid-feedback';
                    this.parentNode.appendChild(errorDiv);
                }
                errorDiv.textContent = 'Please enter a valid phone number for ' + phoneInput.getSelectedCountryData().name;
            }
        });



        // Clear validation styling on input
        document.getElementById('owner_phone').addEventListener('input', function() {
            this.classList.remove('is-invalid', 'is-valid');
            const errorDiv = this.parentNode.querySelector('.invalid-feedback');
            if (errorDiv) {
                errorDiv.remove();
            }
            

        });

        // Validate phone number before form submission
        document.querySelector('form').addEventListener('submit', function(e) {
            if (!phoneInput || !phoneInput.isValidNumber()) {
                e.preventDefault();

                // Show a better error message
                const phoneField = document.getElementById('owner_phone');
                phoneField.classList.add('is-invalid');

                // Create or update error message
                let errorDiv = phoneField.parentNode.querySelector('.invalid-feedback');
                if (!errorDiv) {
                    errorDiv = document.createElement('div');
                    errorDiv.className = 'invalid-feedback';
                    phoneField.parentNode.appendChild(errorDiv);
                }
                errorDiv.textContent = phoneInput ? 'Please enter a valid phone number for the selected country.' : 'Phone input not initialized. Please refresh the page.';

                phoneField.focus();
                return false;
            }

            // Validate required files only if they don't already exist
            const companyLogo = document.getElementById('company_logo');
            const boothName = document.getElementById('booth_name');

            // Check if company logo is required (no existing logo)
            if (companyLogo.dataset.hasExisting === 'false' && !companyLogo.files.length) {
                e.preventDefault();
                companyLogo.classList.add('is-invalid');
                let errorDiv = companyLogo.parentNode.querySelector('.invalid-feedback');
                if (!errorDiv) {
                    errorDiv = document.createElement('div');
                    errorDiv.className = 'invalid-feedback';
                    companyLogo.parentNode.appendChild(errorDiv);
                }
                errorDiv.textContent = 'Company logo is required.';
                companyLogo.focus();
                return false;
            }

            // Check if booth name is required
            if (!boothName.value.trim()) {
                e.preventDefault();
                boothName.classList.add('is-invalid');
                let errorDiv = boothName.parentNode.querySelector('.invalid-feedback');
                if (!errorDiv) {
                    errorDiv.className = 'invalid-feedback';
                    boothName.parentNode.appendChild(errorDiv);
                }
                errorDiv.textContent = 'Booth name is required.';
                boothName.focus();
                return false;
            }

            // Remove any error styling
            const phoneField = document.getElementById('owner_phone');
            phoneField.classList.remove('is-invalid');
            const errorDiv = phoneField.parentNode.querySelector('.invalid-feedback');
            if (errorDiv) {
                errorDiv.remove();
            }

            // Update the phone input with the formatted number including country code
            if (phoneInput) {
                const formattedNumber = phoneInput.getNumber(intlTelInputUtils.numberFormat.E164);
                document.getElementById('owner_phone').value = formattedNumber;

                // Update the country field
                const countryData = phoneInput.getSelectedCountryData();
                document.getElementById('owner_phone_country').value = countryData.iso2;

                // Phone number formatted successfully

                // Ensure the phone number is in the correct format
                if (!formattedNumber.startsWith('+')) {
                    alert('Phone number format error. Please check the phone number and try again.');
                    e.preventDefault();
                    return false;
                }
            }
        });

        // Handle file input changes to update validation state
        document.getElementById('company_logo').addEventListener('change', function() {
            if (this.files.length > 0) {
                this.dataset.hasExisting = 'false';
                this.classList.remove('is-invalid');
                const errorDiv = this.parentNode.querySelector('.invalid-feedback');
                if (errorDiv) {
                    errorDiv.remove();
                }
            }
        });



        // Booth name character count
        const boothNameInput = document.getElementById('booth_name');
        const boothNameCount = document.getElementById('booth_name_count');
        
        function updateBoothNameCount() {
            const currentLength = boothNameInput.value.length;
            boothNameCount.textContent = currentLength;
            
            // Change color based on character count
            if (currentLength > 20) {
                boothNameCount.style.color = '#dc3545'; // Red
            } else if (currentLength > 15) {
                boothNameCount.style.color = '#ffc107'; // Yellow
            } else {
                boothNameCount.style.color = '#6c757d'; // Gray
            }
            
            // Clear validation errors when user types
            if (boothNameInput.value.trim()) {
                boothNameInput.classList.remove('is-invalid');
                const errorDiv = boothNameInput.parentNode.querySelector('.invalid-feedback');
                if (errorDiv) {
                    errorDiv.remove();
                }
            }
        }
        
        boothNameInput.addEventListener('input', updateBoothNameCount);
        boothNameInput.addEventListener('keyup', updateBoothNameCount);
        
        // Initialize count on page load
        updateBoothNameCount();
    });
</script>
@endpush