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
        // Buat atau ambil izin yang sudah ada
        $permissions = [
            'view-user',
            'add-user',
            'edit-user',
            'delete-user'
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Pastikan role 'admin' sudah ada
        $adminRole = Role::firstOrCreate(['name' => 'masteradmin']);

        // Berikan semua izin kepada role admin
        $adminRole->givePermissionTo(Permission::pluck('name')->toArray());

        //role admintoko
        $adminTokoRole = Role::firstOrCreate(['name' => 'admintoko']);

        //create permission
        $permission2 = Permission::firstOrCreate(['name' => 'redeem-rewards']);
        $adminTokoRole->givePermissionTo($permission2);
        $adminTokoRole->givePermissionTo(['redeem-rewards']);
    }
}
