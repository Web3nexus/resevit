import { defineConfig, loadEnv } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig(({ mode }) => {
    const env = loadEnv(mode, process.cwd(), '');
    const host = new URL(env.APP_URL).hostname;

    return {
        plugins: [
            laravel({
                input: ['resources/css/app.css', 'resources/js/app.js', 'resources/css/filament/securegate/theme.css'],
                refresh: true,
            }),
            tailwindcss(),
        ],
        server: {
            host: '127.0.0.1',
            cors: true,
            hmr: {
                host: host,
            },
        },
    };
});
