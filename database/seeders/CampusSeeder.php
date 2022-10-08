<?php

namespace Database\Seeders;

use App\Models\Campus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CampusSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $data = [
      [
        'name' => 'Kampus Tanjung Duren',
        'address' => 'JI Tanjung Duren Barat II No 1 Grogol Jakarta Barat.',
        'email' => 'tj@undira.ac.id',
        'phone' => '021-21194454',
      ],
      [
        'name' => 'Kampus Green Ville',
        'address' => 'JI. Mangga 14, No 3 Kec Kebon Jeruk Jakarta Barat, 11510',
        'email' => 'tjv@undira.ac.id',
        'phone' => '021-21194454',
      ],
      [
        'name' => 'Kampus Cibubur',
        'address' => 'Jl. Rawa Dolar 65 Jatiranggon, Kec Jatisampurna, Bekasi',
        'email' => 'tj@undira.ac.id',
        'phone' => '021-22176334',
      ],
    ];

    foreach ($data as $d) {
      Campus::updateOrCreate(
        [
          'name' => $d['name'],
        ],
        [
          'address' => $d['address'],
          'email' => $d['email'],
          'phone' => $d['phone'],
        ]
      );
    }
  }
}
