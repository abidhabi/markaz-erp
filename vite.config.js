import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/assets/css/bootstrap.min.css',
                'resources/assets/css/style.css',
                'resources/assets/js/bootstrap.bundle.min.js',
            ],
            refresh: true,
        }),
    ],
});