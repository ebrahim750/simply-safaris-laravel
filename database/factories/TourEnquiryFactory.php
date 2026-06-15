<?php

namespace Database\Factories;

use App\Models\Tour;
use App\Models\TourEnquiry;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TourEnquiry>
 */
class TourEnquiryFactory extends Factory
{
    protected $model = TourEnquiry::class;

    public function definition(): array
    {
        return [
            'tour_id' => Tour::factory(),
            'name' => fake()->name(),
            'email' => fake()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'booking_date' => fake()->dateTimeBetween('today', '+1 year')->format('Y-m-d'),
            'adults' => fake()->numberBetween(1, 10),
            'children' => fake()->optional()->numberBetween(0, 10),
            'message' => fake()->optional()->paragraph(),
        ];
    }
}
