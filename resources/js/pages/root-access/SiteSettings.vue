<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import Heading from '@/components/Heading.vue';
import { buttonVariants } from '@/components/ui/button';
import { cn } from '@/lib/utils';

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Root Access',
                href: '/root-access/site-settings/general',
            },
            {
                title: 'Settings',
                href: '/root-access/site-settings/general',
            },
        ],
    },
});

const tabs = [
    {
        label: 'General',
        description: 'Manage site name and tagline',
        href: '/root-access/site-settings/general',
    },
    {
        label: 'Logo',
        description: 'Upload logos and favicon',
        href: '/root-access/site-settings/logo',
    },
    {
        label: 'Colors',
        description: 'Configure site color palette',
        href: '/root-access/site-settings/colors',
    },
];
</script>

<template>
    <Head title="Settings" />

    <div class="flex h-full flex-1 flex-col gap-6 rounded-xl p-4">
        <Heading
            title="Settings"
            description="Manage the singleton site settings record from the root access area."
        />

        <div class="grid gap-4 md:grid-cols-3">
            <Link
                v-for="tab in tabs"
                :key="tab.label"
                :href="tab.href"
                :class="cn(
                    buttonVariants({ variant: 'outline' }),
                    'h-32 flex-col justify-center gap-2 p-4 text-center',
                )"
            >
                <span class="text-lg font-semibold">{{ tab.label }}</span>
                <span class="text-xs text-muted-foreground">{{ tab.description }}</span>
            </Link>
        </div>
    </div>
</template>
