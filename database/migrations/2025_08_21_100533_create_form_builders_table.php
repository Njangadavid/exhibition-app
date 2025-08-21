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
        Schema::create('form_builders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->json('settings')->nullable(); // Form-wide settings like styling, notifications, etc.
            
            // Form behavior settings
            $table->boolean('allow_multiple_submissions')->default(false);
            $table->boolean('require_login')->default(false);
            $table->boolean('send_confirmation_email')->default(true);
            $table->text('confirmation_message')->nullable();
            $table->text('redirect_url')->nullable();
            
            // Form appearance
            $table->string('submit_button_text')->default('Submit');
            $table->string('theme_color')->default('#007bff');
            
            $table->timestamps();
            
            // Index for faster lookups
            $table->index('event_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form_builders');
    }
};