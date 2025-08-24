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
    public function sendTriggeredEmail(string $triggerType, Booking $booking): void
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
                $this->sendEmailToMembers($template, $booking);
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
     * Send email to booth members
     */
    private function sendEmailToMembers(EmailTemplate $template, Booking $booking): void
    {
        if (empty($booking->member_details)) {
            Log::error("member_details is empty",$booking);
            return;
        }

        foreach ($booking->member_details as $member) {
            $emailLog = EmailLog::create([
                'event_id' => $booking->event_id,
                'template_id' => $template->id,
                'recipient_email' => $member['email'] ?? '',
                'recipient_type' => 'member',
                'booking_id' => $booking->id,
                'trigger_type' => $template->trigger_type,
                'status' => 'pending'
            ]);

            try {
                $data = $this->prepareEmailData($booking, 'member', $member);
                $processedContent = $template->processMergeFields($template->content, $data);
                $processedSubject = $template->processMergeFields($template->subject, $data);

                // Dispatch email job to queue
                SendEmailJob::dispatch(
                    $emailLog->id,
                    $template->id,
                    $member['email'],
                    $processedSubject,
                    $processedContent
                );
                
                // Email log status will be updated by the job

            } catch (\Exception $e) {
                $emailLog->markAsFailed($e->getMessage());
                Log::error("Failed to send email to member", [
                    'member_email' => $member['email'],
                    'error' => $e->getMessage()
                ]);
            }
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
            $data['member'] = [
                'name' => $this->sanitizeString($memberData['name'] ?? ''),
                'email' => $this->sanitizeString($memberData['email'] ?? ''),
                'role' => $this->sanitizeString($memberData['role'] ?? '')
            ];
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
}
