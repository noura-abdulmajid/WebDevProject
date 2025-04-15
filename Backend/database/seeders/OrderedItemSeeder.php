<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\OrderedItem;

class OrderedItemSeeder extends Seeder
{
    public function run(): void
    {
        OrderedItem::factory()->count(50)->create();
    }
}
