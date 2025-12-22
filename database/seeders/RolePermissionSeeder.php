<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create permissions
        $permissions = [
            'manage users',
            'manage products',
            'manage orders',
            'manage categories',
            'manage blogs',
            'manage sliders',
            'manage discounts',
            'manage suppliers',
            'manage stock',
            'manage returns',
            'manage receipts',
            'manage reports',
            'manage notifications',
            'manage inbox',
            'manage policies',
            'manage reviews',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles
        $superAdminRole = Role::firstOrCreate(['name' => 'Super Admin']);
        $adminRole = Role::firstOrCreate(['name' => 'Admin']);

        // Assign all permissions to Super Admin
        $superAdminRole->syncPermissions($permissions);

        // Assign some permissions to Admin (example)
        $adminPermissions = [
            'manage products',
            'manage orders',
            'manage categories',
            'manage blogs',
            'manage sliders',
        ];
        $adminRole->syncPermissions($adminPermissions);
    }
}
