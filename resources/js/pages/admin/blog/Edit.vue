<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { UploadCloudIcon, ExternalLink as ExternalLinkIcon, X } from 'lucide-vue-next';
import { ref } from 'vue';
import { update as updatePost, index as postIndex, edit as editPost } from '@/actions/App/Http/Controllers/Admin/BlogPostController';
import { show as blogShow } from '@/actions/App/Http/Controllers/BlogController';
import Heading from '@/components/Heading.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Checkbox } from '@/components/ui/checkbox';
import { Editor } from '@/components/ui/editor';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Switch } from '@/components/ui/switch';
import { cn } from '@/lib/utils';

type CategoryOption = { id: number; name: string };

type PostData = {
    id: number;
    title: string;
    slug: string;
    content: string | null;
    featured_image: string | null;
    is_published: boolean;
    published_at: string | null;
    category_ids: number[];
};

const props = defineProps<{
    post: PostData;
    categories: CategoryOption[];
}>();

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Blog',
                href: postIndex().url,
            },
            {
                title: 'Edit',
                href: editPost.url({ post: 0 }),
            },
        ],
    },
});

const form = useForm({
    title: props.post.title,
    slug: props.post.slug,
    content: props.post.content ?? '',
    featured_image: null as File | null,
    is_published: props.post.is_published,
    category_ids: [...props.post.category_ids],
});

const featuredImagePreview = ref<string | null>(props.post.featured_image);

function generateSlug(): void {
    if (form.slug) {
        return;
    }
    form.slug = form.title
        .toLowerCase()
        .replace(/[^a-z0-9]+/g, '-')
        .replace(/^-|-$/g, '');
}

function handleFeaturedImageChange(event: Event): void {
    const file = (event.target as HTMLInputElement).files?.[0] ?? null;
    form.featured_image = file;

    if (featuredImagePreview.value && featuredImagePreview.value !== props.post.featured_image) {
        URL.revokeObjectURL(featuredImagePreview.value);
    }
    featuredImagePreview.value = file ? URL.createObjectURL(file) : null;
}

function removeFeaturedImage(): void {
    if (featuredImagePreview.value && featuredImagePreview.value !== props.post.featured_image) {
        URL.revokeObjectURL(featuredImagePreview.value);
    }
    featuredImagePreview.value = null;
    form.featured_image = null;
}

function toggleCategory(id: number, value: boolean | 'indeterminate'): void {
    const selectedIds = [...form.category_ids];
    if (value === true) {
        if (!selectedIds.includes(id)) {
            selectedIds.push(id);
        }
    } else {
        form.category_ids = selectedIds.filter((selectedId) => selectedId !== id);
        return;
    }
    form.category_ids = selectedIds;
}

function submit(): void {
    form.patch(updatePost.url({ post: props.post.id }), {
        forceFormData: true,
    });
}

function fieldError(key: string): string | undefined {
    return form.errors[key];
}
</script>

