<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import {
    index as countriesIndex,
    update as updateCountry,
} from '@/actions/App/Http/Controllers/Admin/DestinationCountryController';
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

type Country = {
    id: number;
    name: string;
    slug: string;
};

const props = defineProps<{
    country: Country;
}>();

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Destinations',
                href: countriesIndex(),
            },
            {
                title: 'Countries',
                href: countriesIndex(),
            },
        ],
    },
});

const form = useForm({
    name: props.country.name,
});

function submit(): void {
    form.patch(updateCountry.url({ country: props.country.id }), {
        preserveScroll: true,
    });
}
</script>

<template>
    <Head :title="`Edit ${props.country.name}`" />

    <div class="flex h-full flex-1 flex-col gap-6 rounded-xl p-4">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
            <Heading
                :title="`Edit ${props.country.name}`"
                description="Update destination country details."
            />

            <div class="flex gap-2">
                <Button as-child variant="outline">
                    <Link :href="countriesIndex()">Cancel</Link>
                </Button>
                <Button
                    type="button"
                    :disabled="form.processing"
                    @click="submit"
                >
                    Save changes
                </Button>
            </div>
        </div>

        <Card>
            <CardHeader>
                <CardTitle>Country details</CardTitle>
                <CardDescription>
                    Slug is auto-generated from the country name.
                </CardDescription>
            </CardHeader>
            <CardContent>
                <form class="space-y-6" @submit.prevent="submit">
                    <div class="grid gap-2">
                        <Label for="name">Name</Label>
                        <Input
                            id="name"
                            v-model="form.name"
                            placeholder="Kenya"
                            required
                        />
                        <InputError :message="form.errors.name" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="slug">Current slug</Label>
                        <Input
                            id="slug"
                            :model-value="props.country.slug"
                            disabled
                        />
                    </div>

                    <div class="flex flex-col-reverse gap-3 border-t border-border pt-6 sm:flex-row sm:justify-end">
                        <Button as-child type="button" variant="outline">
                            <Link :href="countriesIndex()">Cancel</Link>
                        </Button>
                        <Button type="submit" :disabled="form.processing">
                            Save changes
                        </Button>
                    </div>
                </form>
            </CardContent>
        </Card>
    </div>
</template>
