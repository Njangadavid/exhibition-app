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
        Schema::table('email_logs', function (Blueprint $table) {
            // Modify the status column to include 'processing' enum value
            $table->enum('status', ['pending', 'processing', 'sent', 'failed'])->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('email_logs', function (Blueprint $table) {
            // Revert the status column to exclude 'processing' enum value
            $table->enum('status', ['pending', 'sent', 'failed'])->change();
        });
    }
};
