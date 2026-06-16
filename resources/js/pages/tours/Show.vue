<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { CheckCircle, XCircle } from 'lucide-vue-next';
import { Autoplay, Navigation, Pagination } from 'swiper/modules';
import { Swiper, SwiperSlide } from 'swiper/vue';
import { computed, onMounted, onUnmounted, ref } from 'vue';
import TourEnquiryForm from '@/components/TourEnquiryForm.vue';
import TourCard from '@/components/tours/TourCard.vue';
import { usePhotoSwipe } from '@/composables/usePhotoSwipe';
import { home } from '@/routes';
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';

const props = defineProps<{
    tour: {
        id: number;
        name: string;
        main_image: string | null;
        gallery_images: string[];
        price: string;
        price_amount: number;
        duration_days: number | null;
        overview: string | null;
        pricing_detailed: string | null;
        external_url: string | null;
        external_link_button_text: string | null;
        show_external_booking: boolean;
        categories: Array<{ id: number; name: string }>;
        activities: Array<{ id: number; name: string }>;
        places: Array<{ id: number; name: string; country?: string; country_code?: string | null }>;
        itinerary_items: Array<{ title: string; content: string | null }>;
        inclusions: Array<{ text: string }>;
        exclusions: Array<{ text: string }>;
    };
    turnstileSiteKey: string;
    isLocal: boolean;
    status?: string;
    seasonalRecommendations: Array<{
        id: number;
        name: string;
        main_image: string | null;
        price: string;
        price_amount: number;
        duration_days: number | null;
        categories: Array<{ id: number; name: string }>;
        places: Array<{ id: number; name: string; country?: string; country_code?: string | null }>;
    }>;
}>();

const enquiryAction = computed(() => `/tours/${props.tour.id}/enquiry`);

const activeTab = ref('overview');

const tabs = [
    { id: 'overview', label: 'Overview', icon: 'icon-Vector-51' },
    { id: 'itinerary', label: 'Itinerary', icon: 'icon-destination-2-1' },
    { id: 'inclusions', label: 'Inclusions', icon: 'icon-checkmark-outline' },
    { id: 'rates', label: 'Rates', icon: 'icon-price-tag-1' },
    { id: 'gallery', label: 'Gallery', icon: 'icon-image-gallery-1' },
];

function setTab(id: string): void {
    activeTab.value = id;
}

const destinationLabel = computed(() =>
    props.tour.places.length > 1 ? 'Destinations' : 'Destination',
);

const uniquePlaces = computed(() => [...new Set(props.tour.places.map((place) => place.name))]);

const destinationFirst = computed(() =>
    uniquePlaces.value.length > 0 ? uniquePlaces.value[0] : 'N/A',
);

const destinationMoreCount = computed(() =>
    Math.max(0, uniquePlaces.value.length - 1),
);

const durationValue = computed(() =>
    props.tour.duration_days
        ? `${props.tour.duration_days} ${props.tour.duration_days === 1 ? 'day' : 'days'}`
        : 'N/A',
);

const categoryLabel = computed(() =>
    props.tour.categories.length > 1 ? 'Tour Categories' : 'Tour Category',
);

const uniqueCategoryNames = computed(() =>
    props.tour.categories.map((c) => c.name),
);

const categoryDisplay = computed(() =>
    uniqueCategoryNames.value.length > 0
        ? uniqueCategoryNames.value.slice(0, 3).join(', ')
        : 'N/A',
);

const categoryMoreCount = computed(() =>
    Math.max(0, uniqueCategoryNames.value.length - 3),
);

const showCountriesModal = ref(false);
const showPlacesModal = ref(false);
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

const countryLabel = computed(() =>
    uniqueCountries.value.length > 1 ? 'Countries' : 'Country',
);

const countryFirst = computed(() =>
    uniqueCountries.value.length > 0 ? uniqueCountries.value[0].name : 'N/A',
);

