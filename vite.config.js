import { defineConfig, loadEnv } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig(({ mode }) => {
    const env = loadEnv(mode, process.cwd(), '');
    const devHost = env.VITE_DEV_HOST || '127.0.0.1';
    const devPort = Number(env.VITE_DEV_PORT || 5173);

    return {
        server: {
            host: devHost,
            port: devPort,
            strictPort: false,
            hmr: {
                host: devHost,
                port: devPort,
            },
        },
        plugins: [
            laravel({
                input: ['resources/css/app.css', 'resources/js/app.js'],
                refresh: true,
            }),
        ],
    };
});
