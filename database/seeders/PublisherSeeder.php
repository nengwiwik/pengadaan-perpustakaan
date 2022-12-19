<?php

namespace Database\Seeders;

use App\Models\Publisher;
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
    $data = [
      [
        'code' => 'NFI',
        'name' => 'Nurfachmi Publishing',
        'address' => 'JI Tanjung Duren Barat II No 1 Grogol Jakarta Barat.',
        'email' => 'publishing@nurfachmi.com',
        'phone' => '021-21194454',
      ],
      [
        'code' => 'ERL',
        'name' => 'Erlangga',
        'address' => 'JI Tanjung Duren Barat II No 1 Grogol Jakarta Barat.',
        'email' => 'info@erlangga.com',
        'phone' => '021-21194454',
      ],
    ];

    foreach ($data as $d) {
      Publisher::updateOrCreate(
        [
          'code' => $d['code'],
        ],
        [
          'name' => $d['name'],
          'address' => $d['address'],
          'email' => $d['email'],
          'phone' => $d['phone'],
        ]
      );
    }
  }
}
