<?php

namespace core\pulse;

use core\support\Env;
use core\logging\Logger;

class Vite
{
    public static function render(array|string $assets): string
    {
        $isDev = Env::get('APP_ENV') === 'local';
        $viteUrl = 'http://localhost:5173';
        $manifestPath = base_path('public/build/.vite/manifest.json');
        $assets = (array) $assets;

        $tags = [];

        if ($isDev) {
            // Inject the Vite HMR client
            $tags[] = "<script type=\"module\" src=\"{$viteUrl}/@vite/client\"></script>";
        }

        static $manifest = null;

        foreach ($assets as $asset) {
            $key = str_replace('resources/', '', $asset);

            if ($isDev) {
                $url = "{$viteUrl}/{$key}";

                if (str_ends_with($key, '.css')) {
                    $tags[] = "<link rel=\"stylesheet\" href=\"{$url}\">";
                } elseif (str_ends_with($key, '.js')) {
                    $tags[] = "<script type=\"module\" src=\"{$url}\"></script>";
                }

            } else {
                // Load manifest once
                if ($manifest === null) {
                    if (file_exists($manifestPath)) {
                        $manifest = json_decode(file_get_contents($manifestPath), true);
                    } else {
                        $manifest = [];
                        Logger::warning("Vite manifest not found: {$manifestPath}");
                    }
                }

                $resolved = $manifest[$key] ?? null;

                if ($resolved) {
                    if (!empty($resolved['file'])) {
                        if (str_ends_with($resolved['file'], '.js')) {
                            $tags[] = "<script type=\"module\" src=\"/build/{$resolved['file']}\"></script>";
                        } elseif (str_ends_with($resolved['file'], '.css')) {
                            $tags[] = "<link rel=\"stylesheet\" href=\"/build/{$resolved['file']}\">";
                        }
                    }

                    if (!empty($resolved['css'])) {
                        foreach ($resolved['css'] as $css) {
                            $tags[] = "<link rel=\"stylesheet\" href=\"/build/{$css}\">";
                        }
                    }

                    $tags[] = "<!-- Injected by Vite: {$resolved['file']} -->";
                } else {
                    Logger::warning("Vite asset key not found in manifest: '{$key}'");
                    Logger::debug("Available keys: " . implode(', ', array_keys($manifest)));
                }
            }
        }

        return implode(PHP_EOL, $tags);
    }
}
