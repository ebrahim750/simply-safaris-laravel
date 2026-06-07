<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';
import CountryMap from '@/components/CountryMap.vue';
import { home } from '@/routes';
import destinations from '@/routes/destinations';
import tourRoutes from '@/routes/tours';
import { DollarSign, CalendarCheck, Clock, Globe, Plug, Car, Wallet } from 'lucide-vue-next';

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
const showOverviewModal = ref(false);
const overviewRef = ref<HTMLElement | null>(null);
const isOverviewTruncated = ref(false);

const checkOverviewOverflow = () => {
    if (overviewRef.value) {
        isOverviewTruncated.value = overviewRef.value.scrollHeight > overviewRef.value.clientHeight;
    }
};

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
    checkOverviewOverflow();
    window.addEventListener('resize', checkOverviewOverflow);

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
        window.removeEventListener('resize', checkOverviewOverflow);
    };
});
</script>

<template>
    <Head :title="country.name" />

    <!-- Hero Section -->
    <section class="page-title">
        <div v-if="country.main_image" class="hero-image">
            <img :src="country.main_image" :alt="country.name" />
            <div class="hero-overlay"></div>
        </div>
        <div class="tf-container full">
            <div class="row align-items-stretch">
                <div class="col-lg-9">
                    <div class="page-title-inner">
                        <div class="title">{{ country.name }}</div>
                        <ul class="breadcrumbs">
                            <li><Link :href="home()">Home</Link></li>
                            <li><Link :href="destinations.index()">Destinations</Link></li>
                            <li><span>{{ country.name }}</span></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="hero-sidebar">
                        <div class="map-card">
                            <div class="map-wrapper">
                                <CountryMap
                                    :country-code="country.country_code || ''"
                                    :country-slug="country.slug"
                                />
                            </div>
                            <div class="btn-maps-wrapper">
                                <a
                                    :href="`https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(country.name)}`"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="btn-maps"
                                >
                                    Open Map
                                </a>
                            </div>
                        </div>
                        <div v-if="country.overview" ref="overviewRef" class="hero-overview">
                            <div v-html="country.overview"></div>
                        </div>
                        <a
                            v-if="isOverviewTruncated"
                            href="#"
                            class="read-more-link"
                            @click.prevent="showOverviewModal = true"
                        >
                            Read more
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Country Info Section -->
    <section class="section-country-info">
        <div class="tf-container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="country-overview-v2">
                        <h2>Overview</h2>

                        <div class="overview-grid">
                            <div class="overview-item">
                                <DollarSign class="overview-icon" :size="28" style="color: #4da528;" />
                                <div class="overview-content">
                                    <h4>Rates</h4>
                                    <p>{{ rates_range ?? '-' }}</p>
                                </div>
                            </div>

                            <div class="overview-item">
                                <CalendarCheck class="overview-icon" :size="28" style="color: #4da528;" />
                                <div class="overview-content">
                                    <h4>Best time to go</h4>
                                    <p>{{ country.best_time_to_go ?? '-' }}</p>
                                </div>
                            </div>

                            <div class="overview-item">
                                <CalendarCheck class="overview-icon" :size="28" style="color: #4da528;" />
                                <div class="overview-content">
                                    <h4>High Season</h4>
                                    <p>{{ country.high_season ?? '-' }}</p>
                                </div>
                            </div>

                            <div class="overview-item">
                                <Clock class="overview-icon" :size="28" style="color: #4da528;" />
                                <div class="overview-content">
                                    <h4>Time Zone</h4>
                                    <p>{{ country.timezone ?? '-' }}</p>
                                </div>
                            </div>

                            <div class="overview-item">
                                <Globe class="overview-icon" :size="28" style="color: #4da528;" />
                                <div class="overview-content">
                                    <h4>Language</h4>
                                    <p>{{ country.languages ?? '-' }}</p>
                                </div>
                            </div>

                            <div class="overview-item">
                                <Plug class="overview-icon" :size="28" style="color: #4da528;" />
                                <div class="overview-content">
                                    <h4>Electrical</h4>
                                    <template v-if="country.electrical">
                                        <p>{{ country.electrical.voltage }}</p>
                                        <p v-if="country.electrical.plugs"><strong>{{ country.electrical.plugs }}</strong></p>
                                    </template>
                                    <p v-else>-</p>
                                </div>
                            </div>

                            <div class="overview-item">
                                <Wallet class="overview-icon" :size="28" style="color: #4da528;" />
                                <div class="overview-content">
                                    <h4>Currency</h4>
                                    <p>{{ country.currency ?? '-' }}</p>
                                </div>
                            </div>

                            <div class="overview-item">
                                <Car class="overview-icon" :size="28" style="color: #4da528;" />
                                <div class="overview-content">
                                    <h4>Driving Side</h4>
                                    <p>{{ country.driving_side ?? '-' }}</p>
                                </div>
                            </div>

                            <div class="overview-item">
                                <CalendarCheck class="overview-icon" :size="28" style="color: #4da528;" />
                                <div class="overview-content">
                                    <h4>Tourist Info</h4>
                                    <p v-if="country.tourism_link">
                                        <a
                                            :href="country.tourism_link"
                                            target="_blank"
                                            rel="noopener noreferrer"
                                            class="tourism-link"
                                        >
                                            <strong>{{ country.name }} Tourism Board</strong>
                                        </a>
                                    </p>
                                    <p v-else>-</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Climate Section -->
    <section v-if="country.climate" class="section-climate">
        <div class="tf-container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="climate-inner">
                        <h2>Climate</h2>

                        <div class="climate-grid">
                            <!-- Month headers -->
                            <div class="climate-row climate-header">
                                <div class="climate-label"></div>
                                <div
                                    v-for="month in country.climate.months"
                                    :key="month"
                                    class="climate-cell"
                                >
                                    {{ month }}
                                </div>
                            </div>

                            <!-- Max temp -->
                            <div class="climate-row">
                                <div class="climate-label">Maximum daytime temperature °C</div>
                                <div
                                    v-for="(temp, index) in country.climate.max_temp"
                                    :key="'temp-' + index"
                                    class="climate-cell"
                                >
                                    <span
                                        v-if="temp !== null"
                                        class="climate-badge climate-badge-red"
                                    >
                                        {{ temp }}
                                    </span>
                                    <span v-else class="climate-dash">-</span>
                                </div>
                            </div>

                            <!-- Sunshine hours -->
                            <div class="climate-row">
                                <div class="climate-label">Hours of sunshine (daily)</div>
                                <div
                                    v-for="(hours, index) in country.climate.sunshine_hours"
                                    :key="'sun-' + index"
                                    class="climate-cell"
                                >
                                    <span
                                        v-if="hours !== null"
                                        class="climate-badge climate-badge-yellow"
                                    >
                                        {{ hours }}
                                    </span>
                                    <span v-else class="climate-dash">-</span>
                                </div>
                            </div>

                            <!-- Rainfall days -->
                            <div class="climate-row">
                                <div class="climate-label">Days with some rainfall</div>
                                <div
                                    v-for="(days, index) in country.climate.rainfall_days"
                                    :key="'rain-' + index"
                                    class="climate-cell"
                                >
                                    <span
                                        v-if="days !== null"
                                        class="climate-badge climate-badge-blue"
                                    >
                                        {{ days }}
                                    </span>
                                    <span v-else class="climate-dash">-</span>
                                </div>
                            </div>

                            <!-- Sea temp -->
                            <div v-if="country.climate.sea_temp" class="climate-row">
                                <div class="climate-label">Sea temperature °C</div>
                                <div
                                    v-for="(temp, index) in country.climate.sea_temp"
                                    :key="'sea-' + index"
                                    class="climate-cell"
                                >
                                    <span
                                        v-if="temp !== null"
                                        class="climate-badge climate-badge-red"
                                    >
                                        {{ temp }}
                                    </span>
                                    <span v-else class="climate-dash">-</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Places Section with Sidebar -->
    <section class="section-places">
        <div class="tf-container">
            <div class="row">
                <!-- Sticky Sidebar -->
                <div class="col-lg-3">
                    <div class="places-sidebar">
                        <div class="sidebar-inner">
                            <h4>Destinations in {{ country.name }}</h4>
                            <nav class="places-nav">
                                <ul>
                                    <li
                                        v-for="place in country.places"
                                        :key="place.id"
                                        :class="{ active: activeSection === place.slug }"
                                    >
                                        <a
                                            href="javascript:void(0)"
                                            @click.prevent="scrollToSection(place.slug)"
                                        >
                                            {{ place.name }}
                                        </a>
                                    </li>
                                </ul>
                            </nav>

                        </div>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="col-lg-9">
                    <div class="places-content">
                        <div
                            v-for="place in country.places"
                            :id="`place-${place.slug}`"
                            :key="place.id"
                            class="place-section"
                        >
                            <div class="place-card">
                                <div class="row">
                                    <!-- Place Image (Left) -->
                                    <div class="col-md-5">
                                        <div class="place-image-wrapper">
                                            <div
                                                v-if="place.main_image"
                                                class="place-image"
                                                :style="{ backgroundImage: `url(${place.main_image})` }"
                                            ></div>
                                            <div
                                                v-else
                                                class="place-image placeholder"
                                            >
                                                <span>{{ place.name.charAt(0) }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Place Content (Right) -->
                                    <div class="col-md-7">
                                        <div class="place-details">
                                            <div class="place-title-row">
                                                <h3 class="place-name">{{ place.name }}</h3>
                                                <a href="#" class="inline-flex shrink-0 items-center rounded-lg bg-[#4da528]/15 px-3 py-1.5 font-medium text-[#4da528] transition-colors hover:bg-[#4da528]/25" style="font-size: 13px;">View tours</a>
                                            </div>
                                            <div v-if="place.overview" class="place-overview">
                                                <div v-html="place.overview"></div>
                                            </div>

                                            <!-- Related Tours -->
                                            <div v-if="place.tours.length > 0" class="place-tours">
                                                <h5 class="tours-heading">
                                                    Tours in {{ place.name }}
                                                </h5>
                                                <div class="tours-grid">
                                                    <div
                                                        v-for="tour in place.tours"
                                                        :key="tour.id"
                                                        class="tour-card"
                                                    >
                                                        <Link
                                                            :href="tourRoutes.show({ tour: tour.id })"
                                                            class="tour-link"
                                                        >
                                                            <div class="tour-image">
                                                                <img
                                                                    v-if="tour.main_image"
                                                                    :src="tour.main_image"
                                                                    :alt="tour.name"
                                                                />
                                                                <img
                                                                    v-else
                                                                    src="/template/images/page/no-image.jpg"
                                                                    :alt="tour.name"
                                                                />
                                                            </div>
                                                            <div class="tour-info">
                                                                <h6 class="tour-title">{{ tour.name }}</h6>
                                                                <div class="tour-meta">
                                                                    <span v-if="tour.duration_days" class="tour-duration">
                                                                        <i class="icon-time-left"></i>
                                                                        {{ tour.duration_days }} {{ tour.duration_days === 1 ? 'day' : 'days' }}
                                                                    </span>
                                                                    <span class="tour-price">{{ tour.price }}</span>
                                                                </div>
                                                            </div>
                                                        </Link>
                                                    </div>
                                                </div>
                                                <div v-if="place.tours_count > 4" class="view-all-tours">
                                                    <a href="javascript:void(0)" class="btn-view-all">
                                                        View {{ place.tours_count - 4 }} More Tours
                                                        <i class="icon-arrow-right"></i>
                                                    </a>
                                                </div>
                                            </div>
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

    <!-- Overview Modal -->
    <div v-if="showOverviewModal" class="modal-overlay" @click.self="showOverviewModal = false">
        <div class="modal-content">
            <button class="modal-close" @click="showOverviewModal = false">×</button>
            <h3>About {{ country.name }}</h3>
            <div class="modal-body" v-html="country.overview"></div>
        </div>
    </div>
</template>

<style scoped>
.page-title {
    position: relative;
    min-height: 600px;
    display: flex;
    align-items: stretch;
    background: #f8f9fa;
    padding-bottom: 0;
}

.hero-image {
    position: absolute;
    top: 0;
    left: 0;
    width: 75%;
    height: 100%;
    z-index: 0;
}

.hero-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.hero-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(180deg, rgba(0, 0, 0, 0.4) 0%, rgba(0, 0, 0, 0.6) 50%, rgba(0, 0, 0, 0.8) 100%);
}