const countryMoreCount = computed(() =>
    Math.max(0, uniqueCountries.value.length - 1),
);

const { openAt } = usePhotoSwipe(props.tour.gallery_images, props.tour.name);

const useSwiper = ref(true);
let swiperInstance: any = null;

function onSwiperInit(swiper: any): void {
    swiperInstance = swiper;
}

function swiperSlideNext(): void {
    swiperInstance?.slideNext();
}

function swiperSlidePrev(): void {
    swiperInstance?.slidePrev();
}

// ── Custom gallery carousel ──────────────────────────────────────────
const currentIndex = ref(0);
const slidesPerView = ref(4);
const isDragging = ref(false);
const dragStartX = ref(0);

function updateSlidesPerView(): void {
    const w = window.innerWidth;

    if (w < 640) {
        slidesPerView.value = 1;
    } else if (w < 992) {
        slidesPerView.value = 2;
    } else if (w < 1200) {
        slidesPerView.value = 3;
    } else {
        slidesPerView.value = 4;
    }
}

onMounted(() => {
    updateSlidesPerView();
    window.addEventListener('resize', updateSlidesPerView);
});

onUnmounted(() => {
    window.removeEventListener('resize', updateSlidesPerView);
});

const slidePercent = computed(() => 100 / slidesPerView.value);
const maxIndex = computed(() =>
    Math.max(
        0,
        (props.tour?.gallery_images?.length ?? 0) - slidesPerView.value,
    ),
);
const dotCount = computed(() => maxIndex.value + 1);

const trackStyle = computed(() => ({
    transform: `translateX(-${currentIndex.value * slidePercent.value}%)`,
    transition: isDragging.value ? 'none' : 'transform 0.35s ease-out',
}));

function carouselNext(): void {
    if (currentIndex.value < maxIndex.value) {
        currentIndex.value++;
    }
}

function carouselPrev(): void {
    if (currentIndex.value > 0) {
        currentIndex.value--;
    }
}

function carouselGoTo(index: number): void {
    currentIndex.value = Math.min(index, maxIndex.value);
}

function onDragStart(e: PointerEvent): void {
    isDragging.value = true;
    dragStartX.value = e.clientX;
    (e.currentTarget as HTMLElement)?.setPointerCapture?.(e.pointerId);
}

function onDragEnd(e: PointerEvent): void {
    if (!isDragging.value) {
        return;
    }

    isDragging.value = false;
    const diff = dragStartX.value - e.clientX;
    const threshold = 50;

    if (diff > threshold) {
        carouselNext();
    } else if (diff < -threshold) {
        carouselPrev();
    }

    (e.currentTarget as HTMLElement)?.releasePointerCapture?.(e.pointerId);
}
</script>

