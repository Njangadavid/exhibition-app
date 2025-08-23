<?php

namespace App\Services;

use Yabacon\Paystack;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;

class PaystackService
{
    protected $paystack;
    protected $secretKey;
    protected $publicKey;

    public function __construct()
    {
        // No default initialization - must be configured via payment method
    }

    /**
     * Initialize Paystack with payment method configuration
     */
    public function initializeWithPaymentMethod($paymentMethod)
    {
        // Get configuration from payment method using helper methods
        $this->secretKey = $paymentMethod->getSecretKey();
        $this->publicKey = $paymentMethod->getPublicKey();
        
        if ($this->secretKey) {
            $this->paystack = new Paystack($this->secretKey);
        }
    }

    /**
     * Initialize a transaction
     */
    public function initializeTransaction($data)
    {
        try {
            if (!$this->paystack) {
                throw new \Exception('Paystack not configured');
            }

            // Log the data being sent to Paystack
            Log::info('Sending data to Paystack', [
                'amount' => $data['amount'] * 100,
                'email' => $data['email'],
                'reference' => $data['reference'],
                'callback_url' => $data['callback_url'],
                'webhook_url' => $data['webhook_url'] ?? null,
                'currency' => $data['currency'] ?? 'USD',
                'metadata' => $data['metadata'] ?? [],
            ]);

            $transaction = $this->paystack->transaction->initialize([
                'amount' => $data['amount'] * 100, // Convert to cents (smallest currency unit)
                'email' => $data['email'],
                'reference' => $data['reference'],
                'callback_url' => $data['callback_url'],
                'webhook_url' => $data['webhook_url'] ?? null, // Add webhook URL support
                'currency' => $data['currency'] ?? 'USD',
                'metadata' => $data['metadata'] ?? [],
            ]);

            Log::info('Paystack transaction initialized', [
                'reference' => $data['reference'],
                'response' => $transaction
            ]);

            return $transaction;

        } catch (\Exception $e) {
            Log::error('Paystack transaction initialization failed', [
                'error' => $e->getMessage(),
                'data' => $data
            ]);
            throw $e;
        }
    }

    /**
     * Verify a transaction
     */
    public function verifyTransaction($reference)
    {
        try {
            if (!$this->paystack) {
                throw new \Exception('Paystack not configured');
            }

            // Paystack library expects an array with the reference
            $transaction = $this->paystack->transaction->verify(['reference' => $reference]);

            Log::info('Paystack transaction verified', [
                'reference' => $reference,
                'response' => $transaction
            ]);

            return $transaction;

        } catch (\Exception $e) {
            Log::error('Paystack transaction verification failed', [
                'error' => $e->getMessage(),
                'reference' => $reference
            ]);
            throw $e;
        }
    }

    /**
     * Charge a card (for direct card payments)
     */
    public function chargeCard($data)
    {
        try {
            if (!$this->paystack) {
                throw new \Exception('Paystack not configured');
            }

            $charge = $this->paystack->transaction->charge([
                'amount' => $data['amount'] * 100,
                'email' => $data['email'],
                'authorization_code' => $data['authorization_code'],
                'reference' => $data['reference'],
                'currency' => $data['currency'] ?? 'NGN',
                'metadata' => $data['metadata'] ?? [],
            ]);

            Log::info('Paystack card charge successful', [
                'reference' => $data['reference'],
                'response' => $charge
            ]);

            return $charge;

        } catch (\Exception $e) {
            Log::error('Paystack card charge failed', [
                'error' => $e->getMessage(),
                'data' => $data
            ]);
            throw $e;
        }
    }

    /**
     * Get public key for frontend
     */
    public function getPublicKey()
    {
        return $this->publicKey;
    }

    /**
     * Check if Paystack is configured with payment method
     */
    public function isConfiguredWithPaymentMethod($paymentMethod)
    {
        return !empty($paymentMethod->getSecretKey()) && !empty($paymentMethod->getPublicKey());
    }

