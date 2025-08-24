<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Booking extends Model
{
    protected $fillable = [
        'event_id',
        'floorplan_item_id',
        'booking_reference',
        'status',
        'total_amount',
        'notes',
        'booking_date',
        'confirmed_at',
        'cancelled_at',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'booking_date' => 'datetime',
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
     * Get the email logs for this booking.
     */
    public function emailLogs(): HasMany
    {
        return $this->hasMany(EmailLog::class);
    }

    /**
     * Get the booth owner for this booking
     */
    public function boothOwner(): BelongsTo
    {
        return $this->belongsTo(BoothOwner::class);
    }

    /**
     * Get the booth members for this booking through booth owner
     */
    public function boothMembers(): HasManyThrough
    {
        return $this->hasManyThrough(BoothMember::class, BoothOwner::class);
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
     * Get access token through booth owner
     */
    public function getAccessTokenAttribute(): ?string
    {
        return $this->boothOwner?->access_token;
    }

    /**
     * Get access token expiry through booth owner
     */
    public function getAccessTokenExpiresAtAttribute(): ?string
    {
        return $this->boothOwner?->access_token_expires_at;
    }

    /**
     * Check if access token is valid and not expired.
     */
    public function isAccessTokenValid(): bool
    {
        return $this->boothOwner?->isAccessTokenValid() ?? false;
    }

    /**
     * Generate a new access token and set expiration through booth owner.
     */
    public function refreshAccessToken(): string
    {
        if (!$this->boothOwner) {
            throw new \Exception('No booth owner found for this booking');
        }

        $this->boothOwner->access_token = BoothOwner::generateAccessToken();
        $this->boothOwner->access_token_expires_at = now()->addYear(); // 1 year validity
        $this->boothOwner->save();

        return $this->boothOwner->access_token;
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
     * Calculate remaining balance to be paid.
     */
    public function getBalanceAttribute(): float
    {
        $totalAmount = $this->floorplanItem->price ?? 0;
        return max(0, $totalAmount - $this->total_paid);
    }

    /**
     * Calculate remaining amount to be paid (alias for balance).
     */
    public function getRemainingAmountAttribute(): float
    {
        return $this->balance;
    }

    /**
     * Check if booking is fully paid.
     */
    public function isFullyPaid(): bool
    {
        return $this->balance <= 0;
    }

    /**
     * Check if booking has any completed payments.
     */
    public function hasCompletedPayments(): bool
    {
        return $this->payments()->where('status', 'completed')->exists();
    }

    /**
     * Check if booking has any payments.
     */
    public function hasPayments(): bool
    {
        return $this->payments()->exists();
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

    /**
     * Check if changing to a new booth would be an upgrade (more expensive).
     */
    public function isBoothUpgrade(FloorplanItem $newBooth): bool
    {
        $currentBooth = $this->floorplanItem;
        if (!$currentBooth) {
            return false;
        }

        $currentPrice = $currentBooth->price ?? 0;
        $newPrice = $newBooth->price ?? 0;

        return $newPrice > $currentPrice;
    }

    /**
     * Get the price difference for upgrading to a new booth.
     */
    public function getBoothUpgradePriceDifference(FloorplanItem $newBooth): float
    {
        if (!$this->isBoothUpgrade($newBooth)) {
            return 0;
        }

        $currentBooth = $this->floorplanItem;
        $currentPrice = $currentBooth->price ?? 0;
        $newPrice = $newBooth->price ?? 0;

        return $newPrice - $currentPrice;
    }

    /**
     * Check if there are pending payments after an upgrade.
     */
    public function hasPendingUpgradePayments(): bool
    {
        $totalAmount = $this->floorplanItem->price ?? 0;
        $totalPaid = $this->total_paid;
        
        return $totalAmount > $totalPaid;
    }

    /**
     * Get the amount due after an upgrade.
     */
    public function getUpgradeAmountDue(): float
    {
        $totalAmount = $this->floorplanItem->price ?? 0;
        $totalPaid = $this->total_paid;
        
        return max(0, $totalAmount - $totalPaid);
    }

    /**
     * Get the owner email from owner_details.
     */
    public function getOwnerEmailAttribute(): ?string
    {
        return $this->owner_details['email'] ?? null;
    }

    /**
     * Get the owner name from owner_details.
     */
    public function getOwnerNameAttribute(): ?string
    {
        return $this->owner_details['name'] ?? null;
    }

    /**
     * Get the owner phone from owner_details.
     */
    public function getOwnerPhoneAttribute(): ?string
    {
        return $this->owner_details['phone'] ?? null;
    }

    /**
     * Get the company name from owner_details.
     */
    public function getCompanyNameAttribute(): ?string
    {
        return $this->owner_details['company_name'] ?? null;
    }
}
