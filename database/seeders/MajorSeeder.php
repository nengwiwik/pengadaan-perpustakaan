<?php

namespace Database\Seeders;

use App\Models\Major;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MajorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            'Umum',
            'Akutansi',
            'Ilmu Komunikasi',
            'Manajemen',
            'Sastra Inggris',
            'Teknik Elektro',
            'Teknik Informatika',
            'Teknik Mesin',
            'Teknik Sipil',
        ];

        foreach ($data as $d) {
            $major = Major::updateOrCreate(
                [
                    'name' => $d,
                ],
                [
                    'name' => $d,
                ]
            );

            // $prodi = User::updateOrCreate(
            //     [
            //         'email' => str($major->name)->slug() . '@undira.ac.id'
            //     ],
            //     [
            //         'name' => "Dosen {$major->name}",
            //         'email_verified_at' => now(),
            //         'password' => bcrypt('password'),
            //         'campus_id' => 1,
            //         'major_id' => $major->getKey(),
            //     ]
            // );
            // $prodi->assignRole('Admin Prodi');
        }
    }
}
