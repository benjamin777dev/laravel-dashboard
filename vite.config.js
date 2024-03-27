
import { defineConfig } from 'vite';
// import laravel from 'laravel-vite-plugin';
import { viteStaticCopy } from 'vite-plugin-static-copy'

export default defineConfig({
    build: {
        manifest: true,
        rtl: true,
        outDir: 'public/build/',
        cssCodeSplit: true,
        rollupOptions: {
            output: {
              assetFileNames: (css) => {
                if(css.name.split('.').pop() == 'css') {
                    return 'css/' + `[name]` + '.min.' + 'css';
                } else {
                    return 'icons/' + css.name;
                }
            },
                entryFileNames: 'js/' + `[name]` + `.js`,
            },
        },
      },
    plugins: [
       import("laravel-vite-plugin").then(({default:laravel})=> laravel(
            {
                input: [
                    'resources/scss/bootstrap.scss',
                    'resources/scss/icons.scss',
                    'resources/scss/app.scss',
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
            })
        ),
         viteStaticCopy({
            targets: [
                {
                    src: 'resources/fonts',
                    dest: ''
                },
                {
                    src: 'resources/images',
                    dest: ''
                },
                {
                    src: 'resources/customImages',
                    dest: ''
                },
                {
                    src: 'resources/js',
                    dest: ''
                },
                {
                    src: 'resources/json',
                    dest: ''
                },
                {
                    src: 'resources/libs',
                    dest: ''
                },
            ]
         }),
    ],
});