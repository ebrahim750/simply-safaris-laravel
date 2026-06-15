<script setup lang="ts">
import { Head, router, usePage } from '@inertiajs/vue3';
import { Eye, Trash2 } from 'lucide-vue-next';
import { ref, watch } from 'vue';
import Heading from '@/components/Heading.vue';
import Pagination from '@/components/Pagination.vue';
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
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Spinner } from '@/components/ui/spinner';
import {
    Tooltip,
    TooltipContent,
    TooltipProvider,
    TooltipTrigger,
} from '@/components/ui/tooltip';
import { tour as adminFormSubmissionsTour } from '@/routes/admin/form-submissions';
import { destroy as destroyTourEnquiry } from '@/routes/admin/form-submissions/tour';

function formatDate(value: string | null): string {
    if (!value) {
        return '-';
    }

    const date = new Date(value);

    return Number.isNaN(date.getTime())
        ? '-'
        : date.toLocaleDateString('en-US', {
              month: 'short',
              day: 'numeric',
              year: 'numeric',
          });
}

function formatDateTime(value: string | null): string {
    if (!value) {
        return '-';
    }

    const date = new Date(value);

    return Number.isNaN(date.getTime())
        ? '-'
        : date.toLocaleString('en-US', {
              month: 'short',
              day: 'numeric',
              year: 'numeric',
              hour: 'numeric',
              minute: '2-digit',
          });
}

function debounce<T extends (...args: Parameters<T>) => ReturnType<T>>(
    fn: T,
    delay: number,
): (...args: Parameters<T>) => void {
    let timeoutId: ReturnType<typeof setTimeout> | null = null;

    return (...args) => {
        if (timeoutId !== null) {
            clearTimeout(timeoutId);
        }

        timeoutId = setTimeout(() => fn(...args), delay);
    };
}

const page = usePage();
const searchParams = new URLSearchParams(page.url.split('?')[1] ?? '');
const search = ref(searchParams.get('search') ?? '');

const applySearch = debounce((value: string): void => {
    router.get(
        adminFormSubmissionsTour.url({ query: { search: value || undefined } }),
        {},
        {
            preserveState: true,
            preserveScroll: true,
            replace: true,
        },
    );
}, 300);

watch(search, (value) => applySearch(value));

const isViewDialogOpen = ref(false);
const selectedEnquiry = ref<Enquiry | null>(null);

const isDeleteDialogOpen = ref(false);
const enquiryPendingDeletion = ref<Enquiry | null>(null);
const deletingEnquiryId = ref<number | null>(null);

function openViewDialog(enquiry: Enquiry): void {
    selectedEnquiry.value = enquiry;
    isViewDialogOpen.value = true;
}

function openDeleteDialog(enquiry: Enquiry): void {
    if (deletingEnquiryId.value !== null) {
        return;
    }

    (document.activeElement as HTMLElement | null)?.blur();
    enquiryPendingDeletion.value = enquiry;
    isDeleteDialogOpen.value = true;
}

function handleDeleteDialogOpenChange(open: boolean): void {
    isDeleteDialogOpen.value = open;

    if (!open && deletingEnquiryId.value === null) {
        enquiryPendingDeletion.value = null;
    }
}

function cancelDeleteDialog(): void {
    if (deletingEnquiryId.value !== null) {
        return;
    }

    isDeleteDialogOpen.value = false;
    enquiryPendingDeletion.value = null;
}

function deleteEnquiry(): void {
    if (
        enquiryPendingDeletion.value === null ||
        deletingEnquiryId.value !== null
    ) {
        return;
    }

    deletingEnquiryId.value = enquiryPendingDeletion.value.id;

    router.delete(
        destroyTourEnquiry.url({
            tour_enquiry: enquiryPendingDeletion.value.id,
        }),
        {
            preserveScroll: true,
            onFinish: () => {
                deletingEnquiryId.value = null;
                isDeleteDialogOpen.value = false;
                enquiryPendingDeletion.value = null;
            },
        },
    );
}

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Form Submissions',
                href: adminFormSubmissionsTour(),
            },
            {
                title: 'Tour Form',
                href: adminFormSubmissionsTour(),
            },
        ],
    },
});

type PaginatorLink = {
    url: string | null;
    label: string;
    active: boolean;
};

type Enquiry = {
    id: number;
    tour: { id: number; name: string } | null;
    name: string;
    email: string;
    phone: string;
    booking_date: string;
    adults: string;
    children: string | null;
    message: string | null;
    created_at: string;
};

