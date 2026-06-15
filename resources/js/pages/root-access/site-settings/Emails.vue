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
import { Spinner } from '@/components/ui/spinner';
import { Switch } from '@/components/ui/switch';
import { cn } from '@/lib/utils';

type SiteSettings = {
    form_emails: string | null;
    form_emails_enabled: boolean;
};

const props = defineProps<{
    siteSettings: SiteSettings;
    status?: string;
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
                title: 'Emails',
                href: '/root-access/site-settings/emails',
            },
        ],
    },
});

const form = useForm({
    form_emails: props.siteSettings.form_emails ?? '',
    form_emails_enabled: props.siteSettings.form_emails_enabled,
    _method: 'put',
});

function submit(): void {
    form.post('/root-access/site-settings/emails', {
        preserveScroll: true,
    });
}

const tabs = [
    { label: 'General', href: '/root-access/site-settings/general' },
    { label: 'Logo', href: '/root-access/site-settings/logo' },
    { label: 'Colors', href: '/root-access/site-settings/colors' },
    { label: 'Emails', href: '/root-access/site-settings/emails' },
];

const currentUrl = computed(() => usePage().url);

function isActive(href: string): boolean {
    return currentUrl.value === href || currentUrl.value.startsWith(href + '?');
}
</script>

<template>
    <Head title="Email Settings" />

    <div class="flex h-full flex-1 flex-col gap-6 rounded-xl p-4">
        <Heading
            title="Email Settings"
            description="Configure form notification emails from the root access area."
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
                <CardTitle>Form emails</CardTitle>
                <CardDescription>
                    Set the recipients for form submission notifications.
                </CardDescription>
            </CardHeader>
            <CardContent>
                <form class="space-y-6" @submit.prevent="submit">
                    <div
                        v-if="status"
                        class="text-sm font-medium text-green-600"
                    >
                        {{ status }}
                    </div>

                    <div class="space-y-2">
                        <Label for="form_emails">Form Emails</Label>
                        <Input
                            id="form_emails"
                            v-model="form.form_emails"
                            type="text"
                            placeholder="email@example.com, another@example.com"
                        />
                        <p class="text-xs text-muted-foreground">
                            Separate multiple emails with commas.
                        </p>
                        <InputError :message="form.errors.form_emails" />
                    </div>

                    <div class="flex items-center gap-3">
                        <Switch
                            id="form_emails_enabled"
                            :model-value="form.form_emails_enabled"
                            @update:model-value="
                                form.form_emails_enabled = $event
                            "
                        />
                        <Label for="form_emails_enabled" class="cursor-pointer">
                            Enable form email notifications
                        </Label>
                    </div>
                    <InputError :message="form.errors.form_emails_enabled" />

                    <div class="flex justify-end">
                        <Button type="submit" :disabled="form.processing">
                            <Spinner v-if="form.processing" class="mr-2" />
                            Save changes
                        </Button>
                    </div>
                </form>
            </CardContent>
        </Card>
    </div>
</template>
