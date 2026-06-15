<script setup lang="ts">
import { Head, router, usePage } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import Heading from '@/components/Heading.vue';
import Pagination from '@/components/Pagination.vue';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { tour as adminFormSubmissionsTour } from '@/routes/admin/form-submissions';

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
                        placeholder="Search enquiries by tour, name, email, phone..."
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
                                    Message
                                </th>
                                <th class="px-4 py-3 text-left font-medium">
                                    Submitted At
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
                                <td class="max-w-xs truncate px-4 py-3">
                                    {{ enquiry.message ?? '-' }}
                                </td>
                                <td class="px-4 py-3">
                                    {{ formatDateTime(enquiry.created_at) }}
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
