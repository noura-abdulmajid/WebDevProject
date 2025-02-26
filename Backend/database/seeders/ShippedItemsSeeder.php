<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ShippedItems;

class ShippedItemsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ShippedItems::factory()->count(100)->create();
    }
}
