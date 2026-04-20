<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import AppLogoIcon from '@/components/AppLogoIcon.vue';

const page = usePage();

const siteSettings = computed(() => page.props.siteSettings);
const siteName = computed(() => siteSettings.value.siteName ?? page.props.name);
const adminLogoLightUrl = computed(() => siteSettings.value.adminLogoLightUrl);
const adminLogoDarkUrl = computed(() => siteSettings.value.adminLogoDarkUrl);
const hasCustomAdminLogo = computed(
    () => Boolean(adminLogoLightUrl.value || adminLogoDarkUrl.value),
);
</script>

<template>
    <div
        class="flex aspect-square size-8 items-center justify-center overflow-hidden rounded-md bg-sidebar-primary text-sidebar-primary-foreground"
    >
        <template v-if="hasCustomAdminLogo">
            <img
                v-if="adminLogoLightUrl"
                :src="adminLogoLightUrl"
                :alt="`${siteName} logo`"
                class="block size-full object-contain p-1 dark:hidden"
            >
            <img
                v-if="adminLogoDarkUrl"
                :src="adminLogoDarkUrl"
                :alt="`${siteName} logo`"
                class="hidden size-full object-contain p-1 dark:block"
            >
            <img
                v-else-if="adminLogoLightUrl"
                :src="adminLogoLightUrl"
                :alt="`${siteName} logo`"
                class="hidden size-full object-contain p-1 dark:block"
            >
            <img
                v-else-if="adminLogoDarkUrl"
                :src="adminLogoDarkUrl"
                :alt="`${siteName} logo`"
                class="block size-full object-contain p-1 dark:hidden"
            >
        </template>
        <AppLogoIcon
            v-else
            class="size-5 fill-current text-white dark:text-black"
        />
    </div>
    <div class="ml-1 grid flex-1 text-left text-sm">
        <span class="mb-0.5 truncate leading-tight font-semibold"
            >{{ siteName }}</span
        >
    </div>
</template>
