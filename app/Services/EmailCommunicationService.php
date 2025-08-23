<?php

namespace App\Services;

use App\Models\EmailTemplate;
use App\Models\EmailLog;
use App\Models\Booking;
use App\Models\Event;
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
            if ($triggerType === 'member_registration' || $triggerType === 'booth_confirmed') {
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

            // Send email (you can use Laravel Mail or any email service)
            $this->sendEmail($template->recipient_email, $processedSubject, $processedContent);
            
            $emailLog->markAsSent();

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

                $this->sendEmail($member['email'], $processedSubject, $processedContent);
                
                $emailLog->markAsSent();

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
     * Prepare email data with merge fields
     */
    private function prepareEmailData(Booking $booking, string $recipientType, array $memberData = []): array
    {
        $event = $booking->event;
        $floorplanItem = $booking->floorplanItem;

        $data = [
            'event' => [
                'name' => $event->name ?? '',
                'start_date' => $event->start_date?->format('F d, Y') ?? '',
                'end_date' => $event->end_date?->format('F d, Y') ?? '',
                'venue' => $event->venue ?? ''
            ],
            'owner' => [
                'name' => $booking->owner_details['name'] ?? '',
                'email' => $booking->owner_email ?? '',
                'company' => $booking->owner_details['company'] ?? '',
                'phone' => $booking->owner_details['phone'] ?? ''
            ],
            'booth' => [
                'number' => $floorplanItem->item_id ?? '',
                'type' => $floorplanItem->type ?? '',
                'price' => number_format($floorplanItem->price ?? 0, 2),
                'location' => $floorplanItem->location ?? ''
            ]
        ];

        // Add member data if sending to member
        if ($recipientType === 'member' && !empty($memberData)) {
            $data['member'] = [
                'name' => $memberData['name'] ?? '',
                'email' => $memberData['email'] ?? '',
                'role' => $memberData['role'] ?? ''
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
}
