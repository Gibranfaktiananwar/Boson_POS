<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {

        // create role
        $adminRole = Role::firstOrCreate(['name' => 'masteradmin']);
        $adminTokoRole = Role::firstOrCreate(['name' => 'admintoko']);

        // Create a data user for masteradmin role 
        $masteradminUser = User::updateOrCreate(
            [
                'email' => 'masteradmin@gmail.com',
            ],
            [
                'name' => 'Masteradmin',
                'password' => bcrypt('password'),
            ],
        );
        // put the user into the masteradmin role
        $masteradminUser->assignRole($adminRole);

        // create a data user for masteradmin role
        $admintokoUser = User::updateOrCreate(
            [
                'email' => 'admintoko@gmail.com',
            ],
            [
                'name' => 'tokoABC',
                'password' => Hash::make('password'),
            ],
        );
        // pu the user into admintoko role
        $admintokoUser->assignRole($adminTokoRole);
    }
}