<template>
    <Head :title="tour.name" />

    <div
        v-if="status"
        class="mb-4 text-center text-sm font-medium text-green-600"
    >
        {{ status }}
    </div>

    <!-- Hero -->
    <section
        class="breadcumb-section hero-section"
        :style="{
            backgroundImage: tour.main_image
                ? `url(${tour.main_image})`
                : 'url(/template/images/page/breakcrumb.jpg)',
        }"
    >
        <div class="tf-container">
            <div class="row">
                <div class="col-lg-12 center z-index1">
                    <h1 class="title">{{ tour.name }}</h1>
                    <ul class="breadcumb-list flex-five">
                        <li><Link :href="home()">Home</Link></li>
                        <li><Link href="/tours">Tours</Link></li>
                        <li>
                            <span>{{ tour.name }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Tour single -->
    <section class="tour-single">
        <div class="tf-container">
            <!-- Tabs nav -->
            <div class="row pd-main">
                <div class="col-lg-12">
                    <ul
                        class="nav justify-content-between tab-tour-single"
                        style="position: relative; z-index: 2"
                        role="tablist"
                    >
                        <li
                            v-for="tab in tabs"
                            :key="tab.id"
                            class="nav-item"
                            role="presentation"
                        >
                            <button
                                :class="[
                                    'nav-link',
                                    { active: activeTab === tab.id },
                                ]"
                                type="button"
                                role="tab"
                                :aria-selected="activeTab === tab.id"
                                @click="setTab(tab.id)"
                            >
                                <i :class="tab.icon"></i> {{ tab.label }}
                            </button>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Overview-only meta + carousel -->
            <div v-show="activeTab === 'overview'" class="row">
                <div class="col-lg-12">
                    <!-- Meta + swiper -->
                    <div class="row mb-50">
                        <div class="col-lg-12">
                            <div class="inner-heading-wrap flex-two">
                                <div class="inner-heading">
                                    <div class="tour-meta-grid">
                                        <div class="tour-meta-item">
                                            <span class="tour-meta-label">{{
                                                countryLabel
                                            }}</span>
                                            <span class="tour-meta-value">
                                                {{ countryFirst }}<span
                                                    v-if="countryMoreCount > 0"
                                                    class="show-more-link"
                                                    @click.prevent="showCountriesModal = true"
                                                >
                                                    +{{ countryMoreCount }} more</span
                                                >
                                            </span>
                                        </div>
                                        <div class="tour-meta-item">
                                            <span class="tour-meta-label">{{
                                                destinationLabel
                                            }}</span>
                                            <span class="tour-meta-value">
                                                {{ destinationFirst }}<span
                                                    v-if="destinationMoreCount > 0"
                                                    class="show-more-link"
                                                    @click.prevent="showPlacesModal = true"
                                                >
                                                    +{{ destinationMoreCount }} more</span
                                                >
                                            </span>
                                        </div>
                                        <div class="tour-meta-item">
                                            <span class="tour-meta-label"
                                                >Duration</span
                                            >
                                            <span class="tour-meta-value">{{
                                                durationValue
                                            }}</span>
                                        </div>
                                        <div class="tour-meta-item">
                                            <span class="tour-meta-label">{{
                                                categoryLabel
                                            }}</span>
                                            <span class="tour-meta-value">{{
                                                categoryDisplay
                                            }}<span
                                                    v-if="categoryMoreCount > 0"
                                                    class="show-more-link"
                                                    @click.prevent="showCategoriesModal = true"
                                                >
                                                    +{{ categoryMoreCount }} more</span
                                                ></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="inner-price">
                                    <p>
                                        <span style="margin-right: 4px"
                                            >From</span
                                        >
                                        <span class="price-sale text-main">{{
                                            tour.price
                                        }}</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Swiper v12 carousel -->
                    <div
                        v-if="tour.gallery_images.length > 0 && useSwiper"
                        class="tour-gallery-swiper-wrap mb-40"
                    >
                        <Swiper
                            :modules="[Autoplay, Pagination]"
                            :pagination="{ clickable: true }"
                            :slides-per-view="4"
                            :space-between="16"
                            :grab-cursor="true"
                            :breakpoints="{
                                320: {
                                    slidesPerView: 1,
                                    spaceBetween: 12,
                                },
                                640: {
                                    slidesPerView: 2,
                                    spaceBetween: 12,
                                },
                                992: {
                                    slidesPerView: 3,
                                    spaceBetween: 16,
                                },
                                1200: {
                                    slidesPerView: 4,
                                    spaceBetween: 16,
                                },
                            }"
                            :loop="tour.gallery_images.length > 4"
                            :autoplay="{
                                delay: 3000,
                                disableOnInteraction: true,
                            }"
                            class="tour-gallery-swiper"
                            @swiper="onSwiperInit"
                        >
                            <SwiperSlide
                                v-for="(img, index) in tour.gallery_images"
                                :key="index"
                                class="cursor-pointer"
                                @click="openAt(index)"
                            >
                                <img
                                    :src="img"
                                    :alt="`${tour.name} gallery image ${index + 1}`"
                                    class="swiper-slide-img"
                                />
                            </SwiperSlide>
                        </Swiper>

                        <!-- Custom navigation arrows -->
                        <button
                            v-if="tour.gallery_images.length > 1"
                            class="swiper-custom-arrow swiper-custom-prev"
                            @click="swiperSlidePrev"
                        >
                            <svg
                                width="16"
                                height="16"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2.5"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            >
                                <polyline points="15 18 9 12 15 6" />
                            </svg>
                        </button>
                        <button
                            v-if="tour.gallery_images.length > 1"
                            class="swiper-custom-arrow swiper-custom-next"
                            @click="swiperSlideNext"
                        >
                            <svg
                                width="16"
                                height="16"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2.5"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            >
                                <polyline points="9 18 15 12 9 6" />
                            </svg>
                        </button>
                    </div>

                    <!-- Custom Vue carousel (fallback) -->
                    <div
                        v-if="tour.gallery_images.length > 0 && !useSwiper"
                        class="mb-40"
                    >
                        <div class="tour-gallery-carousel">
                            <div class="carousel-track-wrapper">
                                <div
                                    class="carousel-track"
                                    :style="trackStyle"
                                    @pointerdown="onDragStart"
                                    @pointerup="onDragEnd"
                                    @pointerleave="onDragEnd"
                                >
                                    <div
                                        v-for="(
                                            img, index
                                        ) in tour.gallery_images"
                                        :key="index"
                                        class="carousel-slide"
                                    >
                                        <div class="carousel-slide-inner">
                                            <img
                                                :src="img"
                                                :alt="`${tour.name} gallery image ${index + 1}`"
                                            />
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Navigation arrows -->
                            <button
                                v-if="maxIndex > 0"
                                class="carousel-arrow carousel-prev"
                                :disabled="currentIndex <= 0"
                                @click="carouselPrev"
                            >
                                <svg
                                    width="16"
                                    height="16"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2.5"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                >
                                    <polyline points="15 18 9 12 15 6" />
                                </svg>
                            </button>
                            <button
                                v-if="maxIndex > 0"
                                class="carousel-arrow carousel-next"
                                :disabled="currentIndex >= maxIndex"
                                @click="carouselNext"
                            >
                                <svg
                                    width="16"
                                    height="16"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2.5"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                >
                                    <polyline points="9 18 15 12 9 6" />
                                </svg>
                            </button>

                            <!-- Dot pagination -->
                            <div v-if="dotCount > 1" class="carousel-dots">
                                <button
                                    v-for="(_, dotIndex) in dotCount"
                                    :key="dotIndex"
                                    class="carousel-dot"
                                    :class="{
                                        active: currentIndex === dotIndex,
                                    }"
                                    @click="carouselGoTo(dotIndex)"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab content + sidebar -->
            <div class="row">
                <div class="col-lg-8">
                    <div class="tab-content">
                        <!-- Overview Tab -->
                        <div
                            v-show="activeTab === 'overview'"
                            class="tab-pane fade show active"
                            role="tabpanel"
                            tabindex="0"
                        >
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="information-content-tour">
                                        <div
                                            v-if="tour.overview"
                                            class="description-wrap mb-40"
                                        >
                                            <span class="description"
                                                >Description</span
                                            >
                                            <div
                                                class="des"
                                                v-html="tour.overview"
                                            ></div>
                                        </div>
                                        <div
                                            v-else
                                            class="description-wrap mb-40"
                                        >
                                            <p class="des">
                                                No description available for
                                                this tour.
                                            </p>
                                        </div>

                                        <div class="expect-wrap">
                                            <h4 class="title mb-40">
                                                Tour Activities
                                            </h4>
                                            <ul
                                                v-if="
                                                    tour.activities.length > 0
                                                "
                                                class="listing-icon activities-grid"
                                            >
                                                <li
                                                    v-for="activity in tour.activities"
                                                    :key="activity.id"
                                                    class="flex-three"
                                                >
                                                    <i class="icon-10"></i>
                                                    <p>{{ activity.name }}</p>
                                                </li>
                                            </ul>
                                            <p v-else class="des">
                                                No activities listed for this
                                                tour.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Itinerary Tab -->
                        <div
                            v-show="activeTab === 'itinerary'"
                            class="tab-pane fade show active"
                            role="tabpanel"
                            tabindex="0"
                        >
                            <div class="planing-content-tour">
                                <h3 class="title-plan">Tour Plan :</h3>
                                <div
                                    v-for="(
                                        item, index
                                    ) in tour.itinerary_items"
                                    :key="index"
                                    class="tour-planing-section flex"
                                >
                                    <div class="number-box flex-five">
                                        {{ String(index + 1).padStart(2, '0') }}
                                    </div>
                                    <div class="content-box">
                                        <h5 class="title">
                                            {{ item.title }}
                                        </h5>
                                        <div
                                            v-if="item.content"
                                            class="des"
                                            v-html="item.content"
                                        ></div>
                                    </div>
                                </div>

                                <div
                                    v-if="tour.itinerary_items.length === 0"
                                    class="tour-planing-section flex"
                                >
                                    <div class="content-box">
                                        <p class="des">
                                            No itinerary items available for
                                            this tour.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Inclusions Tab -->
                        <div
                            v-show="activeTab === 'inclusions'"
                            class="tab-pane fade show active"
                            role="tabpanel"
                            tabindex="0"
                        >
                            <div class="expect-wrap mb-70">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h5 class="mb-18">Whats Included</h5>
                                        <ul
                                            v-if="tour.inclusions.length > 0"
                                            class="listing-clude"
                                        >
                                            <li
                                                v-for="(
                                                    inclusion, index
                                                ) in tour.inclusions"
                                                :key="index"
                                                class="flex-three"
                                            >
                                                <CheckCircle
                                                    class="clude-icon included-icon"
                                                />
                                                <p>
                                                    {{ inclusion.text }}
                                                </p>
                                            </li>
                                        </ul>
                                        <p v-else class="des">
                                            No inclusions listed.
                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        <h5 class="mb-18">Whats Excluded</h5>
                                        <ul
                                            v-if="tour.exclusions.length > 0"
                                            class="listing-clude"
                                        >
                                            <li
                                                v-for="(
                                                    exclusion, index
                                                ) in tour.exclusions"
                                                :key="index"
                                                class="flex-three"
                                            >
                                                <XCircle
                                                    class="clude-icon excluded-icon"
                                                />
                                                <p>
                                                    {{ exclusion.text }}
                                                </p>
                                            </li>
                                        </ul>
                                        <p v-else class="des">
                                            No exclusions listed.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Rates Tab -->
                        <div
                            v-show="activeTab === 'rates'"
                            class="tab-pane fade show active"
                            role="tabpanel"
                            tabindex="0"
                        >
                            <div class="review-content-tour">
                                <div
                                    v-if="tour.pricing_detailed"
                                    class="description-wrap mb-40"
                                >
                                    <span class="description"
                                        >Detailed Pricing:</span
                                    >
                                    <div
                                        class="des"
                                        v-html="tour.pricing_detailed"
                                    ></div>
                                </div>
                                <div v-else class="description-wrap mb-40">
                                    <p class="des">
                                        Detailed pricing information is not
                                        available for this tour.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Shot Gallery Tab -->
                        <div
                            v-show="activeTab === 'gallery'"
                            class="tab-pane fade show active"
                            role="tabpanel"
                            tabindex="0"
                        >
                            <div class="gallery-content-tour">
                                <div
                                    v-for="(img, index) in tour.gallery_images"
                                    :key="index"
                                    class="gallery-item cursor-pointer"
                                    @click="openAt(index)"
                                >
                                    <img
                                        :src="img"
                                        :alt="`${tour.name} gallery image ${index + 1}`"
                                        class="gallery-item-img"
                                    />
                                </div>
                                <div
                                    v-if="tour.gallery_images.length === 0"
                                    class="gallery-item"
                                >
                                    <img
                                        src="/template/images/gallery/gallery.jpg"
                                        alt="image"
                                        class="gallery-item-img"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="col-lg-4">
                    <div class="side-bar-right">
                        <div class="sidebar-widget">
                            <h6 class="block-heading !mb-0">Enquiry & Quote</h6>
                            <p class="sidebar-subtitle mb-22">
                                Request a booking for this tour
                            </p>
                            <TourEnquiryForm
                                :action="enquiryAction"
                                :is-local="isLocal"
                                :turnstile-site-key="turnstileSiteKey"
                                :status="status"
                            />
                        </div>
                        <div class="sidebar-widget">
                            <h6 class="block-heading">Book With Confidence</h6>
                            <ul class="category-confidence">
                                <li class="flex-three">
                                    <i class="icon-customer-service-1"></i>
                                    <span>Customer care available 24/7</span>
                                </li>
                                <li class="flex-three">
                                    <i class="icon-Vector-6"></i>
                                    <span>Hand-picked Tours & Activities</span>
                                </li>
                                <li class="flex-three">
                                    <i class="icon-insurance-1"></i>
                                    <span>Free Travel Insureance</span>
                                </li>
                                <li class="flex-three">
                                    <i class="icon-price-tag-1-1"></i>
                                    <span>No-hassle best price guarantee</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Seasonal Recommendations -->
    <section
        v-if="seasonalRecommendations.length > 0"
        class="seasonal-recommendations-section mb-40"
    >
        <div class="tf-container">
            <div class="row">
                <div class="col-lg-12 mb-30">
                    <h3 class="title">Seasonal Recommendations</h3>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="seasonal-carousel-wrap">
                        <Swiper
                            :modules="[Autoplay, Navigation, Pagination]"
                            :pagination="{ clickable: true }"
                            :navigation="{
                                nextEl: '.seasonal-swiper-next',
                                prevEl: '.seasonal-swiper-prev',
                            }"
                            :slides-per-view="3"
                            :space-between="24"
                            :grab-cursor="true"
                            :breakpoints="{
                                320: {
                                    slidesPerView: 1,
                                    spaceBetween: 16,
                                },
                                640: {
                                    slidesPerView: 2,
                                    spaceBetween: 20,
                                },
                                992: {
                                    slidesPerView: 3,
                                    spaceBetween: 24,
                                },
                            }"
                            :loop="seasonalRecommendations.length > 3"
                            :autoplay="{
                                delay: 3500,
                                disableOnInteraction: true,
                            }"
                            class="seasonal-swiper"
                        >
                            <SwiperSlide
                                v-for="recommendation in seasonalRecommendations"
                                :key="recommendation.id"
                            >
                                <TourCard
                                    :tour="recommendation"
                                    full-height
                                    image-size="compact"
                                />
                            </SwiperSlide>
                        </Swiper>

                        <button
                            v-if="seasonalRecommendations.length > 1"
                            class="swiper-custom-arrow swiper-custom-prev seasonal-swiper-prev"
                            aria-label="Previous seasonal recommendation"
                        >
                            <svg
                                width="16"
                                height="16"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2.5"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            >
                                <polyline points="15 18 9 12 15 6" />
                            </svg>
                        </button>
                        <button
                            v-if="seasonalRecommendations.length > 1"
                            class="swiper-custom-arrow swiper-custom-next seasonal-swiper-next"
                            aria-label="Next seasonal recommendation"
                        >
                            <svg
                                width="16"
                                height="16"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2.5"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            >
                                <polyline points="9 18 15 12 9 6" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <template v-if="isMounted">
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
</template>

