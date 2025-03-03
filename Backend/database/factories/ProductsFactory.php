<?php

namespace Database\Factories;

use App\Models\Products;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductsFactory extends Factory
{
    protected $model = Products::class;

    public function definition(): array
    {
        return [
            'p_name' => $this->faker->word,
            'description' => $this->faker->sentence,
            'categories' => [$this->faker->randomElement(['Trainers', 'Loafers', 'Heels', 'Ballerinas', 'Boots'])],
            'colours' => [$this->faker->safeColorName],
            'photo' => '/image/banner.png',
            'sizes' => $this->faker->randomElements(['3', '4', '5', '6', '7', '8'], $this->faker->numberBetween(1, 6)),
            'price' => $this->faker->randomFloat(2, 10, 100),
            'sustainability' => $this->faker->boolean,
            'overall_stock_status' => $this->faker->randomElement(['in_stock', 'out_of_stock', 'discontinued']),
        ];
    }
}
