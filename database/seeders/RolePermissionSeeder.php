<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Initial Permission List
        $permissions = [
            'view-user',
            'add-user',
            'edit-user',
            'delete-user',
            'redeem-rewards',
        ];

        // Create permissions
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create Role: masteradmin
        $adminRole = Role::firstOrCreate(['name' => 'masteradmin']);
        // Grant all permission to masteradmin role
        $adminRole->givePermissionTo(Permission::pluck('name')->toArray());

        // Create Role: admintoko
        $adminTokoRole = Role::firstOrCreate(['name' => 'admintoko']);
        //  grant redeem-rewards permission to the admintoko role.
        $permission2 = Permission::firstOrCreate(['name' => 'redeem-rewards']);
        $adminTokoRole->givePermissionTo($permission2);
        $adminTokoRole->givePermissionTo(['redeem-rewards']);
    }
}
