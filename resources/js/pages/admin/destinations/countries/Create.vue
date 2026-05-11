<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { Globe, UploadCloudIcon } from 'lucide-vue-next';
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
import { Editor } from '@/components/ui/editor';
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
    overview: '',
    main_image: null as File | null,
    best_time_to_go: '',
    high_season: '',
    tourism_link: '',
});

const countrySearch = ref('');
const mainImagePreview = ref<string | null>(null);

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

const selectedCountryOption = computed(
    () =>
        props.country_options.find((country) => country.name === form.name) ??
        null,
);

function textAreaClass(errorKey?: string): string {
    return cn(
        'flex min-h-28 w-full resize-none rounded-md border border-input bg-transparent px-3 py-2 text-sm shadow-xs transition-[color,box-shadow] outline-none placeholder:text-muted-foreground focus-visible:border-ring focus-visible:ring-[3px] focus-visible:ring-ring/50',
        errorKey && form.errors[errorKey]
            ? 'border-destructive ring-destructive/20'
            : '',
    );
}

function handleMainImageChange(event: Event): void {
    const file = (event.target as HTMLInputElement).files?.[0] ?? null;
    form.main_image = file;

    if (mainImagePreview.value) {
        URL.revokeObjectURL(mainImagePreview.value);
        mainImagePreview.value = null;
    }

    if (file) {
        mainImagePreview.value = URL.createObjectURL(file);
    }
}

function submit(): void {
    form.post(storeCountry.url(), {
        preserveScroll: true,
        forceFormData: true,
    });
}
</script>

<template>
    <Head title="Add Country" />

    <div class="flex h-full flex-1 flex-col gap-6 rounded-xl p-4">
        <div
            class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between"
        >
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
                    <Spinner v-if="form.processing" class="size-4" />
                    Add country
                </Button>
            </div>
        </div>

        <form class="space-y-6" @submit.prevent="submit">
            <Card>
                <CardHeader>
                    <CardTitle>Country details</CardTitle>
                    <CardDescription>
                        Provide the country name. Slug is generated
                        automatically.
                    </CardDescription>
                </CardHeader>
                <CardContent class="space-y-6">
                    <div class="grid gap-2">
                        <Label for="name">Country</Label>
                        <Select v-model="form.name">
                            <SelectTrigger id="name" class="w-full">
                                <SelectValue placeholder="Select country">
                                    <div
                                        v-if="selectedCountryOption"
                                        class="flex items-center gap-2"
                                    >
                                        <img
                                            v-if="
                                                selectedCountryOption.flag_svg ||
                                                selectedCountryOption.flag_png
                                            "
                                            :src="
                                                selectedCountryOption.flag_svg ??
                                                selectedCountryOption.flag_png ??
                                                undefined
                                            "
                                            :alt="`${selectedCountryOption.name} flag`"
                                            class="h-4 w-6 rounded-[2px] border border-border object-cover"
                                        />
                                        <span>{{
                                            selectedCountryOption.name
                                        }}</span>
                                    </div>
                                </SelectValue>
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
                                            v-if="
                                                country.flag_svg ||
                                                country.flag_png
                                            "
                                            :src="
                                                country.flag_svg ??
                                                country.flag_png ??
                                                undefined
                                            "
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

                    <div class="grid gap-2">
                        <Label for="overview">Overview</Label>
                        <Editor
                            v-model="form.overview"
                            :error="!!form.errors.overview"
                            placeholder="General overview of the country..."
                        />
                        <InputError :message="form.errors.overview" />
                    </div>
                </CardContent>
            </Card>

            <Card>
                <CardHeader>
                    <CardTitle>Travel information</CardTitle>
                    <CardDescription>
                        Practical details for travellers.
                    </CardDescription>
                </CardHeader>
                <CardContent class="space-y-6">
                    <div class="grid gap-6 md:grid-cols-2">
                        <div class="grid gap-2">
                            <Label for="best_time_to_go">Best time to go</Label>
                            <textarea
                                id="best_time_to_go"
                                v-model="form.best_time_to_go"
                                :class="textAreaClass('best_time_to_go')"
                                placeholder="When is the best time to visit..."
                            />
                            <InputError
                                :message="form.errors.best_time_to_go"
                            />
                        </div>

                        <div class="grid gap-2">
                            <Label for="high_season">High season</Label>
                            <textarea
                                id="high_season"
                                v-model="form.high_season"
                                :class="textAreaClass('high_season')"
                                placeholder="High season details..."
                            />
                            <InputError :message="form.errors.high_season" />
                        </div>
                    </div>

                    <div class="grid gap-6 md:grid-cols-2">
                        <div class="grid gap-2">
                            <Label for="tourism_link">Tourism link</Label>
                            <div class="relative">
                                <Globe class="absolute left-3 top-1/2 size-4 -translate-y-1/2 text-muted-foreground" />
                                <Input
                                    id="tourism_link"
                                    v-model="form.tourism_link"
                                    placeholder="Official tourism website..."
                                    class="pl-9"
                                />
                            </div>
                            <InputError :message="form.errors.tourism_link" />
                        </div>
                    </div>
                </CardContent>
            </Card>

            <Card>
                <CardHeader>
                    <CardTitle>Media</CardTitle>
                    <CardDescription>
                        Upload a hero image for this country.
                    </CardDescription>
                </CardHeader>
                <CardContent>
                    <div class="grid gap-3">
                        <Label for="main_image">Main image</Label>
                        <div
                            :class="
                                cn(
                                    'relative flex min-h-56 flex-col items-center justify-center gap-3 rounded-lg border-2 border-dashed border-border bg-muted/40 p-6 text-center',
                                    form.errors.main_image &&
                                        'border-destructive',
                                )
                            "
                        >
                            <Input
                                id="main_image"
                                type="file"
                                accept=".png,.jpg,.jpeg,.webp"
                                class="absolute inset-0 h-full w-full cursor-pointer opacity-0"
                                @change="handleMainImageChange"
                            />
                            <img
                                v-if="mainImagePreview"
                                :src="mainImagePreview"
                                alt="Main image preview"
                                class="max-h-56 w-full rounded-md border border-border object-cover"
                            />
                            <template v-else>
                                <UploadCloudIcon
                                    class="h-10 w-10 text-muted-foreground"
                                />
                                <div class="space-y-1">
                                    <p class="text-sm font-medium">
                                        Choose main image
                                    </p>
                                    <p class="text-xs text-muted-foreground">
                                        PNG, JPG, JPEG, or WEBP
                                    </p>
                                </div>
                            </template>
                        </div>
                        <InputError :message="form.errors.main_image" />
                    </div>
                </CardContent>
            </Card>

        </form>
    </div>
</template>
