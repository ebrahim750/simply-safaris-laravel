<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { CalendarDays, MapPin, Tag } from 'lucide-vue-next';
import { computed, onMounted, ref } from 'vue';
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

const showCountriesModal = ref(false);
const showCategoriesModal = ref(false);

const isMounted = ref(false);

onMounted(() => {
    isMounted.value = true;
});

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

const countryDisplay = computed(() => {
    if (uniqueCountries.value.length === 0) {
        return '-';
    }

    return uniqueCountries.value[0].name;
});

const countryMoreCount = computed(() => {
    return Math.max(0, uniqueCountries.value.length - 1);
});

const uniqueCategoryNames = computed(() =>
    props.tour.categories.map((c) => c.name),
);

const categoryFirst = computed(() =>
    uniqueCategoryNames.value.length > 0
        ? uniqueCategoryNames.value[0]
        : null,
);

const categoryMoreCount = computed(() =>
    Math.max(0, uniqueCategoryNames.value.length - 1),
);

function durationLabel(days: number | null): string {
    if (!days) {
        return '-';
    }

    return `${days} ${days === 1 ? 'day' : 'days'}`;
}
</script>

<template>
    <div class="relative rounded-xl bg-white shadow-sm transition-shadow hover:shadow-md" :class="{ 'h-full': fullHeight }">
        <div v-if="isLoading" class="absolute inset-0 z-10 animate-pulse bg-white/60"></div>
        <Link :href="tourShow({ tour: tour.id })" class="block">
            <div
                class="image-housing overflow-hidden rounded-lg"
                :class="{ 'image-housing-compact': imageSize === 'compact' }"
            >
                <img v-if="tour.main_image" :src="tour.main_image" :alt="tour.name" />
                <img v-else src="/images/defaults/600x400.png" :alt="tour.name" />
            </div>
        </Link>
        <div class="p-5">
            <div class="mb-3 flex flex-wrap items-center gap-10">
                <span class="map inline-flex items-center gap-1.5">
                    <MapPin class="icon-inline" />
                    {{ countryDisplay }}<span v-if="countryMoreCount > 0" class="more-link" @click.prevent.stop="showCountriesModal = true"> +{{ countryMoreCount }} more</span>
                </span>
            </div>
            <h3 class="title-tour-list mb-3 text-lg font-semibold text-[#081E2A]">
                <Link :href="tourShow({ tour: tour.id })" class="block truncate hover:text-[#4DA528]">
                    {{ tour.name }}
                </Link>
            </h3>
            <div class="mb-3 flex items-center gap-1.5">
                <CalendarDays class="icon-inline" />
                <span class="text-sm text-gray-600">{{ durationLabel(tour.duration_days) }}</span>
                <span v-if="categoryFirst" class="category-meta">
                    <Tag class="icon-inline" />
                    {{ categoryFirst }}<span v-if="categoryMoreCount > 0" class="more-link" @click.prevent.stop="showCategoriesModal = true"> +{{ categoryMoreCount }} more</span>
                </span>
            </div>
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-1">
                    <span class="text-sm text-gray-500">From</span>
                    <span class="text-lg font-bold text-[#4DA528]">{{ tour.price }}</span>
                </div>
            </div>
        </div>

        <template v-if="isMounted">
            <Teleport to="body">
                <div v-if="showCountriesModal" class="modal-overlay" @click.self="showCountriesModal = false">
                    <div class="modal-content">
                        <button class="modal-close" @click="showCountriesModal = false">&times;</button>
                        <h3>Countries</h3>
                        <p class="modal-subtitle">{{ tour.name }}</p>
                        <ul class="modal-list">
                            <li v-for="entry in uniqueCountries" :key="entry.name" class="flex items-center gap-3">
                                <img v-if="entry.code" :src="`https://flagcdn.com/w40/${entry.code.toLowerCase()}.png`" :alt="entry.name" class="h-5 w-auto rounded-sm" />
                                {{ entry.name }}
                            </li>
                        </ul>
                    </div>
                </div>

                <div v-if="showCategoriesModal" class="modal-overlay" @click.self="showCategoriesModal = false">
                    <div class="modal-content">
                        <button class="modal-close" @click="showCategoriesModal = false">&times;</button>
                        <h3>Tour Categories</h3>
                        <p class="modal-subtitle">{{ tour.name }}</p>
                        <ul class="modal-list">
                            <li v-for="category in uniqueCategoryNames" :key="category">
                                {{ category }}
                            </li>
                        </ul>
                    </div>
                </div>
            </Teleport>
        </template>
    </div>
</template>

<style scoped>
.image-housing {
    height: 380px;
    width: 100%;
}

.image-housing-compact {
    height: 300px;
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

.category-meta {
    margin-left: auto;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 13px;
    font-weight: 500;
    color: #83827F;
    font-family: "Plus Jakarta Sans", sans-serif;
    white-space: nowrap;
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
