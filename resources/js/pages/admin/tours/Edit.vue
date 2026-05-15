<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { Plus, Trash2, UploadCloudIcon, X } from 'lucide-vue-next';
import { computed, ref } from 'vue';
import {
    edit as editTour,
    update as updateTour,
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
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import { Editor } from '@/components/ui/editor';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Switch } from '@/components/ui/switch';
import { cn } from '@/lib/utils';
import { tours as adminTours } from '@/routes/admin';

type TourOption = {
    id: number;
    name: string;
    image: string | null;
};

type PlaceOption = TourOption & {
    country_id: number;
    country_name: string;
    country_code: string | null;
};

type ItineraryItemForm = {
    title: string;
    content: string;
};

type TextRowForm = {
    text: string;
};

type Tour = {
    id: number;
    name: string;
    price: number;
    main_image: string | null;
    gallery_images: string[];
    external_url: string | null;
    external_link_button_text: string | null;
    show_external_booking: boolean;
    duration_days: number | null;
    overview: string | null;
    pricing_detailed: string | null;
    is_hidden: boolean;
    is_featured_on_home: boolean;
    is_featured_as_seasonal_recommendation: boolean;
    category_ids: number[];
    activity_ids: number[];
    place_ids: number[];
    itinerary_items: ItineraryItemForm[];
    inclusions: TextRowForm[];
    exclusions: TextRowForm[];
};

const props = defineProps<{
    currency: string;
    tour: Tour;
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
                title: 'Edit',
                href: editTour.url({ tour: 0 }),
            },
        ],
    },
});

const form = useForm({
    name: props.tour.name,
    price: String(Math.floor(props.tour.price)),
    main_image: null as File | null,
    gallery_images: [] as File[],
    external_url: props.tour.external_url ?? '',
    external_link_button_text: props.tour.external_link_button_text ?? '',
    show_external_booking: props.tour.show_external_booking,
    duration_days: props.tour.duration_days ? String(props.tour.duration_days) : '',
    overview: props.tour.overview ?? '',
    pricing_detailed: props.tour.pricing_detailed ?? '',
    is_hidden: props.tour.is_hidden,
    is_featured_on_home: props.tour.is_featured_on_home,
    is_featured_as_seasonal_recommendation: props.tour.is_featured_as_seasonal_recommendation,
    category_ids: props.tour.category_ids,
    activity_ids: props.tour.activity_ids,
    place_ids: props.tour.place_ids,
    itinerary_items: props.tour.itinerary_items,
    inclusions: props.tour.inclusions,
    exclusions: props.tour.exclusions,
});

const mainImagePreview = ref<string | null>(props.tour.main_image);
const galleryImagePreviews = ref<string[]>([...props.tour.gallery_images]);
const draggedGalleryIndex = ref<number | null>(null);
const pricingDialogOpen = ref(false);
const categorySearch = ref('');
const activitySearch = ref('');

const placesByCountry = computed(() => {
    const grouped = new Map<
        number,
        {
            country_id: number;
            country_name: string;
            country_code: string | null;
            places: PlaceOption[];
        }
    >();

    for (const place of props.places) {
        const existing = grouped.get(place.country_id);
        if (existing) {
            existing.places.push(place);
        } else {
            grouped.set(place.country_id, {
                country_id: place.country_id,
                country_name: place.country_name,
                country_code: place.country_code,
                places: [place],
            });
        }
    }

    return Array.from(grouped.values()).sort((a, b) =>
        a.country_name.localeCompare(b.country_name),
    );
});

const filteredCategories = computed(() =>
    props.categories.filter((c) =>
        c.name.toLowerCase().includes(categorySearch.value.toLowerCase()),
    ),
);

const filteredActivities = computed(() =>
    props.activities.filter((a) =>
        a.name.toLowerCase().includes(activitySearch.value.toLowerCase()),
    ),
);

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
        | 'is_featured_as_seasonal_recommendation'
        | 'show_external_booking',
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
        if (!selectedIds.includes(id)) {
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

    if (mainImagePreview.value && !props.tour.main_image) {
        URL.revokeObjectURL(mainImagePreview.value);
    }

    mainImagePreview.value = file
        ? URL.createObjectURL(file)
        : props.tour.main_image;
}

