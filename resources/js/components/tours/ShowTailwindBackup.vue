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
            <div class="absolute top-3 left-3 right-3 flex items-start justify-between">
                <span class="rounded-full bg-[#4DA528] px-3 py-1 text-xs font-medium text-white">Featured</span>
                <div class="flex gap-2">
                    <span class="flex h-7 w-7 items-center justify-center rounded-full bg-white/90 text-xs text-gray-600">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor"><path d="M20.8 7.8c-.7-2.4-2.6-4.3-5-5C14.8 2.5 13.5 2 12 2S9.2 2.5 8.2 2.8c-2.4.7-4.3 2.6-5 5C2.8 9 2.3 10.5 2.3 12s.5 3 1 4.2c.7 2.4 2.6 4.3 5 5 1 .3 2.3.8 3.8.8s2.8-.5 3.8-.8c2.4-.7 4.3-2.6 5-5 .5-1.2 1-2.7 1-4.2s-.6-3-1.1-4.2z"/></svg>
                        5
                    </span>
                    <span class="flex h-7 w-7 items-center justify-center rounded-full bg-white/90 text-xs text-gray-600">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                        2
                    </span>
                </div>
            </div>
        </Link>
        <div class="p-4">
            <span class="mb-2 inline-block rounded bg-[#f0f9eb] px-2 py-0.5 text-xs font-medium text-[#4DA528]">Bestseller</span>
            <div class="mb-2 flex items-center gap-1 text-xs text-gray-400">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z" /><circle cx="12" cy="10" r="3" /></svg>
                {{ countryDisplay || 'Worldwide' }}
            </div>
            <h3 class="mb-2 font-bold leading-snug text-gray-900 transition-colors hover:text-[#4DA528]">
                <Link :href="tourShow({ tour: tour.id })">{{ tour.name }}</Link>
            </h3>
            <div class="mb-3 flex items-center gap-1 text-xs text-yellow-400">
                ★★★★★ <span class="text-gray-400">(1 Review)</span>
            </div>
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
                <button class="flex h-8 w-8 items-center justify-center rounded-full bg-gray-100 text-gray-400 transition-colors hover:bg-[#4DA528] hover:text-white">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m19 21-7-4-7 4V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16z" /></svg>
                </button>
            </div>
        </div>
    </div>
</template>
