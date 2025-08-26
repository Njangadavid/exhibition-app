@extends('layouts.public')

@section('title', 'Payment Receipt - ' . $event->name)

@section('content')
<div class="container py-4">
    <div class="receipt-container">
        <!-- Receipt Header -->
        <div class="receipt-header">
            <div class="mb-2">
                <i class="bi bi-check-circle-fill" style="font-size: 2.5rem;"></i>
            </div>
            <h1>Payment Receipt</h1>
            <p>Transaction completed successfully</p>
        </div>

        <!-- Receipt Body -->
        <div class="receipt-body">
            <!-- Receipt Number and Date -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="receipt-number">
                        Receipt #{{ $payment->id }}
                    </div>
                    <small class="text-muted">Generated on {{ now()->format('M j, Y \a\t g:i A') }}</small>
                </div>
                <div class="col-md-6 text-end">
                    <div class="status-badge">
                        <i class="bi bi-check-circle me-1"></i>PAID
                    </div>
                </div>
            </div>

            <!-- Amount Display -->
            <div class="amount-display">
                ${{ number_format($payment->amount, 2) }}
            </div>

            <!-- Transaction Details -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <h5 class="section-title">
                        <i class="bi bi-credit-card me-2"></i>Transaction Details
                    </h5>
                    <div class="info-row">
                        <span class="info-label">Transaction ID:</span>
                        <span class="info-value">{{ $payment->gateway_transaction_id }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Payment Method:</span>
                        <span class="info-value">{{ ucfirst($payment->method) }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Currency:</span>
                        <span class="info-value">{{ strtoupper($payment->currency) }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Date:</span>
                        <span class="info-value">{{ $payment->created_at->format('M j, Y g:i A') }}</span>
                    </div>
                </div>
                <div class="col-md-6">
                    <h5 class="section-title">
                        <i class="bi bi-calendar-event me-2"></i>Event Details
                    </h5>
                    <div class="info-row">
                        <span class="info-label">Event:</span>
                        <span class="info-value">{{ $event->name }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Space:</span>
                        <span class="info-value">{{ $booking->floorplanItem->label ?? 'N/A' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Type:</span>
                        <span class="info-value">{{ ucfirst($booking->floorplanItem->type ?? 'booth') }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Booking ID:</span>
                        <span class="info-value">#{{ $booking->id }}</span>
                    </div>
                </div>
            </div>

            <!-- Customer Information -->
            <div class="row mb-3">
                <div class="col-12">
                    <h5 class="section-title">
                        <i class="bi bi-person-circle me-2"></i>Customer Information
                    </h5>
                    <div class="info-row">
                        <span class="info-label">Name:</span>
                        <span class="info-value">{{ $booking->boothOwner->form_responses['name'] ?? 'N/A' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Email:</span>
                        <span class="info-value">{{ $booking->boothOwner->form_responses['email'] ?? 'N/A' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Phone:</span>
                        <span class="info-value">{{ $booking->boothOwner->form_responses['phone'] ?? 'N/A' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Company:</span>
                        <span class="info-value">{{ $booking->boothOwner->form_responses['company_name'] ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>

            <!-- Paystack Receipt Data (if available) -->
            @if(isset($receipt) && $receipt->status && isset($receipt->data))
            <div class="row mb-3">
                <div class="col-12">
                    <h5 class="section-title">
                        <i class="bi bi-receipt me-2"></i>Paystack Transaction Details
                    </h5>
                    <div class="info-row">
                        <span class="info-label">Transaction Reference:</span>
                        <span class="info-value">{{ $receipt->data->receipt_number ?? 'N/A' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Payment Channel:</span>
                        <span class="info-value">{{ ucfirst($receipt->data->channel ?? 'Card') }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Gateway Response:</span>
                        <span class="info-value">
                            <span class="badge bg-success" style="font-size: 0.75rem;">{{ ucfirst($receipt->data->gateway_response ?? 'Success') }}</span>
                        </span>
                    </div>
                    @if(isset($receipt->data->transaction_date))
                    <div class="info-row">
                        <span class="info-label">Transaction Date:</span>
                        <span class="info-value">{{ \Carbon\Carbon::parse($receipt->data->transaction_date)->format('M j, Y g:i A') }}</span>
                    </div>
                    @endif
                    <div class="alert alert-info mt-2">
                        <div class="d-flex align-items-start">
                            <i class="bi bi-info-circle me-2 mt-1"></i>
                            <div>
                                <small class="fw-bold">Note:</small>
                                <p class="mb-0 small">This receipt is generated from Paystack transaction data. For official Paystack receipts, please check your email or Paystack dashboard.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Additional Paystack Transaction Details (if available) -->
            @if(isset($transactionDetails) && $transactionDetails->status && isset($transactionDetails->data))
            <div class="row mb-3">
                <div class="col-12">
                    <h5 class="section-title">
                        <i class="bi bi-list-ul me-2"></i>Additional Transaction Information
                    </h5>
                    @if(isset($transactionDetails->data->customer))
                    <div class="info-row">
                        <span class="info-label">Customer Email:</span>
                        <span class="info-value">{{ $transactionDetails->data->customer->email ?? 'N/A' }}</span>
                    </div>
                    @endif
                    @if(isset($transactionDetails->data->authorization))
                    <div class="info-row">
                        <span class="info-label">Authorization Code:</span>
                        <span class="info-value">{{ $transactionDetails->data->authorization->authorization_code ?? 'N/A' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Card Type:</span>
                        <span class="info-value">{{ $transactionDetails->data->authorization->card_type ?? 'N/A' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Last 4 Digits:</span>
                        <span class="info-value">{{ $transactionDetails->data->authorization->last4 ?? 'N/A' }}</span>
                    </div>
                    @endif
                    @if(isset($transactionDetails->data->fees))
                    <div class="info-row">
                        <span class="info-label">Transaction Fee:</span>
                        <span class="info-value">{{ number_format(($transactionDetails->data->fees / 100), 2) }} {{ strtoupper($transactionDetails->data->currency ?? 'NGN') }}</span>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Terms and Notes -->
            <div class="row">
                <div class="col-12">
                    <div class="alert alert-light">
                        <h6 class="fw-bold">Important Notes:</h6>
                        <ul class="mb-0 small">
                            <li>This receipt serves as proof of payment for your booking</li>
                            <li>Keep this receipt for your records</li>
                            <li>For any questions, contact the event organizers</li>
                            <li>Receipt generated automatically by Paystack</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Receipt Footer -->
        <div class="receipt-footer">
            <div class="row">
                <div class="col-md-6 text-start">
                    <small class="footer-text">
                        <strong>Generated by:</strong> {{ config('app.name') }}
                    </small>
                </div>
                <div class="col-md-6 text-end">
                    <small class="footer-text">
                        <strong>Powered by:</strong> Paystack
                    </small>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="text-center mt-4">
        <button onclick="window.print()" class="btn btn-success btn-lg me-3">
            <i class="bi bi-printer me-2"></i>Print Receipt
        </button>
        
        <button onclick="resendPaymentEmail()" class="btn btn-primary btn-lg me-3">
            <i class="bi bi-envelope me-2"></i>Resend Payment Email
        </button>
         
         <a href="{{ route('bookings.owner-form-token', ['eventSlug' => $event->slug, 'accessToken' => $booking->boothOwner->access_token]) }}"
             class="btn btn-outline-secondary btn-lg">
              <i class="bi bi-pencil-square me-2"></i>Edit Details
          </a>
               
    </div>
</div>
@endsection

@push('scripts')
<script>
function resendPaymentEmail() {
    if (confirm('Are you sure you want to resend the payment confirmation email? This will send the email again with the receipt attached.')) {
        // Show loading state
        const button = event.target;
        const originalText = button.innerHTML;
        button.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Sending...';
        button.disabled = true;
        
        // Make AJAX request to resend payment email
        fetch('{{ route("bookings.resend-payment-email", ["eventSlug" => $event->slug, "accessToken" => $booking->boothOwner->access_token]) }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Payment confirmation email has been resent successfully!');
            } else {
                alert('Failed to resend email: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to resend email. Please try again.');
        })
        .finally(() => {
            // Restore button state
            button.innerHTML = originalText;
            button.disabled = false;
        });
    }
}
</script>
@endpush

@push('styles')
<style>
    @media print {
        .no-print { display: none !important; }
        body { margin: 0; padding: 15px; }
        .receipt-container { box-shadow: none; border: 2px solid #000; }
        .receipt-header { background: #28a745 !important; -webkit-print-color-adjust: exact; }
        .receipt-footer { background: #f8f9fa !important; -webkit-print-color-adjust: exact; }
    }
    
    .receipt-container {
        max-width: 700px;
        margin: 0 auto;
        background: white;
        box-shadow: 0 0 20px rgba(0,0,0,0.1);
        border-radius: 8px;
        overflow: hidden;
        font-size: 14px;
    }
    
    .receipt-header {
        background: linear-gradient(135deg, #28a745, #20c997);
        color: white;
        padding: 25px;
        text-align: center;
    }
    
    .receipt-header h1 {
        font-size: 1.5rem;
        margin-bottom: 8px;
    }
    
    .receipt-header p {
        font-size: 0.95rem;
        margin-bottom: 0;
    }
    
    .receipt-body {
        padding: 30px;
    }
    
    .receipt-footer {
        background: #f8f9fa;
        padding: 15px;
        text-align: center;
        border-top: 1px solid #dee2e6;
    }
    
    .receipt-number {
        font-size: 1rem;
        font-weight: bold;
        color: #28a745;
    }
    
    .amount-display {
        font-size: 2rem;
        font-weight: bold;
        color: #28a745;
        text-align: center;
        margin: 15px 0;
    }
    
    .info-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 12px;
        padding: 8px 0;
        border-bottom: 1px solid #f1f3f4;
        font-size: 0.9rem;
    }
    
    .info-row:last-child {
        border-bottom: none;
    }
    
    .info-label {
        font-weight: 600;
        color: #495057;
    }
    
    .info-value {
        color: #212529;
        text-align: right;
    }
    
    .status-badge {
        background: #28a745;
        color: white;
        padding: 6px 12px;
        border-radius: 15px;
        font-size: 0.8rem;
        font-weight: 600;
    }
    
    .section-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #28a745;
        margin-bottom: 15px;
        padding-bottom: 8px;
        border-bottom: 2px solid #e9ecef;
    }
    
    .alert {
        font-size: 0.85rem;
        padding: 12px;
    }
    
    .alert h6 {
        font-size: 0.9rem;
        margin-bottom: 8px;
    }
    
    .alert ul {
        margin-bottom: 0;
    }
    
    .alert li {
        margin-bottom: 4px;
    }
    
    .qr-code {
        text-align: center;
        margin: 15px 0;
    }
    
    .qr-code img {
        max-width: 120px;
        height: auto;
    }
    
    .footer-text {
        font-size: 0.8rem;
        color: #6c757d;
    }
    
    .btn {
        font-size: 0.9rem;
        padding: 8px 16px;
    }
    
    .btn-lg {
        font-size: 1rem;
        padding: 10px 20px;
    }
</style>
@endpush
