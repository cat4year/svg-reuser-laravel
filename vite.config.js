import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/platform/platform.css', 'resources/js/platform/platform.js'],
            refresh: true,
        }),
    ],
});
