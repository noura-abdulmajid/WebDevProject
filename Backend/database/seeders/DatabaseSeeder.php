<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            ShippedSeeder::class,
            ShippedItemsSeeder::class,
            InventorySeeder::class,
            ContactSeeder::class,
            OrdersSeeder::class,
            OrderedItemsSeeder::class,
            ProductsSeeder::class,
            CustomerSeeder::class,
        ]);


    }
}