.page-title .row {
    width: 100%;
    margin: 0;
}

.page-title .row > .col-lg-9 {
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    padding-top: 60px;
    padding-bottom: 60px;
}

.page-title-inner {
    position: relative;
    z-index: 2;
}

.page-title-inner .title {
    color: #fff;
    font-size: 64px;
    font-weight: 700;
    margin-bottom: 20px;
    text-shadow: 0 2px 20px rgba(0, 0, 0, 0.5);
}

.breadcrumbs {
    justify-content: center;
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 12px;
}

.breadcrumbs li {
    display: flex;
    align-items: center;
    gap: 12px;
}

.breadcrumbs li:not(:last-child)::after {
    content: '/';
    color: rgba(255, 255, 255, 0.6);
}

.breadcrumbs li a {
    color: rgba(255, 255, 255, 0.9);
    text-decoration: none;
    transition: color 0.3s ease;
    font-size: 16px;
}

.breadcrumbs li a:hover {
    color: #4da528;
}

.breadcrumbs li span {
    color: rgba(255, 255, 255, 0.7);
    font-size: 16px;
}

.page-title .row > .col-lg-3 {
    display: flex;
    align-items: flex-start;
}

.hero-sidebar {
    position: relative;
    z-index: 2;
    width: 100%;
    padding-top: 0;
    padding-bottom: 40px;
}

