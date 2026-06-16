<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import TailwindPublicFooter from '@/components/public/TailwindPublicFooter.vue';
import TailwindPublicHeader from '@/components/public/TailwindPublicHeader.vue';
</script>

<template>
    <Head>
        <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&family=Yesteryear&display=swap" rel="stylesheet" />
    </Head>

    <div>
        <TailwindPublicHeader />

        <main>
            <slot />
        </main>

        <TailwindPublicFooter />
    </div>
</template>
