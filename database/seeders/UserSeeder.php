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
        $admin = new User();
        $admin->name = 'Super Admin';
        $admin->email = 'admin@gmail.com';
        $admin->email_verified_at = now();
        $admin->password = bcrypt('password');
        $admin->save();
        $admin->assignRole('Super Admin');

        $penerbit = new User();
        $penerbit->name = 'Penerbit erlangga';
        $penerbit->email = 'penerbit@gmail.com';
        $penerbit->email_verified_at = now();
        $penerbit->password = bcrypt('password');
        $penerbit->save();
        $penerbit->assignRole('Penerbit');

        $prodi = new User();
        $prodi->name = 'Prodi TI';
        $prodi->email = 'prodi@gmail.com';
        $prodi->email_verified_at = now();
        $prodi->password = bcrypt('password');
        $prodi->save();
        $prodi->assignRole('Admin Prodi');
    }

}
