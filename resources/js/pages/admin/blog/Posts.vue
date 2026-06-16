<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import type { ColumnDef, FilterFn, SortingState, Updater } from '@tanstack/vue-table';
import {
    FlexRender,
    getCoreRowModel,
    getFilteredRowModel,
    getSortedRowModel,
    useVueTable,
} from '@tanstack/vue-table';
import { ArrowUpDown, Eye, EyeOff, Pencil, Plus, Trash2 } from 'lucide-vue-next';
import { computed, h, ref } from 'vue';
import {
    create as createPost,
    destroy as destroyPost,
    edit as editPost,
    index as postIndex,
    togglePublished as togglePostPublished,
} from '@/actions/App/Http/Controllers/Admin/BlogPostController';
import Heading from '@/components/Heading.vue';
import TourImageThumbnail from '@/components/TourImageThumbnail.vue';
import {
    AlertDialog,
    AlertDialogCancel,
    AlertDialogContent,
    AlertDialogDescription,
    AlertDialogFooter,
    AlertDialogHeader,
    AlertDialogTitle,
} from '@/components/ui/alert-dialog';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Spinner } from '@/components/ui/spinner';
import {
    Tooltip,
    TooltipContent,
    TooltipProvider,
    TooltipTrigger,
} from '@/components/ui/tooltip';
import { show as blogShow } from '@/actions/App/Http/Controllers/BlogController';

type AdminPost = {
    id: number;
    title: string;
    slug: string;
    featured_image: string | null;
    author: string;
    is_published: boolean;
    categories: { id: number; name: string }[];
    created_at: string | null;
    created_at_display: string;
};

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Blog',
                href: postIndex().url,
            },
        ],
    },
});

const props = defineProps<{
    posts: AdminPost[];
}>();

const sorting = ref<SortingState>([{ id: 'created_at', desc: true }]);
const globalFilter = ref('');
const deletingPostId = ref<number | null>(null);
const isDeleteDialogOpen = ref(false);
const postPendingDeletion = ref<AdminPost | null>(null);

const globalPostFilter: FilterFn<AdminPost> = (row, _, filterValue) => {
    const searchValue = String(filterValue).trim().toLowerCase();
    if (searchValue === '') {
        return true;
    }
    return [row.original.title, row.original.author]
        .join(' ')
        .toLowerCase()
        .includes(searchValue);
};

function updateValue<T>(updaterOrValue: Updater<T>, state: { value: T }): void {
    state.value =
        typeof updaterOrValue === 'function'
            ? (updaterOrValue as (value: T) => T)(state.value)
            : updaterOrValue;
}

function togglePublished(post: AdminPost): void {
    router.patch(
        togglePostPublished.url({ post: post.id }),
        {},
        { preserveScroll: true, preserveState: true },
    );
}

function openDeleteDialog(post: AdminPost): void {
    if (deletingPostId.value !== null) {
        return;
    }
    (document.activeElement as HTMLElement | null)?.blur();
    postPendingDeletion.value = post;
    isDeleteDialogOpen.value = true;
}

function handleDeleteDialogOpenChange(open: boolean): void {
    isDeleteDialogOpen.value = open;
    if (!open && deletingPostId.value === null) {
        postPendingDeletion.value = null;
    }
}

function cancelDeleteDialog(): void {
    if (deletingPostId.value !== null) {
        return;
    }
    isDeleteDialogOpen.value = false;
    postPendingDeletion.value = null;
}

function deletePost(): void {
    if (postPendingDeletion.value === null || deletingPostId.value !== null) {
        return;
    }
    deletingPostId.value = postPendingDeletion.value.id;
    router.delete(destroyPost.url({ post: postPendingDeletion.value.id }), {
        preserveScroll: true,
        onFinish: () => {
            deletingPostId.value = null;
            isDeleteDialogOpen.value = false;
            postPendingDeletion.value = null;
        },
    });
}

