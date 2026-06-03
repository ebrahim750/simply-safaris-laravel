<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { home } from '@/routes';

defineProps<{
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
        places: Array<{ id: number; name: string }>;
        itinerary_items: Array<{ title: string; content: string }>;
        inclusions: Array<{ text: string }>;
        exclusions: Array<{ text: string }>;
    };
}>();
</script>

<template>
    <Head :title="tour.name" />

    <!-- Breadcrumb -->
    <section class="breadcumb-section">
        <div class="tf-container">
            <div class="row">
                <div class="col-lg-12 center z-index1">
                    <h1 class="title">{{ tour.name }}</h1>
                    <ul class="breadcumb-list flex-five">
                        <li><Link :href="home()">Home</Link></li>
                        <li><Link href="/tours">Tours</Link></li>
                        <li><span>{{ tour.name }}</span></li>
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

    <!-- Simple Tour Detail -->
    <section class="tour-single pd-main">
        <div class="tf-container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="tour-single-content">
                        <div class="tour-single-image mb-40">
                            <img
                                v-if="tour.main_image"
                                :src="tour.main_image"
                                :alt="tour.name"
                                class="w-full"
                            />
                            <img
                                v-else
                                src="https://placehold.co/1200x600/e2e8f0/475569?text=No+Image"
                                :alt="tour.name"
                                class="w-full"
                            />
                        </div>
                        <h2 class="title-tour mb-20">{{ tour.name }}</h2>
                        <div class="tour-meta flex-three mb-30">
                            <div class="meta-item flex-three" v-if="tour.duration_days">
                                <i class="icon-time-left"></i>
                                <span
                                    >{{ tour.duration_days }}
                                    {{ tour.duration_days === 1 ? 'day' : 'days' }}</span
                                >
                            </div>
                            <div class="meta-item flex-three" v-if="tour.places.length > 0">
                                <i class="icon-Vector4"></i>
                                <span>{{ tour.places.map((p) => p.name).join(', ') }}</span>
                            </div>
                            <div class="meta-item flex-three" v-if="tour.categories.length > 0">
                                <i class="icon-Vector-22"></i>
                                <span>{{ tour.categories.map((c) => c.name).join(', ') }}</span>
                            </div>
                        </div>
                        <div v-if="tour.overview" class="tour-overview mb-40">
                            <h4 class="title-tour mb-15">Overview</h4>
                            <p class="des">{{ tour.overview }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="tour-single-sidebar">
                        <div class="widget-price mb-30">
                            <h4 class="title-tour mb-15">Price</h4>
                            <div class="price-box">
                                <span class="price">{{ tour.price }}</span>
                                <span class="per">/per person</span>
                            </div>
                        </div>
                        <div v-if="tour.show_external_booking && tour.external_url" class="widget-booking">
                            <a
                                :href="tour.external_url"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="btn-main w-full block text-center"
                            >
                                <p class="btn-main-text">
                                    {{ tour.external_link_button_text || 'Book Now' }}
                                </p>
                                <p class="iconer">
                                    <i class="icon-arrow-right"></i>
                                </p>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