.map-card {
    background: #fff;
    border-radius: 16px 16px 0 0;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    padding: 0 0 16px;
    margin-top: 30px;
    margin-bottom: 16px;
}

.hero-sidebar .map-wrapper {
    margin-bottom: 12px;
}

.hero-sidebar :deep(.country-map) {
    aspect-ratio: 3 / 2;
    height: auto;
    border-radius: 12px;
}

.btn-maps-wrapper {
    text-align: center;
}

.btn-maps {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    padding: 6px 14px;
    background: #4da528;
    color: #fff;
    text-align: center;
    border-radius: 9999px;
    text-decoration: none;
    font-weight: 500;
    font-size: 12px;
    transition: background 0.3s ease;
}

.btn-maps:hover {
    background: #3d8a20;
}

.hero-overview {
    color: #666;
    line-height: 1.7;
    font-size: 14px;
    display: -webkit-box;
    -webkit-line-clamp: 5;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.hero-overview :deep(p) {
    margin: 0 0 12px;
}

.hero-overview :deep(p:last-child) {
    margin-bottom: 0;
}

.read-more-link {
    display: inline-block;
    margin-top: 8px;
    color: #4da528;
    font-size: 14px;
    font-weight: 500;
    text-decoration: none;
    transition: color 0.3s ease;
}

.read-more-link:hover {
    color: #3d8a20;
    text-decoration: underline;
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
    max-width: 600px;
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
    margin-bottom: 16px;
    color: #081e2a;
}

.modal-body {
    color: #666;
    line-height: 1.8;
    font-size: 15px;
}

.modal-body :deep(p) {
    margin: 0 0 12px;
}

.modal-body :deep(p:last-child) {
    margin-bottom: 0;
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

@media (max-width: 991px) {
    .hero-image {
        width: 100%;
    }

    .page-title .row > .col-lg-9 {
        padding-top: 80px;
        padding-bottom: 40px;
    }

    .hero-sidebar {
        margin-top: 0;
        margin-bottom: 40px;
    }

    .page-title-inner .title {
        font-size: 48px;
    }
}

/* Country Info */
.section-country-info {
    padding: 60px 0;
    background: #fff;
}

.country-overview-v2 {
    margin-bottom: 50px;
}

.country-overview-v2 h2 {
    font-size: 28px;
    font-weight: 700;
    margin-bottom: 20px;
    color: #081e2a;
}

.country-overview-v2 .overview-text {
    color: #555;
    line-height: 1.8;
    font-size: 16px;
    margin: 0 0 40px;
    max-width: 900px;
}

.overview-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 40px;
}

.overview-item {
    display: flex;
    align-items: flex-start;
    gap: 16px;
}

.overview-icon {
    flex-shrink: 0;
    color: #4a5d4a;
    margin-top: 2px;
}

.overview-content h4 {
    font-size: 18px;
    font-weight: 600;
    margin-bottom: 6px;
    color: #081e2a;
}

.overview-content p {
    color: #666;
    line-height: 1.6;
    margin: 0;
    font-size: 15px;
}

.overview-content p strong {
    color: #4a5d4a;
    font-weight: 600;
}

.tourism-link {
    color: #4a5d4a;
    text-decoration: none;
}

.tourism-link:hover {
    text-decoration: underline;
}

@media (max-width: 991px) {
    .overview-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 30px;
    }
}

