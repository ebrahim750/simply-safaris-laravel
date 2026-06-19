<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { debounce } from 'lodash-es';
import { Bike, MapPin, Tag } from 'lucide-vue-next';
import { SliderRange, SliderRoot, SliderThumb, SliderTrack } from 'reka-ui';
import { computed, ref, watch } from 'vue';
import Pagination from '@/components/Pagination.vue';
import TourCardTailwind from '@/components/tours/TourCardTailwind.vue';
import { home } from '@/routes';
import tourRoutes from '@/routes/tours';
import type { QueryParams } from '@/wayfinder';

interface Tour {
    id: number;
    name: string;
    main_image: string | null;
    price: string;
    price_amount: number;
    duration_days: number | null;
    categories: Array<{ id: number; name: string }>;
    places: Array<{ id: number; name: string; country?: string; country_code?: string | null }>;
}

interface PaginatorLink {
    url: string | null;
    label: string;
    active: boolean;
}

interface ToursPaginator {
    current_page: number;
    last_page: number;
    per_page: number;
    prev_page_url: string | null;
    next_page_url: string | null;
    data: Tour[];
    links: PaginatorLink[];
}

const props = defineProps<{
    tours: ToursPaginator;
    countries: Array<{ id: number; name: string; country_code: string | null; places: Array<{ id: number; name: string }> }>;
    activities: Array<{ id: number; name: string }>;
    categories: Array<{ id: number; name: string }>;
    filters: {
        search: string | null;
        categories: number[];
        activities: number[];
        places: number[];
        countries: number[];
        price: { min: number; max: number };
        duration: { min: number; max: number };
    };
}>();

const isDestinationOpen = ref(false);
const isActivitiesOpen = ref(false);
const isCategoriesOpen = ref(false);
const isLoading = ref(false);

const selectedCountries = ref<number[]>(props.filters.countries);
const selectedPlaces = ref<number[]>(props.filters.places);
const selectedCategories = ref<number[]>(props.filters.categories);
const selectedActivities = ref<number[]>(props.filters.activities);
const priceRange = ref([props.filters.price.min, props.filters.price.max]);
const durationRange = ref([props.filters.duration.min, props.filters.duration.max]);

const destinationCount = computed(() => selectedCountries.value.length + selectedPlaces.value.length);

const hasFilterFilters = computed(() =>
    selectedCountries.value.length > 0 ||
    selectedPlaces.value.length > 0 ||
    selectedCategories.value.length > 0 ||
    selectedActivities.value.length > 0 ||
    priceRange.value[0] !== 100 ||
    priceRange.value[1] !== 5000 ||
    durationRange.value[0] !== 1 ||
    durationRange.value[1] !== 30
);

const hasActiveFilters = computed(() =>
    hasFilterFilters.value || props.filters.search !== null
);

function applyFilters(): void {
    const params: QueryParams = {};

    if (selectedCategories.value.length > 0) {
 params.categories = selectedCategories.value; 
}

    if (selectedActivities.value.length > 0) {
 params.activities = selectedActivities.value; 
}

    if (selectedPlaces.value.length > 0) {
 params.places = selectedPlaces.value; 
}

    if (selectedCountries.value.length > 0) {
 params.countries = selectedCountries.value; 
}

    if (priceRange.value[0] !== 100) {
 params.price_min = priceRange.value[0]; 
}

    if (priceRange.value[1] !== 5000) {
 params.price_max = priceRange.value[1]; 
}

    if (durationRange.value[0] !== 1) {
 params.duration_min = durationRange.value[0]; 
}

    if (durationRange.value[1] !== 30) {
 params.duration_max = durationRange.value[1]; 
}

    if (props.filters.search) {
 params.search = props.filters.search; 
}

    isLoading.value = true;
    router.get(tourRoutes.index({ query: params }).url, {}, { preserveState: true, preserveScroll: true, onFinish: () => {
 isLoading.value = false; 
} });
}

const debouncedApplyFilters = debounce(applyFilters, 300);

watch(priceRange, () => debouncedApplyFilters(), { deep: true, flush: 'sync' });
watch(durationRange, () => debouncedApplyFilters(), { deep: true, flush: 'sync' });

