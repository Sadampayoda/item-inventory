<?php

namespace Database\Seeders;

use App\Models\Item;
use App\Models\Warehouse;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ItemsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $warehouses = Warehouse::pluck('code')->toArray();

        for ($i = 1; $i <= 10; $i++) {
            Item::create([
                'code' => 'ITEM-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'name' => 'Item ' . $i,
                'description' => 'Deskripsi item ' . $i,
                'warehouse' => $warehouses[array_rand($warehouses)],
                'stock_on_hand' => rand(10, 100),
                'stock_avalaible' => rand(5, 80),
                'min_stock' => rand(5, 10),
                'is_active' => true,
            ]);
        }
    }
}
