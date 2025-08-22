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
        Schema::table('payments', function (Blueprint $table) {
            // Add Paystack-specific fields for better payment gateway integration
            $table->string('gateway_reference')->nullable()->after('gateway_transaction_id');
            $table->string('gateway_authorization_code')->nullable()->after('gateway_reference');
            $table->string('gateway_customer_code')->nullable()->after('gateway_authorization_code');
            $table->string('gateway_bank_code')->nullable()->after('gateway_customer_code');
            $table->string('gateway_account_number')->nullable()->after('gateway_bank_code');
            $table->string('gateway_account_name')->nullable()->after('gateway_account_number');
            $table->json('gateway_metadata')->nullable()->after('gateway_response');
            $table->string('failure_code')->nullable()->after('failure_reason');
            
            // Update method enum to include Paystack and other African payment gateways
            $table->enum('method', ['cash', 'card', 'bank_transfer', 'paypal', 'stripe', 'paystack', 'flutterwave', 'other'])->default('card')->change();
            
            // Change default currency to NGN for Paystack
            $table->string('currency', 3)->default('NGN')->change();
            
            // Add indexes for better performance
            $table->index('gateway_reference');
            $table->index(['gateway', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            // Remove Paystack-specific fields
            $table->dropColumn([
                'gateway_reference',
                'gateway_authorization_code',
                'gateway_customer_code',
                'gateway_bank_code',
                'gateway_account_number',
                'gateway_account_name',
                'gateway_metadata',
                'failure_code'
            ]);
            
            // Remove added indexes
            $table->dropIndex(['gateway', 'status']);
            $table->dropIndex(['gateway_reference']);
            
            // Revert method enum and currency defaults
            $table->enum('method', ['cash', 'card', 'bank_transfer', 'paypal', 'stripe', 'other'])->default('card')->change();
            $table->string('currency', 3)->default('USD')->change();
        });
    }
};
