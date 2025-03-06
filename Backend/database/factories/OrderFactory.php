<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Customer;

class OrderFactory extends Factory
{
    public function definition(): array
    {
        $subtotal = fake()->randomFloat(2, 20, 500);
        $deliveryCharge = fake()->randomFloat(2, 0, 50);

        return [
            'C_ID' => Customer::inRandomOrder()->first()?->C_ID,
            'order_date' => fake()->dateTimeBetween('-1 year', 'now'),
            'shipping_address' => fake()->address(),
            'subtotal' => $subtotal,
            'delivery_charge' => $deliveryCharge,
            'total_payment' => $subtotal + $deliveryCharge,
        ];
    }
}
