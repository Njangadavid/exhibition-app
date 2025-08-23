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
        Schema::create('email_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->foreignId('template_id')->constrained('email_templates')->onDelete('cascade');
            $table->string('recipient_email');
            $table->enum('recipient_type', ['owner', 'member']);
            $table->foreignId('booking_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('trigger_type');
            $table->enum('status', ['sent', 'failed', 'pending'])->default('pending');
            $table->timestamp('sent_at')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamps();
            
            // Indexes for better performance
            $table->index(['event_id', 'template_id']);
            $table->index(['recipient_email', 'status']);
            $table->index(['booking_id', 'trigger_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_logs');
    }
};
