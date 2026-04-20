<?php

namespace Database\Factories;

use App\Models\Tour;
use App\Models\TourItineraryItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TourItineraryItem>
 */
class TourItineraryItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tour_id' => Tour::factory(),
            'position' => fake()->numberBetween(0, 20),
            'title' => fake()->sentence(3),
            'content' => '<p>'.fake()->paragraph().'</p>',
        ];
    }
}
