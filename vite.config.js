import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/calculator.js',
                'resources/js/main.js',
                'resources/css/calculator.css',
                'resources/js/jquery-ui.min.js',
                'resources/js/jquery.ui.touch-punch.js'
            ],
            refresh: true,
        }),
    ],
});
