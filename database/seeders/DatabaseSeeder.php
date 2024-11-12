<?php

namespace Database\Seeders;

use App\Models\Cost;
use App\Models\Inventory;
use App\Models\Supplier;
use App\Models\Supply;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $supliers = Supplier::factory(10)->create();

        $inventories = Inventory::factory(100)->create();

        $supliers->each(function ($suplier) {
            $suplier->supplies()->saveMany(Supply::factory(10)->make());
        });

        $inventories->each(function ($inventory) {
            $inventory->costs()->saveMany(Cost::factory(10)->make());
        });
    }
}
