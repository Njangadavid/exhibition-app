@extends('layouts.public')

@section('title', 'Booking Successful - ' . $event->name)

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Success Header -->
            <div class="text-center mb-5">
                @if($booking->status === 'confirmed')
                    <div class="mb-4">
                        <i class="bi bi-check-circle-fill text-success" style="font-size: 4rem;"></i>
                    </div>
                    <h1 class="h2 text-success mb-3">Booking Confirmed!</h1>
                    <p class="lead text-muted">Your payment has been processed successfully and your booking is confirmed.</p>
                @else
                    <div class="mb-4">
                        <i class="bi bi-clock-fill text-warning" style="font-size: 4rem;"></i>
                    </div>
                    <h1 class="h2 text-warning mb-3">Booking Pending</h1>
                    <p class="lead text-muted">Your booking has been created. Please complete the bank transfer to confirm your reservation.</p>
                @endif
            </div>

            <!-- Booking Details Card -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-receipt me-2"></i>Booking Details
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="fw-bold text-primary mb-3">Event & Space</h6>
                            <div class="mb-2">
                                <strong>Booking ID:</strong> #{{ $booking->id }}
                            </div>
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
                                <strong>Members:</strong> {{ count($booking->member_details ?? []) }} registered
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6 class="fw-bold text-primary mb-3">Contact Information</h6>
                            <div class="mb-2">
                                <strong>Owner:</strong> {{ $booking->owner_name }}
                            </div>
                            <div class="mb-2">
                                <strong>Email:</strong> {{ $booking->owner_email }}
                            </div>
                            <div class="mb-2">
                                <strong>Phone:</strong> {{ $booking->owner_phone }}
                            </div>
                            <div class="mb-2">
                                <strong>Company:</strong> {{ $booking->company_name }}
                            </div>
                        </div>
                    </div>

                    <!-- Payment Information -->
                    @if($booking->payments && $booking->payments->count() > 0)
                        <hr class="my-4">
                        <h6 class="fw-bold text-primary mb-3">Payment Information</h6>
                        @foreach($booking->payments as $payment)
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span>Payment #{{ $payment->id }}</span>
                                <span class="badge bg-{{ $payment->status === 'completed' ? 'success' : ($payment->status === 'pending' ? 'warning' : 'danger') }}">
                                    {{ ucfirst($payment->status) }}
                                </span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span>Amount</span>
                                <span class="fw-bold">${{ number_format($payment->amount, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span>Method</span>
                                <span>{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</span>
                            </div>
                            @if($payment->gateway_transaction_id)
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span>Transaction ID</span>
                                    <span class="font-monospace small">{{ $payment->gateway_transaction_id }}</span>
                                </div>
                            @endif
                        @endforeach
                    @endif
                </div>
            </div>

            <!-- Next Steps -->
            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-list-check me-2"></i>What's Next?
                    </h5>
                </div>
                <div class="card-body">
                    @if($booking->status === 'confirmed')
                        <div class="alert alert-success">
                            <h6 class="alert-heading">Your booking is confirmed!</h6>
                            <hr>
                            <ul class="mb-0">
                                <li>You will receive a confirmation email shortly with your booking details</li>
                                <li>Login credentials will be sent to {{ $booking->owner_email }}</li>
                                <li>You can manage your booking and add more members through your dashboard</li>
                                <li>Event details and updates will be sent to your registered email</li>
                            </ul>
                        </div>
                    @else
                        <div class="alert alert-warning">
                            <h6 class="alert-heading">Complete your payment</h6>
                            <p>To confirm your booking, please complete the bank transfer:</p>
                            <hr>
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>Bank Name:</strong> Exhibition Bank<br>
                                    <strong>Account Name:</strong> {{ $event->name }}<br>
                                    <strong>Account Number:</strong> 1234567890
                                </div>
                                <div class="col-md-6">
                                    <strong>Sort Code:</strong> 12-34-56<br>
                                    <strong>Reference:</strong> BOOKING-{{ $booking->id }}<br>
                                    <strong>Amount:</strong> ${{ number_format($booking->floorplanItem->price ?? 0, 2) }}
                                </div>
                            </div>
                            <hr class="mb-2">
                            <p class="mb-0 small">Your booking will be confirmed once payment is verified (usually within 24 hours).</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="text-center">
                <a href="{{ route('events.public.floorplan-token', ['event' => $event->slug, 'accessToken' => $booking->access_token]) }}" class="btn btn-outline-primary me-3">
                    <i class="bi bi-arrow-left me-2"></i>Back to Event
                </a>
                @if($booking->status === 'confirmed')
                    <button type="button" class="btn btn-success" onclick="window.print()">
                        <i class="bi bi-printer me-2"></i>Print Confirmation
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
@media print {
    .btn, nav, footer {
        display: none !important;
    }
    
    .container {
        max-width: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
    }
}
</style>
@endpush
