<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { show as tourShow } from '@/routes/tours2';

interface TourCardPlace {
    id: number;
    name: string;
    country?: string | null;
    country_code?: string | null;
}

interface TourCardTour {
    id: number;
    name: string;
    main_image: string | null;
    price: string;
    duration_days: number | null;
    categories: Array<{ id: number; name: string }>;
    places: TourCardPlace[];
}

const props = withDefaults(
    defineProps<{
        tour: TourCardTour;
        isLoading?: boolean;
        fullHeight?: boolean;
        imageSize?: 'default' | 'compact';
    }>(),
    {
        isLoading: false,
        fullHeight: false,
        imageSize: 'default',
    },
);

const countryDisplay = props.tour.places.length > 0
    ? (props.tour.places[0].country ?? '')
    : '';
</script>

<template>
    <div class="group overflow-hidden rounded-2xl bg-white shadow-sm transition-shadow hover:shadow-lg">
        <div v-if="isLoading" class="absolute inset-0 z-10 animate-pulse bg-white/60"></div>
        <Link :href="tourShow({ tour: tour.id })" class="relative block overflow-hidden">
            <img
                v-if="tour.main_image"
                :src="tour.main_image"
                :alt="tour.name"
                class="h-52 w-full object-cover transition-transform duration-500 group-hover:scale-105"
            />
            <img
                v-else
                src="/images/defaults/600x400.png"
                :alt="tour.name"
                class="h-52 w-full object-cover transition-transform duration-500 group-hover:scale-105"
            />
        </Link>
        <div class="p-4">
            <div class="mb-2 flex items-center gap-1 text-xs text-gray-400">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z" /><circle cx="12" cy="10" r="3" /></svg>
                {{ countryDisplay || 'Worldwide' }}
            </div>
            <h3 class="mb-2 truncate font-bold leading-snug text-gray-900 transition-colors hover:text-[#4DA528]">
                <Link :href="tourShow({ tour: tour.id })">{{ tour.name }}</Link>
            </h3>
            <div class="mb-3 flex items-center justify-between text-xs text-gray-500">
                <span class="flex items-center gap-1">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10" /><polyline points="12 6 12 12 16 14" /></svg>
                    {{ tour.duration_days ?? '-' }} {{ tour.duration_days === 1 ? 'day' : 'days' }}
                </span>
                <span class="flex items-center gap-1">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" /><circle cx="9" cy="7" r="4" /><path d="M23 21v-2a4 4 0 0 0-3-3.87" /><path d="M16 3.13a4 4 0 0 1 0 7.75" /></svg>
                    12 Person
                </span>
            </div>
            <div class="flex items-center justify-between border-t border-gray-100 pt-3">
                <div>
                    <span class="text-xs text-gray-400">From</span>
                    <span class="text-sm font-bold text-[#4DA528]">{{ tour.price }}</span>
                </div>
            </div>
        </div>
    </div>
</template>
