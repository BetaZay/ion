const { defineConfig } = require('vite');
const path = require('path');
const dotenv = require('dotenv');
const fs = require('fs');

module.exports = async () => {
    // Dynamically import ESM plugin
    const liveReload = (await import('vite-plugin-live-reload')).default;

    // Load .env manually
    const envPath = path.resolve(__dirname, '.env');
    if (fs.existsSync(envPath)) {
        Object.assign(process.env, dotenv.parse(fs.readFileSync(envPath)));
    }

    const port = process.env.VITE_PORT || 5173;
    const origin = `${process.env.VITE_ORIGIN || 'http://localhost'}:${port}`;

    return defineConfig({
        root: 'resources',
        base: process.env.NODE_ENV === 'production' ? '/build/' : '/',
        plugins: [
            liveReload([
                '../resources/views/**/*.pulse.php',
            ]),
        ],
        build: {
            manifest: true,
            outDir: path.resolve(__dirname, 'public/build'),
            emptyOutDir: true,
            rollupOptions: {
                input: {
                    app: path.resolve(__dirname, 'resources/js/app.js'),
                    style: path.resolve(__dirname, 'resources/css/app.css'),
                },
                output: {
                    entryFileNames: 'js/[name].js',
                    assetFileNames: 'css/[name].[ext]',
                },
            },
        },
        server: {
            strictPort: true,
            port: Number(port),
            origin,
            hmr: {
                host: 'localhost',
            },
            watch: {
                usePolling: true,
            },
        },
    });
};
