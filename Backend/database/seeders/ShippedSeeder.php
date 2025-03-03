<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Shipped;

class ShippedSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Shipped::factory()->count(50)->create();
    }
}
