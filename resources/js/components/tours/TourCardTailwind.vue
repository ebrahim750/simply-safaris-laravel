<script setup lang="ts">
import { computed, onMounted, ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import { MapPin } from 'lucide-vue-next';
import { show as tourShow } from '@/routes/tours';

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
        return 'Worldwide';
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
</script>

<template>
    <div class="group overflow-hidden rounded-2xl bg-white shadow-sm transition-shadow hover:shadow-lg">
        <!-- Skeleton Loading -->
        <template v-if="isLoading">
            <div class="animate-pulse">
                <div class="h-52 w-full bg-gray-200"></div>
                <div class="p-4">
                    <div class="mb-2 flex items-center gap-1">
                        <div class="h-3 w-3 rounded-full bg-gray-200"></div>
                        <div class="h-3 w-20 rounded bg-gray-200"></div>
                    </div>
                    <div class="mb-2 h-5 w-3/4 rounded bg-gray-200"></div>
                    <div class="mb-3 flex items-center justify-between">
                        <div class="h-3 w-16 rounded bg-gray-200"></div>
                        <div class="h-3 w-20 rounded bg-gray-200"></div>
                    </div>
                    <div class="border-t border-gray-100 pt-3">
                        <div class="flex items-center gap-2">
                            <div class="h-3 w-10 rounded bg-gray-200"></div>
                            <div class="h-4 w-16 rounded bg-gray-200"></div>
                        </div>
                    </div>
                </div>
            </div>
        </template>

        <!-- Real Content -->
        <template v-else>
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
            <div class="mb-2 flex items-center gap-1 text-xs text-gray-600">
                <MapPin class="size-3.5 shrink-0 text-[#4DA528]" />
                <span>{{ countryDisplay }}</span>
                <span
                    v-if="countryMoreCount > 0"
                    class="cursor-pointer font-medium text-[#4DA528] hover:underline"
                    @click.prevent.stop="showCountriesModal = true"
                >+ {{ countryMoreCount }} more</span>
            </div>
            <h3 class="mb-2 truncate font-bold leading-snug text-gray-900 transition-colors hover:text-[#4DA528]">
                <Link :href="tourShow({ tour: tour.id })">{{ tour.name }}</Link>
            </h3>
            <div class="mb-3 flex items-center justify-between text-xs text-gray-500">
                <span class="flex items-center gap-1">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2" /><line x1="16" y1="2" x2="16" y2="6" /><line x1="8" y1="2" x2="8" y2="6" /><line x1="3" y1="10" x2="21" y2="10" /></svg>
                    {{ tour.duration_days ?? '-' }} {{ tour.duration_days === 1 ? 'day' : 'days' }}
                </span>
                <span v-if="categoryFirst" class="flex items-center gap-1">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2H2v10l9.29 9.29c.94.94 2.48.94 3.42 0l6.58-6.58c.94-.94.94-2.48 0-3.42L12 2Z" /><path d="M7 7h.01" /></svg>
                    {{ categoryFirst }}
                    <span
                        v-if="categoryMoreCount > 0"
                        class="cursor-pointer font-medium text-[#4DA528] hover:underline"
                        @click.prevent.stop="showCategoriesModal = true"
                    >+ {{ categoryMoreCount }} more</span>
                </span>
            </div>
            <div class="flex items-center justify-between border-t border-gray-100 pt-3">
                <div class="flex items-center gap-2">
                    <span class="text-xs text-gray-400">From</span>
                    <span class="text-sm font-bold text-[#4DA528]">{{ tour.price }}</span>
                </div>
            </div>
        </div>

        <template v-if="isMounted">
            <Teleport to="body">
                <div
                    v-if="showCountriesModal"
                    class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/60 p-5"
                    @click.self="showCountriesModal = false"
                >
                    <div class="relative w-full max-w-[400px] rounded-2xl bg-white p-[30px] shadow-2xl">
                        <button class="absolute top-4 right-4 cursor-pointer border-none bg-transparent text-[28px] leading-none text-[#666] transition-colors hover:text-[#333]" @click="showCountriesModal = false">&times;</button>
                        <h3 class="mb-1 text-[22px] font-semibold text-[#081e2a]">Countries</h3>
                        <p class="mb-4 text-sm text-[#888]">{{ tour.name }}</p>
                        <ul class="m-0 list-none p-0">
                            <li v-for="entry in uniqueCountries" :key="entry.name" class="flex items-center gap-2 border-b border-[#eee] px-0 py-[10px] text-[15px] leading-relaxed text-[#666] last:border-b-0">
                                <img v-if="entry.code" :src="`https://flagcdn.com/w40/${entry.code.toLowerCase()}.png`" :alt="entry.name" class="h-5 w-auto rounded-sm" />
                                {{ entry.name }}
                            </li>
                        </ul>
                    </div>
                </div>
                <div
                    v-if="showCategoriesModal"
                    class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/60 p-5"
                    @click.self="showCategoriesModal = false"
                >
                    <div class="relative w-full max-w-[400px] rounded-2xl bg-white p-[30px] shadow-2xl">
                        <button class="absolute top-4 right-4 cursor-pointer border-none bg-transparent text-[28px] leading-none text-[#666] transition-colors hover:text-[#333]" @click="showCategoriesModal = false">&times;</button>
                        <h3 class="mb-1 text-[22px] font-semibold text-[#081e2a]">Categories</h3>
                        <p class="mb-4 text-sm text-[#888]">{{ tour.name }}</p>
                        <ul class="m-0 list-none p-0">
                            <li v-for="category in uniqueCategoryNames" :key="category" class="border-b border-[#eee] px-0 py-[10px] text-[15px] leading-relaxed text-[#666] last:border-b-0">
                                {{ category }}
                            </li>
                        </ul>
                    </div>
                </div>
            </Teleport>
        </template>
        </template>
    </div>
</template>
