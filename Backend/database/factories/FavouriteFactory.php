<?php

namespace Database\Factories;

use App\Models\Favourite;
use App\Models\Customer;
use App\Models\Products;
use Illuminate\Database\Eloquent\Factories\Factory;

class FavouriteFactory extends Factory
{
    protected $model = Favourite::class;

    public function definition(): array
    {
        return [
            'C_ID' => Customer::query()->inRandomOrder()->value('C_ID')
                ?? Customer::factory()->create()->C_ID,
            'P_ID' => Products::query()->inRandomOrder()->value('P_ID')
                ?? Products::factory()->create()->P_ID,
            'added' => $this->faker->dateTimeBetween('-1 month', 'now'),
        ];
    }
}