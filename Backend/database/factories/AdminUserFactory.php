<?php

namespace Database\Factories;

use App\Models\AdminUser;
use Illuminate\Database\Eloquent\Factories\Factory;

class AdminUserFactory extends Factory
{
    protected $model = AdminUser::class;

    public function definition(): array
    {
        return [
            'username' => $this->faker->userName(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => bcrypt('admin123'),
            'role' => $this->faker->randomElement(['super_admin', 'editor', 'moderator']),
            'first_name' => $this->faker->firstName(),
            'surname' => $this->faker->lastName(),
            'date_joined' => now()
        ];
    }
}