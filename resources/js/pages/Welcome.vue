<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { login } from '@/routes';
</script>

<template>
    <Head title="Simply Safaris" />

    <div class="flex min-h-screen flex-col items-center justify-center gap-4">
        <h1 class="text-4xl font-bold">simply safaris</h1>
        <Link
            :href="login()"
            class="text-sm text-muted-foreground underline underline-offset-4 hover:text-foreground"
        >
            Login
        </Link>
    </div>
</template>
