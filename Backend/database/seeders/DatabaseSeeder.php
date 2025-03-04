<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AdminUser;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 调用其他 Seeders
        $this->call([
            CustomerSeeder::class,
            ProductsSeeder::class,
            ShippedSeeder::class,
            ShippedItemsSeeder::class,
            InventorySeeder::class,
            OrdersSeeder::class,
            OrderedItemsSeeder::class,
            FavouriteSeeder::class,
        ]);

        AdminUser::factory()->count(10)->create([
            'role' => AdminUser::ROLE_ADMIN,
        ]);
    }
}