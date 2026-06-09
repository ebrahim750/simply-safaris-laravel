<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { ChevronLeft, ChevronRight } from 'lucide-vue-next';
import { computed } from 'vue';

interface PaginatorLink {
    url: string | null;
    label: string;
    active: boolean;
}

interface Paginator {
    current_page: number;
    last_page: number;
    prev_page_url: string | null;
    next_page_url: string | null;
    links: PaginatorLink[];
}

const props = defineProps<{
    paginator: Paginator;
}>();

const pageLinks = computed(() =>
    props.paginator.links.filter(
        (link) =>
            !link.label.toLowerCase().includes('previous') &&
            !link.label.toLowerCase().includes('next')
    )
);
</script>

<template>
    <nav v-if="paginator.last_page > 1" aria-label="Pagination" class="flex items-center gap-4">
        <Link
            :href="paginator.prev_page_url ?? '#'"
            :preserve-state="true"
            :preserve-scroll="true"
            class="flex h-12 w-12 items-center justify-center rounded-full border border-gray-200 bg-white text-gray-700 transition-colors hover:border-[#4DA528] hover:text-[#4DA528] disabled:cursor-not-allowed disabled:opacity-40"
            :class="{ 'pointer-events-none opacity-40': !paginator.prev_page_url }"
        >
            <ChevronLeft class="h-5 w-5" />
        </Link>

        <Link
            v-for="link in pageLinks"
            :key="link.label"
            :href="link.url ?? '#'"
            :preserve-state="true"
            :preserve-scroll="true"
            class="flex h-12 w-12 items-center justify-center rounded-full text-sm font-medium transition-colors"
            :class="
                link.active
                    ? 'bg-[#4DA528] text-white'
                    : 'border border-gray-200 bg-white text-gray-700 hover:border-[#4DA528] hover:text-[#4DA528]'
            "
        >
            <span v-html="link.label" />
        </Link>

        <Link
            :href="paginator.next_page_url ?? '#'"
            :preserve-state="true"
            :preserve-scroll="true"
            class="flex h-12 w-12 items-center justify-center rounded-full border border-gray-200 bg-white text-gray-700 transition-colors hover:border-[#4DA528] hover:text-[#4DA528]"
            :class="{ 'pointer-events-none opacity-40': !paginator.next_page_url }"
        >
            <ChevronRight class="h-5 w-5" />
        </Link>
    </nav>
</template>
