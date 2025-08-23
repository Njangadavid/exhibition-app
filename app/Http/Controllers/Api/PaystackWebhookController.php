<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Booking;
use App\Services\PaystackService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class PaystackWebhookController extends Controller
{
    protected $paystackService;

    public function __construct(PaystackService $paystackService)
    {
        $this->paystackService = $paystackService;
    }

    /**
     * Handle Paystack webhook
     */
    public function handleWebhook(Request $request)
    {
        try {
            Log::info('Paystack webhook received', [
                'headers' => $request->headers->all(),
                'body' => $request->all()
            ]);

            // Verify webhook signature (security measure)
            if (!$this->verifyWebhookSignature($request)) {
                Log::warning('Paystack webhook signature verification failed');
                return response()->json(['error' => 'Invalid signature'], 400);
            }

            // Get the event data
            $eventData = $request->input('data');
            $event = $request->input('event');

            if (!$eventData || !$event) {
                Log::error('Paystack webhook missing required data', $request->all());
                return response()->json(['error' => 'Missing required data'], 400);
            }

            // Handle different webhook events
            switch ($event) {
                case 'charge.success':
                    return $this->handleSuccessfulPayment($eventData);
                
                case 'charge.failed':
                    return $this->handleFailedPayment($eventData);
                
                case 'transfer.success':
                    return $this->handleTransferSuccess($eventData);
                
                case 'transfer.failed':
                    return $this->handleTransferFailed($eventData);
                
                default:
                    Log::info('Unhandled Paystack webhook event', [
                        'event' => $event,
                        'data' => $eventData
                    ]);
                    return response()->json(['status' => 'ignored'], 200);
            }

        } catch (\Exception $e) {
            Log::error('Paystack webhook processing failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);

            return response()->json(['error' => 'Internal server error'], 500);
        }
    }

    /**
     * Handle successful payment
     */
    protected function handleSuccessfulPayment($eventData)
    {
        try {
            DB::beginTransaction();

            $reference = $eventData['reference'] ?? null;
            if (!$reference) {
                throw new \Exception('No reference in successful payment event');
            }

            // Find the payment record
            $payment = Payment::where('gateway_transaction_id', $reference)->first();
            if (!$payment) {
                Log::warning('Payment record not found for reference', ['reference' => $reference]);
                DB::rollBack();
                return response()->json(['error' => 'Payment not found'], 404);
            }

            // Update payment status
            $payment->status = 'completed';
            $payment->gateway_response = json_encode($eventData);
            $payment->save();

            // Update booking status
            $booking = $payment->booking;
            if ($booking) {
                $booking->status = 'booked';
                $booking->save();

                Log::info('Payment completed via webhook', [
                    'payment_id' => $payment->id,
                    'booking_id' => $booking->id,
                    'reference' => $reference,
                    'amount' => $eventData['amount'] ?? 'unknown'
                ]);
            }

            DB::commit();
            return response()->json(['status' => 'success']);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to process successful payment webhook', [
                'error' => $e->getMessage(),
                'event_data' => $eventData
            ]);
            throw $e;
        }
    }

    /**
     * Handle failed payment
     */
    protected function handleFailedPayment($eventData)
    {
        try {
            $reference = $eventData['reference'] ?? null;
            if (!$reference) {
                throw new \Exception('No reference in failed payment event');
            }

            // Find and update the payment record
            $payment = Payment::where('gateway_transaction_id', $reference)->first();
            if ($payment) {
                $payment->status = 'failed';
                $payment->gateway_response = json_encode($eventData);
                $payment->save();

                Log::info('Payment failed via webhook', [
                    'payment_id' => $payment->id,
                    'reference' => $reference,
                    'reason' => $eventData['failure_reason'] ?? 'unknown'
                ]);
            }

            return response()->json(['status' => 'success']);

        } catch (\Exception $e) {
            Log::error('Failed to process failed payment webhook', [
                'error' => $e->getMessage(),
                'event_data' => $eventData
            ]);
            throw $e;
        }
    }

    /**
     * Handle transfer success
     */
    protected function handleTransferSuccess($eventData)
    {
        Log::info('Transfer successful via webhook', [
            'transfer_code' => $eventData['transfer_code'] ?? 'unknown',
            'amount' => $eventData['amount'] ?? 'unknown'
        ]);

        return response()->json(['status' => 'success']);
    }

    /**
     * Handle transfer failure
     */
    protected function handleTransferFailed($eventData)
    {
        Log::warning('Transfer failed via webhook', [
            'transfer_code' => $eventData['transfer_code'] ?? 'unknown',
            'reason' => $eventData['failure_reason'] ?? 'unknown'
        ]);

        return response()->json(['status' => 'success']);
    }

    /**
     * Verify webhook signature for security
     */
    protected function verifyWebhookSignature(Request $request)
    {
        $signature = $request->header('X-Paystack-Signature');
        $payload = $request->getContent();
        $webhookSecret = config('services.paystack.webhook_secret');

        if (!$signature || !$webhookSecret) {
            Log::warning('Webhook signature verification skipped - missing signature or secret');
            return true; // Allow if not configured (for development)
        }

        $expectedSignature = hash_hmac('sha512', $payload, $webhookSecret);
        
        if (!hash_equals($expectedSignature, $signature)) {
            Log::error('Webhook signature mismatch', [
                'expected' => $expectedSignature,
                'received' => $signature
            ]);
            return false;
        }

        return true;
    }
}
