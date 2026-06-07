<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';
import { home } from '@/routes';
import destinations from '@/routes/destinations';
import tourRoutes from '@/routes/tours';

const props = defineProps<{
    country: {
        id: number;
        name: string;
        slug: string;
        main_image: string | null;
        overview: string | null;
        best_time_to_go: string | null;
        high_season: string | null;
        tourism_link: string | null;
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
    <section class="page-title">
        <div v-if="country.main_image" class="hero-image">
            <img :src="country.main_image" :alt="country.name" />
            <div class="hero-overlay"></div>
        </div>
        <div class="tf-container full">
            <div class="row">
                <div class="col-lg-12">
                    <div class="page-title-inner">
                        <div class="title">{{ country.name }}</div>
                        <ul class="breadcrumbs">
                            <li><Link :href="home()">Home</Link></li>
                            <li><Link :href="destinations.index()">Destinations</Link></li>
                            <li><span>{{ country.name }}</span></li>
                        </ul>
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
                    <div class="country-info-grid">
                        <div v-if="country.overview" class="info-card">
                            <div class="info-icon">
                                <i class="icon-15"></i>
                            </div>
                            <h4>Overview</h4>
                            <p>{{ country.overview }}</p>
                        </div>
                        <div v-if="country.best_time_to_go" class="info-card">
                            <div class="info-icon">
                                <i class="icon-17"></i>
                            </div>
                            <h4>Best Time to Go</h4>
                            <p>{{ country.best_time_to_go }}</p>
                        </div>
                        <div v-if="country.high_season" class="info-card">
                            <div class="info-icon">
                                <i class="icon-18"></i>
                            </div>
                            <h4>High Season</h4>
                            <p>{{ country.high_season }}</p>
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
                            <h4>Places in {{ country.name }}</h4>
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
                            <div v-if="country.tourism_link" class="widget-link">
                                <a
                                    :href="country.tourism_link"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="btn"
                                >
                                    Tourism Information
                                </a>
                            </div>
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
                                            <h3 class="place-name">{{ place.name }}</h3>
                                            <p v-if="place.overview" class="place-overview">
                                                {{ place.overview }}
                                            </p>

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
</template>

<style scoped>
.hero-image {
    position: absolute;
    inset: 0;
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

.page-title {
    position: relative;
    min-height: 600px;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    padding-top: 140px;
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

@media (max-width: 767px) {
    .page-title {
        min-height: 400px;
        padding-top: 120px;
    }

    .page-title-inner .title {
        font-size: 42px;
    }
}

/* Country Info */
.section-country-info {
    padding: 60px 0;
    background: #f8f9fa;
}

.country-info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 30px;
}

.info-card {
    background: #fff;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 2px 20px rgba(0, 0, 0, 0.06);
}

.info-icon {
    width: 50px;
    height: 50px;
    background: #4da528;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 15px;
}

.info-icon i {
    color: #fff;
    font-size: 20px;
}

.info-card h4 {
    font-size: 20px;
    font-weight: 600;
    margin-bottom: 10px;
    color: #081e2a;
}

.info-card p {
    color: #666;
    line-height: 1.7;
    margin: 0;
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

.place-name {
    font-size: 28px;
    font-weight: 700;
    margin-bottom: 15px;
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
    .page-title-inner .title {
        font-size: 36px;
    }

    .place-image-wrapper {
        min-height: 200px;
    }

    .tours-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}
</style>

