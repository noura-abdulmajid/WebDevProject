<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Order;
use App\Models\AdminUser;

class ShippedFactory extends Factory
{
    public function definition(): array
    {
        return [
            'O_ID' => Order::inRandomOrder()->first()?->O_ID,
            'shipped_date' => fake()->dateTimeBetween('-1 year', 'now'),
            'shipped_by' => AdminUser::inRandomOrder()->first()?->A_ID,
            'stationery_printed' => fake()->boolean(),
            'delivery_status' => fake()->randomElement(['pending', 'shipped', 'delivered', 'canceled']),
        ];
    }
}