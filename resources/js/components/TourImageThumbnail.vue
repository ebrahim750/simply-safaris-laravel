<script setup lang="ts">
import { nextTick, ref, watch } from 'vue';

const props = defineProps<{
    imageUrl: string | null;
    name: string;
}>();

const hasImageError = ref(false);
const isHovering = ref(false);
const thumbRef = ref<HTMLDivElement | null>(null);
const previewPos = ref({ top: 0, left: 0 });

async function updatePosition() {
    await nextTick();
    const el = thumbRef.value;
    if (!el) return;
    const rect = el.getBoundingClientRect();
    previewPos.value = {
        top: rect.top + window.scrollY - 16,
        left: rect.right + window.scrollX + 8,
    };
}

watch(isHovering, (hovering) => {
    if (hovering) updatePosition();
});
</script>

<template>
    <div
        ref="thumbRef"
        class="relative inline-block"
        @mouseenter="isHovering = true"
        @mouseleave="isHovering = false"
    >
        <img
            v-if="imageUrl && !hasImageError"
            :src="imageUrl"
            :alt="`${name} main image`"
            class="h-12 w-16 rounded-md border border-border bg-muted object-cover"
            loading="lazy"
            @error="hasImageError = true"
        />
        <div
            v-else
            class="flex h-12 w-16 items-center justify-center rounded-md border border-dashed border-border bg-muted/40 text-[11px] font-medium uppercase tracking-wide text-muted-foreground"
        >
            No image
        </div>
    </div>

    <Teleport to="body">
        <Transition
            enter-active-class="transition ease-out duration-200"
            enter-from-class="opacity-0 scale-95"
            enter-to-class="opacity-100 scale-100"
            leave-active-class="transition ease-in duration-150"
            leave-from-class="opacity-100 scale-100"
            leave-to-class="opacity-0 scale-95"
        >
            <div
                v-if="isHovering && imageUrl && !hasImageError"
                class="fixed z-[9999] pointer-events-none"
                :style="{
                    top: `${previewPos.top}px`,
                    left: `${previewPos.left}px`,
                }"
            >
                <img
                    :src="imageUrl"
                    :alt="`${name} main image preview`"
                    class="h-40 w-56 rounded-lg border border-border bg-muted object-cover shadow-xl"
                />
            </div>
        </Transition>
    </Teleport>
</template>
