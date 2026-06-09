<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { MapPin, Bike, CalendarDays, Tag, Compass } from 'lucide-vue-next';
import { SliderRange, SliderRoot, SliderThumb, SliderTrack } from 'reka-ui';
import { computed, ref } from 'vue';
import { home } from '@/routes';
import tourRoutes from '@/routes/tours';
import Pagination from '@/components/Pagination.vue';

const priceRange = ref([100, 5000]);
const durationRange = ref([1, 30]);

interface Tour {
    id: number;
    name: string;
    main_image: string | null;
    price: string;
    price_amount: number;
    duration_days: number | null;
    categories: Array<{ id: number; name: string }>;
    places: Array<{ id: number; name: string; country?: string }>;
}

interface PaginatorLink {
    url: string | null;
    label: string;
    active: boolean;
}

interface ToursPaginator {
    current_page: number;
    last_page: number;
    prev_page_url: string | null;
    next_page_url: string | null;
    data: Tour[];
    links: PaginatorLink[];
}

defineProps<{
    tours: ToursPaginator;
    countries: Array<{
        id: number;
        name: string;
        country_code: string | null;
        places: Array<{ id: number; name: string }>;
    }>;
    activities: Array<{ id: number; name: string }>;
    categories: Array<{ id: number; name: string }>;
}>();

const isDestinationOpen = ref(false);
const isActivitiesOpen = ref(false);
const isCategoriesOpen = ref(false);

const selectedCountries = ref<number[]>([]);
const selectedPlaces = ref<number[]>([]);
const selectedCategories = ref<number[]>([]);
const selectedActivities = ref<number[]>([]);

const destinationCount = computed(() => selectedCountries.value.length + selectedPlaces.value.length);

const hasActiveFilters = computed(() =>
    selectedCountries.value.length > 0 ||
    selectedPlaces.value.length > 0 ||
    selectedCategories.value.length > 0 ||
    selectedActivities.value.length > 0 ||
    priceRange.value[0] !== 100 ||
    priceRange.value[1] !== 5000 ||
    durationRange.value[0] !== 1 ||
    durationRange.value[1] !== 30
);

function clearFilters(): void {
    selectedCountries.value = [];
    selectedPlaces.value = [];
    selectedCategories.value = [];
    selectedActivities.value = [];
    priceRange.value = [100, 5000];
    durationRange.value = [1, 30];
}
</script>

