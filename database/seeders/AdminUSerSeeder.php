<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AdminUSerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::firstOrCreate(['name' => 'superuser']);
        $permissions = Permission::all();
        $adminRole->syncPermissions($permissions);

        $admin = User::firstOrCreate([
            'email' => 'lukmanhakim1805@gmail.com',
        ], [
            'name' => 'Haris Lukman Hakim',
            'password' => Hash::make('1234567890'),
        ]);
        $admin->assignRole($adminRole);
    }
}
