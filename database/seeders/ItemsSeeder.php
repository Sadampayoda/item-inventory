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

        $itemNames = [
            'Beras Medium 5kg',
            'Beras Premium 10kg',
            'Gula Pasir 1kg',
            'Minyak Goreng 2L',
            'Tepung Terigu 1kg',
            'Susu UHT 1L',
            'Kopi Bubuk 250gr',
            'Teh Celup 50pcs',
            'Mie Instan Goreng',
            'Garam Dapur 500gr',
        ];

        foreach ($itemNames as $index => $name) {
            $stock = rand(20, 100);

            Item::create([
                'code' => 'ITEM-' . str_pad($index + 1, 4, '0', STR_PAD_LEFT),
                'name' => $name,
                'description' => 'Produk ' . $name,
                'warehouse' => $warehouses[array_rand($warehouses)],
                'stock_on_hand' => $stock,
                'stock_avalaible' => $stock,
                'min_stock' => rand(5, 15),
                'is_active' => true,
            ]);
        }
    }
}
