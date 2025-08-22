<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Booking extends Model
{
    protected $fillable = [
        'event_id',
        'floorplan_item_id',
        'booking_reference',
        'access_token',
        'access_token_expires_at',
        'status',
        'total_amount',
        'owner_details',
        'member_details',
        'notes',
        'booking_date',
        'confirmed_at',
        'cancelled_at',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'owner_details' => 'array',
        'member_details' => 'array',
        'booking_date' => 'datetime',
        'access_token_expires_at' => 'datetime',
        'confirmed_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    /**
     * Get the event that owns the booking.
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Get the floorplan item that was booked.
     */
    public function floorplanItem(): BelongsTo
    {
        return $this->belongsTo(FloorplanItem::class);
    }

    /**
     * Get the payments for this booking.
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Generate a unique booking reference.
     */
    public static function generateReference(): string
    {
        do {
            $reference = 'BK' . date('Ymd') . strtoupper(substr(md5(uniqid()), 0, 6));
        } while (static::where('booking_reference', $reference)->exists());

        return $reference;
    }

    /**
     * Generate a unique access token.
     */
    public static function generateAccessToken(): string
    {
        do {
            $token = 'AT_' . strtoupper(substr(md5(uniqid() . time()), 0, 32));
        } while (static::where('access_token', $token)->exists());

        return $token;
    }

    /**
     * Check if access token is valid and not expired.
     */
    public function isAccessTokenValid(): bool
    {
        if (!$this->access_token) {
            return false;
        }

        // Check if expiration date exists and is in the past
        if ($this->access_token_expires_at) {
            // Ensure it's a Carbon instance
            $expiryDate = $this->access_token_expires_at;
            if (is_string($expiryDate)) {
                $expiryDate = \Carbon\Carbon::parse($expiryDate);
            }
            
            if ($expiryDate->isPast()) {
                return false;
            }
        }

        return true;
    }

    /**
     * Generate a new access token and set expiration.
     */
    public function refreshAccessToken(): string
    {
        $this->access_token = static::generateAccessToken();
        $this->access_token_expires_at = now()->addYear(); // 1 year validity
        $this->save();

        return $this->access_token;
    }

    /**
     * Calculate total paid amount from completed payments.
     */
    public function getTotalPaidAttribute(): float
    {
        return $this->payments()
            ->where('status', 'completed')
            ->sum('amount');
    }

    /**
     * Calculate remaining amount to be paid.
     */
    public function getRemainingAmountAttribute(): float
    {
        return max(0, $this->total_amount - $this->total_paid);
    }

    /**
     * Check if booking is fully paid.
     */
    public function isFullyPaid(): bool
    {
        return $this->remaining_amount <= 0;
    }

    /**
     * Check if booking has any payments.
     */
    public function hasPayments(): bool
    {
        return $this->payments()->where('status', 'completed')->exists();
    }

    /**
     * Get the payment status based on payment history.
     */
    public function getPaymentStatusAttribute(): string
    {
        $totalPaid = $this->total_paid;
        $totalAmount = $this->total_amount;

        if ($totalPaid <= 0) {
            return 'pending';
        } elseif ($totalPaid < $totalAmount) {
            return 'partial';
        } else {
            return 'paid';
        }
    }

    /**
     * Update booking status based on payment status.
     */
    public function updateStatusBasedOnPayments(): void
    {
        if ($this->isFullyPaid()) {
            $this->update([
                'status' => 'confirmed',
                'confirmed_at' => now(),
            ]);
        }
    }
}
