<script setup lang="ts">
import { Head } from '@inertiajs/vue3';

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
</script>

<template>
    <Head title="Destinations" />

    <section class="page-title">
        <div class="tf-container full">
            <div class="row">
                <div class="col-lg-12">
                    <div class="page-title-inner">
                        <div class="title">Destinations</div>
                        <ul class="breadcrumbs">
                            <li><a href="/">Home</a></li>
                            <li><span>Destinations</span></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section-destination">
        <div class="tf-container">
            <div class="row">
                <div
                    v-for="country in countries"
                    :key="country.id"
                    class="col-lg-4 col-md-6"
                >
                    <div class="destination-item">
                        <div class="image">
                            <img
                                v-if="country.main_image"
                                :src="country.main_image"
                                :alt="country.name"
                            />
                            <img
                                v-else
                                src="/template/images/page/no-image.jpg"
                                :alt="country.name"
                            />
                        </div>
                        <div class="content">
                            <h3>
                                <a :href="`/destinations/${country.slug}`">
                                    {{ country.name }}
                                </a>
                            </h3>
                            <p v-if="country.overview">{{ country.overview }}</p>
                            <ul v-if="country.places.length > 0" class="place-list">
                                <li v-for="place in country.places" :key="place.id">
                                    <a :href="`/destinations/${country.slug}/${place.slug}`">
                                        {{ place.name }}
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
