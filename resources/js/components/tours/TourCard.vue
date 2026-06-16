<script setup lang="ts">
import { computed, ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import { CalendarDays, Compass, MapPin } from 'lucide-vue-next';
import tourRoutes from '@/routes/tours';

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

const showCountriesModal = ref(false);
const showPlacesModal = ref(false);

const uniqueCountries = computed(() => {
    const seen = new Map<string, string | null>();

    for (const place of props.tour.places) {
        const name = place.country;

        if (name && !seen.has(name)) {
            seen.set(name, place.country_code ?? null);
        }
    }

    return [...seen].map(([name, code]) => ({ name, code }));
});

const uniquePlaces = computed(() => {
    return [...new Set(props.tour.places.map((place) => place.name))];
});

const countryDisplay = computed(() => {
    if (uniqueCountries.value.length === 0) {
        return '-';
    }

    return uniqueCountries.value[0].name;
});

const countryMoreCount = computed(() => {
    return Math.max(0, uniqueCountries.value.length - 1);
});

const placeDisplay = computed(() => {
    if (uniquePlaces.value.length === 0) {
        return '-';
    }

    return uniquePlaces.value[0];
});

const placeMoreCount = computed(() => {
    return Math.max(0, uniquePlaces.value.length - 1);
});

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
                    {{ countryDisplay }}<span
                        v-if="countryMoreCount > 0"
                        class="more-link"
                        @click.prevent.stop="showCountriesModal = true"
                    >
                        +{{ countryMoreCount }} more</span
                    >
                </span>
                <span v-if="uniquePlaces.length > 0" class="map">
                    <Compass class="icon-inline" />
                    {{ placeDisplay }}<span
                        v-if="placeMoreCount > 0"
                        class="more-link"
                        @click.prevent.stop="showPlacesModal = true"
                    >
                        +{{ placeMoreCount }} more</span
                    >
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

        <Teleport to="body">
            <div v-if="showCountriesModal" class="modal-overlay" @click.self="showCountriesModal = false">
                <div class="modal-content">
                    <button class="modal-close" @click="showCountriesModal = false">&times;</button>
                    <h3>Countries</h3>
                    <p class="modal-subtitle">{{ tour.name }}</p>
                    <ul class="modal-list">
                        <li v-for="entry in uniqueCountries" :key="entry.name" class="flex items-center gap-3">
                            <img
                                v-if="entry.code"
                                :src="`https://flagcdn.com/w40/${entry.code.toLowerCase()}.png`"
                                :alt="entry.name"
                                class="h-5 w-auto rounded-sm"
                            />
                            {{ entry.name }}
                        </li>
                    </ul>
                </div>
            </div>

            <div v-if="showPlacesModal" class="modal-overlay" @click.self="showPlacesModal = false">
                <div class="modal-content">
                    <button class="modal-close" @click="showPlacesModal = false">&times;</button>
                    <h3>Places</h3>
                    <p class="modal-subtitle">{{ tour.name }}</p>
                    <ul class="modal-list">
                        <li v-for="place in uniquePlaces" :key="place">
                            {{ place }}
                        </li>
                    </ul>
                </div>
            </div>
        </Teleport>
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

.more-link {
    color: #4da528;
    cursor: pointer;
}

.more-link:hover {
    text-decoration: underline;
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

.modal-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.6);
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
}

.modal-content {
    background: #fff;
    border-radius: 16px;
    max-width: 400px;
    width: 100%;
    max-height: 80vh;
    overflow-y: auto;
    padding: 30px;
    position: relative;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
}

.modal-content h3 {
    font-size: 22px;
    font-weight: 600;
    margin-bottom: 4px;
    color: #081e2a;
}

.modal-subtitle {
    font-size: 14px;
    color: #888;
    margin: 0 0 16px;
}

.modal-close {
    position: absolute;
    top: 16px;
    right: 16px;
    padding: 0;
    border: none;
    background: transparent;
    font-size: 28px;
    line-height: 1;
    color: #666;
    cursor: pointer;
}

.modal-close:hover {
    color: #333;
}

.modal-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.modal-list li {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 10px 0;
    color: #666;
    line-height: 1.6;
    font-size: 15px;
    border-bottom: 1px solid #eee;
}

.modal-list li:last-child {
    border-bottom: none;
}
</style>
