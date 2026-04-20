<script setup lang="ts">
import { Head, useForm, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import SiteSettingsController from '@/actions/App/Http/Controllers/RootAccess/SiteSettingsController';
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
import { Spinner } from '@/components/ui/spinner';
import { siteSettings as rootAccessSiteSettings } from '@/routes/root-access';

type SiteSettings = {
    site_name: string;
    site_tagline: string | null;
    primary_color: string | null;
    secondary_color: string | null;
    accent_color: string | null;
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
                href: rootAccessSiteSettings(),
            },
            {
                title: 'Site settings',
                href: rootAccessSiteSettings(),
            },
        ],
    },
});

const page = usePage();
const status = computed(() => page.props.flash.status);

const form = useForm({
    site_name: props.siteSettings.site_name,
    site_tagline: props.siteSettings.site_tagline ?? '',
    primary_color: props.siteSettings.primary_color ?? '',
    secondary_color: props.siteSettings.secondary_color ?? '',
    accent_color: props.siteSettings.accent_color ?? '',
    logo: null as File | null,
    admin_logo_light: null as File | null,
    admin_logo_dark: null as File | null,
    favicon: null as File | null,
    _method: 'put',
});

function submit(): void {
    form.post(SiteSettingsController.update.url(), {
        preserveScroll: true,
        forceFormData: true,
    });
}
</script>

<template>
    <Head title="Site settings" />

    <div class="flex h-full flex-1 flex-col gap-6 rounded-xl p-4">
        <Heading
            title="Site settings"
            description="Manage the singleton site settings record from the root access area."
        />

        <Alert v-if="status">
            <AlertTitle>Site settings saved</AlertTitle>
            <AlertDescription>{{ status }}</AlertDescription>
        </Alert>

        <Card class="max-w-4xl">
            <CardHeader>
                <CardTitle>Branding and site details</CardTitle>
                <CardDescription>
                    Update site names, colors, and uploaded branding assets.
                </CardDescription>
            </CardHeader>
            <CardContent>
                <form class="space-y-8" @submit.prevent="submit">
                    <div class="grid gap-6 md:grid-cols-2">
                        <div class="grid gap-2">
                            <Label for="site_name">Site name</Label>
                            <Input
                                id="site_name"
                                v-model="form.site_name"
                                placeholder="Site name"
                                required
                            />
                            <InputError :message="form.errors.site_name" />
                        </div>

                        <div class="grid gap-2">
                            <Label for="site_tagline">Site tagline</Label>
                            <Input
                                id="site_tagline"
                                v-model="form.site_tagline"
                                placeholder="Short tagline"
                            />
                            <InputError :message="form.errors.site_tagline" />
                        </div>

                        <div class="grid gap-2">
                            <Label for="primary_color">Primary color</Label>
                            <Input
                                id="primary_color"
                                v-model="form.primary_color"
                                placeholder="#1f2937"
                            />
                            <InputError :message="form.errors.primary_color" />
                        </div>

                        <div class="grid gap-2">
                            <Label for="secondary_color">Secondary color</Label>
                            <Input
                                id="secondary_color"
                                v-model="form.secondary_color"
                                placeholder="#6b7280"
                            />
                            <InputError :message="form.errors.secondary_color" />
                        </div>

                        <div class="grid gap-2 md:col-span-2">
                            <Label for="accent_color">Accent color</Label>
                            <Input
                                id="accent_color"
                                v-model="form.accent_color"
                                placeholder="#f59e0b"
                            />
                            <InputError :message="form.errors.accent_color" />
                        </div>
                    </div>

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
                        data-test="save-site-settings-button"
                    >
                        <Spinner v-if="form.processing" />
                        Save site settings
                    </Button>
                </form>
            </CardContent>
        </Card>
    </div>
</template>
