<script setup lang="ts">
import {
    SidebarGroup,
    SidebarGroupContent,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import { useAppearanceCycle } from '@/composables/useAppearanceCycle';

const { cycle, label, actionIcon } = useAppearanceCycle();
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
                        <component :is="actionIcon" />
                        <span>{{ label }}</span>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarGroupContent>
    </SidebarGroup>
</template>
