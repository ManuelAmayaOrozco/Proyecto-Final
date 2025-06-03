import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/js/app.js',
                'resources/css/user_styles/user-index_styles.css',
                'resources/js/editor.js',
                'resources/js/alpine.js'
            ],
            refresh: true,
        }),
    ],
});