<style scoped>
.tf-container {
    max-width: 1320px;
}
.tf-container.w-1456 {
    max-width: 1475px;
}

.tour-gallery-carousel {
    position: relative;
    overflow: hidden;
    padding-bottom: 36px;
}

.carousel-track-wrapper {
    overflow: hidden;
    cursor: grab;
}

.carousel-track-wrapper:active {
    cursor: grabbing;
}

.carousel-track {
    display: flex;
    will-change: transform;
}

.carousel-slide {
    flex: 0 0 25%;
    padding: 0 8px;
    box-sizing: border-box;
}

.carousel-slide-inner {
    border-radius: 8px;
    overflow: hidden;
}

.carousel-slide-inner img {
    width: 100%;
    height: 180px;
    object-fit: cover;
    display: block;
}

@media (max-width: 1199px) {
    .carousel-slide {
        flex: 0 0 33.333%;
    }
}

@media (max-width: 991px) {
    .carousel-slide {
        flex: 0 0 50%;
    }
}

@media (max-width: 639px) {
    .carousel-slide {
        flex: 0 0 100%;
        padding: 0 4px;
    }
}

/* Arrows */
.carousel-arrow {
    position: absolute;
    top: calc(50% - 15px);
    transform: translateY(-50%);
    width: 36px;
    height: 36px;
    background: #ffffff;
    border: none;
    border-radius: 50%;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    color: #081e2a;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    z-index: 2;
    transition: opacity 0.2s;
}

