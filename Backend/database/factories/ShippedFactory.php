<?php

namespace Database\Factories;

use App\Models\Shipped;
use App\Models\Order;
use App\Models\AdminUsers;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Shipped>
 */
class ShippedFactory extends Factory
{
    protected $model = Shipped::class;

    public function definition(): array
    {
        return [
            'O_ID' => Order::inRandomOrder()->first()?->O_ID ?? Order::factory(),
            'shipped_date' => $this->faker->optional()->dateTimeBetween('-1 month', 'now'),
            'shipped_by' => AdminUsers::inRandomOrder()->first()?->A_ID ?? AdminUsers::factory(),
            'stationery_printed' => $this->faker->boolean,
            'delivery_status' => $this->faker->randomElement(['pending', 'shipped', 'delivered', 'canceled']),
        ];
    }
}
