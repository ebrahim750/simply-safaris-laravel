<script setup lang="ts">
import { Head, router, useForm } from '@inertiajs/vue3';
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
import { ArrowUpDown, Check, Pencil, Plus, Trash2, X } from 'lucide-vue-next';
import { computed, h, ref } from 'vue';
import {
    destroy as destroyTourCategory,
    store as storeTourCategory,
    update as updateTourCategory,
} from '@/actions/App/Http/Controllers/Admin/TourCategoryController';
import Heading from '@/components/Heading.vue';
import InputError from '@/components/InputError.vue';
import {
    AlertDialog,
    AlertDialogAction,
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
import { Collapsible, CollapsibleContent } from '@/components/ui/collapsible';
import { Input } from '@/components/ui/input';
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
const isCreateCategoryOpen = ref(false);
const editingCategoryId = ref<number | null>(null);
const deletingCategoryId = ref<number | null>(null);
const isDeleteDialogOpen = ref(false);
const categoryPendingDeletion = ref<AdminCategory | null>(null);
const createCategoryForm = useForm({
    name: '',
});
const editCategoryForm = useForm({
    name: '',
});

const globalCategoryFilter: FilterFn<AdminCategory> = (row, _, filterValue) => {
    const searchValue = String(filterValue).trim().toLowerCase();

    if (searchValue === '') {
        return true;
    }

    return row.original.name
        .toLowerCase()
        .includes(searchValue);
};

function updateValue<T>(updaterOrValue: Updater<T>, state: { value: T }): void {
    state.value =
        typeof updaterOrValue === 'function'
            ? (updaterOrValue as (value: T) => T)(state.value)
            : updaterOrValue;
}

function resetCreateCategoryForm(): void {
    createCategoryForm.reset();
    createCategoryForm.clearErrors();
}

function closeCreateCategoryPanel(): void {
    isCreateCategoryOpen.value = false;
    resetCreateCategoryForm();
}

function toggleCreateCategoryPanel(): void {
    if (isCreateCategoryOpen.value) {
        closeCreateCategoryPanel();

        return;
    }

    cancelEditingCategory();
    isCreateCategoryOpen.value = true;
}

function submitCreateCategory(): void {
    createCategoryForm.post(storeTourCategory.url(), {
        preserveScroll: true,
        onSuccess: () => {
            createCategoryForm.reset();
            createCategoryForm.clearErrors();
        },
    });
}

function startEditingCategory(category: AdminCategory): void {
    closeCreateCategoryPanel();
    editingCategoryId.value = category.id;
    editCategoryForm.name = category.name;
    editCategoryForm.clearErrors();
}

function cancelEditingCategory(): void {
    editingCategoryId.value = null;
    editCategoryForm.reset();
    editCategoryForm.clearErrors();
}

function submitEditCategory(): void {
    if (editingCategoryId.value === null) {
        return;
    }

    editCategoryForm.patch(
        updateTourCategory.url({ category: editingCategoryId.value }),
        {
            preserveScroll: true,
            onSuccess: () => {
                cancelEditingCategory();
            },
        },
    );
}

function openDeleteDialog(category: AdminCategory): void {
    if (deletingCategoryId.value !== null) {
        return;
    }

    closeCreateCategoryPanel();
    cancelEditingCategory();
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
    if (categoryPendingDeletion.value === null || deletingCategoryId.value !== null) {
        return;
    }

    deletingCategoryId.value = categoryPendingDeletion.value.id;

    router.delete(
        destroyTourCategory.url({ category: categoryPendingDeletion.value.id }),
        {
            preserveScroll: true,
            onFinish: () => {
                deletingCategoryId.value = null;
                isDeleteDialogOpen.value = false;
                categoryPendingDeletion.value = null;
            },
        },
    );
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
                    onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
                },
                {
                    default: () => ['Name', h(ArrowUpDown, { class: 'size-4' })],
                },
            ),
        cell: ({ row }) => {
            if (editingCategoryId.value === row.original.id) {
                return h('div', { class: 'space-y-2' }, [
                    h(Input, {
                        modelValue: editCategoryForm.name,
                        class: 'max-w-sm',
                        placeholder: 'Category name',
                        disabled: editCategoryForm.processing,
                        'onUpdate:modelValue': (value: string | number) => {
                            editCategoryForm.name = String(value);
                        },
                    }),
                    h(InputError, {
                        message: editCategoryForm.errors.name,
                    }),
                ]);
            }

            return h('div', { class: 'font-medium text-foreground' }, row.original.name);
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
                    onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
                },
                {
                    default: () => ['Created', h(ArrowUpDown, { class: 'size-4' })],
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
        cell: ({ row }) => {
            if (editingCategoryId.value === row.original.id) {
                return h(
                    'div',
                    { class: 'flex justify-end gap-2' },
                    [
                        h(
                            Button,
                            {
                                size: 'sm',
                                class: 'h-8 cursor-pointer px-3',
                                type: 'button',
                                disabled: editCategoryForm.processing,
                                onClick: submitEditCategory,
                            },
                            {
                                default: () =>
                                    editCategoryForm.processing
                                        ? [
                                              h(Spinner, { class: 'size-3.5' }),
                                              'Saving',
                                          ]
                                        : [
                                              h(Check, { class: 'size-3.5' }),
                                              'Save',
                                          ],
                            },
                        ),
                        h(
                            Button,
                            {
                                variant: 'outline',
                                size: 'sm',
                                class: 'h-8 cursor-pointer px-3 text-muted-foreground',
                                type: 'button',
                                disabled: editCategoryForm.processing,
                                onClick: cancelEditingCategory,
                            },
                            {
                                default: () => [
                                    h(X, { class: 'size-3.5' }),
                                    'Cancel',
                                ],
                            },
                        ),
                    ],
                );
            }

            return h(
                'div',
                { class: 'flex justify-end gap-2' },
                [
                    h(
                        Button,
                        {
                            variant: 'outline',
                            size: 'sm',
                            class: 'h-8 cursor-pointer px-3 text-muted-foreground',
                            type: 'button',
                            onClick: () => startEditingCategory(row.original),
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
                                    : [
                                          h(Trash2, { class: 'size-3.5' }),
                                          'Delete',
                                      ],
                        },
                    ),
                ],
            );
        },
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

const filteredRowCount = computed(() => table.getFilteredRowModel().rows.length);
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
                    <AlertDialogTitle class="text-center">Delete category</AlertDialogTitle>
                    <AlertDialogDescription class="text-center">
                        <template v-if="categoryPendingDeletion">
                            Delete {{ categoryPendingDeletion.name }}? This will also remove it from
                            all associated tours. This action cannot be undone.
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
                    <AlertDialogAction as-child>
                        <Button
                            type="button"
                            class="cursor-pointer bg-destructive text-white hover:bg-destructive/90"
                            :disabled="deletingCategoryId !== null"
                            @click="deleteCategory"
                        >
                            <Spinner v-if="deletingCategoryId !== null" />
                            Delete category
                        </Button>
                    </AlertDialogAction>
                </AlertDialogFooter>
            </AlertDialogContent>
        </AlertDialog>

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
                        :variant="isCreateCategoryOpen ? 'secondary' : 'default'"
                        @click="toggleCreateCategoryPanel"
                    >
                        <Plus class="size-4" />
                        {{ isCreateCategoryOpen ? 'Close' : 'Add category' }}
                    </Button>
                </div>
            </CardHeader>
            <CardContent class="space-y-4">
                <Collapsible :open="isCreateCategoryOpen" class="space-y-4">
                    <CollapsibleContent>
                        <div class="flex justify-end">
                            <form
                                class="inline-block w-full max-w-md rounded-lg border border-border bg-muted/30 p-4"
                                @submit.prevent="submitCreateCategory"
                            >
                                <div class="flex flex-col gap-3">
                                    <div class="w-full max-w-sm space-y-2">
                                        <Input
                                            v-model="createCategoryForm.name"
                                            placeholder="Add a new category"
                                            :disabled="createCategoryForm.processing"
                                        />
                                        <InputError :message="createCategoryForm.errors.name" />
                                    </div>

                                    <div class="flex gap-2">
                                        <Button
                                            type="submit"
                                            class="cursor-pointer"
                                            :disabled="createCategoryForm.processing"
                                        >
                                            <Spinner v-if="createCategoryForm.processing" />
                                            Add
                                        </Button>
                                        <Button
                                            type="button"
                                            variant="outline"
                                            class="cursor-pointer"
                                            :disabled="createCategoryForm.processing"
                                            @click="closeCreateCategoryPanel"
                                        >
                                            Cancel
                                        </Button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </CollapsibleContent>
                </Collapsible>

                <div
                    class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between"
                >
                    <Input
                        :model-value="globalFilter"
                        class="w-full sm:max-w-sm"
                        placeholder="Search categories by name..."
                        @update:model-value="table.setGlobalFilter($event)"
                    />
                    <p class="text-sm text-muted-foreground">
                        {{ filteredRowCount }} categor{{ filteredRowCount === 1 ? 'y' : 'ies' }}
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
