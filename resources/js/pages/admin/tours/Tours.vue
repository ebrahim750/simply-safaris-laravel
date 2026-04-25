<script setup lang="ts">
import { Head, Link, router, usePage } from '@inertiajs/vue3';
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
import { ArrowUpDown, Eye, EyeOff, Pencil, Plus, Trash2 } from 'lucide-vue-next';
import { computed, defineComponent, h, ref } from 'vue';
import {
    create as createTour,
    destroy as destroyTour,
    toggleHidden as toggleTourHidden,
} from '@/actions/App/Http/Controllers/Admin/TourController';
import Heading from '@/components/Heading.vue';
import { Alert, AlertDescription, AlertTitle } from '@/components/ui/alert';
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
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Spinner } from '@/components/ui/spinner';
import {
    Tooltip,
    TooltipContent,
    TooltipProvider,
    TooltipTrigger,
} from '@/components/ui/tooltip';
import { tours as adminTours } from '@/routes/admin';

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Tours',
                href: adminTours(),
            },
        ],
    },
});

type AdminTour = {
    id: number;
    name: string;
    main_image: string | null;
    price_amount: number;
    price: string;
    is_hidden: boolean;
    is_featured_on_home: boolean;
    is_featured_as_seasonal_recommendation: boolean;
    created_at: string | null;
    created_at_display: string;
};

const props = defineProps<{
    tours: AdminTour[];
}>();
const page = usePage();
const status = computed(() => page.props.flash.status as string | undefined);

const sorting = ref<SortingState>([
    {
        id: 'created_at',
        desc: true,
    },
]);
const globalFilter = ref('');
const deletingTourId = ref<number | null>(null);
const isDeleteDialogOpen = ref(false);
const tourPendingDeletion = ref<AdminTour | null>(null);

