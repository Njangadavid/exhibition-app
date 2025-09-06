<?php

namespace App\Services;

use App\Models\Event;
use App\Models\EventEmailSettings;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class EmailConfigurationService
{
    /**
     * Configure mail settings for a specific event.
     */
    public function configureForEvent(Event $event): bool
    {
        $emailSettings = $event->emailSettings;
        
        if (!$emailSettings || !$emailSettings->isConfigured()) {
            Log::warning('Event email settings not configured, using global settings', [
                'event_id' => $event->id,
                'event_slug' => $event->slug
            ]);
            return false;
        }

        try {
            // Create a new Symfony SMTP transport with the event's email settings
            $transport = new \Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport(
                $emailSettings->smtp_host,
                $emailSettings->smtp_port,
                $emailSettings->smtp_encryption === 'ssl'
            );
            
            $transport->setUsername($emailSettings->smtp_username);
            $transport->setPassword($emailSettings->smtp_password);
            
            // Create a new mailer with the custom transport
            $mailer = new \Symfony\Component\Mailer\Mailer($transport);
            
            // Store the custom mailer for this request
            app()->instance('mail.manager.custom', $mailer);
            
            Log::info('Email configuration updated for event using Symfony Mailer', [
                'event_id' => $event->id,
                'event_slug' => $event->slug,
                'smtp_host' => $emailSettings->smtp_host,
                'smtp_port' => $emailSettings->smtp_port,
                'smtp_username' => $emailSettings->smtp_username,
                'smtp_encryption' => $emailSettings->smtp_encryption,
                'send_as_email' => $emailSettings->send_as_email
            ]);
            
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to configure email settings for event', [
                'event_id' => $event->id,
                'event_slug' => $event->slug,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Reset mail configuration to global settings.
     */
    public function resetToGlobal(): void
    {
        try {
            // Remove the custom mailer instance
            app()->forgetInstance('mail.manager.custom');
            
            Log::info('Email configuration reset to global settings');
        } catch (\Exception $e) {
            Log::error('Failed to reset email configuration', [
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Test email configuration for an event.
     */
    public function testConfiguration(Event $event, string $testEmail): array
    {
        $emailSettings = $event->emailSettings;
        
        if (!$emailSettings || !$emailSettings->isConfigured()) {
            return [
                'success' => false,
                'message' => 'Email settings not configured for this event'
            ];
        }

        try {
            // Configure for this event
            $configured = $this->configureForEvent($event);
            
            if (!$configured) {
                return [
                    'success' => false,
                    'message' => 'Failed to configure email settings for this event'
                ];
            }
            
            // Send test email using the custom mailer
            Log::info('Attempting to send test email', [
                'to' => $testEmail,
                'from' => $emailSettings->send_as_email,
                'from_name' => $emailSettings->send_as_name
            ]);
            
            // Get the custom mailer
            $customMailer = app('mail.manager.custom');
            
            // Create the email message with branded content
            $email = (new \Symfony\Component\Mime\Email())
                ->from(new \Symfony\Component\Mime\Address($emailSettings->send_as_email, $emailSettings->send_as_name))
                ->to($testEmail)
                ->subject('✅ SMTP Test Successful - ExhibitHub Email System')
                ->html($this->generateBrandedTestEmail($emailSettings));
            
            // Send the email using the custom mailer
            $customMailer->send($email);
            
            Log::info('Test email sent successfully');
            
            return [
                'success' => true,
                'message' => 'Test email sent successfully'
            ];
            
        } catch (\Exception $e) {
            // Reset to global settings on error
            $this->resetToGlobal();
            
            Log::error('Test email sending failed', [
                'event_id' => $event->id,
                'test_email' => $testEmail,
                'error' => $e->getMessage(),
                'error_class' => get_class($e),
                'trace' => $e->getTraceAsString()
            ]);
            
            return [
                'success' => false,
                'message' => 'Failed to send test email: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Get the current mail configuration.
     */
    public function getCurrentConfig(): array
    {
        return [
            'driver' => config('mail.default'),
            'smtp' => config('mail.mailers.smtp'),
            'from' => config('mail.from')
        ];
    }

    /**
     * Create or update email settings for an event.
     */
    public function createOrUpdateSettings(Event $event, array $settingsData): EventEmailSettings
    {
        $emailSettings = $event->emailSettings;
        
        if ($emailSettings) {
            $emailSettings->update($settingsData);
        } else {
            $emailSettings = $event->emailSettings()->create($settingsData);
        }
        
        Log::info('Email settings updated for event', [
            'event_id' => $event->id,
            'event_slug' => $event->slug,
            'smtp_host' => $emailSettings->smtp_host,
            'send_as_email' => $emailSettings->send_as_email
        ]);
        
        return $emailSettings;
    }

    /**
     * Check if an event has valid email settings.
     */
    public function hasValidSettings(Event $event): bool
    {
        $emailSettings = $event->emailSettings;
        return $emailSettings && $emailSettings->isConfigured();
    }

    /**
     * Get email settings for an event with fallback to global.
     */
    public function getSettingsForEvent(Event $event): array
    {
        $emailSettings = $event->emailSettings;
        
        if ($emailSettings && $emailSettings->isConfigured()) {
            return $emailSettings->getSmtpConfig();
        }
        
        // Fallback to global settings
        return [
            'driver' => config('mail.default'),
            'smtp' => config('mail.mailers.smtp'),
            'from' => config('mail.from')
        ];
    }

    /**
     * Generate a branded test email HTML content
     */
    private function generateBrandedTestEmail($emailSettings): string
    {
        return '
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>ExhibitHub Email Test</title>
            <style>
                body {
                    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
                    line-height: 1.6;
                    color: #333;
                    max-width: 600px;
                    margin: 0 auto;
                    padding: 20px;
                    background-color: #f8f9fa;
                }
                .email-container {
                    background: white;
                    border-radius: 12px;
                    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                    overflow: hidden;
                }
                                 .header {
                     background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                     color: white;
                     padding: 30px 20px;
                     text-align: center;
                 }
                 .logo-container {
                     display: flex;
                     align-items: center;
                     justify-content: center;
                     margin-bottom: 20px;
                     gap: 15px;
                 }
                 .logo-img {
                     height: 60px;
                     width: auto;
                     border-radius: 8px;
                 }
                 .logo-text-container {
                     text-align: left;
                 }
                 .logo-text {
                     font-size: 32px;
                     font-weight: bold;
                     margin: 0;
                     line-height: 1.1;
                 }
                 .company-name {
                     font-size: 16px;
                     opacity: 0.9;
                     margin: 5px 0 0 0;
                     font-weight: 500;
                 }
                 .tagline {
                     font-size: 16px;
                     opacity: 0.9;
                     margin: 10px 0 0 0;
                 }
                .content {
                    padding: 30px 20px;
                }
                .success-badge {
                    background: #28a745;
                    color: white;
                    padding: 8px 16px;
                    border-radius: 20px;
                    font-size: 14px;
                    font-weight: bold;
                    display: inline-block;
                    margin-bottom: 20px;
                }
                .test-details {
                    background: #f8f9fa;
                    border-left: 4px solid #28a745;
                    padding: 20px;
                    margin: 20px 0;
                    border-radius: 0 8px 8px 0;
                }
                                 .features-grid {
                     display: flex;
                     flex-wrap: wrap;
                     gap: 15px;
                     margin: 25px 0;
                     justify-content: flex-start;
                 }
                 .feature-card {
                     background: #f8f9fa;
                     border: 1px solid #e9ecef;
                     border-radius: 12px;
                     padding: 20px;
                     text-align: center;
                     transition: all 0.3s ease;
                     box-sizing: border-box;
                     width: 100%;
                     min-height: 200px;
                 }
                 .feature-card:hover {
                     transform: translateY(-2px);
                     box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
                     border-color: #667eea;
                 }
                 .feature-icon {
                     font-size: 2.5rem;
                     margin-bottom: 15px;
                     display: block;
                 }
                 .feature-title {
                     font-size: 1.1rem;
                     font-weight: 600;
                     margin-bottom: 10px;
                     color: #333;
                 }
                 .feature-description {
                     font-size: 0.9rem;
                     color: #666;
                     line-height: 1.5;
                     margin: 0;
                 }
                .cta-section {
                    background: #e3f2fd;
                    padding: 25px;
                    text-align: center;
                    margin: 25px 0;
                    border-radius: 8px;
                }
                .cta-button {
                    display: inline-block;
                    background: #1976d2;
                    color: white;
                    padding: 12px 24px;
                    text-decoration: none;
                    border-radius: 6px;
                    font-weight: bold;
                    margin: 10px 5px;
                }
                .footer {
                    background: #f8f9fa;
                    padding: 20px;
                    text-align: center;
                    font-size: 14px;
                    color: #666;
                }
                .contact-info {
                    margin: 15px 0;
                }
                                 .contact-info a {
                     color: #1976d2;
                     text-decoration: none;
                 }
                 .contact-details {
                     background: #f8f9fa;
                     padding: 20px;
                     margin: 20px 0;
                     border-radius: 8px;
                     text-align: center;
                 }
                 .contact-details h4 {
                     margin: 0 0 15px 0;
                     color: #333;
                 }
                 .contact-info p {
                     margin: 5px 0;
                     font-size: 14px;
                 }
                 .contact-info a {
                     color: #1976d2;
                     text-decoration: none;
                     font-weight: 500;
                 }
                 @media (max-width: 900px) {
                     .feature-card {
                         flex: 0 0 calc(50% - 8px);
                         max-width: calc(50% - 8px);
                     }
                 }
                 @media (max-width: 600px) {
                     .logo-container {
                         flex-direction: column;
                         text-align: center;
                         gap: 10px;
                     }
                     .logo-text-container {
                         text-align: center;
                     }
                     .feature-card {
                         padding: 15px;
                     }
                     .features-table td {
                         display: block !important;
                         width: 100% !important;
                         padding: 5px 10px !important;
                     }
                 }
            </style>
        </head>
        <body>
                         <div class="email-container">
                 <div class="header">
                     <div class="logo-container">
                         <img src="https://expo.seamlex.co.ke/images/sajili-2.png" alt="Sajili Seamlex Solutions" class="logo-img">
                         <div class="logo-text-container">
                             <div class="logo-text">ExhibitHub</div>
                             <div class="company-name">by Sajili Seamlex Solutions</div>
                         </div>
                     </div>
                     <p class="tagline">Transform Events into Experiences</p>
                 </div>
                
                <div class="content">
                    <div class="success-badge">✅ SMTP Configuration Test Successful</div>
                    
                    <h2>Your Email System is Working Perfectly!</h2>
                    
                    <p>Congratulations! Your event email configuration has been successfully tested and is ready to use.</p>
                    
                    <div class="test-details">
                        <h3>📧 Test Details</h3>
                        <p><strong>Event:</strong> ' . htmlspecialchars($emailSettings->event->name) . '</p>
                        <p><strong>SMTP Server:</strong> ' . htmlspecialchars($emailSettings->smtp_host) . ':' . $emailSettings->smtp_port . '</p>
                        <p><strong>From Address:</strong> ' . htmlspecialchars($emailSettings->send_as_email) . '</p>
                        <p><strong>Test Time:</strong> ' . now()->format('F j, Y \a\t g:i A') . '</p>
                    </div>
                    
                                         <h3>🚀 What You Can Do Now</h3>
                     <table class="features-table" width="100%" cellpadding="0" cellspacing="0" border="0" style="margin: 25px 0;">
                         <tr>
                             <td width="33.33%" style="padding: 8px; vertical-align: top;">
                                 <div class="feature-card" style="width: 100%; height: 100%;">
                                     <span class="feature-icon">🏢</span>
                                     <h4 class="feature-title">Smart Booth Management</h4>
                                     <p class="feature-description">Easily manage booth assignments, track availability, and handle complex floorplan layouts with our intuitive interface.</p>
                                 </div>
                             </td>
                             <td width="33.33%" style="padding: 8px; vertical-align: top;">
                                 <div class="feature-card" style="width: 100%; height: 100%;">
                                     <span class="feature-icon">👥</span>
                                     <h4 class="feature-title">Member Registration</h4>
                                     <p class="feature-description">Streamlined member management with custom forms, QR codes for check-ins, and automated email communications.</p>
                                 </div>
                             </td>
                             <td width="33.33%" style="padding: 8px; vertical-align: top;">
                                 <div class="feature-card" style="width: 100%; height: 100%;">
                                     <span class="feature-icon">💳</span>
                                     <h4 class="feature-title">Payment Processing</h4>
                                     <p class="feature-description">Secure payment handling with multiple gateway support, automated receipts, and comprehensive financial tracking.</p>
                                 </div>
                             </td>
                         </tr>
                         <tr>
                             <td width="33.33%" style="padding: 8px; vertical-align: top;">
                                 <div class="feature-card" style="width: 100%; height: 100%;">
                                     <span class="feature-icon">📧</span>
                                     <h4 class="feature-title">Email Automation</h4>
                                     <p class="feature-description">Automated email workflows for registrations, confirmations, and updates with customizable templates.</p>
                                 </div>
                             </td>
                             <td width="33.33%" style="padding: 8px; vertical-align: top;">
                                 <div class="feature-card" style="width: 100%; height: 100%;">
                                     <span class="feature-icon">📱</span>
                                     <h4 class="feature-title">Mobile Responsive</h4>
                                     <p class="feature-description">Fully responsive design that works perfectly on all devices, from desktop to mobile phones.</p>
                                 </div>
                             </td>
                             <td width="33.33%" style="padding: 8px; vertical-align: top;">
                                 <div class="feature-card" style="width: 100%; height: 100%;">
                                     <span class="feature-icon">🔒</span>
                                     <h4 class="feature-title">Enterprise Security</h4>
                                     <p class="feature-description">Built with modern security standards, secure access tokens, and robust data protection measures.</p>
                                 </div>
                             </td>
                         </tr>
                     </table>
                     
                     <div class="contact-details">
                         <h4>📞 Get in Touch</h4>
                         <p><strong>Email:</strong> <a href="mailto:info@seamlex.co.ke">info@seamlex.co.ke</a></p>
                         <p><strong>Phone:</strong> +254 734575275 / +254 739454556</p>
                         <p><strong>Platform:</strong> <a href="https://expo.seamlex.co.ke">expo.seamlex.co.ke</a></p>
                     </div>
                     
                     <div class="cta-section">
                         <h3>Ready to Get Started?</h3>
                         <p>Explore all the powerful features of ExhibitHub for your event management needs.</p>
                         <a href="https://expo.seamlex.co.ke" class="cta-button">Visit ExhibitHub</a>
                         <a href="mailto:info@seamlex.co.ke" class="cta-button">Contact Us</a>
                     </div>
                </div>
                
                                 <div class="footer">
                     <div class="contact-info">
                         <p><strong>ExhibitHub</strong> by Sajili Seamlex Solutions</p>
                         <p><strong>Email:</strong> <a href="mailto:info@seamlex.co.ke">info@seamlex.co.ke</a></p>
                         <p><strong>Phone:</strong> +254 734575275 / +254 739454556</p>
                         <p>
                             <a href="https://expo.seamlex.co.ke">ExhibitHub Platform</a> | 
                             <a href="https://seamlex.co.ke">Company Website</a> | 
                             <a href="mailto:info@seamlex.co.ke">Contact Support</a>
                         </p>
                         <p>This is a test email to verify your SMTP configuration is working correctly.</p>
                     </div>
                 </div>
            </div>
        </body>
        </html>';
    }
}
