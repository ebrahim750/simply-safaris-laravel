<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';
import { computed, watch } from 'vue';
import { Toaster, toast } from 'vue-sonner';
import 'vue-sonner/style.css';
import { useAppearance } from '@/composables/useAppearance';

const page = usePage();
const { resolvedAppearance } = useAppearance();
const sonnerTheme = computed(() =>
    resolvedAppearance.value === 'dark' ? 'dark' : 'light',
);

let lastShownStatus: string | null = null;

watch(
    () => page.props.flash?.status,
    (status) => {
        if (typeof status !== 'string' || status.length === 0) {
            return;
        }

        if (status === lastShownStatus) {
            return;
        }

        lastShownStatus = status;

        toast.success(status);
    },
    { immediate: true },
);
</script>

<template>
    <Toaster
        :key="sonnerTheme"
        :theme="sonnerTheme"
        position="top-right"
        close-button-position="top-right"
        rich-colors
        close-button
    />
</template>
