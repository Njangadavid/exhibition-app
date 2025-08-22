# Paystack Integration Setup Guide

## Overview
This application now integrates with Paystack for secure payment processing. Users can make payments using various methods including cards, bank transfers, and mobile money.

## Configuration

### 1. Environment Variables
Add the following variables to your `.env` file:

```env
# Paystack Configuration
PAYSTACK_PUBLIC_KEY=pk_test_your_public_key_here
PAYSTACK_SECRET_KEY=sk_test_your_secret_key_here
PAYSTACK_WEBHOOK_SECRET=your_webhook_secret_here
```

### 2. Get Your Paystack Keys

#### Test Environment (Development)
1. Go to [Paystack Dashboard](https://dashboard.paystack.com/)
2. Sign up or log in to your account
3. Navigate to **Settings > API Keys & Webhooks**
4. Copy your **Test Public Key** and **Test Secret Key**

#### Live Environment (Production)
1. In the same dashboard, switch to **Live** mode
2. Copy your **Live Public Key** and **Live Secret Key**

### 3. Payment Method Configuration

#### In Admin Panel
1. Go to your event dashboard
2. Navigate to **Payment Methods**
3. Create a new payment method with:
   - **Name**: Paystack
   - **Code**: `paystack`
   - **Type**: `card` (or appropriate type)
   - **Description**: Secure payment processing via Paystack
   - **Is Active**: ✅ Checked
   - **Is Default**: ✅ Checked (if you want it as default)

## How It Works

### 1. Payment Flow
1. User clicks "Payment Now" button
2. System initializes Paystack transaction
3. User is redirected to Paystack payment page
4. User completes payment
5. Paystack redirects back to callback URL
6. System verifies payment and updates booking status

### 2. Supported Payment Methods
- **Credit/Debit Cards**: Visa, Mastercard, Verve
- **Bank Transfers**: Direct bank transfers
- **Mobile Money**: Various mobile money services
- **USSD**: Bank USSD codes

### 3. Currency Support
- **Default**: Nigerian Naira (NGN)
- **Other currencies**: Can be configured per event

## Security Features

### 1. Transaction Verification
- All payments are verified server-side
- Reference numbers are unique per transaction
- Metadata includes booking details for tracking

### 2. Webhook Support
- Configure webhooks for real-time payment updates
- Webhook secret ensures authenticity
- Automatic payment status updates

## Testing

### 1. Test Cards
Use these test card numbers for development:

```
Card Number: 4084 0840 8408 4081
Expiry: Any future date
CVV: Any 3 digits
```

### 2. Test Bank Accounts
- Use any valid Nigerian bank account number
- Transactions will be simulated

## Troubleshooting

### Common Issues

#### 1. "Paystack not configured" Error
- Check your `.env` file has the correct keys
- Ensure keys are not empty or have extra spaces
- Verify you're using the correct environment (test/live)

#### 2. Payment Verification Fails
- Check your server can reach Paystack API
- Verify your secret key is correct
- Check server logs for detailed error messages

#### 3. Callback Not Working
- Ensure callback URL is accessible
- Check route is properly defined
- Verify Paystack can reach your server

### Debug Mode
Enable detailed logging by checking:
- Laravel logs in `storage/logs/laravel.log`
- Paystack transaction responses
- Booking and payment status updates

## Production Checklist

Before going live:
- [ ] Switch to live Paystack keys
- [ ] Test complete payment flow
- [ ] Configure webhooks
- [ ] Set up SSL certificate
- [ ] Test with real payment methods
- [ ] Monitor payment success rates

## Support

For technical support:
- Check Paystack documentation: [docs.paystack.com](https://docs.paystack.com/)
- Review Laravel logs for errors
- Contact Paystack support for API issues
- Check application error logs for debugging info
