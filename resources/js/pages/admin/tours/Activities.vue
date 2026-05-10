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
    destroy as destroyTourActivity,
    store as storeTourActivity,
    update as updateTourActivity,
} from '@/actions/App/Http/Controllers/Admin/TourActivityController';
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
import { Collapsible, CollapsibleContent } from '@/components/ui/collapsible';
import { Input } from '@/components/ui/input';
import { Spinner } from '@/components/ui/spinner';
import { activities as adminToursActivities } from '@/routes/admin/tours';

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Tours',
                href: adminToursActivities(),
            },
            {
                title: 'Activities',
                href: adminToursActivities(),
            },
        ],
    },
});

type AdminActivity = {
    id: number;
    name: string;
    created_at: string | null;
    created_at_display: string;
};

const props = defineProps<{
    activities: AdminActivity[];
}>();

const sorting = ref<SortingState>([
    {
        id: 'created_at',
        desc: true,
    },
]);
const globalFilter = ref('');
const isCreateActivityOpen = ref(false);
const editingActivityId = ref<number | null>(null);
const deletingActivityId = ref<number | null>(null);
const isDeleteDialogOpen = ref(false);
const activityPendingDeletion = ref<AdminActivity | null>(null);
const createActivityForm = useForm({
    name: '',
});
const editActivityForm = useForm({
    name: '',
});

const globalActivityFilter: FilterFn<AdminActivity> = (row, _, filterValue) => {
    const searchValue = String(filterValue).trim().toLowerCase();

    if (searchValue === '') {
        return true;
    }

    return row.original.name.toLowerCase().includes(searchValue);
};

function updateValue<T>(updaterOrValue: Updater<T>, state: { value: T }): void {
    state.value =
        typeof updaterOrValue === 'function'
            ? (updaterOrValue as (value: T) => T)(state.value)
            : updaterOrValue;
}

function resetCreateActivityForm(): void {
    createActivityForm.reset();
    createActivityForm.clearErrors();
}

function closeCreateActivityPanel(): void {
    isCreateActivityOpen.value = false;
    resetCreateActivityForm();
}

function toggleCreateActivityPanel(): void {
    if (isCreateActivityOpen.value) {
        closeCreateActivityPanel();

        return;
    }

    cancelEditingActivity();
    isCreateActivityOpen.value = true;
}

function submitCreateActivity(): void {
    createActivityForm.post(storeTourActivity.url(), {
        preserveScroll: true,
        onSuccess: () => {
            createActivityForm.reset();
            createActivityForm.clearErrors();
        },
    });
}

function startEditingActivity(activity: AdminActivity): void {
    closeCreateActivityPanel();
    editingActivityId.value = activity.id;
    editActivityForm.name = activity.name;
    editActivityForm.clearErrors();
}

function cancelEditingActivity(): void {
    editingActivityId.value = null;
    editActivityForm.reset();
    editActivityForm.clearErrors();
}

function submitEditActivity(): void {
    if (editingActivityId.value === null) {
        return;
    }

    editActivityForm.patch(
        updateTourActivity.url({ activity: editingActivityId.value }),
        {
            preserveScroll: true,
            onSuccess: () => {
                cancelEditingActivity();
            },
        },
    );
}

function openDeleteDialog(activity: AdminActivity): void {
    if (deletingActivityId.value !== null) {
        return;
    }

    closeCreateActivityPanel();
    cancelEditingActivity();
    activityPendingDeletion.value = activity;
    isDeleteDialogOpen.value = true;
}

function handleDeleteDialogOpenChange(open: boolean): void {
    isDeleteDialogOpen.value = open;

    if (!open && deletingActivityId.value === null) {
        activityPendingDeletion.value = null;
    }
}

function cancelDeleteDialog(): void {
    if (deletingActivityId.value !== null) {
        return;
    }

    isDeleteDialogOpen.value = false;
    activityPendingDeletion.value = null;
}

function deleteActivity(): void {
    if (
        activityPendingDeletion.value === null ||
        deletingActivityId.value !== null
    ) {
        return;
    }

    deletingActivityId.value = activityPendingDeletion.value.id;

    router.delete(destroyTourActivity.url(activityPendingDeletion.value.id), {
        preserveScroll: true,
        onFinish: () => {
            deletingActivityId.value = null;
            isDeleteDialogOpen.value = false;
            activityPendingDeletion.value = null;
        },
    });
}

