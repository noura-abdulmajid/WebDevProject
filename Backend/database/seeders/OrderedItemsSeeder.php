<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\OrderedItems;

class OrderedItemsSeeder extends Seeder
{
    public function run(): void
    {
        OrderedItems::factory()->count(50)->create();
    }
}
