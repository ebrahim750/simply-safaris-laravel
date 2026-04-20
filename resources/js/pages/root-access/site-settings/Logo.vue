<script setup lang="ts">
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import { UploadCloudIcon } from 'lucide-vue-next';
import { computed, ref } from 'vue';
import Heading from '@/components/Heading.vue';
import InputError from '@/components/InputError.vue';
import { Alert, AlertDescription, AlertTitle } from '@/components/ui/alert';
import {
    AlertDialog,
    AlertDialogAction,
    AlertDialogCancel,
    AlertDialogContent,
    AlertDialogDescription,
    AlertDialogFooter,
    AlertDialogHeader,
    AlertDialogTitle,
    AlertDialogTrigger,
} from '@/components/ui/alert-dialog';
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
import { cn } from '@/lib/utils';

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
            { title: 'Root Access', href: '/root-access/site-settings' },
            { title: 'Settings', href: '/root-access/site-settings' },
            { title: 'Logo', href: '/root-access/site-settings/logo' },
        ],
    },
});



const logoForm = useForm({ logo: null as File | null, _method: 'put' });
const faviconForm = useForm({ favicon: null as File | null, _method: 'put' });
const adminLogoLightForm = useForm({ admin_logo_light: null as File | null, _method: 'put' });
const adminLogoDarkForm = useForm({ admin_logo_dark: null as File | null, _method: 'put' });

const logoPreview = ref<string | null>(null);
const faviconPreview = ref<string | null>(null);
const adminLightPreview = ref<string | null>(null);
const adminDarkPreview = ref<string | null>(null);

function handleLogoFile(event: Event): void {
    const file = (event.target as HTMLInputElement).files?.[0] ?? null;
    logoForm.logo = file;

    if (file) {
        if (logoPreview.value) {
URL.revokeObjectURL(logoPreview.value);
}

        logoPreview.value = URL.createObjectURL(file);
    }
}

function handleFaviconFile(event: Event): void {
    const file = (event.target as HTMLInputElement).files?.[0] ?? null;
    faviconForm.favicon = file;

    if (file) {
        if (faviconPreview.value) {
URL.revokeObjectURL(faviconPreview.value);
}

        faviconPreview.value = URL.createObjectURL(file);
    }
}

function handleAdminLightFile(event: Event): void {
    const file = (event.target as HTMLInputElement).files?.[0] ?? null;
    adminLogoLightForm.admin_logo_light = file;

    if (file) {
        if (adminLightPreview.value) {
URL.revokeObjectURL(adminLightPreview.value);
}

        adminLightPreview.value = URL.createObjectURL(file);
    }
}

function handleAdminDarkFile(event: Event): void {
    const file = (event.target as HTMLInputElement).files?.[0] ?? null;
    adminLogoDarkForm.admin_logo_dark = file;

    if (file) {
        if (adminDarkPreview.value) {
URL.revokeObjectURL(adminDarkPreview.value);
}

        adminDarkPreview.value = URL.createObjectURL(file);
    }
}

function uploadLogo(): void {
    logoForm.post('/root-access/site-settings/logo', {
        preserveScroll: true,
        forceFormData: true,
        onSuccess: () => {
            logoForm.reset();
            logoPreview.value = null;
        },
    });
}

function removeLogo(): void {
    logoForm.delete('/root-access/site-settings/logo?field=logo', {
        preserveScroll: true,
        forceFormData: true,
        onSuccess: () => {
            logoForm.reset();
            logoPreview.value = null;
        },
    });
}

function uploadFavicon(): void {
    faviconForm.post('/root-access/site-settings/logo', {
        preserveScroll: true,
        forceFormData: true,
        onSuccess: () => {
            faviconForm.reset();
            faviconPreview.value = null;
        },
    });
}

function removeFavicon(): void {
    faviconForm.delete('/root-access/site-settings/logo?field=favicon', {
        preserveScroll: true,
        forceFormData: true,
        onSuccess: () => {
            faviconForm.reset();
            faviconPreview.value = null;
        },
    });
}

function uploadAdminLight(): void {
    adminLogoLightForm.post('/root-access/site-settings/logo', {
        preserveScroll: true,
        forceFormData: true,
        onSuccess: () => {
            adminLogoLightForm.reset();
            adminLightPreview.value = null;
        },
    });
}

