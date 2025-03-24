import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: ['resources/views/**/*.blade.php'],
        }),
    ],
    server: {
        cors: true, // Enable cross-origin requests
        strictPort: true, // Prevents auto-changing ports
        hmr: {
            host: 'localhost', // Ensures hot module reloading works correctly
        },
    },
    build: {
        outDir: 'public/build', // Ensure correct output directory
        emptyOutDir: true, // Clean build directory before new build
    },
});
