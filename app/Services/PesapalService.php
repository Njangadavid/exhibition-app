<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Config;

class PesapalService
{
    protected $consumerKey;
    protected $consumerSecret;
    protected $baseUrl;
    protected $environment;

    public function __construct()
    {
        // No default initialization - must be configured via payment method
    }

    /**
     * Initialize Pesapal with payment method configuration
     */
    public function initializeWithPaymentMethod($paymentMethod)
    {
        // Try Pesapal-specific fields first
        $this->consumerKey = $paymentMethod->getConfig('consumer_key');
        $this->consumerSecret = $paymentMethod->getConfig('consumer_secret');
        
        // If not found, try standard payment method fields
        if (empty($this->consumerKey)) {
            $this->consumerKey = $paymentMethod->getConfig('public_key');
        }
        if (empty($this->consumerSecret)) {
            $this->consumerSecret = $paymentMethod->getConfig('secret_key');
        }
        
        $this->environment = $paymentMethod->getEnvironment();
        
        // Set base URL based on environment
        $this->baseUrl = $this->environment === 'test' 
            ? 'https://cybqa.pesapal.com/pesapalv3' 
            : 'https://pay.pesapal.com/v3';
    }

    /**
     * Convert amount to smallest currency unit (cents, etc.)
     */
    protected function convertToSmallestUnit($amount, $currency = 'KES')
    {
        // Check if the amount is already in the smallest unit
        if (is_numeric($amount) && $amount > 1000 && $amount % 100 == 0) {
            Log::info('Amount appears to already be in smallest unit', [
                'amount' => $amount,
                'currency' => $currency
            ]);
            return $amount;
        }

        // Standard conversion for currencies with 2 decimal places
        $currenciesWithTwoDecimals = ['USD', 'NGN', 'KES', 'EUR', 'GBP', 'CAD', 'AUD', 'TZS', 'UGX'];
        
        if (in_array(strtoupper($currency), $currenciesWithTwoDecimals)) {
            $converted = $amount * 100;
            Log::info('Converting amount to smallest unit', [
                'original' => $amount,
                'currency' => $currency,
                'converted' => $converted
            ]);
            return $converted;
        }

        // For currencies with 0 decimal places (like JPY), no conversion needed
        $currenciesWithZeroDecimals = ['JPY', 'KRW', 'VND'];
        if (in_array(strtoupper($currency), $currenciesWithZeroDecimals)) {
            Log::info('Currency has no decimal places, no conversion needed', [
                'amount' => $amount,
                'currency' => $currency
            ]);
            return $amount;
        }

        // Default: assume 2 decimal places
        Log::info('Using default conversion (2 decimal places)', [
            'amount' => $amount,
            'currency' => $currency
        ]);
        return $amount * 100;
    }

