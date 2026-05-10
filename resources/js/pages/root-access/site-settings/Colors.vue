<script setup lang="ts">
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import { computed, defineAsyncComponent, onMounted, ref } from 'vue';
import 'vue3-colorpicker/style.css';
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
import { cn } from '@/lib/utils';

const ColorPicker = defineAsyncComponent(() =>
    import('vue3-colorpicker').then((m) => m.ColorPicker),
);

const isMounted = ref(false);
onMounted(() => {
    isMounted.value = true;
});

type SiteSettings = {
    primary_color: string | null;
    secondary_color: string | null;
    accent_color: string | null;
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
                title: 'Colors',
                href: '/root-access/site-settings/colors',
            },
        ],
    },
});

const page = usePage();
const status = computed(() => page.props.flash.status);

const form = useForm({
    primary_color: props.siteSettings.primary_color ?? '',
    secondary_color: props.siteSettings.secondary_color ?? '',
    accent_color: props.siteSettings.accent_color ?? '',
    _method: 'put',
});

function submit(): void {
    form.post('/root-access/site-settings/colors', {
        preserveScroll: true,
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
    <Head title="Color Settings" />

    <div class="flex h-full flex-1 flex-col gap-6 rounded-xl p-4">
        <Heading
            title="Color Settings"
            description="Configure site color palette."
        />

        <div class="flex max-w-4xl gap-3 rounded-lg border border-border p-1">
            <Link
                v-for="tab in tabs"
                :key="tab.label"
                :href="tab.href"
                :class="
                    cn(
                        'rounded-md px-4 py-2 text-sm font-medium transition-colors',
                        isActive(tab.href)
                            ? 'bg-primary text-primary-foreground'
                            : 'text-muted-foreground hover:bg-muted hover:text-foreground',
                    )
                "
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
                <CardTitle>Site colors</CardTitle>
                <CardDescription>
                    Update the site color palette using hex color codes.
                </CardDescription>
            </CardHeader>
            <CardContent>
                <form class="space-y-8" @submit.prevent="submit">
                    <div class="grid gap-6 md:grid-cols-2">
                        <div class="grid gap-2">
                            <Label>Primary color</Label>
                            <div class="flex items-center gap-3">
                                <ClientOnly>
                                    <ColorPicker
                                        v-model:pureColor="form.primary_color"
                                        format="hex"
                                        class="h-32 w-32"
                                    />
                                </ClientOnly>
                                <Input
                                    v-model="form.primary_color"
                                    placeholder="Select color"
                                    class="w-28"
                                />
                            </div>
                            <InputError :message="form.errors.primary_color" />
                        </div>

                        <div class="grid gap-2">
                            <Label>Secondary color</Label>
                            <div class="flex items-center gap-3">
                                <ColorPicker
                                    v-if="isMounted"
                                    v-model:pureColor="form.secondary_color"
                                    format="hex"
                                    class="h-32 w-32"
                                />
                                <Input
                                    v-model="form.secondary_color"
                                    placeholder="Select color"
                                    class="w-28"
                                />
                            </div>
                            <InputError
                                :message="form.errors.secondary_color"
                            />
                        </div>

                        <div class="grid gap-2">
                            <Label>Accent color</Label>
                            <div class="flex items-center gap-3">
                                <ColorPicker
                                    v-if="isMounted"
                                    v-model:pureColor="form.accent_color"
                                    format="hex"
                                    class="h-32 w-32"
                                />
                                <Input
                                    v-model="form.accent_color"
                                    placeholder="Select color"
                                    class="w-28"
                                />
                            </div>
                            <InputError :message="form.errors.accent_color" />
                        </div>
                    </div>

                    <Button
                        type="submit"
                        :disabled="form.processing"
                        data-test="save-color-settings-button"
                    >
                        <Spinner v-if="form.processing" />
                        Save
                    </Button>
                </form>
            </CardContent>
        </Card>
    </div>
</template>
