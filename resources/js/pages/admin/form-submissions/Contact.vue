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
import { contact as adminFormSubmissionsContact } from '@/routes/admin/form-submissions';
import { destroy as destroyContactSubmission } from '@/routes/admin/form-submissions/contact';

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
        adminFormSubmissionsContact.url({
            query: { search: value || undefined },
        }),
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
const selectedSubmission = ref<Submission | null>(null);

const isDeleteDialogOpen = ref(false);
const submissionPendingDeletion = ref<Submission | null>(null);
const deletingSubmissionId = ref<number | null>(null);

function openViewDialog(submission: Submission): void {
    selectedSubmission.value = submission;
    isViewDialogOpen.value = true;
}

function openDeleteDialog(submission: Submission): void {
    if (deletingSubmissionId.value !== null) {
        return;
    }

    (document.activeElement as HTMLElement | null)?.blur();
    submissionPendingDeletion.value = submission;
    isDeleteDialogOpen.value = true;
}

function handleDeleteDialogOpenChange(open: boolean): void {
    isDeleteDialogOpen.value = open;

    if (!open && deletingSubmissionId.value === null) {
        submissionPendingDeletion.value = null;
    }
}

function cancelDeleteDialog(): void {
    if (deletingSubmissionId.value !== null) {
        return;
    }

    isDeleteDialogOpen.value = false;
    submissionPendingDeletion.value = null;
}

function deleteSubmission(): void {
    if (
        submissionPendingDeletion.value === null ||
        deletingSubmissionId.value !== null
    ) {
        return;
    }

    deletingSubmissionId.value = submissionPendingDeletion.value.id;

    router.delete(
        destroyContactSubmission.url({
            contact_submission: submissionPendingDeletion.value.id,
        }),
        {
            preserveScroll: true,
            onFinish: () => {
                deletingSubmissionId.value = null;
                isDeleteDialogOpen.value = false;
                submissionPendingDeletion.value = null;
            },
        },
    );
}

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Form Submissions',
                href: adminFormSubmissionsContact(),
            },
            {
                title: 'Contact Form',
                href: adminFormSubmissionsContact(),
            },
        ],
    },
});

type PaginatorLink = {
    url: string | null;
    label: string;
    active: boolean;
};

type Submission = {
    id: number;
    name: string;
    email: string;
    phone: string;
    message: string;
    ip_address: string | null;
    user_agent: string | null;
    created_at: string;
};

type SubmissionsPaginator = {
    data: Submission[];
    current_page: number;
    from: number;
    last_page: number;
    total: number;
    prev_page_url: string | null;
    next_page_url: string | null;
    links: PaginatorLink[];
};

defineProps<{
    submissions: SubmissionsPaginator;
}>();
</script>

