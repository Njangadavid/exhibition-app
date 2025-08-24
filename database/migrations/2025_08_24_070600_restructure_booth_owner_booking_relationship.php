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
        // First, add booth_owner_id to bookings table if it doesn't exist
        if (!Schema::hasColumn('bookings', 'booth_owner_id')) {
            Schema::table('bookings', function (Blueprint $table) {
                $table->foreignId('booth_owner_id')->nullable()->after('id')->constrained()->onDelete('cascade');
            });
        }

        // Copy existing booth_owner_id from booth_owners to bookings
        $boothOwners = DB::table('booth_owners')->get();
        foreach ($boothOwners as $boothOwner) {
            if (isset($boothOwner->booking_id)) {
                DB::table('bookings')
                    ->where('id', $boothOwner->booking_id)
                    ->update(['booth_owner_id' => $boothOwner->id]);
            }
        }

        // Remove booking_id from booth_owners table if it exists
        if (Schema::hasColumn('booth_owners', 'booking_id')) {
            Schema::table('booth_owners', function (Blueprint $table) {
                // Check if foreign key exists before dropping
                $foreignKeys = DB::select("
                    SELECT CONSTRAINT_NAME 
                    FROM information_schema.KEY_COLUMN_USAGE 
                    WHERE TABLE_SCHEMA = DATABASE() 
                    AND TABLE_NAME = 'booth_owners' 
                    AND COLUMN_NAME = 'booking_id'
                    AND REFERENCED_TABLE_NAME IS NOT NULL
                ");
                
                if (!empty($foreignKeys)) {
                    $table->dropForeign(['booking_id']);
                }
                $table->dropColumn('booking_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Add booking_id back to booth_owners table
        Schema::table('booth_owners', function (Blueprint $table) {
            $table->foreignId('booking_id')->nullable()->after('id')->constrained()->onDelete('cascade');
        });

        // Copy booth_owner_id back to booth_owners.booking_id
        $bookings = DB::table('bookings')->whereNotNull('booth_owner_id')->get();
        foreach ($bookings as $booking) {
            DB::table('booth_owners')
                ->where('id', $booking->booth_owner_id)
                ->update(['booking_id' => $booking->id]);
        }

        // Remove booth_owner_id from bookings table
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropForeign(['booth_owner_id']);
            $table->dropColumn('booth_owner_id');
        });
    }
};