@media (max-width: 767px) {
    .overview-grid {
        grid-template-columns: 1fr;
        gap: 24px;
    }
}

/* Places Section */
.section-places {
    padding: 80px 0;
}

/* Sidebar */
.places-sidebar {
    position: sticky;
    top: 120px;
}

.sidebar-inner {
    background: #fff;
    border-radius: 12px;
    padding: 30px;
    box-shadow: 0 2px 20px rgba(0, 0, 0, 0.06);
}

.sidebar-inner h4 {
    font-size: 20px;
    font-weight: 600;
    margin-bottom: 20px;
    color: #081e2a;
}

.places-nav ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.places-nav li {
    margin-bottom: 8px;
}

.places-nav li a {
    display: block;
    padding: 10px 15px;
    color: #666;
    text-decoration: none;
    border-radius: 8px;
    transition: all 0.3s ease;
    font-size: 15px;
}

.places-nav li a:hover {
    background: #f0f7ed;
    color: #4da528;
}

.places-nav li.active a {
    background: #4da528;
    color: #fff;
}

.widget-link {
    margin-top: 20px;
    padding-top: 20px;
    border-top: 1px solid #eee;
}

.widget-link .btn {
    display: block;
    width: 100%;
    padding: 12px 20px;
    background: #4da528;
    color: #fff;
    text-align: center;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 500;
    transition: background 0.3s ease;
}

