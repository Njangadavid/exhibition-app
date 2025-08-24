<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BoothMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'booth_owner_id',
        'qr_code',
        'form_responses',
        'status'
    ];

    protected $casts = [
        'form_responses' => 'array'
    ];

    /**
     * Get the booth owner that owns this member
     */
    public function boothOwner(): BelongsTo
    {
        return $this->belongsTo(BoothOwner::class);
    }

    /**
     * Generate a unique QR code
     */
    public static function generateQrCode(): string
    {
        do {
            $qrCode = '1' . str_pad(rand(0, 99999), 5, '0', STR_PAD_LEFT);
        } while (static::where('qr_code', $qrCode)->exists() ||
                 BoothOwner::where('qr_code', $qrCode)->exists());
        
        return $qrCode;
    }

    /**
     * Get member name from form responses
     */
    public function getNameAttribute(): ?string
    {
        return $this->form_responses['name'] ?? null;
    }

    /**
     * Get member email from form responses
     */
    public function getEmailAttribute(): ?string
    {
        return $this->form_responses['email'] ?? null;
    }

    /**
     * Get member phone from form responses
     */
    public function getPhoneAttribute(): ?string
    {
        return $this->form_responses['phone'] ?? null;
    }

    /**
     * Get member company from form responses
     */
    public function getCompanyAttribute(): ?string
    {
        return $this->form_responses['company'] ?? null;
    }

    /**
     * Get member title from form responses
     */
    public function getTitleAttribute(): ?string
    {
        return $this->form_responses['title'] ?? null;
    }

    /**
     * Check if member is active
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }
}
