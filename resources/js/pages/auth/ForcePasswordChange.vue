<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import ForcePasswordChangeController from '@/actions/App/Http/Controllers/Auth/ForcePasswordChangeController';
import InputError from '@/components/InputError.vue';
import PasswordInput from '@/components/PasswordInput.vue';
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';

defineOptions({
    layout: {
        title: 'Set a new password',
        description:
            'Your account was created with a temporary password. Choose a new password to continue.',
    },
});
</script>

<template>
    <Head title="Set a new password" />

    <Form
        v-bind="ForcePasswordChangeController.update.form()"
        reset-on-success
        :reset-on-error="['password', 'password_confirmation']"
        v-slot="{ errors, processing }"
    >
        <div class="space-y-6">
            <div
                class="rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-900"
            >
                You need to update your password before continuing.
            </div>

            <div class="grid gap-2">
                <Label for="password">New password</Label>
                <PasswordInput
                    id="password"
                    name="password"
                    class="mt-1 block w-full"
                    required
                    autocomplete="new-password"
                    autofocus
                    placeholder="New password"
                />
                <InputError :message="errors.password" />
            </div>

            <div class="grid gap-2">
                <Label for="password_confirmation">Confirm password</Label>
                <PasswordInput
                    id="password_confirmation"
                    name="password_confirmation"
                    class="mt-1 block w-full"
                    required
                    autocomplete="new-password"
                    placeholder="Confirm password"
                />
                <InputError :message="errors.password_confirmation" />
            </div>

            <div class="flex items-center">
                <Button
                    class="w-full"
                    :disabled="processing"
                    data-test="force-password-update-button"
                >
                    <Spinner v-if="processing" />
                    Save new password
                </Button>
            </div>
        </div>
    </Form>
</template>
