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
        Schema::create('email_templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('subject');
            $table->longText('content');
            $table->enum('trigger_type', [
                'owner_registration',
                'member_registration', 
                'payment_successful',
                'booth_confirmed',
                'event_reminder'
            ]);
            $table->json('conditions')->nullable(); // Store conditions like payment amount, booth type, etc.
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            // Indexes for better performance
            $table->index(['event_id', 'trigger_type']);
            $table->index(['event_id', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::runDown();
    }
};
