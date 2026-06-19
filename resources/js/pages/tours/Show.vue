<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { CheckCircle, XCircle } from 'lucide-vue-next';
import { Autoplay, Navigation, Pagination } from 'swiper/modules';
import { Swiper, SwiperSlide } from 'swiper/vue';
import { computed, onMounted, ref } from 'vue';
import TourEnquiryFormTailwind from '@/components/TourEnquiryFormTailwind.vue';
import TourCardTailwind from '@/components/tours/TourCardTailwind.vue';
import { usePhotoSwipe } from '@/composables/usePhotoSwipe';
import { home } from '@/routes';
import { enquiry as toursEnquiry } from '@/routes/tours';
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

const enquiryAction = computed(() => toursEnquiry({ tour: props.tour.id }).url);
const activeTab = ref('overview');

const tabs = [
    { id: 'overview', label: 'Overview', svg: '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>' },
    { id: 'itinerary', label: 'Itinerary', svg: '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>' },
    { id: 'inclusions', label: 'Inclusions', svg: '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>' },
    { id: 'rates', label: 'Rates', svg: '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>' },
    { id: 'gallery', label: 'Gallery', svg: '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>' },
];

const countryLabel = computed(() => uniqueCountries.value.length > 1 ? 'Countries' : 'Country');
const destinationLabel = computed(() => props.tour.places.length > 1 ? 'Destinations' : 'Destination');
const uniquePlaces = computed(() => [...new Set(props.tour.places.map((place) => place.name))]);
const destinationFirst = computed(() => uniquePlaces.value.length > 0 ? uniquePlaces.value[0] : 'N/A');
const destinationMoreCount = computed(() => Math.max(0, uniquePlaces.value.length - 1));
const durationValue = computed(() => props.tour.duration_days ? `${props.tour.duration_days} ${props.tour.duration_days === 1 ? 'day' : 'days'}` : 'N/A');
const categoryLabel = computed(() => props.tour.categories.length > 1 ? 'Tour Categories' : 'Tour Category');
const uniqueCategoryNames = computed(() => props.tour.categories.map((c) => c.name));
const categoryDisplay = computed(() => uniqueCategoryNames.value.length > 0 ? uniqueCategoryNames.value.slice(0, 3).join(', ') : 'N/A');
const categoryMoreCount = computed(() => Math.max(0, uniqueCategoryNames.value.length - 3));

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

const countryFirst = computed(() => uniqueCountries.value.length > 0 ? uniqueCountries.value[0].name : 'N/A');
const countryMoreCount = computed(() => Math.max(0, uniqueCountries.value.length - 1));

const { openAt } = usePhotoSwipe(props.tour.gallery_images, props.tour.name);

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
</script>

