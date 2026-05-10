<script setup lang="ts">
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import type { ColumnDef, FilterFn, SortingState, Updater } from '@tanstack/vue-table';
import {
    FlexRender,
    getCoreRowModel,
    getFilteredRowModel,
    getSortedRowModel,
    useVueTable,
} from '@tanstack/vue-table';
import { ArrowUpDown, Pencil, Plus, Trash2 } from 'lucide-vue-next';
import { computed, h, ref } from 'vue';
import {
    create as createCountry,
    destroy as destroyCountry,
    edit as editCountry,
    index as countriesIndex,
} from '@/actions/App/Http/Controllers/Admin/DestinationCountryController';
import Heading from '@/components/Heading.vue';
import { Alert, AlertDescription, AlertTitle } from '@/components/ui/alert';
import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import { Input } from '@/components/ui/input';

type AdminCountry = {
    country_code: string | null;
    id: number;
    name: string;
    slug: string;
    created_at: string | null;
    created_at_display: string;
};

const props = defineProps<{
    countries: AdminCountry[];
}>();

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Destinations',
                href: countriesIndex(),
            },
            {
                title: 'Countries',
                href: countriesIndex(),
            },
        ],
    },
});

const page = usePage();
const status = computed(() => page.props.flash.status as string | undefined);
const sorting = ref<SortingState>([
    {
        id: 'created_at',
        desc: true,
    },
]);
const globalFilter = ref('');

const globalCountryFilter: FilterFn<AdminCountry> = (row, _, filterValue) => {
    const searchValue = String(filterValue).trim().toLowerCase();

    if (searchValue === '') {
        return true;
    }

    return [row.original.name, row.original.slug]
        .concat(row.original.country_code ?? '')
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

function removeCountry(country: AdminCountry): void {
    if (! window.confirm(`Delete ${country.name}?`)) {
        return;
    }

    router.delete(destroyCountry.url({ country: country.id }), {
        preserveScroll: true,
    });
}

const columns: ColumnDef<AdminCountry>[] = [
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
    },
    {
        accessorKey: 'slug',
        header: 'Slug',
        cell: ({ row }) =>
            h('span', { class: 'font-mono text-xs text-muted-foreground' }, row.original.slug),
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
            h('div', { class: 'text-muted-foreground' }, row.original.created_at_display),
    },
    {
        id: 'actions',
        enableSorting: false,
        header: () => h('div', { class: 'text-right' }, 'Actions'),
        cell: ({ row }) =>
            h('div', { class: 'flex justify-end gap-2' }, [
                h(
                    Link,
                    {
                        href: editCountry({ country: row.original.id }),
                        class: 'inline-flex size-8 items-center justify-center rounded-md border border-input bg-background text-muted-foreground shadow-xs transition-colors hover:bg-accent hover:text-accent-foreground',
                        'aria-label': `Edit ${row.original.name}`,
                    },
                    {
                        default: () => h(Pencil, { class: 'size-4' }),
                    },
                ),
                h(
                    Button,
                    {
                        variant: 'outline',
                        size: 'icon',
                        class: 'size-8 cursor-pointer text-destructive hover:text-destructive',
                        type: 'button',
                        onClick: () => removeCountry(row.original),
                    },
                    {
                        default: () => h(Trash2, { class: 'size-4' }),
                    },
                ),
            ]),
    },
];

const table = useVueTable({
    get data() {
        return props.countries;
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
    globalFilterFn: globalCountryFilter,
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
    <Head title="Countries" />

    <div class="flex h-full flex-1 flex-col gap-6 rounded-xl p-4">
        <Heading
            title="Countries"
            description="Manage destination countries used across the admin."
        />

        <Alert v-if="status">
            <AlertTitle>Countries updated</AlertTitle>
            <AlertDescription>{{ status }}</AlertDescription>
        </Alert>

        <Card>
            <CardHeader>
                <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                    <div class="space-y-1">
                        <CardTitle>All countries</CardTitle>
                        <CardDescription>View and manage destination countries.</CardDescription>
                    </div>
                    <Button as-child class="cursor-pointer">
                        <Link :href="createCountry()">
                            <Plus class="size-4" />
                            Add country
                        </Link>
                    </Button>
                </div>
            </CardHeader>
            <CardContent class="space-y-4">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <Input
                        :model-value="globalFilter"
                        class="w-full sm:max-w-sm"
                        placeholder="Search countries by name or slug..."
                        @update:model-value="table.setGlobalFilter($event)"
                    />
                    <p class="text-sm text-muted-foreground">
                        {{ filteredRowCount }} countr{{ filteredRowCount === 1 ? 'y' : 'ies' }}
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
                                    No countries matched your search.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </CardContent>
        </Card>
    </div>
</template>
