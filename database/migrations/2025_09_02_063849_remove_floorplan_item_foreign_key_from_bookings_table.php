<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, try to find the actual constraint name
        $constraints = DB::select("
            SELECT CONSTRAINT_NAME 
            FROM information_schema.KEY_COLUMN_USAGE 
            WHERE TABLE_SCHEMA = DATABASE() 
            AND TABLE_NAME = 'bookings' 
            AND COLUMN_NAME = 'floorplan_item_id' 
            AND CONSTRAINT_NAME != 'PRIMARY'
        ");
        
        // Drop each foreign key constraint found
        foreach ($constraints as $constraint) {
            DB::statement("ALTER TABLE bookings DROP FOREIGN KEY {$constraint->CONSTRAINT_NAME}");
        }
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
