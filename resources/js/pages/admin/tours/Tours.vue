<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
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
import { ArrowUpDown, Pencil } from 'lucide-vue-next';
import { computed, h, ref } from 'vue';
import Heading from '@/components/Heading.vue';
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

const sorting = ref<SortingState>([
    {
        id: 'created_at',
        desc: true,
    },
]);
const globalFilter = ref('');

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

const columns: ColumnDef<AdminTour>[] = [
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
            h('div', { class: 'font-medium text-foreground' }, row.original.name),
    },
    {
        accessorKey: 'price',
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
        accessorKey: 'is_hidden',
        header: 'Hidden',
        cell: ({ row }) =>
            h(
                Badge,
                { variant: row.original.is_hidden ? 'destructive' : 'secondary' },
                {
                    default: () => row.original.is_hidden ? 'Yes' : 'No',
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
        cell: () =>
            h(
                'div',
                { class: 'flex justify-end gap-2' },
                [
                    h(
                        Button,
                        {
                            variant: 'outline',
                            size: 'sm',
                            class: 'h-7 cursor-pointer px-2 text-xs text-muted-foreground',
                            type: 'button',
                        },
                        {
                            default: () => h(Pencil, { class: 'size-3 mr-1' }),
                        },
                    ),
                ],
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
        <Heading
            title="Tours"
            description="Manage tours available for booking."
        />

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
