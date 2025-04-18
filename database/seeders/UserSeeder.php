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

        $adminRole = Role::firstOrCreate(['name' => 'masteradmin']);
        $adminTokoRole = Role::firstOrCreate(['name' => 'admintoko']);

        $masteradminUser = User::updateOrCreate(
            [
                'email' => 'masteradmin@gmail.com',
            ],
            [
                'name' => 'Masteradmin',
                'password' => bcrypt('password'),
            ],
        );

        $masteradminUser->assignRole($adminRole);


        $admintokoUser = User::updateOrCreate(
            [
                'email' => 'admintoko@gmail.com',
            ],
            [
                'name' => 'tokoABC',
                'password' => Hash::make('password'),
            ],
        );

        $admintokoUser->assignRole($adminTokoRole);
    }
}
