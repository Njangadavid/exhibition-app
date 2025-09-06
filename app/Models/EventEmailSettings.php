<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Crypt;

class EventEmailSettings extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'smtp_host',
        'smtp_port',
        'smtp_username',
        'smtp_password',
        'smtp_encryption',
        'send_as_email',
        'send_as_name',
        'is_active'
    ];

    protected $casts = [
        'smtp_port' => 'integer',
        'is_active' => 'boolean',
    ];

    protected $hidden = [
        'smtp_password',
    ];

    /**
     * Get the event that owns the email settings.
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Encrypt the SMTP password when setting it.
     */
    public function setSmtpPasswordAttribute($value)
    {
        // Only encrypt if a value is provided (not null or empty)
        if (!empty($value)) {
            $this->attributes['smtp_password'] = Crypt::encryptString($value);
        }
    }

    /**
     * Decrypt the SMTP password when retrieving it.
     */
    public function getSmtpPasswordAttribute($value)
    {
        try {
            return Crypt::decryptString($value);
        } catch (\Exception $e) {
            return $value; // Return as-is if decryption fails (for old data)
        }
    }

    /**
     * Get the SMTP configuration array for Laravel Mail.
     */
    public function getSmtpConfig(): array
    {
        return [
            'driver' => 'smtp',
            'host' => $this->smtp_host,
            'port' => $this->smtp_port,
            'username' => $this->smtp_username,
            'password' => $this->smtp_password,
            'encryption' => $this->smtp_encryption ?: null,
            'from' => [
                'address' => $this->send_as_email,
                'name' => $this->send_as_name ?: $this->send_as_email,
            ],
        ];
    }

    /**
     * Check if the email settings are properly configured.
     */
    public function isConfigured(): bool
    {
        return !empty($this->smtp_host) && 
               !empty($this->smtp_username) && 
               !empty($this->smtp_password) && 
               !empty($this->send_as_email) &&
               $this->is_active;
    }
}