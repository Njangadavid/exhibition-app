<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Get all existing bookings with member_details
        $bookings = DB::table('bookings')
            ->whereNotNull('member_details')
            ->where('member_details', '!=', '[]')
            ->get();

        foreach ($bookings as $booking) {
            try {
                // Parse the member_details JSON
                $memberDetails = json_decode($booking->member_details, true);
                
                if (!is_array($memberDetails) || empty($memberDetails)) {
                    continue;
                }

                // Create booth owner from owner_details
                $ownerDetails = json_decode($booking->owner_details, true);
                $ownerQrCode = $this->generateQrCode();
                
                $boothOwnerId = DB::table('booth_owners')->insertGetId([
                    'booking_id' => $booking->id,
                    'qr_code' => $ownerQrCode,
                    'form_responses' => json_encode($ownerDetails),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Update booking with booth_owner_id
                DB::table('bookings')
                    ->where('id', $booking->id)
                    ->update(['booth_owner_id' => $boothOwnerId]);

                // Create booth members from member_details
                foreach ($memberDetails as $memberData) {
                    $memberQrCode = $this->generateQrCode();
                    
                    DB::table('booth_members')->insert([
                        'booth_owner_id' => $boothOwnerId,
                        'qr_code' => $memberQrCode,
                        'form_responses' => json_encode($memberData),
                        'status' => 'active',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

            } catch (\Exception $e) {
                // Log error but continue with other bookings
                Log::error('Failed to migrate booking member data', [
                    'booking_id' => $booking->id,
                    'error' => $e->getMessage()
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This migration cannot be easily reversed as it transforms data
        // The original JSON data would need to be reconstructed
    }

    /**
     * Generate unique QR code starting with "1" + 5 random digits
     */
    private function generateQrCode(): string
    {
        do {
            $qrCode = '1' . str_pad(rand(0, 99999), 5, '0', STR_PAD_LEFT);
        } while (DB::table('booth_owners')->where('qr_code', $qrCode)->exists() ||
                 DB::table('booth_members')->where('qr_code', $qrCode)->exists());
        
        return $qrCode;
    }
};
