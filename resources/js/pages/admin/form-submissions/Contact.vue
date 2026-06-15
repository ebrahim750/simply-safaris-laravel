<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import Heading from '@/components/Heading.vue';

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Form Submissions',
                href: '/admin/form-submissions/contact',
            },
            {
                title: 'Contact Form',
                href: '/admin/form-submissions/contact',
            },
        ],
    },
});
</script>

<template>
    <Head title="Contact Form Submissions" />

    <div class="flex h-full flex-1 flex-col gap-6 rounded-xl p-4">
        <Heading
            title="Contact Form Submissions"
            description="Contact form submissions will appear here."
        />
    </div>
</template>
