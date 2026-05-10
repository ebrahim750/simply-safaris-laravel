<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { UploadCloudIcon } from 'lucide-vue-next';
import { ref } from 'vue';
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
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { cn } from '@/lib/utils';

type Country = {
    id: number;
    name: string;
    slug: string;
    overview: string | null;
    main_image: string | null;
    best_time_to_go: string | null;
    high_season: string | null;
    electrical: string | null;
    tourist_info: string | null;
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
    overview: props.country.overview ?? '',
    main_image: null as File | null,
    best_time_to_go: props.country.best_time_to_go ?? '',
    high_season: props.country.high_season ?? '',
    electrical: props.country.electrical ?? '',
    tourist_info: props.country.tourist_info ?? '',
});

const mainImagePreview = ref<string | null>(props.country.main_image);

function textAreaClass(errorKey?: string): string {
    return cn(
        'flex min-h-28 w-full rounded-md border border-input bg-transparent px-3 py-2 text-sm shadow-xs transition-[color,box-shadow] outline-none placeholder:text-muted-foreground focus-visible:border-ring focus-visible:ring-[3px] focus-visible:ring-ring/50',
        errorKey && form.errors[errorKey]
            ? 'border-destructive ring-destructive/20'
            : '',
    );
}

function handleMainImageChange(event: Event): void {
    const file = (event.target as HTMLInputElement).files?.[0] ?? null;
    form.main_image = file;

    if (mainImagePreview.value && !props.country.main_image) {
        URL.revokeObjectURL(mainImagePreview.value);
    }

    mainImagePreview.value = file ? URL.createObjectURL(file) : props.country.main_image;
}

function submit(): void {
    form.post(updateCountry.url({ country: props.country.id }), {
        preserveScroll: true,
        forceFormData: true,
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

        <form class="space-y-6" @submit.prevent="submit">
            <Card>
                <CardHeader>
                    <CardTitle>Country details</CardTitle>
                    <CardDescription>
                        Slug is auto-generated from the country name.
                    </CardDescription>
                </CardHeader>
                <CardContent class="space-y-6">
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

                    <div class="grid gap-2">
                        <Label for="overview">Overview</Label>
                        <textarea
                            id="overview"
                            v-model="form.overview"
                            :class="textAreaClass('overview')"
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
                            <InputError :message="form.errors.best_time_to_go" />
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

                    <div class="grid gap-6 md:grid-cols-3">
                        <div class="grid gap-2">
                            <Label for="electrical">Electrical</Label>
                            <textarea
                                id="electrical"
                                v-model="form.electrical"
                                :class="textAreaClass('electrical')"
                                placeholder="Voltage, plug types..."
                            />
                            <InputError :message="form.errors.electrical" />
                        </div>

                        <div class="grid gap-2">
                            <Label for="tourist_info">Tourist info</Label>
                            <Input
                                id="tourist_info"
                                v-model="form.tourist_info"
                                placeholder="Official tourism website..."
                            />
                            <InputError :message="form.errors.tourist_info" />
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
                            :class="cn(
                                'relative flex min-h-56 flex-col items-center justify-center gap-3 rounded-lg border-2 border-dashed border-border bg-muted/40 p-6 text-center',
                                form.errors.main_image && 'border-destructive',
                            )"
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
                                <UploadCloudIcon class="h-10 w-10 text-muted-foreground" />
                                <div class="space-y-1">
                                    <p class="text-sm font-medium">Choose main image</p>
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

            <div class="flex flex-col-reverse gap-3 pt-6 sm:flex-row sm:justify-end">
                <Button as-child type="button" variant="outline">
                    <Link :href="countriesIndex()">Cancel</Link>
                </Button>
                <Button type="submit" :disabled="form.processing">
                    Save changes
                </Button>
            </div>
        </form>
    </div>
</template>
