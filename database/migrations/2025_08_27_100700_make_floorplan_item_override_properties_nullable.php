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
        Schema::table('floorplan_items', function (Blueprint $table) {
            // Make all override properties nullable to support inheritance system
            $table->string('fill_color')->nullable()->change();
            $table->string('stroke_color')->nullable()->change();
            $table->integer('border_width')->nullable()->change();
            $table->string('font_family')->nullable()->change();
            $table->integer('font_size')->nullable()->change();
            $table->string('text_color')->nullable()->change();
            $table->string('label_position')->nullable()->change();
            $table->decimal('price', 10, 2)->nullable()->change();
            $table->integer('label_font_size')->nullable()->change();
            $table->string('label_background_color')->nullable()->change();
            $table->string('label_color')->nullable()->change();
            $table->decimal('booth_width_meters', 5, 2)->nullable()->change();
            $table->decimal('booth_height_meters', 5, 2)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('floorplan_items', function (Blueprint $table) {
            // Revert nullable properties back to their original state
            $table->string('fill_color')->nullable(false)->change();
            $table->string('stroke_color')->nullable(false)->change();
            $table->integer('border_width')->nullable(false)->change();
            $table->string('font_family')->nullable(false)->change();
            $table->integer('font_size')->nullable(false)->change();
            $table->string('text_color')->nullable(false)->change();
            $table->string('label_position')->nullable(false)->change();
            $table->decimal('price', 10, 2)->nullable(false)->change();
            $table->integer('label_font_size')->nullable(false)->change();
            $table->string('label_background_color')->nullable(false)->change();
            $table->string('label_color')->nullable(false)->change();
            $table->decimal('booth_width_meters', 5, 2)->nullable(false)->change();
            $table->decimal('booth_height_meters', 5, 2)->nullable(false)->change();
        });
    }
};
