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
        Schema::create('form_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('form_builder_id')->constrained()->onDelete('cascade');
            $table->string('field_id')->unique(); // Unique identifier for the field
            $table->string('label');
            $table->string('type'); // text, email, phone, textarea, select, checkbox, radio, file, date, number
            $table->integer('sort_order')->default(0);
            $table->boolean('required')->default(false);
            $table->text('help_text')->nullable();
            $table->text('placeholder')->nullable();
            
            // Field validation
            $table->json('validation_rules')->nullable(); // Store validation rules as JSON
            $table->text('error_message')->nullable();
            
            // Field options (for select, radio, checkbox)
            $table->json('options')->nullable(); // Store options as JSON array
            $table->text('default_value')->nullable();
            
            // Field styling and behavior
            $table->boolean('show_label')->default(true);
            $table->string('css_class')->nullable();
            $table->string('width')->default('full'); // full, half, third, quarter
            
            // Conditional logic
            $table->json('conditional_logic')->nullable(); // Show/hide based on other fields
            
            $table->timestamps();
            
            // Indexes for faster lookups
            $table->index('form_builder_id');
            $table->index('sort_order');
            $table->index('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form_fields');
    }
};