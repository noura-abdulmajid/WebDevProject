<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;

class OrdersSeeder extends Seeder
{
    public function run(): void
    {
        Order::factory()->count(100)->create();
    }
}
