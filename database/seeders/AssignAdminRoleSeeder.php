<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AssignAdminRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Find the admin role
        $adminRole = Role::where('name', 'admin')->first();
        
        if (!$adminRole) {
            $this->command->error('Admin role not found. Please run RoleSeeder first.');
            return;
        }

        // Find the test user
        $testUser = User::where('email', 'test@example.com')->first();
        
        if (!$testUser) {
            $this->command->error('Test user not found. Please run DatabaseSeeder first.');
            return;
        }

        // Assign admin role to test user
        if (!$testUser->hasRole('admin')) {
            $testUser->assignRole($adminRole);
            $this->command->info('Admin role assigned to test user successfully.');
        } else {
            $this->command->info('Test user already has admin role.');
        }
    }
}