.carousel-arrow:disabled {
    opacity: 0.35;
    cursor: not-allowed;
}

.carousel-prev {
    left: 4px;
}

.carousel-next {
    right: 4px;
}

/* Dots */
.carousel-dots {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 8px;
    padding-top: 12px;
}

.carousel-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    border: none;
    background: rgba(0, 0, 0, 0.2);
    cursor: pointer;
    padding: 0;
    transition: background 0.2s;
}

.carousel-dot.active {
    background: #4da528;
}

/* Swiper overrides */
.tour-gallery-swiper-wrap {
    position: relative;
}

.tour-gallery-swiper {
    overflow: hidden;
    padding-bottom: 30px;
}

.tour-gallery-swiper .swiper-slide-img {
    width: 100%;
    height: 180px;
    object-fit: cover;
    border-radius: 8px;
    display: block;
}

.tour-gallery-swiper :deep(.swiper-pagination) {
    bottom: 0;
}

.tour-gallery-swiper :deep(.swiper-pagination-bullet-active) {
    background: #4da528;
}

.tour-gallery-swiper :deep(.swiper-pagination-bullet-active::before) {
    display: none;
}

/* Custom Swiper navigation arrows */
.swiper-custom-arrow {
    position: absolute;
    top: 42%;
    transform: translateY(-50%);
    width: 38px;
    height: 38px;
    padding: 0;
    background: rgba(255, 255, 255, 0.92);
    border: none;
    border-radius: 50%;
    box-shadow: 0 1px 6px rgba(0, 0, 0, 0.18);
    color: #081e2a;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    z-index: 2;
    opacity: 0;
    transition:
        opacity 0.25s ease,
        background 0.2s;
}

