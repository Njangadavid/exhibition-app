# Pesapal Integration Documentation

## Overview

This document describes the Pesapal payment gateway integration for the Exhibition App. Pesapal is a popular payment gateway in East Africa, particularly in Kenya, Uganda, and Tanzania.

## Features Implemented

### 1. PesapalService Class
- **Location**: `app/Services/PesapalService.php`
- **Purpose**: Handles all Pesapal API interactions
- **Key Methods**:
  - `initializePayment()` - Initialize a new payment
  - `getPaymentStatus()` - Check payment status
  - `verifyPayment()` - Verify payment via IPN
  - `generateReceipt()` - Generate payment receipt
  - `getAccessToken()` - Get API access token

### 2. Payment Method Configuration
- **Location**: Payment methods admin pages
- **Fields Added**:
  - Consumer Key
  - Consumer Secret
  - Test Mode toggle
  - Currency selection

### 3. PesapalController
- **Location**: `app/Http/Controllers/PesapalController.php`
- **Purpose**: Handles HTTP requests for Pesapal payments
- **Key Methods**:
  - `initializePayment()` - Initialize payment from frontend
  - `handleCallback()` - Handle payment callback
  - `handleCancel()` - Handle payment cancellation
  - `handleIPN()` - Handle Instant Payment Notifications
  - `getPaymentStatus()` - Get payment status

## API Endpoints

### Payment Initialization
```
POST /pesapal/{event}/initialize
```
**Body**:
```json
{
    "amount": 1000,
    "email": "user@example.com",
    "first_name": "John",
    "last_name": "Doe",
    "phone_number": "+254712345678",
    "reference": "ORDER_123",
    "description": "Payment for Event"
}
```

### Payment Callback
```
GET /pesapal/{event}/callback?OrderTrackingId=xxx&OrderMerchantReference=xxx
```

### Payment Cancellation
```
GET /pesapal/{event}/cancel
```

### IPN (Instant Payment Notification)
```
POST /pesapal/{event}/ipn
```

### Payment Status Check
```
GET /pesapal/{event}/status?order_tracking_id=xxx
```

## Configuration

### 1. Payment Method Setup
1. Go to Event → Payment Methods
2. Click "Add Payment Method"
3. Select "Pesapal" as payment type
4. Fill in:
   - **Consumer Key**: Your Pesapal consumer key
   - **Consumer Secret**: Your Pesapal consumer secret
   - **Currency**: Select appropriate currency (KES, USD, etc.)
   - **Test Mode**: Enable for testing

### 2. Environment Variables
Add to your `.env` file:
```env
PESAPAL_CONSUMER_KEY=your_consumer_key
PESAPAL_CONSUMER_SECRET=your_consumer_secret
PESAPAL_ENVIRONMENT=test # or 'live'
```

## Usage Examples

### 1. Initialize Payment (Frontend)
```javascript
// Example frontend code
const initializePesapalPayment = async (paymentData) => {
    try {
        const response = await fetch(`/pesapal/${eventId}/initialize`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(paymentData)
        });

        const result = await response.json();
        
        if (result.success) {
            // Redirect to Pesapal payment page
            window.location.href = result.redirect_url;
        } else {
            console.error('Payment initialization failed:', result.message);
        }
    } catch (error) {
        console.error('Error:', error);
    }
};
```

### 2. Check Payment Status
```javascript
const checkPaymentStatus = async (orderTrackingId) => {
    try {
        const response = await fetch(`/pesapal/${eventId}/status?order_tracking_id=${orderTrackingId}`);
        const result = await response.json();
        
        if (result.success) {
            console.log('Payment status:', result.status);
        }
    } catch (error) {
        console.error('Error checking status:', error);
    }
};
```

### 3. Backend Usage
```php
use App\Services\PesapalService;
use App\Models\PaymentMethod;

// Get Pesapal payment method
$paymentMethod = PaymentMethod::where('event_id', $eventId)
    ->where('type', 'pesapal')
    ->where('is_active', true)
    ->first();

// Initialize service
$pesapalService = new PesapalService();
$pesapalService->initializeWithPaymentMethod($paymentMethod);

// Initialize payment
$paymentData = [
    'amount' => 1000,
    'currency' => 'KES',
    'email' => 'user@example.com',
    'reference' => 'ORDER_123',
    'callback_url' => route('pesapal.callback', $event),
    // ... other required fields
];

$result = $pesapalService->initializePayment($paymentData);
```

## Testing

### 1. Unit Tests
Run the Pesapal service tests:
```bash
php artisan test tests/Unit/PesapalServiceTest.php
```

### 2. Test Credentials
For testing, use Pesapal's sandbox environment:
- **Test Consumer Key**: Available from Pesapal developer portal
- **Test Consumer Secret**: Available from Pesapal developer portal
- **Test Environment**: Set `test_mode` to `true` in payment method config

### 3. Test Payment Flow
1. Create a test payment method with test credentials
2. Initialize a payment with test data
3. Use Pesapal's test payment methods
4. Verify callback and IPN handling

## Security Considerations

1. **API Keys**: Never expose consumer secret in frontend code
2. **Validation**: Always validate payment responses
3. **Logging**: Log all payment activities for audit
4. **HTTPS**: Ensure all payment endpoints use HTTPS
5. **IPN Verification**: Always verify IPN signatures

## Error Handling

The service includes comprehensive error handling:
- API connection errors
- Invalid credentials
- Payment failures
- Network timeouts
- Invalid responses

All errors are logged with context for debugging.

## Support

For Pesapal-specific issues:
- **Documentation**: https://developer.pesapal.com
- **Support**: Contact Pesapal support team
- **Status**: Check Pesapal status page

## Changelog

### v1.0.0 (Initial Implementation)
- Basic Pesapal service implementation
- Payment method configuration
- Payment initialization and verification
- IPN handling
- Receipt generation
- Unit tests
