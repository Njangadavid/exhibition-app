<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $fillable = [
        'booking_id',
        'payment_method_id',
        'event_id',
        'payment_reference',
        'type',
        'status',
        'method',
        'amount',
        'currency',
        'gateway',
        'gateway_transaction_id',
        'gateway_reference',
        'gateway_authorization_code',
        'gateway_customer_code',
        'gateway_bank_code',
        'gateway_account_number',
        'gateway_account_name',
        'gateway_response',
        'gateway_metadata',
        'notes',
        'processed_at',
        'failed_at',
        'failure_reason',
        'failure_code',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'gateway_response' => 'array',
        'processed_at' => 'datetime',
        'failed_at' => 'datetime',
    ];

    /**
     * Get the booking that owns the payment.
     */
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Get the payment method used for this payment.
     */
    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    /**
     * Get the event this payment belongs to.
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Generate a unique payment reference.
     */
    public static function generateReference(): string
    {
        do {
            $reference = 'PAY' . date('Ymd') . strtoupper(substr(md5(uniqid()), 0, 8));
        } while (static::where('payment_reference', $reference)->exists());

        return $reference;
    }

    /**
     * Check if payment is completed.
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Check if payment is pending.
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if payment failed.
     */
    public function isFailed(): bool
    {
        return in_array($this->status, ['failed', 'cancelled']);
    }

    /**
     * Mark payment as completed.
     */
    public function markAsCompleted(): void
    {
        $this->update([
            'status' => 'completed',
            'processed_at' => now(),
        ]);
    }

    /**
     * Mark payment as failed.
     */
    public function markAsFailed(string $reason = null, string $code = null): void
    {
        $this->update([
            'status' => 'failed',
            'failed_at' => now(),
            'failure_reason' => $reason,
            'failure_code' => $code,
        ]);
    }

    /**
     * Check if payment is using Paystack gateway.
     */
    public function isPaystack(): bool
    {
        return $this->gateway === 'paystack';
    }

    /**
     * Get Paystack-specific data.
     */
    public function getPaystackData(): array
    {
        if (!$this->isPaystack()) {
            return [];
        }

        return [
            'reference' => $this->gateway_reference,
            'authorization_code' => $this->gateway_authorization_code,
            'customer_code' => $this->gateway_customer_code,
            'bank_code' => $this->gateway_bank_code,
            'account_number' => $this->gateway_account_number,
            'account_name' => $this->gateway_account_name,
            'metadata' => $this->gateway_metadata,
        ];
    }

    /**
     * Set Paystack-specific data.
     */
    public function setPaystackData(array $data): void
    {
        $this->update([
            'gateway' => 'paystack',
            'gateway_reference' => $data['reference'] ?? null,
            'gateway_authorization_code' => $data['authorization_code'] ?? null,
            'gateway_customer_code' => $data['customer_code'] ?? null,
            'gateway_bank_code' => $data['bank_code'] ?? null,
            'gateway_account_number' => $data['account_number'] ?? null,
            'gateway_account_name' => $data['account_name'] ?? null,
            'gateway_metadata' => $data['metadata'] ?? null,
        ]);
    }

    /**
     * Get payment method display name.
     */
    public function getMethodDisplayNameAttribute(): string
    {
        return match($this->method) {
            'paystack' => 'Paystack',
            'flutterwave' => 'Flutterwave',
            'stripe' => 'Stripe',
            'paypal' => 'PayPal',
            'bank_transfer' => 'Bank Transfer',
            'cash' => 'Cash',
            default => ucfirst($this->method),
        };
    }
}
