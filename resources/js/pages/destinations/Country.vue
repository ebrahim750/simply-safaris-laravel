<script setup lang="ts">
import { Head } from '@inertiajs/vue3';

defineProps<{
    country: {
        id: number;
        name: string;
        slug: string;
        main_image: string | null;
        overview: string | null;
        best_time_to_go: string | null;
        high_season: string | null;
        tourism_link: string | null;
        places: Array<{ id: number; name: string; slug: string; overview: string | null; main_image: string | null }>;
    };
}>();
</script>

<template>
    <Head :title="country.name" />

    <section class="page-title">
        <div class="tf-container full">
            <div class="row">
                <div class="col-lg-12">
                    <div class="page-title-inner">
                        <div class="title">{{ country.name }}</div>
                        <ul class="breadcrumbs">
                            <li><a href="/">Home</a></li>
                            <li><a href="/destinations">Destinations</a></li>
                            <li><span>{{ country.name }}</span></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section-country-detail">
        <div class="tf-container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="content">
                        <div v-if="country.main_image" class="image">
                            <img :src="country.main_image" :alt="country.name" />
                        </div>
                        <div v-if="country.overview" class="overview">
                            <h3>Overview</h3>
                            <p>{{ country.overview }}</p>
                        </div>
                        <div v-if="country.best_time_to_go" class="best-time">
                            <h3>Best Time to Go</h3>
                            <p>{{ country.best_time_to_go }}</p>
                        </div>
                        <div v-if="country.high_season" class="high-season">
                            <h3>High Season</h3>
                            <p>{{ country.high_season }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="sidebar">
                        <div class="widget-places">
                            <h4>Places to Visit</h4>
                            <ul>
                                <li v-for="place in country.places" :key="place.id">
                                    <a :href="`/destinations/${country.slug}/${place.slug}`">
                                        {{ place.name }}
                                    </a>
                                </li>
                            </ul>
                        </div>
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
        </div>
    </section>
</template>