const columns: ColumnDef<AdminActivity>[] = [
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
            if (editingActivityId.value === row.original.id) {
                return h('div', { class: 'space-y-2' }, [
                    h(Input, {
                        modelValue: editActivityForm.name,
                        class: 'max-w-sm',
                        placeholder: 'Activity name',
                        disabled: editActivityForm.processing,
                        'onUpdate:modelValue': (value: string | number) => {
                            editActivityForm.name = String(value);
                        },
                    }),
                    h(InputError, {
                        message: editActivityForm.errors.name,
                    }),
                ]);
            }

            return h(
                'div',
                { class: 'font-medium text-foreground' },
                row.original.name,
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
        cell: ({ row }) => {
            if (editingActivityId.value === row.original.id) {
                return h('div', { class: 'flex justify-end gap-2' }, [
                    h(
                        Button,
                        {
                            size: 'sm',
                            class: 'h-8 cursor-pointer px-3',
                            type: 'button',
                            disabled: editActivityForm.processing,
                            onClick: submitEditActivity,
                        },
                        {
                            default: () =>
                                editActivityForm.processing
                                    ? [
                                          h(Spinner, { class: 'size-3.5' }),
                                          'Saving',
                                      ]
                                    : [h(Check, { class: 'size-3.5' }), 'Save'],
                        },
                    ),
                    h(
                        Button,
                        {
                            variant: 'outline',
                            size: 'sm',
                            class: 'h-8 cursor-pointer px-3 text-muted-foreground',
                            type: 'button',
                            disabled: editActivityForm.processing,
                            onClick: cancelEditingActivity,
                        },
                        {
                            default: () => [
                                h(X, { class: 'size-3.5' }),
                                'Cancel',
                            ],
                        },
                    ),
                ]);
            }

            return h('div', { class: 'flex justify-end gap-2' }, [
                h(
                    Button,
                    {
                        variant: 'outline',
                        size: 'sm',
                        class: 'h-8 cursor-pointer px-3 text-muted-foreground',
                        type: 'button',
                        onClick: () => startEditingActivity(row.original),
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
                        disabled: deletingActivityId.value === row.original.id,
                        onClick: () => openDeleteDialog(row.original),
                    },
                    {
                        default: () =>
                            deletingActivityId.value === row.original.id
                                ? [
                                      h(Spinner, { class: 'size-3.5' }),
                                      'Deleting',
                                  ]
                                : [h(Trash2, { class: 'size-3.5' }), 'Delete'],
                    },
                ),
            ]);
        },
    },
];

const table = useVueTable({
    get data() {
        return props.activities;
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
    globalFilterFn: globalActivityFilter,
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
    <Head title="Tour Activities" />

    <div class="flex h-full flex-1 flex-col gap-6 rounded-xl p-4">
        <AlertDialog
            :open="isDeleteDialogOpen"
            @update:open="handleDeleteDialogOpenChange"
        >
            <AlertDialogContent>
                <AlertDialogHeader>
                    <AlertDialogTitle class="text-center"
                        >Delete activity</AlertDialogTitle
                    >
                    <AlertDialogDescription class="text-center">
                        <template v-if="activityPendingDeletion">
                            Delete {{ activityPendingDeletion.name }}? This will
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
                            :disabled="deletingActivityId !== null"
                            @click="cancelDeleteDialog"
                        >
                            Cancel
                        </Button>
                    </AlertDialogCancel>
                    <Button
                        type="button"
                        class="cursor-pointer bg-destructive text-white hover:bg-destructive/90"
                        :disabled="deletingActivityId !== null"
                        @click="deleteActivity"
                    >
                        <Spinner v-if="deletingActivityId !== null" />
                        Delete activity
                    </Button>
                </AlertDialogFooter>
            </AlertDialogContent>
        </AlertDialog>

        <Heading
            title="Tour Activities"
            description="Manage tour activities."
        />

        <Card>
            <CardHeader>
                <div
                    class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between"
                >
                    <div class="space-y-1">
                        <CardTitle>All activities</CardTitle>
                        <CardDescription>
                            Activities that tours can include.
                        </CardDescription>
                    </div>

                    <Button
                        type="button"
                        class="cursor-pointer"
                        :variant="
                            isCreateActivityOpen ? 'secondary' : 'default'
                        "
                        @click="toggleCreateActivityPanel"
                    >
                        <Plus class="size-4" />
                        {{ isCreateActivityOpen ? 'Close' : 'Add activity' }}
                    </Button>
                </div>
            </CardHeader>
            <CardContent class="space-y-4">
                <Collapsible :open="isCreateActivityOpen" class="space-y-4">
                    <CollapsibleContent>
                        <div class="flex justify-end">
                            <form
                                class="inline-block w-full max-w-md rounded-lg border border-border bg-muted/30 p-4"
                                @submit.prevent="submitCreateActivity"
                            >
                                <div class="flex flex-col gap-3">
                                    <div class="w-full max-w-sm space-y-2">
                                        <Input
                                            v-model="createActivityForm.name"
                                            placeholder="Add a new activity"
                                            :disabled="
                                                createActivityForm.processing
                                            "
                                        />
                                        <InputError
                                            :message="
                                                createActivityForm.errors.name
                                            "
                                        />
                                    </div>

                                    <div class="flex gap-2">
                                        <Button
                                            type="submit"
                                            class="cursor-pointer"
                                            :disabled="
                                                createActivityForm.processing
                                            "
                                        >
                                            <Spinner
                                                v-if="
                                                    createActivityForm.processing
                                                "
                                            />
                                            Add
                                        </Button>
                                        <Button
                                            type="button"
                                            variant="outline"
                                            class="cursor-pointer"
                                            :disabled="
                                                createActivityForm.processing
                                            "
                                            @click="closeCreateActivityPanel"
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
                        placeholder="Search activities by name..."
                        @update:model-value="table.setGlobalFilter($event)"
                    />
                    <p class="text-sm text-muted-foreground">
                        {{ filteredRowCount }} activit{{
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
                                    No activities matched your search.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </CardContent>
        </Card>
    </div>
</template>
