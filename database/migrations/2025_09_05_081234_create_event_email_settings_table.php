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
        Schema::create('event_email_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->string('smtp_host');
            $table->integer('smtp_port')->default(587);
            $table->string('smtp_username');
            $table->text('smtp_password'); // Encrypted
            $table->string('smtp_encryption')->default('tls'); // tls, ssl, or null
            $table->string('send_as_email');
            $table->string('send_as_name')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            // Ensure one email setting per event
            $table->unique('event_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_email_settings');
    }
};
