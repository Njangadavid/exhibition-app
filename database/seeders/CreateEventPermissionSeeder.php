<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;
use App\Models\Role;

class CreateEventPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create the "Create Event" permission
        $permission = Permission::firstOrCreate([
            'name' => 'create_events',
            'display_name' => 'Create Events',
            'description' => 'Allows user to create new events',
            'category' => 'Events',
            'is_active' => true,
        ]);

        // Assign the permission to admin and organizer roles
        $adminRole = Role::where('name', 'admin')->first();
        $organizerRole = Role::where('name', 'organizer')->first();

        if ($adminRole && !$adminRole->permissions()->where('permission_id', $permission->id)->exists()) {
            $adminRole->permissions()->attach($permission->id);
        }

        if ($organizerRole && !$organizerRole->permissions()->where('permission_id', $permission->id)->exists()) {
            $organizerRole->permissions()->attach($permission->id);
        }

        $this->command->info('Create Event permission created and assigned to admin and organizer roles.');
    }
}