<script setup lang="ts">
import jsVectorMap from 'jsvectormap';
import 'jsvectormap/dist/maps/world.js';
import 'jsvectormap/dist/jsvectormap.css';
import { nextTick, onMounted, onUnmounted, shallowRef, useId } from 'vue';

const props = defineProps<{
    countryCode: string;
    countrySlug: string;
}>();

const containerId = `country-map-${useId()}`;
const mapInstance = shallowRef<InstanceType<typeof jsVectorMap> | null>(null);
let resizeHandler: (() => void) | null = null;
let resizeObserver: ResizeObserver | null = null;

// Country to continent mapping (ISO 3166-1 alpha-2)
const continentMap: Record<string, string> = {
    // Africa
    DZ: 'africa', AO: 'africa', BJ: 'africa', BW: 'africa', BF: 'africa',
    BI: 'africa', CM: 'africa', CV: 'africa', CF: 'africa', TD: 'africa',
    KM: 'africa', CG: 'africa', CD: 'africa', CI: 'africa', DJ: 'africa',
    EG: 'africa', GQ: 'africa', ER: 'africa', ET: 'africa', GA: 'africa',
    GM: 'africa', GH: 'africa', GN: 'africa', GW: 'africa', KE: 'africa',
    LS: 'africa', LR: 'africa', LY: 'africa', MG: 'africa', MW: 'africa',
    ML: 'africa', MR: 'africa', MU: 'africa', MA: 'africa', MZ: 'africa',
    NA: 'africa', NE: 'africa', NG: 'africa', RW: 'africa', ST: 'africa',
    SN: 'africa', SC: 'africa', SL: 'africa', SO: 'africa', ZA: 'africa',
    SS: 'africa', SD: 'africa', SZ: 'africa', TZ: 'africa', TG: 'africa',
    TN: 'africa', UG: 'africa', ZM: 'africa', ZW: 'africa',
    // Asia
    AF: 'asia', AM: 'asia', AZ: 'asia', BH: 'asia', BD: 'asia',
    BT: 'asia', BN: 'asia', KH: 'asia', CN: 'asia', CY: 'asia',
    GE: 'asia', IN: 'asia', ID: 'asia', IR: 'asia', IQ: 'asia',
    IL: 'asia', JP: 'asia', JO: 'asia', KZ: 'asia', KW: 'asia',
    KG: 'asia', LA: 'asia', LB: 'asia', MY: 'asia', MV: 'asia',
    MN: 'asia', MM: 'asia', NP: 'asia', KP: 'asia', OM: 'asia',
    PK: 'asia', PS: 'asia', PH: 'asia', QA: 'asia', RU: 'asia',
    SA: 'asia', SG: 'asia', KR: 'asia', LK: 'asia', SY: 'asia',
    TW: 'asia', TJ: 'asia', TH: 'asia', TL: 'asia', TR: 'asia',
    TM: 'asia', AE: 'asia', UZ: 'asia', VN: 'asia', YE: 'asia',
    // Europe
    AL: 'europe', AD: 'europe', AT: 'europe', BY: 'europe', BE: 'europe',
    BA: 'europe', BG: 'europe', HR: 'europe', CZ: 'europe', DK: 'europe',
    EE: 'europe', FI: 'europe', FR: 'europe', DE: 'europe', GR: 'europe',
    HU: 'europe', IS: 'europe', IE: 'europe', IT: 'europe', LV: 'europe',
    LI: 'europe', LT: 'europe', LU: 'europe', MT: 'europe', MD: 'europe',
    MC: 'europe', ME: 'europe', NL: 'europe', MK: 'europe', NO: 'europe',
    PL: 'europe', PT: 'europe', RO: 'europe', SM: 'europe', RS: 'europe',
    SK: 'europe', SI: 'europe', ES: 'europe', SE: 'europe', CH: 'europe',
    UA: 'europe', GB: 'europe', VA: 'europe',
    // North America
    AG: 'north-america', BS: 'north-america', BB: 'north-america', BZ: 'north-america',
    CA: 'north-america', CR: 'north-america', CU: 'north-america', DM: 'north-america',
    DO: 'north-america', SV: 'north-america', GD: 'north-america', GT: 'north-america',
    HT: 'north-america', HN: 'north-america', JM: 'north-america', MX: 'north-america',
    NI: 'north-america', PA: 'north-america', KN: 'north-america', LC: 'north-america',
    VC: 'north-america', TT: 'north-america', US: 'north-america',
    // South America
    AR: 'south-america', BO: 'south-america', BR: 'south-america', CL: 'south-america',
    CO: 'south-america', EC: 'south-america', GY: 'south-america', PY: 'south-america',
    PE: 'south-america', SR: 'south-america', UY: 'south-america', VE: 'south-america',
    // Oceania
    AU: 'oceania', FJ: 'oceania', KI: 'oceania', MH: 'oceania', FM: 'oceania',
    NR: 'oceania', NZ: 'oceania', PW: 'oceania', PG: 'oceania', WS: 'oceania',
    SB: 'oceania', TO: 'oceania', TV: 'oceania', VU: 'oceania',
};

