<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BoothOwner extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'qr_code',
        'form_responses',
        'access_token',
        'access_token_expires_at'
    ];

    protected $casts = [
        'form_responses' => 'array',
        'access_token_expires_at' => 'datetime'
    ];

    /**
     * Get the booking that owns the booth owner
     */
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Get the booth members for this owner
     */
    public function boothMembers(): HasMany
    {
        return $this->hasMany(BoothMember::class);
    }

    /**
     * Generate a unique QR code
     */
    public static function generateQrCode(): string
    {
        do {
            $qrCode = '1' . str_pad(rand(0, 99999), 5, '0', STR_PAD_LEFT);
        } while (static::where('qr_code', $qrCode)->exists() ||
                 BoothMember::where('qr_code', $qrCode)->exists());
        
        return $qrCode;
    }

    /**
     * Get owner name from form responses
     */
    public function getNameAttribute(): ?string
    {
        return $this->form_responses['name'] ?? null;
    }

    /**
     * Get owner email from form responses
     */
    public function getEmailAttribute(): ?string
    {
        return $this->form_responses['email'] ?? null;
    }

    /**
     * Get owner phone from form responses
     */
    public function getPhoneAttribute(): ?string
    {
        return $this->form_responses['phone'] ?? null;
    }

    /**
     * Get owner company from form responses
     */
    public function getCompanyAttribute(): ?string
    {
        return $this->form_responses['company'] ?? null;
    }

    /**
     * Get owner title from form responses
     */
    public function getTitleAttribute(): ?string
    {
        return $this->form_responses['title'] ?? null;
    }

    /**
     * Generate a unique access token
     */
    public static function generateAccessToken(): string
    {
        do {
            $token = 'AT_' . strtoupper(substr(md5(uniqid() . time()), 0, 32));
        } while (static::where('access_token', $token)->exists());
        
        return $token;
    }

    /**
     * Check if access token is valid and not expired
     */
    public function isAccessTokenValid(): bool
    {
        if (!$this->access_token) {
            return false;
        }

        if ($this->access_token_expires_at && $this->access_token_expires_at->isPast()) {
            return false;
        }

        return true;
    }

    /**
     * Get the access token for this booth owner
     */
    public function getAccessToken(): ?string
    {
        return $this->access_token;
    }

    /**
     * Get the access token expiry date
     */
    public function getAccessTokenExpiry(): ?string
    {
        return $this->access_token_expires_at?->format('Y-m-d H:i:s');
    }
}
