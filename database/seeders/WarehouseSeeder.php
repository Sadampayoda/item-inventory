<?php

namespace Database\Seeders;

use App\Models\Warehouse;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WarehouseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $warehouses = [
            [
                'code' => 'WH-JKT',
                'name' => 'Warehouse Jakarta',
                'descrption' => 'Gudang utama Jakarta',
            ],
            [
                'code' => 'WH-BDG',
                'name' => 'Warehouse Bandung',
                'descrption' => 'Gudang Bandung',
            ],
            [
                'code' => 'WH-SBY',
                'name' => 'Warehouse Surabaya',
                'descrption' => 'Gudang Surabaya',
            ],
            [
                'code' => 'WH-SMG',
                'name' => 'Warehouse Semarang',
                'descrption' => 'Gudang Semarang',
            ],
            [
                'code' => 'WH-DPS',
                'name' => 'Warehouse Denpasar',
                'descrption' => 'Gudang Bali',
            ],
        ];

        foreach ($warehouses as $warehouse) {
            Warehouse::create($warehouse);
        }
    }
}