const globalTourFilter: FilterFn<AdminTour> = (row, _, filterValue) => {
    const searchValue = String(filterValue).trim().toLowerCase();

    if (searchValue === '') {
        return true;
    }

    return [row.original.name, row.original.price]
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

function toggleTourVisibility(tour: AdminTour): void {
    router.patch(
        toggleTourHidden.url({ tour: tour.id }),
        {},
        {
            preserveScroll: true,
            preserveState: true,
        },
    );
}

function openDeleteDialog(tour: AdminTour): void {
    if (deletingTourId.value !== null) {
        return;
    }

    (document.activeElement as HTMLElement | null)?.blur();
    tourPendingDeletion.value = tour;
    isDeleteDialogOpen.value = true;
}

function handleDeleteDialogOpenChange(open: boolean): void {
    isDeleteDialogOpen.value = open;

    if (!open && deletingTourId.value === null) {
        tourPendingDeletion.value = null;
    }
}

function cancelDeleteDialog(): void {
    if (deletingTourId.value !== null) {
        return;
    }

    isDeleteDialogOpen.value = false;
    tourPendingDeletion.value = null;
}

function deleteTour(): void {
    if (tourPendingDeletion.value === null || deletingTourId.value !== null) {
        return;
    }

    deletingTourId.value = tourPendingDeletion.value.id;

    router.delete(
        destroyTour.url({ tour: tourPendingDeletion.value.id }),
        {
            preserveScroll: true,
            onFinish: () => {
                deletingTourId.value = null;
                isDeleteDialogOpen.value = false;
                tourPendingDeletion.value = null;
            },
        },
    );
}

const TourImageThumbnail = defineComponent({
    name: 'TourImageThumbnail',
    props: {
        imageUrl: {
            type: String,
            default: null,
        },
        name: {
            type: String,
            required: true,
        },
    },
    setup(componentProps) {
        const hasImageError = ref(false);

        return () =>
            componentProps.imageUrl && !hasImageError.value
                ? h('img', {
                      src: componentProps.imageUrl,
                      alt: `${componentProps.name} main image`,
                      class: 'h-12 w-16 rounded-md border border-border bg-muted object-cover',
                      loading: 'lazy',
                      onError: () => {
                          hasImageError.value = true;
                      },
                  })
                : h(
                      'div',
                      {
                          class: 'flex h-12 w-16 items-center justify-center rounded-md border border-dashed border-border bg-muted/40 text-[11px] font-medium uppercase tracking-wide text-muted-foreground',
                      },
                      'No image',
                  );
    },
});

const columns: ColumnDef<AdminTour>[] = [
    {
        id: 'main_image',
        header: 'Main image',
        enableSorting: false,
        cell: ({ row }) =>
            h(TourImageThumbnail, {
                imageUrl: row.original.main_image,
                name: row.original.name,
            }),
    },
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
        cell: ({ row }) =>
            h('div', { class: 'flex flex-col items-start gap-1' }, [
                h('span', { class: 'font-medium text-foreground' }, row.original.name),
                row.original.is_hidden
                    ? h(
                          Badge,
                          {
                              variant: 'outline',
                              class: 'border-orange-200/80 bg-orange-50 px-1.5 py-0 text-[10px] text-orange-700 dark:border-orange-500/30 dark:bg-orange-500/10 dark:text-orange-300',
                          },
                          {
                              default: () => 'Hidden',
                          },
                      )
                    : null,
            ]),
    },
    {
        id: 'price',
        accessorFn: (row) => row.price_amount,
        header: ({ column }) =>
            h(
                Button,
                {
                    variant: 'ghost',
                    class: '-ml-3 h-8 px-3 text-left font-medium',
                    onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
                },
                {
                    default: () => ['Price', h(ArrowUpDown, { class: 'size-4' })],
                },
            ),
        cell: ({ row }) =>
            h('div', { class: 'text-muted-foreground' }, row.original.price),
    },
    {
        accessorKey: 'is_featured_on_home',
        header: 'Featured on Home',
        cell: ({ row }) =>
            h(
                Badge,
                { variant: row.original.is_featured_on_home ? 'default' : 'secondary' },
                {
                    default: () => row.original.is_featured_on_home ? 'Yes' : 'No',
                },
            ),
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
        cell: ({ row }) =>
            h(
                TooltipProvider,
                { delayDuration: 0 },
                {
                    default: () =>
                        h('div', { class: 'flex justify-end gap-2' }, [
                            h(
                                Tooltip,
                                {},
                                {
                                    default: () => [
                                        h(
                                            TooltipTrigger,
                                            { asChild: true },
                                            {
                                                default: () =>
                                                    h(
                                                        Button,
                                                        {
                                                            variant: 'outline',
                                                            size: 'icon',
                                                            class: 'size-8 cursor-pointer text-muted-foreground',
                                                            type: 'button',
                                                            'aria-label': 'Edit tour',
                                                        },
                                                        {
                                                            default: () => h(Pencil, { class: 'size-4' }),
                                                        },
                                                    ),
                                            },
                                        ),
                                        h(
                                            TooltipContent,
                                            {},
                                            {
                                                default: () => h('p', 'Edit tour'),
                                            },
                                        ),
                                    ],
                                },
                            ),
                            h(
                                Tooltip,
                                {},
                                {
                                    default: () => [
                                        h(
                                            TooltipTrigger,
                                            { asChild: true },
                                            {
                                                default: () =>
                                                    h(
                                                        Button,
                                                        {
                                                            variant: 'outline',
                                                            size: 'icon',
                                                            class: 'size-8 cursor-pointer text-muted-foreground',
                                                            type: 'button',
                                                            'aria-label': row.original.is_hidden
                                                                ? 'Unhide tour'
                                                                : 'Hide tour',
                                                            onClick: () =>
                                                                toggleTourVisibility(row.original),
                                                        },
                                                        {
                                                            default: () =>
                                                                h(
                                                                    row.original.is_hidden
                                                                        ? Eye
                                                                        : EyeOff,
                                                                    { class: 'size-4' },
                                                                ),
                                                        },
                                                    ),
                                            },
                                        ),
                                        h(
                                            TooltipContent,
                                            {},
                                            {
                                                default: () =>
                                                    h(
                                                        'p',
                                                        row.original.is_hidden
                                                            ? 'Unhide tour'
                                                            : 'Hide tour',
                                                    ),
                                            },
                                        ),
                                    ],
                                },
                            ),
                            h(
                                Tooltip,
                                {},
                                {
                                    default: () => [
                                        h(
                                            TooltipTrigger,
                                            { asChild: true },
                                            {
                                                default: () =>
                                                    h(
                                                        Button,
                                                        {
                                                            variant: 'outline',
                                                            size: 'icon',
                                                            class: 'size-8 cursor-pointer text-destructive hover:text-destructive',
                                                            type: 'button',
                                                            'aria-label': 'Delete tour',
                                                            disabled: deletingTourId.value === row.original.id,
                                                            onClick: () =>
                                                                openDeleteDialog(row.original),
                                                        },
                                                        {
                                                            default: () => h(Trash2, { class: 'size-4' }),
                                                        },
                                                    ),
                                            },
                                        ),
                                        h(
                                            TooltipContent,
                                            {},
                                            {
                                                default: () => h('p', 'Delete tour'),
                                            },
                                        ),
                                    ],
                                },
                            ),
                        ]),
                },
            ),
    },
];

