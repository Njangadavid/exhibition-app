<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin Role
        $adminRole = Role::firstOrCreate(
            ['name' => 'admin'],
            [
                'display_name' => 'Administrator',
                'description' => 'Full system access with all permissions',
                'is_active' => true,
            ]
        );

        // Create Organizer Role
        $organizerRole = Role::firstOrCreate(
            ['name' => 'organizer'],
            [
                'display_name' => 'Event Organizer',
                'description' => 'Can manage their own events and related content',
                'is_active' => true,
            ]
        );

        // Create User Role (basic access)
        $userRole = Role::firstOrCreate(
            ['name' => 'user'],
            [
                'display_name' => 'User',
                'description' => 'Basic user access with limited permissions',
                'is_active' => true,
            ]
        );

        // Assign permissions to Admin role (all permissions)
        $adminPermissions = Permission::all();
        $adminRole->permissions()->sync($adminPermissions->pluck('id'));

        // Assign permissions to Organizer role
        $organizerPermissions = Permission::whereIn('name', [
            'manage_own_events',
            'view_events',
            'manage_own_floorplans',
            'manage_own_bookings',
            'view_booking_reports',
            'manage_own_forms',
            'manage_own_emails',
        ])->get();
        $organizerRole->permissions()->sync($organizerPermissions->pluck('id'));

        // Assign permissions to User role (minimal permissions)
        $userPermissions = Permission::whereIn('name', [
            'view_events',
        ])->get();
        $userRole->permissions()->sync($userPermissions->pluck('id'));
    }
}
