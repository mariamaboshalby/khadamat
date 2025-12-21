<?php

namespace Database\Seeders;

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
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            // Customer permissions
            'view_customers',
            'edit_customers',
            'delete_customers',
            
            // Technician permissions
            'view_technicians',
            'create_technicians',
            'edit_technicians',
            'delete_technicians',
            'assign_roles',
            
            // Specialization permissions
            'view_specializations',
            'create_specializations',
            'edit_specializations',
            'delete_specializations',
            
            // Warehouse item permissions
            'view_warehouse_items',
            'create_warehouse_items',
            'edit_warehouse_items',
            'delete_warehouse_items',
            
            // Report permissions
            'view_reports',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles and assign permissions
        
        // Admin role - has all permissions
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->syncPermissions(Permission::all());

        // Technician role - limited permissions
        $technicianRole = Role::firstOrCreate(['name' => 'technician']);
        $technicianRole->syncPermissions([
            'view_warehouse_items',
        ]);

        // Customer role - very limited permissions
        $customerRole = Role::firstOrCreate(['name' => 'customer']);
        // Customers don't need special permissions by default
    }
}
