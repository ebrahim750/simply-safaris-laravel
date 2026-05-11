<script setup lang="ts">
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import type {
    ColumnDef,
    FilterFn,
    SortingState,
    Updater,
} from '@tanstack/vue-table';
import {
    FlexRender,
    getCoreRowModel,
    getFilteredRowModel,
    getSortedRowModel,
    useVueTable,
} from '@tanstack/vue-table';
import { ArrowUpDown, Pencil, Plus, Trash2, X } from 'lucide-vue-next';
import { computed, h, ref } from 'vue';
import {
    destroy as destroyTourCategory,
    store as storeTourCategory,
    update as updateTourCategory,
} from '@/actions/App/Http/Controllers/Admin/TourCategoryController';
import { edit as editTour } from '@/actions/App/Http/Controllers/Admin/TourController';
import Heading from '@/components/Heading.vue';
import InputError from '@/components/InputError.vue';
import {
    AlertDialog,
    AlertDialogCancel,
    AlertDialogContent,
    AlertDialogDescription,
    AlertDialogFooter,
    AlertDialogHeader,
    AlertDialogTitle,
} from '@/components/ui/alert-dialog';
import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import { FileUpload } from '@/components/ui/file-upload';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import { categories as adminToursCategories } from '@/routes/admin/tours';

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Tours',
                href: adminToursCategories(),
            },
            {
                title: 'Categories',
                href: adminToursCategories(),
            },
        ],
    },
});

type AdminCategory = {
    id: number;
    name: string;
    description: string | null;
    image_url: string | null;
    tours: { id: number; name: string; main_image: string | null }[];
    created_at: string | null;
    created_at_display: string;
};

const props = defineProps<{
    categories: AdminCategory[];
}>();

const sorting = ref<SortingState>([
    {
        id: 'created_at',
        desc: true,
    },
]);
const globalFilter = ref('');
const isModalOpen = ref(false);
const editingCategoryId = ref<number | null>(null);
const deletingCategoryId = ref<number | null>(null);
const isDeleteDialogOpen = ref(false);
const categoryPendingDeletion = ref<AdminCategory | null>(null);
const isRelatedToursDialogOpen = ref(false);
const categoryForRelatedTours = ref<AdminCategory | null>(null);
const imagePreview = ref<string | null>(null);

const categoryForm = useForm<{
    name: string;
    description: string;
    image: File | null;
}>({
    name: '',
    description: '',
    image: null,
});

const globalCategoryFilter: FilterFn<AdminCategory> = (row, _, filterValue) => {
    const searchValue = String(filterValue).trim().toLowerCase();

    if (searchValue === '') {
        return true;
    }

    return (
        row.original.name.toLowerCase().includes(searchValue) ||
        (row.original.description ?? '').toLowerCase().includes(searchValue)
    );
};

function updateValue<T>(updaterOrValue: Updater<T>, state: { value: T }): void {
    state.value =
        typeof updaterOrValue === 'function'
            ? (updaterOrValue as (value: T) => T)(state.value)
            : updaterOrValue;
}

function resetForm(): void {
    categoryForm.reset();
    categoryForm.clearErrors();
    imagePreview.value = null;
}

function closeModal(): void {
    isModalOpen.value = false;
    editingCategoryId.value = null;
    resetForm();
}

function openCreateModal(): void {
    editingCategoryId.value = null;
    resetForm();
    isModalOpen.value = true;
}

function openEditModal(category: AdminCategory): void {
    editingCategoryId.value = category.id;
    categoryForm.name = category.name;
    categoryForm.description = category.description ?? '';
    categoryForm.image = null;
    imagePreview.value = category.image_url;
    isModalOpen.value = true;
}

function openRelatedToursDialog(category: AdminCategory): void {
    categoryForRelatedTours.value = category;
    isRelatedToursDialogOpen.value = true;
}

function closeRelatedToursDialog(): void {
    isRelatedToursDialogOpen.value = false;
    categoryForRelatedTours.value = null;
}

function submitCategoryForm(): void {
    if (editingCategoryId.value === null) {
        categoryForm.post(storeTourCategory.url(), {
            preserveScroll: true,
            forceFormData: true,
            onSuccess: () => {
                resetForm();
                isModalOpen.value = false;
            },
        });
    } else {
        categoryForm.patch(
            updateTourCategory.url({ category: editingCategoryId.value }),
            {
                preserveScroll: true,
                forceFormData: true,
                onSuccess: () => {
                    resetForm();
                    isModalOpen.value = false;
                    editingCategoryId.value = null;
                },
            },
        );
    }
}