const table = useVueTable({
    get data() {
        return props.tours;
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
    globalFilterFn: globalTourFilter,
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
    <Head title="Tours" />

    <div class="flex h-full flex-1 flex-col gap-6 rounded-xl p-4">
        <AlertDialog
            :open="isDeleteDialogOpen"
            @update:open="handleDeleteDialogOpenChange"
        >
            <AlertDialogContent>
                <AlertDialogHeader>
                    <AlertDialogTitle class="text-center">Delete tour</AlertDialogTitle>
                    <AlertDialogDescription class="text-center">
                        <template v-if="tourPendingDeletion">
                            Delete {{ tourPendingDeletion.name }}? This action cannot be undone.
                        </template>
                    </AlertDialogDescription>
                </AlertDialogHeader>
                <AlertDialogFooter>
                    <AlertDialogCancel as-child>
                        <Button
                            type="button"
                            variant="outline"
                            class="cursor-pointer"
                            :disabled="deletingTourId !== null"
                            @click="cancelDeleteDialog"
                        >
                            Cancel
                        </Button>
                    </AlertDialogCancel>
                    <Button
                        type="button"
                        class="cursor-pointer bg-destructive text-white hover:bg-destructive/90"
                        :disabled="deletingTourId !== null"
                        @click="deleteTour"
                    >
                        <Spinner v-if="deletingTourId !== null" class="size-4" />
                        Delete tour
                    </Button>
                </AlertDialogFooter>
            </AlertDialogContent>
        </AlertDialog>

        <Heading
            title="Tours"
            description="Manage tours available for booking."
        />

        <Alert v-if="status">
            <AlertTitle>Tours updated</AlertTitle>
            <AlertDescription>{{ status }}</AlertDescription>
        </Alert>

        <Card>
            <CardHeader>
                <div
                    class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between"
                >
                    <div class="space-y-1">
                        <CardTitle>All tours</CardTitle>
                        <CardDescription>
                            View and manage tour listings.
                        </CardDescription>
                    </div>
                    <Button as-child class="cursor-pointer">
                        <Link :href="createTour()">
                            <Plus class="size-4" />
                            New tour
                        </Link>
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
                        placeholder="Search tours by name or price..."
                        @update:model-value="table.setGlobalFilter($event)"
                    />
                    <p class="text-sm text-muted-foreground">
                        {{ filteredRowCount }} tour{{ filteredRowCount === 1 ? '' : 's' }}
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
                                    No tours matched your search.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </CardContent>
        </Card>
    </div>
</template>
