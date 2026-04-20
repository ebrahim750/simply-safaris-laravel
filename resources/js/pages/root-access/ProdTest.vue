<script setup lang="ts">
import { Form, Head, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import ProdTestController from '@/actions/App/Http/Controllers/RootAccess/ProdTestController';
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
import { prodTest as rootAccessProdTest } from '@/routes/root-access';

const props = defineProps<{
    defaultReceiverEmail: string;
}>();

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Root Access',
                href: rootAccessProdTest(),
            },
            {
                title: 'Prod test',
                href: rootAccessProdTest(),
            },
        ],
    },
});

const page = usePage();
const activeTab = ref<'email-test'>('email-test');
const receiverEmail = ref(props.defaultReceiverEmail);
const status = computed(() => page.props.flash.status);
</script>

<template>
    <Head title="Prod test" />

    <div class="flex h-full flex-1 flex-col gap-6 rounded-xl p-4">
        <Heading
            title="Prod test"
            description="Run targeted production checks from the root access area."
        />

        <div class="inline-flex gap-1 rounded-lg bg-neutral-100 p-1 dark:bg-neutral-800">
            <button
                @click="activeTab = 'email-test'"
                :class="[
                    'flex items-center rounded-md px-3.5 py-1.5 text-sm transition-colors',
                    activeTab === 'email-test'
                        ? 'bg-white shadow-xs dark:bg-neutral-700 dark:text-neutral-100'
                        : 'text-neutral-500 hover:bg-neutral-200/60 hover:text-black dark:text-neutral-400 dark:hover:bg-neutral-700/60',
                ]"
            >
                Email test
            </button>
        </div>

        <Alert v-if="status">
            <AlertTitle>Email queued</AlertTitle>
            <AlertDescription>{{ status }}</AlertDescription>
        </Alert>

        <Card v-if="activeTab === 'email-test'" class="max-w-2xl">
            <CardHeader>
                <CardTitle>Email test</CardTitle>
                <CardDescription>
                    Queue a test email for background delivery.
                </CardDescription>
            </CardHeader>
            <CardContent>
                <Form
                    v-bind="ProdTestController.sendEmailTest.form()"
                    :options="{ preserveScroll: true }"
                    class="space-y-6"
                    v-slot="{ errors, processing }"
                >
                    <div class="grid gap-2">
                        <Label for="receiver_email">Receiver email</Label>
                        <Input
                            id="receiver_email"
                            v-model="receiverEmail"
                            name="receiver_email"
                            type="email"
                            autocomplete="email"
                            placeholder="projects@simplyit.co.tz"
                            required
                        />
                        <InputError :message="errors.receiver_email" />
                    </div>

                    <Button type="submit" :disabled="processing" data-test="send-prod-test-email-button">
                        <Spinner v-if="processing" />
                        Send test email
                    </Button>
                </Form>
            </CardContent>
        </Card>
    </div>
</template>
