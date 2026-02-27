import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import path from 'path';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js'
            ],
            refresh: true, // hot module reload for Blade
        }),
    ],
    resolve: {
        alias: {
            '@': path.resolve(__dirname, 'resources/js'), // shorthand for JS imports
        },
    },
    build: {
        outDir: 'public/build', // compiled assets location
        rollupOptions: {
            output: {
                entryFileNames: '[name].js',
                chunkFileNames: '[name].js',
                assetFileNames: '[name].[ext]',
            },
        },
    },
});