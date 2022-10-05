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
        'email' => 'admin@gmail.com'
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
        'email' => 'penerbit@gmail.com'
      ],
      [
        'name' => 'Penerbit Erlangga',
        'email_verified_at' => now(),
        'password' => bcrypt('password'),
      ]
    );;
    $penerbit->assignRole('Penerbit');

    $prodi = User::updateOrCreate(
      [
        'email' => 'prodi@gmail.com'
      ],
      [
        'name' => 'Prodi TI',
        'email_verified_at' => now(),
        'password' => bcrypt('password'),
      ]
    );;
    $prodi->assignRole('Admin Prodi');
  }
}
