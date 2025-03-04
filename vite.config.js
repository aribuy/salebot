import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from "@vitejs/plugin-vue";
import svgLoader from "vite-svg-loader";

export default defineConfig({
    plugins: [
        vue(),svgLoader(),
        laravel({
            input: ['resources/js/app.js'],
            buildDirectory: 'client/js/build',
            // refresh: true,
        }),
    ],
});
