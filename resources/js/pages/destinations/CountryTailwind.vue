<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';
import CountryMap from '@/components/CountryMap.vue';
import { home2 } from '@/routes';
import destinations2 from '@/routes/destinations2';
import { DollarSign, CalendarCheck, Clock, Globe, Plug, Car, Wallet, Clock4 } from 'lucide-vue-next';

const props = defineProps<{
    country: {
        id: number;
        name: string;
        slug: string;
        country_code: string | null;
        main_image: string | null;
        overview: string | null;
        best_time_to_go: string | null;
        high_season: string | null;
        tourism_link: string | null;
        timezone: string | null;
        languages: string | null;
        currency: string | null;
        driving_side: string | null;
        electrical: {
            voltage: string | null;
            plugs: string | null;
        } | null;
        climate: {
            months: string[];
            max_temp: (number | null)[];
            sunshine_hours: (number | null)[];
            rainfall_days: (number | null)[];
            sea_temp: (number | null)[] | null;
        } | null;
        places: Array<{
            id: number;
            name: string;
            slug: string;
            overview: string | null;
            main_image: string | null;
            tours_count: number;
            tours: Array<{
                id: number;
                name: string;
                main_image: string | null;
                price: string;
                duration_days: number | null;
            }>;
        }>;
    };
    rates_range: string | null;
}>();

const activeSection = ref('');

const scrollToSection = (slug: string) => {
    const element = document.getElementById(`place-${slug}`);

    if (element) {
        const headerOffset = 120;
        const elementPosition = element.getBoundingClientRect().top;
        const offsetPosition = elementPosition + window.scrollY - headerOffset;

        window.scrollTo({
            top: offsetPosition,
            behavior: 'smooth',
        });
    }
};

onMounted(() => {
    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    activeSection.value = entry.target.id.replace('place-', '');
                }
            });
        },
        { rootMargin: '-120px 0px -60% 0px' }
    );

    props.country.places.forEach((place) => {
        const element = document.getElementById(`place-${place.slug}`);

        if (element) {
            observer.observe(element);
        }
    });

    // Handle hash-based scroll on page load and after Inertia navigation
    const handleHashScroll = () => {
        const hash = window.location.hash.replace('#', '');

        if (hash) {
            setTimeout(() => {
                scrollToSection(hash);
            }, 300);
        }
    };

    handleHashScroll();

    // Listen for Inertia navigation events
    const removeNavigateListener = router.on('navigate', () => {
        handleHashScroll();
    });

    return () => {
        observer.disconnect();
        removeNavigateListener();
    };
});
</script>

