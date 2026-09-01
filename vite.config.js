import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { bunny } from 'laravel-vite-plugin/fonts';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css', 
                'resources/js/app.js', 
                'resources/js/admin/sidebar.js',
                'resources/js/admin/materi/index.js',
                'resources/js/admin/materi/content.js',
                'resources/js/admin/utils.js',
                'resources/js/admin/applications/index.js',
                'resources/js/admin/categories/index.js',
                'resources/js/admin/pengguna/index.js',
                'resources/js/public/welcome.js'
            ],
            refresh: true,
            fonts: [
                bunny('Instrument Sans', {
                    weights: [400, 500, 600],
                }),
            ],
        }),
        tailwindcss(),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
