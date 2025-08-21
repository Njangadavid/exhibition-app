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
        Schema::create('floorplan_designs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->string('name')->default('Main Floorplan');
            $table->string('canvas_size')->default('800x600');
            $table->integer('canvas_width')->default(800);
            $table->integer('canvas_height')->default(600);
            
            // Color scheme
            $table->string('bg_color')->default('#ffffff');
            $table->string('fill_color')->default('#e5e7eb');
            $table->string('stroke_color')->default('#374151');
            $table->string('text_color')->default('#111827');
            
            // Styling options
            $table->integer('border_width')->default(2);
            $table->string('font_family')->default('Arial');
            $table->integer('font_size')->default(12);
            
            // Grid settings
            $table->integer('grid_size')->default(20);
            $table->string('grid_color')->default('#e5e7eb');
            $table->boolean('show_grid')->default(true);
            $table->boolean('snap_to_grid')->default(true);
            
            // Booking properties
            $table->integer('default_booth_capacity')->default(5);
            $table->string('label_prefix', 3)->default('A');
            $table->integer('starting_label_number')->default(1);
            $table->enum('default_label_position', ['top', 'bottom', 'left', 'right', 'center'])->default('top');
            $table->decimal('default_price', 10, 2)->default(100.00);
            $table->boolean('enable_auto_labeling')->default(true);
            
            $table->timestamps();
            
            // Index for faster lookups
            $table->index('event_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('floorplan_designs');
    }
};