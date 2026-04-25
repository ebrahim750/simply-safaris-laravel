<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import {
    LayoutGrid,
    Map,
    MapPin,
    Settings,
    TestTubeDiagonal,
    Users,
} from 'lucide-vue-next';
import { computed } from 'vue';
import AppLogo from '@/components/AppLogo.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import SidebarAppearanceToggle from '@/components/SidebarAppearanceToggle.vue';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarGroup,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
    SidebarMenuSub,
    SidebarMenuSubButton,
    SidebarMenuSubItem,
} from '@/components/ui/sidebar';
import { useCurrentUrl } from '@/composables/useCurrentUrl';
import { dashboard } from '@/routes';
import { tours as adminTours } from '@/routes/admin';
import adminDestinationsRoutes from '@/routes/admin/destinations';
import adminToursRoutes from '@/routes/admin/tours';
import {
    prodTest as rootAccessProdTest,
    siteSettings as rootAccessSiteSettings,
    users as rootAccessUsers,
} from '@/routes/root-access';
import type { NavItem } from '@/types';

const adminToursCategories = adminToursRoutes.categories;
const adminToursActivities = adminToursRoutes.activities;
const adminDestinationsCountries = adminDestinationsRoutes.countries;
const adminDestinationsPlaces = adminDestinationsRoutes.places;

const page = usePage();
const { isCurrentUrl, isCurrentOrParentUrl } = useCurrentUrl();
const isRootUser = computed(() => page.props.auth.user.account_type === 'root');
const isAdminOrRoot = computed(() =>
    ['root', 'admin'].includes(page.props.auth.user.account_type)
);
const isToursExpanded = computed(() => isCurrentOrParentUrl(adminTours().url));
const isDestinationsExpanded = computed(() =>
    isCurrentOrParentUrl(adminDestinationsCountries().url)
    || isCurrentOrParentUrl(adminDestinationsPlaces().url)
);

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
        title: 'Settings',
        href: rootAccessSiteSettings(),
        icon: Settings,
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
            <SidebarGroup v-if="isAdminOrRoot" class="px-2 py-0">
                <SidebarMenu>
                    <SidebarMenuItem>
                        <SidebarMenuButton
                            as-child
                            :is-active="isCurrentOrParentUrl(adminTours().url)"
                            tooltip="Tours"
                        >
                            <Link :href="adminTours()">
                                <Map />
                                <span>Tours</span>
                            </Link>
                        </SidebarMenuButton>
                        <SidebarMenuSub v-if="isToursExpanded">
                            <SidebarMenuSubItem>
                                <SidebarMenuSubButton
                                    as-child
                                    :is-active="isCurrentOrParentUrl(adminToursCategories().url)"
                                    tooltip="Categories"
                                >
                                    <Link :href="adminToursCategories()">
                                        Categories
                                    </Link>
                                </SidebarMenuSubButton>
                            </SidebarMenuSubItem>
                            <SidebarMenuSubItem>
                                <SidebarMenuSubButton
                                    as-child
                                    :is-active="isCurrentOrParentUrl(adminToursActivities().url)"
                                    tooltip="Activities"
                                >
                                    <Link :href="adminToursActivities()">
                                        Activities
                                    </Link>
                                </SidebarMenuSubButton>
                            </SidebarMenuSubItem>
                        </SidebarMenuSub>
                    </SidebarMenuItem>
                    <SidebarMenuItem>
                        <SidebarMenuButton
                            as-child
                            :is-active="
                                isCurrentOrParentUrl(adminDestinationsCountries().url)
                                || isCurrentOrParentUrl(adminDestinationsPlaces().url)
                            "
                            tooltip="Destinations"
                        >
                            <Link :href="adminDestinationsCountries()">
                                <MapPin />
                                <span>Destinations</span>
                            </Link>
                        </SidebarMenuButton>
                        <SidebarMenuSub v-if="isDestinationsExpanded">
                            <SidebarMenuSubItem>
                                <SidebarMenuSubButton
                                    as-child
                                    :is-active="isCurrentOrParentUrl(adminDestinationsCountries().url)"
                                    tooltip="Countries"
                                >
                                    <Link :href="adminDestinationsCountries()">
                                        Countries
                                    </Link>
                                </SidebarMenuSubButton>
                            </SidebarMenuSubItem>
                            <SidebarMenuSubItem>
                                <SidebarMenuSubButton
                                    as-child
                                    :is-active="isCurrentOrParentUrl(adminDestinationsPlaces().url)"
                                    tooltip="Places"
                                >
                                    <Link :href="adminDestinationsPlaces()">
                                        Places
                                    </Link>
                                </SidebarMenuSubButton>
                            </SidebarMenuSubItem>
                        </SidebarMenuSub>
                    </SidebarMenuItem>
                </SidebarMenu>
            </SidebarGroup>
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
