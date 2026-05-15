<script setup lang="ts">
import { computed } from 'vue';
import { cn } from '@/lib/utils';
import { Input } from '@/components/ui/input';
import InputError from '@/components/InputError.vue';
import { Label } from '@/components/ui/label';
import { ImageIcon, UploadCloudIcon, X } from 'lucide-vue-next';

const props = defineProps<{
    id: string;
    label: string;
    modelValue: File | null;
    previewUrl: string | null;
    previewAlt: string;
    accept?: string;
    hint?: string;
    error?: string;
    previewHeight?: string;
}>();

const emit = defineEmits<{
    'update:modelValue': [value: File | null];
}>();

function handleFile(event: Event): void {
    const target = event.target as HTMLInputElement;
    emit('update:modelValue', target.files?.[0] ?? null);
}

const hasPreview = computed(() => !!props.previewUrl || !!props.modelValue);
const previewSrc = computed(() => {
    if (props.modelValue) {
        return URL.createObjectURL(props.modelValue);
    }
    return props.previewUrl;
});
</script>

<template>
    <div class="grid gap-2">
        <Label :for="id">{{ label }}</Label>

        <div
            :class="cn(
                'relative flex flex-col items-center justify-center gap-3 rounded-lg border-2 border-dashed border-border bg-muted/50 p-6 transition-colors hover:border-muted-foreground/50',
                error && 'border-destructive',
            )"
        >
            <input
                v-if="!hasPreview"
                :id="id"
                type="file"
                :accept="accept"
                class="absolute inset-0 z-10 cursor-pointer opacity-0"
                @change="handleFile"
            />

            <template v-if="hasPreview">
                <div class="relative flex flex-col items-center gap-2">
                    <img
                        :src="previewSrc ?? undefined"
                        :alt="previewAlt"
                        :class="cn(
                            'rounded-md border border-border bg-background p-2 object-contain',
                            previewHeight ? '' : 'h-20 w-auto',
                        )"
                        :style="previewHeight ? { height: previewHeight, width: 'auto' } : {}"
                    />
                </div>
                <span class="text-xs text-muted-foreground">
                    {{ modelValue ? modelValue.name : 'Current image' }}
                </span>
                <label
                    :for="id"
                    class="cursor-pointer text-sm text-primary hover:underline"
                >
                    Replace image
                </label>
                <input
                    :id="id"
                    type="file"
                    :accept="accept"
                    class="sr-only"
                    @change="handleFile"
                />
            </template>

            <label
                v-else
                :for="id"
                class="flex flex-col items-center gap-2 text-muted-foreground cursor-pointer"
            >
                <UploadCloudIcon class="h-10 w-10" />
                <span class="text-sm">Click or drag to upload</span>
                <span v-if="accept" class="text-xs">{{ accept }}</span>
            </label>
        </div>

        <InputError v-if="error" :message="error" />
        <p v-if="hint && !error" class="text-xs text-muted-foreground">
            {{ hint }}
        </p>
    </div>
</template>
