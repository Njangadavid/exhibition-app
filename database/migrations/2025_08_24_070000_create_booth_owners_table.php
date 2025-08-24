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
        Schema::create('booth_owners', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->onDelete('cascade');
            $table->string('qr_code', 6)->unique(); // Format: "1" + 5 digits
            $table->json('form_responses'); // Store all form field responses from form builder
            $table->timestamps();
            
            // Indexes for better performance
            $table->index(['booking_id']);
            $table->index('qr_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booth_owners');
    }
};
