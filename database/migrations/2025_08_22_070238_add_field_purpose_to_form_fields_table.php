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
        Schema::table('form_fields', function (Blueprint $table) {
            $table->enum('field_purpose', [
                'general',          // Default - no specific purpose
                'member_name',      // Member's name/full name
                'member_email',     // Member's email address
                'member_phone',     // Member's phone number
                'member_company',   // Member's company
                'member_title',     // Member's job title
                'member_address',   // Member's address
                'member_id',        // Member's ID number
                'member_bio',       // Member's biography
                'member_notes',     // Additional notes about member
            ])->default('general')->after('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('form_fields', function (Blueprint $table) {
            $table->dropColumn('field_purpose');
        });
    }
};