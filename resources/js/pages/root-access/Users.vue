<script setup lang="ts">
import type {
    ColumnDef,
    FilterFn,
    SortingState,
    Updater,
} from '@tanstack/vue-table';
import { Form, Head, router } from '@inertiajs/vue3';
import {
    FlexRender,
    getCoreRowModel,
    getFilteredRowModel,
    getSortedRowModel,
    useVueTable,
} from '@tanstack/vue-table';
import { ArrowUpDown } from 'lucide-vue-next';
import { computed, h, ref } from 'vue';
import RootAccessUserController from '@/actions/App/Http/Controllers/RootAccess/UserController';
import Heading from '@/components/Heading.vue';
import InputError from '@/components/InputError.vue';
import PasswordInput from '@/components/PasswordInput.vue';
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
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
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
    DialogFooter,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { Spinner } from '@/components/ui/spinner';
import { users as rootAccessUsers } from '@/routes/root-access';

type RootUser = {
    id: number;
    name: string;
    email: string;
    account_type: string;
    created_at: string | null;
    created_at_display: string;
    can_delete: boolean;
};

const props = defineProps<{
    users: RootUser[];
}>();
const isCreateUserDialogOpen = ref(false);
const createUserAccountType = ref('admin');
const createUserPassword = ref('');
const sendCredentialsEmail = ref(false);
const deletingUserId = ref<number | null>(null);
const isDeleteDialogOpen = ref(false);
const userPendingDeletion = ref<RootUser | null>(null);

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Root Access',
                href: rootAccessUsers(),
            },
            {
                title: 'Users',
                href: rootAccessUsers(),
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

const globalUserFilter: FilterFn<RootUser> = (row, _, filterValue) => {
    const searchValue = String(filterValue).trim().toLowerCase();

    if (searchValue === '') {
        return true;
    }

    return [row.original.name, row.original.email, row.original.account_type]
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

function generatePassword(length: number = 16): void {
    const characters =
        'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz23456789!@#$%^&*';
    const randomValues = crypto.getRandomValues(new Uint32Array(length));

    createUserPassword.value = Array.from(randomValues, (value) => {
        return characters[value % characters.length];
    }).join('');
}

function openDeleteDialog(user: RootUser): void {
    if (!user.can_delete || deletingUserId.value !== null) {
        return;
    }

    userPendingDeletion.value = user;
    isDeleteDialogOpen.value = true;
}

function handleDeleteDialogOpenChange(open: boolean): void {
    isDeleteDialogOpen.value = open;
}

function cancelDeleteDialog(): void {
    if (deletingUserId.value !== null) {
        return;
    }

    isDeleteDialogOpen.value = false;
    userPendingDeletion.value = null;
}

function deleteUser(): void {
    if (deletingUserId.value !== null || userPendingDeletion.value === null) {
        return;
    }

    deletingUserId.value = userPendingDeletion.value.id;

    router.delete(
        RootAccessUserController.destroy.url(userPendingDeletion.value.id),
        {
        preserveScroll: true,
        onFinish: () => {
            deletingUserId.value = null;
            isDeleteDialogOpen.value = false;
            userPendingDeletion.value = null;
        },
        },
    );
}

const columns: ColumnDef<RootUser>[] = [
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
        accessorKey: 'email',
        header: ({ column }) =>
            h(
                Button,
                {
                    variant: 'ghost',
                    class: '-ml-3 h-8 px-3 text-left font-medium',
                    onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
                },
                {
                    default: () => ['Email', h(ArrowUpDown, { class: 'size-4' })],
                },
            ),
        cell: ({ row }) =>
            h('div', { class: 'text-muted-foreground' }, row.original.email),
    },
    {
        accessorKey: 'account_type',
        header: ({ column }) =>
            h(
                Button,
                {
                    variant: 'ghost',
                    class: '-ml-3 h-8 px-3 text-left font-medium',
                    onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
                },
                {
                    default: () => [
                        'Account type',
                        h(ArrowUpDown, { class: 'size-4' }),
                    ],
                },
            ),
        cell: ({ row }) =>
            h(
                Badge,
                { variant: 'secondary' },
                {
                    default: () => row.original.account_type,
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
            row.original.can_delete
                ? h(
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
                                  default: () => 'Edit',
                              },
                          ),
                          h(
                              Button,
                              {
                                  variant: 'outline',
                                  size: 'sm',
                                  class: 'h-7 cursor-pointer px-2 text-xs text-muted-foreground',
                                  disabled: deletingUserId.value === row.original.id,
                                  type: 'button',
                                  onClick: () => openDeleteDialog(row.original),
                              },
                              {
                                  default: () =>
                                      deletingUserId.value === row.original.id
                                          ? [
                                                h(Spinner, { class: 'size-3.5' }),
                                                'Deleting',
                                            ]
                                          : 'Delete',
                              },
                          ),
                      ],
                  )
                : h(
                      'div',
                      { class: 'text-right text-xs text-muted-foreground' },
                      'Current user',
                  ),
    },
];

