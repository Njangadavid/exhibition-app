<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;
use App\Models\User;

class AssignEventOwnersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the first admin user as the default owner
        $admin = User::where('email', 'njangadavid@gmail.com')->first();
        
        if (!$admin) {
            // If no admin found, get the first user
            $admin = User::first();
        }
        
        if ($admin) {
            // Assign all events without owners to the admin
            Event::whereNull('owner_id')->update(['owner_id' => $admin->id]);
            
            $this->command->info('Assigned event owners successfully.');
        } else {
            $this->command->error('No users found to assign as event owners.');
        }
    }
}