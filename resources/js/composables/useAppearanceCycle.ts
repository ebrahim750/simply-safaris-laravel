import { Moon, Sun } from 'lucide-vue-next';
import { computed } from 'vue';
import { useAppearance } from '@/composables/useAppearance';

export function useAppearanceCycle() {
    const { appearance, resolvedAppearance, updateAppearance } = useAppearance();

    function cycle() {
        updateAppearance(
            resolvedAppearance.value === 'dark' ? 'light' : 'dark',
        );
    }

    const label = computed(() =>
        resolvedAppearance.value === 'dark'
            ? 'Switch to light mode'
            : 'Switch to dark mode',
    );

    const actionIcon = computed(() =>
        resolvedAppearance.value === 'dark' ? Sun : Moon,
    );

    return { appearance, resolvedAppearance, cycle, label, actionIcon };
}
