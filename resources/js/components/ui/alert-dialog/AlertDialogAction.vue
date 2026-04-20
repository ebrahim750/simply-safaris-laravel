<script setup lang="ts">
import type { AlertDialogActionProps } from "reka-ui"
import type { HTMLAttributes } from "vue"
import { AlertDialogAction } from "reka-ui"
import { cn } from "@/lib/utils"
import { buttonVariants } from "@/components/ui/button"

const props = defineProps<AlertDialogActionProps & { class?: HTMLAttributes["class"] }>()
</script>

<template>
  <AlertDialogAction
    data-slot="alert-dialog-action"
    v-bind="props"
    :class="cn(buttonVariants(), 'cursor-pointer', props.class)"
  >
    <slot />
  </AlertDialogAction>
</template>
