<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\EmailTemplate;
use App\Models\Event;

class EmailTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the first event (or create one if none exists)
        $event = Event::first();
        
        if (!$event) {
            $this->command->info('No events found. Please create an event first.');
            return;
        }

        // Sample email templates
        $templates = [
            [
                'name' => 'Welcome Email for New Owners',
                'subject' => 'Welcome to {{ event.name }}! Your booth is confirmed.',
                'content' => '
                    <h2>Welcome to {{ event.name }}!</h2>
                    <p>Dear {{ owner.name }},</p>
                    <p>Thank you for registering for <strong>{{ event.name }}</strong>! We\'re excited to have you join us.</p>
                    
                    <h3>Your Booking Details:</h3>
                    <ul>
                        <li><strong>Booth Number:</strong> {{ booth.number }}</li>
                        <li><strong>Booth Type:</strong> {{ booth.type }}</li>
                        <li><strong>Location:</strong> {{ booth.location }}</li>
                        <li><strong>Price:</strong> ${{ booth.price }}</li>
                    </ul>
                    
                    <h3>Event Information:</h3>
                    <ul>
                        <li><strong>Date:</strong> {{ event.start_date }} - {{ event.end_date }}</li>
                        <li><strong>Venue:</strong> {{ event.venue }}</li>
                    </ul>
                    
                    <p>If you have any questions, please don\'t hesitate to contact us.</p>
                    
                    <p>Best regards,<br>
                    The {{ event.name }} Team</p>
                ',
                'trigger_type' => 'owner_registration',
                'is_active' => true
            ],
            [
                'name' => 'Payment Confirmation Email',
                'subject' => 'Payment Received - {{ event.name }}',
                'content' => '
                    <h2>Payment Confirmation</h2>
                    <p>Dear {{ owner.name }},</p>
                    <p>Thank you for your payment for <strong>{{ event.name }}</strong>!</p>
                    
                    <h3>Payment Details:</h3>
                    <ul>
                        <li><strong>Amount Paid:</strong> ${{ payment.amount }}</li>
                        <li><strong>Payment Method:</strong> {{ payment.method }}</li>
                        <li><strong>Payment Date:</strong> {{ payment.date }}</li>
                        <li><strong>Reference:</strong> {{ payment.reference }}</li>
                    </ul>
                    
                    <h3>Your Booth:</h3>
                    <ul>
                        <li><strong>Booth Number:</strong> {{ booth.number }}</li>
                        <li><strong>Booth Type:</strong> {{ booth.type }}</li>
                        <li><strong>Location:</strong> {{ booth.location }}</li>
                    </ul>
                    
                    <p>Your booth is now fully confirmed. We look forward to seeing you at the event!</p>
                    
                    <p>Best regards,<br>
                    The {{ event.name }} Team</p>
                ',
                'trigger_type' => 'payment_successful',
                'is_active' => true
            ],
            [
                'name' => 'Member Registration Welcome',
                'subject' => 'Welcome to the team - {{ event.name }}',
                'content' => '
                    <h2>Welcome to the Team!</h2>
                    <p>Dear {{ member.name }},</p>
                    <p>You have been added as a team member for <strong>{{ event.name }}</strong>!</p>
                    
                    <h3>Your Details:</h3>
                    <ul>
                        <li><strong>Name:</strong> {{ member.name }}</li>
                        <li><strong>Email:</strong> {{ member.email }}</li>
                        <li><strong>Role:</strong> {{ member.role }}</li>
                    </ul>
                    
                    <h3>Event Information:</h3>
                    <ul>
                        <li><strong>Event:</strong> {{ event.name }}</li>
                        <li><strong>Date:</strong> {{ event.start_date }} - {{ event.end_date }}</li>
                        <li><strong>Venue:</strong> {{ event.venue }}</li>
                    </ul>
                    
                    <h3>Booth Details:</h3>
                    <ul>
                        <li><strong>Booth Number:</strong> {{ booth.number }}</li>
                        <li><strong>Booth Type:</strong> {{ booth.type }}</li>
                        <li><strong>Location:</strong> {{ booth.location }}</li>
                    </ul>
                    
                    <p>We\'re excited to have you on board!</p>
                    
                    <p>Best regards,<br>
                    The {{ event.name }} Team</p>
                ',
                'trigger_type' => 'member_registration',
                'is_active' => true
            ]
        ];

        foreach ($templates as $templateData) {
            EmailTemplate::create([
                'event_id' => $event->id,
                'name' => $templateData['name'],
                'subject' => $templateData['subject'],
                'content' => $templateData['content'],
                'trigger_type' => $templateData['trigger_type'],
                'is_active' => $templateData['is_active']
            ]);
        }

        $this->command->info('Sample email templates created successfully!');
    }
}
