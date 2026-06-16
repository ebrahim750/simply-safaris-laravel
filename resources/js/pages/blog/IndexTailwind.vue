<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { Calendar, User } from 'lucide-vue-next';
import Pagination from '@/components/Pagination.vue';
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
    <section
        class="relative flex items-center bg-cover bg-center bg-no-repeat py-[137px]"
        style="background-image: url('/template/images/page/breakcrumb.jpg')"
    >
        <div class="absolute inset-0" style="background: linear-gradient(262deg, rgba(0,0,0,0.33), rgba(4,29,11,0.89))"></div>
        <div class="relative z-[1] mx-auto w-full max-w-[1320px] px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="mb-[26px] text-[65px] font-semibold leading-[82px] text-white max-lg:text-[45px] max-lg:leading-[50px]">Blog</h1>
                <nav class="flex items-center justify-center text-[18px] leading-[22.68px]">
                    <Link :href="home()" class="relative mr-[30px] text-[#4DA528] after:absolute after:right-[-18px] after:top-1/2 after:-translate-y-1/2 after:text-[10px] after:text-white after:content-['\203A']">Home</Link>
                    <span class="text-white">Blog</span>
                </nav>
                <img class="absolute left-[7%] top-[40%] hidden w-auto lg:block" src="/template/images/page/mask-bcrumb.png" alt="" />
            </div>
        </div>
    </section>

    <!-- Blog Content -->
    <section class="py-[120px] max-lg:py-20">
        <div class="mx-auto max-w-[1320px] px-4 sm:px-6 lg:px-8">
            <div class="flex flex-wrap">
                <!-- Main Content -->
                <div class="w-full lg:w-8/12">
                    <div v-if="selectedCategory" class="mb-6 flex items-center gap-3">
                        <span class="text-sm text-gray-500">Showing posts in</span>
                        <span class="rounded-full bg-[#4DA528]/10 px-4 py-1.5 text-sm font-medium text-[#4DA528]">{{ selectedCategory.name }}</span>
                        <Link :href="blogIndex().url" class="text-xs text-gray-400 hover:text-gray-600">Clear</Link>
                    </div>

                    <div v-if="posts.data.length === 0" class="py-16 text-center">
                        <h3 class="text-2xl text-gray-500">No posts found</h3>
                        <p class="mt-2 text-gray-400">Check back soon for new articles.</p>
                    </div>

                    <div v-else class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <article v-for="post in posts.data" :key="post.id" class="overflow-hidden rounded-[12px] border border-[#f0f0f0] bg-white transition-shadow hover:shadow-[0_8px_30px_rgba(0,0,0,0.08)]">
                            <Link :href="blogShow({ post: post.slug })" class="block h-[220px] overflow-hidden bg-gray-50">
                                <img v-if="post.featured_image" :src="post.featured_image" :alt="post.title" class="h-full w-full object-cover transition-transform duration-[0.4s] ease-out hover:scale-105" />
                                <div v-else class="flex h-full w-full items-center justify-center bg-gray-100">
                                    <span class="text-gray-400">No image</span>
                                </div>
                            </Link>

                            <div class="p-5">
                                <div class="mb-2.5 flex items-center gap-3">
                                    <span class="inline-flex items-center rounded-full bg-[#4DA528]/[0.08] px-2.5 py-[3px] text-[12px] font-medium text-[#4DA528]">{{ post.published_at_display }}</span>
                                    <span class="inline-flex items-center gap-1 text-[12px] capitalize leading-none text-gray-400">
                                        <User class="size-3.5 align-middle" />
                                        {{ post.author }}
                                    </span>
                                </div>

                                <h3 class="mb-2.5 text-[15px] font-semibold leading-[1.4]">
                                    <Link :href="blogShow({ post: post.slug })" class="line-clamp-2 text-[#334155] hover:text-[#4DA528]">{{ post.title }}</Link>
                                </h3>

                                <p v-if="post.excerpt" class="mb-3.5 line-clamp-3 text-[14px] leading-[1.6] text-gray-500">{{ post.excerpt }}</p>

                                <Link :href="blogShow({ post: post.slug })" class="blog-card-link inline-flex items-center gap-1 text-[13px] font-semibold text-[#4DA528] transition-all hover:gap-2">
                                    Read More
                                </Link>
                            </div>
                        </article>
                    </div>

                    <div v-if="posts.last_page > 1" class="mt-5 flex justify-center">
                        <Pagination :paginator="posts" />
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="w-full lg:w-4/12 lg:pl-[33px] max-lg:mt-10">
                    <div v-if="recentPosts.length > 0" class="sidebar-widget-wrapper mb-[31px] bg-[#F4F6F8] px-[30px] py-[34px]">
                        <h4 class="block-heading-tailwind mb-[22px] pl-[15px] text-[20px] font-semibold capitalize text-[#081E2A]">Recent Posts</h4>
                        <div>
                            <Link
                                v-for="(recent, index) in recentPosts"
                                :key="recent.id"
                                :href="blogShow({ post: recent.slug })"
                                class="group flex items-center gap-3 border-b border-gray-100 py-4 transition-colors first:pt-0 last:border-b-0 last:pb-0"
                            >
                                <span class="w-6 shrink-0 text-center text-lg font-bold leading-none tabular-nums text-[#4DA528]">
                                    {{ String(index + 1).padStart(2, '0') }}
                                </span>
                                <div class="h-12 w-14 shrink-0 overflow-hidden rounded-md bg-gray-100">
                                    <img v-if="recent.featured_image" :src="recent.featured_image" alt="" class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105" />
                                    <div v-else class="flex h-full w-full items-center justify-center">
                                        <Calendar class="size-4 text-gray-300" />
                                    </div>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <h5 class="line-clamp-2 text-[12px] font-semibold leading-snug text-gray-600 transition-colors group-hover:text-[#4DA528]">{{ recent.title }}</h5>
                                    <span class="mt-0.5 block text-[11px] text-gray-400">{{ recent.published_at }}</span>
                                </div>
                            </Link>
                        </div>
                    </div>

                    <div v-if="categories.length > 0" class="sidebar-widget-wrapper bg-[#F4F6F8] px-[30px] py-[34px]">
                        <h4 class="block-heading-tailwind mb-[22px] pl-[15px] text-[20px] font-semibold capitalize text-[#081E2A]">Categories</h4>
                        <ul class="category-blog-tailwind">
                            <li v-for="category in categories" :key="category.id" class="[&:not(:last-child)]:mb-[10px]">
                                <Link
                                    :href="blogIndex({ query: { category: category.slug } as QueryParams }).url"
                                    class="flex items-center justify-between border-b border-dashed border-[rgba(0,0,0,0.1)] px-0 py-4 text-[16px] font-medium leading-[28px] text-[#333] transition-colors hover:text-[#4DA528]"
                                >
                                    <span>{{ category.name }}</span>
                                    <span class="text-[#4DA528]">{{ String(category.posts_count).padStart(2, '0') }}</span>
                                </Link>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<style scoped>
.block-heading-tailwind {
    position: relative;
}

.block-heading-tailwind::before {
    position: absolute;
    content: '';
    height: 100%;
    width: 2px;
    background-color: #4DA528;
    left: 0;
}

.blog-card-link::after {
    content: '\2192';
    font-size: 14px;
    transition: transform 0.2s ease;
}
</style>