function clearFilters(): void {
    selectedCountries.value = [];
    selectedPlaces.value = [];
    selectedCategories.value = [];
    selectedActivities.value = [];
    priceRange.value = [100, 5000];
    durationRange.value = [1, 30];
    debouncedApplyFilters.cancel();
    router.get('/tours');
}
</script>

<template>
    <Head title="Tours" />

    <!-- Breadcrumb -->
    <section class="relative flex items-center bg-cover bg-center bg-no-repeat py-[137px]" style="background-image: url('/template/images/page/breakcrumb.jpg')">
        <div class="absolute inset-0" style="background: linear-gradient(262deg, rgba(0,0,0,0.33), rgba(4,29,11,0.89))"></div>
        <div class="relative z-[1] mx-auto w-full max-w-[1366px] px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="mb-[26px] text-[65px] font-semibold leading-[82px] text-white max-lg:text-[45px] max-lg:leading-[50px]">Tours</h1>
                <nav class="flex items-center justify-center text-[18px] leading-[22.68px]">
                    <Link :href="home()" class="relative mr-[30px] text-[#4DA528] after:absolute after:right-[-18px] after:top-1/2 after:-translate-y-1/2 after:text-[10px] after:text-white after:content-['\203A']">Home</Link>
                    <span class="text-white">Tours</span>
                </nav>
                <img class="absolute left-[7%] top-[40%] hidden w-auto lg:block" src="/template/images/page/mask-bcrumb.png" alt="" />
            </div>
        </div>
    </section>

    <!-- Tours + Filter -->
    <section class="pt-8 pb-[105px]">
        <div class="mx-auto max-w-[1366px] px-4 sm:px-6 lg:px-8">
            <p v-if="props.filters.search" class="mb-3 text-center text-[25px] text-gray-600">Search results for: "{{ props.filters.search }}"</p>
            <div v-if="props.filters.search" class="mb-12 flex justify-center">
                <button
                    type="button"
                    class="cursor-pointer rounded-md px-3 py-1 text-[13px] font-medium text-gray-500 transition-colors hover:bg-gray-100"
                    @click="clearFilters"
                >Clear search term</button>
            </div>
            <div class="flex flex-wrap lg:gap-[30px]">
                <!-- Sidebar Filters -->
                <div class="w-full lg:w-[340px] lg:shrink-0 xl:w-[380px]">
                    <form class="rounded-[10px] border border-[#EDEEEE] px-10 py-9" @submit.prevent>
                        <div class="mb-10">
                            <div class="mb-[30px] flex items-center justify-between gap-2">
                                <h6 class="title-tour-tailwind pl-[10px] text-[20px] font-semibold text-[#081e2a]">Filter Tours</h6>
                                <button type="button" class="rounded-md px-2 py-0.5 text-[13px] font-medium text-red-500 transition-colors hover:bg-red-50" :class="{ 'opacity-0 pointer-events-none': !hasFilterFilters }" @click="clearFilters">Clear filters</button>
                            </div>

                            <!-- Destinations -->
                            <div class="mb-[22px] overflow-hidden rounded-lg border border-gray-200 bg-white">
                                <button type="button" class="relative flex w-full items-center py-3 pl-[53px] pr-10 text-left" @click="isDestinationOpen = !isDestinationOpen">
                                    <MapPin class="absolute left-5 top-1/2 size-5 -translate-y-1/2 text-[#4DA528]" />
                                    <div class="flex-1">
                                        <span class="block text-[14px] font-medium text-[#4f545a]">Destination</span>
                                        <span class="block text-[16px] font-semibold text-[#081e2a]">Location</span>
                                    </div>
                                    <span v-if="destinationCount > 0" class="mr-1 inline-flex items-center justify-center rounded-full bg-[#4DA528] px-1.5 py-0.5 text-[12px] font-semibold text-white">selected: {{ destinationCount }}</span>
                                    <span class="absolute right-4 top-1/2 -mt-1 h-2 w-2 border-r-[1.7px] border-b-[1.7px] border-[#3a3a3c] transition-transform duration-150" :class="isDestinationOpen ? '-rotate-[135deg]' : 'rotate-45'" />
                                </button>
                                <div v-show="isDestinationOpen" class="max-h-[280px] overflow-y-auto border-t border-gray-100 px-4 py-3">
                                    <div v-for="country in countries" :key="country.id">
                                        <div class="py-1.5">
                                            <label :for="'country-' + country.id" class="inline-flex items-center gap-2 text-[15px] font-semibold text-[#081e2a] cursor-pointer">
                                                <input :id="'country-' + country.id" v-model="selectedCountries" type="checkbox" :value="country.id" class="accent-[#4da528]" @change="applyFilters" />
                                                {{ country.name }}
                                                <img v-if="country.country_code" :src="`https://flagcdn.com/w40/${country.country_code.toLowerCase()}.png`" :alt="country.name" class="ml-auto h-4 w-6 rounded-sm border border-gray-200 object-cover" />
                                            </label>
                                        </div>
                                        <div class="pl-7 pb-2">
                                            <div v-for="place in country.places" :key="place.id" class="py-1">
                                                <label :for="'place-' + place.id" class="inline-flex items-center gap-2 text-[14px] text-[#4f545a] cursor-pointer">
                                                    <input :id="'place-' + place.id" v-model="selectedPlaces" type="checkbox" :value="place.id" class="accent-[#4da528]" @change="applyFilters" />
                                                    {{ place.name }}
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Categories -->
                            <div class="mb-[22px] overflow-hidden rounded-lg border border-gray-200 bg-white">
                                <button type="button" class="relative flex w-full items-center py-3 pl-[53px] pr-10 text-left" @click="isCategoriesOpen = !isCategoriesOpen">
                                    <Tag class="absolute left-5 top-1/2 size-5 -translate-y-1/2 text-[#4DA528]" />
                                    <div class="flex-1">
                                        <span class="block text-[14px] font-medium text-[#4f545a]">Categories</span>
                                        <span class="block text-[16px] font-semibold text-[#081e2a]">Tour Type</span>
                                    </div>
                                    <span v-if="selectedCategories.length > 0" class="mr-1 inline-flex items-center justify-center rounded-full bg-[#4DA528] px-1.5 py-0.5 text-[12px] font-semibold text-white">selected: {{ selectedCategories.length }}</span>
                                    <span class="absolute right-4 top-1/2 -mt-1 h-2 w-2 border-r-[1.7px] border-b-[1.7px] border-[#3a3a3c] transition-transform duration-150" :class="isCategoriesOpen ? '-rotate-[135deg]' : 'rotate-45'" />
                                </button>
                                <div v-show="isCategoriesOpen" class="max-h-[280px] overflow-y-auto border-t border-gray-100 px-4 py-3">
                                    <div v-for="category in categories" :key="category.id" class="py-1.5">
                                        <label :for="'category-' + category.id" class="inline-flex items-center gap-2 text-[14px] text-[#4f545a] cursor-pointer">
                                            <input :id="'category-' + category.id" v-model="selectedCategories" type="checkbox" :value="category.id" class="accent-[#4da528]" @change="applyFilters" />
                                            {{ category.name }}
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Activities -->
                            <div class="mb-[22px] overflow-hidden rounded-lg border border-gray-200 bg-white">
                                <button type="button" class="relative flex w-full items-center py-3 pl-[53px] pr-10 text-left" @click="isActivitiesOpen = !isActivitiesOpen">
                                    <Bike class="absolute left-5 top-1/2 size-5 -translate-y-1/2 text-[#4DA528]" />
                                    <div class="flex-1">
                                        <span class="block text-[14px] font-medium text-[#4f545a]">Activities</span>
                                        <span class="block text-[16px] font-semibold text-[#081e2a]">Activity Type</span>
                                    </div>
                                    <span v-if="selectedActivities.length > 0" class="mr-1 inline-flex items-center justify-center rounded-full bg-[#4DA528] px-1.5 py-0.5 text-[12px] font-semibold text-white">selected: {{ selectedActivities.length }}</span>
                                    <span class="absolute right-4 top-1/2 -mt-1 h-2 w-2 border-r-[1.7px] border-b-[1.7px] border-[#3a3a3c] transition-transform duration-150" :class="isActivitiesOpen ? '-rotate-[135deg]' : 'rotate-45'" />
                                </button>
                                <div v-show="isActivitiesOpen" class="max-h-[280px] overflow-y-auto border-t border-gray-100 px-4 py-3">
                                    <div v-for="activity in activities" :key="activity.id" class="py-1.5">
                                        <label :for="'activity-' + activity.id" class="inline-flex items-center gap-2 text-[14px] text-[#4f545a] cursor-pointer">
                                            <input :id="'activity-' + activity.id" v-model="selectedActivities" type="checkbox" :value="activity.id" class="accent-[#4da528]" @change="applyFilters" />
                                            {{ activity.name }}
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Price Filter -->
                            <fieldset class="relative mb-10 mt-10">
                                <h6 class="mb-3 text-[18px] font-semibold text-[#081e2a]">Filter By Price</h6>
                                <div>
                                    <div class="mb-2 flex items-center justify-between text-[12px] text-gray-400">
                                        <span>Min</span><span>Max</span>
                                    </div>
                                    <SliderRoot v-model="priceRange" class="range-slider" :min="100" :max="5000" :step="100" :min-steps-between-thumbs="1">
                                        <SliderTrack class="slider-track"><SliderRange class="slider-fill" /></SliderTrack>
                                        <SliderThumb v-for="(_, index) in priceRange" :key="index" class="slider-thumb" :aria-label="index === 0 ? 'Minimum price' : 'Maximum price'" />
                                    </SliderRoot>
                                    <div class="mt-2 flex items-center justify-between text-[12px] text-gray-400">
                                        <span>${{ priceRange[0].toLocaleString() }}</span><span>${{ priceRange[1].toLocaleString() }}</span>
                                    </div>
                                </div>
                            </fieldset>

                            <!-- Duration Filter -->
                            <fieldset class="relative mb-10">
                                <h6 class="mb-3 text-[18px] font-semibold text-[#081e2a]">Filter By Duration</h6>
                                <div>
                                    <div class="mb-2 flex items-center justify-between text-[12px] text-gray-400">
                                        <span>Min</span><span>Max</span>
                                    </div>
                                    <SliderRoot v-model="durationRange" class="range-slider" :min="1" :max="30" :step="1" :min-steps-between-thumbs="1">
                                        <SliderTrack class="slider-track"><SliderRange class="slider-fill" /></SliderTrack>
                                        <SliderThumb v-for="(_, index) in durationRange" :key="index" class="slider-thumb" :aria-label="index === 0 ? 'Minimum duration' : 'Maximum duration'" />
                                    </SliderRoot>
                                    <div class="mt-2 flex items-center justify-between text-[12px] text-gray-400">
                                        <span>{{ durationRange[0] }} {{ durationRange[0] === 1 ? 'day' : 'days' }}</span><span>{{ durationRange[1] }} {{ durationRange[1] === 1 ? 'day' : 'days' }}</span>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                    </form>
                </div>

                <!-- Tour Cards -->
                <div class="relative w-full lg:min-w-0 lg:flex-1 max-lg:mt-10">
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                        <TourCardTailwind v-for="tour in tours.data" :key="tour.id" :tour="tour" :is-loading="isLoading" />
                    </div>
                    <div v-if="tours.data.length > 0 && !isLoading" class="mt-16 flex justify-center">
                        <Pagination :paginator="tours" />
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<style scoped>
.range-slider {
    position: relative;
    display: flex;
    align-items: center;
    width: 100%;
    height: 18px;
    margin-bottom: 8px;
    margin-top: 6px;
    user-select: none;
    touch-action: none;
}

.slider-track {
    position: relative;
    flex: 1;
    height: 4px;
    background: #e0e0e0;
    border-radius: 2px;
}

.slider-fill {
    position: absolute;
    height: 100%;
    background: #4da528;
    border-radius: 2px;
}

.slider-thumb {
    display: block;
    width: 18px;
    height: 18px;
    background: #4da528;
    border-radius: 50%;
    border: 2px solid #fff;
    box-shadow: 0 1px 4px rgba(0, 0, 0, 0.2);
    cursor: pointer;
}

.slider-thumb:focus-visible {
    outline: 2px solid rgba(77, 165, 40, 0.35);
    outline-offset: 2px;
}

.title-tour-tailwind {
    position: relative;
}

.title-tour-tailwind::before {
    height: 17px;
    width: 1px;
    background-color: #4DA528;
    position: absolute;
    content: '';
    left: 0;
    top: 50%;
    transform: translateY(-50%);
}
</style>
