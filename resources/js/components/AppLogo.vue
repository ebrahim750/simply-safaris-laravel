<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import AppLogoIcon from '@/components/AppLogoIcon.vue';

const page = usePage();

const appBranding = computed(() => page.props.appBranding);
const siteName = computed(() => appBranding.value.siteName ?? page.props.name);
const adminLogoLightUrl = computed(() => appBranding.value.adminLogoLightUrl);
const adminLogoDarkUrl = computed(() => appBranding.value.adminLogoDarkUrl);
const hasCustomAdminLogo = computed(
    () => Boolean(adminLogoLightUrl.value || adminLogoDarkUrl.value),
);
</script>

<template>
    <template v-if="hasCustomAdminLogo">
        <template v-if="adminLogoLightUrl && adminLogoDarkUrl">
            <img
                :src="adminLogoLightUrl"
                :alt="`${siteName} logo`"
                class="block h-8 w-auto max-w-32 object-contain dark:hidden"
            >
            <img
                :src="adminLogoDarkUrl"
                :alt="`${siteName} logo`"
                class="hidden h-8 w-auto max-w-32 object-contain dark:block"
            >
        </template>
        <img
            v-else-if="adminLogoLightUrl"
            :src="adminLogoLightUrl"
            :alt="`${siteName} logo`"
            class="block h-8 w-auto max-w-32 object-contain"
        >
        <img
            v-else-if="adminLogoDarkUrl"
            :src="adminLogoDarkUrl"
            :alt="`${siteName} logo`"
            class="block h-8 w-auto max-w-32 object-contain"
        >
    </template>
    <div
        v-else
        class="flex aspect-square size-8 items-center justify-center overflow-hidden rounded-md bg-sidebar-primary text-sidebar-primary-foreground"
    >
        <AppLogoIcon class="size-5 fill-current text-white dark:text-black" />
    </div>
    <div class="ml-1 grid flex-1 text-left text-sm">
        <span class="mb-0.5 truncate leading-tight font-semibold"
            >{{ siteName }}</span
        >
    </div>
</template>
