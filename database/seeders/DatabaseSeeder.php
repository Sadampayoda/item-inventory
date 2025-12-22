<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
            'level' => 'admin',
        ]);

        User::create([
            'name' => 'Warehouse User',
            'email' => 'warehouse@example.com',
            'password' => Hash::make('password123'),
            'level' => 'warehouse',
        ]);

        User::create([
            'name' => 'Management Warehouse User',
            'email' => 'management@example.com',
            'password' => Hash::make('password123'),
            'level' => 'manajement_warehouse',
        ]);


        $this->call([
            WarehouseSeeder::class,
            ItemsSeeder::class,
        ]);
    }
}