function removeAdminLight(): void {
    adminLogoLightForm.delete('/root-access/site-settings/logo?field=admin_logo_light', {
        preserveScroll: true,
        forceFormData: true,
        onSuccess: () => {
            adminLogoLightForm.reset();
            adminLightPreview.value = null;
        },
    });
}

function uploadAdminDark(): void {
    adminLogoDarkForm.post('/root-access/site-settings/logo', {
        preserveScroll: true,
        forceFormData: true,
        onSuccess: () => {
            adminLogoDarkForm.reset();
            adminDarkPreview.value = null;
        },
    });
}

function removeAdminDark(): void {
    adminLogoDarkForm.delete('/root-access/site-settings/logo?field=admin_logo_dark', {
        preserveScroll: true,
        forceFormData: true,
        onSuccess: () => {
            adminLogoDarkForm.reset();
            adminDarkPreview.value = null;
        },
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

        <div class="flex gap-3 rounded-lg border border-border p-1 max-w-4xl">
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



        <Card class="max-w-4xl">
            <CardHeader>
                <CardTitle>Branding assets</CardTitle>
                <CardDescription>
                    Upload site logos and favicon. All images are stored publicly.
                </CardDescription>
            </CardHeader>
            <CardContent>
                <div class="grid gap-8 md:grid-cols-2">
                    <div class="grid gap-3">
                        <div class="flex items-center justify-between">
                            <Label for="logo">Site logo</Label>
                            <Button
                                v-if="logoForm.logo"
                                variant="secondary"
                                size="sm"
                                class="h-7 text-xs"
                                :disabled="logoForm.processing"
                                @click="uploadLogo"
                            >
                                <Spinner v-if="logoForm.processing" />
                                Upload
                            </Button>
                            <AlertDialog v-else-if="siteSettings.logo_url">
                                <AlertDialogTrigger as-child>
                                    <Button
                                        variant="secondary"
                                        size="sm"
                                        class="h-7 text-xs text-destructive hover:text-destructive"
                                    >
                                        Remove
                                    </Button>
                                </AlertDialogTrigger>
                                <AlertDialogContent>
                                    <AlertDialogHeader>
                                        <AlertDialogTitle>Remove site logo?</AlertDialogTitle>
                                        <AlertDialogDescription>
                                            This action cannot be undone.
                                        </AlertDialogDescription>
                                    </AlertDialogHeader>
                                    <AlertDialogFooter>
                                        <AlertDialogCancel>Cancel</AlertDialogCancel>
                                        <AlertDialogAction
                                            :disabled="logoForm.processing"
                                            @click="removeLogo"
                                        >
                                            <Spinner v-if="logoForm.processing" />
                                            Remove
                                        </AlertDialogAction>
                                    </AlertDialogFooter>
                                </AlertDialogContent>
                            </AlertDialog>
                        </div>
                        <div
                            :class="cn(
                                'relative flex flex-col items-center justify-center gap-3 rounded-lg border-2 border-dashed border-border bg-muted/50 p-6 transition-colors hover:border-muted-foreground/50',
                                logoForm.errors.logo && 'border-destructive',
                            )"
                        >
                            <input
                                id="logo"
                                type="file"
                                accept=".png,.jpg,.jpeg,.webp,.svg"
                                class="absolute inset-0 z-10 cursor-pointer opacity-0"
                                @change="handleLogoFile"
                            />
                            <div
                                v-if="logoPreview || siteSettings.logo_url"
                                class="flex flex-col items-center gap-2"
                            >
                                <img
                                    :src="logoPreview ?? siteSettings.logo_url ?? undefined"
                                    alt="Current site logo"
                                    class="w-full rounded-md border border-border bg-background p-2 object-contain"
                                />
                            </div>
                            <div v-else class="flex flex-col items-center gap-2 text-muted-foreground">
                                <UploadCloudIcon class="h-10 w-10" />
                                <span class="text-sm">Click or drag to upload</span>
                                <span class="text-xs">.png, .jpg, .jpeg, .webp, .svg</span>
                            </div>
                        </div>
                        <InputError :message="logoForm.errors.logo" />
                    </div>

                    <div class="grid gap-3">
                        <div class="flex items-center justify-between">
                            <Label for="favicon">Favicon</Label>
                            <Button
                                v-if="faviconForm.favicon"
                                variant="secondary"
                                size="sm"
                                class="h-7 text-xs"
                                :disabled="faviconForm.processing"
                                @click="uploadFavicon"
                            >
                                <Spinner v-if="faviconForm.processing" />
                                Upload
                            </Button>
                            <AlertDialog v-else-if="siteSettings.favicon_url">
                                <AlertDialogTrigger as-child>
                                    <Button
                                        variant="secondary"
                                        size="sm"
                                        class="h-7 text-xs text-destructive hover:text-destructive"
                                    >
                                        Remove
                                    </Button>
                                </AlertDialogTrigger>
                                <AlertDialogContent>
                                    <AlertDialogHeader>
                                        <AlertDialogTitle>Remove favicon?</AlertDialogTitle>
                                        <AlertDialogDescription>
                                            This action cannot be undone.
                                        </AlertDialogDescription>
                                    </AlertDialogHeader>
                                    <AlertDialogFooter>
                                        <AlertDialogCancel>Cancel</AlertDialogCancel>
                                        <AlertDialogAction
                                            :disabled="faviconForm.processing"
                                            @click="removeFavicon"
                                        >
                                            <Spinner v-if="faviconForm.processing" />
                                            Remove
                                        </AlertDialogAction>
                                    </AlertDialogFooter>
                                </AlertDialogContent>
                            </AlertDialog>
                        </div>
                        <div
                            :class="cn(
                                'relative flex flex-col items-center justify-center gap-3 rounded-lg border-2 border-dashed border-border bg-muted/50 p-6 transition-colors hover:border-muted-foreground/50',
                                faviconForm.errors.favicon && 'border-destructive',
                            )"
                        >
                            <input
                                id="favicon"
                                type="file"
                                accept=".ico,.png,.svg"
                                class="absolute inset-0 z-10 cursor-pointer opacity-0"
                                @change="handleFaviconFile"
                            />
                            <div
                                v-if="faviconPreview || siteSettings.favicon_url"
                                class="flex flex-col items-center gap-2"
                            >
                                <img
                                    :src="faviconPreview ?? siteSettings.favicon_url ?? undefined"
                                    alt="Current favicon"
                                    class="w-full rounded-md border border-border bg-background p-2 object-contain"
                                />
                            </div>
                            <div v-else class="flex flex-col items-center gap-2 text-muted-foreground">
                                <UploadCloudIcon class="h-10 w-10" />
                                <span class="text-sm">Click or drag to upload</span>
                                <span class="text-xs">.ico, .png, .svg</span>
                            </div>
                        </div>
                        <InputError :message="faviconForm.errors.favicon" />
                    </div>

                    <div class="grid gap-3 mt-6 md:mt-0">
                        <div class="flex items-center justify-between">
                            <Label for="admin_logo_light">Admin logo light</Label>
                            <Button
                                v-if="adminLogoLightForm.admin_logo_light"
                                variant="secondary"
                                size="sm"
                                class="h-7 text-xs"
                                :disabled="adminLogoLightForm.processing"
                                @click="uploadAdminLight"
                            >
                                <Spinner v-if="adminLogoLightForm.processing" />
                                Upload
                            </Button>
                            <AlertDialog v-else-if="siteSettings.admin_logo_light_url">
                                <AlertDialogTrigger as-child>
                                    <Button
                                        variant="secondary"
                                        size="sm"
                                        class="h-7 text-xs text-destructive hover:text-destructive"
                                    >
                                        Remove
                                    </Button>
                                </AlertDialogTrigger>
                                <AlertDialogContent>
                                    <AlertDialogHeader>
                                        <AlertDialogTitle>Remove admin logo light?</AlertDialogTitle>
                                        <AlertDialogDescription>
                                            This action cannot be undone.
                                        </AlertDialogDescription>
                                    </AlertDialogHeader>
                                    <AlertDialogFooter>
                                        <AlertDialogCancel>Cancel</AlertDialogCancel>
                                        <AlertDialogAction
                                            :disabled="adminLogoLightForm.processing"
                                            @click="removeAdminLight"
                                        >
                                            <Spinner v-if="adminLogoLightForm.processing" />
                                            Remove
                                        </AlertDialogAction>
                                    </AlertDialogFooter>
                                </AlertDialogContent>
                            </AlertDialog>
                        </div>
                        <div
                            :class="cn(
                                'relative flex flex-col items-center justify-center gap-3 rounded-lg border-2 border-dashed border-border bg-muted/50 p-6 transition-colors hover:border-muted-foreground/50',
                                adminLogoLightForm.errors.admin_logo_light && 'border-destructive',
                            )"
                        >
                            <input
                                id="admin_logo_light"
                                type="file"
                                accept=".png,.jpg,.jpeg,.webp,.svg"
                                class="absolute inset-0 z-10 cursor-pointer opacity-0"
                                @change="handleAdminLightFile"
                            />
                            <div
                                v-if="adminLightPreview || siteSettings.admin_logo_light_url"
                                class="flex flex-col items-center gap-2"
                            >
                                <img
                                    :src="adminLightPreview ?? siteSettings.admin_logo_light_url ?? undefined"
                                    alt="Current admin light logo"
                                    class="w-full rounded-md border border-border bg-background p-2 object-contain"
                                />
                            </div>
                            <div v-else class="flex flex-col items-center gap-2 text-muted-foreground">
                                <UploadCloudIcon class="h-10 w-10" />
                                <span class="text-sm">Click or drag to upload</span>
                                <span class="text-xs">.png, .jpg, .jpeg, .webp, .svg</span>
                            </div>
                        </div>
                        <InputError :message="adminLogoLightForm.errors.admin_logo_light" />
                    </div>

                    <div class="grid gap-3 mt-6 md:mt-0">
                        <div class="flex items-center justify-between">
                            <Label for="admin_logo_dark">Admin logo dark</Label>
                            <Button
                                v-if="adminLogoDarkForm.admin_logo_dark"
                                variant="secondary"
                                size="sm"
                                class="h-7 text-xs"
                                :disabled="adminLogoDarkForm.processing"
                                @click="uploadAdminDark"
                            >
                                <Spinner v-if="adminLogoDarkForm.processing" />
                                Upload
                            </Button>
                            <AlertDialog v-else-if="siteSettings.admin_logo_dark_url">
                                <AlertDialogTrigger as-child>
                                    <Button
                                        variant="secondary"
                                        size="sm"
                                        class="h-7 text-xs text-destructive hover:text-destructive"
                                    >
                                        Remove
                                    </Button>
                                </AlertDialogTrigger>
                                <AlertDialogContent>
                                    <AlertDialogHeader>
                                        <AlertDialogTitle>Remove admin logo dark?</AlertDialogTitle>
                                        <AlertDialogDescription>
                                            This action cannot be undone.
                                        </AlertDialogDescription>
                                    </AlertDialogHeader>
                                    <AlertDialogFooter>
                                        <AlertDialogCancel>Cancel</AlertDialogCancel>
                                        <AlertDialogAction
                                            :disabled="adminLogoDarkForm.processing"
                                            @click="removeAdminDark"
                                        >
                                            <Spinner v-if="adminLogoDarkForm.processing" />
                                            Remove
                                        </AlertDialogAction>
                                    </AlertDialogFooter>
                                </AlertDialogContent>
                            </AlertDialog>
                        </div>
                        <div
                            :class="cn(
                                'relative flex flex-col items-center justify-center gap-3 rounded-lg border-2 border-dashed border-border bg-muted/50 p-6 transition-colors hover:border-muted-foreground/50',
                                adminLogoDarkForm.errors.admin_logo_dark && 'border-destructive',
                            )"
                        >
                            <input
                                id="admin_logo_dark"
                                type="file"
                                accept=".png,.jpg,.jpeg,.webp,.svg"
                                class="absolute inset-0 z-10 cursor-pointer opacity-0"
                                @change="handleAdminDarkFile"
                            />
                            <div
                                v-if="adminDarkPreview || siteSettings.admin_logo_dark_url"
                                class="flex flex-col items-center gap-2"
                            >
                                <img
                                    :src="adminDarkPreview ?? siteSettings.admin_logo_dark_url ?? undefined"
                                    alt="Current admin dark logo"
                                    class="w-full rounded-md border border-border bg-background p-2 object-contain"
                                />
                            </div>
                            <div v-else class="flex flex-col items-center gap-2 text-muted-foreground">
                                <UploadCloudIcon class="h-10 w-10" />
                                <span class="text-sm">Click or drag to upload</span>
                                <span class="text-xs">.png, .jpg, .jpeg, .webp, .svg</span>
                            </div>
                        </div>
                        <InputError :message="adminLogoDarkForm.errors.admin_logo_dark" />
                    </div>
                </div>
            </CardContent>
        </Card>
    </div>
</template>