    /**
     * Get access token for API authentication
     */
    public function getAccessToken()
    {
        try {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl . '/api/Auth/RequestToken', [
                'consumer_key' => $this->consumerKey,
                'consumer_secret' => $this->consumerSecret,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                Log::info('Pesapal access token obtained', [
                    'token' => substr($data['token'] ?? '', 0, 20) . '...'
                ]);
                return $data['token'] ?? null;
            }

            Log::error('Failed to get Pesapal access token', [
                'status' => $response->status(),
                'response' => $response->body()
            ]);
            return null;

        } catch (\Exception $e) {
            Log::error('Pesapal access token request failed', [
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * Register IPN URL with Pesapal
     */
    public function registerIPN($ipnUrl)
    {
        try {
            $accessToken = $this->getAccessToken();
            if (!$accessToken) {
                throw new \Exception('Failed to obtain access token');
            }

            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $accessToken,
            ])->post($this->baseUrl . '/api/URLSetup/RegisterIPN', [
                'url' => $ipnUrl,
                'ipn_notification_type' => 'GET'
            ]);

            if ($response->successful()) {
                $result = $response->json();
                Log::info('IPN URL registered successfully', [
                    'ipn_url' => $ipnUrl,
                    'ipn_id' => $result['ipn_id'] ?? null
                ]);
                return $result;
            }

            Log::error('Failed to register IPN URL', [
                'status' => $response->status(),
                'response' => $response->body(),
                'ipn_url' => $ipnUrl
            ]);
            throw new \Exception('Failed to register IPN URL: ' . $response->body());

        } catch (\Exception $e) {
            Log::error('IPN registration failed', [
                'error' => $e->getMessage(),
                'ipn_url' => $ipnUrl
            ]);
            throw $e;
        }
    }

    /**
     * Initialize a payment request
     */
    public function initializePayment($data)
    {
        try {
            $accessToken = $this->getAccessToken();
            if (!$accessToken) {
                throw new \Exception('Failed to obtain access token');
            }

            // Register IPN URL first to get a valid IPN ID
            $eventSlug = $data['event_slug'] ?? 'default';
            $ipnUrl = $data['ipn_url'] ?? route('payments.pesapal.ipn', ['eventSlug' => $eventSlug]);
            $ipnResult = $this->registerIPN($ipnUrl);
            $ipnId = $ipnResult['ipn_id'] ?? null;

            if (!$ipnId) {
                throw new \Exception('Failed to get IPN ID from registration');
            }

            // Use amount as-is for Pesapal (no conversion needed)
            $paymentData = [
                'id' => $data['reference'],
                'currency' => $data['currency'] ?? 'KES',
                'amount' => $data['amount'],
                'description' => $data['description'] ?? 'Payment for ' . $data['reference'],
                'callback_url' => $data['callback_url'],
                'cancellation_url' => $data['cancellation_url'] ?? $data['callback_url'],
                'notification_id' => $ipnId,
                'billing_address' => [
                    'phone_number' => $data['phone_number'] ?? '',
                    'email_address' => $data['email'],
                    'country_code' => $data['country_code'] ?? 'KE',
                    'first_name' => $data['first_name'] ?? '',
                    'middle_name' => $data['middle_name'] ?? '',
                    'last_name' => $data['last_name'] ?? '',
                    'line_1' => $data['line_1'] ?? '',
                    'line_2' => $data['line_2'] ?? '',
                    'city' => $data['city'] ?? '',
                    'state' => $data['state'] ?? '',
                    'postal_code' => $data['postal_code'] ?? '',
                    'zip_code' => $data['zip_code'] ?? '',
                ]
            ];

            Log::info('Sending payment data to Pesapal', [
                'amount' => $data['amount'],
                'email' => $data['email'],
                'reference' => $data['reference'],
                'callback_url' => $data['callback_url'],
                'currency' => $data['currency'] ?? 'KES',
                'ipn_id' => $ipnId,
                'ipn_url' => $ipnUrl,
                'full_payment_data' => $paymentData
            ]);

            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $accessToken,
            ])->post($this->baseUrl . '/api/Transactions/SubmitOrderRequest', $paymentData);

            Log::info('Pesapal API response details', [
                'status_code' => $response->status(),
                'response_headers' => $response->headers(),
                'response_body' => $response->body(),
                'reference' => $data['reference']
            ]);

            if ($response->successful()) {
                $result = $response->json();
                Log::info('Pesapal payment initialized', [
                    'reference' => $data['reference'],
                    'response' => $result
                ]);
                return $result;
            }

            Log::error('Pesapal payment initialization failed', [
                'status' => $response->status(),
                'response' => $response->body(),
                'reference' => $data['reference'],
                'payment_data_sent' => $paymentData
            ]);
            throw new \Exception('Payment initialization failed: ' . $response->body());

        } catch (\Exception $e) {
            Log::error('Pesapal payment initialization error', [
                'error' => $e->getMessage(),
                'data' => $data
            ]);
            throw $e;
        }
    }

    /**
     * Get payment status
     */
    public function getPaymentStatus($orderTrackingId)
    {
        try {
            $accessToken = $this->getAccessToken();
            if (!$accessToken) {
                throw new \Exception('Failed to obtain access token');
            }

            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $accessToken,
            ])->get($this->baseUrl . '/api/Transactions/GetTransactionStatus', [
                'orderTrackingId' => $orderTrackingId
            ]);

            if ($response->successful()) {
                $result = $response->json();
                Log::info('Pesapal payment status retrieved', [
                    'orderTrackingId' => $orderTrackingId,
                    'full_response' => $result,
                    'payment_status' => $result['payment_status'] ?? 'not_found',
                    'status' => $result['status'] ?? 'not_found'
                ]);
                return $result;
            }

