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
        // First, add access_token columns to booth_owners table
        Schema::table('booth_owners', function (Blueprint $table) {
            $table->string('access_token')->nullable()->after('form_responses');
            $table->timestamp('access_token_expires_at')->nullable()->after('access_token');
        });

        // Copy access tokens from existing bookings to booth owners
        $boothOwners = DB::table('booth_owners')->get();
        foreach ($boothOwners as $boothOwner) {
            $booking = DB::table('bookings')->where('id', $boothOwner->booking_id)->first();
            if ($booking && $booking->access_token) {
                DB::table('booth_owners')
                    ->where('id', $boothOwner->id)
                    ->update([
                        'access_token' => $booking->access_token,
                        'access_token_expires_at' => $booking->access_token_expires_at
                    ]);
            }
        }

        // Remove access_token columns from bookings table
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['access_token', 'access_token_expires_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Add access_token columns back to bookings table
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('access_token')->nullable()->after('booth_owner_id');
            $table->timestamp('access_token_expires_at')->nullable()->after('access_token');
        });

        // Copy access tokens back from booth owners to bookings
        $boothOwners = DB::table('booth_owners')->get();
        foreach ($boothOwners as $boothOwner) {
            if ($boothOwner->access_token) {
                DB::table('bookings')
                    ->where('id', $boothOwner->booking_id)
                    ->update([
                        'access_token' => $boothOwner->access_token,
                        'access_token_expires_at' => $boothOwner->access_token_expires_at
                    ]);
            }
        }

        // Remove access_token columns from booth_owners table
        Schema::table('booth_owners', function (Blueprint $table) {
            $table->dropColumn(['access_token', 'access_token_expires_at']);
        });
    }
};
