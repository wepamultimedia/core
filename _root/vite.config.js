import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import vue from "@vitejs/plugin-vue";
import * as path from "path";

export default defineConfig({
    resolve: {
        alias: {
            "@/": path.resolve(__dirname, "./resources/js"),
            "@pages": path.resolve(__dirname, "./resources/js/Pages"),
            "@core": path.resolve(__dirname, "./resources/js/Vendor/Core")
        }
    },
    plugins: [
        laravel({
            input: ["resources/js/app.js", "resources/scss/app.scss"],
            ssr: "resources/js/ssr.js",
            refresh: true
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false
                },
                compilerOptions: {
                    isCustomElement: (tag) => ['oembed'].includes(tag)
                }
            }
        })
    ]
});
