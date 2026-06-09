<script setup lang="ts">
import { Skeleton } from '@/components/ui/skeleton';
</script>

<template>
    <div class="tour-listing box-sd">
        <div class="tour-listing-image">
            <Skeleton class="h-[300px] w-full rounded-lg" />
        </div>
        <div class="tour-listing-content space-y-3">
            <div class="flex gap-2 flex-wrap">
                <Skeleton class="h-3 w-24" />
                <Skeleton class="h-3 w-24" />
            </div>
            <Skeleton class="h-5 w-3/4" />
            <div class="flex items-center gap-2">
                <Skeleton class="h-3 w-4" />
                <Skeleton class="h-3 w-20" />
            </div>
            <Skeleton class="h-4 w-28" />
        </div>
    </div>
</template>
