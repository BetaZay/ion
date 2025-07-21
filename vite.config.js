import { defineConfig } from 'vite';
import liveReload from 'vite-plugin-live-reload';
import dotenv from 'dotenv';
import fs from 'fs';
import path from 'path';

export default ({ mode }) => {
    Object.assign(process.env, dotenv.parse(fs.readFileSync(path.resolve('.env'))));

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
            outDir: path.resolve('public/build'),
            emptyOutDir: true,
            rollupOptions: {
                input: {
                    app: path.resolve('resources/js/app.js'),
                    style: path.resolve('resources/css/app.css'),
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