<template>
    <Head :title="country.name" />

    <!-- Hero Section -->
    <section class="relative h-[500px] bg-cover bg-center bg-no-repeat" :style="country.main_image ? { backgroundImage: `url(${country.main_image})` } : {}">
        <div v-if="country.main_image" class="absolute inset-0" style="background: linear-gradient(180deg, rgba(0,0,0,0.1) 0%, rgba(0,0,0,0.7) 100%);"></div>
        <div v-else class="absolute inset-0" style="background: linear-gradient(262deg, rgba(0,0,0,0.33), rgba(4,29,11,0.89));"></div>
        <div class="relative z-10 mx-auto w-full max-w-[1366px] px-4 sm:px-6 lg:px-8">
            <div class="flex flex-wrap items-stretch pt-[140px] pb-20">
                <div class="w-full lg:w-3/4">
                    <div class="pt-20 pb-10 lg:pt-[120px]">
                        <h1 class="mb-4 text-[65px] font-bold leading-[1.1] text-white max-lg:text-[48px] max-lg:text-center max-md:text-[36px]">{{ country.name }}</h1>
                        <nav class="flex items-center text-[18px] leading-[22.68px] max-lg:justify-center">
                            <Link :href="home2()" class="relative mr-[30px] text-[#4DA528] after:absolute after:right-[-18px] after:top-1/2 after:-translate-y-1/2 after:text-[10px] after:text-white after:content-['\203A']">Home</Link>
                            <Link :href="destinations2.index()" class="relative mr-[30px] text-[#4DA528] after:absolute after:right-[-18px] after:top-1/2 after:-translate-y-1/2 after:text-[10px] after:text-white after:content-['\203A']">Destinations</Link>
                            <span class="text-white">{{ country.name }}</span>
                        </nav>
                    </div>
                </div>
                <div class="w-full lg:w-1/4">
                    <div class="relative z-20 max-lg:mt-0 max-lg:mb-10">
                        <!-- Map Card -->
                        <div class="rounded-[16px] bg-white shadow-[0_2px_20px_rgba(0,0,0,0.06)]">
                            <div class="overflow-hidden rounded-t-[16px]">
                                <CountryMap
                                    :country-code="country.country_code || ''"
                                    :country-slug="country.slug"
                                />
                            </div>
                            <div class="p-4">
                                <a
                                    :href="`https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(country.name)}`"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="block w-full rounded-[8px] bg-[#4DA528] px-5 py-3 text-center text-[14px] font-medium text-white transition-colors hover:bg-[#3d8a20]"
                                >
                                    Open Map
                                </a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Country Info Section -->
    <section class="bg-white py-[60px]">
        <div class="mx-auto w-full max-w-[1366px] px-4 sm:px-6 lg:px-8">
            <div class="mb-[50px]">
                <h2 class="mb-5 text-[28px] font-bold text-[#081E2A]">Overview</h2>

                <div v-if="country.overview" class="mb-10 max-w-[900px] text-[16px] leading-[1.8] text-gray-500" v-html="country.overview"></div>

                <div class="grid grid-cols-1 gap-10 md:grid-cols-2 lg:grid-cols-3">
                    <!-- Rates -->
                    <div class="flex items-start gap-4">
                        <DollarSign class="mt-0.5 shrink-0 text-[#4da528]" :size="28" />
                        <div>
                            <h4 class="mb-1.5 text-[18px] font-semibold text-[#081E2A]">Rates</h4>
                            <p class="text-[15px] leading-[1.6] text-gray-500">{{ rates_range ?? '-' }}</p>
                        </div>
                    </div>

                    <!-- Best time to go -->
                    <div class="flex items-start gap-4">
                        <CalendarCheck class="mt-0.5 shrink-0 text-[#4da528]" :size="28" />
                        <div>
                            <h4 class="mb-1.5 text-[18px] font-semibold text-[#081E2A]">Best time to go</h4>
                            <p class="text-[15px] leading-[1.6] text-gray-500">{{ country.best_time_to_go ?? '-' }}</p>
                        </div>
                    </div>

                    <!-- High Season -->
                    <div class="flex items-start gap-4">
                        <CalendarCheck class="mt-0.5 shrink-0 text-[#4da528]" :size="28" />
                        <div>
                            <h4 class="mb-1.5 text-[18px] font-semibold text-[#081E2A]">High Season</h4>
                            <p class="text-[15px] leading-[1.6] text-gray-500">{{ country.high_season ?? '-' }}</p>
                        </div>
                    </div>

                    <!-- Time Zone -->
                    <div class="flex items-start gap-4">
                        <Clock class="mt-0.5 shrink-0 text-[#4da528]" :size="28" />
                        <div>
                            <h4 class="mb-1.5 text-[18px] font-semibold text-[#081E2A]">Time Zone</h4>
                            <p class="text-[15px] leading-[1.6] text-gray-500">{{ country.timezone ?? '-' }}</p>
                        </div>
                    </div>

                    <!-- Language -->
                    <div class="flex items-start gap-4">
                        <Globe class="mt-0.5 shrink-0 text-[#4da528]" :size="28" />
                        <div>
                            <h4 class="mb-1.5 text-[18px] font-semibold text-[#081E2A]">Language</h4>
                            <p class="text-[15px] leading-[1.6] text-gray-500">{{ country.languages ?? '-' }}</p>
                        </div>
                    </div>

                    <!-- Electrical -->
                    <div class="flex items-start gap-4">
                        <Plug class="mt-0.5 shrink-0 text-[#4da528]" :size="28" />
                        <div>
                            <h4 class="mb-1.5 text-[18px] font-semibold text-[#081E2A]">Electrical</h4>
                            <template v-if="country.electrical">
                                <p class="text-[15px] leading-[1.6] text-gray-500">{{ country.electrical.voltage }}</p>
                                <p v-if="country.electrical.plugs" class="text-[15px] leading-[1.6] text-gray-500"><strong class="text-gray-600">{{ country.electrical.plugs }}</strong></p>
                            </template>
                            <p v-else class="text-[15px] leading-[1.6] text-gray-500">-</p>
                        </div>
                    </div>

                    <!-- Currency -->
                    <div class="flex items-start gap-4">
                        <Wallet class="mt-0.5 shrink-0 text-[#4da528]" :size="28" />
                        <div>
                            <h4 class="mb-1.5 text-[18px] font-semibold text-[#081E2A]">Currency</h4>
                            <p class="text-[15px] leading-[1.6] text-gray-500">{{ country.currency ?? '-' }}</p>
                        </div>
                    </div>

                    <!-- Driving Side -->
                    <div class="flex items-start gap-4">
                        <Car class="mt-0.5 shrink-0 text-[#4da528]" :size="28" />
                        <div>
                            <h4 class="mb-1.5 text-[18px] font-semibold text-[#081E2A]">Driving Side</h4>
                            <p class="text-[15px] leading-[1.6] text-gray-500">{{ country.driving_side ?? '-' }}</p>
                        </div>
                    </div>

                    <!-- Tourist Info -->
                    <div class="flex items-start gap-4">
                        <CalendarCheck class="mt-0.5 shrink-0 text-[#4da528]" :size="28" />
                        <div>
                            <h4 class="mb-1.5 text-[18px] font-semibold text-[#081E2A]">Tourist Info</h4>
                            <p v-if="country.tourism_link" class="text-[15px] leading-[1.6] text-gray-500">
                                <a :href="country.tourism_link" target="_blank" rel="noopener noreferrer" class="font-semibold text-[#4a5d4a] no-underline hover:underline">
                                    {{ country.name }} Tourism Board
                                </a>
                            </p>
                            <p v-else class="text-[15px] leading-[1.6] text-gray-500">-</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Climate Section -->
    <section v-if="country.climate" class="bg-[#f8f9fa] py-[60px]">
        <div class="mx-auto w-full max-w-[1366px] px-4 sm:px-6 lg:px-8">
            <div>
                <h2 class="mb-[30px] text-[28px] font-bold text-[#081E2A]">Climate</h2>

                <div class="flex flex-col gap-5">
                    <!-- Header row -->
                    <div class="grid grid-cols-[200px_repeat(12,1fr)] items-center gap-[10px] px-4 pb-1 max-lg:grid-cols-[140px_repeat(12,1fr)] max-lg:gap-[6px] max-md:grid-cols-[120px_repeat(12,1fr)] max-md:gap-[3px]">
                        <div class="pr-3"></div>
                        <div
                            v-for="month in country.climate.months"
                            :key="month"
                            class="text-center text-[13px] font-semibold tracking-[0.3px] text-gray-400 max-md:text-[10px]"
                        >
                            {{ month }}
                        </div>
                    </div>

                    <!-- Max temp row -->
                    <div class="grid grid-cols-[200px_repeat(12,1fr)] items-center gap-[10px] rounded-[12px] px-4 py-[10px] transition-colors hover:bg-black/[0.02] max-lg:grid-cols-[140px_repeat(12,1fr)] max-lg:gap-[6px] max-md:grid-cols-[120px_repeat(12,1fr)] max-md:gap-[3px] max-md:px-[10px] max-md:py-2">
                        <div class="pr-3 text-[14px] font-medium text-gray-500 max-lg:text-[12px]">Maximum daytime temperature °C</div>
                        <div
                            v-for="(temp, index) in country.climate.max_temp"
                            :key="'temp-' + index"
                            class="flex items-center justify-center"
                        >
                            <span
                                v-if="temp !== null"
                                class="flex size-[42px] items-center justify-center rounded-full text-[14px] font-bold text-white shadow-[0_2px_6px_rgba(0,0,0,0.12)] transition-all duration-200 hover:scale-120 hover:shadow-[0_6px_16px_rgba(0,0,0,0.18)] cursor-default max-lg:size-[36px] max-lg:text-[12px] max-md:size-[30px] max-md:text-[10px]"
                                style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); animation: climate-pop-in 0.4s cubic-bezier(0.34, 1.56, 0.64, 1) both; animation-delay: {{ index * 0.02 }}s;"
                            >
                                {{ temp }}
                            </span>
                            <span v-else class="text-[14px] text-gray-300">-</span>
                        </div>
                    </div>

                    <!-- Sunshine hours row -->
                    <div class="grid grid-cols-[200px_repeat(12,1fr)] items-center gap-[10px] rounded-[12px] px-4 py-[10px] transition-colors hover:bg-black/[0.02] max-lg:grid-cols-[140px_repeat(12,1fr)] max-lg:gap-[6px] max-md:grid-cols-[120px_repeat(12,1fr)] max-md:gap-[3px] max-md:px-[10px] max-md:py-2">
                        <div class="pr-3 text-[14px] font-medium text-gray-500 max-lg:text-[12px]">Hours of sunshine (daily)</div>
                        <div
                            v-for="(hours, index) in country.climate.sunshine_hours"
                            :key="'sun-' + index"
                            class="flex items-center justify-center"
                        >
                            <span
                                v-if="hours !== null"
                                class="flex size-[42px] items-center justify-center rounded-full text-[14px] font-bold text-gray-800 shadow-[0_2px_6px_rgba(0,0,0,0.12)] transition-all duration-200 hover:scale-120 hover:shadow-[0_6px_16px_rgba(0,0,0,0.18)] cursor-default max-lg:size-[36px] max-lg:text-[12px] max-md:size-[30px] max-md:text-[10px]"
                                style="background: linear-gradient(135deg, #facc15 0%, #eab308 100%); animation: climate-pop-in 0.4s cubic-bezier(0.34, 1.56, 0.64, 1) both; animation-delay: {{ index * 0.02 }}s;"
                            >
                                {{ hours }}
                            </span>
                            <span v-else class="text-[14px] text-gray-300">-</span>
                        </div>
                    </div>

                    <!-- Rainfall days row -->
                    <div class="grid grid-cols-[200px_repeat(12,1fr)] items-center gap-[10px] rounded-[12px] px-4 py-[10px] transition-colors hover:bg-black/[0.02] max-lg:grid-cols-[140px_repeat(12,1fr)] max-lg:gap-[6px] max-md:grid-cols-[120px_repeat(12,1fr)] max-md:gap-[3px] max-md:px-[10px] max-md:py-2">
                        <div class="pr-3 text-[14px] font-medium text-gray-500 max-lg:text-[12px]">Days with some rainfall</div>
                        <div
                            v-for="(days, index) in country.climate.rainfall_days"
                            :key="'rain-' + index"
                            class="flex items-center justify-center"
                        >
                            <span
                                v-if="days !== null"
                                class="flex size-[42px] items-center justify-center rounded-full text-[14px] font-bold text-white shadow-[0_2px_6px_rgba(0,0,0,0.12)] transition-all duration-200 hover:scale-120 hover:shadow-[0_6px_16px_rgba(0,0,0,0.18)] cursor-default max-lg:size-[36px] max-lg:text-[12px] max-md:size-[30px] max-md:text-[10px]"
                                style="background: linear-gradient(135deg, #60a5fa 0%, #3b82f6 100%); animation: climate-pop-in 0.4s cubic-bezier(0.34, 1.56, 0.64, 1) both; animation-delay: {{ index * 0.02 }}s;"
                            >
                                {{ days }}
                            </span>
                            <span v-else class="text-[14px] text-gray-300">-</span>
                        </div>
                    </div>

                    <!-- Sea temp row -->
                    <div v-if="country.climate.sea_temp" class="grid grid-cols-[200px_repeat(12,1fr)] items-center gap-[10px] rounded-[12px] px-4 py-[10px] transition-colors hover:bg-black/[0.02] max-lg:grid-cols-[140px_repeat(12,1fr)] max-lg:gap-[6px] max-md:grid-cols-[120px_repeat(12,1fr)] max-md:gap-[3px] max-md:px-[10px] max-md:py-2">
                        <div class="pr-3 text-[14px] font-medium text-gray-500 max-lg:text-[12px]">Sea temperature °C</div>
                        <div
                            v-for="(temp, index) in country.climate.sea_temp"
                            :key="'sea-' + index"
                            class="flex items-center justify-center"
                        >
                            <span
                                v-if="temp !== null"
                                class="flex size-[42px] items-center justify-center rounded-full text-[14px] font-bold text-white shadow-[0_2px_6px_rgba(0,0,0,0.12)] transition-all duration-200 hover:scale-120 hover:shadow-[0_6px_16px_rgba(0,0,0,0.18)] cursor-default max-lg:size-[36px] max-lg:text-[12px] max-md:size-[30px] max-md:text-[10px]"
                                style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); animation: climate-pop-in 0.4s cubic-bezier(0.34, 1.56, 0.64, 1) both; animation-delay: {{ index * 0.02 }}s;"
                            >
                                {{ temp }}
                            </span>
                            <span v-else class="text-[14px] text-gray-300">-</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Places Section with Sidebar -->
    <section v-if="country.places.length > 0" class="py-[80px]">
        <div class="mx-auto w-full max-w-[1366px] px-4 sm:px-6 lg:px-8">
            <div class="flex flex-wrap">
                <!-- Sticky Sidebar -->
                <div class="w-full lg:w-1/4">
                    <div class="sticky top-[120px] max-lg:relative max-lg:top-0 max-lg:mb-10">
                        <div class="rounded-[12px] bg-white p-[30px] shadow-[0_2px_20px_rgba(0,0,0,0.06)]">
                            <h4 class="mb-5 text-[20px] font-semibold text-[#081E2A]">Destinations in {{ country.name }}</h4>
                            <nav class="places-nav">
                                <ul class="m-0 list-none p-0">
                                    <li
                                        v-for="place in country.places"
                                        :key="place.id"
                                        class="mb-2 last:mb-0"
                                        :class="{ 'active': activeSection === place.slug }"
                                    >
                                        <a
                                            :href="`#${place.slug}`"
                                            class="block rounded-[8px] px-[15px] py-[10px] text-[15px] text-gray-500 no-underline transition-all duration-300 hover:bg-[#f0f7ed] hover:text-[#4DA528]"
                                            :class="{ '!bg-[#4DA528] !text-white': activeSection === place.slug }"
                                            @click.prevent="scrollToSection(place.slug)"
                                        >
                                            {{ place.name }}
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                            <div class="mt-5 border-t border-gray-100 pt-5">
                                <Link
                                    :href="`/tours2?countries=${country.id}`"
                                    class="block w-full rounded-[8px] bg-[#4DA528] px-5 py-3 text-center text-[14px] font-medium text-white no-underline transition-colors hover:bg-[#3d8a20]"
                                >
                                    View All Tours
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Place Cards -->
                <div class="w-full lg:w-3/4 lg:pl-[30px]">
                    <div
                        v-for="place in country.places"
                        :id="`place-${place.slug}`"
                        :key="place.id"
                        class="mb-[60px] last:mb-0 scroll-mt-[140px]"
                    >
                        <div class="overflow-hidden rounded-[16px] bg-white shadow-[0_4px_30px_rgba(0,0,0,0.08)]">
                            <div class="flex flex-wrap">
                                <!-- Image -->
                                <div class="w-full md:w-2/5 lg:w-2/5">
                                    <div
                                        class="h-full min-h-[350px] bg-cover bg-center bg-no-repeat transition-transform duration-500 hover:scale-105 max-md:min-h-[250px]"
                                        :style="place.main_image ? { backgroundImage: `url(${place.main_image})` } : {}"
                                        :class="{ 'flex items-center justify-center bg-gradient-to-br from-[#667eea] to-[#764ba2]': !place.main_image }"
                                    >
                                        <span v-if="!place.main_image" class="text-[72px] font-bold text-white/90">{{ place.name.charAt(0) }}</span>
                                    </div>
                                </div>

                                <!-- Details -->
                                <div class="w-full md:w-3/5 lg:w-3/5">
                                    <div class="p-10 max-md:p-[25px]">
                                        <div class="mb-[15px] flex items-center justify-between gap-4">
                                            <h3 class="m-0 text-[28px] font-bold text-[#081E2A]">{{ place.name }}</h3>
                                            <span v-if="place.tours_count > 0" class="shrink-0 rounded-full bg-[#4DA528]/10 px-4 py-1.5 text-[13px] font-semibold text-[#4DA528]">{{ place.tours_count }} {{ place.tours_count === 1 ? 'Tour' : 'Tours' }}</span>
                                        </div>
                                        <div v-if="place.overview" class="mb-[25px] text-[15px] leading-[1.8] text-gray-500" v-html="place.overview"></div>

                                        <!-- Sub-tours -->
                                        <div v-if="place.tours.length > 0">
                                            <h4 class="mb-5 text-[18px] font-semibold text-[#081E2A]">Tours</h4>
                                            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                                                <div
                                                    v-for="tour in place.tours"
                                                    :key="tour.id"
                                                    class="overflow-hidden rounded-[12px] shadow-[0_2px_15px_rgba(0,0,0,0.06)] transition-all duration-300 hover:-translate-y-[5px] hover:shadow-[0_8px_25px_rgba(0,0,0,0.12)]"
                                                >
                                                    <Link
                                                        :href="`/tours2/${tour.id}`"
                                                        class="block text-inherit no-underline"
                                                    >
                                                        <div class="aspect-[16/10] overflow-hidden">
                                                            <img
                                                                v-if="tour.main_image"
                                                                :src="tour.main_image"
                                                                :alt="tour.name"
                                                                class="size-full object-cover transition-transform duration-500 hover:scale-110"
                                                            />
                                                            <div v-else class="flex size-full items-center justify-center bg-gray-100 text-gray-400">No image</div>
                                                        </div>
                                                        <div class="p-[15px]">
                                                            <h5 class="mb-[10px] text-[15px] font-semibold leading-[1.4] text-[#081E2A] line-clamp-2">{{ tour.name }}</h5>
                                                            <div class="flex flex-wrap items-center justify-between gap-2">
                                                                <span v-if="tour.duration_days" class="inline-flex items-center gap-1 text-[13px] text-gray-500">
                                                                    <Clock4 class="size-3.5 text-[#4DA528]" />
                                                                    {{ tour.duration_days }} {{ tour.duration_days === 1 ? 'day' : 'days' }}
                                                                </span>
                                                                <span class="text-[15px] font-bold text-[#4DA528]">{{ tour.price }}</span>
                                                            </div>
                                                        </div>
                                                    </Link>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- View all tours link -->
                                        <div v-if="place.tours_count > place.tours.length" class="mt-5 text-center">
                                            <Link
                                                :href="`/tours2?countries=${country.id}&places=${place.id}`"
                                                class="inline-flex items-center gap-2 rounded-[8px] border-2 border-[#4DA528] bg-transparent px-[25px] py-3 text-[14px] font-medium text-[#4DA528] no-underline transition-all hover:bg-[#4DA528] hover:text-white"
                                            >
                                                View All Tours
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                                            </Link>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

</template>

<style scoped>
@keyframes climate-pop-in {
    0% {
        opacity: 0;
        transform: scale(0.6);
    }
    70% {
        transform: scale(1.08);
    }
    100% {
        opacity: 1;
        transform: scale(1);
    }
}
</style>
