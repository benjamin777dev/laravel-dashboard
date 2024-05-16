import { defineConfig } from 'vite';
import {laravel} from 'laravel-vite-plugin';
import { viteStaticCopy } from 'vite-plugin-static-copy';

export default defineConfig({
    build: {
        manifest: true,
        outDir: 'public/build/',
        cssCodeSplit: true,
        rollupOptions: {
            output: {
                assetFileNames: (assetInfo) => {
                    const ext = assetInfo.name.split('.').pop();
                    if (ext === 'css') {
                        return 'css/[name].min.css';
                    } else {
                        return 'icons/' + assetInfo.name;
                    }
                },
                entryFileNames: 'js/[name].js',
            },
        },
    },
    plugins: [
        laravel({
            input: [
                'resources/scss/bootstrap.scss',
                'resources/scss/icons.scss',
                'resources/scss/app.scss',
                'resources/sass/app.scss',
                'resources/js/app.js',
                'resources/js/custom.js',
                'resources/js/dashboard.js',
                'resources/css/dashboard.css',
                'resources/js/pipeline.js',
                'resources/css/pipeline.css',
                'resources/js/contacts.js',
                'resources/css/contacts.css',
                'resources/css/custom.css',
                'resources/css/app.css'
            ],
            refresh: true,
        }),
        viteStaticCopy({
            targets: [
                { src: 'resources/fonts', dest: '' },
                { src: 'resources/images', dest: '' },
                { src: 'resources/customImages', dest: '' },
                { src: 'resources/js', dest: '' },
                { src: 'resources/json', dest: '' },
                { src: 'resources/libs', dest: '' },
            ],
        }),
    ],
});
