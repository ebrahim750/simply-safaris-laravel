<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
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
import { ArrowUpDown, Pencil, Plus, Trash2 } from 'lucide-vue-next';
import { computed, h, ref } from 'vue';
import {
    create as createCountry,
    destroy as destroyCountry,
    edit as editCountry,
    index as countriesIndex,
} from '@/actions/App/Http/Controllers/Admin/DestinationCountryController';
import Heading from '@/components/Heading.vue';
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
import { Input } from '@/components/ui/input';
import { Spinner } from '@/components/ui/spinner';

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

const sorting = ref<SortingState>([
    {
        id: 'created_at',
        desc: true,
    },
]);
const globalFilter = ref('');
const isDeleteDialogOpen = ref(false);
const deletingCountryId = ref<number | null>(null);
const countryPendingDeletion = ref<AdminCountry | null>(null);

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

function openDeleteDialog(country: AdminCountry): void {
    if (deletingCountryId.value !== null) {
        return;
    }

    countryPendingDeletion.value = country;
    isDeleteDialogOpen.value = true;
}

function handleDeleteDialogOpenChange(open: boolean): void {
    isDeleteDialogOpen.value = open;
}

function cancelDeleteDialog(): void {
    if (deletingCountryId.value !== null) {
        return;
    }

    isDeleteDialogOpen.value = false;
    countryPendingDeletion.value = null;
}

function deleteCountry(): void {
    if (deletingCountryId.value !== null || countryPendingDeletion.value === null) {
        return;
    }

    deletingCountryId.value = countryPendingDeletion.value.id;

    router.delete(
        destroyCountry.url({ country: countryPendingDeletion.value.id }),
        {
            preserveScroll: true,
            onFinish: () => {
                deletingCountryId.value = null;
                isDeleteDialogOpen.value = false;
                countryPendingDeletion.value = null;
            },
        },
    );
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
                    onClick: () =>
                        column.toggleSorting(column.getIsSorted() === 'asc'),
                },
                {
                    default: () => [
                        'Country',
                        h(ArrowUpDown, { class: 'size-4' }),
                    ],
                },
            ),
        cell: ({ row }) =>
            h('div', { class: 'flex items-center gap-3' }, [
                row.original.country_code
                    ? h('img', {
                          src: `https://flagcdn.com/w40/${row.original.country_code}.png`,
                          alt: `${row.original.name} flag`,
                          class: 'h-5 w-7 rounded-[2px] border border-border object-cover',
                      })
                    : null,
                h('span', row.original.name),
            ]),
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
                        size: 'icon',
                        class: 'size-8 cursor-pointer',
                        disabled: deletingCountryId.value !== null,
                        asChild: true,
                    },
                    {
                        default: () =>
                            h(
                                Link,
                                {
                                    href: editCountry({ country: row.original.id }),
                                    'aria-label': `Edit ${row.original.name}`,
                                },
                                {
                                    default: () => h(Pencil, { class: 'size-4' }),
                                },
                            ),
                    },
                ),
                h(
                    Button,
                    {
                        variant: 'outline',
                        size: 'icon',
                        class: 'size-8 cursor-pointer text-destructive hover:text-destructive',
                        type: 'button',
                        disabled: deletingCountryId.value !== null,
                        onClick: () => openDeleteDialog(row.original),
                    },
                    {
                        default: () =>
                            deletingCountryId.value === row.original.id
                                ? h(Spinner, { class: 'size-4' })
                                : h(Trash2, { class: 'size-4' }),
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

const filteredRowCount = computed(
    () => table.getFilteredRowModel().rows.length,
);
</script>

<template>
    <Head title="Countries" />

    <div class="flex h-full flex-1 flex-col gap-6 rounded-xl p-4">
        <AlertDialog
            :open="isDeleteDialogOpen"
            @update:open="handleDeleteDialogOpenChange"
        >
            <AlertDialogContent>
                <AlertDialogHeader>
                    <AlertDialogTitle>Delete country</AlertDialogTitle>
                    <AlertDialogDescription>
                        <template v-if="countryPendingDeletion">
                            Delete {{ countryPendingDeletion.name }}? This action
                            cannot be undone.
                        </template>
                    </AlertDialogDescription>
                </AlertDialogHeader>
                <AlertDialogFooter>
                    <AlertDialogCancel as-child>
                        <Button
                            type="button"
                            variant="outline"
                            class="cursor-pointer"
                            :disabled="deletingCountryId !== null"
                            @click="cancelDeleteDialog"
                        >
                            Cancel
                        </Button>
                    </AlertDialogCancel>
                    <AlertDialogAction as-child>
                        <Button
                            type="button"
                            class="cursor-pointer bg-destructive text-white hover:bg-destructive/90"
                            :disabled="deletingCountryId !== null"
                            @click="deleteCountry"
                        >
                            <Spinner
                                v-if="deletingCountryId !== null"
                                class="size-4"
                            />
                            Delete country
                        </Button>
                    </AlertDialogAction>
                </AlertDialogFooter>
            </AlertDialogContent>
        </AlertDialog>

        <Heading
            title="Countries"
            description="Manage destination countries used across the admin."
        />

        <Card>
            <CardHeader>
                <div
                    class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between"
                >
                    <div class="space-y-1">
                        <CardTitle>All countries</CardTitle>
                        <CardDescription
                            >View and manage destination
                            countries.</CardDescription
                        >
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
                <div
                    class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between"
                >
                    <Input
                        :model-value="globalFilter"
                        class="w-full sm:max-w-sm"
                        placeholder="Search countries by name..."
                        @update:model-value="table.setGlobalFilter($event)"
                    />
                    <p class="text-sm text-muted-foreground">
                        {{ filteredRowCount }} countr{{
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
