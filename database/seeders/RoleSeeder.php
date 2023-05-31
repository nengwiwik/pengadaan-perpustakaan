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
    $role = Role::updateOrCreate(
      ['name' => 'Super Admin'],
      ['guard_name' => 'web']
    );
    $permissions = [
      'manage users',
    ];
    foreach ($permissions as $permission) {
      Permission::updateOrCreate(
        ['name' => $permission],
        ['guard_name' => 'web']
      );
      $role->givePermissionTo($permission);
    }

    // Penerbit
    $role = Role::updateOrCreate(
      ['name' => 'Penerbit'],
      ['guard_name' => 'web']
    );
    $permissions = [
      'manage products',
    ];
    foreach ($permissions as $permission) {
      Permission::updateOrCreate(
        ['name' => $permission],
        ['guard_name' => 'web']
      );
      $role->givePermissionTo($permission);
    }

    // Admin Prodi
    $role = Role::updateOrCreate(
      ['name' => 'Admin Prodi'],
      ['guard_name' => 'web']
    );
    $permissions = [
      'manage procurement-books',
    ];
    foreach ($permissions as $permission) {
      Permission::updateOrCreate(
        ['name' => $permission],
        ['guard_name' => 'web']
      );
      $role->givePermissionTo($permission);
    }
  }
}
