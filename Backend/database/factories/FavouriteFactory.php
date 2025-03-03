<?php

namespace Database\Factories;

use App\Models\Favourite;
use App\Models\Customer;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class FavouriteFactory extends Factory
{
    protected $model = Favourite::class;

    public function definition(): array
    {
        return [
            'C_ID' => Customer::query()->inRandomOrder()->value('C_ID') ?? Customer::factory(),
            'P_ID' => Product::query()->inRandomOrder()->value('P_ID') ?? Product::factory(),
            'added' => $this->faker->dateTimeBetween('-1 month', 'now'),
        ];
    }
}