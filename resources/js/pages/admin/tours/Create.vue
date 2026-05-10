<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { Plus, Trash2, UploadCloudIcon } from 'lucide-vue-next';
import { ref } from 'vue';
import {
    create as createTour,
    store as storeTour,
} from '@/actions/App/Http/Controllers/Admin/TourController';
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
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Switch } from '@/components/ui/switch';
import { cn } from '@/lib/utils';
import { tours as adminTours } from '@/routes/admin';

type TourOption = {
    id: number;
    name: string;
};

type PlaceOption = TourOption & {
    country_id: number;
    country_name: string;
};

type ItineraryItemForm = {
    title: string;
    content: string;
};

type TextRowForm = {
    text: string;
};

const props = defineProps<{
    categories: TourOption[];
    activities: TourOption[];
    places: PlaceOption[];
}>();

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Tours',
                href: adminTours(),
            },
            {
                title: 'New Tour',
                href: createTour(),
            },
        ],
    },
});

const form = useForm({
    name: '',
    price: '',
    main_image: null as File | null,
    gallery_images: [] as File[],
    external_url: '',
    external_link_button_text: '',
    duration_days: '',
    overview: '',
    pricing_detailed: '',
    is_hidden: false,
    is_featured_on_home: false,
    is_featured_as_seasonal_recommendation: false,
    category_ids: [] as number[],
    activity_ids: [] as number[],
    place_ids: [] as number[],
    itinerary_items: [] as ItineraryItemForm[],
    inclusions: [] as TextRowForm[],
    exclusions: [] as TextRowForm[],
});

const mainImagePreview = ref<string | null>(null);
const galleryImagePreviews = ref<string[]>([]);

function textAreaClass(errorKey?: string): string {
    return cn(
        'flex min-h-28 w-full rounded-md border border-input bg-transparent px-3 py-2 text-sm shadow-xs transition-[color,box-shadow] outline-none placeholder:text-muted-foreground focus-visible:border-ring focus-visible:ring-[3px] focus-visible:ring-ring/50',
        errorKey && form.errors[errorKey]
            ? 'border-destructive ring-destructive/20'
            : '',
    );
}

function setBooleanField(
    field:
        | 'is_hidden'
        | 'is_featured_on_home'
        | 'is_featured_as_seasonal_recommendation',
    value: boolean | 'indeterminate',
): void {
    form[field] = value === true;
}

