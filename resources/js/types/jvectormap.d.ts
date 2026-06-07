declare module 'jsvectormap' {
    interface JsVectorMapOptions {
        selector: string | HTMLElement;
        map?: string;
        backgroundColor?: string;
        draggable?: boolean;
        zoomButtons?: boolean;
        zoomOnScroll?: boolean;
        showTooltip?: boolean;
        regionStyle?: Record<string, Record<string, unknown>>;
        selectedRegions?: string[];
        focusOn?: { region?: string; regions?: string[]; animate?: boolean };
        onRegionClick?: (event: PointerEvent, code: string) => void;
        [key: string]: unknown;
    }

    class JsVectorMap {
        constructor(options: JsVectorMapOptions);
        static addMap(name: string, map: unknown): void;
        destroy(): void;
        updateSize(): void;
        reset(): void;
    }

    export default JsVectorMap;
}

declare module 'jsvectormap/dist/maps/world.js' {
    const world: unknown;
    export default world;
}

declare module 'jsvectormap/dist/jsvectormap.css' {
    const css: string;
    export default css;
}
