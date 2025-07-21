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
    $manifestPath = base_path('public/build/manifest.json');

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
            if ($manifest === null && file_exists($manifestPath)) {
                $manifest = json_decode(file_get_contents($manifestPath), true);
            }

            if (isset($manifest[$key])) {
                $entry = $manifest[$key];

                if (!empty($entry['file'])) {
                    echo "<script type=\"module\" src=\"/build/{$entry['file']}\"></script>" . PHP_EOL;
                }

                if (!empty($entry['css'])) {
                    foreach ($entry['css'] as $css) {
                        echo "<link rel=\"stylesheet\" href=\"/build/{$css}\">" . PHP_EOL;
                    }
                }
            }
        }
    }
}
