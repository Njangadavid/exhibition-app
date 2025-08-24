<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Payment;
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
                    <div class="value">' . htmlspecialchars($event->name) . '</div>
                </div>
                <div class="row">
                    <div class="label">Event Date:</div>
                    <div class="value">' . ($event->start_date ? $event->start_date->format('F d, Y') : 'TBD') . '</div>
                </div>
                <div class="row">
                    <div class="label">Venue:</div>
                    <div class="value">' . htmlspecialchars($event->venue ?? 'TBD') . '</div>
                </div>
            </div>
            
            <div class="section">
                <div class="section-title">Exhibitor Information</div>
                <div class="row">
                    <div class="label">Company Name:</div>
                    <div class="value">' . htmlspecialchars($booking->owner_details['company_name'] ?? '') . '</div>
                </div>
                <div class="row">
                    <div class="label">Contact Person:</div>
                    <div class="value">' . htmlspecialchars($booking->owner_details['name'] ?? '') . '</div>
                </div>
                <div class="row">
                    <div class="label">Email:</div>
                    <div class="value">' . htmlspecialchars($booking->owner_details['email'] ?? '') . '</div>
                </div>
                <div class="row">
                    <div class="label">Booth/Space:</div>
                    <div class="value">' . htmlspecialchars($booking->floorplanItem->label ?? '') . '</div>
                </div>
            </div>
            
            <div class="section">
                <div class="section-title">Payment Details</div>
                <div class="row">
                    <div class="label">Payment Method:</div>
                    <div class="value">' . ucfirst($payment->payment_method ?? 'Paystack') . '</div>
                </div>
                <div class="row">
                    <div class="label">Transaction ID:</div>
                    <div class="value">' . htmlspecialchars($payment->gateway_transaction_id ?? '') . '</div>
                </div>
                <div class="row">
                    <div class="label">Amount Paid:</div>
                    <div class="value amount">$' . number_format($payment->amount, 2) . '</div>
                </div>
                <div class="row">
                    <div class="label">Currency:</div>
                    <div class="value">' . strtoupper($payment->currency ?? 'USD') . '</div>
                </div>
                <div class="row">
                    <div class="label">Status:</div>
                    <div class="value">' . ucfirst($payment->status ?? 'Completed') . '</div>
                </div>
            </div>
            
            <div class="footer">
                <p>Thank you for your payment. This receipt serves as proof of your transaction.</p>
                <p>For any questions, please contact the event organizers.</p>
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
        
        return sprintf(
            'receipt_%s_%s_%s.pdf',
            $event->slug ?? 'event',
            $booking->booking_reference ?? 'booking',
            $date
        );
    }
}
