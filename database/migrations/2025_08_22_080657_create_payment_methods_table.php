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
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->string('name'); // e.g., "Paystack", "Stripe", "Bank Transfer"
            $table->string('code'); // e.g., "paystack", "stripe", "bank_transfer"
            $table->string('type'); // e.g., "card", "bank_transfer", "digital_wallet"
            $table->text('description')->nullable();
            $table->json('config')->nullable(); // Gateway-specific configuration
            $table->boolean('is_active')->default(true);
            $table->boolean('is_default')->default(false);
            $table->integer('sort_order')->default(0);
            $table->string('icon')->nullable(); // Icon class or image path
            $table->string('color')->nullable(); // Brand color
            $table->timestamps();
            
            // Make code unique per event
            $table->unique(['event_id', 'code']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_methods');
    }
};
