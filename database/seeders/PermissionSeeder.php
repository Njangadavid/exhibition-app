<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            // User Management
            [
                'name' => 'manage_users',
                'display_name' => 'Manage Users',
                'description' => 'Create, edit, delete, and manage user accounts',
                'category' => 'user_management',
            ],
            [
                'name' => 'assign_roles',
                'display_name' => 'Assign Roles',
                'description' => 'Assign and remove roles from users',
                'category' => 'user_management',
            ],
            [
                'name' => 'view_user_reports',
                'display_name' => 'View User Reports',
                'description' => 'Access user activity and management reports',
                'category' => 'user_management',
            ],

            // Event Management
            [
                'name' => 'manage_events',
                'display_name' => 'Manage All Events',
                'description' => 'Create, edit, delete, and manage all events',
                'category' => 'event_management',
            ],
            [
                'name' => 'manage_own_events',
                'display_name' => 'Manage Own Events',
                'description' => 'Create, edit, delete, and manage own events only',
                'category' => 'event_management',
            ],
            [
                'name' => 'view_events',
                'display_name' => 'View Events',
                'description' => 'View and access event information',
                'category' => 'event_management',
            ],

            // Floorplan Management
            [
                'name' => 'manage_floorplans',
                'display_name' => 'Manage Floorplans',
                'description' => 'Design and manage event floorplans',
                'category' => 'floorplan_management',
            ],
            [
                'name' => 'manage_own_floorplans',
                'display_name' => 'Manage Own Floorplans',
                'description' => 'Design and manage floorplans for own events',
                'category' => 'floorplan_management',
            ],

            // Booking Management
            [
                'name' => 'manage_bookings',
                'display_name' => 'Manage Bookings',
                'description' => 'View and manage all bookings',
                'category' => 'booking_management',
            ],
            [
                'name' => 'manage_own_bookings',
                'display_name' => 'Manage Own Bookings',
                'description' => 'View and manage bookings for own events',
                'category' => 'booking_management',
            ],
            [
                'name' => 'view_booking_reports',
                'display_name' => 'View Booking Reports',
                'description' => 'Access booking reports and analytics',
                'category' => 'booking_management',
            ],

            // Payment Management
            [
                'name' => 'manage_payments',
                'display_name' => 'Manage Payments',
                'description' => 'Process and manage payments',
                'category' => 'payment_management',
            ],
            [
                'name' => 'view_payment_reports',
                'display_name' => 'View Payment Reports',
                'description' => 'Access payment reports and analytics',
                'category' => 'payment_management',
            ],

            // Form Management
            [
                'name' => 'manage_forms',
                'display_name' => 'Manage Forms',
                'description' => 'Create and manage dynamic forms',
                'category' => 'form_management',
            ],
            [
                'name' => 'manage_own_forms',
                'display_name' => 'Manage Own Forms',
                'description' => 'Create and manage forms for own events',
                'category' => 'form_management',
            ],

            // Email Management
            [
                'name' => 'manage_emails',
                'display_name' => 'Manage Emails',
                'description' => 'Send emails and manage email templates',
                'category' => 'email_management',
            ],
            [
                'name' => 'manage_own_emails',
                'display_name' => 'Manage Own Emails',
                'description' => 'Send emails for own events',
                'category' => 'email_management',
            ],

            // System Administration
            [
                'name' => 'system_settings',
                'display_name' => 'System Settings',
                'description' => 'Access and modify system settings',
                'category' => 'system_administration',
            ],
            [
                'name' => 'view_audit_logs',
                'display_name' => 'View Audit Logs',
                'description' => 'Access system audit logs and activity',
                'category' => 'system_administration',
            ],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission['name']],
                $permission
            );
        }
    }
}
