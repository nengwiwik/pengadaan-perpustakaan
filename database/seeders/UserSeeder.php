<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $admin = User::updateOrCreate(
      [
        'email' => '41119051@mahasiswa.undira.ac.id'
      ],
      [
        'name' => 'Super Admin',
        'email_verified_at' => now(),
        'password' => bcrypt('password'),
      ]
    );
    $admin->assignRole(User::ROLE_SUPER_ADMIN);

    $penerbit = User::updateOrCreate(
      [
        'email' => 'wiwik@nurfachmi.com'
      ],
      [
        'name' => 'Penerbit Wiwik',
        'email_verified_at' => now(),
        'password' => bcrypt('password'),
        'publisher_id' => 1,
      ]
    );
    $penerbit->assignRole(User::ROLE_PENERBIT);

    // $penerbit = User::updateOrCreate(
    //   [
    //     'email' => 'penerbit@gmail.com'
    //   ],
    //   [
    //     'name' => 'Penerbit Erlangga',
    //     'email_verified_at' => now(),
    //     'password' => bcrypt('password'),
    //     'publisher_id' => 2,
    //   ]
    // );
    // $penerbit->assignRole(User::ROLE_PENERBIT);

    $prodi = User::updateOrCreate(
      [
        'email' => 'wiwik@undira.ac.id'
      ],
      [
        'name' => 'Prodi TI',
        'email_verified_at' => now(),
        'password' => bcrypt('password'),
        'campus_id' => 1,
        'major_id' => 7,
      ]
    );
    $prodi->assignRole(User::ROLE_ADMIN_PRODI);
  }
}