<template>
    <Head title="Tours" />

    <!-- Breadcrumb -->
    <section class="breadcumb-section">
        <div class="tf-container">
            <div class="row">
                <div class="col-lg-12 center z-index1">
                    <h1 class="title">Tours</h1>
                    <ul class="breadcumb-list flex-five">
                        <li><Link :href="home()">Home</Link></li>
                        <li><span>Tours</span></li>
                    </ul>
                    <img
                        class="bcrumb-ab"
                        src="/template/images/page/mask-bcrumb.png"
                        alt=""
                    />
                </div>
            </div>
        </div>
    </section>

    <!-- Widget archieve tour -->
    <section class="archieve-tour">
        <div class="tf-container">
            <div class="row">
                <div class="col-lg-3">
                    <form action="/" class="sider-bar-tour-package">
                        <div class="widget-filter mb-40">
                            <div class="flex items-center justify-between gap-2">
                                <h6 class="title-tour !mb-0 whitespace-nowrap">Filter Tours</h6>
                                <button
                                    type="button"
                                    class="shrink-0 whitespace-nowrap font-medium !text-red-500 !border-red-500 bg-transparent hover:!bg-red-50 rounded-md px-2 py-0.5 my-1 transition-colors btn-clear-filter"
                                    :class="{ 'opacity-0 pointer-events-none': !hasActiveFilters }"
                                    @click="clearFilters"
                                >
                                    Clear filter
                                </button>
                            </div>
                            <div class="group-select-wrap">
                                <div class="destination-accordion mb-22">
                                    <button
                                        type="button"
                                        class="accordion-trigger"
                                        @click="isDestinationOpen = !isDestinationOpen"
                                    >
                                        <MapPin class="filter-icon" />
                                        <div class="search-bar-group relative">
                                            <label>Destination</label>
                                            <span class="current">Location</span>
                                        </div>
                                        <span v-if="destinationCount > 0" class="filter-badge">selected: {{ destinationCount }}</span>
                                        <span
                                            class="trigger-chevron"
                                            :class="{ 'is-open': isDestinationOpen }"
                                        >&#9662;</span>
                                    </button>
                                    <div v-show="isDestinationOpen" class="accordion-panel">
                                        <div
                                            v-for="country in countries"
                                            :key="country.id"
                                            class="accordion-country"
                                        >
                                            <div class="country-header group-check-box-wrap">
                                                <div class="checkbox">
                                                    <input
                                                        :id="'country-' + country.id"
                                                        v-model="selectedCountries"
                                                        type="checkbox"
                                                        :value="country.id"
                                                    />
                                                    <label :for="'country-' + country.id">
                                                        <img
                                                            v-if="country.country_code"
                                                            :src="`https://flagcdn.com/w40/${country.country_code.toLowerCase()}.png`"
                                                            :alt="country.name"
                                                            class="country-flag"
                                                        />
                                                        <span>{{ country.name }}</span>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="country-places group-check-box-wrap">
                                                <div
                                                    v-for="place in country.places"
                                                    :key="place.id"
                                                    class="checkbox"
                                                >
                                                    <input
                                                        :id="'place-' + place.id"
                                                        v-model="selectedPlaces"
                                                        type="checkbox"
                                                        :value="place.id"
                                                    />
                                                    <label :for="'place-' + place.id">{{ place.name }}</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="destination-accordion mb-22">
                                    <button
                                        type="button"
                                        class="accordion-trigger"
                                        @click="isCategoriesOpen = !isCategoriesOpen"
                                    >
                                        <Tag class="filter-icon" />
                                        <div class="search-bar-group relative">
                                            <label>Categories</label>
                                            <span class="current">Tour Type</span>
                                        </div>
                                        <span v-if="selectedCategories.length > 0" class="filter-badge">selected: {{ selectedCategories.length }}</span>
                                        <span
                                            class="trigger-chevron"
                                            :class="{ 'is-open': isCategoriesOpen }"
                                        >&#9662;</span>
                                    </button>
                                    <div v-show="isCategoriesOpen" class="accordion-panel">
                                        <div
                                            v-for="category in categories"
                                            :key="category.id"
                                            class="category-item group-check-box-wrap"
                                        >
                                            <div class="checkbox">
                                                <input
                                                    :id="'category-' + category.id"
                                                    v-model="selectedCategories"
                                                    type="checkbox"
                                                    :value="category.id"
                                                />
                                                <label :for="'category-' + category.id">{{ category.name }}</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="destination-accordion mb-22">
                                    <button
                                        type="button"
                                        class="accordion-trigger"
                                        @click="isActivitiesOpen = !isActivitiesOpen"
                                    >
                                        <Bike class="filter-icon" />
                                        <div class="search-bar-group relative">
                                            <label>Activities</label>
                                            <span class="current">Booking Type</span>
                                        </div>
                                        <span v-if="selectedActivities.length > 0" class="filter-badge">selected: {{ selectedActivities.length }}</span>
                                        <span
                                            class="trigger-chevron"
                                            :class="{ 'is-open': isActivitiesOpen }"
                                        >&#9662;</span>
                                    </button>
                                    <div v-show="isActivitiesOpen" class="accordion-panel">
                                        <div
                                            v-for="activity in activities"
                                            :key="activity.id"
                                            class="activity-item group-check-box-wrap"
                                        >
                                            <div class="checkbox">
                                                <input
                                                    :id="'activity-' + activity.id"
                                                    v-model="selectedActivities"
                                                    type="checkbox"
                                                    :value="activity.id"
                                                />
                                                <label :for="'activity-' + activity.id">{{ activity.name }}</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <fieldset class="group-select relative mb-40">
                                    <h6 class="title-tour">Filter By Price</h6>
                                    <div class="widget widget-price">
                                        <div class="slider-labels flex-two">
                                            <span>Min</span>
                                            <span>Max</span>
                                        </div>
                                        <SliderRoot
                                            v-model="priceRange"
                                            class="range-slider"
                                            :min="100"
                                            :max="5000"
                                            :step="100"
                                            :min-steps-between-thumbs="1"
                                        >
                                            <SliderTrack class="slider-track">
                                                <SliderRange class="slider-fill" />
                                            </SliderTrack>
                                            <SliderThumb
                                                v-for="(_, index) in priceRange"
                                                :key="index"
                                                class="slider-thumb"
                                                :aria-label="index === 0 ? 'Minimum price' : 'Maximum price'"
                                            />
                                        </SliderRoot>
                                        <div class="slider-labels flex-two">
                                            <span>${{ priceRange[0].toLocaleString() }}</span>
                                            <span>${{ priceRange[1].toLocaleString() }}</span>
                                        </div>
                                    </div>
                                </fieldset>
                                <fieldset class="group-select relative mb-40">
                                    <h6 class="title-tour">Filter By Duration</h6>
                                    <div class="widget widget-price">
                                        <div class="slider-labels flex-two">
                                            <span>Min</span>
                                            <span>Max</span>
                                        </div>
                                        <SliderRoot
                                            v-model="durationRange"
                                            class="range-slider"
                                            :min="1"
                                            :max="30"
                                            :step="1"
                                            :min-steps-between-thumbs="1"
                                        >
                                            <SliderTrack class="slider-track">
                                                <SliderRange class="slider-fill" />
                                            </SliderTrack>
                                            <SliderThumb
                                                v-for="(_, index) in durationRange"
                                                :key="index"
                                                class="slider-thumb"
                                                :aria-label="index === 0 ? 'Minimum duration' : 'Maximum duration'"
                                            />
                                        </SliderRoot>
                                        <div class="slider-labels flex-two">
                                            <span>{{ durationRange[0] }} {{ durationRange[0] === 1 ? 'day' : 'days' }}</span>
                                            <span>{{ durationRange[1] }} {{ durationRange[1] === 1 ? 'day' : 'days' }}</span>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>

                        </div>
                    </form>
                </div>
                <div class="col-lg-9 listing-list-car-wrap">
                    <div class="listing-list-car-grid mb-60 mt-37">
                        <div
                            v-for="tour in tours.data"
                            :key="tour.id"
                            class="tour-listing box-sd"
                        >
                            <Link
                                :href="tourRoutes.show({ tour: tour.id })"
                                class="tour-listing-image"
                            >
                                <div class="image-housing">
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
                                    <span class="map"
                                        ><MapPin class="icon-inline" />
                                        {{
                                            tour.places.length > 0
                                                ? [...new Set(tour.places.map((p) => p.country).filter(Boolean))].join(', ')
                                                : '-'
                                        }}</span
                                    >
                                    <span v-if="tour.places.length > 0" class="map"
                                        ><Compass class="icon-inline" />
                                        {{
                                            tour.places.length > 3
                                                ? tour.places.slice(0, 3).map((p) => p.name).join(', ') + '...'
                                                : tour.places.map((p) => p.name).join(', ')
                                        }}</span
                                    >
                                </div>
                                <h3 class="title-tour-list">
                                    <Link :href="tourRoutes.show({ tour: tour.id })"
                                        >{{ tour.name }}</Link
                                    >
                                </h3>
                                <div class="icon-box flex-three">
                                    <div class="icons flex-three">
                                        <CalendarDays class="icon-inline" />
                                        <span
                                            >{{ tour.duration_days ? `${tour.duration_days} ${tour.duration_days === 1 ? 'day' : 'days'}` : '-' }}</span
                                        >
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
                    </div>
                    <div class="row" v-if="tours.data.length > 0">
                        <div class="col-md-12">
                            <div class="flex justify-center">
                                <Pagination :paginator="tours" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


