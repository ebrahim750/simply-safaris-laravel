<?php

namespace Database\Seeders;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\User;
use Illuminate\Database\Seeder;

class BlogSeeder extends Seeder
{
    public function run(): void
    {
        if (User::query()->count() === 0) {
            User::factory()->create([
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'account_type' => 'admin',
            ]);
        }

        BlogCategory::factory()
            ->count(5)
            ->create();

        BlogPost::factory()
            ->count(12)
            ->published()
            ->recycle(User::query()->get())
            ->create();

        BlogPost::factory()
            ->count(3)
            ->unpublished()
            ->recycle(User::query()->get())
            ->create();
    }
}
