<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Super Admin
        $role = Role::create([
            'name' => 'Super Admin',
            'guard_name' => 'web'
        ]);
        $permissions = [
            'manage users',
        ];
        foreach($permissions as $permission) {
            Permission::create(['name' => $permission]);
            $role->givePermissionTo($permission);
        }

        // Penerbit
        $role = Role::create([
            'name' => 'Penerbit',
            'guard_name' => 'web'
        ]);
        $permissions = [
            'manage products',
        ];
        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
            $role->givePermissionTo($permission);
        }

        // Admin Prodi
        $role = Role::create([
            'name' => 'Admin Prodi',
            'guard_name' => 'web'
        ]);
        $permissions = [
            'manage books',
        ];
        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
            $role->givePermissionTo($permission);
        }
    }
}
