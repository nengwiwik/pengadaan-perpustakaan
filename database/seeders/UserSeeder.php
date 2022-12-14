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
        'email' => 'admin@undira.ac.id'
      ],
      [
        'name' => 'Super Admin',
        'email_verified_at' => now(),
        'password' => bcrypt('password'),
      ]
    );
    $admin->assignRole('Super Admin');

    $penerbit = User::updateOrCreate(
      [
        'email' => 'publishing@nurfachmi.com'
      ],
      [
        'name' => 'Penerbit Nurfachmi',
        'email_verified_at' => now(),
        'password' => bcrypt('password'),
        'publisher_id' => 1,
      ]
    );
    $penerbit->assignRole('Penerbit');

    $penerbit = User::updateOrCreate(
      [
        'email' => 'penerbit@gmail.com'
      ],
      [
        'name' => 'Penerbit Erlangga',
        'email_verified_at' => now(),
        'password' => bcrypt('password'),
        'publisher_id' => 2,
      ]
    );
    $penerbit->assignRole('Penerbit');

    $prodi = User::updateOrCreate(
      [
        'email' => 'prodi@undira.ac.id'
      ],
      [
        'name' => 'Prodi TI',
        'email_verified_at' => now(),
        'password' => bcrypt('password'),
        'campus_id' => 1,
        'major_id' => 7,
      ]
    );
    $prodi->assignRole('Admin Prodi');
  }
}
