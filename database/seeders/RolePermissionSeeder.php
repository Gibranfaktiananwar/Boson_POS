<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cache permission Spatie
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Daftar permission lengkap
        $permissions = [
            // User
            'view-user',
            'add-user',
            'edit-user',
            'delete-user',

            // Category
            'view-category',
            'add-category',
            'edit-category',
            'delete-category',

            // Product
            'view-product',
            'add-product',
            'edit-product',
            'delete-product',

            // Catalog
            'view-catalog',

            // Cart
            'view-cart',
            'add-cart',
            'edit-cart',
            'delete-cart',

            // Rewards
            'redeem-rewards',
        ];

        // Buat permissions (idempotent)
        foreach ($permissions as $p) {
            Permission::firstOrCreate(['name' => $p, 'guard_name' => 'web']);
        }

        // Buat roles (idempotent)
        $master = Role::firstOrCreate(['name' => 'masteradmin', 'guard_name' => 'web']);
        $adminToko = Role::firstOrCreate(['name' => 'admintoko', 'guard_name' => 'web']);

        // masteradmin dapat SEMUA permission
        $master->syncPermissions(Permission::pluck('name')->all());

        // admintoko: hanya yang kamu inginkan (di contoh ini tetap 'redeem-rewards')
        $adminToko->syncPermissions(['redeem-rewards']);
    }
}