.tour-gallery-swiper-wrap:hover .swiper-custom-arrow {
    opacity: 1;
}

.swiper-custom-arrow:hover {
    background: #ffffff;
}

.swiper-custom-prev {
    left: 8px;
}

.swiper-custom-next {
    right: 8px;
}

/* Always show arrows on touch devices */
@media (hover: none) {
    .swiper-custom-arrow {
        opacity: 0.9;
    }
}

/* Align tab icons consistently with their labels */
.tab-tour-single .nav-link i {
    vertical-align: middle;
}

.listing-clude li .clude-icon {
    width: 18px;
    height: 18px;
    margin-right: 19px;
    flex-shrink: 0;
}

.listing-clude li .included-icon {
    color: #4da528;
}

.listing-clude li .excluded-icon {
    color: #ff494f;
}

.tour-meta-grid {
    display: flex;
    flex-wrap: wrap;
    gap: 16px 0;
}

.tour-meta-item {
    flex: 0 1 auto;
    min-width: 120px;
    padding: 0 24px;
    border-right: 1px solid #e0e2e4;
}

.tour-meta-item:first-child {
    padding-left: 0;
}

.tour-meta-item:last-child {
    border-right: none;
}

.tour-meta-label {
    display: block;
    font-size: 12px;
    font-weight: 500;
    line-height: 18px;
    color: #6b7280;
    text-transform: uppercase;
    margin-bottom: 4px;
}

