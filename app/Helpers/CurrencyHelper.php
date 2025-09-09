<?php

namespace App\Helpers;

class CurrencyHelper
{
    /**
     * Currency symbol mapping
     */
    private static $currencySymbols = [
        'USD' => '$',
        'KES' => 'KSh',
        'NGN' => '₦',
        'EUR' => '€',
        'GBP' => '£',
        'CAD' => 'C$',
        'AUD' => 'A$',
        'ZAR' => 'R',
        'GHS' => '₵',
        'TZS' => 'TSh',
        'UGX' => 'USh',
        'XOF' => 'CFA',
        'XAF' => 'FCFA',
    ];

    /**
     * Get currency symbol for a given currency code
     */
    public static function getSymbol(string $currency): string
    {
        return self::$currencySymbols[strtoupper($currency)] ?? strtoupper($currency);
    }

    /**
     * Format amount with currency symbol
     */
    public static function formatAmount(float $amount, string $currency): string
    {
        $symbol = self::getSymbol($currency);
        return $symbol . number_format($amount, 2);
    }

    /**
     * Get currency from event's default payment method
     */
    public static function getEventCurrency($event): string
    {
        $defaultPaymentMethod = $event->paymentMethods->where('is_default', true)->first();
        return $defaultPaymentMethod?->getConfig('currency', 'USD') ?? 'USD';
    }

    /**
     * Get currency from payment's event
     */
    public static function getPaymentCurrency($payment): string
    {
        if ($payment->event_id) {
            $event = \App\Models\Event::find($payment->event_id);
            if ($event) {
                return self::getEventCurrency($event);
            }
        }
        return $payment->currency ?? 'USD';
    }

    /**
     * Get currency symbol for event's default payment method
     */
    public static function getEventCurrencySymbol($event): string
    {
        $currency = self::getEventCurrency($event);
        return self::getSymbol($currency);
    }

    /**
     * Get currency symbol for payment
     */
    public static function getPaymentCurrencySymbol($payment): string
    {
        $currency = self::getPaymentCurrency($payment);
        return self::getSymbol($currency);
    }

    /**
     * Format amount with event's currency
     */
    public static function formatEventAmount(float $amount, $event): string
    {
        $currency = self::getEventCurrency($event);
        return self::formatAmount($amount, $currency);
    }

    /**
     * Format amount with payment's currency
     */
    public static function formatPaymentAmount(float $amount, $payment): string
    {
        $currency = self::getPaymentCurrency($payment);
        return self::formatAmount($amount, $currency);
    }
}
