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
            'external_url' => fake()->optional()->url(),
            'external_link_button_text' => fake()->optional()->words(2, true),
            'show_external_booking' => fake()->boolean(),
            'duration_days' => fake()->numberBetween(1, 21),
            'overview' => fake()->paragraphs(3, true),
            'pricing_detailed' => fake()->paragraphs(2, true),
            'is_hidden' => fake()->boolean(20),
            'is_featured_on_home' => fake()->boolean(10),
            'is_featured_as_seasonal_recommendation' => fake()->boolean(10),
        ];
    }
}
