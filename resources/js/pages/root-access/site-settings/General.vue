<script setup lang="ts">
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import Heading from '@/components/Heading.vue';
import InputError from '@/components/InputError.vue';
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
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { Spinner } from '@/components/ui/spinner';
import { cn } from '@/lib/utils';

type SiteSettings = {
    site_name: string;
    site_tagline: string | null;
    currency: string;
};

type CurrencyOption = {
    value: string;
    label: string;
};

const props = defineProps<{
    siteSettings: SiteSettings;
    currencyOptions: CurrencyOption[];
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
                title: 'General',
                href: '/root-access/site-settings/general',
            },
        ],
    },
});

const form = useForm({
    site_name: props.siteSettings.site_name,
    site_tagline: props.siteSettings.site_tagline ?? '',
    currency: props.siteSettings.currency,
    _method: 'put',
});

function submit(): void {
    form.post('/root-access/site-settings/general', {
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
    <Head title="General Settings" />

    <div class="flex h-full flex-1 flex-col gap-6 rounded-xl p-4">
        <Heading
            title="General Settings"
            description="Manage the singleton site settings record from the root access area."
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

        <Card class="max-w-4xl">
            <CardHeader>
                <CardTitle>Site details</CardTitle>
                <CardDescription>
                    Update site name and tagline.
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
                            <Label for="currency">Currency</Label>
                            <Select v-model="form.currency">
                                <SelectTrigger id="currency" class="w-full">
                                    <SelectValue
                                        placeholder="Select currency"
                                    />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem
                                        v-for="option in props.currencyOptions"
                                        :key="option.value"
                                        :value="option.value"
                                    >
                                        {{ option.label }}
                                    </SelectItem>
                                </SelectContent>
                            </Select>
                            <InputError :message="form.errors.currency" />
                        </div>
                    </div>

                    <Button
                        type="submit"
                        :disabled="form.processing"
                        data-test="save-general-settings-button"
                    >
                        <Spinner v-if="form.processing" />
                        Save
                    </Button>
                </form>
            </CardContent>
        </Card>
    </div>
</template>