const table = useVueTable({
    get data() {
        return props.users;
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
    globalFilterFn: globalUserFilter,
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
    <Head title="Users" />

    <div class="flex h-full flex-1 flex-col gap-6 rounded-xl p-4">
        <AlertDialog
            :open="isDeleteDialogOpen"
            @update:open="handleDeleteDialogOpenChange"
        >
            <AlertDialogContent>
                <AlertDialogHeader>
                    <AlertDialogTitle>Delete user</AlertDialogTitle>
                    <AlertDialogDescription>
                        <template v-if="userPendingDeletion">
                            Delete {{ userPendingDeletion.email }}? This action cannot
                            be undone.
                        </template>
                    </AlertDialogDescription>
                </AlertDialogHeader>
                <AlertDialogFooter>
                    <AlertDialogCancel as-child>
                        <Button
                            type="button"
                            variant="outline"
                            class="cursor-pointer"
                            :disabled="deletingUserId !== null"
                            @click="cancelDeleteDialog"
                        >
                            Cancel
                        </Button>
                    </AlertDialogCancel>
                    <AlertDialogAction as-child>
                        <Button
                            type="button"
                            class="cursor-pointer bg-destructive text-white hover:bg-destructive/90"
                            :disabled="deletingUserId !== null"
                            @click="deleteUser"
                        >
                            <Spinner v-if="deletingUserId !== null" class="size-4" />
                            Delete user
                        </Button>
                    </AlertDialogAction>
                </AlertDialogFooter>
            </AlertDialogContent>
        </AlertDialog>

        <Heading
            title="Users"
            description="Manage visibility into every account from the root access area."
        />

        <Card>
            <CardHeader>
                <div
                    class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between"
                >
                    <div class="space-y-1">
                        <CardTitle>All users</CardTitle>
                        <CardDescription>
                            Root accounts can review names, emails, and account
                            types.
                        </CardDescription>
                    </div>

                    <Dialog
                        :open="isCreateUserDialogOpen"
                        @update:open="isCreateUserDialogOpen = $event"
                    >
                        <DialogTrigger as-child>
                            <Button data-test="open-create-user-dialog-button">
                                Create user
                            </Button>
                        </DialogTrigger>
                        <DialogContent class="sm:max-w-lg">
                            <Form
                                v-bind="RootAccessUserController.store.form()"
                                :reset-on-success="[
                                    'name',
                                    'email',
                                    'account_type',
                                    'send_credentials_email',
                                    'password',
                                    'password_confirmation',
                                ]"
                                :options="{ preserveScroll: true }"
                                class="space-y-6"
                                @success="
                                    () => {
                                        isCreateUserDialogOpen = false;
                                        createUserAccountType = 'admin';
                                        createUserPassword = '';
                                        sendCredentialsEmail = false;
                                    }
                                "
                                v-slot="{ errors, processing }"
                            >
                                <DialogHeader class="space-y-3">
                                    <DialogTitle>Create user</DialogTitle>
                                </DialogHeader>

                                <div class="grid gap-4">
                                    <div class="grid gap-2">
                                        <Label for="name">Name</Label>
                                        <Input
                                            id="name"
                                            name="name"
                                            placeholder="Full name"
                                            required
                                        />
                                        <InputError :message="errors.name" />
                                    </div>

                                    <div class="grid gap-2">
                                        <Label for="email">Email address</Label>
                                        <Input
                                            id="email"
                                            name="email"
                                            type="email"
                                            placeholder="email@example.com"
                                            required
                                        />
                                        <InputError :message="errors.email" />
                                    </div>

                                    <div class="grid gap-2">
                                        <Label for="account_type">Account type</Label>
                                        <input
                                            type="hidden"
                                            name="account_type"
                                            :value="createUserAccountType"
                                        />
                                        <Select v-model="createUserAccountType">
                                            <SelectTrigger id="account_type" class="w-full">
                                                <SelectValue placeholder="Select account type" />
                                            </SelectTrigger>
                                            <SelectContent>
                                                <SelectItem value="admin">Admin</SelectItem>
                                                <SelectItem value="root">Root</SelectItem>
                                            </SelectContent>
                                        </Select>
                                        <InputError :message="errors.account_type" />
                                    </div>

                                    <div class="grid gap-2">
                                        <Label for="password">Password</Label>
                                        <input
                                            type="hidden"
                                            name="password_confirmation"
                                            :value="createUserPassword"
                                        />
                                        <div class="grid grid-cols-[minmax(0,1fr)_auto] gap-2">
                                            <PasswordInput
                                                id="password"
                                                v-model="createUserPassword"
                                                name="password"
                                                autocomplete="new-password"
                                                placeholder="Password"
                                                class="w-full"
                                                required
                                            />
                                            <Button
                                                type="button"
                                                variant="secondary"
                                                class="whitespace-nowrap"
                                                @click="generatePassword()"
                                            >
                                                Generate
                                            </Button>
                                        </div>
                                        <InputError :message="errors.password" />
                                        <InputError
                                            :message="errors.password_confirmation"
                                        />
                                    </div>

                                    <div class="flex items-start justify-between">
                                        <Label
                                            for="send_credentials_email"
                                            class="flex items-center gap-2"
                                        >
                                            <input
                                                type="hidden"
                                                name="send_credentials_email"
                                                :value="sendCredentialsEmail ? '1' : '0'"
                                            />
                                            <Checkbox
                                                id="send_credentials_email"
                                                v-model:checked="sendCredentialsEmail"
                                            />
                                            <span class="leading-5">
                                                Send credentials to user via email
                                            </span>
                                        </Label>
                                    </div>
                                </div>

                                <DialogFooter class="justify-start gap-2 sm:justify-start">
                                    <Button
                                        type="submit"
                                        :disabled="processing"
                                        data-test="create-user-button"
                                    >
                                        <Spinner v-if="processing" />
                                        Create user
                                    </Button>
                                </DialogFooter>
                            </Form>
                        </DialogContent>
                    </Dialog>
                </div>
            </CardHeader>
            <CardContent class="space-y-4">
                <div
                    class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between"
                >
                    <Input
                        :model-value="globalFilter"
                        class="w-full sm:max-w-sm"
                        placeholder="Search users by name, email, or type..."
                        @update:model-value="table.setGlobalFilter($event)"
                    />
                    <p class="text-sm text-muted-foreground">
                        {{ filteredRowCount }} user{{ filteredRowCount === 1 ? '' : 's' }}
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
                                    No users matched your search.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </CardContent>
        </Card>
    </div>
</template>
