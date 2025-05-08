import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js','resources/js/product.js','resources/js/productAdd.js','resources/js/user.js','resources/js/customer.js'],
            refresh: true,
        }),
        tailwindcss(),
    ],
    build: {
        manifest: true,
        outDir: 'public/build',
    },
});
