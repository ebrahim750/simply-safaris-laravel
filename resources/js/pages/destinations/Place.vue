<script setup lang="ts">
import { Head } from '@inertiajs/vue3';

defineProps<{
    country: {
        id: number;
        name: string;
        slug: string;
    };
    place: {
        id: number;
        name: string;
        slug: string;
        overview: string | null;
        main_image: string | null;
        country: {
            id: number;
            name: string;
            slug: string;
        };
    };
}>();
</script>

<template>
    <Head :title="place.name" />

    <section class="page-title">
        <div class="tf-container full">
            <div class="row">
                <div class="col-lg-12">
                    <div class="page-title-inner">
                        <div class="title">{{ place.name }}</div>
                        <ul class="breadcrumbs">
                            <li><a href="/">Home</a></li>
                            <li><a href="/destinations">Destinations</a></li>
                            <li>
                                <a :href="`/destinations/${country.slug}`">{{ country.name }}</a>
                            </li>
                            <li><span>{{ place.name }}</span></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section-place-detail">
        <div class="tf-container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="content">
                        <div v-if="place.main_image" class="image">
                            <img :src="place.main_image" :alt="place.name" />
                        </div>
                        <div v-if="place.overview" class="overview">
                            <h3>About {{ place.name }}</h3>
                            <p>{{ place.overview }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="sidebar">
                        <div class="widget-country">
                            <h4>Country</h4>
                            <p>
                                <a :href="`/destinations/${country.slug}`">{{ country.name }}</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
