<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmailTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'name',
        'subject',
        'content',
        'trigger_type',
        'conditions',
        'is_active'
    ];

    protected $casts = [
        'conditions' => 'array',
        'is_active' => 'boolean'
    ];

    /**
     * Get the event that owns the email template
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Get available trigger types
     */
    public static function getTriggerTypes(): array
    {
        return [
            'owner_registration' => 'Owner Registration',
            'member_registration' => 'Member Registration',
            'payment_successful' => 'Payment Successful',
            'booth_confirmed' => 'Booth Confirmed',
            'event_reminder' => 'Event Reminder'
        ];
    }

    /**
     * Get trigger type display name
     */
    public function getTriggerTypeDisplayNameAttribute(): string
    {
        return self::getTriggerTypes()[$this->trigger_type] ?? $this->trigger_type;
    }

    /**
     * Check if template should send based on conditions
     */
    public function shouldSend($booking): bool
    {
        if (empty($this->conditions)) {
            return true;
        }

        foreach ($this->conditions as $condition) {
            if (!$this->evaluateCondition($condition, $booking)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Evaluate a single condition
     */
    private function evaluateCondition($condition, $booking): bool
    {
        $field = $condition['field'] ?? '';
        $operator = $condition['operator'] ?? '';
        $value = $condition['value'] ?? '';

        switch ($field) {
            case 'booth_type':
                $boothType = $booking->floorplanItem->type ?? '';
                return $this->compareValues($boothType, $operator, $value);
            
            case 'payment_amount':
                $amount = $booking->total_amount ?? 0;
                return $this->compareValues($amount, $operator, $value);
            
            case 'booth_price':
                $price = $booking->floorplanItem->price ?? 0;
                return $this->compareValues($price, $operator, $value);
            
            default:
                return true;
        }
    }

    /**
     * Compare values based on operator
     */
    private function compareValues($actual, $operator, $expected): bool
    {
        switch ($operator) {
            case 'equals':
                return $actual == $expected;
            case 'not_equals':
                return $actual != $expected;
            case 'greater_than':
                return $actual > $expected;
            case 'less_than':
                return $actual < $expected;
            case 'contains':
                return str_contains(strtolower($actual), strtolower($expected));
            default:
                return true;
        }
    }

    /**
     * Get available merge fields for this template
     */
    public function getAvailableMergeFields(): array
    {
        $baseFields = [
            'event' => [
                'name' => 'Event Name',
                'start_date' => 'Event Start Date',
                'end_date' => 'Event End Date',
                'venue' => 'Event Venue'
            ],
            'owner' => [
                'name' => 'Owner Name',
                'email' => 'Owner Email',
                'company' => 'Company Name',
                'phone' => 'Phone Number',
                'account_link' => 'Direct Account Link'
            ],
            'booth' => [
                'number' => 'Booth Number',
                'type' => 'Booth Type',
                'price' => 'Booth Price',
                'location' => 'Booth Location'
            ]
        ];

        // Add member fields for member registration templates
        if ($this->trigger_type === 'member_registration') {
            $baseFields['member'] = [
                'name' => 'Member Name',
                'email' => 'Member Email',
                'role' => 'Member Role'
            ];
        }

        // Add payment fields for payment templates
        if ($this->trigger_type === 'payment_successful') {
            $baseFields['payment'] = [
                'amount' => 'Payment Amount',
                'method' => 'Payment Method',
                'date' => 'Payment Date',
                'reference' => 'Payment Reference'
            ];
        }

        return $baseFields;
    }

    /**
     * Process merge fields in content
     */
    public function processMergeFields($content, $data): string
    {
        // Sanitize content first
        $content = $this->sanitizeString($content);
        
        foreach ($data as $category => $fields) {
            if (is_array($fields)) {
                foreach ($fields as $key => $value) {
                    $placeholder = "{{ {$category}.{$key} }}";
                    $sanitizedValue = $this->sanitizeString($value ?? '');
                    $content = str_replace($placeholder, $sanitizedValue, $content);
                }
            }
        }

        return $content;
    }

    /**
     * Sanitize string to ensure proper UTF-8 encoding
     */
    private function sanitizeString($value): string
    {
        if ($value === null) {
            return '';
        }

        $value = (string) $value;

        // Remove any invalid UTF-8 characters
        $cleaned = iconv('UTF-8', 'UTF-8//IGNORE', $value);
        
        // Convert to UTF-8 if not already
        if (!mb_check_encoding($cleaned, 'UTF-8')) {
            $cleaned = mb_convert_encoding($cleaned, 'UTF-8', 'auto');
        }

        return $cleaned;
    }

    /**
     * Clone the template
     */
    public function clone($newName = null): EmailTemplate
    {
        $clone = $this->replicate();
        $clone->name = $newName ?: $this->name . ' (Copy)';
        $clone->is_active = false; // Cloned templates start as inactive
        $clone->save();

        return $clone;
    }
}
