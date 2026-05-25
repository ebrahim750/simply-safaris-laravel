<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { onMounted, onUnmounted, ref } from 'vue';
import PublicHeader from '@/components/public/PublicHeader.vue';
import PublicFooter from '@/components/public/PublicFooter.vue';

const showBackToTop = ref(false);

const handleScroll = () => {
    showBackToTop.value = window.scrollY > 300;
};

const scrollToTop = () => {
    window.scrollTo({ top: 0, behavior: 'smooth' });
};

onMounted(() => {
    window.addEventListener('scroll', handleScroll, { passive: true });
});

onUnmounted(() => {
    window.removeEventListener('scroll', handleScroll);
});
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

        <a
            id="scroll-top"
            class="button-go"
            :class="{ show: showBackToTop }"
            @click="scrollToTop"
        ></a>
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

<style scoped>
#scroll-top {
    opacity: 0;
    visibility: hidden;
    transition: opacity 0.3s, visibility 0.3s;
    cursor: pointer;
}

#scroll-top.show {
    opacity: 1;
    visibility: visible;
}
</style>
