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
        Schema::table('form_builders', function (Blueprint $table) {
            // Add type column to distinguish between different form types
            $table->enum('type', ['member_registration', 'exhibitor_registration', 'speaker_registration', 'delegate_registration', 'general'])->default('general')->after('event_id');
            
            // Add index for faster lookups by type
            $table->index(['event_id', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('form_builders', function (Blueprint $table) {
            // Remove type column and index
            $table->dropIndex(['event_id', 'type']);
            $table->dropColumn('type');
        });
    }
};
