@extends('layouts.public')

@section('title', 'Booking Successful - ' . $event->name)

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Success Header -->
            <div class="text-center mb-5">
                <div class="mb-4">
                    <i class="bi bi-check-circle-fill text-success" style="font-size: 5rem;"></i>
                </div>
                <h1 class="h2 text-success mb-3">🎉 Payment Successful!</h1>
                <p class="lead text-muted">Your booking has been confirmed and payment processed successfully.</p>
                <div class="alert alert-success d-inline-block">
                    <strong>Status:</strong> 
                    <span class="badge bg-success fs-6">{{ ucfirst($booking->status) }}</span>
                </div>
            </div>

            <!-- Booking Summary Card -->
            <div class="card shadow-sm mb-4 border-success">
                <div class="card-header bg-success text-white">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-receipt me-2"></i>Booking Summary
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="fw-bold text-success mb-3">
                                <i class="bi bi-calendar-event me-2"></i>Event Details
                            </h6>
                            <div class="mb-3">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="bi bi-building text-muted me-2"></i>
                                    <strong>Event:</strong>
                                </div>
                                <div class="ms-4">{{ $event->name }}</div>
                            </div>
                            <div class="mb-3">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="bi bi-geo-alt text-muted me-2"></i>
                                    <strong>Space:</strong>
                                </div>
                                <div class="ms-4">
                                    {{ $booking->floorplanItem->label ?? 'N/A' }}
                                    <span class="badge bg-primary ms-2">{{ ucfirst($booking->floorplanItem->type ?? 'booth') }}</span>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="bi bi-people text-muted me-2"></i>
                                    <strong>Team Members:</strong>
                                </div>
                                <div class="ms-4">
                                    {{ count($booking->member_details ?? []) + 1 }} total
                                    <small class="text-muted d-block">(including you)</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6 class="fw-bold text-success mb-3">
                                <i class="bi bi-person-circle me-2"></i>Your Information
                            </h6>
                            <div class="mb-3">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="bi bi-person text-muted me-2"></i>
                                    <strong>Name:</strong>
                                </div>
                                <div class="ms-4">{{ $booking->boothOwner->form_responses['name'] ?? 'N/A' }}</div>
                            </div>
                            <div class="mb-3">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="bi bi-envelope text-muted me-2"></i>
                                    <strong>Email:</strong>
                                </div>
                                <div class="ms-4">{{ $booking->boothOwner->form_responses['email'] ?? 'N/A' }}</div>
                            </div>
                            <div class="mb-3">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="bi bi-telephone text-muted me-2"></i>
                                    <strong>Phone:</strong>
                                </div>
                                <div class="ms-4">{{ $booking->boothOwner->form_responses['phone'] ?? 'N/A' }}</div>
                            </div>
                            <div class="mb-3">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="bi bi-briefcase text-muted me-2"></i>
                                    <strong>Company:</strong>
                                </div>
                                <div class="ms-4">{{ $booking->boothOwner->form_responses['company_name'] ?? 'N/A' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Next Steps Card -->
            <div class="card shadow-sm mb-4 border-info">
                <div class="card-header bg-info text-white">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-list-check me-2"></i>What Happens Next?
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="d-flex align-items-start mb-3">
                                <div class="bg-info bg-opacity-10 rounded-circle p-2 me-3">
                                    <i class="bi bi-envelope text-info"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold">Confirmation Email</h6>
                                    <p class="small text-muted mb-0">You'll receive a detailed confirmation email shortly</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-start mb-3">
                                <div class="bg-info bg-opacity-10 rounded-circle p-2 me-3">
                                    <i class="bi bi-calendar-check text-info"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold">Event Updates</h6>
                                    <p class="small text-muted mb-0">Stay tuned for event details and important updates</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-start mb-3">
                                <div class="bg-info bg-opacity-10 rounded-circle p-2 me-3">
                                    <i class="bi bi-gear text-info"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold">Manage Your Booking</h6>
                                    <p class="small text-muted mb-0">Use your access link to modify details anytime</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-start mb-3">
                                <div class="bg-info bg-opacity-10 rounded-circle p-2 me-3">
                                    <i class="bi bi-people text-info"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold">Team Management</h6>
                                    <p class="small text-muted mb-0">Add or update team members as needed</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Important Information -->
            <div class="alert alert-info">
                <div class="d-flex align-items-start">
                    <i class="bi bi-info-circle-fill me-3 mt-1"></i>
                    <div>
                        <h6 class="alert-heading">Important Information</h6>
                        <ul class="mb-0 small">
                            <li>Keep your access link safe - you'll need it to manage your booking</li>
                            <li>All communications will be sent to: <strong>{{ $booking->owner_details['email'] ?? 'N/A' }}</strong></li>
                            <li>For any questions, contact the event organizers</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Next Step: View Receipt -->
            <div class="text-center">
                <div class="mb-3">
                    <h5 class="text-success">Next Step: Get Your Receipt</h5>
                    <p class="text-muted">Download and print your payment receipt for your records</p>
                </div>
                @if($booking->payments && $booking->payments->where('status', 'completed')->count() > 0)
                    <a href="{{ route('bookings.receipt', ['eventSlug' => $event->slug, 'accessToken' => $booking->access_token]) }}" class="btn btn-success btn-lg">
                        <i class="bi bi-receipt me-2"></i>View & Print Receipt
                    </a>
                @else
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        Receipt will be available once payment is confirmed
                    </div>
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
    
    .card {
        border: 1px solid #000 !important;
        box-shadow: none !important;
    }
}

.bg-opacity-10 {
    background-color: rgba(var(--bs-info-rgb), 0.1) !important;
}
</style>
@endpush