function removeMainImage(): void {
    if (mainImagePreview.value && !props.tour.main_image) {
        URL.revokeObjectURL(mainImagePreview.value);
    }

    mainImagePreview.value = null;
    form.main_image = null;
}

function handleGalleryImagesChange(event: Event): void {
    const files = Array.from((event.target as HTMLInputElement).files ?? []);

    form.gallery_images = [...form.gallery_images, ...files];
    galleryImagePreviews.value = [
        ...galleryImagePreviews.value,
        ...files.map((file) => URL.createObjectURL(file)),
    ];

    (event.target as HTMLInputElement).value = '';
}

function removeGalleryImage(index: number): void {
    const preview = galleryImagePreviews.value[index];
    // Only revoke if it's a blob URL (newly uploaded), not an existing server URL
    if (preview.startsWith('blob:')) {
        URL.revokeObjectURL(preview);
    }
    galleryImagePreviews.value.splice(index, 1);
    form.gallery_images.splice(index, 1);
}

function onGalleryDragStart(event: DragEvent, index: number): void {
    draggedGalleryIndex.value = index;
    event.dataTransfer?.setData('text/plain', String(index));
    event.dataTransfer!.effectAllowed = 'move';
}

function onGalleryDragOver(event: DragEvent): void {
    event.preventDefault();
    event.dataTransfer!.dropEffect = 'move';
}

function onGalleryDrop(event: DragEvent, targetIndex: number): void {
    event.preventDefault();
    const sourceIndex = draggedGalleryIndex.value;

    if (sourceIndex === null || sourceIndex === targetIndex) {
        draggedGalleryIndex.value = null;

        return;
    }

    const files = [...form.gallery_images];
    const previews = [...galleryImagePreviews.value];
    const [movedFile] = files.splice(sourceIndex, 1);
    const [movedPreview] = previews.splice(sourceIndex, 1);

    files.splice(targetIndex, 0, movedFile);
    previews.splice(targetIndex, 0, movedPreview);

    form.gallery_images = files;
    galleryImagePreviews.value = previews;
    draggedGalleryIndex.value = null;
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
    form.patch(updateTour.url({ tour: props.tour.id }), {
        preserveScroll: true,
        forceFormData: true,
    });
}
</script>

