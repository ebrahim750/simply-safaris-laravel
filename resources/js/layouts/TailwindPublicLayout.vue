<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import TailwindPublicFooter from '@/components/public/TailwindPublicFooter.vue';
import TailwindPublicHeader from '@/components/public/TailwindPublicHeader.vue';
</script>

<template>
    <Head />

    <div>
        <TailwindPublicHeader />

        <main>
            <slot />
        </main>

        <TailwindPublicFooter />
    </div>
</template>
