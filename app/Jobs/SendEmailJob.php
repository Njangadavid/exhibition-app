<?php

namespace App\Jobs;

use App\Models\EmailTemplate;
use App\Models\EmailLog;
use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 60;
    public $tries = 3;

    protected $emailLogId;
    protected $templateId;
    protected $recipientEmail;
    protected $subject;
    protected $content;
    protected $attachmentData;

    /**
     * Create a new job instance.
     */
    public function __construct(int $emailLogId, int $templateId, string $recipientEmail, string $subject, string $content, ?array $attachmentData = null)
    {
        $this->emailLogId = $emailLogId;
        $this->templateId = $templateId;
        $this->recipientEmail = $recipientEmail;
        $this->subject = $subject;
        $this->content = $content;
        $this->attachmentData = $attachmentData;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            // Update email log status to processing
            $emailLog = EmailLog::find($this->emailLogId);
            if (!$emailLog) {
                Log::error('Email log not found', ['email_log_id' => $this->emailLogId]);
                return;
            }

            $emailLog->markAsProcessing();

            // Sanitize content to ensure proper UTF-8 encoding
            $sanitizedContent = $this->sanitizeContent($this->content);
            $sanitizedSubject = $this->sanitizeContent($this->subject);
            
            // Ensure content is properly formatted as HTML
            $htmlContent = $this->ensureHtmlContent($sanitizedContent);
            
            // Add intuitive footer for owner_registration and payment triggers
            $htmlContent = $this->addIntuitiveFooter($htmlContent, $this->templateId);

            // Send the email using Laravel Mail as HTML
            Mail::html($htmlContent, function ($message) {
                $message->to($this->recipientEmail)
                        ->subject($this->subject);
                
                // Add attachment if provided
                if ($this->attachmentData) {
                    $message->attachData(
                        $this->attachmentData['content'],
                        $this->attachmentData['filename'],
                        ['mime' => $this->attachmentData['mime_type']]
                    );
                }
            });

            // Mark as sent
            $emailLog->update([
                'status' => 'sent',
                'sent_at' => now()
            ]);

            Log::info('Email sent successfully', [
                'email_log_id' => $this->emailLogId,
                'recipient' => $this->recipientEmail,
                'subject' => $this->subject
            ]);

        } catch (\Exception $e) {
            // Update email log status to failed
            $emailLog = EmailLog::find($this->emailLogId);
            if ($emailLog) {
                $emailLog->update([
                    'status' => 'failed',
                    'error_message' => $e->getMessage()
                ]);
            }

            Log::error('Failed to send email', [
                'email_log_id' => $this->emailLogId,
                'error' => $e->getMessage()
            ]);

            throw $e;
        }
    }

    /**
     * Ensure content is properly formatted as HTML
     */
    private function ensureHtmlContent(string $content): string
    {
        // If content already has HTML tags, return as is
        if (strip_tags($content) !== $content) {
            return $content;
        }

        // If content is plain text, wrap it in basic HTML structure
        $lines = explode("\n", $content);
        $htmlLines = array_map(function($line) {
            $line = trim($line);
            if (empty($line)) {
                return '<br>';
            }
            return '<p>' . htmlspecialchars($line) . '</p>';
        }, $lines);

        return '<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Email</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    ' . implode("\n", $htmlLines) . '
</body>
</html>';
    }

    /**
     * Add intuitive footer for owner_registration and payment triggers
     */
    private function addIntuitiveFooter(string $htmlContent, int $templateId): string
    {
        // Get the template to check trigger type
        $template = \App\Models\EmailTemplate::find($templateId);
        
        if (!$template || !in_array($template->trigger_type, ['owner_registration', 'payment_successful'])) {
            return $htmlContent;
        }

        // Get the booking from the email log
        $emailLog = \App\Models\EmailLog::find($this->emailLogId);
        if (!$emailLog || !$emailLog->booking) {
            return $htmlContent;
        }

        $booking = $emailLog->booking;
        $event = $booking->event;
        $accountLink = route('bookings.owner-form-token', [
            'eventSlug' => $event->slug,
            'accessToken' => $booking->access_token
        ]);

        $footer = '
        <div style="margin-top: 40px; padding-top: 20px; border-top: 2px solid #007bff; text-align: center; background-color: #f8f9fa; padding: 20px; border-radius: 8px;">
            <h3 style="color: #007bff; margin-bottom: 15px; font-size: 18px;">
                <i class="bi bi-person-circle" style="margin-right: 8px;"></i>
                Manage Your Account
            </h3>
            <p style="color: #666; margin-bottom: 20px; font-size: 14px;">
                Need to update your information or make changes to your booking?
            </p>
            <a href="' . $accountLink . '" 
               style="display: inline-block; background-color: #007bff; color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px; font-weight: bold; font-size: 16px;">
                <i class="bi bi-pencil-square" style="margin-right: 8px;"></i>
                Access Your Account
            </a>
            <p style="color: #999; margin-top: 15px; font-size: 12px;">
                This link is unique to you and provides secure access to your booking details.
            </p>
        </div>';

        // Insert footer before closing body tag
        if (strpos($htmlContent, '</body>') !== false) {
            return str_replace('</body>', $footer . '</body>', $htmlContent);
        } else {
            // If no body tag, append to the end
            return $htmlContent . $footer;
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        // Update email log status to failed
        $emailLog = EmailLog::find($this->emailLogId);
        if ($emailLog) {
            $emailLog->update([
                'status' => 'failed',
                'error_message' => $exception->getMessage()
            ]);
        }

        Log::error('Email job failed permanently', [
            'email_log_id' => $this->emailLogId,
            'error' => $exception->getMessage()
        ]);
    }

    /**
     * Sanitize content to ensure proper UTF-8 encoding
     */
    private function sanitizeContent(string $content): string
    {
        if (empty($content)) {
            return '';
        }

        // Remove any invalid UTF-8 characters
        $cleaned = iconv('UTF-8', 'UTF-8//IGNORE', $content);
        
        // Convert to UTF-8 if not already
        if (!mb_check_encoding($cleaned, 'UTF-8')) {
            $cleaned = mb_convert_encoding($cleaned, 'UTF-8', 'auto');
        }

        // Additional cleanup for common problematic characters
        $cleaned = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $cleaned);
        
        return $cleaned;
    }
}
