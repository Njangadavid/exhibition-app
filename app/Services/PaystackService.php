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
        $this->secretKey = config('services.paystack.secret_key');
        $this->publicKey = config('services.paystack.public_key');
        
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

            $transaction = $this->paystack->transaction->initialize([
                'amount' => $data['amount'] * 100, // Convert to kobo (smallest currency unit)
                'email' => $data['email'],
                'reference' => $data['reference'],
                'callback_url' => $data['callback_url'],
                'currency' => $data['currency'] ?? 'NGN',
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

            $transaction = $this->paystack->transaction->verify($reference);

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
     * Check if Paystack is configured
     */
    public function isConfigured()
    {
        return !empty($this->secretKey) && !empty($this->publicKey);
    }
}
