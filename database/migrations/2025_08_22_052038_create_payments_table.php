<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->onDelete('cascade');
            $table->string('payment_reference')->unique();
            $table->enum('type', ['partial', 'full', 'refund', 'adjustment'])->default('partial');
            $table->enum('status', ['pending', 'processing', 'completed', 'failed', 'cancelled', 'refunded'])->default('pending');
            $table->enum('method', ['cash', 'card', 'bank_transfer', 'paypal', 'stripe', 'paystack', 'flutterwave', 'other'])->default('card');
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('NGN'); // Changed to NGN for Paystack
            $table->string('gateway')->nullable(); // paystack, stripe, paypal, etc.
            $table->string('gateway_transaction_id')->nullable(); // Paystack: trx_ref, Stripe: pi_xxx
            $table->string('gateway_reference')->nullable(); // Paystack: ref_xxx, Stripe: ch_xxx
            $table->string('gateway_authorization_code')->nullable(); // Paystack: AUTH_xxx
            $table->string('gateway_customer_code')->nullable(); // Paystack: CUS_xxx
            $table->string('gateway_bank_code')->nullable(); // Paystack: bank code for transfers
            $table->string('gateway_account_number')->nullable(); // Paystack: account number for transfers
            $table->string('gateway_account_name')->nullable(); // Paystack: account name for transfers
            $table->json('gateway_response')->nullable(); // Full gateway response
            $table->json('gateway_metadata')->nullable(); // Custom metadata for gateway
            $table->text('notes')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->timestamp('failed_at')->nullable();
            $table->string('failure_reason')->nullable();
            $table->string('failure_code')->nullable(); // Gateway-specific error codes
            $table->timestamps();
            
            // Indexes for better performance
            $table->index(['booking_id', 'status']);
            $table->index('payment_reference');
            $table->index('gateway_transaction_id');
            $table->index('gateway_reference');
            $table->index(['gateway', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
