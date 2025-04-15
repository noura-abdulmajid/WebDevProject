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
            CustomerSeeder::class,
            ProductsSeeder::class,
            InventorySeeder::class,
            OrdersSeeder::class,
            ShippedSeeder::class,
            ShippedItemsSeeder::class,
            OrderedItemSeeder::class,
            FavouriteSeeder::class,
            AdminUserSeeder::class,
            SiteReviewSeeder::class,
        ]);
    }
}