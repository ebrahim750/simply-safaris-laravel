<?php

namespace Database\Factories;

use App\Models\Place;
use App\Models\Tour;
use App\Models\TourActivity;
use App\Models\TourCategory;
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
            'main_image' => 'https://picsum.photos/seed/'.fake()->uuid().'/1200/800',
            'gallery_images' => collect(range(1, 10))
                ->map(fn () => 'https://picsum.photos/seed/'.fake()->uuid().'/1200/800')
                ->all(),
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

    public function configure(): static
    {
        return $this->afterCreating(function (Tour $tour): void {
            if (TourCategory::query()->count() === 0) {
                TourCategory::factory()->count(5)->create();
            }

            if (TourActivity::query()->count() === 0) {
                TourActivity::factory()->count(5)->create();
            }

            if (Place::query()->count() === 0) {
                Place::factory()->count(5)->create();
            }

            $categoryIds = TourCategory::query()
                ->inRandomOrder()
                ->limit(fake()->numberBetween(1, 3))
                ->pluck('id')
                ->all();

            $activityIds = TourActivity::query()
                ->inRandomOrder()
                ->limit(fake()->numberBetween(1, 3))
                ->pluck('id')
                ->all();

            $placeIds = Place::query()
                ->inRandomOrder()
                ->limit(fake()->numberBetween(1, 3))
                ->pluck('id')
                ->all();

            $tour->categories()->sync($this->buildPivotPayload($categoryIds));
            $tour->activities()->sync($this->buildPivotPayload($activityIds));
            $tour->places()->sync($this->buildPivotPayload($placeIds));

            $tour->itineraryItems()->createMany(
                $this->buildPositionedRecords(fake()->numberBetween(2, 5), ['title', 'content'])
            );

            $tour->inclusions()->createMany(
                $this->buildPositionedRecords(fake()->numberBetween(2, 5), ['text'])
            );

            $tour->exclusions()->createMany(
                $this->buildPositionedRecords(fake()->numberBetween(2, 5), ['text'])
            );
        });
    }

    /**
     * @param  array<int, int>  $ids
     * @return array<int, array{position: int}>
     */
    private function buildPivotPayload(array $ids): array
    {
        $payload = [];

        foreach (array_values($ids) as $index => $id) {
            $payload[$id] = [
                'position' => $index + 1,
            ];
        }

        return $payload;
    }

    /**
     * @param  array<int, string>  $fields
     * @return array<int, array<string, mixed>>
     */
    private function buildPositionedRecords(int $count, array $fields): array
    {
        $records = [];

        foreach (range(1, $count) as $index) {
            $record = [
                'position' => $index,
            ];

            foreach ($fields as $field) {
                $record[$field] = match ($field) {
                    'title' => fake()->sentence(3),
                    'content' => '<p>'.fake()->paragraph().'</p>',
                    'text' => fake()->sentence(),
                    default => null,
                };
            }

            $records[] = $record;
        }

        return $records;
    }
}
