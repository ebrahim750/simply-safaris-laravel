<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreBlogPostRequest;
use App\Http\Requests\Admin\UpdateBlogPostRequest;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class BlogPostController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('admin/blog/Posts', [
            'posts' => BlogPost::query()
                ->latest('id')
                ->with(['user:id,name', 'categories:id,name'])
                ->get()
                ->map(fn (BlogPost $post): array => $this->mapIndexPost($post)),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('admin/blog/Create', [
            'categories' => BlogCategory::query()
                ->orderBy('name')
                ->get(['id', 'name']),
        ]);
    }

    public function store(StoreBlogPostRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        DB::transaction(function () use ($request, $validated): void {
            $slug = $validated['slug'] ?? null;
            $slug = $slug ?: Str::slug($validated['title']);

            if (BlogPost::query()->where('slug', $slug)->exists()) {
                $slug = $slug.'-'.Str::random(6);
            }

            $post = BlogPost::query()->create([
                'user_id' => $request->user()?->id,
                'title' => $validated['title'],
                'slug' => $slug,
                'content' => $validated['content'] ?? null,
                'featured_image' => '',
                'published_at' => $validated['published_at'] ?? null,
                'is_published' => $validated['is_published'],
            ]);

            if ($request->hasFile('featured_image')) {
                $post->forceFill([
                    'featured_image' => $this->storeImage($request->file('featured_image'), 'blog/'.$post->id),
                ])->save();
            }

            $post->categories()->sync($this->buildPivotPayload($validated['category_ids'] ?? []));
        });

        return to_route('admin.blog')
            ->with('status', 'Post created.');
    }

    public function edit(BlogPost $post): Response
    {
        return Inertia::render('admin/blog/Edit', [
            'post' => [
                'id' => $post->id,
                'title' => $post->title,
                'slug' => $post->slug,
                'content' => $post->content,
                'featured_image' => blank($post->featured_image)
                    ? null
                    : (Str::startsWith($post->featured_image, ['http://', 'https://', '/'])
                        ? $post->featured_image
                        : Storage::disk('public')->url($post->featured_image)),
                'is_published' => $post->is_published,
                'published_at' => $post->published_at?->toIso8601String(),
                'category_ids' => $post->categories->pluck('id')->all(),
            ],
            'categories' => BlogCategory::query()
                ->orderBy('name')
                ->get(['id', 'name']),
        ]);
    }

    public function update(UpdateBlogPostRequest $request, BlogPost $post): RedirectResponse
    {
        $validated = $request->validated();

        DB::transaction(function () use ($request, $validated, $post): void {
            $slug = $validated['slug'] ?? null;
            $slug = $slug ?: Str::slug($validated['title']);

            if ($slug !== $post->slug && BlogPost::query()->where('slug', $slug)->exists()) {
                $slug = $slug.'-'.Str::random(6);
            }

            $post->update([
                'title' => $validated['title'],
                'slug' => $slug,
                'content' => $validated['content'] ?? null,
                'published_at' => $validated['published_at'] ?? null,
                'is_published' => $validated['is_published'],
            ]);

            if ($request->hasFile('featured_image')) {
                if ($post->featured_image) {
                    Storage::disk('public')->delete($post->featured_image);
                }
                $post->forceFill([
                    'featured_image' => $this->storeImage($request->file('featured_image'), 'blog/'.$post->id),
                ])->save();
            }

            $post->categories()->sync($this->buildPivotPayload($validated['category_ids'] ?? []));
        });

        return to_route('admin.blog.edit', $post)
            ->with('status', 'Post updated.');
    }

    public function togglePublished(BlogPost $post): RedirectResponse
    {
        $post->forceFill([
            'is_published' => ! $post->is_published,
            'published_at' => ! $post->is_published ? now() : $post->published_at,
        ])->save();

        return back();
    }

    public function destroy(BlogPost $post): RedirectResponse
    {
        if ($post->featured_image) {
            Storage::disk('public')->delete($post->featured_image);
        }

        $post->delete();

        return to_route('admin.blog')
            ->with('status', 'Post deleted.');
    }

    /**
     * @return array<string, mixed>
     */
    private function mapIndexPost(BlogPost $post): array
    {
        return [
            'id' => $post->id,
            'title' => $post->title,
            'slug' => $post->slug,
            'featured_image' => blank($post->featured_image)
                ? null
                : (Str::startsWith($post->featured_image, ['http://', 'https://', '/'])
                    ? $post->featured_image
                    : Storage::disk('public')->url($post->featured_image)),
            'author' => $post->user?->name ?? 'Unknown',
            'is_published' => $post->is_published,
            'categories' => $post->categories->map(fn ($category) => ['id' => $category->id, 'name' => $category->name])->all(),
            'created_at' => $post->created_at?->toIso8601String(),
            'created_at_display' => $post->created_at?->format('M j, Y g:i A') ?? 'N/A',
        ];
    }

    private function storeImage(UploadedFile $file, string $directory): string
    {
        return $file->store($directory, 'public');
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
}
