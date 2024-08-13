import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { viteStaticCopy } from 'vite-plugin-static-copy';
import removeConsole from 'vite-plugin-remove-console';

export default defineConfig(({ mode }) => {
    return {
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
                    'resources/css/custom.css',
                    'resources/js/custom.js',
                    'resources/js/helper.js',
                    'resources/js/dashboard.js',
                    'resources/css/dashboard.css',
                    'resources/js/pipeline.js',
                    'resources/css/pipeline.css',
                    'resources/js/contacts.js',
                    'resources/css/contacts.css',
                    'resources/css/app.css',
                    'resources/js/toast.js',
                    'resources/js/dropdown.js',
                    'resources/js/datatable.js',
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
            // Conditionally include removeConsole plugin only in production
            mode === 'production' && removeConsole(),
        ].filter(Boolean), // This filters out falsey values like "false" from the plugins array
    };
});