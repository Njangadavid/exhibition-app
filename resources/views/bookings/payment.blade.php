@extends('layouts.public')

@section('title', 'Payment - ' . $event->name)
@php
    $showProgress = true;
    $currentStep = 4;
@endphp
@section('content')
<div class="container-fluid p-0">
    <!-- Progress Steps -->
     

    <!-- Main Content -->
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- Header -->
                <div class="text-center mb-4">
                    <h2 class="h3 mb-2">Complete Your Booking</h2>
                    <p class="text-muted">Review your details and proceed with payment</p>
                </div>

                <!-- Booking Summary Card -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-receipt me-2"></i>Booking Summary
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- Space Details -->
                            <div class="col-md-6">
                                <h6 class="fw-bold text-primary mb-3">Space Details</h6>
                                <div class="mb-2">
                                    <strong>Event:</strong> {{ $event->name }}
                                </div>
                                <div class="mb-2">
                                    <strong>Space:</strong> {{ $booking->floorplanItem->label ?? 'N/A' }}
                                </div>
                                <div class="mb-2">
                                    <strong>Type:</strong> {{ ucfirst($booking->floorplanItem->type ?? 'booth') }}
                                </div>
                                <div class="mb-2">
                                    <strong>Capacity:</strong> {{ $booking->floorplanItem->max_capacity ?? 5 }} members
                                </div>
                                <div class="mb-2">
                                    <strong>Members Registered:</strong> {{ count($booking->member_details ?? []) }}
                                </div>
                            </div>

                            <!-- Owner Details -->
                            <div class="col-md-6">
                                <h6 class="fw-bold text-primary mb-3">Owner Details</h6>
                                <div class="mb-2">
                                    <strong>Name:</strong> {{ $booking->owner_details['name'] ?? 'N/A' }}
                                </div>
                                <div class="mb-2">
                                    <strong>Email:</strong> {{ $booking->owner_details['email'] ?? 'N/A' }}
                                </div>
                                <div class="mb-2">
                                    <strong>Phone:</strong> {{ $booking->owner_details['phone'] ?? 'N/A' }}
                                </div>
                                <div class="mb-2">
                                    <strong>Company:</strong> {{ $booking->owner_details['company_name'] ?? 'N/A' }}
                                </div>
                            </div>
                        </div>

                        <!-- Price Breakdown -->
                        <hr class="my-4">
                        <div class="row">
                            <div class="col-12">
                                <h6 class="fw-bold text-primary mb-3">Price Breakdown</h6>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span>Space Rental ({{ $booking->floorplanItem->label ?? 'N/A' }})</span>
                                    <span>${{ number_format($booking->floorplanItem->price ?? 0, 2) }}</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span>Members ({{ count($booking->member_details ?? []) }} registered)</span>
                                    <span class="text-success">Included</span>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between align-items-center">
                                    <strong class="fs-5">Total Amount</strong>
                                    <strong class="fs-4 text-primary">${{ number_format($booking->floorplanItem->price ?? 0, 2) }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Action -->
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-credit-card me-2"></i>Complete Your Payment
                        </h5>
                    </div>
                    <div class="card-body text-center">
                        <div class="mb-4">
                            <i class="bi bi-shield-check text-success" style="font-size: 4rem;"></i>
                            <h4 class="mt-3 mb-2">Ready to Complete Your Booking?</h4>
                            <p class="text-muted">Your booking details have been saved. Click the button below to proceed with payment.</p>
                        </div>

                        <!-- Payment Method Info -->
                        @php
                            $defaultPaymentMethod = $event->paymentMethods->where('is_active', true)->where('is_default', true)->first();
                        @endphp
                        
                        @if($defaultPaymentMethod)
                            <div class="mb-4">
                                 <div class="row justify-content-center">
                                    <div class="col-md-6">
                                        <div class="card payment-method-display">
                                            <div class="card-body text-center">
                                                @if($defaultPaymentMethod->icon)
                                                    <i class="{{ $defaultPaymentMethod->icon }} fs-1" style="color: {{ $defaultPaymentMethod->color ?? '#0d6efd' }}"></i>
                                                @else
                                                    <i class="bi bi-credit-card fs-1" style="color: {{ $defaultPaymentMethod->color ?? '#0d6efd' }}"></i>
                                                @endif
                                                <h6 class="card-title mt-2">{{ $defaultPaymentMethod->name }}</h6>
                                                <p class="card-text text-muted small">{{ $defaultPaymentMethod->description ?? 'Secure payment processing' }}</p>
                                                <a href="{{ route('bookings.process-payment', ['eventSlug' => $event->slug, 'accessToken' => $booking->access_token]) }}" class="btn btn-success btn-sm mt-2">
                                                    <i class="bi bi-credit-card me-1"></i>Payment Now ${{ number_format($booking->floorplanItem->price ?? 0, 2) }}
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="mb-4">
                                <div class="alert alert-warning">
                                    <i class="bi bi-exclamation-triangle me-2"></i>
                                    No default payment method is currently available. Please contact the event organizer.
                                </div>
                            </div>
                        @endif

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-center">
                            <a href="{{ route('bookings.member-form', ['eventSlug' => $event->slug, 'accessToken' => $booking->access_token]) }}" class="btn btn-outline-secondary btn-lg">
                                <i class="bi bi-arrow-left me-2"></i>Back to Members
                            </a>
                        </div>

                        <!-- Security Notice -->
                        <div class="mt-4">
                            <p class="text-muted small mb-0">
                                <i class="bi bi-shield-lock me-1"></i>
                                Your payment information is secure and encrypted. We use industry-standard security measures to protect your data.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.breadcrumb-item a:hover {
    color: #0d6efd !important;
}

.card {
    border: none;
    border-radius: 10px;
}

.card-header {
    border-radius: 10px 10px 0 0 !important;
}

.btn-lg {
    padding: 0.75rem 1.5rem;
    font-size: 1.1rem;
}

.payment-method-card {
    cursor: pointer;
    transition: all 0.3s ease;
    border: 2px solid #e9ecef;
}

.payment-method-card:hover {
    border-color: #0d6efd;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

.payment-method-card.active {
    border-color: #0d6efd;
    background-color: #f8f9fa;
}

.payment-method-card .form-check-input {
    transform: scale(1.2);
}

.payment-method-display {
    border: 2px solid #e9ecef;
    transition: all 0.3s ease;
}

.payment-method-display:hover {
    border-color: #0d6efd;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

.payment-method-display .badge {
    font-size: 0.75rem;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Payment method selection
    const paymentCards = document.querySelectorAll('.payment-method-card');
    const paymentSections = document.querySelectorAll('.payment-section');
    
    paymentCards.forEach(card => {
        card.addEventListener('click', function() {
            // Remove active class from all cards
            paymentCards.forEach(c => c.classList.remove('active'));
            
            // Add active class to clicked card
            this.classList.add('active');
            
            // Check the radio button
            const radio = this.querySelector('input[type="radio"]');
            radio.checked = true;
            
            // Show/hide relevant payment sections
            const selectedMethod = radio.value;
            paymentSections.forEach(section => {
                if (section.dataset.method === selectedMethod) {
                    section.style.display = 'block';
                } else {
                    section.style.display = 'none';
                }
            });
        });
    });

    // Card number formatting
    const cardNumberInput = document.getElementById('card_number');
    if (cardNumberInput) {
        cardNumberInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\s/g, '').replace(/[^0-9]/gi, '');
            let formattedValue = value.match(/.{1,4}/g)?.join(' ') || value;
            this.value = formattedValue;
        });
    }

    // Expiry date formatting
    const expiryInput = document.getElementById('expiry_date');
    if (expiryInput) {
        expiryInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length >= 2) {
                value = value.substring(0, 2) + '/' + value.substring(2, 4);
            }
            this.value = value;
        });
    }

    // CVV validation
    const cvvInput = document.getElementById('cvv');
    if (cvvInput) {
        cvvInput.addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    }

    // Form submission
    const paymentForm = document.getElementById('paymentForm');
    if (paymentForm) {
        paymentForm.addEventListener('submit', function(e) {
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            
            submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Processing...';
            submitBtn.disabled = true;
            
            // Re-enable button after 10 seconds as fallback
            setTimeout(() => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }, 10000);
        });
    }

    // Initialize first payment method as active
    const firstPaymentCard = document.querySelector('.payment-method-card');
    if (firstPaymentCard) {
        firstPaymentCard.classList.add('active');
    }
});
</script>
@endpush
