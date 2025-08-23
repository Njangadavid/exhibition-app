# Email Communication System

## Overview
The Email Communication System allows event organizers to create automated email templates that are triggered by specific events in the booking process. The system supports merge fields, conditional sending, and comprehensive logging.

## Features

### 🎯 **Trigger Types**
- **Owner Registration**: Sent when a new booth owner registers
- **Member Registration**: Sent when booth members are added
- **Payment Successful**: Sent when payment is completed
- **Booth Confirmed**: Sent when booth is confirmed
- **Event Reminder**: Sent as event reminders

### 🔧 **Template Management**
- Rich text editor (TinyMCE) for professional email design
- Merge field insertion with one-click buttons
- Template cloning for quick creation
- Active/inactive status management
- Template testing with sample data

### 📊 **Merge Fields**
The system automatically provides relevant merge fields based on the trigger type:

#### Event Fields
- `{{ event.name }}` - Event name
- `{{ event.start_date }}` - Event start date
- `{{ event.end_date }}` - Event end date
- `{{ event.venue }}` - Event venue

#### Owner Fields
- `{{ owner.name }}` - Owner name
- `{{ owner.email }}` - Owner email
- `{{ owner.company }}` - Company name
- `{{ owner.phone }}` - Phone number

#### Booth Fields
- `{{ booth.number }}` - Booth number
- `{{ booth.type }}` - Booth type
- `{{ booth.price }}` - Booth price
- `{{ booth.location }}` - Booth location

#### Member Fields (for member templates)
- `{{ member.name }}` - Member name
- `{{ member.email }}` - Member email
- `{{ member.role }}` - Member role

#### Payment Fields (for payment templates)
- `{{ payment.amount }}` - Payment amount
- `{{ payment.method }}` - Payment method
- `{{ payment.date }}` - Payment date
- `{{ payment.reference }}` - Payment reference

## How to Use

### 1. **Access Email Templates**
Navigate to your event dashboard and click on "Email Templates" in the navigation menu.

### 2. **Create a New Template**
1. Click "Create Template" button
2. Fill in template details:
   - **Name**: Descriptive name for the template
   - **Trigger Type**: When this email should be sent
   - **Subject**: Email subject line (supports merge fields)
   - **Content**: Email body (supports merge fields and HTML)
   - **Active Status**: Whether to activate immediately

### 3. **Use Merge Fields**
- Click on any merge field button in the right panel
- The field will be inserted at your cursor position
- Merge fields are automatically replaced with actual data when emails are sent

### 4. **Test Your Template**
- Use the "Test Template" button to see how your email looks
- Test with sample data to ensure merge fields work correctly
- Preview both subject and content with processed data

### 5. **Clone Templates**
- Use the "Clone" button to create copies of existing templates
- Perfect for creating variations or testing changes
- Cloned templates start as inactive

### 6. **Manage Templates**
- **View**: See template details and preview
- **Edit**: Modify existing templates
- **Activate/Deactivate**: Control when templates are used
- **Delete**: Remove unused templates

## Technical Implementation

### **Database Structure**
- `email_templates`: Stores template configuration
- `email_logs`: Tracks all sent emails and their status

### **Automatic Email Sending**
The system automatically sends emails when:
- New bookings are created (owner registration)
- Members are added to booths
- Payments are completed
- Booths are confirmed

### **Email Service Integration**
Currently logs emails to the application log. To send actual emails:
1. Configure Laravel Mail settings in `.env`
2. Update `EmailCommunicationService::sendEmail()` method
3. Use services like SendGrid, Mailgun, or SMTP

## Best Practices

### **Template Design**
- Keep subject lines clear and engaging
- Use merge fields for personalization
- Test templates before activating
- Use HTML formatting for professional appearance

### **Content Guidelines**
- Welcome emails: Friendly, informative, include all relevant details
- Payment confirmations: Clear payment details, next steps
- Member invitations: Role clarification, event context
- Reminders: Actionable information, clear deadlines

### **Testing**
- Always test templates with sample data
- Verify merge fields display correctly
- Check email formatting across devices
- Test both active and inactive states

## Troubleshooting

### **Common Issues**
1. **Merge fields not working**: Ensure proper syntax `{{ field.name }}`
2. **Templates not sending**: Check if template is active
3. **Emails not received**: Verify email service configuration
4. **Formatting issues**: Use HTML tags consistently

### **Debug Mode**
- Check application logs for email sending errors
- Use template testing feature to verify merge field processing
- Verify trigger conditions are met

## Future Enhancements

### **Planned Features**
- Email scheduling and delays
- Advanced conditional logic
- Email analytics and open rates
- Template categories and organization
- Bulk email sending
- Email preview in different email clients

### **Integration Possibilities**
- SMS notifications
- Push notifications
- Social media integration
- CRM system integration

## Support

For technical support or feature requests, please contact the development team or create an issue in the project repository.

---

**Note**: This system is designed to be extensible. Additional trigger types, merge fields, and email services can be easily added by extending the existing architecture.

