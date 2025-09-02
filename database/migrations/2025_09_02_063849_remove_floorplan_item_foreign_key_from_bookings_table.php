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
        Schema::table('bookings', function (Blueprint $table) {
            // Drop the foreign key constraint
            $table->dropForeign(['floorplan_item_id']);
            
            // Keep the column but remove the foreign key constraint
            // The column will remain as a regular integer column
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // Re-add the foreign key constraint
            $table->foreign('floorplan_item_id')->references('id')->on('floorplan_items')->onDelete('cascade');
        });
    }
};
