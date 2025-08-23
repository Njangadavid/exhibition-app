<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmailLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'template_id',
        'recipient_email',
        'recipient_type',
        'booking_id',
        'trigger_type',
        'status',
        'sent_at',
        'error_message'
    ];

    protected $casts = [
        'sent_at' => 'datetime'
    ];

    /**
     * Get the event that owns the email log
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Get the email template used
     */
    public function template(): BelongsTo
    {
        return $this->belongsTo(EmailTemplate::class);
    }

    /**
     * Get the booking associated with this email
     */
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Get status display name
     */
    public function getStatusDisplayNameAttribute(): string
    {
        return match($this->status) {
            'sent' => 'Sent',
            'failed' => 'Failed',
            'pending' => 'Pending',
            default => 'Unknown'
        };
    }

    /**
     * Get recipient type display name
     */
    public function getRecipientTypeDisplayNameAttribute(): string
    {
        return match($this->recipient_type) {
            'owner' => 'Owner',
            'member' => 'Member',
            default => 'Unknown'
        };
    }

    /**
     * Mark email as sent
     */
    public function markAsSent(): void
    {
        $this->update([
            'status' => 'sent',
            'sent_at' => now()
        ]);
    }

    /**
     * Mark email as failed
     */
    public function markAsFailed(string $errorMessage): void
    {
        $this->update([
            'status' => 'failed',
            'error_message' => $errorMessage
        ]);
    }
}
