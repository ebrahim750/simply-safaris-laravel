<?php

namespace Database\Factories;

use App\Models\Tour;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Tour>
 */
class TourFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->sentence(3),
            'price' => fake()->randomFloat(2, 50, 5000),
            'main_image' => fake()->imageUrl(1200, 800, 'travel'),
            'gallery_images' => [
                fake()->imageUrl(1200, 800, 'travel'),
                fake()->imageUrl(1200, 800, 'travel'),
                fake()->imageUrl(1200, 800, 'travel'),
            ],
        ];
    }
}
