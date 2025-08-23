<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Services\PaystackService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class PaystackController extends Controller
{
    protected $paystackService;

    public function __construct(PaystackService $paystackService)
    {
        $this->paystackService = $paystackService;
    }

    /**
     * Initialize Paystack payment
     */
    public function initializePayment(Request $request, $eventSlug, $accessToken)
    {
        // Find the event
        $event = Event::where('slug', $eventSlug)->firstOrFail();

        // Find the booking by access token
        $booking = Booking::with(['floorplanItem'])
            ->where('access_token', $accessToken)
            ->where('access_token_expires_at', '>', now())
            ->firstOrFail();

        // Check if access token is valid
        if (!$booking->isAccessTokenValid()) {
            return redirect()->route('events.public.floorplan', $eventSlug)
                ->with('error', 'Access token has expired or is invalid.');
        }

        // Get the default payment method for this event
        $paymentMethod = PaymentMethod::where('event_id', $event->id)
            ->where('is_active', true)
            ->where('is_default', true)
            ->first();

        if (!$paymentMethod) {
            return back()->with('error', 'No default payment method is configured for this event. Please contact support.');
        }

        // Check if Paystack is configured with this payment method
        if (!$this->paystackService->isConfiguredWithPaymentMethod($paymentMethod)) {
            Log::error('Paystack configuration check failed', [
                'payment_method_id' => $paymentMethod->id,
                'payment_method_name' => $paymentMethod->name,
                'has_secret_key' => !empty($paymentMethod->getSecretKey()),
                'has_public_key' => !empty($paymentMethod->getPublicKey()),
                'config' => $paymentMethod->config
            ]);
            return back()->with('error', 'Paystack payment is not properly configured for this payment method. Please contact support.');
        }

        // Initialize Paystack with the payment method configuration
        $this->paystackService->initializeWithPaymentMethod($paymentMethod);

        try {
            // Use accessors for payment calculations
            $balance = $booking->balance;

            // Check if there's a balance to pay
            if ($balance <= 0) {
                return redirect()->route('bookings.payment', ['eventSlug' => $eventSlug, 'accessToken' => $accessToken])
                    ->with('info', 'No balance due. Your booking is fully paid.');
            }

            // Generate unique reference
            $reference = 'BK_' . $booking->id . '_' . time();

            // Initialize Paystack transaction with balance amount
            $transactionData = [
                'amount' => $balance * 100, // Convert to kobo (Paystack expects amount in smallest currency unit)
                'email' => $booking->owner_details['email'],
                'reference' => $reference,
                'callback_url' => route('paystack.callback', ['eventSlug' => $event->slug, 'accessToken' => $booking->access_token]),
                'webhook_url' => url('/api/webhooks/paystack'), // Use full URL for webhook
                'currency' => $paymentMethod->getConfig('currency', 'USD'),
                'metadata' => [
                    'booking_id' => $booking->id,
                    'event_id' => $event->id,
                    'space_label' => $booking->floorplanItem->label,
                    'owner_name' => $booking->owner_details['name'],
                    'balance_paid' => $balance,
                    'total_amount' => $booking->floorplanItem->price ?? 0,
                    'previous_payments' => $booking->total_paid,
                ],
            ];

            // Log the transaction data being sent to Paystack
            Log::info('Paystack transaction data', [
                'transaction_data' => $transactionData,
                'payment_method_config' => $paymentMethod->config
            ]);

            $transaction = $this->paystackService->initializeTransaction($transactionData);

            if ($transaction->status) {
                // Log the full Paystack response
                Log::info('Paystack response received', [
                    'transaction' => $transaction,
                    'status' => $transaction->status,
                    'message' => $transaction->message ?? 'No message'
                ]);

                // Store payment record
                $payment = new Payment();
                $payment->booking_id = $booking->id;
                
                // Generate and log payment reference
                $paymentRef = Payment::generateReference();
                Log::info('Generated payment reference', ['reference' => $paymentRef]);
                $payment->payment_reference = $paymentRef;
                
                $payment->amount = $balance; // Use balance amount instead of full price
                $payment->currency = $paymentMethod->getConfig('currency', 'USD');
                $payment->method = $paymentMethod->name;
                $payment->gateway = $paymentMethod->type ?? 'paystack';
                $payment->gateway_transaction_id = $reference;
                $payment->status = 'pending';
                $payment->gateway_response = json_encode($transaction);
                
                // Log the payment object before saving
                Log::info('Payment object before save', [
                    'payment_reference' => $payment->payment_reference,
                    'amount' => $payment->amount,
                    'currency' => $payment->currency,
                    'method' => $payment->method,
                    'gateway' => $payment->gateway,
                    'status' => $payment->status
                ]);
                
                $payment->save();

                // Update booking status to reserved
                $booking->status = 'reserved';
                $booking->save();

                Log::info('Paystack transaction initialized', [
                    'booking_id' => $booking->id,
                    'reference' => $reference,
                    'authorization_url' => $transaction->data->authorization_url
                ]);


                // Redirect to Paystack payment page (when live)
                return redirect($transaction->data->authorization_url);
            } else {
                throw new \Exception('Failed to initialize Paystack transaction: ' . $transaction->message);
            }
        } catch (\Exception $e) {
            Log::error('Paystack payment initialization failed', [
                'booking_id' => $booking->id,
                'error' => $e->getMessage()
            ]);

            return back()->with('error', 'Failed to initialize payment. Please try again.');
        }
    }

    /**
     * Handle Paystack callback
     */
    public function handleCallback(Request $request, $eventSlug, $accessToken)
    {
        // Find the event
        $event = Event::where('slug', $eventSlug)->firstOrFail();

        // Find the booking by access token
        $booking = Booking::with(['floorplanItem'])
            ->where('access_token', $accessToken)
            ->where('access_token_expires_at', '>', now())
            ->firstOrFail();

        // Check if access token is valid
        if (!$booking->isAccessTokenValid()) {
            return redirect()->route('events.public.floorplan', $eventSlug)
                ->with('error', 'Access token has expired or is invalid.');
        }

        try {
            // Get the default payment method for this event to configure the service
            $paymentMethod = PaymentMethod::where('event_id', $event->id)
                ->where('is_active', true)
                ->where('is_default', true)
                ->first();

            if (!$paymentMethod) {
                throw new \Exception('No default payment method is configured for this event.');
            }

            // Check if Paystack is properly configured with this payment method
            if (!$this->paystackService->isConfiguredWithPaymentMethod($paymentMethod)) {
                Log::error('Paystack configuration check failed in callback', [
                    'payment_method_id' => $paymentMethod->id,
                    'payment_method_name' => $paymentMethod->name,
                    'has_secret_key' => !empty($paymentMethod->getSecretKey()),
                    'has_public_key' => !empty($paymentMethod->getPublicKey()),
                    'config' => $paymentMethod->config
                ]);
                throw new \Exception('Paystack payment is not properly configured for this payment method.');
            }

            // Initialize Paystack with the payment method configuration
            $this->paystackService->initializeWithPaymentMethod($paymentMethod);

            // Verify the transaction
            $reference = $request->query('reference');
            if (!$reference) {
                throw new \Exception('No reference provided in callback');
            }

            // Log the verification attempt
            Log::info('Attempting to verify Paystack transaction', [
                'booking_id' => $booking->id,
                'reference' => $reference,
                'payment_method_id' => $paymentMethod->id
            ]);

            $transaction = $this->paystackService->verifyTransaction($reference);

            if ($transaction->status && $transaction->data->status === 'success') {
                // Payment successful
                $payment = Payment::where('gateway_transaction_id', $reference)->first();

                if ($payment) {
                    $payment->status = 'completed';
                    $payment->gateway_response = json_encode($transaction);
                    $payment->save();
                }

                // Update booking status
                $booking->status = 'booked';
                $booking->save();

                Log::info('Paystack payment successful', [
                    'booking_id' => $booking->id,
                    'reference' => $reference,
                    'amount' => $transaction->data->amount / 100
                ]);

                return redirect()->route('bookings.success', ['eventSlug' => $eventSlug, 'accessToken' => $booking->access_token])
                    ->with('success', 'Payment successful! Your booking has been confirmed.');
            } else {
                // Payment failed
                $payment = Payment::where('gateway_transaction_id', $reference)->first();

                if ($payment) {
                    $payment->status = 'failed';
                    $payment->gateway_response = json_encode($transaction);
                    $payment->save();
                }

                Log::warning('Paystack payment failed', [
                    'booking_id' => $booking->id,
                    'reference' => $reference,
                    'response' => $transaction
                ]);

                return redirect()->route('bookings.payment', ['eventSlug' => $eventSlug, 'accessToken' => $booking->access_token])
                    ->with('error', 'Payment failed. Please try again.');
            }
        } catch (\Exception $e) {
            Log::error('Paystack callback processing failed', [
                'booking_id' => $booking->id,
                'error' => $e->getMessage(),
                'request_data' => $request->all(),
                'event_id' => $event->id,
                'event_slug' => $eventSlug,
                'access_token' => $accessToken,
                'payment_method_found' => isset($paymentMethod) ? $paymentMethod->id : 'not_checked',
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('bookings.payment', ['eventSlug' => $eventSlug, 'accessToken' => $booking->access_token])
                ->with('error', 'Payment verification failed. Please contact support.');
        }
    }

    /**
     * Show payment status
     */
    public function showPaymentStatus(Request $request, $eventSlug, $accessToken)
    {
        // Find the event
        $event = Event::where('slug', $eventSlug)->firstOrFail();

        // Find the booking by access token
        $booking = Booking::with(['floorplanItem', 'payments'])
            ->where('access_token', $accessToken)
            ->where('access_token_expires_at', '>', now())
            ->firstOrFail();

        // Check if access token is valid
        if (!$booking->isAccessTokenValid()) {
            return redirect()->route('events.public.floorplan', $eventSlug)
                ->with('error', 'Access token has expired or is invalid.');
        }

        return view('bookings.payment-status', compact('event', 'booking'));
    }

    /**
     * Generate Paystack receipt for printing
     */
    public function generateReceipt(Request $request, $eventSlug, $accessToken)
    {
        try {
            // Find the event
            $event = Event::where('slug', $eventSlug)->firstOrFail();

            // Find the booking by access token
            $booking = Booking::with(['floorplanItem', 'payments'])
                ->where('access_token', $accessToken)
                ->where('access_token_expires_at', '>', now())
                ->firstOrFail();

            // Check if access token is valid
            if (!$booking->isAccessTokenValid()) {
                return redirect()->route('events.public.floorplan', $eventSlug)
                    ->with('error', 'Access token has expired or is invalid.');
            }

            // Get the default payment method for this event
            $paymentMethod = PaymentMethod::where('event_id', $event->id)
                ->where('is_active', true)
                ->where('is_default', true)
                ->first();

            if (!$paymentMethod) {
                throw new \Exception('No default payment method is configured for this event.');
            }

            // Initialize Paystack with the payment method configuration
            $this->paystackService->initializeWithPaymentMethod($paymentMethod);

            // Get the latest completed payment
            $payment = $booking->payments()
                ->where('status', 'completed')
                ->latest()
                ->first();

            if (!$payment) {
                throw new \Exception('No completed payment found for this booking.');
            }

            // Generate receipt from Paystack
            $receipt = $this->paystackService->generateReceipt(
                $payment->gateway_transaction_id,
                $booking->owner_details['email'] ?? null
            );

            if ($receipt->status && isset($receipt->data)) {
                // Also get detailed transaction information for additional context
                try {
                    $transactionDetails = $this->paystackService->getTransactionDetails($payment->gateway_transaction_id);
                } catch (\Exception $e) {
                    Log::warning('Could not retrieve additional transaction details', [
                        'reference' => $payment->gateway_transaction_id,
                        'error' => $e->getMessage()
                    ]);
                    $transactionDetails = null;
                }

                // Set current step to 5 (Receipt) and show progress
                $currentStep = 5;
                $showProgress = true;

                // Return the receipt data for display/printing
                return view('bookings.receipt', compact('event', 'booking', 'payment', 'receipt', 'transactionDetails', 'currentStep', 'showProgress'));
            } else {
                throw new \Exception('Failed to generate receipt from Paystack.');
            }

        } catch (\Exception $e) {
            Log::error('Receipt generation failed', [
                'event_slug' => $eventSlug,
                'access_token' => $accessToken,
                'error' => $e->getMessage()
            ]);

            return redirect()->route('bookings.payment', ['eventSlug' => $eventSlug, 'accessToken' => $accessToken])
                ->with('error', 'Failed to generate receipt. Please try again.');
        }
    }
}