</template>

<style scoped>
.listing-list-car-grid {
    grid-template-columns: repeat(3, 1fr);
}

.image-housing {
    height: 300px;
    width: 100%;
    overflow: hidden;
    border-radius: 8px;
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
    color: #4DA528;
    flex-shrink: 0;
}

.filter-icon {
    width: 20px;
    height: 20px;
    color: #4DA528;
    position: absolute;
    left: 20px;
    top: 50%;
    transform: translateY(-50%);
    pointer-events: none;
}

.location-meta {
    gap: 40px;
}

.mt-30 {
    margin-top: 30px;
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

.slider-labels {
    font-size: 12px;
    color: #999;
}

.destination-accordion {
    border: 1px solid #e5e5e5;
    border-radius: 8px;
    background: #fff;
    overflow: hidden;
}

.accordion-trigger {
    position: relative;
    display: flex;
    align-items: center;
    width: 100%;
    padding: 12px 40px 12px 53px;
    background: none;
    border: none;
    cursor: pointer;
    text-align: left;
}

.accordion-trigger .search-bar-group {
    flex: 1;
}

.accordion-trigger .search-bar-group label {
    display: block;
    font-size: 14px;
    font-weight: 500;
    color: #4f545a;
    margin-bottom: 2px;
}

.accordion-trigger .current {
    display: block;
    font-size: 16px;
    font-weight: 600;
    color: #081e2a;
}

.trigger-chevron {
    position: absolute;
    right: 16px;
    top: 50%;
    margin-top: -4px;
    width: 8px;
    height: 8px;
    border-bottom: 1.7px solid #3a3a3c;
    border-right: 1.7px solid #3a3a3c;
    transform: rotate(45deg);
    transition: all 0.15s ease-in-out;
    pointer-events: none;
}

.trigger-chevron.is-open {
    transform: rotate(-135deg);
    margin-top: 0;
}

.accordion-panel {
    max-height: 280px;
    overflow-y: auto;
    padding: 0 16px 12px;
    border-top: 1px solid #f0f0f0;
}

.country-header {
    padding: 6px 0;
}

.destination-accordion .country-header.group-check-box-wrap input[type="checkbox"] + label {
    display: inline-flex !important;
    align-items: center;
    gap: 8px;
    font-size: 15px !important;
    font-weight: 600 !important;
    color: #081e2a !important;
}

.country-flag {
    width: 20px;
    height: 14px;
    border-radius: 2px;
    object-fit: cover;
    border: 1px solid #e5e5e5;
    order: 1;
}

.country-places {
    padding-left: 28px;
    padding-bottom: 8px;
}

.activity-item,
.category-item {
    padding: 6px 0;
}

.btn-clear-filter {
    font-size: 13px !important;
}

.filter-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 20px;
    height: 20px;
    padding: 0 6px;
    background: #4DA528;
    color: #fff;
    font-size: 12px;
    font-weight: 600;
    border-radius: 10px;
    margin-right: 4px;
}

.destination-accordion .activity-item.group-check-box-wrap input[type="checkbox"] + label,
.destination-accordion .category-item.group-check-box-wrap input[type="checkbox"] + label {
    font-size: 14px !important;
    font-weight: 400 !important;
    color: #4f545a !important;
}

.destination-accordion .country-places.group-check-box-wrap input[type="checkbox"] + label {
    font-size: 14px !important;
    font-weight: 400 !important;
    color: #4f545a !important;
}

.place-option {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 4px 0;
    font-size: 14px;
    color: #4f545a;
    cursor: pointer;
}

.place-option input[type="checkbox"] {
    width: 16px;
    height: 16px;
    accent-color: #4da528;
}

@media (max-width: 991px) {
    .listing-list-car-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 767px) {
    .listing-list-car-grid {
        grid-template-columns: 1fr;
    }
}
</style>
