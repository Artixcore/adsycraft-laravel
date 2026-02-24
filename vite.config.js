import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js', 'resources/js/dashboard.js', 'resources/js/connectors.js', 'resources/js/onboarding.js', 'resources/js/ad-library.js', 'resources/js/ads.js', 'resources/js/calendar.js', 'resources/js/growth-blueprint.js', 'resources/js/settings.js'],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
