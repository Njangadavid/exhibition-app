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
            $table->decimal('booth_width_meters', 5, 2)->nullable()->after('label_color');
            $table->decimal('booth_height_meters', 5, 2)->nullable()->after('booth_width_meters');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('floorplan_items', function (Blueprint $table) {
            $table->dropColumn(['booth_width_meters', 'booth_height_meters']);
        });
    }
};
