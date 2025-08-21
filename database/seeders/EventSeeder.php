<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Event;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create some active events
        Event::factory()->active()->count(3)->create();
        
        // Create some upcoming events
        Event::factory()->upcoming()->count(5)->create();
        
        // Create some completed events
        Event::factory()->completed()->count(2)->create();
        
        // Create some draft events
        Event::factory()->count(3)->create();
        
        $this->command->info('Events seeded successfully!');
    }
}
