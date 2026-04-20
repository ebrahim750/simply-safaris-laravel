<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import { home } from '@/routes';

defineProps<{
    title?: string;
    description?: string;
}>();

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
    <div
        class="flex min-h-svh flex-col items-center justify-center gap-6 bg-background p-6 md:p-10"
    >
        <div class="w-full max-w-sm">
            <div class="flex flex-col gap-8">
                <div class="flex flex-col items-center gap-4">
                    <Link
                        :href="home()"
                        class="flex flex-col items-center gap-2 font-medium"
                    >
                        <template v-if="hasCustomAdminLogo">
                            <template
                                v-if="adminLogoLightUrl && adminLogoDarkUrl"
                            >
                                <img
                                    :src="adminLogoLightUrl"
                                    :alt="`${siteName} logo`"
                                    class="block h-20 w-auto max-w-60 object-contain dark:hidden"
                                >
                                <img
                                    :src="adminLogoDarkUrl"
                                    :alt="`${siteName} logo`"
                                    class="hidden h-20 w-auto max-w-60 object-contain dark:block"
                                >
                            </template>
                            <img
                                v-else-if="adminLogoLightUrl"
                                :src="adminLogoLightUrl"
                                :alt="`${siteName} logo`"
                                class="block h-20 w-auto max-w-60 object-contain"
                            >
                            <img
                                v-else-if="adminLogoDarkUrl"
                                :src="adminLogoDarkUrl"
                                :alt="`${siteName} logo`"
                                class="block h-20 w-auto max-w-60 object-contain"
                            >
                        </template>
                        <div
                            v-else
                            class="mb-1 flex h-9 w-9 items-center justify-center rounded-md"
                        >
                            <AppLogoIcon
                                class="size-9 fill-current text-[var(--foreground)] dark:text-white"
                            />
                        </div>
                        <span class="sr-only">{{ siteName }}</span>
                    </Link>
                    <div class="space-y-2 text-center">
                        <h1 class="text-xl font-medium">{{ title }}</h1>
                        <p class="text-center text-sm text-muted-foreground">
                            {{ description }}
                        </p>
                    </div>
                </div>
                <slot />
            </div>
        </div>
    </div>
</template>