.widget-link .btn:hover {
    background: #3d8a20;
}

/* Place Sections */
.place-section {
    margin-bottom: 60px;
}

.place-card {
    background: #fff;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 4px 30px rgba(0, 0, 0, 0.08);
}

.place-image-wrapper {
    height: 100%;
    min-height: 350px;
}

.place-image {
    width: 100%;
    height: 100%;
    min-height: 350px;
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    transition: transform 0.5s ease;
}

.place-card:hover .place-image {
    transform: scale(1.05);
}

.place-image.placeholder {
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.place-image.placeholder span {
    font-size: 72px;
    font-weight: 700;
    color: rgba(255, 255, 255, 0.9);
}

.place-details {
    padding: 40px;
}

.place-title-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    margin-bottom: 15px;
}

.place-name {
    font-size: 28px;
    font-weight: 700;
    margin: 0;
    color: #081e2a;
}



.place-overview {
    color: #666;
    line-height: 1.8;
    margin-bottom: 25px;
    font-size: 15px;
}

/* Tours */
.tours-heading {
    font-size: 18px;
    font-weight: 600;
    margin-bottom: 20px;
    color: #081e2a;
}

.tours-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 20px;
}

.tour-card {
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 15px rgba(0, 0, 0, 0.06);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.tour-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
}

