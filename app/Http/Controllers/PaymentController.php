<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Services\PaystackService;
use App\Services\PesapalService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    /**
     * Process payment for booking.
     */
    public function processPayment(Request $request, $eventSlug, $accessToken)
    {
        $event = Event::with('paymentMethods')->where('slug', $eventSlug)->firstOrFail();

        // Find booth owner by access token, then get the booking
        $boothOwner = \App\Models\BoothOwner::where('access_token', $accessToken)->firstOrFail();
        $booking = $boothOwner->booking;

        if (!$booking) {
            return redirect()->route('events.public.floorplan', $eventSlug)
                ->with('error', 'No booking found for this access token. Please start over.');
        }

        // Verify access token is valid
        if (!$booking->isAccessTokenValid()) {
            return redirect()->route('events.public.floorplan', $eventSlug)
                ->with('error', 'Invalid or expired access link. Please start over.');
        }

        try {
            // Get the default payment method for this event
            $paymentMethod = $event->paymentMethods->where('is_active', true)->where('is_default', true)->first();

            if (!$paymentMethod) {
                return back()->with('error', 'No payment method available for this event.');
            }

            // Route to appropriate payment processor based on gateway
            $gateway = $paymentMethod->gateway ?: $this->detectGateway($paymentMethod);

            Log::info('Payment routing debug', [
                'payment_method_id' => $paymentMethod->id,
                'payment_method_name' => $paymentMethod->name,
                'payment_method_type' => $paymentMethod->type,
                'payment_method_gateway' => $paymentMethod->gateway,
                'detected_gateway' => $gateway,
                'is_paystack' => $paymentMethod->isPaystack(),
                'is_pesapal' => $paymentMethod->isPesapal(),
                'public_key' => $paymentMethod->getConfig('public_key'),
                'secret_key' => $paymentMethod->getConfig('secret_key'),
                'public_key_starts_with_pk' => str_starts_with($paymentMethod->getConfig('public_key'), 'pk_'),
                'config' => $paymentMethod->config
            ]);

            switch ($gateway) {
                case 'paystack':
                    return $this->processPaystackPayment($request, $event, $booking, $paymentMethod);

                case 'pesapal':
                    return $this->processPesapalPayment($request, $event, $booking, $paymentMethod);

                case 'manual':
                default:
                    return $this->processLegacyPayment($request, $event, $booking);
            }
        } catch (\Exception $e) {
            Log::error('Payment processing failed', [
                'booking_id' => $booking->id,
                'error' => $e->getMessage(),
                'event_slug' => $eventSlug
            ]);

            return back()->with('error', 'Payment processing failed. Please try again.');
        }
    }

    /**
     * Detect payment gateway based on payment method configuration
     */
    private function detectGateway($paymentMethod)
    {
        // If gateway is explicitly set, use it
        if ($paymentMethod->gateway) {
            Log::info('Using explicit gateway', ['gateway' => $paymentMethod->gateway]);
            return $paymentMethod->gateway;
        }

        // Auto-detect based on configuration
        if ($paymentMethod->isPaystack()) {
            Log::info('Detected as Paystack');
            return 'paystack';
        } elseif ($paymentMethod->isPesapal()) {
            Log::info('Detected as Pesapal');
            return 'pesapal';
        } elseif ($paymentMethod->isBankTransfer()) {
            Log::info('Detected as Manual/Bank Transfer');
            return 'manual';
        }

        // Default fallback - if we have public_key/secret_key but not Paystack, assume Pesapal
        if ($paymentMethod->getConfig('public_key') && $paymentMethod->getConfig('secret_key')) {
            Log::info('Fallback: Detected as Pesapal based on public_key/secret_key');
            return 'pesapal';
        }

        Log::info('Default fallback: Manual');
        return 'manual';
    }

    /**
     * Process Paystack payment
     */
    private function processPaystackPayment(Request $request, Event $event, Booking $booking, PaymentMethod $paymentMethod)
    {
        try {
            $paystackService = new PaystackService();
            $paystackService->initializeWithPaymentMethod($paymentMethod);

            // Prepare payment data
            $paymentData = [
                'booth_owner_id' => $booking->boothOwner->id,
                'booth_owner_form_responses' => $booking->boothOwner->form_responses,
                'booking_id' => $booking->id,
                'floorplan_item' => $booking->floorplanItem,
                'access_token' => $booking->boothOwner->access_token,
            ];

            Log::info('Paystack payment initialization data', $paymentData);

            // Initialize Paystack transaction
            $transaction = $paystackService->initializeTransaction([
                'amount' => $booking->balance * 100, // Convert to kobo
                'email' => $booking->boothOwner->form_responses['email'] ?? 'test@example.com',
                'reference' => 'BOOK_' . $booking->id . '_' . time(),
                'callback_url' => route('payments.paystack.callback', [
                    'eventSlug' => $event->slug,
                    'accessToken' => $booking->boothOwner->access_token
                ]),
                'metadata' => [
                    'booking_id' => $booking->id,
                    'booth_owner_id' => $booking->boothOwner->id,
                    'event_id' => $event->id,
                ]
            ]);

            if ($transaction && isset($transaction['data']['authorization_url'])) {
                // Store payment record
                Payment::create([
                    'booking_id' => $booking->id,
                    'payment_method_id' => $paymentMethod->id,
                    'event_id' => $event->id,
                    'amount' => $booking->balance,
                    'currency' => $paymentMethod->getConfig('currency', 'NGN'),
                    'status' => 'pending',
                    'gateway' => 'paystack',
                    'gateway_reference' => $transaction['data']['reference'],
                    'gateway_response' => $transaction,
                ]);

                return redirect($transaction['data']['authorization_url']);
            } else {
                Log::error('Paystack transaction initialization failed', [
                    'response' => $transaction,
                    'booking_id' => $booking->id
                ]);

                return back()->with('error', 'Failed to initialize payment. Please try again.');
            }
        } catch (\Exception $e) {
            Log::error('Paystack payment processing failed', [
                'booking_id' => $booking->id,
                'error' => $e->getMessage()
            ]);

            return back()->with('error', 'Payment processing failed. Please try again.');
        }
    }

    /**
     * Process Pesapal payment
     */
    private function processPesapalPayment(Request $request, Event $event, Booking $booking, PaymentMethod $paymentMethod)
    {
        try {
            $pesapalService = new PesapalService();
            $pesapalService->initializeWithPaymentMethod($paymentMethod);

            // Prepare payment data
            $paymentData = [
                'amount' => $booking->balance, // Pesapal uses the actual amount, not converted to smallest unit
                'currency' => $paymentMethod->getConfig('currency', 'KES'),
                'description' => "Payment for {$event->name} - Booth {$booking->floorplanItem->label}",
                'reference' => 'BOOK_' . $booking->id . '_' . time(),
                'email' => $booking->boothOwner->form_responses['email'] ?? 'test@example.com',
                'phone_number' => $booking->boothOwner->form_responses['phone'] ?? '',
                'event_slug' => $event->slug,
                'callback_url' => route('payments.pesapal.callback', [
                    'eventSlug' => $event->slug,
                    'accessToken' => $booking->boothOwner->access_token
                ]),
                'cancellation_url' => route('payments.pesapal.cancel', [
                    'eventSlug' => $event->slug,
                    'accessToken' => $booking->boothOwner->access_token
                ]),
            ];

            Log::info('Pesapal payment initialization data', $paymentData);

            // Initialize Pesapal payment
            $response = $pesapalService->initializePayment($paymentData);

            if ($response && isset($response['redirect_url'])) {
                // Store payment record
                Payment::create([
                    'booking_id' => $booking->id,
                    'payment_reference' => Payment::generateReference(),
                    'payment_method_id' => $paymentMethod->id,
                    'event_id' => $event->id,
                    'amount' => $booking->balance,
                    'currency' => $paymentMethod->getConfig('currency', 'KES'),
                    'status' => 'pending',
                    'gateway' => 'pesapal',
                    'gateway_reference' => $paymentData['reference'],
                    'gateway_response' => $response,
                ]);

                // Store redirect URL in session for popup window
                session(['pesapal_redirect_url' => $response['redirect_url']]);

                return redirect()->route('bookings.payment', [
                    'eventSlug' => $event->slug,
                    'accessToken' => $booking->boothOwner->access_token
                ])->with('pesapal_redirect', $response['redirect_url']);
            } else {
                Log::error('Pesapal payment initialization failed', [
                    'response' => $response,
                    'booking_id' => $booking->id
                ]);

                return back()->with('error', 'Failed to initialize payment. Please try again.');
            }
        } catch (\Exception $e) {
            Log::error('Pesapal payment processing failed', [
                'booking_id' => $booking->id,
                'error' => $e->getMessage()
            ]);

            return back()->with('error', 'Payment processing failed. Please try again.');
        }
    }

    /**
     * Process legacy payment (manual/bank transfer)
     */
    private function processLegacyPayment(Request $request, Event $event, Booking $booking)
    {
        // For manual payments, just redirect to success page
        return redirect()->route('bookings.success', [
            'eventSlug' => $event->slug,
            'accessToken' => $booking->boothOwner->access_token
        ])->with('success', 'Payment instructions will be sent to your email.');
    }

    /**
     * Handle Paystack payment callback
     */
    public function paystackCallback(Request $request, $eventSlug, $accessToken)
    {
        $event = Event::where('slug', $eventSlug)->firstOrFail();
        $boothOwner = \App\Models\BoothOwner::where('access_token', $accessToken)->firstOrFail();
        $booking = $boothOwner->booking;

        if (!$booking) {
            return redirect()->route('events.public.floorplan', $eventSlug)
                ->with('error', 'No booking found for this access token.');
        }

        try {
            $paystackService = new PaystackService();
            $paystackService->initializeWithPaymentMethod($booking->event->paymentMethods->where('is_default', true)->first());

            $reference = $request->query('reference');
            $transaction = $paystackService->verifyTransaction($reference);

            if ($transaction && $transaction['status']) {
                // Update payment status
                $payment = Payment::where('gateway_reference', $reference)->first();
                if ($payment) {
                    $payment->update([
                        'status' => 'completed',
                        'gateway_response' => $transaction,
                    ]);

                    // Update booking status
                    $booking->update(['status' => 'confirmed']);

                    Log::info('Paystack payment completed successfully', [
                        'booking_id' => $booking->id,
                        'payment_id' => $payment->id,
                        'reference' => $reference,
                        'amount' => $payment->amount
                    ]);
                } else {
                    Log::error('Paystack payment record not found', [
                        'reference' => $reference,
                        'booking_id' => $booking->id
                    ]);
                }

                return redirect()->route('bookings.success', [
                    'eventSlug' => $eventSlug,
                    'accessToken' => $accessToken
                ])->with('success', 'Payment completed successfully!');
            } else {
                Log::error('Paystack payment verification failed', [
                    'reference' => $reference,
                    'transaction' => $transaction,
                    'booking_id' => $booking->id
                ]);

                return redirect()->route('events.public.floorplan', $eventSlug)
                    ->with('error', 'Payment verification failed.');
            }
        } catch (\Exception $e) {
            Log::error('Paystack callback failed', [
                'error' => $e->getMessage(),
                'event_slug' => $eventSlug,
                'access_token' => $accessToken
            ]);

            return redirect()->route('events.public.floorplan', $eventSlug)
                ->with('error', 'Payment verification failed.');
        }
    }

    /**
     * Handle Pesapal payment callback
     */
    public function pesapalCallback(Request $request, $eventSlug, $accessToken)
    {
        $event = Event::where('slug', $eventSlug)->firstOrFail();
        $boothOwner = \App\Models\BoothOwner::where('access_token', $accessToken)->firstOrFail();
        $booking = $boothOwner->booking;

        if (!$booking) {
            return redirect()->route('events.public.floorplan', $eventSlug)
                ->with('error', 'No booking found for this access token.');
        }

        try {
            $pesapalService = new PesapalService();
            $pesapalService->initializeWithPaymentMethod($booking->event->paymentMethods->where('is_default', true)->first());

            $orderTrackingId = $request->query('OrderTrackingId') ?? $request->input('OrderTrackingId');
            $merchantReference = $request->query('OrderMerchantReference') ?? $request->query('MerchantReference') ?? $request->input('OrderMerchantReference') ?? $request->input('MerchantReference');


            if ($orderTrackingId) {

                // Check if this payment has already been processed
                $existingPayment = Payment::where('gateway_reference', $merchantReference)
                    ->where('status', 'completed')
                    ->first();

                if ($existingPayment) {
                    return redirect()->route('bookings.success', [
                        'eventSlug' => $eventSlug,
                        'accessToken' => $accessToken
                    ])->with('success', 'Payment completed successfully!');
                }

                // Verify payment with Pesapal
                $paymentStatus = $pesapalService->getPaymentStatus($orderTrackingId);

                // If merchant reference is not in query params, get it from payment status response
                if (!$merchantReference && $paymentStatus && isset($paymentStatus['merchant_reference'])) {
                    $merchantReference = $paymentStatus['merchant_reference'];
                }


                // Check for various success statuses
                $isCompleted = false;
                if ($paymentStatus) {
                    // Check multiple possible success indicators
                    $status = $paymentStatus['payment_status'] ?? $paymentStatus['status'] ?? 'unknown';
                    $statusDescription = $paymentStatus['payment_status_description'] ?? '';
                    $statusCode = $paymentStatus['status_code'] ?? 0;

                    // Check if payment is completed based on various indicators
                    $isCompleted = in_array(strtoupper($status), ['COMPLETED', 'SUCCESS', 'SUCCESSFUL', 'PAID']) ||
                        in_array(strtoupper($statusDescription), ['COMPLETED', 'SUCCESS', 'SUCCESSFUL', 'PAID']) ||
                        $statusCode === 1; // Pesapal uses status_code: 1 for success
                }

                if ($isCompleted) {
                    // Update payment status
                    $payment = Payment::where('gateway_reference', $merchantReference)->first();


                    if ($payment) {
                        $payment->update([
                            'status' => 'completed',
                            'gateway_response' => $paymentStatus,
                        ]);

                        // Update booking status
                        $booking->update(['status' => 'booked']);

                        // Send payment confirmation email
                        try {
                            $emailService = app(\App\Services\EmailCommunicationService::class);
                            $emailService->sendTriggeredEmail('payment_successful', $booking);
                            Log::info('Pesapal payment confirmation email triggered', [
                                'booking_id' => $booking->id,
                                'trigger_type' => 'payment_successful'
                            ]);
                        } catch (\Exception $e) {
                            Log::error('Failed to send Pesapal payment confirmation email', [
                                'booking_id' => $booking->id,
                                'error' => $e->getMessage()
                            ]);
                        }

                        return redirect()->route('bookings.success', [
                            'eventSlug' => $eventSlug,
                            'accessToken' => $accessToken
                        ])->with('success', 'Payment completed successfully!');
                    } else {

                        // Try to find payment by booking ID and gateway
                        $paymentByBooking = Payment::where('booking_id', $booking->id)
                            ->where('gateway', 'pesapal')
                            ->where('status', 'pending')
                            ->first();

                        if ($paymentByBooking) {

                            $paymentByBooking->update([
                                'status' => 'completed',
                                'gateway_reference' => $merchantReference,
                                'gateway_response' => $paymentStatus,
                            ]);

                            // Update booking status
                            $booking->update(['status' => 'booked']);

                            // Send payment confirmation email
                            try {
                                $emailService = app(\App\Services\EmailCommunicationService::class);
                                $emailService->sendTriggeredEmail('payment_successful', $booking);
                                Log::info('Pesapal payment confirmation email triggered (fallback)', [
                                    'booking_id' => $booking->id,
                                    'trigger_type' => 'payment_successful'
                                ]);
                            } catch (\Exception $e) {
                                Log::error('Failed to send Pesapal payment confirmation email (fallback)', [
                                    'booking_id' => $booking->id,
                                    'error' => $e->getMessage()
                                ]);
                            }

                            return redirect()->route('bookings.success', [
                                'eventSlug' => $eventSlug,
                                'accessToken' => $accessToken
                            ])->with('success', 'Payment completed successfully!');
                        }
                    }
                }
            }

            return redirect()->route('bookings.payment', [
                'eventSlug' => $eventSlug,
                'accessToken' => $accessToken
            ])->with('error', 'Payment verification failed. Please contact support if payment was deducted.');
        } catch (\Exception $e) {
            Log::error('Pesapal callback failed', [
                'error' => $e->getMessage(),
                'event_slug' => $eventSlug,
                'access_token' => $accessToken,
                'orderTrackingId' => $request->query('OrderTrackingId'),
                'merchantReference' => $request->query('MerchantReference'),
                'request_data' => $request->all()
            ]);

            return redirect()->route('bookings.payment', [
                'eventSlug' => $eventSlug,
                'accessToken' => $accessToken
            ])->with('error', 'Payment verification failed. Please contact support if payment was deducted.');
        }
    }

    /**
     * Handle Pesapal payment cancellation
     */
    public function pesapalCancel(Request $request, $eventSlug, $accessToken)
    {
        $event = Event::where('slug', $eventSlug)->firstOrFail();
        $boothOwner = \App\Models\BoothOwner::where('access_token', $accessToken)->firstOrFail();
        $booking = $boothOwner->booking;

        if (!$booking) {
            return redirect()->route('events.public.floorplan', $eventSlug)
                ->with('error', 'No booking found for this access token.');
        }

        // Update payment status to cancelled
        $payment = Payment::where('booking_id', $booking->id)
            ->where('status', 'pending')
            ->where('gateway', 'pesapal')
            ->first();

        if ($payment) {
            $payment->update(['status' => 'cancelled']);
        }

        return redirect()->route('bookings.payment', [
            'eventSlug' => $eventSlug,
            'accessToken' => $accessToken
        ])->with('error', 'Payment was cancelled. You can try again.');
    }

    /**
     * Generate receipt for any payment gateway
     */
    public function generateReceipt(Request $request, $eventSlug, $accessToken)
    {
        try {
            // Find the event
            $event = Event::where('slug', $eventSlug)->firstOrFail();

            // Find booth owner by access token, then get the booking
            $boothOwner = \App\Models\BoothOwner::where('access_token', $accessToken)
                ->where('access_token_expires_at', '>', now())
                ->firstOrFail();
            $booking = $boothOwner->booking()->with(['floorplanItem', 'payments'])->first();

            if (!$booking) {
                return redirect()->route('events.public.floorplan', $eventSlug)
                    ->with('error', 'No booking found for this access token. Please start over.');
            }

            // Check if access token is valid
            if (!$booking->isAccessTokenValid()) {
                return redirect()->route('events.public.floorplan', $eventSlug)
                    ->with('error', 'Access token has expired or is invalid.');
            }

            // Get the latest completed payment
            $payment = $booking->payments()
                ->where('status', 'completed')
                ->latest()
                ->first();

            if (!$payment) {
                throw new \Exception('No completed payment found for this booking.');
            }

            // Get the payment method for this payment
            $paymentMethod = $payment->paymentMethod;
            if (!$paymentMethod) {
                // Fallback: get default payment method for the event
                $paymentMethod = PaymentMethod::where('event_id', $payment->event_id)
                    ->where('is_active', true)
                    ->where('is_default', true)
                    ->first();

                if (!$paymentMethod) {
                    throw new \Exception('Payment method not found for this payment.');
                }
            }

            // Generate receipt based on gateway
            $receipt = null;
            $transactionDetails = null;

            switch ($payment->gateway) {
                case 'paystack':
                    $receipt = $this->generatePaystackReceipt($payment, $paymentMethod, $booking);
                    break;

                case 'pesapal':
                    $receipt = $this->generatePesapalReceipt($payment, $paymentMethod, $booking);
                    break;

                default:
                    // For manual payments or unknown gateways, create a simple receipt
                    $receipt = $this->generateSimpleReceipt($payment);
                    break;
            }

            if ($receipt && $receipt->status && isset($receipt->data)) {
                // Set current step to 5 (Receipt) and show progress
                $currentStep = 5;
                $showProgress = true;

                // Return the receipt data for display/printing
                return view('bookings.receipt', compact('event', 'booking', 'payment', 'receipt', 'transactionDetails', 'currentStep', 'showProgress'));
            } else {
                throw new \Exception('Failed to generate receipt.');
            }
        } catch (\Exception $e) {
            Log::error('Receipt generation failed', [
                'event_slug' => $eventSlug,
                'access_token' => $accessToken,
                'error' => $e->getMessage()
            ]);

            return redirect()->route('bookings.success', [
                'eventSlug' => $eventSlug,
                'accessToken' => $accessToken
            ])->with('error', 'Failed to generate receipt: ' . $e->getMessage());
        }
    }

    /**
     * Generate Paystack receipt
     */
    private function generatePaystackReceipt($payment, $paymentMethod, $booking)
    {
        try {
            $paystackService = new \App\Services\PaystackService();
            $paystackService->initializeWithPaymentMethod($paymentMethod);

            $receipt = $paystackService->generateReceipt(
                $payment->gateway_transaction_id,
                $booking->boothOwner->form_responses['email'] ?? null
            );

            if ($receipt->status && isset($receipt->data)) {
                // Also get detailed transaction information for additional context
                try {
                    $transactionDetails = $paystackService->getTransactionDetails($payment->gateway_transaction_id);
                } catch (\Exception $e) {
                    Log::warning('Could not retrieve additional Paystack transaction details', [
                        'reference' => $payment->gateway_transaction_id,
                        'error' => $e->getMessage()
                    ]);
                }
            }

            return $receipt;
        } catch (\Exception $e) {
            Log::error('Paystack receipt generation failed', [
                'payment_id' => $payment->id,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Generate Pesapal receipt
     */
    private function generatePesapalReceipt($payment, $paymentMethod, $booking)
    {
        try {
            $pesapalService = new PesapalService();
            $pesapalService->initializeWithPaymentMethod($paymentMethod);

            // Extract order tracking ID from gateway response
            $orderTrackingId = null;
            if ($payment->gateway_response && is_array($payment->gateway_response)) {
                $orderTrackingId = $payment->gateway_response['order_tracking_id'] ?? null;
            }

            Log::info('Pesapal receipt generation debug', [
                'payment_id' => $payment->id,
                'gateway_reference' => $payment->gateway_reference,
                'gateway_response' => $payment->gateway_response,
                'extracted_order_tracking_id' => $orderTrackingId
            ]);

            // If we don't have order tracking ID, create a simple receipt from stored data
            if (!$orderTrackingId) {
                Log::warning('Order tracking ID not found, creating receipt from stored payment data', [
                    'payment_id' => $payment->id
                ]);

                return (object) [
                    'status' => true,
                    'data' => (object) [
                        'receipt_number' => $payment->gateway_reference,
                        'channel' => 'pesapal',
                        'amount' => $payment->amount,
                        'currency' => $payment->currency,
                        'gateway_response' => 'completed',
                        'transaction_date' => $payment->created_at->toISOString(),
                        'url' => null,
                        'qr_code' => null
                    ]
                ];
            } else {
                // Generate receipt from Pesapal API
                return $pesapalService->generateReceipt(
                    $orderTrackingId,
                    $booking->boothOwner->form_responses['email'] ?? null
                );
            }
        } catch (\Exception $e) {
            Log::error('Pesapal receipt generation failed', [
                'payment_id' => $payment->id,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Generate simple receipt for manual payments or unknown gateways
     */
    private function generateSimpleReceipt($payment)
    {
        return (object) [
            'status' => true,
            'data' => (object) [
                'receipt_number' => $payment->gateway_reference ?? $payment->payment_reference,
                'channel' => $payment->gateway ?? 'manual',
                'amount' => $payment->amount,
                'currency' => $payment->currency,
                'gateway_response' => 'completed',
                'transaction_date' => $payment->created_at->toISOString(),
                'url' => null,
                'qr_code' => null
            ]
        ];
    }

    /**
     * Handle Pesapal IPN (Instant Payment Notification)
     */
    public function pesapalIPN(Request $request, $eventSlug)
    {
        Log::info('Pesapal IPN received', [
            'event_slug' => $eventSlug,
            'request_data' => $request->all(),
            'headers' => $request->headers->all()
        ]);

        try {
            $event = Event::where('slug', $eventSlug)->firstOrFail();

            // Get the payment method
            $paymentMethod = $event->paymentMethods->where('gateway', 'pesapal')->where('is_active', true)->first();
            if (!$paymentMethod) {
                Log::error('Pesapal payment method not found for IPN', [
                    'event_slug' => $eventSlug
                ]);
                return response()->json(['status' => 'error', 'message' => 'Payment method not found'], 404);
            }

            $pesapalService = app(PesapalService::class);
            $pesapalService->initializeWithPaymentMethod($paymentMethod);

            // Get the order tracking ID from the request
            $orderTrackingId = $request->input('OrderTrackingId');
            $orderMerchantReference = $request->input('OrderMerchantReference');

            if (!$orderTrackingId) {
                Log::error('No OrderTrackingId in IPN request', [
                    'request_data' => $request->all()
                ]);
                return response()->json(['status' => 'error', 'message' => 'No OrderTrackingId provided'], 400);
            }

            // Verify the payment
            $paymentStatus = $pesapalService->getPaymentStatus($orderTrackingId);

            if ($paymentStatus && $paymentStatus['payment_status'] === 'COMPLETED') {
                // Find the payment record
                $payment = Payment::where('gateway_reference', $orderMerchantReference)->first();

                if ($payment) {
                    $payment->update([
                        'status' => 'completed',
                        'gateway_response' => $paymentStatus,
                    ]);

                    // Update booking status
                    $booking = $payment->booking;
                    $booking->update(['status' => 'booked']);

                    // Send payment confirmation email
                    try {
                        $emailService = app(\App\Services\EmailCommunicationService::class);
                        $emailService->sendTriggeredEmail('payment_successful', $booking);
                        Log::info('Pesapal payment confirmation email triggered (IPN)', [
                            'booking_id' => $booking->id,
                            'trigger_type' => 'payment_successful'
                        ]);
                    } catch (\Exception $e) {
                        Log::error('Failed to send Pesapal payment confirmation email (IPN)', [
                            'booking_id' => $booking->id,
                            'error' => $e->getMessage()
                        ]);
                    }

                    Log::info('Payment completed via IPN', [
                        'booking_id' => $booking->id,
                        'payment_id' => $payment->id,
                        'order_tracking_id' => $orderTrackingId,
                        'order_merchant_reference' => $orderMerchantReference
                    ]);
                } else {
                    Log::error('Payment record not found for IPN', [
                        'order_merchant_reference' => $orderMerchantReference,
                        'order_tracking_id' => $orderTrackingId
                    ]);
                }

                return response()->json(['status' => 'success', 'message' => 'Payment processed successfully']);
            } else {
                Log::warning('Payment not completed via IPN', [
                    'order_tracking_id' => $orderTrackingId,
                    'payment_status' => $paymentStatus['payment_status'] ?? 'unknown'
                ]);
                return response()->json(['status' => 'pending', 'message' => 'Payment not completed']);
            }
        } catch (\Exception $e) {
            Log::error('Pesapal IPN processing failed', [
                'event_slug' => $eventSlug,
                'error' => $e->getMessage(),
                'request_data' => $request->all()
            ]);

            return response()->json(['status' => 'error', 'message' => 'IPN processing failed'], 500);
        }
    }
}
