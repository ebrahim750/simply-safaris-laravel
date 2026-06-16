<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import PublicFooter from '@/components/public/PublicFooter.vue';
import PublicHeader from '@/components/public/PublicHeader.vue';
</script>

<template>
    <Head />

    <div>
        <PublicHeader />

        <main>
            <slot />
        </main>

        <PublicFooter />
    </div>
</template>
