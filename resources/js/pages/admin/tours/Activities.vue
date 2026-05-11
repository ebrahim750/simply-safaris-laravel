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
    destroy as destroyTourActivity,
    store as storeTourActivity,
    update as updateTourActivity,
} from '@/actions/App/Http/Controllers/Admin/TourActivityController';
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
    description: string | null;
    image_url: string | null;
    tours: { id: number; name: string; main_image: string | null }[];
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
const isModalOpen = ref(false);
const editingActivityId = ref<number | null>(null);
const deletingActivityId = ref<number | null>(null);
const isDeleteDialogOpen = ref(false);
const activityPendingDeletion = ref<AdminActivity | null>(null);
const isRelatedToursDialogOpen = ref(false);
const activityForRelatedTours = ref<AdminActivity | null>(null);
const imagePreview = ref<string | null>(null);

const activityForm = useForm<{
    name: string;
    description: string;
    image: File | null;
}>({
    name: '',
    description: '',
    image: null,
});

const globalActivityFilter: FilterFn<AdminActivity> = (row, _, filterValue) => {
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
    activityForm.reset();
    activityForm.clearErrors();
    imagePreview.value = null;
}

function closeModal(): void {
    isModalOpen.value = false;
    editingActivityId.value = null;
    resetForm();
}

function openCreateModal(): void {
    editingActivityId.value = null;
    resetForm();
    isModalOpen.value = true;
}

function openEditModal(activity: AdminActivity): void {
    editingActivityId.value = activity.id;
    activityForm.name = activity.name;
    activityForm.description = activity.description ?? '';
    activityForm.image = null;
    imagePreview.value = activity.image_url;
    isModalOpen.value = true;
}

function openRelatedToursDialog(activity: AdminActivity): void {
    activityForRelatedTours.value = activity;
    isRelatedToursDialogOpen.value = true;
}

function closeRelatedToursDialog(): void {
    isRelatedToursDialogOpen.value = false;
    activityForRelatedTours.value = null;
}

function submitActivityForm(): void {
    if (editingActivityId.value === null) {
        activityForm.post(storeTourActivity.url(), {
            preserveScroll: true,
            forceFormData: true,
            onSuccess: () => {
                resetForm();
                isModalOpen.value = false;
            },
        });
    } else {
        activityForm.patch(
            updateTourActivity.url({ activity: editingActivityId.value }),
            {
                preserveScroll: true,
                forceFormData: true,
                onSuccess: () => {
                    resetForm();
                    isModalOpen.value = false;
                    editingActivityId.value = null;
                },
            },
        );
    }
}

function openDeleteDialog(activity: AdminActivity): void {
    if (deletingActivityId.value !== null) {
        return;
    }

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
            ]),
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

        <Dialog
            :open="isRelatedToursDialogOpen"
            @update:open="closeRelatedToursDialog"
        >
            <DialogContent class="max-w-md">
                <DialogHeader>
                    <DialogTitle>
                        <template v-if="activityForRelatedTours">
                            {{ activityForRelatedTours.name }} — Associated Tours
                        </template>
                    </DialogTitle>
                    <DialogDescription>
                        Tours linked to this activity.
                    </DialogDescription>
                </DialogHeader>

                <div class="space-y-2">
                    <template v-if="activityForRelatedTours">
                        <div
                            v-if="activityForRelatedTours.tours.length === 0"
                            class="py-4 text-center text-sm text-muted-foreground"
                        >
                            No tours associated with this activity.
                        </div>
                        <table v-else class="min-w-full text-sm">
                            <tbody class="divide-y divide-border">
                                <tr
                                    v-for="tour in activityForRelatedTours.tours"
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
                        {{ editingActivityId === null ? 'Add activity' : 'Edit activity' }}
                    </DialogTitle>
                    <DialogDescription>
                        {{ editingActivityId === null ? 'Create a new tour activity.' : 'Update this tour activity.' }}
                    </DialogDescription>
                </DialogHeader>

                <form
                    class="space-y-4"
                    @submit.prevent="submitActivityForm"
                >
                    <div class="space-y-2">
                        <Label for="activity_name">Name</Label>
                        <Input
                            id="activity_name"
                            v-model="activityForm.name"
                            placeholder="Activity name"
                            :disabled="activityForm.processing"
                        />
                        <InputError :message="activityForm.errors.name" />
                    </div>

                    <div class="space-y-2">
                        <Label for="activity_description">Description</Label>
                        <textarea
                            id="activity_description"
                            v-model="activityForm.description"
                            rows="4"
                            class="flex min-h-[80px] w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                            placeholder="Describe this activity..."
                            :disabled="activityForm.processing"
                        />
                        <InputError :message="activityForm.errors.description" />
                    </div>

                    <div class="space-y-2">
                        <FileUpload
                            id="activity_image"
                            label="Image"
                            v-model="activityForm.image"
                            :preview-url="imagePreview"
                            preview-alt="Activity image"
                            accept=".png,.jpg,.jpeg,.webp"
                            hint="PNG, JPG, JPEG, or WEBP up to 5MB"
                            :error="activityForm.errors.image"
                        />
                    </div>

                    <DialogFooter>
                        <Button
                            type="button"
                            variant="outline"
                            class="cursor-pointer"
                            :disabled="activityForm.processing"
                            @click="closeModal"
                        >
                            Cancel
                        </Button>
                        <Button
                            type="submit"
                            class="cursor-pointer"
                            :disabled="activityForm.processing"
                        >
                            <Spinner v-if="activityForm.processing" />
                            {{ editingActivityId === null ? 'Add activity' : 'Save changes' }}
                        </Button>
                    </DialogFooter>
                </form>
            </DialogContent>
        </Dialog>

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
                        @click="openCreateModal"
                    >
                        <Plus class="size-4" />
                        Add activity
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
                        placeholder="Search activities..."
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
