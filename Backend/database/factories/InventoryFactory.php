<?php

namespace Database\Factories;

use App\Models\Inventory;
use App\Models\Products;
use Illuminate\Database\Eloquent\Factories\Factory;

class InventoryFactory extends Factory
{
    protected $model = Inventory::class;

    public function definition(): array
    {
        return [
            'P_ID' => Products::inRandomOrder()->first()?->id ?? Products::factory(),
            'size' => $this->faker->randomElement([36, 38, 40, 42, 44]),
            'price' => $this->faker->randomFloat(2, 10, 500),
            'stock_level' => $this->faker->numberBetween(0, 100),
            'stock_status' => $this->faker->randomElement(['in_stock', 'low_stock', 'out_of_stock']),
        ];
    }
}
