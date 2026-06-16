<?php

namespace Database\Factories;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<BlogPost>
 */
class BlogPostFactory extends Factory
{
    public function definition(): array
    {
        $title = fake()->sentence(4);

        return [
            'user_id' => User::factory(),
            'title' => $title,
            'slug' => Str::slug($title.'-'.fake()->uuid()),
            'content' => collect(range(1, 3))
                ->map(fn () => '<p>'.fake()->paragraph(3).'</p>')
                ->implode("\n"),
            'featured_image' => 'https://picsum.photos/seed/'.fake()->uuid().'/1200/800',
            'published_at' => fake()->boolean(80) ? now()->subDays(fake()->numberBetween(0, 90)) : null,
            'is_published' => fake()->boolean(80),
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function (BlogPost $post): void {
            if (BlogCategory::query()->count() === 0) {
                BlogCategory::factory()->count(5)->create();
            }

            $categoryIds = BlogCategory::query()
                ->inRandomOrder()
                ->limit(fake()->numberBetween(1, 3))
                ->pluck('id')
                ->all();

            $payload = [];
            foreach (array_values($categoryIds) as $index => $id) {
                $payload[$id] = ['position' => $index + 1];
            }

            $post->categories()->sync($payload);
        });
    }

    public function published(): static
    {
        return $this->state(fn (): array => [
            'is_published' => true,
            'published_at' => now()->subDays(fake()->numberBetween(0, 30)),
        ]);
    }

    public function unpublished(): static
    {
        return $this->state(fn (): array => [
            'is_published' => false,
            'published_at' => null,
        ]);
    }
}
