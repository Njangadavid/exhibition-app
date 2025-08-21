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
        Schema::create('floorplan_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('floorplan_design_id')->constrained()->onDelete('cascade');
            $table->string('item_id')->unique(); // Unique identifier for each item
            $table->string('type'); // rectangle, circle, booth, table, etc.
            
            // Position and dimensions
            $table->decimal('x', 8, 2);
            $table->decimal('y', 8, 2);
            $table->decimal('width', 8, 2)->nullable();
            $table->decimal('height', 8, 2)->nullable();
            $table->decimal('radius', 8, 2)->nullable();
            $table->decimal('size', 8, 2)->nullable();
            $table->decimal('rotation', 8, 2)->default(0);
            
            // Styling properties
            $table->string('fill_color')->default('#e5e7eb');
            $table->string('stroke_color')->default('#374151');
            $table->integer('border_width')->default(2);
            $table->string('font_family')->nullable();
            $table->integer('font_size')->nullable();
            $table->string('text_color')->nullable();
            
            // Booking properties
            $table->boolean('bookable')->default(false);
            $table->integer('max_capacity')->default(1);
            $table->string('label')->nullable();
            $table->string('item_name')->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->enum('label_position', ['top', 'bottom', 'left', 'right', 'center'])->nullable();
            
            // Text-specific properties
            $table->text('text_content')->nullable();
            
            $table->timestamps();
            
            // Indexes for faster lookups
            $table->index('floorplan_design_id');
            $table->index('type');
            $table->index('bookable');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('floorplan_items');
    }
};