type EnquiriesPaginator = {
    data: Enquiry[];
    current_page: number;
    from: number;
    last_page: number;
    total: number;
    prev_page_url: string | null;
    next_page_url: string | null;
    links: PaginatorLink[];
};

defineProps<{
    enquiries: EnquiriesPaginator;
}>();
</script>

<template>
    <Head title="Tour Form Submissions" />

    <div class="flex h-full flex-1 flex-col gap-6 rounded-xl p-4">
        <Heading
            title="Tour Form Submissions"
            description="View enquiries submitted from the tour enquiry form."
        />

        <Dialog v-model:open="isViewDialogOpen">
            <DialogContent class="sm:max-w-lg">
                <DialogHeader>
                    <DialogTitle>Tour Enquiry Details</DialogTitle>
                    <DialogDescription>
                        Submitted on
                        {{
                            formatDateTime(selectedEnquiry?.created_at ?? null)
                        }}
                    </DialogDescription>
                </DialogHeader>

                <div v-if="selectedEnquiry" class="space-y-3 text-sm">
                    <div class="grid grid-cols-3 gap-2">
                        <span class="text-muted-foreground">Tour</span>
                        <span class="col-span-2 font-medium">
                            {{ selectedEnquiry.tour?.name ?? 'Unknown' }}
                        </span>
                    </div>
                    <div class="grid grid-cols-3 gap-2">
                        <span class="text-muted-foreground">Name</span>
                        <span class="col-span-2 font-medium">
                            {{ selectedEnquiry.name }}
                        </span>
                    </div>
                    <div class="grid grid-cols-3 gap-2">
                        <span class="text-muted-foreground">Email</span>
                        <span class="col-span-2 font-medium">
                            {{ selectedEnquiry.email }}
                        </span>
                    </div>
                    <div class="grid grid-cols-3 gap-2">
                        <span class="text-muted-foreground">Phone</span>
                        <span class="col-span-2 font-medium">
                            {{ selectedEnquiry.phone }}
                        </span>
                    </div>
                    <div class="grid grid-cols-3 gap-2">
                        <span class="text-muted-foreground">Booking Date</span>
                        <span class="col-span-2 font-medium">
                            {{ formatDate(selectedEnquiry.booking_date) }}
                        </span>
                    </div>
                    <div class="grid grid-cols-3 gap-2">
                        <span class="text-muted-foreground">Adults</span>
                        <span class="col-span-2 font-medium">
                            {{ selectedEnquiry.adults }}
                        </span>
                    </div>
                    <div class="grid grid-cols-3 gap-2">
                        <span class="text-muted-foreground">Children</span>
                        <span class="col-span-2 font-medium">
                            {{ selectedEnquiry.children ?? '0' }}
                        </span>
                    </div>
                    <div class="grid grid-cols-3 gap-2">
                        <span class="text-muted-foreground">Message</span>
                        <span class="col-span-2 font-medium">
                            {{ selectedEnquiry.message ?? '-' }}
                        </span>
                    </div>
                </div>
            </DialogContent>
        </Dialog>

        <AlertDialog
            :open="isDeleteDialogOpen"
            @update:open="handleDeleteDialogOpenChange"
        >
            <AlertDialogContent>
                <AlertDialogHeader>
                    <AlertDialogTitle class="text-center"
                        >Delete tour enquiry</AlertDialogTitle
                    >
                    <AlertDialogDescription class="text-center">
                        <template v-if="enquiryPendingDeletion">
                            Delete enquiry from
                            {{ enquiryPendingDeletion.name }}? This action
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
                            :disabled="deletingEnquiryId !== null"
                            @click="cancelDeleteDialog"
                        >
                            Cancel
                        </Button>
                    </AlertDialogCancel>
                    <Button
                        type="button"
                        class="cursor-pointer bg-destructive text-white hover:bg-destructive/90"
                        :disabled="deletingEnquiryId !== null"
                        @click="deleteEnquiry"
                    >
                        <Spinner
                            v-if="deletingEnquiryId !== null"
                            class="size-4"
                        />
                        Delete enquiry
                    </Button>
                </AlertDialogFooter>
            </AlertDialogContent>
        </AlertDialog>

        <Card>
            <CardHeader>
                <CardTitle>Submissions</CardTitle>
                <CardDescription>
                    Showing {{ enquiries.data.length }} of
                    {{ enquiries.total ?? enquiries.data.length }} enquiries.
                </CardDescription>
            </CardHeader>
            <CardContent class="space-y-4">
                <div
                    class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between"
                >
                    <Input
                        v-model="search"
                        class="w-full sm:max-w-sm"
                        placeholder="Search..."
                        type="search"
                    />
                    <p class="text-sm text-muted-foreground">
                        {{ enquiries.total ?? enquiries.data.length }}
                        {{ enquiries.total === 1 ? 'enquiry' : 'enquiries' }}
                    </p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b">
                                <th class="px-4 py-3 text-left font-medium">
                                    #
                                </th>
                                <th class="px-4 py-3 text-left font-medium">
                                    Tour
                                </th>
                                <th class="px-4 py-3 text-left font-medium">
                                    Name
                                </th>
                                <th class="px-4 py-3 text-left font-medium">
                                    Email
                                </th>
                                <th class="px-4 py-3 text-left font-medium">
                                    Phone
                                </th>
                                <th class="px-4 py-3 text-left font-medium">
                                    Booking Date
                                </th>
                                <th class="px-4 py-3 text-left font-medium">
                                    Adults
                                </th>
                                <th class="px-4 py-3 text-left font-medium">
                                    Children
                                </th>
                                <th class="px-4 py-3 text-left font-medium">
                                    Submitted At
                                </th>
                                <th class="px-4 py-3 text-right font-medium">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="(enquiry, index) in enquiries.data"
                                :key="enquiry.id"
                                class="border-b last:border-b-0"
                            >
                                <td class="px-4 py-3">
                                    {{ (enquiries.from ?? 1) + index }}
                                </td>
                                <td class="px-4 py-3">
                                    {{ enquiry.tour?.name ?? 'Unknown' }}
                                </td>
                                <td class="px-4 py-3">{{ enquiry.name }}</td>
                                <td class="px-4 py-3">{{ enquiry.email }}</td>
                                <td class="px-4 py-3">{{ enquiry.phone }}</td>
                                <td class="px-4 py-3">
                                    {{ formatDate(enquiry.booking_date) }}
                                </td>
                                <td class="px-4 py-3">{{ enquiry.adults }}</td>
                                <td class="px-4 py-3">
                                    {{ enquiry.children ?? '0' }}
                                </td>
                                <td class="px-4 py-3">
                                    {{ formatDateTime(enquiry.created_at) }}
                                </td>
                                <td class="px-4 py-3">
                                    <TooltipProvider :delay-duration="0">
                                        <div class="flex justify-end gap-2">
                                            <Tooltip>
                                                <TooltipTrigger as-child>
                                                    <Button
                                                        variant="outline"
                                                        size="icon"
                                                        class="size-8 cursor-pointer text-muted-foreground"
                                                        type="button"
                                                        aria-label="View enquiry"
                                                        @click="
                                                            openViewDialog(
                                                                enquiry,
                                                            )
                                                        "
                                                    >
                                                        <Eye class="size-4" />
                                                    </Button>
                                                </TooltipTrigger>
                                                <TooltipContent>
                                                    <p>View enquiry</p>
                                                </TooltipContent>
                                            </Tooltip>
                                            <Tooltip>
                                                <TooltipTrigger as-child>
                                                    <Button
                                                        variant="outline"
                                                        size="icon"
                                                        class="size-8 cursor-pointer text-destructive hover:text-destructive"
                                                        type="button"
                                                        aria-label="Delete enquiry"
                                                        :disabled="
                                                            deletingEnquiryId ===
                                                            enquiry.id
                                                        "
                                                        @click="
                                                            openDeleteDialog(
                                                                enquiry,
                                                            )
                                                        "
                                                    >
                                                        <Trash2
                                                            class="size-4"
                                                        />
                                                    </Button>
                                                </TooltipTrigger>
                                                <TooltipContent>
                                                    <p>Delete enquiry</p>
                                                </TooltipContent>
                                            </Tooltip>
                                        </div>
                                    </TooltipProvider>
                                </td>
                            </tr>
                            <tr v-if="enquiries.data.length === 0">
                                <td
                                    colspan="10"
                                    class="px-4 py-8 text-center text-muted-foreground"
                                >
                                    No tour enquiries found.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="mt-6 flex justify-center">
                    <Pagination :paginator="enquiries" />
                </div>
            </CardContent>
        </Card>
    </div>
</template>