.tour-meta-value {
    display: block;
    font-size: 16px;
    font-weight: 600;
    line-height: 24px;
    color: #081e2a;
}

.show-more-link {
    color: #4da528;
    cursor: pointer;
    font-weight: 400;
    font-size: 14px;
}

.show-more-link:hover {
    text-decoration: underline;
}

@media (max-width: 991px) {
    .tour-meta-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 16px;
    }

    .tour-meta-item {
        min-width: auto;
        padding: 0;
        border-right: none;
    }
}

@media (max-width: 575px) {
    .tour-meta-grid {
        grid-template-columns: 1fr;
    }
}

.gallery-content-tour {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 24px;
}

.gallery-item {
    aspect-ratio: 4 / 3;
    overflow: hidden;
    border-radius: 8px;
}

.gallery-item-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

@media (max-width: 991px) {
    .gallery-content-tour {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 575px) {
    .gallery-content-tour {
        grid-template-columns: 1fr;
    }
}

.sidebar-subtitle {
    font-size: 14px;
    line-height: 20px;
    color: #6b7280;
}

.optional-label {
    text-transform: lowercase;
    font-size: 0.85em;
    color: #6b7280;
}

.activities-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 0 30px;
}

@media (max-width: 991px) {
    .activities-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 767px) {
    .activities-grid {
        grid-template-columns: 1fr;
    }
}