<template>
    <Head :title="tour.name" />

    <div v-if="status" class="mb-4 text-center text-sm font-medium text-green-600">{{ status }}</div>

    <!-- Hero -->
    <section
        class="relative flex items-center bg-cover bg-center bg-no-repeat py-[137px]"
        :style="{ backgroundImage: tour.main_image ? `url(${tour.main_image})` : `url('/template/images/page/breakcrumb.jpg')` }"
    >
        <div class="absolute inset-0" style="background: linear-gradient(262deg, rgba(0,0,0,0.33), rgba(4,29,11,0.89))"></div>
        <div class="relative z-[1] mx-auto w-full max-w-[1320px] px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="mb-[26px] text-[65px] font-semibold leading-[82px] text-white max-lg:text-[45px] max-lg:leading-[50px]">{{ tour.name }}</h1>
                <nav class="flex items-center justify-center text-[18px] leading-[22.68px]">
                    <Link :href="home()" class="relative mr-[30px] text-[#4DA528] after:absolute after:right-[-18px] after:top-1/2 after:-translate-y-1/2 after:text-[10px] after:text-white after:content-['\203A']">Home</Link>
                    <Link href="/tours" class="relative mr-[30px] text-[#4DA528] after:absolute after:right-[-18px] after:top-1/2 after:-translate-y-1/2 after:text-[10px] after:text-white after:content-['\203A']">Tours</Link>
                    <span class="text-white">{{ tour.name }}</span>
                </nav>
            </div>
        </div>
    </section>

    <!-- Tour single -->
    <section class="pb-20">
        <div class="mx-auto max-w-[1320px] px-4 sm:px-6 lg:px-8">
            <!-- Tabs nav -->
            <div class="-mt-12 pb-[50px] pt-0">
                <ul class="relative z-[2] flex justify-between rounded-[47px] bg-white p-2.5 shadow-[0_4px_16px_rgba(0,0,0,0.05)]" role="tablist">
                    <li v-for="tab in tabs" :key="tab.id" class="nav-item flex-1" role="presentation">
                        <button type="button" role="tab" :aria-selected="activeTab === tab.id"
                            class="inline-flex w-full items-center justify-center gap-3.5 rounded-[47px] px-8 py-[26px] text-[16px] font-semibold leading-5 transition-colors"
                            :class="[activeTab === tab.id ? 'bg-[#F4F6F8]' : 'text-[#081E2A] hover:bg-[#F4F6F8] cursor-pointer']"
                            @click="activeTab = tab.id">
                            <span class="text-[#4DA528]" v-html="tab.svg"></span>
                            {{ tab.label }}
                        </button>
                    </li>
                </ul>
            </div>

            <!-- Overview tab -->
            <div v-show="activeTab === 'overview'">
                <div class="mb-[50px]">
                    <div class="flex flex-wrap items-center justify-between gap-4">
                        <div class="flex flex-wrap gap-y-4 max-[991px]:grid max-[991px]:grid-cols-2 max-[991px]:gap-4">
                            <div class="min-w-[120px] shrink-0 border-r border-[#e0e2e4] px-6 first:pl-0 last:border-r-0 max-[991px]:min-w-auto max-[991px]:border-r-0 max-[991px]:p-0">
                                <span class="mb-1 block text-xs font-medium uppercase leading-[18px] text-gray-500">{{ countryLabel }}</span>
                                <span class="block text-base font-semibold leading-6 text-[#081e2a]">{{ countryFirst }}<span v-if="countryMoreCount > 0" class="ml-1 cursor-pointer text-sm font-normal text-[#4da528] hover:underline" @click.prevent="showCountriesModal = true">+{{ countryMoreCount }} more</span></span>
                            </div>
                            <div class="min-w-[120px] shrink-0 border-r border-[#e0e2e4] px-6 first:pl-0 last:border-r-0 max-[991px]:min-w-auto max-[991px]:border-r-0 max-[991px]:p-0">
                                <span class="mb-1 block text-xs font-medium uppercase leading-[18px] text-gray-500">{{ destinationLabel }}</span>
                                <span class="block text-base font-semibold leading-6 text-[#081e2a]">{{ destinationFirst }}<span v-if="destinationMoreCount > 0" class="ml-1 cursor-pointer text-sm font-normal text-[#4da528] hover:underline" @click.prevent="showPlacesModal = true">+{{ destinationMoreCount }} more</span></span>
                            </div>
                            <div class="min-w-[120px] shrink-0 border-r border-[#e0e2e4] px-6 first:pl-0 last:border-r-0 max-[991px]:min-w-auto max-[991px]:border-r-0 max-[991px]:p-0">
                                <span class="mb-1 block text-xs font-medium uppercase leading-[18px] text-gray-500">Duration</span>
                                <span class="block text-base font-semibold leading-6 text-[#081e2a]">{{ durationValue }}</span>
                            </div>
                            <div class="min-w-[120px] shrink-0 border-r border-[#e0e2e4] px-6 first:pl-0 last:border-r-0 max-[991px]:min-w-auto max-[991px]:border-r-0 max-[991px]:p-0">
                                <span class="mb-1 block text-xs font-medium uppercase leading-[18px] text-gray-500">{{ categoryLabel }}</span>
                                <span class="block text-base font-semibold leading-6 text-[#081e2a]">{{ categoryDisplay }}<span v-if="categoryMoreCount > 0" class="ml-1 cursor-pointer text-sm font-normal text-[#4da528] hover:underline" @click.prevent="showCategoriesModal = true">+{{ categoryMoreCount }} more</span></span>
                            </div>
                        </div>
                        <div class="shrink-0"><p><span class="mr-1 text-gray-500">From</span><span class="text-xl font-bold text-[#4DA528]">{{ tour.price }}</span></p></div>
                    </div>
                </div>

                <div v-if="tour.gallery_images.length > 0" class="tour-gallery-swiper-wrap relative mb-10">
                    <Swiper :modules="[Autoplay, Pagination]" :pagination="{ clickable: true }" :slides-per-view="4" :space-between="16" :grab-cursor="true" :breakpoints="{ 320: { slidesPerView: 1, spaceBetween: 12 }, 640: { slidesPerView: 2, spaceBetween: 12 }, 992: { slidesPerView: 3, spaceBetween: 16 }, 1200: { slidesPerView: 4, spaceBetween: 16 } }" :loop="tour.gallery_images.length > 4" :autoplay="{ delay: 3000, disableOnInteraction: true }" class="tour-gallery-swiper overflow-hidden pb-[30px]" @swiper="onSwiperInit">
                        <SwiperSlide v-for="(img, index) in tour.gallery_images" :key="index" class="cursor-pointer" @click="openAt(index)">
                            <img :src="img" :alt="`${tour.name} gallery image ${index + 1}`" class="h-[180px] w-full rounded-lg object-cover" />
                        </SwiperSlide>
                    </Swiper>
                    <button v-if="tour.gallery_images.length > 1" class="swiper-custom-arrow swiper-custom-prev" @click="swiperSlidePrev"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6" /></svg></button>
                    <button v-if="tour.gallery_images.length > 1" class="swiper-custom-arrow swiper-custom-next" @click="swiperSlideNext"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6" /></svg></button>
                </div>
            </div>

            <!-- Tab content + sidebar -->
            <div class="flex flex-wrap">
                <div class="w-full lg:w-8/12">
                    <div v-show="activeTab === 'overview'">
                        <div class="mb-10">
                            <span class="text-[20px] font-semibold text-[#081E2A]">Description</span>
                            <div v-if="tour.overview" class="des mt-3 text-[16px] leading-[30.8px] text-[#4F545A]" v-html="tour.overview"></div>
                            <p v-else class="des mt-3 text-[16px] leading-[30.8px] text-[#4F545A]">No description available for this tour.</p>
                        </div>
                        <div>
                            <h4 class="mb-10 text-[20px] font-semibold text-[#081E2A]">Tour Activities</h4>
                            <ul v-if="tour.activities.length > 0" class="grid grid-cols-1 gap-0 gap-x-[30px] sm:grid-cols-2 lg:grid-cols-3">
                                <li v-for="activity in tour.activities" :key="activity.id" class="flex items-center gap-2 py-1 text-[#4F545A]">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#4DA528" stroke-width="2"><path d="M20 6L9 17l-5-5"/></svg>
                                    <p>{{ activity.name }}</p>
                                </li>
                            </ul>
                            <p v-else class="text-[16px] text-[#4F545A]">No activities listed for this tour.</p>
                        </div>
                    </div>

                    <div v-show="activeTab === 'itinerary'">
                        <h3 class="mb-6 text-[22px] font-semibold text-[#081E2A]">Tour Plan :</h3>
                        <div v-for="(item, index) in tour.itinerary_items" :key="index" class="mb-6 flex gap-5">
                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-[#4DA528]/10 text-[16px] font-bold text-[#4DA528]">{{ String(index + 1).padStart(2, '0') }}</div>
                            <div>
                                <h5 class="mb-2 text-[18px] font-semibold text-[#081E2A]">{{ item.title }}</h5>
                                <div v-if="item.content" class="text-[16px] leading-[30.8px] text-[#4F545A]" v-html="item.content"></div>
                            </div>
                        </div>
                        <div v-if="tour.itinerary_items.length === 0" class="flex gap-5"><div class="content-box"><p class="text-[16px] text-[#4F545A]">No itinerary items available for this tour.</p></div></div>
                    </div>

                    <div v-show="activeTab === 'inclusions'">
                        <div class="flex flex-wrap">
                            <div class="mb-10 w-full md:w-1/2">
                                <h5 class="mb-4 text-[18px] font-semibold text-[#081E2A]">Whats Included</h5>
                                <ul v-if="tour.inclusions.length > 0" class="space-y-3">
                                    <li v-for="(inclusion, index) in tour.inclusions" :key="index" class="flex items-center gap-[19px]"><CheckCircle class="size-[18px] shrink-0 text-[#4DA528]" /><p class="text-[#4F545A]">{{ inclusion.text }}</p></li>
                                </ul>
                                <p v-else class="text-[#4F545A]">No inclusions listed.</p>
                            </div>
                            <div class="mb-10 w-full md:w-1/2">
                                <h5 class="mb-4 text-[18px] font-semibold text-[#081E2A]">Whats Excluded</h5>
                                <ul v-if="tour.exclusions.length > 0" class="space-y-3">
                                    <li v-for="(exclusion, index) in tour.exclusions" :key="index" class="flex items-center gap-[19px]"><XCircle class="size-[18px] shrink-0 text-[#ff494f]" /><p class="text-[#4F545A]">{{ exclusion.text }}</p></li>
                                </ul>
                                <p v-else class="text-[#4F545A]">No exclusions listed.</p>
                            </div>
                        </div>
                    </div>

                    <div v-show="activeTab === 'rates'">
                        <div v-if="tour.pricing_detailed" class="mb-10">
                            <span class="text-[20px] font-semibold text-[#081E2A]">Detailed Pricing:</span>
                            <div class="mt-3 text-[16px] leading-[30.8px] text-[#4F545A]" v-html="tour.pricing_detailed"></div>
                        </div>
                        <div v-else class="mb-10"><p class="text-[16px] text-[#4F545A]">Detailed pricing information is not available for this tour.</p></div>
                    </div>

                    <div v-show="activeTab === 'gallery'">
                        <div class="grid grid-cols-2 gap-6 md:grid-cols-3">
                            <div v-for="(img, index) in tour.gallery_images" :key="index" class="aspect-[4/3] cursor-pointer overflow-hidden rounded-lg" @click="openAt(index)"><img :src="img" :alt="`${tour.name} gallery image ${index + 1}`" class="h-full w-full object-cover" /></div>
                            <div v-if="tour.gallery_images.length === 0" class="aspect-[4/3] overflow-hidden rounded-lg"><img src="/template/images/gallery/gallery.jpg" alt="image" class="h-full w-full object-cover" /></div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="w-full lg:w-4/12 lg:pl-[33px] max-lg:mt-10">
                    <div class="sidebar-widget-wrapper mb-[31px] bg-[#F4F6F8] px-[30px] py-[34px]">
                        <h6 class="block-heading-tailwind mb-[22px] pl-[15px] text-[20px] font-semibold capitalize text-[#081E2A]">Enquiry & Quote</h6>
                        <p class="mb-5 text-[14px] leading-5 text-[#6b7280]">Request a booking for this tour</p>
                        <TourEnquiryFormTailwind :action="enquiryAction" :is-local="isLocal" :turnstile-site-key="turnstileSiteKey" :status="status" />
                    </div>
                    <div class="sidebar-widget-wrapper bg-[#F4F6F8] px-[30px] py-[34px]">
                        <h6 class="block-heading-tailwind mb-[22px] pl-[15px] text-[20px] font-semibold capitalize text-[#081E2A]">Book With Confidence</h6>
                        <ul class="space-y-4">
                            <li class="flex items-center gap-3 text-sm text-[#081e2a]"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#4DA528" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg><span>Customer care available 24/7</span></li>
                            <li class="flex items-center gap-3 text-sm text-[#081e2a]"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#4DA528" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg><span>Hand-picked Tours & Activities</span></li>
                            <li class="flex items-center gap-3 text-sm text-[#081e2a]"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#4DA528" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg><span>Free Travel Insurance</span></li>
                            <li class="flex items-center gap-3 text-sm text-[#081e2a]"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#4DA528" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg><span>No-hassle best price guarantee</span></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Seasonal Recommendations -->
    <section v-if="seasonalRecommendations.length > 0" class="mt-[70px] mb-10">
        <div class="mx-auto max-w-[1320px] px-4 sm:px-6 lg:px-8">
            <h3 class="mb-8 text-center text-[42px] font-['Yesteryear'] text-[#4DA528]">Seasonal Recommendations</h3>
            <div class="seasonal-carousel-wrap relative">
                <Swiper :modules="[Autoplay, Navigation, Pagination]" :pagination="{ clickable: true }" :navigation="{ nextEl: '.seasonal-swiper-next', prevEl: '.seasonal-swiper-prev' }" :slides-per-view="3" :space-between="24" :grab-cursor="true" :breakpoints="{ 320: { slidesPerView: 1, spaceBetween: 16 }, 640: { slidesPerView: 2, spaceBetween: 20 }, 992: { slidesPerView: 3, spaceBetween: 24 } }" :loop="seasonalRecommendations.length > 3" :autoplay="{ delay: 3500, disableOnInteraction: false }" class="seasonal-swiper pb-14">
                    <SwiperSlide v-for="recommendation in seasonalRecommendations" :key="recommendation.id" class="!h-auto">
                        <TourCardTailwind :tour="recommendation" />
                        <div class="h-12"></div>
                    </SwiperSlide>
                </Swiper>
                <button v-if="seasonalRecommendations.length > 3" class="swiper-custom-arrow swiper-custom-prev seasonal-swiper-prev !h-[52px] !w-[52px]" aria-label="Previous"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6" /></svg></button>
                <button v-if="seasonalRecommendations.length > 3" class="swiper-custom-arrow swiper-custom-next seasonal-swiper-next !h-[52px] !w-[52px]" aria-label="Next"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6" /></svg></button>
            </div>
        </div>
    </section>

    <!-- Modals -->
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
                        <li v-for="entry in uniqueCountries" :key="entry.name" class="flex items-center gap-3 border-b border-[#eee] px-0 py-[10px] text-[15px] leading-relaxed text-[#666] last:border-b-0">
                            <img v-if="entry.code" :src="`https://flagcdn.com/w40/${entry.code.toLowerCase()}.png`" :alt="entry.name" class="h-5 w-auto rounded-sm" />
                            {{ entry.name }}
                        </li>
                    </ul>
                </div>
            </div>
            <div
                v-if="showPlacesModal"
                class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/60 p-5"
                @click.self="showPlacesModal = false"
            >
                <div class="relative w-full max-w-[400px] rounded-2xl bg-white p-[30px] shadow-2xl">
                    <button class="absolute top-4 right-4 cursor-pointer border-none bg-transparent text-[28px] leading-none text-[#666] transition-colors hover:text-[#333]" @click="showPlacesModal = false">&times;</button>
                    <h3 class="mb-1 text-[22px] font-semibold text-[#081e2a]">Places</h3>
                    <p class="mb-4 text-sm text-[#888]">{{ tour.name }}</p>
                    <ul class="m-0 list-none p-0">
                        <li v-for="place in uniquePlaces" :key="place" class="border-b border-[#eee] px-0 py-[10px] text-[15px] leading-relaxed text-[#666] last:border-b-0">{{ place }}</li>
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
                    <h3 class="mb-1 text-[22px] font-semibold text-[#081e2a]">Tour Categories</h3>
                    <p class="mb-4 text-sm text-[#888]">{{ tour.name }}</p>
                    <ul class="m-0 list-none p-0">
                        <li v-for="category in uniqueCategoryNames" :key="category" class="border-b border-[#eee] px-0 py-[10px] text-[15px] leading-relaxed text-[#666] last:border-b-0">{{ category }}</li>
                    </ul>
                </div>
            </div>
        </Teleport>
    </template>
</template>

<style scoped>
.tour-gallery-swiper-wrap { position: relative; }
.tour-gallery-swiper :deep(.swiper-pagination) { bottom: 0; }
.tour-gallery-swiper :deep(.swiper-pagination-bullet-active) { background: #4da528; }
.tour-gallery-swiper :deep(.swiper-pagination-bullet-active::before) { display: none; }

.seasonal-swiper :deep(.swiper-pagination) { bottom: 0; }
.seasonal-swiper :deep(.swiper-pagination-bullet-active) { background: #4da528; }
.seasonal-swiper :deep(.swiper-pagination-bullet-active::before) { display: none; }

.swiper-custom-arrow { position: absolute; top: 42%; transform: translateY(-50%); width: 38px; height: 38px; padding: 0; background: rgba(255,255,255,0.92); border: none; border-radius: 50%; box-shadow: 0 1px 6px rgba(0,0,0,0.18); color: #081e2a; display: flex; align-items: center; justify-content: center; cursor: pointer; z-index: 2; opacity: 0; transition: opacity 0.25s ease, background 0.2s; }
.tour-gallery-swiper-wrap:hover .swiper-custom-arrow, .seasonal-carousel-wrap:hover .swiper-custom-arrow { opacity: 1; }
.swiper-custom-arrow:hover { background: #ffffff; }
.swiper-custom-prev { left: 8px; }
.swiper-custom-next { right: 8px; }
.seasonal-carousel-wrap .seasonal-swiper-prev { left: -70px; }
.seasonal-carousel-wrap .seasonal-swiper-next { right: -70px; }
@media (hover: none) { .swiper-custom-arrow { opacity: 0.9; } }
@media (max-width: 1299px) { .seasonal-carousel-wrap .swiper-custom-arrow { width: 44px; height: 44px; } .seasonal-carousel-wrap .swiper-custom-arrow svg { width: 20px; height: 20px; } .seasonal-carousel-wrap .seasonal-swiper-prev { left: -70px; } .seasonal-carousel-wrap .seasonal-swiper-next { right: -70px; } }

.block-heading-tailwind {
    position: relative;
}

.block-heading-tailwind::before {
    position: absolute;
    content: '';
    height: 100%;
    width: 2px;
    background-color: #4DA528;
    left: 0;
}
</style>