const columns: ColumnDef<AdminPost>[] = [
    {
        accessorKey: 'title',
        header: ({ column }) =>
            h(Button, {
                variant: 'ghost',
                class: '-ml-3 h-8 px-3 text-left font-medium',
                onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
            }, {
                default: () => ['Title', h(ArrowUpDown, { class: 'size-4' })],
            }),
        cell: ({ row }) =>
            h('div', { class: 'flex items-center gap-3' }, [
                h(TourImageThumbnail, {
                    imageUrl: row.original.featured_image,
                    name: row.original.title,
                }),
                h('div', { class: 'flex flex-col items-start gap-1' }, [
                    h('span', { class: 'font-medium text-foreground' }, row.original.title),
                    row.original.is_published
                        ? h(Badge, { variant: 'default', class: 'px-1.5 py-0 text-[10px]' }, { default: () => 'Published' })
                        : h(Badge, { variant: 'outline', class: 'border-orange-200/80 bg-orange-50 px-1.5 py-0 text-[10px] text-orange-700 dark:border-orange-500/30 dark:bg-orange-500/10 dark:text-orange-300' }, { default: () => 'Draft' }),
                ]),
            ]),
    },
    {
        id: 'categories',
        header: 'Categories',
        enableSorting: false,
        cell: ({ row }) =>
            h('div', { class: 'flex flex-wrap gap-1.5' }, [
                row.original.categories.length === 0
                    ? h('span', { class: 'text-muted-foreground text-xs' }, '—')
                    : row.original.categories.map((category) =>
                        h(Badge, { variant: 'outline' }, { default: () => category.name }),
                    ),
            ]),
    },
    {
        id: 'author',
        accessorKey: 'author',
        header: 'Author',
        cell: ({ row }) => h('div', { class: 'text-muted-foreground' }, row.original.author),
    },
    {
        accessorKey: 'created_at',
        header: ({ column }) =>
            h(Button, {
                variant: 'ghost',
                class: '-ml-3 h-8 px-3 text-left font-medium',
                onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
            }, {
                default: () => ['Created', h(ArrowUpDown, { class: 'size-4' })],
            }),
        cell: ({ row }) => h('div', { class: 'text-muted-foreground' }, row.original.created_at_display),
    },
    {
        id: 'actions',
        enableSorting: false,
        header: () => h('div', { class: 'text-right' }, 'Actions'),
        cell: ({ row }) =>
            h(TooltipProvider, { delayDuration: 0 }, {
                default: () =>
                    h('div', { class: 'flex justify-end gap-2' }, [
                        h(Tooltip, {}, {
                            default: () => [
                                h(TooltipTrigger, { asChild: true }, {
                                    default: () =>
                                        h(Link, { href: editPost.url({ post: row.original.id }), asChild: true }, {
                                            default: () =>
                                                h(Button, {
                                                    variant: 'outline',
                                                    size: 'icon',
                                                    class: 'size-8 cursor-pointer text-muted-foreground',
                                                    type: 'button',
                                                    'aria-label': 'Edit post',
                                                }, {
                                                    default: () => h(Pencil, { class: 'size-4' }),
                                                }),
                                        }),
                                }),
                                h(TooltipContent, {}, { default: () => h('p', 'Edit post') }),
                            ],
                        }),
                        h(Tooltip, {}, {
                            default: () => [
                                h(TooltipTrigger, { asChild: true }, {
                                    default: () =>
                                        h(Button, {
                                            variant: 'outline',
                                            size: 'icon',
                                            class: 'size-8 cursor-pointer text-muted-foreground',
                                            type: 'button',
                                            'aria-label': row.original.is_published ? 'Unpublish post' : 'Publish post',
                                            onClick: () => togglePublished(row.original),
                                        }, {
                                            default: () => h(row.original.is_published ? Eye : EyeOff, { class: 'size-4' }),
                                        }),
                                }),
                                h(TooltipContent, {}, { default: () => h('p', row.original.is_published ? 'Unpublish post' : 'Publish post') }),
                            ],
                        }),
                        h(Tooltip, {}, {
                            default: () => [
                                h(TooltipTrigger, { asChild: true }, {
                                    default: () =>
                                        h(Button, {
                                            variant: 'outline',
                                            size: 'icon',
                                            class: 'size-8 cursor-pointer text-destructive hover:text-destructive',
                                            type: 'button',
                                            'aria-label': 'Delete post',
                                            disabled: deletingPostId.value === row.original.id,
                                            onClick: () => openDeleteDialog(row.original),
                                        }, {
                                            default: () => h(Trash2, { class: 'size-4' }),
                                        }),
                                }),
                                h(TooltipContent, {}, { default: () => h('p', 'Delete post') }),
                            ],
                        }),
                    ]),
            }),
    },
];

