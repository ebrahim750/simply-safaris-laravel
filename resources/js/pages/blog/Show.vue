<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { Calendar, Clock, User } from 'lucide-vue-next';
import { show as blogShow, index as blogIndex } from '@/actions/App/Http/Controllers/BlogController';
import { home } from '@/routes';
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

    <section
        class="breadcumb-section"
        :style="post.featured_image ? { backgroundImage: `url(${post.featured_image})` } : {}"
    >
        <div class="tf-container relative z-index1">
            <div class="row">
                <div class="col-lg-12 center">
                    <ul class="breadcumb-list flex-five justify-center">
                        <li><Link :href="home()">Home</Link></li>
                        <li><Link :href="blogIndex()">Blog</Link></li>
                        <li><span>{{ post.title }}</span></li>
                    </ul>

                    <h1 class="title">{{ post.title }}</h1>

                    <div class="flex items-center justify-center gap-5 mt-3 text-sm" style="color: rgba(255,255,255,0.75)">
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

                    <div v-if="post.categories.length > 0" class="flex flex-wrap justify-center gap-2 mt-4">
                        <Link
                            v-for="category in post.categories"
                            :key="category.id"
                            :href="blogIndex({ query: { category: category.slug } as QueryParams }).url"
                            class="rounded-full px-4 py-1.5 text-xs font-medium transition-colors"
                            style="background: rgba(255,255,255,0.15); color: #fff; backdrop-filter: blur(4px);"
                        >
                            {{ category.name }}
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="post-body-section">
        <div class="tf-container">
            <div class="row">
                <div class="col-lg-8 col-12">
                    <article class="post-content" v-if="post.content" v-html="post.content" />
                    <div v-else class="py-16 text-center">
                        <p class="text-gray-400">No content yet.</p>
                    </div>
                </div>

                <div class="col-lg-4 col-12">
                    <div class="side-bar-right">
                        <div class="sidebar-widget" v-if="recentPosts.length > 0">
                            <h4 class="block-heading">Recent Posts</h4>
                            <div class="space-y-0">
                                <Link
                                    v-for="(recent, index) in recentPosts"
                                    :key="recent.id"
                                    :href="blogShow({ post: recent.slug })"
                                    class="group flex items-center gap-3 border-b border-gray-100 py-4 first:pt-0 last:border-b-0 last:pb-0 transition-colors"
                                >
                                    <span class="w-6 text-center text-lg font-bold leading-none tabular-nums shrink-0 text-[#4DA528]">
                                        {{ String(index + 1).padStart(2, '0') }}
                                    </span>
                                    <div class="h-12 w-14 shrink-0 overflow-hidden rounded-md bg-gray-100">
                                        <img
                                            v-if="recent.featured_image"
                                            :src="recent.featured_image"
                                            alt=""
                                            class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105"
                                        />
                                        <div v-else class="flex h-full w-full items-center justify-center">
                                            <Calendar class="size-4 text-gray-300" />
                                        </div>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <h5 class="text-[12px] font-semibold leading-snug line-clamp-2 group-hover:text-[#4DA528] transition-colors recent-post-title">
                                            {{ recent.title }}
                                        </h5>
                                        <span class="mt-0.5 block text-[11px] text-gray-400">{{ recent.published_at }}</span>
                                    </div>
                                </Link>
                            </div>
                        </div>

                        <div class="sidebar-widget" v-if="categories.length > 0">
                            <h4 class="block-heading">Categories</h4>
                            <ul class="category-blog">
                                <li v-for="category in categories" :key="category.id">
                                    <Link
                                        :href="blogIndex({ query: { category: category.slug } as QueryParams }).url"
                                        class="flex-two"
                                    >
                                        <span>{{ category.name }}</span>
                                        <span>{{ String(category.posts_count).padStart(2, '0') }}</span>
                                    </Link>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<style scoped>
.post-body-section {
    padding: 60px 0;
}

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
    .post-body-section {
        padding: 40px 0;
    }

    .post-content {
        font-size: 16px;
    }
}

.recent-post-title {
    color: #4b5563;
}

.recent-post-title:hover {
    color: #4DA528;
}
</style>
