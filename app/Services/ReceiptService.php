<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Helpers\CurrencyHelper;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Route;

class ReceiptService
{
    /**
     * Generate a PDF receipt for a payment
     */
    public function generateReceiptPdf(Payment $payment): string
    {
        try {
            $booking = $payment->booking;
            $event = $booking->event;
            
            // Create HTML content for the receipt
            $html = $this->generateReceiptHtml($payment, $booking, $event);
            
            // Generate PDF using Laravel DOMPDF facade
            $pdf = Pdf::loadHTML($html);
            $pdf->setPaper('A4', 'portrait');
            
            // Generate PDF content
            $pdfContent = $pdf->output();
            
            Log::info('Receipt PDF generated successfully', [
                'payment_id' => $payment->id,
                'booking_id' => $booking->id
            ]);
            
            return $pdfContent;
            
        } catch (\Exception $e) {
            Log::error('Failed to generate receipt PDF', [
                'payment_id' => $payment->id ?? 'unknown',
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }
    
    /**
     * Generate HTML content for the receipt
     */
    private function generateReceiptHtml(Payment $payment, Booking $booking, $event): string
    {
        $receiptNumber = 'RCP-' . str_pad($payment->id, 6, '0', STR_PAD_LEFT);
        $paymentDate = $payment->created_at->format('F d, Y \a\t g:i A');
        
        // Get payment method details
        $paymentMethod = $payment->paymentMethod;
        $gatewayName = $paymentMethod ? $paymentMethod->getGatewayName() : ucfirst($payment->gateway ?? 'Unknown');
        
        // Format amount with proper currency
        $formattedAmount = CurrencyHelper::formatPaymentAmount($payment->amount, $payment);
        $currency = CurrencyHelper::getPaymentCurrency($payment);
        
        // Get gateway-specific transaction details
        $transactionId = $this->getGatewayTransactionId($payment);
        $gatewayResponse = $this->getGatewayResponse($payment);
        
        // Sanitize all text content to prevent UTF-8 issues
        $eventName = $this->sanitizeText($event->name ?? '');
        $eventDate = $event->start_date ? $event->start_date->format('F d, Y') : 'TBD';
        $venue = $this->sanitizeText($event->venue ?? 'TBD');
        $companyName = $this->sanitizeText($booking->boothOwner->form_responses['company_name'] ?? '');
        $contactName = $this->sanitizeText($booking->boothOwner->form_responses['name'] ?? '');
        $email = $this->sanitizeText($booking->boothOwner->form_responses['email'] ?? '');
        $boothSpace = $this->sanitizeText($booking->floorplanItem->label ?? '');
        $boothName = $this->sanitizeText($booking->boothOwner->form_responses['booth_name'] ?? '');
        $gatewayNameSafe = $this->sanitizeText($gatewayName);
        $transactionIdSafe = $this->sanitizeText($transactionId);
        $formattedAmountSafe = $this->sanitizeText($formattedAmount);
        $currencySafe = $this->sanitizeText($currency);
        $statusSafe = $this->sanitizeText(ucfirst($payment->status ?? 'Completed'));
        $gatewayResponseSafe = $this->sanitizeText($gatewayResponse ?? '');
        
        return '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="utf-8">
            <title>Payment Receipt</title>
            <style>
                body { font-family: Arial, sans-serif; margin: 0; padding: 20px; color: #333; }
                .header { text-align: center; border-bottom: 2px solid #007bff; padding-bottom: 20px; margin-bottom: 30px; }
                .receipt-title { font-size: 24px; color: #007bff; margin: 0; }
                .receipt-number { font-size: 16px; color: #666; margin: 10px 0; }
                .section { margin-bottom: 25px; }
                .section-title { font-size: 18px; font-weight: bold; color: #007bff; margin-bottom: 15px; border-bottom: 1px solid #eee; padding-bottom: 5px; }
                .row { display: flex; margin-bottom: 10px; }
                .label { font-weight: bold; width: 150px; }
                .value { flex: 1; }
                .amount { font-size: 20px; font-weight: bold; color: #28a745; }
                .footer { text-align: center; margin-top: 40px; padding-top: 20px; border-top: 1px solid #eee; color: #666; font-size: 12px; }
                .logo { max-width: 200px; max-height: 80px; }
                .gateway-badge { display: inline-block; background: #007bff; color: white; padding: 4px 8px; border-radius: 4px; font-size: 12px; margin-left: 10px; }
            </style>
        </head>
        <body>
            <div class="header">
                <h1 class="receipt-title">Payment Receipt</h1>
                <div class="receipt-number">Receipt #' . $receiptNumber . '</div>
                <div>Date: ' . $paymentDate . '</div>
            </div>
            
            <div class="section">
                <div class="section-title">Event Information</div>
                <div class="row">
                    <div class="label">Event Name:</div>
                    <div class="value">' . $eventName . '</div>
                </div>
                <div class="row">
                    <div class="label">Event Date:</div>
                    <div class="value">' . $eventDate . '</div>
                </div>
                <div class="row">
                    <div class="label">Venue:</div>
                    <div class="value">' . $venue . '</div>
                </div>
            </div>
            
            <div class="section">
                <div class="section-title">Exhibitor Information</div>
                <div class="row">
                    <div class="label">Company Name:</div>
                    <div class="value">' . $companyName . '</div>
                </div>
                <div class="row">
                    <div class="label">Contact Person:</div>
                    <div class="value">' . $contactName . '</div>
                </div>
                <div class="row">
                    <div class="label">Email:</div>
                    <div class="value">' . $email . '</div>
                </div>
                <div class="row">
                    <div class="label">Booth Space:</div>
                    <div class="value">' . $boothSpace . '</div>
                </div>
                ' . ($boothName ? '
                <div class="row">
                    <div class="label">Booth Name:</div>
                    <div class="value">' . $boothName . '</div>
                </div>' : '') . '
            </div>
            
            <div class="section">
                <div class="section-title">Payment Details</div>
                <div class="row">
                    <div class="label">Payment Gateway:</div>
                    <div class="value">' . $gatewayNameSafe . ' <span class="gateway-badge">' . strtoupper($payment->gateway ?? 'UNKNOWN') . '</span></div>
                </div>
                <div class="row">
                    <div class="label">Transaction ID:</div>
                    <div class="value">' . $transactionIdSafe . '</div>
                </div>
                <div class="row">
                    <div class="label">Amount Paid:</div>
                    <div class="value amount">' . $formattedAmountSafe . '</div>
                </div>
                <div class="row">
                    <div class="label">Currency:</div>
                    <div class="value">' . strtoupper($currencySafe) . '</div>
                </div>
                <div class="row">
                    <div class="label">Status:</div>
                    <div class="value">' . $statusSafe . '</div>
                </div>
                ' . ($gatewayResponseSafe ? '
                <div class="row">
                    <div class="label">Gateway Response:</div>
                    <div class="value">' . $gatewayResponseSafe . '</div>
                </div>' : '') . '
            </div>
            
            <div class="footer">
                <p>Thank you for your payment. This receipt serves as proof of your transaction.</p>
                <p>For any questions, please contact the event organizers.</p>
                <p><strong>Generated by:</strong> ' . $gatewayNameSafe . ' Payment System</p>
            </div>
        </body>
        </html>';
    }
    
    /**
     * Get the filename for the receipt
     */
    public function getReceiptFilename(Payment $payment): string
    {
        $booking = $payment->booking;
        $event = $booking->event;
        $date = $payment->created_at->format('Y-m-d');
        $gateway = strtolower($payment->gateway ?? 'unknown');
        
        return sprintf(
            'receipt_%s_%s_%s_%s.pdf',
            $event->slug ?? 'event',
            $booking->booking_reference ?? 'booking',
            $gateway,
            $date
        );
    }
    
    /**
     * Get gateway-specific transaction ID
     */
    private function getGatewayTransactionId(Payment $payment): string
    {
        switch ($payment->gateway) {
            case 'paystack':
                return $payment->gateway_reference ?? $payment->gateway_transaction_id ?? 'N/A';
                
            case 'pesapal':
                // Extract from gateway response if available
                if ($payment->gateway_response && is_array($payment->gateway_response)) {
                    return $payment->gateway_response['order_tracking_id'] ?? 
                           $payment->gateway_response['order_id'] ?? 
                           $payment->gateway_reference ?? 'N/A';
                }
                return $payment->gateway_reference ?? 'N/A';
                
            default:
                return $payment->gateway_reference ?? $payment->gateway_transaction_id ?? 'N/A';
        }
    }
    
    /**
     * Get gateway-specific response message
     */
    private function getGatewayResponse(Payment $payment): ?string
    {
        if (!$payment->gateway_response || !is_array($payment->gateway_response)) {
            return null;
        }
        
        switch ($payment->gateway) {
            case 'paystack':
                return $payment->gateway_response['message'] ?? 
                       $payment->gateway_response['status'] ?? null;
                       
            case 'pesapal':
                return $payment->gateway_response['payment_status_description'] ?? 
                       $payment->gateway_response['payment_status'] ?? 
                       $payment->gateway_response['status'] ?? null;
                       
            default:
                return $payment->gateway_response['message'] ?? 
                       $payment->gateway_response['status'] ?? null;
        }
    }
    
    /**
     * Sanitize text to prevent UTF-8 encoding issues
     */
    private function sanitizeText(?string $text): string
    {
        if (empty($text)) {
            return '';
        }
        
        // Remove or replace problematic characters
        $text = mb_convert_encoding($text, 'UTF-8', 'UTF-8');
        
        // Remove any remaining non-UTF-8 characters
        $text = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $text);
        
        // Ensure proper UTF-8 encoding
        if (!mb_check_encoding($text, 'UTF-8')) {
            $text = mb_convert_encoding($text, 'UTF-8', 'auto');
        }
        
        // Trim and return
        return trim($text);
    }
}