function toggleSelection(
    field: 'category_ids' | 'activity_ids' | 'place_ids',
    id: number,
    value: boolean | 'indeterminate',
): void {
    const selectedIds = [...form[field]];

    if (value === true) {
        if (! selectedIds.includes(id)) {
            selectedIds.push(id);
        }
    } else {
        const nextIds = selectedIds.filter((selectedId) => selectedId !== id);
        form[field] = nextIds;

        return;
    }

    form[field] = selectedIds;
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

function handleGalleryImagesChange(event: Event): void {
    const files = Array.from((event.target as HTMLInputElement).files ?? []);
    form.gallery_images = files;

    for (const preview of galleryImagePreviews.value) {
        URL.revokeObjectURL(preview);
    }

    galleryImagePreviews.value = files.map((file) => URL.createObjectURL(file));
}

function addItineraryItem(): void {
    form.itinerary_items.push({
        title: '',
        content: '',
    });
}

function removeItineraryItem(index: number): void {
    form.itinerary_items.splice(index, 1);
}

function addInclusion(): void {
    form.inclusions.push({
        text: '',
    });
}

function removeInclusion(index: number): void {
    form.inclusions.splice(index, 1);
}

function addExclusion(): void {
    form.exclusions.push({
        text: '',
    });
}

function removeExclusion(index: number): void {
    form.exclusions.splice(index, 1);
}

function fieldError(key: string): string | undefined {
    return form.errors[key];
}

function submit(): void {
    form.post(storeTour.url(), {
        preserveScroll: true,
        forceFormData: true,
    });
}
</script>

<template>
    <Head title="New Tour" />

    <div class="flex h-full flex-1 flex-col gap-6 rounded-xl p-4">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
            <Heading
                title="New Tour"
                description="Create a new tour with media, flags, places, and structured trip details."
            />

            <div class="flex gap-2">
                <Button as-child variant="outline">
                    <Link :href="adminTours()">Cancel</Link>
                </Button>
                <Button
                    type="button"
                    :disabled="form.processing"
                    @click="submit"
                >
                    Create tour
                </Button>
            </div>
        </div>

        <form class="space-y-6" @submit.prevent="submit">
            <div class="grid gap-6 lg:grid-cols-[3fr_1fr]">
                <div class="space-y-6">
                    <Card>
                        <CardHeader>
                            <CardTitle>Tour details</CardTitle>
                            <CardDescription>
                                Fill in the main details first, then attach media and related tour content.
                            </CardDescription>
                        </CardHeader>
                        <CardContent>
                            <div class="grid gap-6 md:grid-cols-2">
                                <div class="grid gap-2">
                                    <Label for="name">Name</Label>
                                    <Input
                                        id="name"
                                        v-model="form.name"
                                        placeholder="7-Day Serengeti Explorer"
                                        required
                                    />
                                    <InputError :message="form.errors.name" />
                                </div>

                                <div class="grid gap-2">
                                    <Label for="price">Price</Label>
                                    <Input
                                        id="price"
                                        v-model="form.price"
                                        inputmode="decimal"
                                        placeholder="12500.00"
                                        required
                                    />
                                    <InputError :message="form.errors.price" />
                                </div>

                                <div class="grid gap-2">
                                    <Label for="duration_days">Duration in days</Label>
                                    <Input
                                        id="duration_days"
                                        v-model="form.duration_days"
                                        inputmode="numeric"
                                        placeholder="7"
                                    />
                                    <InputError :message="form.errors.duration_days" />
                                </div>

                                <div class="grid gap-2">
                                    <Label for="external_url">External booking URL</Label>
                                    <Input
                                        id="external_url"
                                        v-model="form.external_url"
                                        type="url"
                                        placeholder="https://example.com/book"
                                    />
                                    <InputError :message="form.errors.external_url" />
                                </div>

                                <div class="grid gap-2 md:col-span-2">
                                    <Label for="external_link_button_text">External link button text</Label>
                                    <Input
                                        id="external_link_button_text"
                                        v-model="form.external_link_button_text"
                                        placeholder="Book now"
                                    />
                                    <InputError :message="form.errors.external_link_button_text" />
                                </div>

                                <div class="grid gap-2 md:col-span-2">
                                    <Label for="overview">Overview</Label>
                                    <textarea
                                        id="overview"
                                        v-model="form.overview"
                                        :class="textAreaClass('overview')"
                                        placeholder="Summarize the experience, highlights, and ideal traveler fit."
                                    />
                                    <InputError :message="form.errors.overview" />
                                </div>

                                <div class="grid gap-2 md:col-span-2">
                                    <Label for="pricing_detailed">Detailed pricing</Label>
                                    <textarea
                                        id="pricing_detailed"
                                        v-model="form.pricing_detailed"
                                        :class="textAreaClass('pricing_detailed')"
                                        placeholder="Break down seasonal pricing, occupancy, or package notes."
                                    />
                                    <InputError :message="form.errors.pricing_detailed" />
                                </div>
                            </div>
                        </CardContent>
                    </Card>

                    <Card>
                        <CardHeader>
                            <CardTitle>Media</CardTitle>
                            <CardDescription>
                                Upload the primary hero image and any additional gallery images.
                            </CardDescription>
                        </CardHeader>
                        <CardContent class="space-y-4">
                            <div class="grid gap-6 lg:grid-cols-2">
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

                                <div class="grid gap-3">
                                    <Label for="gallery_images">Gallery images</Label>
                                    <div
                                        :class="cn(
                                            'relative flex min-h-56 flex-col items-center justify-center gap-3 rounded-lg border-2 border-dashed border-border bg-muted/40 p-6 text-center',
                                            (form.errors.gallery_images || form.errors['gallery_images.0']) && 'border-destructive',
                                        )"
                                    >
                                        <Input
                                            id="gallery_images"
                                            type="file"
                                            multiple
                                            accept=".png,.jpg,.jpeg,.webp"
                                            class="absolute inset-0 h-full w-full cursor-pointer opacity-0"
                                            @change="handleGalleryImagesChange"
                                        />
                                        <div
                                            v-if="galleryImagePreviews.length"
                                            class="grid w-full grid-cols-2 gap-3"
                                        >
                                            <img
                                                v-for="(preview, index) in galleryImagePreviews"
                                                :key="preview"
                                                :src="preview"
                                                :alt="`Gallery image preview ${index + 1}`"
                                                class="h-28 w-full rounded-md border border-border object-cover"
                                            />
                                        </div>
                                        <template v-else>
                                            <UploadCloudIcon class="h-10 w-10 text-muted-foreground" />
                                            <div class="space-y-1">
                                                <p class="text-sm font-medium">Choose gallery images</p>
                                                <p class="text-xs text-muted-foreground">
                                                    Upload one or more supporting images
                                                </p>
                                            </div>
                                        </template>
                                    </div>
                                    <InputError :message="form.errors.gallery_images" />
                                    <InputError :message="fieldError('gallery_images.0')" />
                                </div>
                            </div>
                        </CardContent>
                    </Card>

                    <Card>
                        <CardHeader>
                            <div class="flex items-center justify-between gap-3">
                                <div class="space-y-1">
                                    <CardTitle>Itinerary</CardTitle>
                                    <CardDescription>
                                        Add ordered itinerary steps for the trip.
                                    </CardDescription>
                                </div>
                                <Button
                                    type="button"
                                    variant="outline"
                                    @click="addItineraryItem"
                                >
                                    <Plus class="size-4" />
                                    Add item
                                </Button>
                            </div>
                        </CardHeader>
                        <CardContent class="space-y-4">
                            <div
                                v-if="form.itinerary_items.length"
                                class="space-y-4"
                            >
                                <div
                                    v-for="(item, index) in form.itinerary_items"
                                    :key="index"
                                    class="rounded-lg border border-border p-4"
                                >
                                    <div class="mb-4 flex items-center justify-between gap-3">
                                        <p class="text-sm font-medium">Stop {{ index + 1 }}</p>
                                        <Button
                                            type="button"
                                            variant="ghost"
                                            class="text-destructive hover:text-destructive"
                                            @click="removeItineraryItem(index)"
                                        >
                                            <Trash2 class="size-4" />
                                            Remove
                                        </Button>
                                    </div>

                                    <div class="grid gap-4">
                                        <div class="grid gap-2">
                                            <Label :for="`itinerary_title_${index}`">Title</Label>
                                            <Input
                                                :id="`itinerary_title_${index}`"
                                                v-model="item.title"
                                                placeholder="Day 1: Arrival in Arusha"
                                            />
                                            <InputError :message="fieldError(`itinerary_items.${index}.title`)" />
                                        </div>

                                        <div class="grid gap-2">
                                            <Label :for="`itinerary_content_${index}`">Content</Label>
                                            <textarea
                                                :id="`itinerary_content_${index}`"
                                                v-model="item.content"
                                                :class="textAreaClass(`itinerary_items.${index}.content`)"
                                                placeholder="Describe what happens during this itinerary step."
                                            />
                                            <InputError :message="fieldError(`itinerary_items.${index}.content`)" />
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div
                                v-else
                                class="rounded-lg border border-dashed border-border p-6 text-sm text-muted-foreground"
                            >
                                No itinerary items added yet.
                            </div>
                        </CardContent>
                    </Card>

                    <div class="grid gap-6 lg:grid-cols-2">
                        <Card class="border-dashed">
                            <CardHeader>
                                <div class="flex items-center justify-between gap-3">
                                    <div class="space-y-1">
                                        <CardTitle class="text-base">Inclusions</CardTitle>
                                        <CardDescription>What is covered by the tour.</CardDescription>
                                    </div>
                                    <Button
                                        type="button"
                                        variant="outline"
                                        @click="addInclusion"
                                    >
                                        <Plus class="size-4" />
                                        Add
                                    </Button>
                                </div>
                            </CardHeader>
                            <CardContent class="space-y-3">
                                <div
                                    v-if="form.inclusions.length"
                                    class="space-y-3"
                                >
                                    <div
                                        v-for="(inclusion, index) in form.inclusions"
                                        :key="index"
                                        class="rounded-md border border-border p-3"
                                    >
                                        <div class="mb-3 flex items-center justify-between gap-3">
                                            <p class="text-sm font-medium">Inclusion {{ index + 1 }}</p>
                                            <Button
                                                type="button"
                                                variant="ghost"
                                                class="text-destructive hover:text-destructive"
                                                @click="removeInclusion(index)"
                                            >
                                                <Trash2 class="size-4" />
                                                Remove
                                            </Button>
                                        </div>
                                        <Input
                                            v-model="inclusion.text"
                                            placeholder="Airport transfers"
                                        />
                                        <InputError :message="fieldError(`inclusions.${index}.text`)" />
                                    </div>
                                </div>
                                <p
                                    v-else
                                    class="text-sm text-muted-foreground"
                                >
                                    No inclusions added yet.
                                </p>
                            </CardContent>
                        </Card>

                        <Card class="border-dashed">
                            <CardHeader>
                                <div class="flex items-center justify-between gap-3">
                                    <div class="space-y-1">
                                        <CardTitle class="text-base">Exclusions</CardTitle>
                                        <CardDescription>What travelers need to cover separately.</CardDescription>
                                    </div>
                                    <Button
                                        type="button"
                                        variant="outline"
                                        @click="addExclusion"
                                    >
                                        <Plus class="size-4" />
                                        Add
                                    </Button>
                                </div>
                            </CardHeader>
                            <CardContent class="space-y-3">
                                <div
                                    v-if="form.exclusions.length"
                                    class="space-y-3"
                                >
                                    <div
                                        v-for="(exclusion, index) in form.exclusions"
                                        :key="index"
                                        class="rounded-md border border-border p-3"
                                    >
                                        <div class="mb-3 flex items-center justify-between gap-3">
                                            <p class="text-sm font-medium">Exclusion {{ index + 1 }}</p>
                                            <Button
                                                type="button"
                                                variant="ghost"
                                                class="text-destructive hover:text-destructive"
                                                @click="removeExclusion(index)"
                                            >
                                                <Trash2 class="size-4" />
                                                Remove
                                            </Button>
                                        </div>
                                        <Input
                                            v-model="exclusion.text"
                                            placeholder="International flights"
                                        />
                                        <InputError :message="fieldError(`exclusions.${index}.text`)" />
                                    </div>
                                </div>
                                <p
                                    v-else
                                    class="text-sm text-muted-foreground"
                                >
                                    No exclusions added yet.
                                </p>
                            </CardContent>
                        </Card>
                    </div>
                </div>

                <div class="space-y-6 lg:sticky lg:top-6 lg:self-start">
                    <Card class="border-dashed">
                        <CardHeader>
                            <CardTitle class="text-base">Places</CardTitle>
                            <CardDescription>Attach places in the order travelers should see them.</CardDescription>
                        </CardHeader>
                        <CardContent class="space-y-3">
                            <div
                                v-if="props.places.length"
                                class="space-y-2"
                            >
                                <label
                                    v-for="place in props.places"
                                    :key="place.id"
                                    class="flex items-start gap-3 rounded-md border border-border p-3"
                                >
                                    <Checkbox
                                        :model-value="form.place_ids.includes(place.id)"
                                        @update:model-value="
                                            toggleSelection('place_ids', place.id, $event)
                                        "
                                    />
                                    <div class="space-y-1">
                                        <p class="text-sm font-medium">{{ place.name }}</p>
                                        <p class="text-xs uppercase tracking-wide text-muted-foreground">
                                            {{ place.country_name }}
                                        </p>
                                    </div>
                                </label>
                            </div>
                            <p
                                v-else
                                class="text-sm text-muted-foreground"
                            >
                                No places are available yet.
                            </p>
                            <InputError :message="fieldError('place_ids')" />
                            <InputError :message="fieldError('place_ids.0')" />
                        </CardContent>
                    </Card>

                    <Card class="border-dashed">
                        <CardHeader>
                            <CardTitle class="text-base">Categories</CardTitle>
                            <CardDescription>Select any matching tour categories.</CardDescription>
                        </CardHeader>
                        <CardContent class="space-y-3">
                            <div
                                v-if="props.categories.length"
                                class="space-y-2"
                            >
                                <label
                                    v-for="category in props.categories"
                                    :key="category.id"
                                    class="flex items-center gap-3 rounded-md border border-border p-3"
                                >
                                    <Checkbox
                                        :model-value="form.category_ids.includes(category.id)"
                                        @update:model-value="
                                            toggleSelection('category_ids', category.id, $event)
                                        "
                                    />
                                    <span class="text-sm font-medium">{{ category.name }}</span>
                                </label>
                            </div>
                            <p
                                v-else
                                class="text-sm text-muted-foreground"
                            >
                                No categories are available yet.
                            </p>
                            <InputError :message="fieldError('category_ids')" />
                            <InputError :message="fieldError('category_ids.0')" />
                        </CardContent>
                    </Card>

                    <Card class="border-dashed">
                        <CardHeader>
                            <CardTitle class="text-base">Activities</CardTitle>
                            <CardDescription>Select activities included in the tour.</CardDescription>
                        </CardHeader>
                        <CardContent class="space-y-3">
                            <div
                                v-if="props.activities.length"
                                class="space-y-2"
                            >
                                <label
                                    v-for="activity in props.activities"
                                    :key="activity.id"
                                    class="flex items-center gap-3 rounded-md border border-border p-3"
                                >
                                    <Checkbox
                                        :model-value="form.activity_ids.includes(activity.id)"
                                        @update:model-value="
                                            toggleSelection('activity_ids', activity.id, $event)
                                        "
                                    />
                                    <span class="text-sm font-medium">{{ activity.name }}</span>
                                </label>
                            </div>
                            <p
                                v-else
                                class="text-sm text-muted-foreground"
                            >
                                No activities are available yet.
                            </p>
                            <InputError :message="fieldError('activity_ids')" />
                            <InputError :message="fieldError('activity_ids.0')" />
                        </CardContent>
                    </Card>

                    <Card class="border-dashed">
                        <CardHeader>
                            <CardTitle class="text-base">Featuring</CardTitle>
                            <CardDescription>Control homepage placement and recommendations.</CardDescription>
                        </CardHeader>
                        <CardContent class="space-y-3">
                            <label class="flex items-start gap-3 rounded-lg border border-border p-4">
                                <Checkbox
                                    :model-value="form.is_featured_on_home"
                                    @update:model-value="setBooleanField('is_featured_on_home', $event)"
                                />
                                <div class="space-y-1">
                                    <p class="font-medium">Featured on home</p>
                                    <p class="text-sm text-muted-foreground">
                                        Make the tour eligible for homepage highlights.
                                    </p>
                                </div>
                            </label>

                            <label class="flex items-start gap-3 rounded-lg border border-border p-4">
                                <Checkbox
                                    :model-value="form.is_featured_as_seasonal_recommendation"
                                    @update:model-value="setBooleanField('is_featured_as_seasonal_recommendation', $event)"
                                />
                                <div class="space-y-1">
                                    <p class="font-medium">Seasonal recommendation</p>
                                    <p class="text-sm text-muted-foreground">
                                        Mark the tour for seasonal recommendations.
                                    </p>
                                </div>
                            </label>
                        </CardContent>
                    </Card>

                    <Card class="border-dashed">
                        <CardHeader>
                            <CardTitle class="text-base">Visibility</CardTitle>
                            <CardDescription>Control whether this tour is publicly visible.</CardDescription>
                        </CardHeader>
                        <CardContent>
                            <div class="flex items-center justify-between gap-3 rounded-lg border border-border p-4">
                                <div class="space-y-1">
                                    <p class="font-medium">Hidden</p>
                                    <p class="text-sm text-muted-foreground">
                                        Keep this tour out of public view.
                                    </p>
                                </div>
                                <Switch
                                    :model-value="form.is_hidden"
                                    @update:model-value="setBooleanField('is_hidden', $event)"
                                />
                            </div>
                        </CardContent>
                    </Card>
                </div>
            </div>

            <div class="flex flex-col-reverse gap-3 pt-6 sm:flex-row sm:justify-end">
                <Button as-child type="button" variant="outline">
                    <Link :href="adminTours()">Cancel</Link>
                </Button>
                <Button type="submit" :disabled="form.processing">
                    Create tour
                </Button>
            </div>
        </form>
    </div>
</template>
