import { onMounted, onUnmounted } from 'vue';

export function useScrollAnimate() {
    let observer: IntersectionObserver | null = null;

    onMounted(() => {
        observer = new IntersectionObserver(
            (entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('animated');
                        observer?.unobserve(entry.target);
                    }
                });
            },
            { threshold: 0.1, rootMargin: '0px 0px -50px 0px' },
        );

        document.querySelectorAll('.wow').forEach((el) => {
            observer?.observe(el);
        });
    });

    onUnmounted(() => {
        observer?.disconnect();
    });
}

export function animateCountTo(
    el: HTMLElement,
    target: number,
    duration: number = 2000,
    suffix: string = '',
    decimals: number = 0,
) {
    const start = performance.now();

    function tick(now: number) {
        const elapsed = now - start;
        const progress = Math.min(elapsed / duration, 1);
        const eased = 1 - Math.pow(1 - progress, 3);
        const current = target * eased;

        el.textContent = current.toFixed(decimals) + suffix;

        if (progress < 1) {
            requestAnimationFrame(tick);
        } else {
            el.textContent = target.toFixed(decimals) + suffix;
        }
    }

    requestAnimationFrame(tick);
}
