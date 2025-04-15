<?php

namespace Database\Factories;

use App\Models\ShippedItem;
use App\Models\Shipped;
use App\Models\Inventory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ShippedItem>
 */
class ShippedItemFactory extends Factory
{
    protected $model = ShippedItem::class;

    public function definition(): array
    {
        return [
            'S_ID' => Shipped::inRandomOrder()->first()?->S_ID,
            'I_ID' => Inventory::inRandomOrder()->first()?->I_ID,
            'quantity' => fake()->numberBetween(1, 100),
        ];
    }
}