.tour-single .pd-main {
    padding-top: 0;
    padding-bottom: 50px;
}

.seasonal-recommendations-section .title {
    font-size: 28px;
    font-weight: 700;
    color: #081e2a;
    text-align: center;
}

.seasonal-carousel-wrap {
    position: relative;
}

.seasonal-recommendations-section {
    margin-top: 70px;
}

.seasonal-carousel-wrap .swiper-custom-arrow {
    width: 52px;
    height: 52px;
}

.seasonal-carousel-wrap .swiper-custom-arrow svg {
    width: 22px;
    height: 22px;
}

.seasonal-swiper {
    overflow: hidden;
    padding-bottom: 36px;
}

.seasonal-swiper :deep(.swiper-slide) {
    height: auto;
}

.seasonal-swiper :deep(.swiper-pagination) {
    bottom: 0;
}

.seasonal-swiper :deep(.swiper-pagination-bullet-active) {
    background: #4da528;
}

.seasonal-swiper :deep(.swiper-pagination-bullet-active::before) {
    display: none;
}

.seasonal-carousel-wrap .seasonal-swiper-prev {
    left: -70px;
}

.seasonal-carousel-wrap .seasonal-swiper-next {
    right: -70px;
}

.seasonal-carousel-wrap:hover .swiper-custom-arrow {
    opacity: 1;
}

@media (max-width: 1299px) {
    .seasonal-carousel-wrap .swiper-custom-arrow {
        width: 44px;
        height: 44px;
    }

    .seasonal-carousel-wrap .swiper-custom-arrow svg {
        width: 20px;
        height: 20px;
    }

    .seasonal-carousel-wrap .seasonal-swiper-prev {
        left: -70px;
    }

    .seasonal-carousel-wrap .seasonal-swiper-next {
        right: -70px;
    }
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
