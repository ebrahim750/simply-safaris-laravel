<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { computed } from 'vue';
import {
    create as createPlace,
    index as placesIndex,
    store as storePlace,
} from '@/actions/App/Http/Controllers/Admin/DestinationPlaceController';
import { index as countriesIndex } from '@/actions/App/Http/Controllers/Admin/DestinationCountryController';
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

type CountryOption = {
    id: number;
    name: string;
    flag_svg: string | null;
    flag_png: string | null;
};

const props = defineProps<{
    countries: CountryOption[];
}>();

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Destinations',
                href: countriesIndex(),
            },
            {
                title: 'Places',
                href: placesIndex(),
            },
            {
                title: 'Add Place',
                href: createPlace(),
            },
        ],
    },
});

const selectedCountry = computed<CountryOption | undefined>(() =>
    props.countries.find((country) => String(country.id) === form.country_id),
);

const form = useForm({
    country_id: '',
    name: '',
    overview: '',
});

function submit(): void {
    form.post(storePlace.url(), {
        preserveScroll: true,
    });
}
</script>

<template>
    <Head title="Add Place" />

    <div class="flex h-full flex-1 flex-col gap-6 rounded-xl p-4">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
            <Heading
                title="Add Place"
                description="Create a new place and assign it to a country."
            />

            <div class="flex gap-2">
                <Button as-child variant="outline">
                    <Link :href="placesIndex()">Cancel</Link>
                </Button>
                <Button
                    type="button"
                    :disabled="form.processing"
                    @click="submit"
                >
                    Save
                </Button>
            </div>
        </div>

        <Card>
            <CardHeader>
                <CardTitle>Place details</CardTitle>
                <CardDescription>
                    Place slug is generated automatically from the name.
                </CardDescription>
            </CardHeader>
            <CardContent>
                <form class="space-y-6" @submit.prevent="submit">
                    <div class="grid gap-6 md:grid-cols-2">
                        <div class="grid gap-2">
                            <Label for="name">Name</Label>
                            <Input
                                id="name"
                                v-model="form.name"
                                placeholder="Masai Mara"
                                required
                            />
                            <InputError :message="form.errors.name" />
                        </div>

                        <div class="grid gap-2">
                            <Label for="country_id">Country</Label>
                            <Select v-model="form.country_id">
                                <SelectTrigger id="country_id" class="w-full">
                                    <div
                                        v-if="selectedCountry"
                                        class="flex items-center gap-2"
                                    >
                                        <img
                                            v-if="selectedCountry.flag_svg || selectedCountry.flag_png"
                                            :src="selectedCountry.flag_svg ?? selectedCountry.flag_png ?? undefined"
                                            :alt="`${selectedCountry.name} flag`"
                                            class="h-4 w-6 rounded-[2px] border border-border object-cover"
                                        />
                                        <span>{{ selectedCountry.name }}</span>
                                    </div>
                                    <span v-else class="text-muted-foreground">Select country</span>
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem
                                        v-for="country in props.countries"
                                        :key="country.id"
                                        :value="String(country.id)"
                                    >
                                        <div class="flex items-center gap-2">
                                            <img
                                                v-if="country.flag_svg || country.flag_png"
                                                :src="country.flag_svg ?? country.flag_png ?? undefined"
                                                :alt="`${country.name} flag`"
                                                class="h-4 w-6 rounded-[2px] border border-border object-cover"
                                            />
                                            <span>{{ country.name }}</span>
                                        </div>
                                    </SelectItem>
                                </SelectContent>
                            </Select>
                            <InputError :message="form.errors.country_id" />
                        </div>
                    </div>

                    <div class="grid gap-2">
                        <Label for="overview">Overview</Label>
                        <textarea
                            id="overview"
                            v-model="form.overview"
                            class="flex min-h-28 w-full rounded-md border border-input bg-transparent px-3 py-2 text-sm shadow-xs transition-[color,box-shadow] outline-none placeholder:text-muted-foreground focus-visible:border-ring focus-visible:ring-[3px] focus-visible:ring-ring/50"
                            placeholder="Short place overview"
                        />
                        <InputError :message="form.errors.overview" />
                    </div>


                </form>
            </CardContent>
        </Card>
    </div>
</template>
