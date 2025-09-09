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

// Configuração Echo/WebSockets
window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    forceTLS: true,
    wsHost: 'ws-mt1.pusher.com',
    wsPort: 443,
    wssPort: 443,
    disableStats: true,
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
            locale: 'pt', 
            fallbackLocale: 'en',
            messages: { en, pt },
        });

        const vueApp = createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(i18n);

        vueApp.mount(el);

        // 🔔 Notificações WebSocket
        if (window.Laravel?.userId) {
            // Solicita permissão do navegador
            if (Notification.permission !== 'granted') {
                Notification.requestPermission();
            }

            // Escuta canal privado do usuário
            window.Echo.private(`usuario.${window.Laravel.userId}`)
                .listen('.TarefaAtribuida', (e: any) => {
                    console.log('Nova tarefa atribuída:', e.tarefa.titulo);

                    // Notificação real do navegador
                    if (Notification.permission === 'granted') {
                        new Notification('Nova Tarefa', {
                            body: e.tarefa.titulo,
                            icon: '/favicon.ico', // opcional
                        });
                    } else {
                        alert('Nova tarefa atribuída: ' + e.tarefa.titulo);
                    }
                });
        }
    },
    progress: {
        color: '#4B5563',
    },
});

// Define tema (dark/light)
initializeTheme();