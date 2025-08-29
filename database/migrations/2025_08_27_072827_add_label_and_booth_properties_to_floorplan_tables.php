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
        // Add new properties to floorplan_designs table
        Schema::table('floorplan_designs', function (Blueprint $table) {
            $table->decimal('default_booth_width_meters', 5, 2)->default(3.0)->after('default_price');
            $table->decimal('default_booth_height_meters', 5, 2)->default(2.0)->after('default_booth_width_meters');
            $table->integer('default_label_font_size')->default(12)->after('default_booth_height_meters');
            $table->string('default_label_background_color', 7)->default('#ffffff')->after('default_label_font_size');
            $table->string('default_label_color', 7)->default('#000000')->after('default_label_background_color');

        });

        // Add new properties to floorplan_items table
        Schema::table('floorplan_items', function (Blueprint $table) {
            $table->integer('label_font_size')->nullable()->after('text_content');
            $table->string('label_background_color', 7)->nullable()->after('label_font_size');
            $table->string('label_color', 7)->nullable()->after('label_background_color');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove properties from floorplan_designs table
        Schema::table('floorplan_designs', function (Blueprint $table) {
            $table->dropColumn([
                'default_booth_width_meters',
                'default_booth_height_meters',
                'default_label_font_size',
                'default_label_background_color',
                'default_label_color',

            ]);
        });

        // Remove properties from floorplan_items table
        Schema::table('floorplan_items', function (Blueprint $table) {
            $table->dropColumn([
                'label_font_size',
                'label_background_color',
                'label_color'
            ]);
        });
    }
};