<template>
    <Head :title="`Edit ${props.tour.name}`" />

    <div class="flex h-full flex-1 flex-col gap-6 rounded-xl p-4">
        <div
            class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between"
        >
            <Heading
                :title="`Edit ${props.tour.name}`"
                description="Update tour details, media, and related content."
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
                    Save changes
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
                                Fill in the main details first, then attach
                                media and related tour content.
                            </CardDescription>
                        </CardHeader>
                        <CardContent>
                            <div class="grid gap-6 md:grid-cols-2">
                                <div class="grid gap-2">
                                    <Label for="name">Tour name</Label>
                                    <Input
                                        id="name"
                                        v-model="form.name"
                                        placeholder="7-Day Serengeti Explorer"
                                        required
                                    />
                                    <InputError :message="form.errors.name" />
                                </div>

                                <div class="grid grid-cols-2 gap-6">
                                    <div class="grid gap-2">
                                        <div class="flex items-center justify-between">
                                            <Label for="price">Price</Label>
                                            <button
                                                type="button"
                                                class="cursor-pointer text-xs text-muted-foreground hover:text-foreground"
                                                @click="pricingDialogOpen = true"
                                            >
                                                Edit detailed pricing
                                            </button>
                                        </div>
                                        <div
                                            class="flex rounded-md border border-input shadow-xs"
                                        >
                                            <div
                                                class="flex items-center justify-center border-r border-input bg-muted px-3 text-sm text-muted-foreground"
                                            >
                                                {{ props.currency }}
                                            </div>
                                            <Input
                                                id="price"
                                                v-model="form.price"
                                                inputmode="numeric"
                                                placeholder="12500"
                                                class="border-0 shadow-none focus-visible:ring-0"
                                                required
                                            />
                                        </div>
                                        <InputError
                                            :message="form.errors.price"
                                        />
                                    </div>

                                    <div class="grid gap-2">
                                        <Label for="duration_days"
                                            >Duration</Label
                                        >
                                        <div
                                            class="flex rounded-md border border-input shadow-xs"
                                        >
                                            <Input
                                                id="duration_days"
                                                v-model="form.duration_days"
                                                inputmode="numeric"
                                                placeholder="7"
                                                class="border-0 shadow-none focus-visible:ring-0"
                                            />
                                            <div
                                                class="flex items-center justify-center border-l border-input bg-muted px-3 text-sm text-muted-foreground"
                                            >
                                                days
                                            </div>
                                        </div>
                                        <InputError
                                            :message="form.errors.duration_days"
                                        />
                                    </div>
                                </div>

                                <div class="grid gap-2 md:col-span-2">
                                    <Label for="overview">Overview</Label>
                                    <Editor
                                        v-model="form.overview"
                                        :error="!!form.errors.overview"
                                        placeholder="Summarize the experience, highlights, and ideal traveler fit."
                                    />
                                    <InputError
                                        :message="form.errors.overview"
                                    />
                                </div>
                            </div>
                        </CardContent>
                    </Card>

                    <Card>
                        <CardHeader>
                            <CardTitle>Media</CardTitle>
                            <CardDescription>
                                Upload the primary hero image and any additional
                                gallery images.
                            </CardDescription>
                        </CardHeader>
                        <CardContent class="space-y-6">
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
                                    <div
                                        v-if="mainImagePreview"
                                        class="group relative w-full"
                                    >
                                        <img
                                            :src="mainImagePreview"
                                            alt="Main image preview"
                                            class="max-h-56 w-full rounded-md border border-border object-cover"
                                        />
                                        <button
                                            type="button"
                                            class="absolute top-2 right-2 flex h-7 w-7 cursor-pointer items-center justify-center rounded-full bg-background/90 text-foreground opacity-0 shadow-sm transition-opacity group-hover:opacity-100 hover:bg-destructive hover:text-destructive-foreground"
                                            @click="removeMainImage"
                                        >
                                            <X class="h-3.5 w-3.5" />
                                        </button>
                                    </div>
                                    <template v-else>
                                        <UploadCloudIcon
                                            class="h-10 w-10 text-muted-foreground"
                                        />
                                        <div class="space-y-1">
                                            <p class="text-sm font-medium">
                                                Choose main image
                                            </p>
                                            <p
                                                class="text-xs text-muted-foreground"
                                            >
                                                PNG, JPG, JPEG, or WEBP
                                            </p>
                                        </div>
                                    </template>
                                </div>
                                <InputError :message="form.errors.main_image" />
                            </div>

                            <div class="grid gap-3">
                                <div class="flex items-center justify-between">
                                    <Label for="gallery_images"
                                        >Gallery images</Label
                                    >
                                    <span
                                        v-if="galleryImagePreviews.length"
                                        class="text-xs text-muted-foreground"
                                    >
                                        {{ galleryImagePreviews.length }}
                                        image{{
                                            galleryImagePreviews.length === 1
                                                ? ''
                                                : 's'
                                        }}
                                    </span>
                                </div>

                                <div
                                    v-if="galleryImagePreviews.length"
                                    class="grid grid-cols-3 gap-3 sm:grid-cols-4 md:grid-cols-5 lg:grid-cols-6"
                                >
                                    <div
                                        v-for="(
                                            preview, index
                                        ) in galleryImagePreviews"
                                        :key="preview"
                                        draggable="true"
                                        class="group relative aspect-square cursor-move overflow-hidden rounded-lg border border-border bg-muted"
                                        @dragstart="
                                            onGalleryDragStart($event, index)
                                        "
                                        @dragover="onGalleryDragOver($event)"
                                        @drop="onGalleryDrop($event, index)"
                                    >
                                        <img
                                            :src="preview"
                                            :alt="`Gallery image preview ${index + 1}`"
                                            class="h-full w-full object-cover transition-opacity"
                                            :class="
                                                draggedGalleryIndex === index
                                                    ? 'opacity-50'
                                                    : 'opacity-100'
                                            "
                                            draggable="false"
                                        />
                                        <button
                                            type="button"
                                            class="absolute top-1.5 right-1.5 flex h-6 w-6 cursor-pointer items-center justify-center rounded-full bg-background/90 text-foreground opacity-0 shadow-sm transition-opacity group-hover:opacity-100 hover:bg-destructive hover:text-destructive-foreground"
                                            @click="removeGalleryImage(index)"
                                        >
                                            <X class="h-3 w-3" />
                                        </button>
                                        <div
                                            class="absolute bottom-1.5 left-1.5 rounded bg-background/80 px-1.5 py-0.5 text-[10px] font-medium text-foreground opacity-0 shadow-sm transition-opacity group-hover:opacity-100"
                                        >
                                            {{ index + 1 }}
                                        </div>
                                    </div>
                                </div>

                                <div
                                    :class="
                                        cn(
                                            'relative flex min-h-32 flex-col items-center justify-center gap-2 rounded-lg border-2 border-dashed border-border bg-muted/40 p-4 text-center transition-colors hover:border-muted-foreground/50',
                                            (form.errors.gallery_images ||
                                                form.errors[
                                                    'gallery_images.0'
                                                ]) &&
                                                'border-destructive',
                                        )
                                    "
                                >
                                    <Input
                                        id="gallery_images"
                                        type="file"
                                        multiple
                                        accept=".png,.jpg,.jpeg,.webp"
                                        class="absolute inset-0 h-full w-full cursor-pointer opacity-0"
                                        @change="handleGalleryImagesChange"
                                    />
                                    <UploadCloudIcon
                                        class="h-8 w-8 text-muted-foreground"
                                    />
                                    <div class="space-y-0.5">
                                        <p class="text-sm font-medium">
                                            Add gallery images
                                        </p>
                                        <p
                                            class="text-xs text-muted-foreground"
                                        >
                                            Click or drag to upload more
                                        </p>
                                    </div>
                                </div>
                                <InputError
                                    :message="form.errors.gallery_images"
                                />
                                <InputError
                                    :message="fieldError('gallery_images.0')"
                                />
                            </div>
                        </CardContent>
                    </Card>

                    <Card>
                        <CardHeader>
                            <div
                                class="flex items-center justify-between gap-3"
                            >
                                <div class="space-y-1">
                                    <CardTitle>Itinerary</CardTitle>
                                    <CardDescription>
                                        Add ordered itinerary steps for the
                                        trip.
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
                                    v-for="(
                                        item, index
                                    ) in form.itinerary_items"
                                    :key="index"
                                    class="rounded-lg border border-border p-4"
                                >
                                    <div
                                        class="mb-4 flex items-center justify-between gap-3"
                                    >
                                        <p class="text-sm font-medium">
                                            Stop {{ index + 1 }}
                                        </p>
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
                                            <Label
                                                :for="`itinerary_title_${index}`"
                                                >Title</Label
                                            >
                                            <Input
                                                :id="`itinerary_title_${index}`"
                                                v-model="item.title"
                                                placeholder="Day 1: Arrival in Arusha"
                                            />
                                            <InputError
                                                :message="
                                                    fieldError(
                                                        `itinerary_items.${index}.title`,
                                                    )
                                                "
                                            />
                                        </div>

                                        <div class="grid gap-2">
                                            <Label
                                                :for="`itinerary_content_${index}`"
                                                >Content</Label
                                            >
                                            <Editor
                                                v-model="item.content"
                                                :error="!!fieldError(`itinerary_items.${index}.content`)"
                                                placeholder="Describe what happens during this itinerary step."
                                            />
                                            <InputError
                                                :message="
                                                    fieldError(
                                                        `itinerary_items.${index}.content`,
                                                    )
                                                "
                                            />
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

                    <Card>
                        <CardHeader>
                            <CardTitle>Inclusions & Exclusions</CardTitle>
                            <CardDescription
                                >What is covered and what travelers need to
                                cover separately.</CardDescription
                            >
                        </CardHeader>
                        <CardContent class="pt-6">
                            <div class="grid gap-6 lg:grid-cols-2">
                                <Card class="border-dashed">
                                    <CardHeader>
                                        <div
                                            class="flex items-center justify-between gap-3"
                                        >
                                            <div class="space-y-1">
                                                <CardTitle class="text-base"
                                                    >Inclusions</CardTitle
                                                >
                                                <CardDescription
                                                    >What is covered by the
                                                    tour.</CardDescription
                                                >
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
                                                v-for="(
                                                    inclusion, index
                                                ) in form.inclusions"
                                                :key="index"
                                                class="rounded-md border border-border p-3"
                                            >
                                                <div
                                                    class="mb-3 flex items-center justify-between gap-3"
                                                >
                                                    <p
                                                        class="text-sm font-medium"
                                                    >
                                                        Inclusion
                                                        {{ index + 1 }}
                                                    </p>
                                                    <Button
                                                        type="button"
                                                        variant="ghost"
                                                        class="text-destructive hover:text-destructive"
                                                        @click="
                                                            removeInclusion(
                                                                index,
                                                            )
                                                        "
                                                    >
                                                        <Trash2
                                                            class="size-4"
                                                        />
                                                        Remove
                                                    </Button>
                                                </div>
                                                <Input
                                                    v-model="inclusion.text"
                                                    placeholder="Airport transfers"
                                                />
                                                <InputError
                                                    :message="
                                                        fieldError(
                                                            `inclusions.${index}.text`,
                                                        )
                                                    "
                                                />
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
                                        <div
                                            class="flex items-center justify-between gap-3"
                                        >
                                            <div class="space-y-1">
                                                <CardTitle class="text-base"
                                                    >Exclusions</CardTitle
                                                >
                                                <CardDescription
                                                    >What travelers need to
                                                    cover
                                                    separately.</CardDescription
                                                >
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
                                                v-for="(
                                                    exclusion, index
                                                ) in form.exclusions"
                                                :key="index"
                                                class="rounded-md border border-border p-3"
                                            >
                                                <div
                                                    class="mb-3 flex items-center justify-between gap-3"
                                                >
                                                    <p
                                                        class="text-sm font-medium"
                                                    >
                                                        Exclusion
                                                        {{ index + 1 }}
                                                    </p>
                                                    <Button
                                                        type="button"
                                                        variant="ghost"
                                                        class="text-destructive hover:text-destructive"
                                                        @click="
                                                            removeExclusion(
                                                                index,
                                                            )
                                                        "
                                                    >
                                                        <Trash2
                                                            class="size-4"
                                                        />
                                                        Remove
                                                    </Button>
                                                </div>
                                                <Input
                                                    v-model="exclusion.text"
                                                    placeholder="International flights"
                                                />
                                                <InputError
                                                    :message="
                                                        fieldError(
                                                            `exclusions.${index}.text`,
                                                        )
                                                    "
                                                />
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
                        </CardContent>
                    </Card>
                </div>

                <div class="space-y-6 lg:sticky lg:top-6 lg:self-start">
                    <Card>
                        <CardHeader>
                            <CardTitle class="text-base">Places</CardTitle>
                            <CardDescription
                                >Attach places in the order travelers should see
                                them.</CardDescription
                            >
                        </CardHeader>
                        <CardContent class="space-y-3">
                            <div v-if="placesByCountry.length" class="space-y-4">
                                <div
                                    v-for="group in placesByCountry"
                                    :key="group.country_id"
                                    class="space-y-2"
                                >
                                    <div class="flex items-center gap-2">
                                        <img
                                            v-if="group.country_code"
                                            :src="`https://flagcdn.com/w40/${group.country_code}.png`"
                                            :alt="`${group.country_name} flag`"
                                            class="h-4 w-6 rounded-[2px] border border-border object-cover"
                                        />
                                        <p
                                            class="text-xs font-semibold tracking-wide text-muted-foreground uppercase"
                                        >
                                            {{ group.country_name }}
                                        </p>
                                    </div>
                                    <label
                                        v-for="place in group.places"
                                        :key="place.id"
                                        class="flex items-start gap-3 rounded-md border border-border p-3"
                                    >
                                        <Checkbox
                                            :model-value="
                                                form.place_ids.includes(place.id)
                                            "
                                            @update:model-value="
                                                toggleSelection(
                                                    'place_ids',
                                                    place.id,
                                                    $event,
                                                )
                                            "
                                        />
                                        <div class="space-y-1">
                                            <p class="text-sm font-medium">
                                                {{ place.name }}
                                            </p>
                                        </div>
                                    </label>
                                </div>
                            </div>
                            <p v-else class="text-sm text-muted-foreground">
                                No places are available yet.
                            </p>
                            <InputError :message="fieldError('place_ids')" />
                            <InputError :message="fieldError('place_ids.0')" />
                        </CardContent>
                    </Card>

                    <Card>
                        <CardHeader>
                            <CardTitle class="text-base">Categories</CardTitle>
                            <CardDescription
                                >Select any matching tour
                                categories.</CardDescription
                            >
                        </CardHeader>
                        <CardContent class="space-y-3">
                            <Input
                                v-if="props.categories.length > 5"
                                v-model="categorySearch"
                                placeholder="Search categories..."
                            />
                            <div
                                v-if="filteredCategories.length"
                                class="space-y-2"
                                :class="{
                                    'max-h-[280px] overflow-y-auto pr-1': props.categories.length > 5,
                                }"
                            >
                                <label
                                    v-for="category in filteredCategories"
                                    :key="category.id"
                                    class="flex items-center gap-3 rounded-md border border-border p-3"
                                >
                                    <Checkbox
                                        :model-value="
                                            form.category_ids.includes(
                                                category.id,
                                            )
                                        "
                                        @update:model-value="
                                            toggleSelection(
                                                'category_ids',
                                                category.id,
                                                $event,
                                            )
                                        "
                                    />
                                    <img
                                        v-if="category.image"
                                        :src="category.image"
                                        :alt="category.name"
                                        class="h-8 w-8 rounded-full object-cover"
                                    />
                                    <div
                                        v-else
                                        class="flex h-8 w-8 items-center justify-center rounded-full bg-muted"
                                    >
                                        <span class="text-xs text-muted-foreground">
                                            {{ category.name.charAt(0).toUpperCase() }}
                                        </span>
                                    </div>
                                    <span class="text-sm font-medium">{{
                                        category.name
                                    }}</span>
                                </label>
                            </div>
                            <p v-else class="text-sm text-muted-foreground">
                                No categories matched your search.
                            </p>
                            <InputError :message="fieldError('category_ids')" />
                            <InputError
                                :message="fieldError('category_ids.0')"
                            />
                        </CardContent>
                    </Card>

                    <Card>
                        <CardHeader>
                            <CardTitle class="text-base">Activities</CardTitle>
                            <CardDescription
                                >Select activities included in the
                                tour.</CardDescription
                            >
                        </CardHeader>
                        <CardContent class="space-y-3">
                            <Input
                                v-if="props.activities.length > 5"
                                v-model="activitySearch"
                                placeholder="Search activities..."
                            />
                            <div
                                v-if="filteredActivities.length"
                                class="space-y-2"
                                :class="{
                                    'max-h-[280px] overflow-y-auto pr-1': props.activities.length > 5,
                                }"
                            >
                                <label
                                    v-for="activity in filteredActivities"
                                    :key="activity.id"
                                    class="flex items-center gap-3 rounded-md border border-border p-3"
                                >
                                    <Checkbox
                                        :model-value="
                                            form.activity_ids.includes(
                                                activity.id,
                                            )
                                        "
                                        @update:model-value="
                                            toggleSelection(
                                                'activity_ids',
                                                activity.id,
                                                $event,
                                            )
                                        "
                                    />
                                    <img
                                        v-if="activity.image"
                                        :src="activity.image"
                                        :alt="activity.name"
                                        class="h-8 w-8 rounded-full object-cover"
                                    />
                                    <div
                                        v-else
                                        class="flex h-8 w-8 items-center justify-center rounded-full bg-muted"
                                    >
                                        <span class="text-xs text-muted-foreground">
                                            {{ activity.name.charAt(0).toUpperCase() }}
                                        </span>
                                    </div>
                                    <span class="text-sm font-medium">{{
                                        activity.name
                                    }}</span>
                                </label>
                            </div>
                            <p v-else class="text-sm text-muted-foreground">
                                No activities matched your search.
                            </p>
                            <InputError :message="fieldError('activity_ids')" />
                            <InputError
                                :message="fieldError('activity_ids.0')"
                            />
                        </CardContent>
                    </Card>

                    <Card>
                        <CardHeader>
                            <CardTitle class="text-base">Featuring</CardTitle>
                            <CardDescription
                                >Control homepage placement and
                                recommendations.</CardDescription
                            >
                        </CardHeader>
                        <CardContent class="space-y-3">
                            <label
                                class="flex items-start gap-3 rounded-lg border border-dashed border-border p-4"
                            >
                                <Checkbox
                                    :model-value="form.is_featured_on_home"
                                    @update:model-value="
                                        setBooleanField(
                                            'is_featured_on_home',
                                            $event,
                                        )
                                    "
                                />
                                <div class="space-y-1">
                                    <p class="font-medium">Featured on home</p>
                                    <p class="text-sm text-muted-foreground">
                                        Make the tour eligible for homepage
                                        highlights.
                                    </p>
                                </div>
                            </label>

                            <label
                                class="flex items-start gap-3 rounded-lg border border-dashed border-border p-4"
                            >
                                <Checkbox
                                    :model-value="
                                        form.is_featured_as_seasonal_recommendation
                                    "
                                    @update:model-value="
                                        setBooleanField(
                                            'is_featured_as_seasonal_recommendation',
                                            $event,
                                        )
                                    "
                                />
                                <div class="space-y-1">
                                    <p class="font-medium">
                                        Seasonal recommendation
                                    </p>
                                    <p class="text-sm text-muted-foreground">
                                        Mark the tour for seasonal
                                        recommendations.
                                    </p>
                                </div>
                            </label>
                        </CardContent>
                    </Card>

                    <Card>
                        <CardHeader>
                            <div
                                class="flex items-center justify-between gap-3"
                            >
                                <div class="space-y-1">
                                    <CardTitle class="text-base"
                                        >External booking</CardTitle
                                    >
                                    <CardDescription
                                        >Optional link to an external booking
                                        page.</CardDescription
                                    >
                                </div>
                                <Switch
                                    :model-value="form.show_external_booking"
                                    @update:model-value="
                                        setBooleanField(
                                            'show_external_booking',
                                            $event,
                                        )
                                    "
                                />
                            </div>
                        </CardHeader>
                        <CardContent class="space-y-3">
                            <div class="grid gap-2">
                                <Label for="external_url"
                                    >External booking URL</Label
                                >
                                <Input
                                    id="external_url"
                                    v-model="form.external_url"
                                    type="url"
                                    placeholder="https://example.com/book"
                                />
                                <InputError
                                    :message="form.errors.external_url"
                                />
                            </div>
                            <div class="grid gap-2">
                                <Label for="external_link_button_text"
                                    >Button text</Label
                                >
                                <Input
                                    id="external_link_button_text"
                                    v-model="form.external_link_button_text"
                                    placeholder="Book now"
                                />
                                <InputError
                                    :message="
                                        form.errors.external_link_button_text
                                    "
                                />
                            </div>
                        </CardContent>
                    </Card>

                    <Card>
                        <CardHeader>
                            <CardTitle class="text-base">Visibility</CardTitle>
                            <CardDescription
                                >Control whether this tour is publicly
                                visible.</CardDescription
                            >
                        </CardHeader>
                        <CardContent>
                            <div
                                class="flex items-center justify-between gap-3 rounded-lg border border-dashed border-border p-4"
                            >
                                <div class="space-y-1">
                                    <p class="font-medium">Hidden</p>
                                    <p class="text-sm text-muted-foreground">
                                        Keep this tour out of public view.
                                    </p>
                                </div>
                                <Switch
                                    :model-value="form.is_hidden"
                                    @update:model-value="
                                        setBooleanField('is_hidden', $event)
                                    "
                                />
                            </div>
                        </CardContent>
                    </Card>
                </div>
            </div>
        </form>
    </div>

    <Dialog v-model:open="pricingDialogOpen">
        <DialogContent class="sm:max-w-4xl" :hide-overlay="true">
            <DialogHeader>
                <DialogTitle>Detailed pricing</DialogTitle>
                <DialogDescription>
                    Break down seasonal pricing, occupancy, or package notes.
                </DialogDescription>
            </DialogHeader>
            <div class="grid gap-4 py-4">
                <Editor
                    v-model="form.pricing_detailed"
                    :error="!!form.errors.pricing_detailed"
                    placeholder="Break down seasonal pricing, occupancy, or package notes."
                />
                <InputError :message="form.errors.pricing_detailed" />
            </div>
        </DialogContent>
    </Dialog>
</template>
