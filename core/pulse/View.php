<?php

namespace core\pulse;

class View
{
    /**
     * Render a view file (app or core)
     */
    public static function render(string $view, array $data = []): void
    {
        if (str_starts_with($view, 'core::')) {
            $relativePath = str_replace('.', '/', substr($view, 6));
            $path = base_path("core/views/{$relativePath}.pulse.php");
        } else {
            $relativePath = str_replace('.', '/', $view);
            $path = base_path("resources/views/{$relativePath}.pulse.php");
        }

        if (!file_exists($path)) {
            echo "View not found: {$view}";
            return;
        }

        self::renderFile($path, $data);
    }

    /**
     * Render an error fallback
     */
    public static function error(string $code): void
    {
        $override = base_path("resources/views/Errors/{$code}.pulse.php");
        $default  = base_path("core/views/Errors/{$code}.pulse.php");

        $file = file_exists($override) ? $override : (file_exists($default) ? $default : null);

        if ($file) {
            self::renderFile($file);
        } else {
            http_response_code((int) $code);
            echo "{$code} Error";
        }
    }

    /**
     * Load and execute a compiled Pulse view
     */
    public static function renderFile(string $file, array $data = []): void
    {
        $hash = sha1(realpath($file) . ':' . filemtime($file));
        $cached = (getenv('ION_CACHE_PATH') ?: base_path('storage/views')) . "/{$hash}.php";

        if (!file_exists($cached) || filemtime($cached) < filemtime($file)) {
            $compiled = Compiler::compile($file);
            file_put_contents($cached, $compiled);
        }

        extract($data);
        require $cached;
    }

    // Component passthroughs
    public static function component(string $name, array $data, callable $body): void
    {
        Component::render($name, $data, $body);
    }

    public static function slot(string $name, callable $body): void
    {
        Component::slot($name, $body);
    }
}
