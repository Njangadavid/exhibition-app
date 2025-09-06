<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;
use App\Models\Role;

class AdminPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            [
                'name' => 'manage_payment_methods',
                'display_name' => 'Manage Payment Methods',
                'description' => 'Allows user to manage payment methods and settings',
                'category' => 'Payments',
            ],
            [
                'name' => 'manage_email_settings',
                'display_name' => 'Manage Email Settings',
                'description' => 'Allows user to manage email settings and configurations',
                'category' => 'Email',
            ],
        ];

        foreach ($permissions as $permissionData) {
            $permission = Permission::firstOrCreate([
                'name' => $permissionData['name'],
            ], [
                'display_name' => $permissionData['display_name'],
                'description' => $permissionData['description'],
                'category' => $permissionData['category'],
                'is_active' => true,
            ]);

            // Assign the permission to admin role only
            $adminRole = Role::where('name', 'admin')->first();
            if ($adminRole && !$adminRole->permissions()->where('permission_id', $permission->id)->exists()) {
                $adminRole->permissions()->attach($permission->id);
            }
        }

        $this->command->info('Admin permissions created and assigned to admin role.');
    }
}
