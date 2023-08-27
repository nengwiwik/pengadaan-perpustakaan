<?php

namespace Database\Seeders;

use App\Models\Publisher;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PublisherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $penerbit = Publisher::updateOrCreate(
            [
                'code' => 'ERL'
            ],
            [
                'name' => 'Erlangga',
                'address' => 'JI Tanjung Duren Barat II No 1 Grogol Jakarta Barat.',
                'email' => 'info@erlangga.com',
                'phone' => '021-21194454',
            ]
        );

        $adminPenerbit = User::updateOrCreate(
            [
                'email' => 'penerbit@undira.biz.id'
            ],
            [
                'name' => 'Penerbit ' . $penerbit->name,
                'email_verified_at' => now(),
                'password' => bcrypt('password'),
                'publisher_id' => 1,
            ]
        );
        $adminPenerbit->assignRole(User::ROLE_PENERBIT);
    }
}
