<?php

namespace Database\Seeders;

use App\Models\Gear;
use Illuminate\Database\Seeder;

class GearSeeder extends Seeder
{
    public function run(): void
    {
        $gears = [
            [
                'nama_gear' => 'Sony A7 Mark II Body Only',
                'merk' => 'Sony',
                'harga_4jam' => 50000,
                'harga_6jam' => 70000,
                'harga_12jam' => 120000,
                'harga_24jam' => 180000,
                'jaminan' => 'KTP dan Kartu Pelajar',
                'status' => 'tersedia',
                'stok' => 3
            ],
            [
                'nama_gear' => 'Lensa Sony FE 50mm f1.8',
                'merk' => 'Sony',
                'harga_4jam' => 20000,
                'harga_6jam' => 30000,
                'harga_12jam' => 50000,
                'harga_24jam' => 80000,
                'jaminan' => 'KTP',
                'status' => 'tersedia',
                'stok' => 2
            ],
            [
                'nama_gear' => 'Wireless Mic Rode Wireless Go',
                'merk' => 'Rode',
                'harga_4jam' => 30000,
                'harga_6jam' => 45000,
                'harga_12jam' => 75000,
                'harga_24jam' => 110000,
                'jaminan' => 'KTP atau SIM',
                'status' => 'tersedia',
                'stok' => 4
            ],
            [
                'nama_gear' => 'Gimbal Stabilizer DJI Ronin SC',
                'merk' => 'DJI',
                'harga_4jam' => 40000,
                'harga_6jam' => 60000,
                'harga_12jam' => 100000,
                'harga_24jam' => 150000,
                'jaminan' => 'KTP dan KK',
                'status' => 'tersedia',
                'stok' => 2
            ],
            [
                'nama_gear' => 'Lampu Godox SL60W LED Video Light',
                'merk' => 'Godox',
                'harga_4jam' => 25000,
                'harga_6jam' => 35000,
                'harga_12jam' => 60000,
                'harga_24jam' => 90000,
                'jaminan' => 'KTP',
                'status' => 'tersedia',
                'stok' => 5
            ]
        ];

        foreach ($gears as $gear) {
            Gear::create($gear);
        }
    }
}