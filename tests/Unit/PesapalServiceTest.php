<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\PesapalService;
use App\Models\PaymentMethod;
use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PesapalServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_pesapal_service_can_be_instantiated()
    {
        $service = new PesapalService();
        $this->assertInstanceOf(PesapalService::class, $service);
    }

    public function test_pesapal_service_initialization_with_payment_method()
    {
        // Create a test user and event
        $user = User::factory()->create();
        $event = Event::factory()->create(['owner_id' => $user->id]);

        // Create a payment method with Pesapal configuration
        $paymentMethod = PaymentMethod::create([
            'event_id' => $event->id,
            'name' => 'Pesapal Test',
            'code' => 'pesapal_test',
            'type' => 'pesapal',
            'description' => 'Test Pesapal payment method',
            'config' => [
                'consumer_key' => 'test_consumer_key',
                'consumer_secret' => 'test_consumer_secret',
                'test_mode' => true,
                'currency' => 'KES'
            ],
            'is_active' => true,
            'is_default' => false,
            'sort_order' => 0
        ]);

        $service = new PesapalService();
        $service->initializeWithPaymentMethod($paymentMethod);

        $this->assertTrue($service->isConfiguredWithPaymentMethod($paymentMethod));
    }

    public function test_pesapal_service_amount_conversion()
    {
        $service = new PesapalService();
        
        // Test KES conversion (should multiply by 100)
        $reflection = new \ReflectionClass($service);
        $method = $reflection->getMethod('convertToSmallestUnit');
        $method->setAccessible(true);

        $result = $method->invoke($service, 100, 'KES');
        $this->assertEquals(10000, $result);

        // Test USD conversion (should multiply by 100)
        $result = $method->invoke($service, 50, 'USD');
        $this->assertEquals(5000, $result);

        // Test JPY conversion (no conversion needed)
        $result = $method->invoke($service, 1000, 'JPY');
        $this->assertEquals(1000, $result);
    }

    public function test_pesapal_service_environment_detection()
    {
        // Create a test user and event
        $user = User::factory()->create();
        $event = Event::factory()->create(['owner_id' => $user->id]);

        // Test mode payment method
        $testPaymentMethod = PaymentMethod::create([
            'event_id' => $event->id,
            'name' => 'Pesapal Test Mode',
            'code' => 'pesapal_test_mode',
            'type' => 'pesapal',
            'config' => [
                'consumer_key' => 'test_key',
                'consumer_secret' => 'test_secret',
                'test_mode' => true
            ],
            'is_active' => true
        ]);

        $service = new PesapalService();
        $service->initializeWithPaymentMethod($testPaymentMethod);

        // The service should use test environment URL
        $this->assertTrue($testPaymentMethod->isTestMode());
        $this->assertEquals('test', $testPaymentMethod->getEnvironment());
    }
}