<template>
    <Head title="Edit Blog Post" />

    <div class="flex h-full flex-1 flex-col gap-6 rounded-xl p-4">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
            <Heading title="Edit Blog Post" :description="'Editing: ' + post.title" />

            <div class="flex flex-col items-end gap-4">
                <div class="flex gap-2">
                    <Button as-child variant="outline" :disabled="form.processing">
                        <Link :href="postIndex()">Cancel</Link>
                    </Button>
                    <Button type="button" :disabled="form.processing" @click="submit">
                        Save changes
                    </Button>
                </div>
                <Button as-child variant="ghost" size="sm" class="text-muted-foreground hover:text-foreground">
                    <a :href="blogShow.url({ post: post.slug })" target="_blank" rel="noopener noreferrer">
                        View post on website
                        <ExternalLinkIcon class="size-4" />
                    </a>
                </Button>
            </div>
        </div>

        <form class="space-y-6" @submit.prevent="submit">
            <div class="grid gap-6 lg:grid-cols-[3fr_1fr]">
                <div class="space-y-6">
                    <Card>
                        <CardHeader>
                            <CardTitle>Post details</CardTitle>
                            <CardDescription>
                                Edit the main details and content for the blog post.
                            </CardDescription>
                        </CardHeader>
                        <CardContent>
                            <div class="grid gap-6 md:grid-cols-2">
                                <div class="grid gap-2">
                                    <Label for="title">Title</Label>
                                    <Input id="title" v-model="form.title" placeholder="Enter post title" required @input="generateSlug" />
                                    <InputError :message="form.errors.title" />
                                </div>

                                <div class="grid gap-2">
                                    <Label for="slug">Slug</Label>
                                    <Input id="slug" v-model="form.slug" placeholder="auto-generated-from-title" />
                                    <InputError :message="form.errors.slug" />
                                </div>
                            </div>
                        </CardContent>
                    </Card>

                    <Card>
                        <CardHeader>
                            <CardTitle>Content</CardTitle>
                            <CardDescription>
                                Write the body of your blog post.
                            </CardDescription>
                        </CardHeader>
                        <CardContent>
                            <Editor v-model="form.content" :error="!!form.errors.content" placeholder="Start writing your post..." />
                            <InputError :message="form.errors.content" class="mt-2" />
                        </CardContent>
                    </Card>
                </div>

                <div class="space-y-6 lg:sticky lg:top-6 lg:self-start">
                    <Card>
                        <CardHeader>
                            <div class="flex items-center justify-between gap-3">
                                <div class="space-y-1">
                                    <CardTitle class="text-base">Visibility</CardTitle>
                                    <CardDescription>Control whether this post is publicly visible.</CardDescription>
                                </div>
                            </div>
                        </CardHeader>
                        <CardContent>
                            <div class="flex items-center justify-between gap-3 rounded-lg border border-dashed border-border p-4">
                                <div class="space-y-1">
                                    <p class="font-medium">Published</p>
                                    <p class="text-sm text-muted-foreground">
                                        Make this post visible on the blog page.
                                    </p>
                                </div>
                                <Switch :model-value="form.is_published" @update:model-value="(v) => (form.is_published = v)" />
                            </div>
                            <InputError :message="fieldError('is_published')" class="mt-2" />
                        </CardContent>
                    </Card>

                    <Card>
                        <CardHeader>
                            <div class="flex items-center justify-between gap-3">
                                <div class="space-y-1">
                                    <CardTitle class="text-base">Featured Image</CardTitle>
                                    <CardDescription>Add or replace the featured image.</CardDescription>
                                </div>
                            </div>
                        </CardHeader>
                        <CardContent>
                            <div
                                :class="
                                    cn(
                                        'relative flex min-h-40 flex-col items-center justify-center gap-3 rounded-lg border-2 border-dashed border-border bg-muted/40 p-6 text-center',
                                        form.errors.featured_image && 'border-destructive',
                                    )
                                "
                            >
                                <Input
                                    id="featured_image"
                                    type="file"
                                    accept=".png,.jpg,.jpeg,.webp"
                                    class="absolute inset-0 h-full w-full cursor-pointer opacity-0"
                                    @change="handleFeaturedImageChange"
                                />
                                <div v-if="featuredImagePreview" class="group relative w-full">
                                    <img
                                        :src="featuredImagePreview"
                                        alt="Featured image preview"
                                        class="max-h-56 w-full rounded-md border border-border object-cover"
                                    />
                                    <button
                                        type="button"
                                        class="absolute top-2 right-2 flex h-7 w-7 cursor-pointer items-center justify-center rounded-full bg-background/90 text-foreground opacity-0 shadow-sm transition-opacity group-hover:opacity-100 hover:bg-destructive hover:text-destructive-foreground"
                                        @click="removeFeaturedImage"
                                    >
                                        <X class="h-3.5 w-3.5" />
                                    </button>
                                </div>
                                <template v-else>
                                    <UploadCloudIcon class="h-10 w-10 text-muted-foreground" />
                                    <div class="space-y-1">
                                        <p class="text-sm font-medium">
                                            Choose featured image
                                        </p>
                                        <p class="text-xs text-muted-foreground">
                                            PNG, JPG, JPEG, or WEBP
                                        </p>
                                    </div>
                                </template>
                            </div>
                            <InputError :message="form.errors.featured_image" class="mt-2" />
                        </CardContent>
                    </Card>

                    <Card>
                        <CardHeader>
                            <CardTitle class="text-base">Categories</CardTitle>
                            <CardDescription>Select categories for this post.</CardDescription>
                        </CardHeader>
                        <CardContent class="space-y-2">
                            <label
                                v-for="category in categories"
                                :key="category.id"
                                class="flex items-start gap-3 rounded-md border border-border p-3"
                            >
                                <Checkbox
                                    :model-value="form.category_ids.includes(category.id)"
                                    @update:model-value="toggleCategory(category.id, $event)"
                                />
                                <div class="space-y-1">
                                    <p class="text-sm font-medium">{{ category.name }}</p>
                                </div>
                            </label>
                            <InputError :message="fieldError('category_ids')" />
                        </CardContent>
                    </Card>
                </div>
            </div>
        </form>
    </div>
</template>
