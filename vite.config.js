import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],

    build: {
        // Target modern browsers — smaller output, no unnecessary polyfills
        target: ['es2020', 'chrome80', 'firefox78', 'safari14'],

        // Remove source maps in production to reduce payload
        sourcemap: false,

        // Minification (esbuild is the default and fastest)
        minify: 'esbuild',

        // CSS code splitting is already on by default in Vite
        cssCodeSplit: true,

        // Raise the warning limit slightly (our single chunk is intentionally small)
        chunkSizeWarningLimit: 300,

        rollupOptions: {
            output: {
                // Manual chunk splitting: separate vendor libs from app code
                manualChunks(id) {
                    if (id.includes('node_modules/alpinejs')) {
                        return 'alpine';
                    }
                    if (id.includes('node_modules/axios')) {
                        return 'axios';
                    }
                },

                // Hashed filenames for long-lived browser caching
                entryFileNames:   'assets/[name]-[hash].js',
                chunkFileNames:   'assets/[name]-[hash].js',
                assetFileNames:   'assets/[name]-[hash][extname]',
            },
        },
    },

    // Ensure CSS is processed through PostCSS (Tailwind purge runs here)
    css: {
        devSourcemap: false,
    },
});
