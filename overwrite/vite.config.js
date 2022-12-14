import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import * as path from "path";

export default defineConfig({
    resolve:{
        alias:{
            '@pages' : path.resolve(__dirname, './resources/js/Pages'),
            '@core' : path.resolve(__dirname, './resources/js/Core'),
            '@core/store' : path.resolve(__dirname, './resources/js/Core/Store'),
        },
    },
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
});