    /**
     * Generate a receipt for a transaction
     */
    public function generateReceipt($reference, $email = null)
    {
        try {
            if (!$this->paystack) {
                throw new \Exception('Paystack not configured');
            }

            // Paystack doesn't have a direct receipt method, but we can get transaction details
            // and format them as a receipt. Let's get the transaction details first
            $transaction = $this->paystack->transaction->verify(['reference' => $reference]);

            if ($transaction->status && isset($transaction->data)) {
                // Create a receipt-like response using transaction data
                $receipt = (object) [
                    'status' => true,
                    'data' => (object) [
                        'receipt_number' => $transaction->data->reference ?? $reference,
                        'channel' => $transaction->data->channel ?? 'card',
                        'amount' => $transaction->data->amount ?? 0,
                        'currency' => $transaction->data->currency ?? 'NGN',
                        'gateway_response' => $transaction->data->gateway_response ?? 'success',
                        'transaction_date' => $transaction->data->transaction_date ?? now()->toISOString(),
                        'url' => null, // Paystack doesn't provide direct receipt URLs
                        'qr_code' => null // Paystack doesn't provide QR codes for receipts
                    ]
                ];

                Log::info('Receipt generated from transaction data', [
                    'reference' => $reference,
                    'receipt_data' => $receipt
                ]);

                return $receipt;
            } else {
                throw new \Exception('Failed to retrieve transaction data for receipt generation');
            }

        } catch (\Exception $e) {
            Log::error('Paystack receipt generation failed', [
                'error' => $e->getMessage(),
                'reference' => $reference
            ]);
            throw $e;
        }
    }

    /**
     * Get receipt details by reference
     */
    public function getReceipt($reference)
    {
        try {
            if (!$this->paystack) {
                throw new \Exception('Paystack not configured');
            }

            // Since Paystack doesn't have a receipt method, we'll get transaction details
            // and format them as receipt data
            $transaction = $this->paystack->transaction->verify(['reference' => $reference]);

            if ($transaction->status && isset($transaction->data)) {
                // Format transaction data as receipt data
                $receipt = (object) [
                    'status' => true,
                    'data' => (object) [
                        'receipt_number' => $transaction->data->reference ?? $reference,
                        'channel' => $transaction->data->channel ?? 'card',
                        'amount' => $transaction->data->amount ?? 0,
                        'currency' => $transaction->data->currency ?? 'NGN',
                        'gateway_response' => $transaction->data->gateway_response ?? 'success',
                        'transaction_date' => $transaction->data->transaction_date ?? now()->toISOString(),
                        'url' => null,
                        'qr_code' => null
                    ]
                ];

                Log::info('Receipt data retrieved from transaction', [
                    'reference' => $reference,
                    'receipt_data' => $receipt
                ]);

                return $receipt;
            } else {
                throw new \Exception('Failed to retrieve transaction data');
            }

        } catch (\Exception $e) {
            Log::error('Paystack receipt retrieval failed', [
                'error' => $e->getMessage(),
                'reference' => $reference
            ]);
            throw $e;
        }
    }

    /**
     * Get detailed transaction information
     */
    public function getTransactionDetails($reference)
    {
        try {
            if (!$this->paystack) {
                throw new \Exception('Paystack not configured');
            }

            $transaction = $this->paystack->transaction->verify(['reference' => $reference]);

            if ($transaction->status && isset($transaction->data)) {
                Log::info('Transaction details retrieved successfully', [
                    'reference' => $reference,
                    'transaction_data' => $transaction->data
                ]);

                return $transaction;
            } else {
                throw new \Exception('Failed to retrieve transaction details');
            }

        } catch (\Exception $e) {
            Log::error('Failed to get transaction details', [
                'error' => $e->getMessage(),
                'reference' => $reference
            ]);
            throw $e;
        }
    }

    /**
     * Get receipt URL for direct download/printing
     */
    public function getReceiptUrl($reference)
    {
        try {
            if (!$this->paystack) {
                throw new \Exception('Paystack not configured');
            }

            // Paystack doesn't provide direct receipt URLs, but we can create our own
            // receipt page URL using the transaction reference
            $transaction = $this->paystack->transaction->verify(['reference' => $reference]);
            
            if ($transaction->status && isset($transaction->data)) {
                // Return null since Paystack doesn't provide direct receipt URLs
                // Users will need to use our custom receipt page
                return null;
            }

            return null;

        } catch (\Exception $e) {
            Log::error('Paystack receipt URL retrieval failed', [
                'error' => $e->getMessage(),
                'reference' => $reference
            ]);
            return null;
        }
    }
}
