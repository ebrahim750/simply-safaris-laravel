<?php

namespace Database\Factories;

use App\Models\Tour;
use App\Models\TourInclusion;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TourInclusion>
 */
class TourInclusionFactory extends Factory
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
            'text' => fake()->sentence(),
        ];
    }
}
