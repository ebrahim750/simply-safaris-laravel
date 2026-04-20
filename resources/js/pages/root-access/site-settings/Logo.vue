<script setup lang="ts">
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import Heading from '@/components/Heading.vue';
import InputError from '@/components/InputError.vue';
import { Alert, AlertDescription, AlertTitle } from '@/components/ui/alert';
import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { cn } from '@/lib/utils';
import { Spinner } from '@/components/ui/spinner';

type SiteSettings = {
    logo_path: string | null;
    admin_logo_path_light: string | null;
    admin_logo_path_dark: string | null;
    favicon_path: string | null;
    logo_url: string | null;
    admin_logo_light_url: string | null;
    admin_logo_dark_url: string | null;
    favicon_url: string | null;
};

const props = defineProps<{
    siteSettings: SiteSettings;
}>();

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Root Access',
                href: '/root-access/site-settings',
            },
            {
                title: 'Settings',
                href: '/root-access/site-settings',
            },
            {
                title: 'Logo',
                href: '/root-access/site-settings/logo',
            },
        ],
    },
});

const page = usePage();
const status = computed(() => page.props.flash.status);

const form = useForm({
    logo: null as File | null,
    admin_logo_light: null as File | null,
    admin_logo_dark: null as File | null,
    favicon: null as File | null,
    _method: 'put',
});

function submit(): void {
    form.post('/root-access/site-settings/logo', {
        preserveScroll: true,
        forceFormData: true,
    });
}

const tabs = [
    { label: 'General', href: '/root-access/site-settings/general' },
    { label: 'Logo', href: '/root-access/site-settings/logo' },
    { label: 'Colors', href: '/root-access/site-settings/colors' },
];

const currentUrl = computed(() => usePage().url);

function isActive(href: string): boolean {
    return currentUrl.value === href || currentUrl.value.startsWith(href + '?');
}
</script>

<template>
    <Head title="Logo Settings" />

    <div class="flex h-full flex-1 flex-col gap-6 rounded-xl p-4">
        <Heading
            title="Logo Settings"
            description="Upload site logos and favicon."
        />

        <div class="flex gap-1 rounded-lg border border-border p-1">
            <Link
                v-for="tab in tabs"
                :key="tab.label"
                :href="tab.href"
                :class="cn(
                    'rounded-md px-4 py-2 text-sm font-medium transition-colors',
                    isActive(tab.href)
                        ? 'bg-primary text-primary-foreground'
                        : 'text-muted-foreground hover:bg-muted hover:text-foreground',
                )"
            >
                {{ tab.label }}
            </Link>
        </div>

        <Alert v-if="status">
            <AlertTitle>Settings saved</AlertTitle>
            <AlertDescription>{{ status }}</AlertDescription>
        </Alert>

        <Card class="max-w-4xl">
            <CardHeader>
                <CardTitle>Branding assets</CardTitle>
                <CardDescription>
                    Upload site logos and favicon. All images are stored publicly.
                </CardDescription>
            </CardHeader>
            <CardContent>
                <form class="space-y-8" @submit.prevent="submit">
                    <div class="grid gap-6 md:grid-cols-2">
                        <div class="grid gap-3">
                            <Label for="logo">Site logo</Label>
                            <Input
                                id="logo"
                                type="file"
                                accept=".png,.jpg,.jpeg,.webp,.svg"
                                @input="form.logo = ($event.target as HTMLInputElement).files?.[0] ?? null"
                            />
                            <p
                                v-if="siteSettings.logo_path"
                                class="text-xs text-muted-foreground"
                            >
                                Current path: {{ siteSettings.logo_path }}
                            </p>
                            <img
                                v-if="siteSettings.logo_url"
                                :src="siteSettings.logo_url"
                                alt="Current site logo"
                                class="h-20 w-auto rounded-md border border-border p-2"
                            />
                            <InputError :message="form.errors.logo" />
                        </div>

                        <div class="grid gap-3">
                            <Label for="favicon">Favicon</Label>
                            <Input
                                id="favicon"
                                type="file"
                                accept=".ico,.png,.svg"
                                @input="form.favicon = ($event.target as HTMLInputElement).files?.[0] ?? null"
                            />
                            <p
                                v-if="siteSettings.favicon_path"
                                class="text-xs text-muted-foreground"
                            >
                                Current path: {{ siteSettings.favicon_path }}
                            </p>
                            <img
                                v-if="siteSettings.favicon_url"
                                :src="siteSettings.favicon_url"
                                alt="Current favicon"
                                class="h-16 w-16 rounded-md border border-border p-2 object-contain"
                            />
                            <InputError :message="form.errors.favicon" />
                        </div>

                        <div class="grid gap-3">
                            <Label for="admin_logo_light">Admin logo light</Label>
                            <Input
                                id="admin_logo_light"
                                type="file"
                                accept=".png,.jpg,.jpeg,.webp,.svg"
                                @input="
                                    form.admin_logo_light =
                                        ($event.target as HTMLInputElement).files?.[0] ?? null
                                "
                            />
                            <p
                                v-if="siteSettings.admin_logo_path_light"
                                class="text-xs text-muted-foreground"
                            >
                                Current path: {{ siteSettings.admin_logo_path_light }}
                            </p>
                            <img
                                v-if="siteSettings.admin_logo_light_url"
                                :src="siteSettings.admin_logo_light_url"
                                alt="Current admin light logo"
                                class="h-20 w-auto rounded-md border border-border p-2"
                            />
                            <InputError :message="form.errors.admin_logo_light" />
                        </div>

                        <div class="grid gap-3">
                            <Label for="admin_logo_dark">Admin logo dark</Label>
                            <Input
                                id="admin_logo_dark"
                                type="file"
                                accept=".png,.jpg,.jpeg,.webp,.svg"
                                @input="
                                    form.admin_logo_dark =
                                        ($event.target as HTMLInputElement).files?.[0] ?? null
                                "
                            />
                            <p
                                v-if="siteSettings.admin_logo_path_dark"
                                class="text-xs text-muted-foreground"
                            >
                                Current path: {{ siteSettings.admin_logo_path_dark }}
                            </p>
                            <img
                                v-if="siteSettings.admin_logo_dark_url"
                                :src="siteSettings.admin_logo_dark_url"
                                alt="Current admin dark logo"
                                class="h-20 w-auto rounded-md border border-border p-2"
                            />
                            <InputError :message="form.errors.admin_logo_dark" />
                        </div>
                    </div>

                    <Button
                        type="submit"
                        :disabled="form.processing"
                        data-test="save-logo-settings-button"
                    >
                        <Spinner v-if="form.processing" />
                        Save
                    </Button>
                </form>
            </CardContent>
        </Card>
    </div>
</template>
