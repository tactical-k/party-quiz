import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

const appUrl = process.env.VITE_APP_URL || 'http://localhost';

export default defineConfig({
    plugins: [
        laravel({
            input: 'resources/js/app.js',
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
    server: {
        hmr: {
            host: appUrl.replace('https://', '').replace('http://', ''),
            protocol: appUrl.startsWith('https') ? 'wss' : 'ws',
        },
    },
});
