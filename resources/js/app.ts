import { createInertiaApp, router } from '@inertiajs/vue3';
import { createApp, createSSRApp, h } from 'vue';
import {
    initializeTheme,
    syncDocumentTheme,
} from '@/composables/useAppearance';
import AppLayout from '@/layouts/AppLayout.vue';
import AuthLayout from '@/layouts/AuthLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

void createInertiaApp({
    title: (title) => (title ? `${title} - ${appName}` : appName),
    layout: (name) => {
        switch (true) {
            case name === 'Welcome':
                return null;
            case name.startsWith('auth/'):
                return AuthLayout;
            case name.startsWith('settings/'):
                return [AppLayout, SettingsLayout];
            default:
                return AppLayout;
        }
    },
    progress: {
        color: '#4B5563',
    },
    setup({ el, App, props, plugin }) {
        const applyUserChosenTheme =
            props.initialPage.props.applyUserChosenTheme ?? true;

        if (!el) {
            const app = createSSRApp({ render: () => h(App, props) });
            app.use(plugin);

            return app;
        }

        initializeTheme(applyUserChosenTheme);

        router.on('navigate', (event) => {
            syncDocumentTheme(
                event.detail.page.props.applyUserChosenTheme ?? true,
            );
        });

        const app = el.hasAttribute('data-server-rendered')
            ? createSSRApp({ render: () => h(App, props) })
            : createApp({ render: () => h(App, props) });

        app.use(plugin);
        app.mount(el);

        return app;
    },
});
