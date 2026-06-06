import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/landing.css',
                'resources/css/landing-fab.css',
                'resources/css/landing-lang-switcher.css',
                'resources/js/landing.js',
                'resources/js/welcome-reveal.js',
                'resources/js/ancient-map-lightbox.js',
                'resources/js/landing-i18n.js',
                'resources/sources/style.scss',
            ],
            refresh: true,
        }),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
