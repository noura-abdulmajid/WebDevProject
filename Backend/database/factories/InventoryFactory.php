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
        $product = Products::inRandomOrder()->first() ?? Products::factory()->create();

        $colors = is_string($product->colours) ? json_decode($product->colours, true) : (array) $product->colours;
        $sizes = is_string($product->sizes) ? json_decode($product->sizes, true) : (array) $product->sizes;

        return [
            'P_ID' => $product->P_ID,
            'color' => $this->faker->randomElement($colors),
            'size' => $this->faker->randomElement($sizes),
            'price' => $this->faker->randomFloat(2, $product->price - 10, $product->price + 10),
            'stock_level' => $this->faker->numberBetween(0, 100),
            'stock_status' => $this->faker->randomElement(['in_stock', 'low_stock', 'out_of_stock']),
        ];
    }
}