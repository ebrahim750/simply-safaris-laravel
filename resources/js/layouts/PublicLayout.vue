<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import PublicHeader from '@/components/public/PublicHeader.vue';
import PublicFooter from '@/components/public/PublicFooter.vue';
</script>

<template>
    <Head>
        <link rel="stylesheet" href="/template/css/app.css" />
        <link rel="stylesheet" href="/template/fonts/style.css" />
        <link rel="stylesheet" href="/template/css/magnific-popup.css" />
        <link rel="stylesheet" href="/template/css/jquery.fancybox.min.css" />
        <link rel="stylesheet" href="/template/css/textanimation.css" />
    </Head>

    <div class="body header-fixed counter-scroll">
        <PublicHeader />

        <main id="main">
            <slot />
        </main>

        <PublicFooter />
    </div>
</template>

<style>
.wow {
    visibility: hidden;
}
.wow.animated {
    visibility: visible;
}
</style>


