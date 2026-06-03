<?php

namespace Database\Factories;

use App\Models\Country;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Country>
 */
class CountryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->country();

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'country_code' => fake()->countryCode(),
            'overview' => null,
            'main_image' => null,
            'best_time_to_go' => null,
            'high_season' => null,
            'tourism_link' => null,
        ];
    }
}
