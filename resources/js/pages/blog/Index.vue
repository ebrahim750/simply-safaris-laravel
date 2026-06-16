<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { Calendar, Clock, User } from 'lucide-vue-next';
import Pagination from '@/components/Pagination.vue';
import { show as blogShow } from '@/actions/App/Http/Controllers/BlogController';
import { home } from '@/routes';

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
}>();
</script>

<template>
    <Head title="Blog" />

    <section class="breadcumb-section">
        <div class="tf-container">
            <div class="row">
                <div class="col-lg-12 center z-index1">
                    <h1 class="title">Blog</h1>
                    <ul class="breadcumb-list flex-five">
                        <li><Link :href="home()">Home</Link></li>
                        <li><span>Blog</span></li>
                    </ul>
                    <img class="bcrumb-ab" src="/template/images/page/mask-bcrumb.png" alt="" />
                </div>
            </div>
        </div>
    </section>

    <section class="our-blog pd-main">
        <div class="tf-container">
            <div class="row">
                <div class="col-lg-8 col-12">
                    <div v-if="posts.data.length === 0" class="text-center py-16">
                        <h3 class="text-2xl text-gray-500">No posts found</h3>
                        <p class="mt-2 text-gray-400">Check back soon for new articles.</p>
                    </div>

                    <div v-else class="blog-grid">
                        <article v-for="post in posts.data" :key="post.id" class="blog-card">
                            <Link :href="blogShow({ post: post.slug })" class="blog-card-image">
                                <img v-if="post.featured_image" :src="post.featured_image" :alt="post.title" />
                                <div v-else class="flex h-full w-full items-center justify-center bg-gray-100">
                                    <span class="text-gray-400">No image</span>
                                </div>
                            </Link>

                            <div class="blog-card-body">
                                <div class="blog-card-meta">
                                    <span class="blog-card-date">{{ post.published_at_display }}</span>
                                    <span class="blog-card-author">{{ post.author }}</span>
                                </div>

                                <h3 class="blog-card-title">
                                    <Link :href="blogShow({ post: post.slug })">{{ post.title }}</Link>
                                </h3>

                                <p v-if="post.excerpt" class="blog-card-excerpt">{{ post.excerpt }}</p>

                                <Link :href="blogShow({ post: post.slug })" class="blog-card-link">
                                    Read More
                                </Link>
                            </div>
                        </article>
                    </div>

                    <div class="mt-10 flex justify-center" v-if="posts.last_page > 1">
                        <Pagination :paginator="posts" />
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
.blog-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 24px;
}

.blog-card {
    background: #fff;
    border-radius: 12px;
    overflow: hidden;
    border: 1px solid #f0f0f0;
    transition: box-shadow 0.3s ease;
}

.blog-card:hover {
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
}

.blog-card-image {
    display: block;
    height: 220px;
    overflow: hidden;
    background: #f9f9f9;
}

.blog-card-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.4s ease;
}

.blog-card:hover .blog-card-image img {
    transform: scale(1.05);
}

.blog-card-body {
    padding: 20px;
}

.blog-card-meta {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 10px;
}

.blog-card-date {
    font-size: 12px;
    font-weight: 500;
    color: #4DA528;
    background: rgba(77, 165, 40, 0.08);
    padding: 3px 10px;
    border-radius: 20px;
}

.blog-card-author {
    font-size: 12px;
    color: #999;
}

.blog-card-title {
    font-size: 17px;
    font-weight: 600;
    line-height: 1.4;
    margin-bottom: 10px;
}

.blog-card-title a {
    color: #081e2a;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.blog-card-title a:hover {
    color: #4DA528;
}

.blog-card-excerpt {
    font-size: 14px;
    color: #666;
    line-height: 1.6;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
    margin-bottom: 14px;
}

.blog-card-link {
    font-size: 13px;
    font-weight: 600;
    color: #4DA528;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    transition: gap 0.2s ease;
}

.blog-card-link:hover {
    gap: 8px;
}

.blog-card-link::after {
    content: '→';
    font-size: 14px;
    transition: transform 0.2s ease;
}

@media (max-width: 991px) {
    .blog-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
    }
}

@media (max-width: 575px) {
    .blog-grid {
        grid-template-columns: 1fr;
        gap: 16px;
    }
}
</style>
