<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import destinations from '@/routes/destinations';

defineProps<{
    countries: Array<{
        id: number;
        name: string;
        slug: string;
        main_image: string | null;
        overview: string | null;
        places: Array<{ id: number; name: string; slug: string }>;
    }>;
}>();

const gridSizes = ['col-md-5', 'col-md-4', 'col-md-3', 'col-md-3', 'col-md-5', 'col-md-4'];

function getGridClass(index: number): string {
    return gridSizes[index % gridSizes.length];
}
</script>

<template>
    <Head title="Destinations" />

    <section class="breadcumb-section">
        <div class="tf-container">
            <div class="row">
                <div class="col-lg-12 center z-index1">
                    <h1 class="title">Destinations</h1>
                    <ul class="breadcumb-list flex-five">
                        <li><a href="/">Home</a></li>
                        <li><span>Destinations</span></li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <section class="tour-destination pd-main">
        <div class="tf-container">
            <div class="row">
                <div
                    v-for="(country, index) in countries"
                    :key="country.id"
                    :class="['mb-37', getGridClass(index)]"
                >
                    <Link
                        :href="destinations.country({ country: country.slug })"
                        class="destination-style relative"
                    >
                        <span class="tour">{{ country.places.length }} places</span>
                        <img
                            v-if="country.main_image"
                            :src="country.main_image"
                            :alt="country.name"
                        />
                        <div
                            v-else
                            class="flex h-full w-full items-center justify-center bg-gray-200"
                        >
                            <span class="text-gray-400 text-4xl">{{ country.name.charAt(0) }}</span>
                        </div>
                        <div class="destination-content">
                            <div class="travel font-yes text-white">Travel to</div>
                            <p class="text-white">{{ country.name }}</p>
                        </div>
                    </Link>
                </div>
            </div>
        </div>
    </section>
</template>
