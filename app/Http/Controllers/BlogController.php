<?php

namespace App\Http\Controllers;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\Country;
use App\Models\Tour;
use App\Models\TourActivity;
use App\Models\TourCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Number;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class BlogController extends Controller
{
    public function index(Request $request): Response
    {
        $selectedCategory = null;

        if ($request->filled('category')) {
            $selectedCategory = BlogCategory::query()
                ->where('slug', $request->input('category'))
                ->first(['id', 'name', 'slug']);
        }

        $posts = BlogPost::query()
            ->where('is_published', true)
            ->whereNotNull('published_at')
            ->when($selectedCategory, fn ($query) => $query
                ->whereHas('categories', fn ($q) => $q->where('blog_categories.id', $selectedCategory->id))
            )
            ->with(['user:id,name', 'categories:id,name'])
            ->latest('published_at')
            ->paginate(12, ['id', 'user_id', 'title', 'slug', 'content', 'featured_image', 'published_at'])
            ->appends($request->only('category'))
            ->through(fn (BlogPost $post) => $this->mapPublicPost($post));

        $recentPosts = BlogPost::query()
            ->where('is_published', true)
            ->whereNotNull('published_at')
            ->latest('published_at')
            ->limit(5)
            ->get(['id', 'title', 'slug', 'featured_image', 'published_at'])
            ->map(fn (BlogPost $post) => [
                'id' => $post->id,
                'title' => $post->title,
                'slug' => $post->slug,
                'featured_image' => $this->resolveImageUrl($post->featured_image),
                'published_at' => $post->published_at?->format('M j, Y'),
            ]);

        $categories = BlogCategory::query()
            ->withCount(['posts' => fn ($query) => $query
                ->where('is_published', true)
                ->whereNotNull('published_at'),
            ])
            ->orderBy('name')
            ->get(['id', 'name', 'slug'])
            ->map(fn (BlogCategory $category) => [
                'id' => $category->id,
                'name' => $category->name,
                'slug' => $category->slug,
                'posts_count' => $category->posts_count,
            ]);

        return Inertia::render('blog/Index', [
            'posts' => $posts,
            'recentPosts' => $recentPosts,
            'categories' => $categories,
            'selectedCategory' => $selectedCategory ? [
                'id' => $selectedCategory->id,
                'name' => $selectedCategory->name,
                'slug' => $selectedCategory->slug,
            ] : null,
        ]);
    }

    public function show(BlogPost $post): Response
    {
        abort_unless($post->is_published && $post->published_at !== null, 404);

        $post->load(['user:id,name', 'categories:id,name,slug']);

        $recentPosts = BlogPost::query()
            ->where('is_published', true)
            ->whereNotNull('published_at')
            ->where('id', '!=', $post->id)
            ->latest('published_at')
            ->limit(5)
            ->get(['id', 'title', 'slug', 'featured_image', 'published_at'])
            ->map(fn (BlogPost $recent) => [
                'id' => $recent->id,
                'title' => $recent->title,
                'slug' => $recent->slug,
                'featured_image' => $this->resolveImageUrl($recent->featured_image),
                'published_at' => $recent->published_at?->format('M j, Y'),
            ]);

        $categories = BlogCategory::query()
            ->withCount(['posts' => fn ($query) => $query
                ->where('is_published', true)
                ->whereNotNull('published_at'),
            ])
            ->orderBy('name')
            ->get(['id', 'name', 'slug'])
            ->map(fn (BlogCategory $category) => [
                'id' => $category->id,
                'name' => $category->name,
                'slug' => $category->slug,
                'posts_count' => $category->posts_count,
            ]);

        return Inertia::render('blog/Show', [
            'post' => [
                'id' => $post->id,
                'title' => $post->title,
                'slug' => $post->slug,
                'content' => $post->content,
                'excerpt' => $this->makeExcerpt($post->content),
                'featured_image' => $this->resolveImageUrl($post->featured_image),
                'published_at' => $post->published_at?->toIso8601String(),
                'published_at_display' => $post->published_at?->format('M j, Y'),
                'author' => $post->user?->name ?? 'Unknown',
                'categories' => $post->categories->map(fn ($category) => [
                    'id' => $category->id,
                    'name' => $category->name,
                    'slug' => $category->slug,
                ])->all(),
            ],
            'recentPosts' => $recentPosts,
            'categories' => $categories,
        ]);
    }

    public function home(): Response
    {
        $posts = BlogPost::query()
            ->where('is_published', true)
            ->whereNotNull('published_at')
            ->with(['user:id,name', 'categories:id,name'])
            ->latest('published_at')
            ->limit(6)
            ->get(['id', 'user_id', 'title', 'slug', 'content', 'featured_image', 'published_at'])
            ->map(fn (BlogPost $post) => [
                'id' => $post->id,
                'title' => $post->title,
                'slug' => $post->slug,
                'excerpt' => $this->makeExcerpt($post->content),
                'featured_image' => $this->resolveImageUrl($post->featured_image),
                'published_at_display' => $post->published_at?->format('M j, Y'),
                'author' => $post->user?->name ?? 'Unknown',
                'categories' => $post->categories->map(fn ($category) => [
                    'id' => $category->id,
                    'name' => $category->name,
                ])->all(),
            ]);

        $countries = Country::query()
            ->with(['places' => fn ($query) => $query->select('id', 'country_id', 'name')->orderBy('name')])
            ->orderBy('name')
            ->get(['id', 'name', 'country_code'])
            ->map(fn ($country) => [
                'id' => $country->id,
                'name' => $country->name,
                'country_code' => $country->country_code,
                'places' => $country->places->map(fn ($place) => ['id' => $place->id, 'name' => $place->name])->all(),
            ]);

        $categories = TourCategory::query()
            ->orderBy('name')
            ->get(['id', 'name']);

        $activities = TourActivity::query()
            ->orderBy('name')
            ->get(['id', 'name']);

        $featuredTours = Tour::query()
            ->where('is_hidden', false)
            ->whereHas('places')
            ->with(['categories:id,name', 'places:id,name,country_id', 'places.country:id,name,country_code'])
            ->latest('id')
            ->limit(12)
            ->get()
            ->map(fn (Tour $tour) => [
                'id' => $tour->id,
                'name' => $tour->name,
                'main_image' => $this->resolveImageUrl($tour->main_image),
                'price' => Number::currency($tour->price, precision: 0),
                'price_amount' => (float) $tour->price,
                'duration_days' => $tour->duration_days,
                'categories' => $tour->categories->map(fn ($c) => ['id' => $c->id, 'name' => $c->name])->all(),
                'places' => $tour->places->map(fn ($p) => ['id' => $p->id, 'name' => $p->name, 'country' => $p->country?->name, 'country_code' => $p->country?->country_code])->all(),
            ]);

        return Inertia::render('Home', [
            'blogPosts' => $posts,
            'countries' => $countries,
            'categories' => $categories,
            'activities' => $activities,
            'featuredTours' => $featuredTours,
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    private function mapPublicPost(BlogPost $post): array
    {
        return [
            'id' => $post->id,
            'title' => $post->title,
            'slug' => $post->slug,
            'excerpt' => $this->makeExcerpt($post->content),
            'featured_image' => $this->resolveImageUrl($post->featured_image),
            'published_at' => $post->published_at?->toIso8601String(),
            'published_at_display' => $post->published_at?->format('M j, Y'),
            'author' => $post->user?->name ?? 'Unknown',
            'categories' => $post->categories->map(fn ($category) => [
                'id' => $category->id,
                'name' => $category->name,
            ])->all(),
        ];
    }

    private function resolveImageUrl(?string $path): ?string
    {
        if (blank($path)) {
            return null;
        }

        return Str::startsWith($path, ['http://', 'https://', '/'])
            ? $path
            : Storage::disk('public')->url($path);
    }

    private function makeExcerpt(?string $content, int $limit = 200): ?string
    {
        if (blank($content)) {
            return null;
        }

        $text = strip_tags($content);
        $text = html_entity_decode($text, ENT_QUOTES, 'UTF-8');
        $text = trim((string) preg_replace('/\s+/', ' ', $text));

        if (mb_strlen($text) <= $limit) {
            return $text;
        }

        $excerpt = mb_substr($text, 0, $limit);
        $lastSpace = mb_strrpos($excerpt, ' ');

        if ($lastSpace !== false) {
            $excerpt = mb_substr($excerpt, 0, $lastSpace);
        }

        return $excerpt.'...';
    }
}
