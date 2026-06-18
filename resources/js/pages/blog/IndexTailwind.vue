<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { Calendar, User } from 'lucide-vue-next';
import Pagination from '@/components/Pagination.vue';
import BlogCard from '@/components/blog/BlogCard.vue';
import { home } from '@/routes';
import { show as blogShow, index as blogIndex } from '@/routes/blog2';
import type { QueryParams } from '@/wayfinder';

interface Post {
    id: number;
    title: string;
    slug: string;
    excerpt: string | null;
    featured_image: string | null;
    published_at: string | null;
    published_at_display: string | null;
    author: string;
    categories: Array<{ id: number; name: string }>;
}

interface Category {
    id: number;
    name: string;
    slug: string;
    posts_count: number;
}

interface RecentPost {
    id: number;
    title: string;
    slug: string;
    featured_image: string | null;
    published_at: string | null;
}

interface PaginatorLink {
    url: string | null;
    label: string;
    active: boolean;
}

interface PostsPaginator {
    current_page: number;
    last_page: number;
    per_page: number;
    prev_page_url: string | null;
    next_page_url: string | null;
    data: Post[];
    links: PaginatorLink[];
}

defineProps<{
    posts: PostsPaginator;
    recentPosts: RecentPost[];
    categories: Category[];
    selectedCategory: { id: number; name: string; slug: string } | null;
}>();
</script>

<template>
    <Head title="Blog" />

    <!-- Breadcrumb -->
    <section class="relative flex items-center bg-cover bg-center bg-no-repeat py-[137px]" style="background-image: url('/template/images/page/breakcrumb.jpg')">
        <div class="absolute inset-0" style="background: linear-gradient(262deg, rgba(0,0,0,0.33), rgba(4,29,11,0.89))"></div>
        <div class="relative z-[1] mx-auto w-full max-w-[1320px] px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="mb-[26px] text-[65px] leading-[82px] font-semibold text-white max-lg:text-[45px] max-lg:leading-[50px]">Blog</h1>
                <nav class="flex items-center justify-center text-[18px] leading-[22.68px]">
                    <Link :href="home()" class="relative mr-[30px] text-[#4DA528] after:absolute after:right-[-18px] after:top-1/2 after:-translate-y-1/2 after:text-[10px] after:text-white after:content-['\203A']">Home</Link>
                    <span class="text-white">Blog</span>
                </nav>
            </div>
        </div>
    </section>

    <!-- Main Blog Section -->
    <section class="py-16 lg:py-24">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col gap-12 lg:flex-row">

                <!-- Blog Grid (Left) -->
                <div class="w-full lg:w-2/3">
                    <div v-if="selectedCategory" class="mb-8 flex items-center gap-3">
                        <span class="text-sm text-gray-500">Showing posts in</span>
                        <span class="rounded-full bg-[#4DA528]/10 px-4 py-1.5 text-sm font-medium text-[#4DA528]">{{ selectedCategory.name }}</span>
                        <Link :href="blogIndex().url" class="text-xs text-gray-400 hover:text-gray-600">Clear</Link>
                    </div>

                    <div v-if="posts.data.length === 0" class="py-16 text-center">
                        <h3 class="text-2xl text-gray-500">No posts found</h3>
                        <p class="mt-2 text-gray-400">Check back soon for new articles.</p>
                    </div>

                    <div v-else class="grid grid-cols-1 gap-8 md:grid-cols-2">
                        <BlogCard v-for="post in posts.data" :key="post.id" :post="post" show-excerpt />
                    </div>

                    <div v-if="posts.last_page > 1" class="mt-12 flex justify-center">
                        <Pagination :paginator="posts" />
                    </div>
                </div>

                <!-- Sidebar (Right) -->
                <aside class="w-full lg:w-1/3">
                    <div class="space-y-8">

                        <!-- Recent Posts Widget -->
                        <div v-if="recentPosts.length > 0" class="rounded-2xl bg-gray-50 p-6">
                            <h4 class="mb-5 border-b border-gray-200 pb-3 text-base font-bold text-gray-900">Recent Posts</h4>
                            <div>
                                <Link
                                    v-for="(recent, index) in recentPosts"
                                    :key="recent.id"
                                    :href="blogShow({ post: recent.slug })"
                                    class="group flex items-center gap-3 border-b border-gray-200 py-4 transition-colors first:pt-0 last:border-b-0 last:pb-0"
                                >
                                    <span class="w-6 shrink-0 text-center text-lg font-bold leading-none tabular-nums text-[#4DA528]">
                                        {{ String(index + 1).padStart(2, '0') }}
                                    </span>
                                    <div class="h-12 w-14 shrink-0 overflow-hidden rounded-md bg-gray-200">
                                        <img v-if="recent.featured_image" :src="recent.featured_image" alt="" class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105" />
                                        <div v-else class="flex h-full w-full items-center justify-center">
                                            <Calendar class="size-4 text-gray-300" />
                                        </div>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <h5 class="line-clamp-2 text-xs font-semibold leading-snug text-gray-900 transition-colors group-hover:text-[#4DA528]">{{ recent.title }}</h5>
                                        <span class="mt-0.5 block text-[11px] text-gray-400">{{ recent.published_at }}</span>
                                    </div>
                                </Link>
                            </div>
                        </div>

                        <!-- Categories Widget -->
                        <div v-if="categories.length > 0" class="rounded-2xl bg-gray-50 p-6">
                            <h4 class="mb-5 border-b border-gray-200 pb-3 text-base font-bold text-gray-900">Categories</h4>
                            <ul>
                                <li v-for="category in categories" :key="category.id">
                                    <Link
                                        :href="blogIndex({ query: { category: category.slug } as QueryParams }).url"
                                        class="flex items-center justify-between border-b border-gray-200 px-4 py-3 text-sm text-gray-600 transition-colors last:border-b-0 hover:bg-gray-100 hover:text-[#4DA528]"
                                    >
                                        <span>{{ category.name }}</span>
                                        <span class="font-medium text-gray-400">{{ category.posts_count }} {{ category.posts_count === 1 ? 'post' : 'posts' }}</span>
                                    </Link>
                                </li>
                            </ul>
                        </div>

                    </div>
                </aside>

            </div>
        </div>
    </section>
</template>
