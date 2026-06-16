<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { CalendarDays, Compass, MapPin } from 'lucide-vue-next';
import tourRoutes from '@/routes/tours';

interface TourCardPlace {
    id: number;
    name: string;
    country?: string | null;
}

interface TourCardTour {
    id: number;
    name: string;
    main_image: string | null;
    price: string;
    duration_days: number | null;
    places: TourCardPlace[];
}

withDefaults(
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

function countrySummary(places: TourCardPlace[]): string {
    if (places.length === 0) {
        return '-';
    }

    return [...new Set(places.map((place) => place.country).filter(Boolean))].join(', ');
}

function placeSummary(places: TourCardPlace[]): string {
    if (places.length > 3) {
        return `${places
            .slice(0, 3)
            .map((place) => place.name)
            .join(', ')}...`;
    }

    return places.map((place) => place.name).join(', ');
}

function durationLabel(days: number | null): string {
    if (!days) {
        return '-';
    }

    return `${days} ${days === 1 ? 'day' : 'days'}`;
}
</script>

<template>
    <div
        class="tour-listing box-sd relative"
        :class="{ 'h-full': fullHeight }"
    >
        <div
            v-if="isLoading"
            class="absolute inset-0 z-10 animate-pulse bg-white/60"
        ></div>
        <Link
            :href="tourRoutes.show({ tour: tour.id })"
            class="tour-listing-image"
        >
            <div
                class="image-housing"
                :class="{ 'image-housing-compact': imageSize === 'compact' }"
            >
                <img
                    v-if="tour.main_image"
                    :src="tour.main_image"
                    :alt="tour.name"
                />
                <img
                    v-else
                    src="/images/defaults/600x400.png"
                    :alt="tour.name"
                />
            </div>
        </Link>
        <div class="tour-listing-content">
            <div class="flex-three location-meta flex-wrap">
                <span class="map">
                    <MapPin class="icon-inline" />
                    {{ countrySummary(tour.places) }}
                </span>
                <span v-if="tour.places.length > 0" class="map">
                    <Compass class="icon-inline" />
                    {{ placeSummary(tour.places) }}
                </span>
            </div>
            <h3 class="title-tour-list">
                <Link :href="tourRoutes.show({ tour: tour.id })">
                    {{ tour.name }}
                </Link>
            </h3>
            <div class="icon-box flex-three">
                <div class="icons flex-three">
                    <CalendarDays class="icon-inline" />
                    <span>{{ durationLabel(tour.duration_days) }}</span>
                </div>
            </div>
            <div class="flex-two">
                <div class="price-box flex-three">
                    <p>
                        From <span class="price-sale">{{ tour.price }}</span>
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.image-housing {
    height: 300px;
    width: 100%;
    overflow: hidden;
    border-radius: 8px;
}

.image-housing-compact {
    height: 220px;
}

.image-housing img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}

.map {
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.icon-inline {
    width: 18px;
    height: 18px;
    color: #4da528;
    flex-shrink: 0;
}

.location-meta {
    gap: 40px;
}

.title-tour-list {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.title-tour-list a {
    display: block;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}
</style>
