<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import {
    create as createCountry,
    index as countriesIndex,
    store as storeCountry,
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
import { Label } from '@/components/ui/label';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
} from '@/components/ui/select';
import { Input } from '@/components/ui/input';

type CountryOption = {
    code: string;
    flag_png: string | null;
    flag_svg: string | null;
    name: string;
    official_name: string | null;
};

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
            {
                title: 'Add Country',
                href: createCountry(),
            },
        ],
    },
});

const form = useForm({
    name: '',
});
const countrySearch = ref('');

const props = defineProps<{
    country_options: CountryOption[];
}>();

const filteredCountryOptions = computed(() => {
    const searchValue = countrySearch.value.trim().toLowerCase();

    if (searchValue === '') {
        return props.country_options;
    }

    return props.country_options.filter((country) =>
        [country.name, country.official_name, country.code]
            .filter(Boolean)
            .join(' ')
            .toLowerCase()
            .includes(searchValue),
    );
});

const selectedCountryOption = computed(() =>
    props.country_options.find((country) => country.name === form.name) ?? null,
);

function submit(): void {
    form.post(storeCountry.url(), {
        preserveScroll: true,
    });
}
</script>

<template>
    <Head title="Add Country" />

    <div class="flex h-full flex-1 flex-col gap-6 rounded-xl p-4">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
            <Heading
                title="Add Country"
                description="Create a new destination country."
            />

            <div class="flex gap-2">
                <Button as-child variant="outline">
                    <Link :href="countriesIndex()">Cancel</Link>
                </Button>
                <Button
                    type="button"
                    :disabled="form.processing || !form.name"
                    @click="submit"
                >
                    Save country
                </Button>
            </div>
        </div>

        <Card>
            <CardHeader>
                <CardTitle>Country details</CardTitle>
                <CardDescription>
                    Provide the country name. Slug is generated automatically.
                </CardDescription>
            </CardHeader>
            <CardContent>
                <form class="space-y-6" @submit.prevent="submit">
                    <div class="grid gap-2">
                        <Label for="name">Country</Label>
                        <Select v-model="form.name">
                            <SelectTrigger id="name" class="w-full">
                                <div
                                    v-if="selectedCountryOption"
                                    class="flex items-center gap-2"
                                >
                                    <img
                                        v-if="selectedCountryOption.flag_svg || selectedCountryOption.flag_png"
                                        :src="selectedCountryOption.flag_svg ?? selectedCountryOption.flag_png ?? undefined"
                                        :alt="`${selectedCountryOption.name} flag`"
                                        class="h-4 w-6 rounded-[2px] border border-border object-cover"
                                    />
                                    <span>{{ selectedCountryOption.name }}</span>
                                </div>
                                <span v-else class="text-muted-foreground">Select country</span>
                            </SelectTrigger>
                            <SelectContent>
                                <div class="p-2">
                                    <Input
                                        v-model="countrySearch"
                                        placeholder="Search country..."
                                        @keydown.stop
                                    />
                                </div>
                                <SelectItem
                                    v-for="country in filteredCountryOptions"
                                    :key="country.code"
                                    :value="country.name"
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
                                <p
                                    v-if="filteredCountryOptions.length === 0"
                                    class="px-2 pb-2 text-sm text-muted-foreground"
                                >
                                    No countries found.
                                </p>
                            </SelectContent>
                        </Select>
                        <InputError :message="form.errors.name" />
                    </div>

                    <div class="flex flex-col-reverse gap-3 border-t border-border pt-6 sm:flex-row sm:justify-end">
                        <Button as-child type="button" variant="outline">
                            <Link :href="countriesIndex()">Cancel</Link>
                        </Button>
                        <Button type="submit" :disabled="form.processing || !form.name">
                            Save country
                        </Button>
                    </div>
                </form>
            </CardContent>
        </Card>
    </div>
</template>
