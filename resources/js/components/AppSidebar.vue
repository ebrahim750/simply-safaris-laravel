<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { LayoutGrid, TestTubeDiagonal, Users } from 'lucide-vue-next';
import { computed } from 'vue';
import AppLogo from '@/components/AppLogo.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import SidebarAppearanceToggle from '@/components/SidebarAppearanceToggle.vue';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import { dashboard } from '@/routes';
import { prodTest as rootAccessProdTest, users as rootAccessUsers } from '@/routes/root-access';
import type { NavItem } from '@/types';

const page = usePage();
const isRootUser = computed(() => page.props.auth.user.account_type === 'root');

const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: dashboard(),
        icon: LayoutGrid,
    },
];

const rootAccessNavItems: NavItem[] = [
    {
        title: 'Users',
        href: rootAccessUsers(),
        icon: Users,
    },
    {
        title: 'Prod test',
        href: rootAccessProdTest(),
        icon: TestTubeDiagonal,
    },
];

</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="dashboard()">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="mainNavItems" />
            <NavMain
                v-if="isRootUser"
                title="Root Access"
                :items="rootAccessNavItems"
            />
        </SidebarContent>

        <SidebarFooter>
            <SidebarAppearanceToggle />
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