const continentFocus: Record<string, { lat: number; lng: number; scale: number }> = {
    africa: { lat: 5, lng: 20, scale: 2.0 },
    asia: { lat: 35, lng: 100, scale: 1.9 },
    europe: { lat: 50, lng: 20, scale: 3.0 },
    'north-america': { lat: 45, lng: -100, scale: 2.0 },
    'south-america': { lat: -15, lng: -60, scale: 2.2 },
    oceania: { lat: -25, lng: 135, scale: 2.3 },
};

function getFocusConfig(countryCode: string): { coords: [number, number]; scale: number; animate: true } | undefined {
    const continent = continentMap[countryCode.toUpperCase()];

    if (!continent) {
        return undefined;
    }

    const focus = continentFocus[continent];

    if (!focus) {
        return undefined;
    }

    return { coords: [focus.lat, focus.lng], scale: focus.scale, animate: true };
}

function stretchSvgToFill(): void {
    const container = document.querySelector(`#${containerId}`);
    const svg = container?.querySelector('svg');
    if (svg) {
        svg.setAttribute('preserveAspectRatio', 'xMidYMid slice');
    }
}

onMounted(async () => {
    await nextTick();

    const code = props.countryCode.trim().toUpperCase();

    mapInstance.value = new jsVectorMap({
        selector: `#${containerId}`,
        map: 'world',
        backgroundColor: 'transparent',
        draggable: false,
        zoomButtons: false,
        zoomOnScroll: false,
        showTooltip: true,
        regionStyle: {
            initial: {
                fill: '#e0e0e0',
                fillOpacity: 1,
                stroke: '#ffffff',
                strokeWidth: 0.1,
                strokeOpacity: 1,
            },
            hover: {
                fill: '#4da528',
                fillOpacity: 0.8,
                cursor: 'pointer',
            },
            selected: {
                fill: '#4da528',
            },
            selectedHover: {},
        },
        selectedRegions: code ? [code] : [],
        focusOn: code ? getFocusConfig(code) : undefined,
        onRegionClick(_event: PointerEvent, regionCode: string) {
            if (regionCode.toLowerCase() !== props.countrySlug) {
                // No-op: navigation would require a code-to-slug mapping.
            }
        },
    });

    stretchSvgToFill();

    // Observe container resizes (handles layout settling on full page reloads)
    const container = document.querySelector(`#${containerId}`);
    if (container) {
        resizeObserver = new ResizeObserver(() => {
            mapInstance.value?.updateSize();
            stretchSvgToFill();
        });
        resizeObserver.observe(container);
    }

    // Ensure correct size after layout fully settles
    requestAnimationFrame(() => {
        mapInstance.value?.updateSize();
        stretchSvgToFill();
    });

    resizeHandler = () => {
        mapInstance.value?.updateSize();
        stretchSvgToFill();
    };
    window.addEventListener('resize', resizeHandler);
});

onUnmounted(() => {
    if (resizeHandler) {
        window.removeEventListener('resize', resizeHandler);
        resizeHandler = null;
    }

    if (resizeObserver) {
        resizeObserver.disconnect();
        resizeObserver = null;
    }

    mapInstance.value?.destroy();
    mapInstance.value = null;
});
</script>

<template>
    <div :id="containerId" class="country-map" />
</template>

<style scoped>
.country-map {
    width: 100%;
    aspect-ratio: 3 / 2;
    height: auto;
    background: #f8f9fa;
    border-radius: 12px;
}

:deep(.jvm-container) {
    width: 100%;
    height: 100%;
    overflow: visible !important;
}

:deep(.jvm-element) {
    cursor: pointer;
}

:deep(.jvm-zoom-btn) {
    display: none;
}
</style>
