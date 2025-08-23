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
        // Update existing event slugs to use the new 'name' column
        $events = \App\Models\Event::all();
        foreach ($events as $event) {
            $event->update(['slug' => \App\Models\Event::generateSlug($event->name)]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This migration is not reversible as it updates data
        // The column rename migration handles the rollback
    }
};
