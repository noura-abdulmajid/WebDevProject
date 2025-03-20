<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Shipped;
use App\Models\Order;

class ShippedSeeder extends Seeder
{
    public function run(): void
    {
        Shipped::factory()->count(50)->create()->each(function ($shipped) {
            $shipped->O_ID = Order::inRandomOrder()->first()->O_ID;
            $shipped->save();
        });
    }
}