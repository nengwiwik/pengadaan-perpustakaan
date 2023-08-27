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
                'email' => 'admin@undira.biz.id'
            ],
            [
                'name' => 'Admin ',
                'email_verified_at' => now(),
                'password' => bcrypt('password'),
            ]
        );
        $admin->assignRole(User::ROLE_SUPER_ADMIN);
    }
}
