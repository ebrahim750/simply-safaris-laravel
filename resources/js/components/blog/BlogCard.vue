<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { User } from 'lucide-vue-next';
import { show as blogShow } from '@/routes/blog2';

interface BlogCategory {
    id: number;
    name: string;
}

interface Post {
    id: number;
    title: string;
    slug: string;
    excerpt?: string | null;
    featured_image?: string | null;
    published_at_display?: string | null;
    author?: string;
    categories: BlogCategory[];
}

defineProps<{
    post: Post;
    showExcerpt?: boolean;
}>();
</script>

<template>
    <article class="group mb-10">
        <Link :href="blogShow({ post: post.slug })" class="relative block overflow-hidden rounded-xl">
            <img
                v-if="post.featured_image"
                :src="post.featured_image"
                :alt="post.title"
                class="h-52 w-full object-cover transition-transform duration-500 group-hover:scale-105"
            />
            <div v-else class="flex h-52 w-full items-center justify-center bg-gray-100">
                <span class="text-gray-400">No image</span>
            </div>
            <span
                v-if="post.categories.length > 0"
                class="absolute bottom-3 left-3 inline-flex items-center gap-1 rounded-full bg-white/90 px-3 py-1 text-xs font-medium text-gray-700"
            >
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1 0-5H20" /></svg>
                {{ post.categories[0].name }}
            </span>
        </Link>
        <div class="pt-5">
            <div class="mb-3 flex items-center gap-4 text-xs text-gray-500">
                <span class="font-medium text-[#4DA528]">{{ post.published_at_display }}</span>
                <span v-if="post.author" class="flex items-center gap-1">
                    <User class="size-3.5" />
                    {{ post.author }}
                </span>
            </div>
            <h3 class="mb-2 text-lg font-bold leading-snug text-gray-900">
                <Link :href="blogShow({ post: post.slug })" class="transition-colors hover:text-[#4DA528]">{{ post.title }}</Link>
            </h3>
            <p v-if="showExcerpt && post.excerpt" class="mb-4 line-clamp-3 text-sm leading-relaxed text-gray-500">{{ post.excerpt }}</p>
            <Link :href="blogShow({ post: post.slug })" class="inline-flex items-center gap-1 text-sm font-medium text-[#4DA528] transition-colors hover:text-[#3d8420]">
                Read More
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4"><path d="M5 12h14" /><path d="m12 5 7 7-7 7" /></svg>
            </Link>
        </div>
    </article>
</template>
