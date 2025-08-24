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
            $baseFields['member'] = $this->getDynamicMemberMergeFields();
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
     * Get dynamic member merge fields from form builder
     */
    private function getDynamicMemberMergeFields(): array
    {
        $memberFields = [];
        
        try {
            // Get the member registration form builder for this event
            $formBuilder = \App\Models\FormBuilder::where('event_id', $this->event_id)
                ->where('type', 'member_registration')
                ->first();
            
            if ($formBuilder) {
                // Get all form fields except sections
                $formFields = \App\Models\FormField::where('form_builder_id', $formBuilder->id)
                    ->where('type', '!=', 'section')
                    ->orderBy('sort_order')
                    ->get();
                
                foreach ($formFields as $field) {
                    // Use exact field label as the key and display name
                    $key = $field->label;
                    $memberFields[$key] = $field->label;
                    
                    // Debug logging
                    \Illuminate\Support\Facades\Log::info('Generated merge field', [
                        'field_id' => $field->field_id,
                        'field_label' => $field->label,
                        'merge_field_key' => $key,
                        'merge_field_placeholder' => "{{ member.{$key} }}"
                    ]);
                }
            }
        } catch (\Exception $e) {
            // Log error but don't fail
            \Illuminate\Support\Facades\Log::warning('Failed to get dynamic member merge fields', [
                'event_id' => $this->event_id,
                'error' => $e->getMessage()
            ]);
        }
        
        // Fallback to basic fields if no dynamic fields found
        if (empty($memberFields)) {
            $memberFields = [
                'name' => 'Member Name',
                'email' => 'Member Email',
                'phone' => 'Phone Number',
                'company' => 'Company Name',
                'title' => 'Job Title'
            ];
        }
        
        return $memberFields;
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

        // Handle dynamic member field extraction for member_registration templates
        if ($this->trigger_type === 'member_registration' && isset($data['member'])) {
            $content = $this->processDynamicMemberFields($content, $data['member']);
        }

        return $content;
    }

    /**
     * Process dynamic member fields using form field structure
     */
    private function processDynamicMemberFields(string $content, array $memberData): string
    {
        try {
            // Get the member registration form builder for this event
            $formBuilder = \App\Models\FormBuilder::where('event_id', $this->event_id)
                ->where('type', 'member_registration')
                ->first();
            
            if ($formBuilder) {
                // Debug logging
                \Illuminate\Support\Facades\Log::info('Processing dynamic member fields', [
                    'event_id' => $this->event_id,
                    'form_builder_id' => $formBuilder->id,
                    'member_data_keys' => array_keys($memberData),
                    'content_length' => strlen($content)
                ]);
                
                // Find and replace merge fields by searching for {{ member.field_label }} pattern
                // This approach directly uses the field label as the merge field key
                preg_match_all('/\{\{\s*member\.([^}]+)\s*\}\}/', $content, $matches);
                
                if (!empty($matches[1])) {
                    foreach ($matches[1] as $fieldLabel) {
                        $fieldLabel = trim($fieldLabel);
                        
                        // Find the form field by label
                        $formField = \App\Models\FormField::where('form_builder_id', $formBuilder->id)
                            ->where('label', $fieldLabel)
                            ->first();
                        
                        if ($formField) {
                            // Get the value from member data using field_id
                            $value = $memberData[$formField->field_id] ?? '';
                            
                            // Create the placeholder to replace
                            $placeholder = "{{ member.{$fieldLabel} }}";
                            
                            // Debug logging
                            \Illuminate\Support\Facades\Log::info('Processing merge field', [
                                'field_label' => $fieldLabel,
                                'field_id' => $formField->field_id,
                                'placeholder' => $placeholder,
                                'value' => $value,
                                'placeholder_in_content' => strpos($content, $placeholder) !== false
                            ]);
                            
                            // Replace the placeholder with the actual value
                            $content = str_replace($placeholder, $this->sanitizeString($value), $content);
                        } else {
                            \Illuminate\Support\Facades\Log::warning('Form field not found for label', [
                                'field_label' => $fieldLabel,
                                'form_builder_id' => $formBuilder->id
                            ]);
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::warning('Failed to process dynamic member fields', [
                'event_id' => $this->event_id,
                'error' => $e->getMessage()
            ]);
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
