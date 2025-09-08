import '../css/app.css';

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import type { DefineComponent } from 'vue';
import { createApp, h } from 'vue';
import { initializeTheme } from './composables/useAppearance';
import { createI18n } from 'vue-i18n';

import en from '../../resources/lang/en.json';
import pt from '../../resources/lang/pt.json';

// --- Echo / Pusher ---
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

declare global {
    interface Window {
        Pusher: typeof Pusher;
        Echo: Echo;
        Laravel: { userId: number | null };
    }
}

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: "pusher",
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    forceTLS: true,
});

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => (title ? `${title} - ${appName}` : appName),
    resolve: (name) =>
        resolvePageComponent(
            `./pages/${name}.vue`,
            import.meta.glob<DefineComponent>('./pages/**/*.vue')
        ),
    setup({ el, App, props, plugin }) {
        const i18n = createI18n({
            locale: 'pt', // idioma inicial
            fallbackLocale: 'en',
            messages: { en, pt },
        });

        const vueApp = createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(i18n);

        vueApp.mount(el);

        // 🔔 Exemplo: escutar eventos de tarefa atribuída
        if (window.Laravel?.userId) {
            window.Echo.private(`usuario.${window.Laravel.userId}`)
                .listen('.TarefaAtribuida', (e: any) => {
                    console.log('Nova tarefa atribuída:', e.tarefa.titulo);
                    alert('Nova tarefa atribuída: ' + e.tarefa.titulo);
                });
        }
    },
    progress: {
        color: '#4B5563',
    },
});

// Define tema (dark/light) no carregamento
initializeTheme();