function openDeleteDialog(category: AdminCategory): void {
    if (deletingCategoryId.value !== null) {
        return;
    }

    categoryPendingDeletion.value = category;
    isDeleteDialogOpen.value = true;
}

function handleDeleteDialogOpenChange(open: boolean): void {
    isDeleteDialogOpen.value = open;

    if (!open && deletingCategoryId.value === null) {
        categoryPendingDeletion.value = null;
    }
}

function cancelDeleteDialog(): void {
    if (deletingCategoryId.value !== null) {
        return;
    }

    isDeleteDialogOpen.value = false;
    categoryPendingDeletion.value = null;
}

function deleteCategory(): void {
    if (
        categoryPendingDeletion.value === null ||
        deletingCategoryId.value !== null
    ) {
        return;
    }

    deletingCategoryId.value = categoryPendingDeletion.value.id;

    router.delete(destroyTourCategory.url(categoryPendingDeletion.value.id), {
        preserveScroll: true,
        onFinish: () => {
            deletingCategoryId.value = null;
            isDeleteDialogOpen.value = false;
            categoryPendingDeletion.value = null;
        },
    });
}

const columns: ColumnDef<AdminCategory>[] = [
    {
        accessorKey: 'name',
        header: ({ column }) =>
            h(
                Button,
                {
                    variant: 'ghost',
                    class: '-ml-3 h-8 px-3 text-left font-medium',
                    onClick: () =>
                        column.toggleSorting(column.getIsSorted() === 'asc'),
                },
                {
                    default: () => [
                        'Name',
                        h(ArrowUpDown, { class: 'size-4' }),
                    ],
                },
            ),
        cell: ({ row }) => {
            const children = [];

            if (row.original.image_url) {
                children.push(
                    h('img', {
                        src: row.original.image_url,
                        alt: row.original.name,
                        class: 'h-8 w-8 rounded-md object-cover',
                    }),
                );
            }

            children.push(
                h(
                    'span',
                    { class: 'font-medium text-foreground' },
                    row.original.name,
                ),
            );

            return h(
                'div',
                { class: 'flex items-center gap-3' },
                children,
            );
        },
    },
    {
        accessorKey: 'description',
        header: () => h('div', { class: 'text-left font-medium' }, 'Description'),
        cell: ({ row }) =>
            h(
                'div',
                {
                    class: 'max-w-xs truncate text-muted-foreground',
                    title: row.original.description ?? undefined,
                },
                row.original.description || '—',
            ),
    },

    {
        id: 'associated_tours',
        header: 'Associated Tours',
        enableSorting: false,
        cell: ({ row }) => {
            const tourCount = row.original.tours.length;

            if (tourCount === 0) {
                return h('span', { class: 'text-muted-foreground text-xs' }, '—');
            }

            return h(
                Button,
                {
                    variant: 'ghost',
                    class: 'h-8 cursor-pointer px-2 text-sm font-normal text-muted-foreground hover:text-foreground',
                    onClick: () => openRelatedToursDialog(row.original),
                },
                {
                    default: () => `${tourCount} tour${tourCount === 1 ? '' : 's'}`,
                },
            );
        },
    },
    {
        accessorKey: 'created_at',
        header: ({ column }) =>
            h(
                Button,
                {
                    variant: 'ghost',
                    class: '-ml-3 h-8 px-3 text-left font-medium',
                    onClick: () =>
                        column.toggleSorting(column.getIsSorted() === 'asc'),
                },
                {
                    default: () => [
                        'Created',
                        h(ArrowUpDown, { class: 'size-4' }),
                    ],
                },
            ),
        cell: ({ row }) =>
            h(
                'div',
                { class: 'text-muted-foreground' },
                row.original.created_at_display,
            ),
    },
    {
        id: 'actions',
        enableSorting: false,
        header: () => h('div', { class: 'text-right' }, 'Actions'),
        cell: ({ row }) =>
            h('div', { class: 'flex justify-end gap-2' }, [
                h(
                    Button,
                    {
                        variant: 'outline',
                        size: 'sm',
                        class: 'h-8 cursor-pointer px-3 text-muted-foreground',
                        type: 'button',
                        onClick: () => openEditModal(row.original),
                    },
                    {
                        default: () => [
                            h(Pencil, { class: 'size-3.5' }),
                            'Edit',
                        ],
                    },
                ),
                h(
                    Button,
                    {
                        variant: 'outline',
                        size: 'sm',
                        class: 'h-8 cursor-pointer px-3 text-destructive hover:text-destructive',
                        type: 'button',
                        disabled: deletingCategoryId.value === row.original.id,
                        onClick: () => openDeleteDialog(row.original),
                    },
                    {
                        default: () =>
                            deletingCategoryId.value === row.original.id
                                ? [
                                      h(Spinner, { class: 'size-3.5' }),
                                      'Deleting',
                                  ]
                                : [h(Trash2, { class: 'size-3.5' }), 'Delete'],
                    },
                ),
            ]),
    },
];

