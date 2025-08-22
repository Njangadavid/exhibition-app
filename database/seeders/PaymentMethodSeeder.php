<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PaymentMethod;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing payment methods
        PaymentMethod::truncate();

        // Add Paystack
        PaymentMethod::create([
            'name' => 'Paystack',
            'code' => 'paystack',
            'type' => 'card',
            'description' => 'Accept payments via cards, bank transfers, and USSD with Paystack',
            'config' => [
                'public_key' => 'pk_live_1af24c675a36499d937b062d8c68ad3c23456',
                'secret_key' => '', // Will be set in admin panel
                'webhook_secret' => '', // Will be set in admin panel
                'test_mode' => false,
                'currency' => 'NGN',
                'supported_countries' => ['NG', 'GH', 'KE', 'ZA'],
            ],
            'is_active' => true,
            'is_default' => true,
            'sort_order' => 1,
            'icon' => 'bi bi-credit-card',
            'color' => '#0d6efd',
        ]);

        // Add Bank Transfer
        PaymentMethod::create([
            'name' => 'Bank Transfer',
            'code' => 'bank_transfer',
            'type' => 'bank_transfer',
            'description' => 'Direct bank transfer to our account',
            'config' => [
                'bank_name' => 'Exhibition Bank',
                'account_name' => 'Exhibition App',
                'account_number' => '1234567890',
                'sort_code' => '12-34-56',
                'swift_code' => 'EXHBGB22',
                'currency' => 'USD',
            ],
            'is_active' => true,
            'is_default' => false,
            'sort_order' => 2,
            'icon' => 'bi bi-bank',
            'color' => '#198754',
        ]);

        // Add Stripe (for future use)
        PaymentMethod::create([
            'name' => 'Stripe',
            'code' => 'stripe',
            'type' => 'card',
            'description' => 'Accept payments via cards with Stripe',
            'config' => [
                'public_key' => '',
                'secret_key' => '',
                'webhook_secret' => '',
                'test_mode' => true,
                'currency' => 'USD',
                'supported_countries' => ['US', 'CA', 'GB', 'AU'],
            ],
            'is_active' => false,
            'is_default' => false,
            'sort_order' => 3,
            'icon' => 'bi bi-stripe',
            'color' => '#6772e5',
        ]);

        // Add PayPal (for future use)
        PaymentMethod::create([
            'name' => 'PayPal',
            'code' => 'paypal',
            'type' => 'digital_wallet',
            'description' => 'Accept payments via PayPal',
            'config' => [
                'client_id' => '',
                'client_secret' => '',
                'webhook_id' => '',
                'test_mode' => true,
                'currency' => 'USD',
                'supported_countries' => ['US', 'CA', 'GB', 'AU', 'DE'],
            ],
            'is_active' => false,
            'is_default' => false,
            'sort_order' => 4,
            'icon' => 'bi bi-paypal',
            'color' => '#003087',
        ]);

        $this->command->info('Payment methods seeded successfully!');
        $this->command->info('Paystack is set as default with your live public key.');
        $this->command->info('Please configure secret key and webhook secret in admin panel.');
    }
}
