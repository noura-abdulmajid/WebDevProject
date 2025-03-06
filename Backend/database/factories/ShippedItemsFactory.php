<?php

namespace Database\Factories;

use App\Models\ShippedItems;
use App\Models\Shipped;
use App\Models\Inventory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ShippedItems>
 */
class ShippedItemsFactory extends Factory
{
    protected $model = ShippedItems::class;

    public function definition(): array
    {
        return [
            'S_ID' => Shipped::inRandomOrder()->first()?->S_ID ?? Shipped::factory(),
            'I_ID' => Inventory::inRandomOrder()->first()?->I_ID ?? Inventory::factory(),
            'quantity' => $this->faker->numberBetween(1, 10),
        ];
    }
}
