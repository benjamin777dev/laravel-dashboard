import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/sass/app.scss',
                'resources/js/app.js',
                'resources/css/custom.css',
                'resources/js/custom.js',
                'resources/js/dashboard.js',
                'resources/css/dashboard.css',
                'resources/js/pipeline.js',
                'resources/css/pipeline.css',
                'resources/js/contacts.js',
                'resources/css/contacts.css',
                'resources/css/app.css'


            ],
            refresh: true,
        }),
    ],
});
