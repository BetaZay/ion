<?php

if (!function_exists('base_path')) {
    function base_path(string $path = ''): string
    {
        return dirname(__DIR__, 2) . '/' . ltrim($path, '/');
    }
}

if (!function_exists('class_basename')) {
    function class_basename(string|object $class): string
    {
        $class = is_object($class) ? get_class($class) : $class;
        return basename(str_replace('\\', '/', $class));
    }
}

if (!is_dir(base_path('storage/views'))) {
    mkdir(base_path('storage/views'), 0777, true);
}

function vite(array $assets): void
{
    $isDev = \core\support\Env::get('APP_ENV') === 'local';
    $viteUrl = 'http://localhost:5173';
    $manifestPath = base_path('public/build/.vite/manifest.json');

    if ($isDev) {
        // Inject the Vite client for HMR
        echo "<script type=\"module\" src=\"{$viteUrl}/@vite/client\"></script>" . PHP_EOL;
    }

    foreach ($assets as $asset) {
        $key = str_replace('resources/', '', $asset);

        if ($isDev) {
            $url = "{$viteUrl}/{$key}";

            if (str_ends_with($key, '.css')) {
                echo "<link rel=\"stylesheet\" href=\"$url\">" . PHP_EOL;
            } elseif (str_ends_with($key, '.js')) {
                echo "<script type=\"module\" src=\"$url\"></script>" . PHP_EOL;
            }
        } else {
            static $manifest = null;

            if ($manifest === null) {
                if (file_exists($manifestPath)) {
                    $manifest = json_decode(file_get_contents($manifestPath), true);
                } else {
                    $manifest = [];
                }
            }

            $resolved = $manifest[$key] ?? null;

            if ($resolved) {

                if (!empty($resolved['file'])) {
                    echo "<script type=\"module\" src=\"/build/{$resolved['file']}\"></script>" . PHP_EOL;
                }

                if (!empty($resolved['css'])) {
                    foreach ($resolved['css'] as $css) {
                        echo "<link rel=\"stylesheet\" href=\"/build/{$css}\">" . PHP_EOL;
                    }
                }
            } else {
                \core\logging\Logger::warning("Vite asset key not found in manifest: '{$key}'");
                \core\logging\Logger::debug("Available keys: " . implode(', ', array_keys($manifest ?? [])));
            }
        }
    }
}
