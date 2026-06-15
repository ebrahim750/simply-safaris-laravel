import PhotoSwipe from 'photoswipe';
import { ref } from 'vue';
import 'photoswipe/style.css';

export type PhotoSwipeItem = {
    src: string;
    width: number;
    height: number;
    alt: string;
};

const FALLBACK_DIMENSIONS = { width: 1920, height: 1080 };

function getImageDimensions(
    src: string,
): Promise<{ width: number; height: number }> {
    return new Promise((resolve) => {
        const image = new Image();

        image.onload = () => {
            resolve({
                width: image.naturalWidth,
                height: image.naturalHeight,
            });
        };

        image.onerror = () => {
            resolve(FALLBACK_DIMENSIONS);
        };

        image.src = src;
    });
}

export function usePhotoSwipe(images: string[], altPrefix = 'Gallery image') {
    const items = ref<PhotoSwipeItem[]>([]);
    const initialized = ref(false);

    async function ensureItems(): Promise<void> {
        if (initialized.value) {
            return;
        }

        const resolved = await Promise.all(
            images.map((src) => getImageDimensions(src)),
        );

        items.value = images.map((src, index) => ({
            src,
            width: resolved[index].width,
            height: resolved[index].height,
            alt: `${altPrefix} gallery image ${index + 1}`,
        }));

        initialized.value = true;
    }

    async function openAt(index: number): Promise<void> {
        await ensureItems();

        const pswp = new PhotoSwipe({
            dataSource: items.value,
            index,
            bgOpacity: 0.85,
        });

        pswp.init();
    }

    return {
        openAt,
    };
}