const table = useVueTable({
    get data() {
        return props.posts;
    },
    columns,
    state: {
        get sorting() {
            return sorting.value;
        },
        get globalFilter() {
            return globalFilter.value;
        },
    },
    globalFilterFn: globalPostFilter,
    onSortingChange: (updaterOrValue) => updateValue(updaterOrValue, sorting),
    onGlobalFilterChange: (updaterOrValue) => updateValue(updaterOrValue, globalFilter),
    getCoreRowModel: getCoreRowModel(),
    getFilteredRowModel: getFilteredRowModel(),
    getSortedRowModel: getSortedRowModel(),
});

const filteredRowCount = computed(() => table.getFilteredRowModel().rows.length);
</script>

<template>
    <Head title="Blog Posts" />

    <div class="flex h-full flex-1 flex-col gap-6 rounded-xl p-4">
        <AlertDialog :open="isDeleteDialogOpen" @update:open="handleDeleteDialogOpenChange">
            <AlertDialogContent>
                <AlertDialogHeader>
                    <AlertDialogTitle class="text-center">Delete post</AlertDialogTitle>
                    <AlertDialogDescription class="text-center">
                        <template v-if="postPendingDeletion">
                            Delete &ldquo;{{ postPendingDeletion.title }}&rdquo;? This action cannot be undone.
                        </template>
                    </AlertDialogDescription>
                </AlertDialogHeader>
                <AlertDialogFooter>
                    <AlertDialogCancel as-child>
                        <Button type="button" variant="outline" class="cursor-pointer" :disabled="deletingPostId !== null" @click="cancelDeleteDialog">
                            Cancel
                        </Button>
                    </AlertDialogCancel>
                    <Button type="button" class="cursor-pointer bg-destructive text-white hover:bg-destructive/90" :disabled="deletingPostId !== null" @click="deletePost">
                        <Spinner v-if="deletingPostId !== null" class="size-4" />
                        Delete post
                    </Button>
                </AlertDialogFooter>
            </AlertDialogContent>
        </AlertDialog>

        <Heading title="Blog Posts" description="Manage blog posts and articles." />

        <Card>
            <CardHeader>
                <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                    <div class="space-y-1">
                        <CardTitle>All posts</CardTitle>
                        <CardDescription>View and manage blog posts.</CardDescription>
                    </div>
                    <Button as-child class="cursor-pointer">
                        <Link :href="createPost()">
                            <Plus class="size-4" />
                            New post
                        </Link>
                    </Button>
                </div>
            </CardHeader>
            <CardContent class="space-y-4">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <Input :model-value="globalFilter" class="w-full sm:max-w-sm" placeholder="Search..." @update:model-value="table.setGlobalFilter($event)" />
                    <p class="text-sm text-muted-foreground">
                        {{ filteredRowCount }} post{{ filteredRowCount === 1 ? '' : 's' }}
                    </p>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-border text-sm">
                        <thead>
                            <tr v-for="headerGroup in table.getHeaderGroups()" :key="headerGroup.id" class="text-left text-muted-foreground">
                                <th v-for="header in headerGroup.headers" :key="header.id" class="px-4 py-3 font-medium">
                                    <FlexRender v-if="!header.isPlaceholder" :render="header.column.columnDef.header" :props="header.getContext()" />
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border">
                            <tr v-for="row in table.getRowModel().rows" :key="row.id">
                                <td v-for="cell in row.getVisibleCells()" :key="cell.id" class="px-4 py-3 align-middle">
                                    <FlexRender :render="cell.column.columnDef.cell" :props="cell.getContext()" />
                                </td>
                            </tr>
                            <tr v-if="table.getRowModel().rows.length === 0">
                                <td :colspan="columns.length" class="px-4 py-8 text-center text-muted-foreground">
                                    No posts matched your search.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </CardContent>
        </Card>
    </div>
</template>
