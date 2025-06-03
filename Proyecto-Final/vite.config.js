import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/js/app.js',
                'resources/js/editor.js',
                'resources/js/alpine.js',
                'resources/css/user_styles/user-index_styles.css',
                'resources/css/partials_styles/header_styles.css',
                'resources/css/partials_styles/footer_styles.css',
            ],
            refresh: true,
        }),
    ],
});