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
        'form_responses'
    ];

    protected $casts = [
        'form_responses' => 'array'
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
}
