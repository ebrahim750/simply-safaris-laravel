declare module 'lodash-es' {
    export function debounce<T extends (...args: never[]) => unknown>(
        func: T,
        wait?: number,
    ): T & { cancel(): void; flush(): ReturnType<T> };
}
