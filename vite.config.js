// Vite configuration for a Laravel project

// ES Module syntax is preferred for consistency
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue'; // Ensure you have this import if you're using Vue
import { viteStaticCopy } from 'vite-plugin-static-copy';
import { resolve } from 'path'; // Necessary for the 'alias' configuration

export default defineConfig({
  build: {
    manifest: true,
    rtl: true,
    outDir: 'public/build/',
    cssCodeSplit: true,
    rollupOptions: {
      output: {
        assetFileNames: (chunk) => {
          if(chunk.name && chunk.name.split('.').pop() === 'css') {
            return 'css/[name].min.css';
          } else {
            return 'icons/[name]';
          }
        },
        entryFileNames: 'js/[name].js',
      },
    },
  },
  plugins: [
    vue({
      css: true,
      exposeFilename: false,
      runtimeCompiler: true,
      template: {
        compilerOptions: {
          isCustomElement: (tag) => tag.startsWith('icon-'),
        },
      },
    }),
    laravel({
      input: [
        'resources/scss/bootstrap.scss',
        'resources/scss/icons.scss',
        'resources/scss/app.scss',
      ],
      refresh: true,                
    }),
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
  resolve: {
    alias: {
      '@': resolve(__dirname, 'resources/js'), 
    }
  }
});
