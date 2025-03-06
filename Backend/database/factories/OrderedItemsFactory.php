<?php

namespace Database\Factories;

use App\Models\OrderedItems;
use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderedItemsFactory extends Factory
{
    protected $model = OrderedItems::class;

    public function definition(): array
    {
        return [
            'O_ID' => Order::inRandomOrder()->first()?->O_ID ?? Order::factory(),
            'quantity' => $this->faker->numberBetween(1, 10),
        ];
    }
}
