@extends('layouts.public')

@section('title', 'Owner Registration')

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
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="owner_name" class="form-label">Full Name *</label>
                                            <input type="text" class="form-control @error('owner_name') is-invalid @enderror" 
                                                   id="owner_name" name="owner_name" 
                                                   value="{{ old('owner_name', $boothOwner->form_responses['name'] ?? '') }}" required>
                                            @error('owner_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="owner_email" class="form-label">Email Address *</label>
                                            <input type="email" class="form-control @error('owner_email') is-invalid @enderror" 
                                                   id="owner_email" name="owner_email" 
                                                   value="{{ old('owner_email', $boothOwner->form_responses['email'] ?? '') }}" required>
                                            @error('owner_email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="owner_phone" class="form-label">Phone Number *</label>
                                            <input type="tel" class="form-control @error('owner_phone') is-invalid @enderror" 
                                                   id="owner_phone" name="owner_phone" 
                                                   value="{{ old('owner_phone', $boothOwner->form_responses['phone'] ?? '') }}" required>
                                            @error('owner_phone')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="company_name" class="form-label">Company Name *</label>
                                            <input type="text" class="form-control @error('company_name') is-invalid @enderror" 
                                                   id="company_name" name="company_name" 
                                                   value="{{ old('company_name', $boothOwner->form_responses['company_name'] ?? '') }}" required>
                                            @error('company_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="company_address" class="form-label">Company Address</label>
                                            <textarea class="form-control @error('company_address') is-invalid @enderror" 
                                                      id="company_address" name="company_address" rows="2">{{ old('company_address', $boothOwner->form_responses['company_address'] ?? '') }}</textarea>
                                            @error('company_address')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="company_website" class="form-label">Company Website</label>
                                            <input type="url" class="form-control @error('company_website') is-invalid @enderror" 
                                                   id="company_website" name="company_website" 
                                                   value="{{ old('company_website',$boothOwner->form_responses['company_website'] ?? '') }}" 
                                                   placeholder="https://example.com">
                                            @error('company_website')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Company Logo Upload -->
                                <div class="row">
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label for="company_logo" class="form-label">Company Logo</label>
                                            @if($boothOwner && isset($boothOwner->form_responses['company_logo']) && $boothOwner->form_responses['company_logo'])
                                                <div class="mb-2">
                                                    <small class="text-muted">Current logo:</small>
                                                    <img src="{{ Storage::url($boothOwner->form_responses['company_logo']) }}" 
                                                         alt="Current Company Logo" class="ms-2" style="max-height: 40px; max-width: 100px;">
                                                </div>
                                            @endif
                                            <input type="file" class="form-control @error('company_logo') is-invalid @enderror" 
                                                   id="company_logo" name="company_logo" accept="image/*">
                                            <div class="form-text">
                                                @if($boothOwner && isset($boothOwner->form_responses['company_logo']))
                                                    Upload a new logo to replace the current one, or leave empty to keep the existing logo.
                                                @else
                                                    Upload your company logo (PNG, JPG, SVG - Max 2MB)
                                                @endif
                                            </div>
                                            @error('company_logo')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Social Media Handles -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="social_facebook" class="form-label">
                                                <i class="bi bi-facebook text-primary me-2"></i>Facebook
                                            </label>
                                            <input type="url" class="form-control @error('social_facebook') is-invalid @enderror" 
                                                   id="social_facebook" name="social_facebook" 
                                                   value="{{ old('social_facebook', $boothOwner->form_responses['social_facebook'] ?? '') }}" 
                                                   placeholder="https://facebook.com/yourcompany">
                                            @error('social_facebook')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="social_twitter" class="form-label">
                                                <i class="bi bi-twitter text-info me-2"></i>Twitter/X
                                            </label>
                                            <input type="url" class="form-control @error('social_twitter') is-invalid @enderror" 
                                                   id="social_twitter" name="social_twitter" 
                                                   value="{{ old('social_twitter', $boothOwner->form_responses['social_twitter'] ?? '') }}" 
                                                   placeholder="https://twitter.com/yourcompany">
                                            @error('social_twitter')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="social_linkedin" class="form-label">
                                                <i class="bi bi-linkedin text-primary me-2"></i>LinkedIn
                                            </label>
                                            <input type="url" class="form-control @error('social_linkedin') is-invalid @enderror" 
                                                   id="social_linkedin" name="social_linkedin" 
                                                   value="{{ old('social_linkedin', $boothOwner->form_responses['social_linkedin'] ?? '') }}" 
                                                   placeholder="https://linkedin.com/company/yourcompany">
                                            @error('social_linkedin')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="social_instagram" class="form-label">
                                                <i class="bi bi-instagram text-danger me-2"></i>Instagram
                                            </label>
                                            <input type="url" class="form-control @error('social_instagram') is-invalid @enderror" 
                                                   id="social_instagram" name="social_instagram" 
                                                   value="{{ old('social_instagram', $boothOwner->form_responses['social_instagram'] ?? '') }}" 
                                                   placeholder="https://instagram.com/yourcompany">
                                            @error('social_instagram')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Email Preference (only show when editing) -->
                                @if($isEditing)
                                <div class="mb-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="resend_email" name="resend_email" value="1">
                                        <label class="form-check-label" for="resend_email">
                                            <i class="bi bi-envelope me-2"></i>
                                            Send me a confirmation email with my updated details
                                        </label>
                                        <div class="form-text text-muted">
                                            Check this box if you'd like to receive a confirmation email with your updated information.
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
