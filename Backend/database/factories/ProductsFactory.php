<?php

namespace Database\Factories;

use App\Models\Products;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductsFactory extends Factory
{
    protected $model = Products::class;

    public function definition(): array
    {
        $brands = [
            'Nike', 'Adidas', 'Puma', 'Reebok', 'New Balance', 'Under Armour',
            'Jordan', 'Converse', 'Vans', 'Asics', 'Saucony', 'Yeezy', 'Fila'
        ];

        $shoeModels = [
            'AirMax', 'Air Force 1', 'Dunk Low', 'Cortez', 'Jordan 1', 'Jordan 4',
            'Ultraboost', 'Superstar', 'Stan Smith', 'NMD', 'Yeezy Boost 350', 'Yeezy Slide',
            'Classic Leather', 'Pump Omni', 'Gel-Kayano', 'Gel-Lyte III',
            'Old Skool', 'Sk8-Hi', 'Era', 'Slip-On', 'FuelCell Rebel', 'Clyde', 'Speedfactory'
        ];

        return [
            'p_name' => $this->faker->randomElement($brands) . ' ' . $this->faker->randomElement($shoeModels),
            'description' => $this->faker->sentence,
            'categories' => json_encode($this->faker->randomElements(
                ['running', 'basketball', 'casual', 'formal', 'outdoor', 'training', 'skateboard'],
                $this->faker->numberBetween(1, 3)
            )),
            'colours' => json_encode($this->faker->randomElements(
                [$this->faker->hexColor, $this->faker->hexColor, $this->faker->hexColor],
                $this->faker->numberBetween(1, 3)
            )),
            'photo' => '/image/banner.png',
            'sizes' => json_encode($this->faker->randomElements(
                ['5', '6', '7', '8', '9', '10', '11', '12'],
                $this->faker->numberBetween(1, 4)
            )),
            'price' => $this->faker->randomFloat(2, 50, 300),
            'sustainability' => $this->faker->boolean,
            'overall_stock_status' => $this->faker->randomElement(['in_stock', 'out_of_stock', 'discontinued']),
            'gender_target' => $this->faker->randomElement(['male', 'female', 'kids', 'unisex']),
        ];
    }
}