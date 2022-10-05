<?php

namespace Database\Seeders;

use App\Models\Major;
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
      Major::updateOrCreate(
        [
          'name' => $d,
        ],
        [
          'name' => $d,
        ]
      );
    }
  }
}
