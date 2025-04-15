<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ShippedItem;
use App\Models\Shipped;
use App\Models\Inventory;

class ShippedItemsSeeder extends Seeder
{
    public function run(): void
    {
        Shipped::all()->each(function ($shipped) {
            ShippedItem::factory()->count(5)->create([
                'S_ID' => $shipped->S_ID,
                'I_ID' => Inventory::inRandomOrder()->first()->I_ID,
            ]);
        });
    }
}