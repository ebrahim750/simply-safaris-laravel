<script setup lang="ts">
import { onMounted, ref } from 'vue';
import {
    SidebarGroup,
    SidebarGroupContent,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import { useAppearanceCycle } from '@/composables/useAppearanceCycle';

const { cycle, label, actionIcon } = useAppearanceCycle();

const isMounted = ref(false);
onMounted(() => {
    isMounted.value = true;
});
</script>

<template>
    <SidebarGroup class="group-data-[collapsible=icon]:p-0">
        <SidebarGroupContent>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton
                        class="text-neutral-600 hover:text-neutral-800 dark:text-neutral-300 dark:hover:text-neutral-100"
                        :tooltip="label"
                        @click="cycle"
                    >
                        <template v-if="isMounted">
                            <component :is="actionIcon" />
                            <span>{{ label }}</span>
                        </template>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarGroupContent>
    </SidebarGroup>
</template>
