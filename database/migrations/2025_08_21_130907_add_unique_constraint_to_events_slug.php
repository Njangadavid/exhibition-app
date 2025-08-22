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
        // First add the slug column if it doesn't exist
        if (!Schema::hasColumn('events', 'slug')) {
            Schema::table('events', function (Blueprint $table) {
                $table->string('slug')->after('title');
            });
        }

        // Generate slugs for existing events
        $events = \App\Models\Event::whereNull('slug')->orWhere('slug', '')->get();
        foreach ($events as $event) {
            $event->update(['slug' => \App\Models\Event::generateSlug($event->title)]);
        }

        // Add unique constraint
        Schema::table('events', function (Blueprint $table) {
            $table->unique('slug');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropUnique(['slug']);
            $table->dropColumn('slug');
        });
    }
};
