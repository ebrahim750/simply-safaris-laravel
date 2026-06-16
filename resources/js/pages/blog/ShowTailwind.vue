<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { Calendar, Clock, User } from 'lucide-vue-next';
import { home } from '@/routes';
import { show as blogShow, index as blogIndex } from '@/routes/blog2';
import type { QueryParams } from '@/wayfinder';

interface PostCategory {
    id: number;
    name: string;
    slug: string;
}

interface PostData {
    id: number;
    title: string;
    slug: string;
    excerpt: string | null;
    content: string | null;
    featured_image: string | null;
    published_at: string | null;
    published_at_display: string | null;
    author: string;
    categories: PostCategory[];
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

defineProps<{
    post: PostData;
    recentPosts: RecentPost[];
    categories: Category[];
}>();
</script>

<template>
    <Head :title="post.title" />

    <!-- Breadcrumb -->
    <section
        class="relative flex items-center bg-cover bg-center bg-no-repeat py-[137px]"
        :style="{ backgroundImage: post.featured_image ? `url(${post.featured_image})` : `url('/template/images/page/breakcrumb.jpg')` }"
    >
        <div class="absolute inset-0" style="background: linear-gradient(262deg, rgba(0,0,0,0.33), rgba(4,29,11,0.89))"></div>
        <div class="relative z-[1] mx-auto w-full max-w-[1320px] px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <nav class="mb-[26px] flex items-center justify-center text-[18px] leading-[22.68px]">
                    <Link :href="home()" class="relative mr-[30px] text-[#4DA528] after:absolute after:right-[-18px] after:top-1/2 after:-translate-y-1/2 after:text-[10px] after:text-white after:content-['\203A']">Home</Link>
                    <Link :href="blogIndex()" class="relative mr-[30px] text-[#4DA528] after:absolute after:right-[-18px] after:top-1/2 after:-translate-y-1/2 after:text-[10px] after:text-white after:content-['\203A']">Blog</Link>
                    <span class="text-white">{{ post.title }}</span>
                </nav>

                <h1 class="mb-[26px] text-[65px] font-semibold leading-[82px] text-white max-lg:text-[45px] max-lg:leading-[50px]">{{ post.title }}</h1>

                <div class="mt-3 flex items-center justify-center gap-5 text-sm text-white/75">
                    <span class="inline-flex items-center gap-1.5">
                        <User class="size-4" />
                        {{ post.author }}
                    </span>
                    <span class="inline-flex items-center gap-1.5">
                        <Calendar class="size-4" />
                        {{ post.published_at_display }}
                    </span>
                    <span v-if="post.content" class="inline-flex items-center gap-1.5">
                        <Clock class="size-4" />
                        {{ Math.ceil(post.content.length / 800) }} min read
                    </span>
                </div>

                <div v-if="post.categories.length > 0" class="mt-4 flex flex-wrap justify-center gap-2">
                    <Link
                        v-for="category in post.categories"
                        :key="category.id"
                        :href="blogIndex({ query: { category: category.slug } as QueryParams }).url"
                        class="rounded-full px-4 py-1.5 text-xs font-medium text-white transition-colors"
                        style="background: rgba(255,255,255,0.15); backdrop-filter: blur(4px);"
                    >
                        {{ category.name }}
                    </Link>
                </div>
            </div>
        </div>
    </section>

    <!-- Post Content -->
    <section class="py-[60px] max-md:py-10">
        <div class="mx-auto max-w-[1320px] px-4 sm:px-6 lg:px-8">
            <div class="flex flex-wrap">
                <!-- Main Content -->
                <div class="w-full lg:w-8/12">
                    <article v-if="post.content" class="post-content" v-html="post.content" />
                    <div v-else class="py-16 text-center">
                        <p class="text-gray-400">No content yet.</p>
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
            <img class="absolute left-[7%] top-[40%] hidden w-auto lg:block" src="/template/images/page/mask-bcrumb.png" alt="" />
        </div>
                                <div class="min-w-0 flex-1">
                                    <h5 class="line-clamp-2 text-[12px] font-semibold leading-snug text-gray-600 transition-colors group-hover:text-[#4DA528]">{{ recent.title }}</h5>
                                    <span class="mt-0.5 block text-[11px] text-gray-400">{{ recent.published_at }}</span>
                                </div>
                            </Link>
                </div>
                <img class="absolute left-[7%] top-[40%] hidden w-auto lg:block" src="/template/images/page/mask-bcrumb.png" alt="" />
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
.post-content {
    font-size: 17px;
    line-height: 1.85;
    color: #333;
}

.post-content :deep(p) {
    margin-bottom: 1.5em;
}

.post-content :deep(h2) {
    font-size: 26px;
    font-weight: 700;
    color: #081e2a;
    margin: 2em 0 0.6em;
}

.post-content :deep(h3) {
    font-size: 21px;
    font-weight: 600;
    color: #081e2a;
    margin: 1.8em 0 0.5em;
}

.post-content :deep(img) {
    border-radius: 10px;
    margin: 2em 0;
    max-width: 100%;
}

.post-content :deep(blockquote) {
    border-left: 3px solid #4DA528;
    padding: 16px 24px;
    margin: 2em 0;
    background: #f8faf8;
    border-radius: 0 8px 8px 0;
    font-style: italic;
    color: #555;
}

.post-content :deep(ul),
.post-content :deep(ol) {
    padding-left: 1.5em;
    margin-bottom: 1.5em;
}

.post-content :deep(li) {
    margin-bottom: 0.5em;
}

.post-content :deep(a) {
    color: #4DA528;
    text-decoration: underline;
}

@media (max-width: 767px) {
    .post-content {
        font-size: 16px;
    }
}

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
</style>
