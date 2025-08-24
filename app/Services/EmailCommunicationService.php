<?php

namespace App\Services;

use App\Models\EmailTemplate;
use App\Models\EmailLog;
use App\Models\Booking;
use App\Models\Event;
use App\Models\Payment;
use App\Jobs\SendEmailJob;
use App\Services\ReceiptService;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class EmailCommunicationService
{
    /**
     * Send triggered email based on event type
     */
    public function sendTriggeredEmail(string $triggerType, Booking $booking, $specificMember = null): void
    {
        try {
            $template = EmailTemplate::where('event_id', $booking->event_id)
                ->where('trigger_type', $triggerType)
                ->where('is_active', true)
                ->first();

            if (!$template) {
                Log::info("No active email template found for trigger: {$triggerType}", [
                    'event_id' => $booking->event_id,
                    'booking_id' => $booking->id
                ]);
                return;
            }

            // Check if template should send based on conditions
            if (!$template->shouldSend($booking)) {
                Log::info("Email template conditions not met", [
                    'template_id' => $template->id,
                    'booking_id' => $booking->id
                ]);
                return;
            }

            // Send to owner
            $this->sendEmailToOwner($template, $booking);

            // Send to members if applicable
            if ($triggerType === 'member_registration') {
                if ($specificMember) {
                    // Send email only to the specific member being added/edited
                    $this->sendEmailToSpecificMember($template, $booking, $specificMember);
                } else {
                    // Send to all members (for backward compatibility)
                    $this->sendEmailToMembers($template, $booking);
                }
            }

        } catch (\Exception $e) {
            Log::error("Failed to send triggered email", [
                'trigger_type' => $triggerType,
                'booking_id' => $booking->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Send email to booth owner
     */
    private function sendEmailToOwner(EmailTemplate $template, Booking $booking): void
    {
        $emailLog = EmailLog::create([
            'event_id' => $booking->event_id,
            'template_id' => $template->id,
            'recipient_email' => $booking->owner_email,
            'recipient_type' => 'owner',
            'booking_id' => $booking->id,
            'trigger_type' => $template->trigger_type,
            'status' => 'pending'
        ]);

        try {
            $data = $this->prepareEmailData($booking, 'owner');
            $processedContent = $template->processMergeFields($template->content, $data);
            $processedSubject = $template->processMergeFields($template->subject, $data);

            // For payment_successful trigger, include receipt PDF
            $attachmentData = null;
            if ($template->trigger_type === 'payment_successful') {
                $attachmentData = $this->prepareReceiptAttachment($booking);
            }

            // Dispatch email job to queue
            SendEmailJob::dispatch(
                $emailLog->id,
                $template->id,
                $booking->owner_email,
                $processedSubject,
                $processedContent,
                $attachmentData
            );
            
            // Email log status will be updated by the job

        } catch (\Exception $e) {
            $emailLog->markAsFailed($e->getMessage());
            throw $e;
        }
    }

    /**
     * Send email to a specific booth member
     */
    private function sendEmailToSpecificMember(EmailTemplate $template, Booking $booking, $memberData): void
    {
        // Get member email from form responses
        $memberEmail = $this->extractMemberEmail($memberData, $booking);
        
        if (!$memberEmail) {
            Log::warning("No email found for member", [
                'booking_id' => $booking->id,
                'member_data' => $memberData
            ]);
            return;
        }

        $emailLog = EmailLog::create([
            'event_id' => $booking->event_id,
            'template_id' => $template->id,
            'recipient_email' => $memberEmail,
            'recipient_type' => 'member',
            'booking_id' => $booking->id,
            'trigger_type' => $template->trigger_type,
            'status' => 'pending'
        ]);

        try {
            $data = $this->prepareEmailData($booking, 'member', $memberData);
            $processedContent = $template->processMergeFields($template->content, $data);
            $processedSubject = $template->processMergeFields($template->subject, $data);

            // Dispatch email job to queue
            SendEmailJob::dispatch(
                $emailLog->id,
                $template->id,
                $memberEmail,
                $processedSubject,
                $processedContent
            );
            
            // Email log status will be updated by the job

        } catch (\Exception $e) {
            $emailLog->markAsFailed($e->getMessage());
            Log::error("Failed to send email to specific member", [
                'member_email' => $memberEmail,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Send email to booth members (for backward compatibility)
     */
    private function sendEmailToMembers(EmailTemplate $template, Booking $booking): void
    {
        // Use the new booth members relationship if available
        if ($booking->boothMembers && $booking->boothMembers->count() > 0) {
            foreach ($booking->boothMembers as $member) {
                $this->sendEmailToSpecificMember($template, $booking, $member->form_responses);
            }
            return;
        }

        // Fallback to old member_details JSON (for backward compatibility)
        if (empty($booking->member_details)) {
            Log::info("No members found for booking", ['booking_id' => $booking->id]);
            return;
        }

        foreach ($booking->member_details as $member) {
            $this->sendEmailToSpecificMember($template, $booking, $member);
        }
    }

    /**
     * Prepare receipt attachment for payment emails
     */
    private function prepareReceiptAttachment(Booking $booking): ?array
    {
        try {
            // Get the latest successful payment
            $payment = $booking->payments()
                ->where('status', 'completed')
                ->latest()
                ->first();

            if (!$payment) {
                Log::warning('No completed payment found for receipt attachment', [
                    'booking_id' => $booking->id
                ]);
                return null;
            }

            $receiptService = app(ReceiptService::class);
            $pdfContent = $receiptService->generateReceiptPdf($payment);
            $filename = $receiptService->getReceiptFilename($payment);

            return [
                'content' => $pdfContent,
                'filename' => $filename,
                'mime_type' => 'application/pdf'
            ];

        } catch (\Exception $e) {
            Log::error('Failed to prepare receipt attachment', [
                'booking_id' => $booking->id,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * Prepare email data with merge fields
     */
    private function prepareEmailData(Booking $booking, string $recipientType, array $memberData = []): array
    {
        $event = $booking->event;
        $floorplanItem = $booking->floorplanItem;

        $data = [
            'event' => [
                'name' => $this->sanitizeString($event->name ?? ''),
                'start_date' => $event->start_date?->format('F d, Y') ?? '',
                'end_date' => $event->end_date?->format('F d, Y') ?? '',
                'venue' => $this->sanitizeString($event->venue ?? '')
            ],
            'owner' => [
                'name' => $this->sanitizeString($booking->owner_details['name'] ?? ''),
                'email' => $this->sanitizeString($booking->owner_email ?? ''),
                'company' => $this->sanitizeString($booking->owner_details['company'] ?? ''),
                'phone' => $this->sanitizeString($booking->owner_details['phone'] ?? ''),
                'account_link' => route('bookings.owner-form-token', [
                    'eventSlug' => $event->slug,
                    'accessToken' => $booking->access_token
                ])
            ],
            'booth' => [
                'number' => $this->sanitizeString($floorplanItem->label ?? ''),
                'type' => $this->sanitizeString($floorplanItem->item_name ?? ''),
                'price' => number_format($floorplanItem->price ?? 0, 2),
                'location' => $this->sanitizeString($floorplanItem->floorplanDesign->name ?? '')
            ]
        ];

        // Add member data if sending to member
        if ($recipientType === 'member' && !empty($memberData)) {
            // Pass the raw member data - the EmailTemplate will process it dynamically
            $data['member'] = $memberData;
        }

        // Add payment data for payment templates
        // Note: This would need to be passed from the calling method
        // For now, we'll add basic payment info
        $data['payment'] = [
            'amount' => number_format($booking->total_amount ?? 0, 2),
            'method' => 'Online Payment',
            'date' => now()->format('F d, Y'),
            'reference' => $booking->id
        ];

        // Validate that the data can be JSON encoded
        $this->validateJsonEncoding($data);

        return $data;
    }

    /**
     * Send email (placeholder - implement with your email service)
     */
    private function sendEmail(string $to, string $subject, string $content): void
    {
        // For now, just log the email
        // In production, implement with Laravel Mail, SendGrid, Mailgun, etc.
        Log::info("Email would be sent", [
            'to' => $to,
            'subject' => $subject,
            'content_length' => strlen($content)
        ]);

        // Example with Laravel Mail:
        // Mail::to($to)->send(new EmailTemplate($subject, $content));
    }

    /**
     * Test email template with sample data
     */
    public function testTemplate(EmailTemplate $template, array $sampleData = []): array
    {
        try {
            $processedContent = $template->processMergeFields($template->content, $sampleData);
            $processedSubject = $template->processMergeFields($template->subject, $sampleData);

            return [
                'success' => true,
                'subject' => $processedSubject,
                'content' => $processedContent
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Sanitize string to ensure proper UTF-8 encoding
     */
    private function sanitizeString(?string $value): string
    {
        if ($value === null) {
            return '';
        }

        // Remove any invalid UTF-8 characters
        $cleaned = iconv('UTF-8', 'UTF-8//IGNORE', $value);
        
        // Convert to UTF-8 if not already
        if (!mb_check_encoding($cleaned, 'UTF-8')) {
            $cleaned = mb_convert_encoding($cleaned, 'UTF-8', 'auto');
        }

        return $cleaned;
    }

    /**
     * Validate that data can be JSON encoded
     */
    private function validateJsonEncoding(array $data): void
    {
        $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            $error = json_last_error_msg();
            Log::error('JSON encoding failed in email data preparation', [
                'error' => $error,
                'data' => $data
            ]);
            throw new \Exception("Failed to prepare email data: {$error}");
        }
    }

    /**
     * Extract member email from form responses
     * Finds form field with type 'email' and uses its field_id to get email value
     */
    private function extractMemberEmail($memberData, Booking $booking = null): ?string
    {
        Log::info('=== EXTRACT MEMBER EMAIL START ===', [
            'member_data' => $memberData,
            'member_data_type' => gettype($memberData)
        ]);

        if (!is_array($memberData)) {
            Log::warning('Member data is not an array', [
                'member_data' => $memberData,
                'type' => gettype($memberData)
            ]);
            return null;
        }

        try {
            // Use the passed booking parameter
            if (!$booking) {
                Log::warning('No booking provided for email extraction');
                return null;
            }

            Log::info('Found booking for email extraction', [
                'booking_id' => $booking->id,
                'event_id' => $booking->event_id
            ]);

            // Get the form builder for this event first
            $formBuilder = \App\Models\FormBuilder::where('event_id', $booking->event_id)
                ->where('type', 'member_registration')
                ->first();

            if (!$formBuilder) {
                Log::warning('No member_registration form builder found', [
                    'event_id' => $booking->event_id,
                    'available_forms' => \App\Models\FormBuilder::where('event_id', $booking->event_id)->pluck('type', 'id')
                ]);
                
                // Fallback: try any form for this event
                $formBuilder = \App\Models\FormBuilder::where('event_id', $booking->event_id)->first();
                if ($formBuilder) {
                    Log::info('Using fallback form for email extraction', [
                        'form_id' => $formBuilder->id,
                        'form_type' => $formBuilder->type
                    ]);
                }
            }

            if (!$formBuilder) {
                Log::error('No form builder found for email extraction', [
                    'event_id' => $booking->event_id
                ]);
                return null;
            }

            Log::info('Form builder found for email extraction', [
                'form_id' => $formBuilder->id,
                'form_type' => $formBuilder->type,
                'form_name' => $formBuilder->name
            ]);

            // Get the form fields that belong to this form builder
            $formFields = \App\Models\FormField::where('form_builder_id', $formBuilder->id)->get();

            if ($formFields->isEmpty()) {
                Log::warning('No form fields found for this form builder', [
                    'form_builder_id' => $formBuilder->id,
                    'event_id' => $booking->event_id
                ]);
                return null;
            }
            Log::info('Form fields found', [
                'total_fields' => $formFields->count(),
                'field_details' => $formFields->map(function($field) {
                    return [
                        'id' => $field->id,
                        'field_id' => $field->field_id,
                        'type' => $field->type,
                        'label' => $field->label
                    ];
                })
            ]);

            // Find the form field with type 'email'
            $emailField = $formFields->where('type', 'email')->first();

            if (!$emailField) {
                Log::warning('No email field found in form', [
                    'form_id' => $formBuilder->id,
                    'available_field_types' => $formFields->pluck('type')->unique()
                ]);
                return null;
            }

            Log::info('Email field found', [
                'field_id' => $emailField->field_id,
                'field_label' => $emailField->label,
                'field_type' => $emailField->type
            ]);

            // Get the email value using the field_id
            $emailValue = $memberData[$emailField->field_id] ?? null;

            if ($emailValue) {
                Log::info('Email successfully extracted', [
                    'email' => $emailValue,
                    'field_id' => $emailField->field_id,
                    'field_label' => $emailField->label
                ]);
                return $emailValue;
            } else {
                Log::warning('Email field found but no value in member data', [
                    'field_id' => $emailField->field_id,
                    'field_label' => $emailField->label,
                    'available_keys' => array_keys($memberData)
                ]);
                return null;
            }

        } catch (\Exception $e) {
            Log::error('Error extracting member email', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'member_data' => $memberData
            ]);
            return null;
        }
    }

    /**
     * Extract member data for email templates using form field structure
     */
    private function extractMemberDataForEmail(array $memberData, $booking): array
    {
        try {
            // Get the member registration form builder for this event
            $formBuilder = \App\Models\FormBuilder::where('event_id', $booking->event_id)
                ->where('type', 'member_registration')
                ->first();

            if (!$formBuilder) {
                // Fallback: try any form for this event
                $formBuilder = \App\Models\FormBuilder::where('event_id', $booking->event_id)->first();
            }

            if (!$formBuilder) {
                Log::warning('No form builder found for member data extraction', [
                    'event_id' => $booking->event_id
                ]);
                return [
                    'name' => 'Member',
                    'email' => '',
                    'role' => ''
                ];
            }

            // Get the form fields
            $formFields = \App\Models\FormField::where('form_builder_id', $formBuilder->id)->get();

            $memberInfo = [
                'name' => 'Member',
                'email' => '',
                'role' => ''
            ];

            // Extract data based on field purposes
            foreach ($formFields as $field) {
                $fieldValue = $memberData[$field->field_id] ?? '';
                
                switch ($field->field_purpose) {
                    case 'member_name':
                        $memberInfo['name'] = $this->sanitizeString($fieldValue);
                        break;
                    case 'member_email':
                        $memberInfo['email'] = $this->sanitizeString($fieldValue);
                        break;
                    case 'member_title':
                        $memberInfo['role'] = $this->sanitizeString($fieldValue);
                        break;
                }
            }

            // If no specific field purposes found, try to find common fields
            if ($memberInfo['name'] === 'Member') {
                foreach ($formFields as $field) {
                    $fieldValue = $memberData[$field->field_id] ?? '';
                    
                    if ($field->type === 'email' && empty($memberInfo['email'])) {
                        $memberInfo['email'] = $this->sanitizeString($fieldValue);
                    } elseif (stripos($field->label, 'name') !== false && $memberInfo['name'] === 'Member') {
                        $memberInfo['name'] = $this->sanitizeString($fieldValue);
                    } elseif (stripos($field->label, 'title') !== false || stripos($field->label, 'role') !== false) {
                        $memberInfo['role'] = $this->sanitizeString($fieldValue);
                    }
                }
            }

            return $memberInfo;

        } catch (\Exception $e) {
            Log::error('Error extracting member data for email', [
                'error' => $e->getMessage(),
                'member_data' => $memberData
            ]);
            
            return [
                'name' => 'Member',
                'email' => '',
                'role' => ''
            ];
        }
    }
}