            Log::error('Failed to get Pesapal payment status', [
                'status' => $response->status(),
                'response' => $response->body(),
                'orderTrackingId' => $orderTrackingId
            ]);
            throw new \Exception('Failed to get payment status: ' . $response->body());

        } catch (\Exception $e) {
            Log::error('Pesapal payment status check failed', [
                'error' => $e->getMessage(),
                'orderTrackingId' => $orderTrackingId
            ]);
            throw $e;
        }
    }

    /**
     * Verify payment using IPN (Instant Payment Notification)
     */
    public function verifyPayment($data)
    {
        try {
            // Pesapal sends IPN data with payment details
            $orderTrackingId = $data['orderTrackingId'] ?? null;
            $orderMerchantReference = $data['orderMerchantReference'] ?? null;
            $orderNotificationType = $data['orderNotificationType'] ?? null;

            if (!$orderTrackingId) {
                throw new \Exception('Missing order tracking ID');
            }

            // Get payment status to verify
            $paymentStatus = $this->getPaymentStatus($orderTrackingId);

            Log::info('Pesapal payment verification', [
                'orderTrackingId' => $orderTrackingId,
                'orderMerchantReference' => $orderMerchantReference,
                'orderNotificationType' => $orderNotificationType,
                'payment_status' => $paymentStatus['payment_status'] ?? 'unknown'
            ]);

            return $paymentStatus;

        } catch (\Exception $e) {
            Log::error('Pesapal payment verification failed', [
                'error' => $e->getMessage(),
                'data' => $data
            ]);
            throw $e;
        }
    }

    /**
     * Get public key for frontend (Pesapal doesn't use public keys like Paystack)
     */
    public function getPublicKey()
    {
        return $this->consumerKey; // Pesapal uses consumer key for frontend
    }

    /**
     * Check if Pesapal is configured with payment method
     */
    public function isConfiguredWithPaymentMethod($paymentMethod)
    {
        // Check for Pesapal-specific fields first
        if (!empty($paymentMethod->getConfig('consumer_key')) && 
            !empty($paymentMethod->getConfig('consumer_secret'))) {
            return true;
        }
        
        // Check for standard payment method fields (public_key/secret_key)
        if (!empty($paymentMethod->getConfig('public_key')) && 
            !empty($paymentMethod->getConfig('secret_key'))) {
            return true;
        }
        
        return false;
    }

    /**
     * Generate a receipt for a transaction
     */
    public function generateReceipt($orderTrackingId, $email = null)
    {
        try {
            $paymentStatus = $this->getPaymentStatus($orderTrackingId);

            Log::info('Pesapal generateReceipt debug', [
                'orderTrackingId' => $orderTrackingId,
                'paymentStatus' => $paymentStatus,
                'has_payment_status' => isset($paymentStatus['payment_status']),
                'has_payment_status_description' => isset($paymentStatus['payment_status_description'])
            ]);

            if ($paymentStatus && (isset($paymentStatus['payment_status']) || isset($paymentStatus['payment_status_description']))) {
                $receipt = (object) [
                    'status' => true,
                    'data' => (object) [
                        'receipt_number' => $orderTrackingId,
                        'channel' => $paymentStatus['payment_method'] ?? 'pesapal',
                        'amount' => $paymentStatus['amount'] ?? 0,
                        'currency' => $paymentStatus['currency'] ?? 'KES',
                        'gateway_response' => $paymentStatus['payment_status_description'] ?? $paymentStatus['payment_status'] ?? 'unknown',
                        'transaction_date' => $paymentStatus['created_date'] ?? now()->toISOString(),
                        'url' => null, // Pesapal doesn't provide direct receipt URLs
                        'qr_code' => null // Pesapal doesn't provide QR codes for receipts
                    ]
                ];

                Log::info('Receipt generated from Pesapal payment data', [
                    'orderTrackingId' => $orderTrackingId,
                    'receipt_data' => $receipt
                ]);

                return $receipt;
            } else {
                throw new \Exception('Failed to retrieve payment data for receipt generation');
            }

        } catch (\Exception $e) {
            Log::error('Pesapal receipt generation failed', [
                'error' => $e->getMessage(),
                'orderTrackingId' => $orderTrackingId
            ]);
            throw $e;
        }
    }

    /**
     * Get receipt details by order tracking ID
     */
    public function getReceipt($orderTrackingId)
    {
        try {
            $paymentStatus = $this->getPaymentStatus($orderTrackingId);

            if ($paymentStatus && isset($paymentStatus['payment_status'])) {
                $receipt = (object) [
                    'status' => true,
                    'data' => (object) [
                        'receipt_number' => $orderTrackingId,
                        'channel' => $paymentStatus['payment_method'] ?? 'pesapal',
                        'amount' => $paymentStatus['amount'] ?? 0,
                        'currency' => $paymentStatus['currency'] ?? 'KES',
                        'gateway_response' => $paymentStatus['payment_status'] ?? 'unknown',
                        'transaction_date' => $paymentStatus['created_date'] ?? now()->toISOString(),
                        'url' => null,
                        'qr_code' => null
                    ]
                ];

                Log::info('Receipt data retrieved from Pesapal payment', [
                    'orderTrackingId' => $orderTrackingId,
                    'receipt_data' => $receipt
                ]);

                return $receipt;
            } else {
                throw new \Exception('Failed to retrieve payment data');
            }

        } catch (\Exception $e) {
            Log::error('Pesapal receipt retrieval failed', [
                'error' => $e->getMessage(),
                'orderTrackingId' => $orderTrackingId
            ]);
            throw $e;
        }
    }

    /**
     * Get detailed transaction information
     */
    public function getTransactionDetails($orderTrackingId)
    {
        try {
            $paymentStatus = $this->getPaymentStatus($orderTrackingId);

            if ($paymentStatus && isset($paymentStatus['payment_status'])) {
                Log::info('Transaction details retrieved successfully', [
                    'orderTrackingId' => $orderTrackingId,
                    'transaction_data' => $paymentStatus
                ]);

                return $paymentStatus;
            } else {
                throw new \Exception('Failed to retrieve transaction details');
            }

        } catch (\Exception $e) {
            Log::error('Failed to get transaction details', [
                'error' => $e->getMessage(),
                'orderTrackingId' => $orderTrackingId
            ]);
            throw $e;
        }
    }

    /**
     * Get receipt URL for direct download/printing
     */
    public function getReceiptUrl($orderTrackingId)
    {
        try {
            // Pesapal doesn't provide direct receipt URLs
            // Users will need to use our custom receipt page
            return null;

        } catch (\Exception $e) {
            Log::error('Pesapal receipt URL retrieval failed', [
                'error' => $e->getMessage(),
                'orderTrackingId' => $orderTrackingId
            ]);
            return null;
        }
    }
}
