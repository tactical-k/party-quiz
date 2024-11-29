import { defineConfig } from 'vite';
import vue from '@vitejs/plugin-vue';
import laravel from 'laravel-vite-plugin';

const appUrl = process.env.VITE_APP_URL || 'http://localhost';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/js/app.js', 'resources/css/app.css'],
            refresh: true,
        }),
        vue(),
    ],
    server: {
        hmr: {
            host: appUrl.replace('https://', '').replace('http://', ''),
            protocol: appUrl.startsWith('https') ? 'wss' : 'ws',
        },
    },
});
