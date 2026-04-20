<?php

namespace Database\Factories;

use App\Models\Tour;
use App\Models\TourExclusion;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TourExclusion>
 */
class TourExclusionFactory extends Factory
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
