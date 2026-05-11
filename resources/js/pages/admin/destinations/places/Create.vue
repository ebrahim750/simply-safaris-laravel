<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { UploadCloudIcon } from 'lucide-vue-next';
import { computed, ref } from 'vue';
import { index as countriesIndex } from '@/actions/App/Http/Controllers/Admin/DestinationCountryController';
import {
    create as createPlace,
    index as placesIndex,
    store as storePlace,
} from '@/actions/App/Http/Controllers/Admin/DestinationPlaceController';
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
} from '@/components/ui/select';
import { Spinner } from '@/components/ui/spinner';
import { cn } from '@/lib/utils';

type CountryOption = {
    id: number;
    name: string;
    country_code: string | null;
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
    main_image: null as File | null,
});

const mainImagePreview = ref<string | null>(null);

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
    form.post(storePlace.url(), {
        preserveScroll: true,
        forceFormData: true,
    });
}
</script>

<template>
    <Head title="Add Place" />

    <div class="flex h-full flex-1 flex-col gap-6 rounded-xl p-4">
        <div
            class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between"
        >
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
                    :disabled="form.processing || !form.name || !form.country_id"
                    @click="submit"
                >
                    <Spinner v-if="form.processing" class="size-4" />
                    Add place
                </Button>
            </div>
        </div>

        <Card>
            <CardHeader>
                <CardTitle>Place details</CardTitle>

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
                                            v-if="selectedCountry.country_code"
                                            :src="`https://flagcdn.com/w40/${selectedCountry.country_code}.png`"
                                            :alt="`${selectedCountry.name} flag`"
                                            class="h-4 w-6 rounded-[2px] border border-border object-cover"
                                        />
                                        <span>{{ selectedCountry.name }}</span>
                                    </div>
                                    <span v-else class="text-muted-foreground"
                                        >Select country</span
                                    >
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem
                                        v-for="country in props.countries"
                                        :key="country.id"
                                        :value="String(country.id)"
                                    >
                                        <div class="flex items-center gap-2">
                                            <img
                                                v-if="country.country_code"
                                                :src="`https://flagcdn.com/w40/${country.country_code}.png`"
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
                        <Editor
                            v-model="form.overview"
                            :error="!!form.errors.overview"
                            placeholder="General overview of the place..."
                        />
                        <InputError :message="form.errors.overview" />
                    </div>

                    <div class="grid gap-2">
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
                </form>
            </CardContent>
        </Card>
    </div>
</template>