.tour-link {
    display: block;
    text-decoration: none;
    color: inherit;
}

.tour-image {
    aspect-ratio: 16 / 10;
    overflow: hidden;
}

.tour-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.tour-card:hover .tour-image img {
    transform: scale(1.1);
}

.tour-info {
    padding: 15px;
}

.tour-title {
    font-size: 15px;
    font-weight: 600;
    margin-bottom: 10px;
    color: #081e2a;
    line-height: 1.4;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.tour-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 8px;
}

.tour-duration {
    font-size: 13px;
    color: #666;
    display: flex;
    align-items: center;
    gap: 5px;
}

.tour-duration i {
    color: #4da528;
}

.tour-price {
    font-size: 15px;
    font-weight: 700;
    color: #4da528;
}

.view-all-tours {
    margin-top: 20px;
    text-align: center;
}

.btn-view-all {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 25px;
    background: transparent;
    border: 2px solid #4da528;
    color: #4da528;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-view-all:hover {
    background: #4da528;
    color: #fff;
}

/* Responsive */
@media (max-width: 991px) {
    .places-sidebar {
        position: relative;
        top: 0;
        margin-bottom: 40px;
    }

    .place-image-wrapper {
        min-height: 250px;
    }

    .place-details {
        padding: 25px;
    }

    .tours-grid {
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    }
}

@media (max-width: 767px) {
    .page-title {
        min-height: 400px;
        padding-top: 120px;
    }

    .page-title .row > .col-lg-9 {
        padding-top: 40px;
        padding-bottom: 40px;
    }

    .page-title-inner .title {
        font-size: 36px;
    }

    .hero-sidebar {
        margin-bottom: 40px;
    }

    .place-image-wrapper {
        min-height: 200px;
    }

    .tours-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

/* Climate Section */
.section-climate {
    padding: 60px 0;
    background: #f8f9fa;
}

.climate-inner h2 {
    font-size: 28px;
    font-weight: 700;
    margin-bottom: 30px;
    color: #081e2a;
}

.climate-grid {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.climate-row {
    display: grid;
    grid-template-columns: 200px repeat(12, 1fr);
    align-items: center;
    gap: 8px;
}

.climate-header .climate-cell {
    font-size: 13px;
    font-weight: 600;
    color: #666;
    text-align: center;
}

.climate-label {
    font-size: 14px;
    color: #555;
    line-height: 1.4;
    padding-right: 12px;
}

.climate-cell {
    display: flex;
    align-items: center;
    justify-content: center;
}

.climate-badge {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 38px;
    height: 38px;
    border-radius: 50%;
    font-size: 13px;
    font-weight: 600;
    color: #fff;
}

.climate-badge-red {
    background: #dc2626;
}

.climate-badge-yellow {
    background: #eab308;
    color: #1f2937;
}

.climate-badge-blue {
    background: #3b82f6;
}

.climate-dash {
    color: #ccc;
    font-size: 14px;
}

@media (max-width: 991px) {
    .climate-row {
        grid-template-columns: 140px repeat(12, 1fr);
        gap: 4px;
    }

    .climate-badge {
        width: 32px;
        height: 32px;
        font-size: 11px;
    }

    .climate-label {
        font-size: 12px;
    }
}

@media (max-width: 767px) {
    .climate-row {
        grid-template-columns: 120px repeat(12, 1fr);
        gap: 2px;
    }

    .climate-badge {
        width: 28px;
        height: 28px;
        font-size: 10px;
    }

    .climate-header .climate-cell {
        font-size: 11px;
    }
}
</style>