<template>
    <Head title="Contact Form Submissions" />

    <div class="flex h-full flex-1 flex-col gap-6 rounded-xl p-4">
        <Heading
            title="Contact Form Submissions"
            description="View messages submitted from the contact form."
        />

        <Dialog v-model:open="isViewDialogOpen">
            <DialogContent class="sm:max-w-lg">
                <DialogHeader>
                    <DialogTitle>Contact Submission Details</DialogTitle>
                    <DialogDescription>
                        Submitted on
                        {{
                            formatDateTime(
                                selectedSubmission?.created_at ?? null,
                            )
                        }}
                    </DialogDescription>
                </DialogHeader>

                <div v-if="selectedSubmission" class="space-y-3 text-sm">
                    <div class="grid grid-cols-3 gap-2">
                        <span class="text-muted-foreground">Name</span>
                        <span class="col-span-2 font-medium">
                            {{ selectedSubmission.name }}
                        </span>
                    </div>
                    <div class="grid grid-cols-3 gap-2">
                        <span class="text-muted-foreground">Email</span>
                        <span class="col-span-2 font-medium">
                            {{ selectedSubmission.email }}
                        </span>
                    </div>
                    <div class="grid grid-cols-3 gap-2">
                        <span class="text-muted-foreground">Phone</span>
                        <span class="col-span-2 font-medium">
                            {{ selectedSubmission.phone }}
                        </span>
                    </div>
                    <div class="grid grid-cols-3 gap-2">
                        <span class="text-muted-foreground">Message</span>
                        <span class="col-span-2 font-medium">
                            {{ selectedSubmission.message }}
                        </span>
                    </div>
                    <div class="grid grid-cols-3 gap-2">
                        <span class="text-muted-foreground">IP Address</span>
                        <span class="col-span-2 font-medium">
                            {{ selectedSubmission.ip_address ?? '-' }}
                        </span>
                    </div>
                    <div class="grid grid-cols-3 gap-2">
                        <span class="text-muted-foreground">User Agent</span>
                        <span
                            class="col-span-2 max-h-32 overflow-auto font-medium"
                        >
                            {{ selectedSubmission.user_agent ?? '-' }}
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
                        >Delete contact submission</AlertDialogTitle
                    >
                    <AlertDialogDescription class="text-center">
                        <template v-if="submissionPendingDeletion">
                            Delete submission from
                            {{ submissionPendingDeletion.name }}? This action
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
                            :disabled="deletingSubmissionId !== null"
                            @click="cancelDeleteDialog"
                        >
                            Cancel
                        </Button>
                    </AlertDialogCancel>
                    <Button
                        type="button"
                        class="cursor-pointer bg-destructive text-white hover:bg-destructive/90"
                        :disabled="deletingSubmissionId !== null"
                        @click="deleteSubmission"
                    >
                        <Spinner
                            v-if="deletingSubmissionId !== null"
                            class="size-4"
                        />
                        Delete submission
                    </Button>
                </AlertDialogFooter>
            </AlertDialogContent>
        </AlertDialog>

        <Card>
            <CardHeader>
                <CardTitle>Submissions</CardTitle>
                <CardDescription>
                    Showing {{ submissions.data.length }} of
                    {{ submissions.total ?? submissions.data.length }}
                    submissions.
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
                        {{ submissions.total ?? submissions.data.length }}
                        {{
                            submissions.total === 1
                                ? 'submission'
                                : 'submissions'
                        }}
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
                                    Name
                                </th>
                                <th class="px-4 py-3 text-left font-medium">
                                    Email
                                </th>
                                <th class="px-4 py-3 text-left font-medium">
                                    Phone
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
                                v-for="(submission, index) in submissions.data"
                                :key="submission.id"
                                class="border-b last:border-b-0"
                            >
                                <td class="px-4 py-3">
                                    {{ (submissions.from ?? 1) + index }}
                                </td>
                                <td class="px-4 py-3">{{ submission.name }}</td>
                                <td class="px-4 py-3">
                                    {{ submission.email }}
                                </td>
                                <td class="px-4 py-3">
                                    {{ submission.phone }}
                                </td>
                                <td class="px-4 py-3">
                                    {{ formatDateTime(submission.created_at) }}
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
                                                        aria-label="View submission"
                                                        @click="
                                                            openViewDialog(
                                                                submission,
                                                            )
                                                        "
                                                    >
                                                        <Eye class="size-4" />
                                                    </Button>
                                                </TooltipTrigger>
                                                <TooltipContent>
                                                    <p>View submission</p>
                                                </TooltipContent>
                                            </Tooltip>
                                            <Tooltip>
                                                <TooltipTrigger as-child>
                                                    <Button
                                                        variant="outline"
                                                        size="icon"
                                                        class="size-8 cursor-pointer text-destructive hover:text-destructive"
                                                        type="button"
                                                        aria-label="Delete submission"
                                                        :disabled="
                                                            deletingSubmissionId ===
                                                            submission.id
                                                        "
                                                        @click="
                                                            openDeleteDialog(
                                                                submission,
                                                            )
                                                        "
                                                    >
                                                        <Trash2
                                                            class="size-4"
                                                        />
                                                    </Button>
                                                </TooltipTrigger>
                                                <TooltipContent>
                                                    <p>Delete submission</p>
                                                </TooltipContent>
                                            </Tooltip>
                                        </div>
                                    </TooltipProvider>
                                </td>
                            </tr>
                            <tr v-if="submissions.data.length === 0">
                                <td
                                    colspan="6"
                                    class="px-4 py-8 text-center text-muted-foreground"
                                >
                                    No contact submissions found.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="mt-6 flex justify-center">
                    <Pagination :paginator="submissions" />
                </div>
            </CardContent>
        </Card>
    </div>
</template>
