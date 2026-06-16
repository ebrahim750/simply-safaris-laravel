<?php

use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;

uses(RefreshDatabase::class);

test('blog index page renders published posts', function () {
    $posts = BlogPost::factory()->count(5)->published()->create();

    $this->get(route('blog.index'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('blog/Index')
            ->has('posts.data', 5)
            ->has('recentPosts', 3)
            ->has('categories')
        );
});

test('blog index page hides unpublished posts', function () {
    BlogPost::factory()->count(3)->published()->create();
    BlogPost::factory()->count(2)->unpublished()->create();

    $this->get(route('blog.index'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('blog/Index')
            ->has('posts.data', 3)
        );
});

test('blog index page returns empty when no posts exist', function () {
    $this->get(route('blog.index'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('blog/Index')
            ->has('posts.data', 0)
        );
});

test('blog show page renders published post by slug', function () {
    $post = BlogPost::factory()->published()->create([
        'title' => 'My Amazing Safari',
        'slug' => 'my-amazing-safari',
    ]);

    $this->get(route('blog.show', $post->slug))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('blog/Show')
            ->where('post.title', 'My Amazing Safari')
            ->where('post.slug', 'my-amazing-safari')
        );
});

test('blog show returns 404 for unpublished post', function () {
    $post = BlogPost::factory()->unpublished()->create();

    $this->get(route('blog.show', $post->slug))
        ->assertNotFound();
});

test('blog show returns 404 for post with null published_at', function () {
    $post = BlogPost::factory()->create([
        'is_published' => true,
        'published_at' => null,
    ]);

    $this->get(route('blog.show', $post->slug))
        ->assertNotFound();
});

test('admin users can view the blog posts page', function () {
    $adminUser = User::factory()->create(['account_type' => 'admin']);
    BlogPost::factory()->count(3)->published()->create();

    $this->actingAs($adminUser)
        ->get(route('admin.blog'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('admin/blog/Posts')
            ->has('posts', 3)
        );
});

test('non-admin users cannot view the blog posts page', function () {
    $user = User::factory()->create(['account_type' => 'customer']);

    $this->actingAs($user)
        ->get(route('admin.blog'))
        ->assertForbidden();
});

test('admin users can create a blog post', function () {
    $adminUser = User::factory()->create(['account_type' => 'admin']);
    $category = BlogCategory::factory()->create();

    $this->actingAs($adminUser)
        ->post(route('admin.blog.store'), [
            'title' => 'A New Blog Post',
            'content' => '<p>Some content.</p>',
            'category_ids' => [$category->id],
            'is_published' => true,
        ])
        ->assertRedirect(route('admin.blog'));

    expect(BlogPost::query()->where('title', 'A New Blog Post')->exists())->toBeTrue();
});

test('admin users can edit a blog post', function () {
    $adminUser = User::factory()->create(['account_type' => 'admin']);
    $post = BlogPost::factory()->published()->create(['title' => 'Original Title']);

    $this->actingAs($adminUser)
        ->patch(route('admin.blog.update', $post), [
            'title' => 'Updated Title',
            'content' => $post->content,
            'category_ids' => [],
            'is_published' => true,
        ])
        ->assertRedirect(route('admin.blog.edit', $post));

    expect($post->refresh()->title)->toBe('Updated Title');
});

test('admin users can toggle blog post publish status', function () {
    $adminUser = User::factory()->create(['account_type' => 'admin']);
    $post = BlogPost::factory()->published()->create();

    $this->actingAs($adminUser)
        ->patch(route('admin.blog.published.toggle', $post))
        ->assertRedirect();

    expect($post->refresh()->is_published)->toBeFalse();
});

test('admin users can delete a blog post', function () {
    $adminUser = User::factory()->create(['account_type' => 'admin']);
    $post = BlogPost::factory()->published()->create();

    $this->actingAs($adminUser)
        ->delete(route('admin.blog.destroy', $post))
        ->assertRedirect(route('admin.blog'));

    expect(BlogPost::query()->find($post->id))->toBeNull();
});

test('blog index includes categories with post counts', function () {
    $category = BlogCategory::factory()->create();
    BlogCategory::factory()->count(2)->create();

    BlogPost::factory()
        ->published()
        ->create()
        ->categories()
        ->attach($category->id, ['position' => 1]);

    $this->get(route('blog.index'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->has('categories', 3)
        );
});
