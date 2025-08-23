# Paystack Webhook Setup Guide

## Overview
This guide explains how to set up Paystack webhooks for automatic payment status updates in the exhibition booking system.

## Webhook URL
```
POST https://yourdomain.com/api/webhooks/paystack
```

## Benefits of Using Webhooks
1. **Real-time Updates**: Payment status changes are processed immediately
2. **Reliability**: Server-to-server communication is more reliable than browser callbacks
3. **Security**: Webhook signature verification ensures authenticity
4. **Automatic Processing**: No manual intervention required for payment updates

## Setup Steps

### 1. Configure Webhook in Paystack Dashboard
1. Log in to your Paystack Dashboard
2. Go to **Settings** → **Webhooks**
3. Add new webhook with the following details:
   - **URL**: `https://yourdomain.com/api/webhooks/paystack`
   - **Events to send**: Select all payment-related events
   - **Secret**: Generate a unique secret key

### 2. Update Environment Variables
Add the webhook secret to your `.env` file:
```env
PAYSTACK_WEBHOOK_SECRET=your_webhook_secret_here
```

### 3. Verify Configuration
The webhook will automatically:
- Verify the signature using the secret
- Process payment success/failure events
- Update booking statuses
- Log all activities for debugging

## Webhook Events Handled

### Payment Events
- `charge.success` - Payment completed successfully
- `charge.failed` - Payment failed

### Transfer Events
- `transfer.success` - Transfer completed
- `transfer.failed` - Transfer failed

## Security Features

### Signature Verification
- Uses HMAC-SHA512 for signature verification
- Compares received signature with expected signature
- Prevents unauthorized webhook calls

### Rate Limiting
- Webhook requests are limited to 60 per minute
- Prevents abuse and ensures system stability

## Testing Webhooks

### Using Paystack Test Mode
1. Use Paystack test credentials
2. Make test payments
3. Check webhook logs in Laravel logs
4. Verify database updates

### Local Development
For local development, you can use tools like:
- **ngrok** to expose local server
- **Paystack test mode** for testing
- **Laravel logs** for debugging

## Troubleshooting

### Common Issues
1. **Webhook not receiving**: Check URL accessibility and firewall settings
2. **Signature verification failed**: Verify webhook secret configuration
3. **Database errors**: Check database connection and table structure

### Debugging
- Check Laravel logs in `storage/logs/laravel.log`
- Verify webhook URL is accessible
- Test with Paystack's webhook testing tool

## Monitoring
- All webhook activities are logged
- Failed webhooks are logged with detailed error information
- Database transactions ensure data consistency

## Production Considerations
1. **HTTPS Required**: Webhook URL must use HTTPS in production
2. **Secret Management**: Store webhook secret securely
3. **Monitoring**: Set up alerts for webhook failures
4. **Backup**: Keep callback URLs as fallback for critical payments
