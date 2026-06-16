<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { Calendar, Clock, User } from 'lucide-vue-next';
import { show as blogShow, index as blogIndex } from '@/actions/App/Http/Controllers/BlogController';
import { home } from '@/routes';

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

    <section class="post-hero">
        <div
            class="post-hero-bg"
            :style="post.featured_image ? { backgroundImage: `url(${post.featured_image})` } : {}"
        />
        <div class="post-hero-overlay" />
        <div class="tf-container relative z-[2]">
            <div class="post-hero-content">
                <nav class="post-hero-breadcrumb">
                    <Link :href="home()">Home</Link>
                    <span class="mx-2">/</span>
                    <Link :href="blogIndex()">Blog</Link>
                </nav>

                <h1 class="post-hero-title">{{ post.title }}</h1>

                <div class="post-hero-meta">
                    <span class="post-hero-author">
                        <User class="size-4" />
                        {{ post.author }}
                    </span>
                    <span class="post-hero-date">
                        <Calendar class="size-4" />
                        {{ post.published_at_display }}
                    </span>
                    <span v-if="post.content" class="post-hero-read">
                        <Clock class="size-4" />
                        {{ Math.ceil(post.content.length / 800) }} min read
                    </span>
                </div>

                <div v-if="post.categories.length > 0" class="post-hero-categories">
                    <Link
                        v-for="category in post.categories"
                        :key="category.id"
                        href="#"
                        class="post-hero-category"
                    >
                        {{ category.name }}
                    </Link>
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
                            <div class="recent-post-list">
                                <div v-for="recent in recentPosts" :key="recent.id" class="list-recent flex-three">
                                    <Link :href="blogShow({ post: recent.slug })" class="recent-image">
                                        <img v-if="recent.featured_image" :src="recent.featured_image" alt="" />
                                    </Link>
                                    <div class="recent-info">
                                        <div class="date">
                                            <Calendar class="inline size-4" />
                                            <span>{{ recent.published_at }}</span>
                                        </div>
                                        <h4 class="title">
                                            <Link :href="blogShow({ post: recent.slug })">{{ recent.title }}</Link>
                                        </h4>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="sidebar-widget" v-if="categories.length > 0">
                            <h4 class="block-heading">Categories</h4>
                            <ul class="category-blog">
                                <li v-for="category in categories" :key="category.id">
                                    <a href="#" class="flex-two">
                                        <span>{{ category.name }}</span>
                                        <span>{{ String(category.posts_count).padStart(2, '0') }}</span>
                                    </a>
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
.post-hero {
    position: relative;
    min-height: 520px;
    display: flex;
    align-items: flex-end;
    padding: 60px 0 50px;
}

.post-hero-bg {
    position: absolute;
    inset: 0;
    background-size: cover;
    background-position: center;
    background-color: #1a1a2e;
}

.post-hero-overlay {
    position: absolute;
    inset: 0;
    z-index: 1;
    background: linear-gradient(
        to top,
        rgba(0, 0, 0, 0.75) 0%,
        rgba(0, 0, 0, 0.45) 40%,
        rgba(0, 0, 0, 0.3) 100%
    );
}

.post-hero-content {
    max-width: 720px;
    margin: 0 auto;
    padding-top: 120px;
    text-align: center;
}

.post-hero-breadcrumb {
    font-size: 14px;
    margin-bottom: 20px;
    justify-content: center;
    display: flex;
}

.post-hero-breadcrumb a {
    color: rgba(255, 255, 255, 0.7);
}

.post-hero-breadcrumb a:hover {
    color: #fff;
}

.post-hero-title {
    font-size: clamp(28px, 5vw, 46px);
    font-weight: 700;
    line-height: 1.15;
    color: #fff;
    margin-bottom: 22px;
    letter-spacing: -0.5px;
}

.post-hero-meta {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: center;
    gap: 20px;
    margin-bottom: 18px;
    font-size: 14px;
    color: rgba(255, 255, 255, 0.75);
}

.post-hero-meta span {
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.post-hero-categories {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 8px;
}

.post-hero-category {
    font-size: 12px;
    font-weight: 500;
    color: #fff;
    background: rgba(255, 255, 255, 0.15);
    padding: 5px 14px;
    border-radius: 20px;
    backdrop-filter: blur(4px);
    transition: background 0.2s ease;
}

.post-hero-category:hover {
    background: rgba(255, 255, 255, 0.25);
}

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
    .post-hero {
        min-height: 400px;
        padding: 40px 0 35px;
    }

    .post-hero-content {
        padding-top: 60px;
    }

    .post-body-section {
        padding: 40px 0;
    }

    .post-content {
        font-size: 16px;
    }
}
</style>