const table = useVueTable({
    get data() {
        return props.categories;
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
    globalFilterFn: globalCategoryFilter,
    onSortingChange: (updaterOrValue) => updateValue(updaterOrValue, sorting),
    onGlobalFilterChange: (updaterOrValue) =>
        updateValue(updaterOrValue, globalFilter),
    getCoreRowModel: getCoreRowModel(),
    getFilteredRowModel: getFilteredRowModel(),
    getSortedRowModel: getSortedRowModel(),
});

const filteredRowCount = computed(
    () => table.getFilteredRowModel().rows.length,
);
</script>

<template>
    <Head title="Tour Categories" />

    <div class="flex h-full flex-1 flex-col gap-6 rounded-xl p-4">
        <AlertDialog
            :open="isDeleteDialogOpen"
            @update:open="handleDeleteDialogOpenChange"
        >
            <AlertDialogContent>
                <AlertDialogHeader>
                    <AlertDialogTitle class="text-center"
                        >Delete category</AlertDialogTitle
                    >
                    <AlertDialogDescription class="text-center">
                        <template v-if="categoryPendingDeletion">
                            Delete {{ categoryPendingDeletion.name }}? This will
                            also remove it from all associated tours. This
                            action cannot be undone.
                        </template>
                    </AlertDialogDescription>
                </AlertDialogHeader>
                <AlertDialogFooter>
                    <AlertDialogCancel as-child>
                        <Button
                            type="button"
                            variant="outline"
                            class="cursor-pointer"
                            :disabled="deletingCategoryId !== null"
                            @click="cancelDeleteDialog"
                        >
                            Cancel
                        </Button>
                    </AlertDialogCancel>
                    <Button
                        type="button"
                        class="cursor-pointer bg-destructive text-white hover:bg-destructive/90"
                        :disabled="deletingCategoryId !== null"
                        @click="deleteCategory"
                    >
                        <Spinner v-if="deletingCategoryId !== null" />
                        Delete category
                    </Button>
                </AlertDialogFooter>
            </AlertDialogContent>
        </AlertDialog>

        <Dialog
            :open="isRelatedToursDialogOpen"
            @update:open="closeRelatedToursDialog"
        >
            <DialogContent class="max-w-md">
                <DialogHeader>
                    <DialogTitle>
                        <template v-if="categoryForRelatedTours">
                            {{ categoryForRelatedTours.name }} — Associated Tours
                        </template>
                    </DialogTitle>
                    <DialogDescription>
                        Tours linked to this category.
                    </DialogDescription>
                </DialogHeader>

                <div class="space-y-2">
                    <template v-if="categoryForRelatedTours">
                        <div
                            v-if="categoryForRelatedTours.tours.length === 0"
                            class="text-center text-sm text-muted-foreground py-4"
                        >
                            No tours associated with this category.
                        </div>
                        <table v-else class="min-w-full text-sm">
                            <tbody class="divide-y divide-border">
                                <tr
                                    v-for="tour in categoryForRelatedTours.tours"
                                    :key="tour.id"
                                    class="group"
                                >
                                    <td class="py-2 pr-3">
                                        <Link :href="editTour({ tour: tour.id })">
                                            <img
                                                v-if="tour.main_image"
                                                :src="tour.main_image"
                                                :alt="tour.name"
                                                class="h-10 w-14 rounded-md border border-border bg-muted object-cover"
                                                loading="lazy"
                                            />
                                            <div
                                                v-else
                                                class="flex h-10 w-14 items-center justify-center rounded-md border border-dashed border-border bg-muted/40 text-[10px] font-medium uppercase tracking-wide text-muted-foreground"
                                            >
                                                No image
                                            </div>
                                        </Link>
                                    </td>
                                    <td class="py-2">
                                        <Link
                                            :href="editTour({ tour: tour.id })"
                                            class="font-medium text-foreground hover:underline"
                                        >
                                            {{ tour.name }}
                                        </Link>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </template>
                </div>

            </DialogContent>
        </Dialog>

        <Dialog :open="isModalOpen" @update:open="closeModal">
            <DialogContent class="max-w-lg">
                <DialogHeader>
                    <DialogTitle>
                        {{ editingCategoryId === null ? 'Add category' : 'Edit category' }}
                    </DialogTitle>
                    <DialogDescription>
                        {{ editingCategoryId === null ? 'Create a new tour category.' : 'Update this tour category.' }}
                    </DialogDescription>
                </DialogHeader>

                <form
                    class="space-y-4"
                    @submit.prevent="submitCategoryForm"
                >
                    <div class="space-y-2">
                        <Label for="category_name">Name</Label>
                        <Input
                            id="category_name"
                            v-model="categoryForm.name"
                            placeholder="Category name"
                            :disabled="categoryForm.processing"
                        />
                        <InputError :message="categoryForm.errors.name" />
                    </div>

                    <div class="space-y-2">
                        <Label for="category_description">Description</Label>
                        <textarea
                            id="category_description"
                            v-model="categoryForm.description"
                            rows="4"
                            class="flex min-h-[80px] w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                            placeholder="Describe this category..."
                            :disabled="categoryForm.processing"
                        />
                        <InputError :message="categoryForm.errors.description" />
                    </div>

                    <div class="space-y-2">
                        <FileUpload
                            id="category_image"
                            label="Image"
                            v-model="categoryForm.image"
                            :preview-url="imagePreview"
                            preview-alt="Category image"
                            accept=".png,.jpg,.jpeg,.webp"
                            hint="PNG, JPG, JPEG, or WEBP up to 5MB"
                            :error="categoryForm.errors.image"
                        />
                    </div>

                    <DialogFooter>
                        <Button
                            type="button"
                            variant="outline"
                            class="cursor-pointer"
                            :disabled="categoryForm.processing"
                            @click="closeModal"
                        >
                            Cancel
                        </Button>
                        <Button
                            type="submit"
                            class="cursor-pointer"
                            :disabled="categoryForm.processing"
                        >
                            <Spinner v-if="categoryForm.processing" />
                            {{ editingCategoryId === null ? 'Add category' : 'Save changes' }}
                        </Button>
                    </DialogFooter>
                </form>
            </DialogContent>
        </Dialog>

        <Heading
            title="Tour Categories"
            description="Manage tour categories."
        />

        <Card>
            <CardHeader>
                <div
                    class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between"
                >
                    <div class="space-y-1">
                        <CardTitle>All categories</CardTitle>
                        <CardDescription>
                            Organize tours by category.
                        </CardDescription>
                    </div>

                    <Button
                        type="button"
                        class="cursor-pointer"
                        @click="openCreateModal"
                    >
                        <Plus class="size-4" />
                        Add category
                    </Button>
                </div>
            </CardHeader>
            <CardContent class="space-y-4">
                <div
                    class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between"
                >
                    <Input
                        :model-value="globalFilter"
                        class="w-full sm:max-w-sm"
                        placeholder="Search categories..."
                        @update:model-value="table.setGlobalFilter($event)"
                    />
                    <p class="text-sm text-muted-foreground">
                        {{ filteredRowCount }} categor{{
                            filteredRowCount === 1 ? 'y' : 'ies'
                        }}
                    </p>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-border text-sm">
                        <thead>
                            <tr
                                v-for="headerGroup in table.getHeaderGroups()"
                                :key="headerGroup.id"
                                class="text-left text-muted-foreground"
                            >
                                <th
                                    v-for="header in headerGroup.headers"
                                    :key="header.id"
                                    class="px-4 py-3 font-medium"
                                >
                                    <FlexRender
                                        v-if="!header.isPlaceholder"
                                        :render="header.column.columnDef.header"
                                        :props="header.getContext()"
                                    />
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border">
                            <tr
                                v-for="row in table.getRowModel().rows"
                                :key="row.id"
                            >
                                <td
                                    v-for="cell in row.getVisibleCells()"
                                    :key="cell.id"
                                    class="px-4 py-3 align-middle"
                                >
                                    <FlexRender
                                        :render="cell.column.columnDef.cell"
                                        :props="cell.getContext()"
                                    />
                                </td>
                            </tr>
                            <tr v-if="table.getRowModel().rows.length === 0">
                                <td
                                    :colspan="columns.length"
                                    class="px-4 py-8 text-center text-muted-foreground"
                                >
                                    No categories matched your search.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </CardContent>
        </Card>
    </div>
</template>
