<?php

namespace Database\Factories;

use App\Models\SiteReview;
use Illuminate\Database\Eloquent\Factories\Factory;

class SiteReviewFactory extends Factory
{
    protected $model = SiteReview::class;

    public function definition(): array
    {
        return [
            'review_email' => $this->faker->unique()->safeEmail,
            'review' => $this->faker->paragraph(5),
            'rating' => $this->faker->numberBetween(1, 5),
            'is_read' => $this->faker->boolean(50),
            'is_replied' => $this->faker->boolean(30),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}