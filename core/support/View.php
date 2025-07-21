<?php

namespace core\support;

class View
{
    /**
     * Render an error view (e.g. 404, 500)
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
     * Render a pulse view file
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
     * Internal renderer with Pulse syntax support
     */
    protected static function renderFile(string $file, array $data = []): void
    {
        // If this isn’t a pulse template, just include it
        if (!str_ends_with($file, '.pulse.php')) {
            extract($data);
            require $file;
            return;
        }

        // Compile & cache
        $hash   = md5($file);
        $cached = base_path("storage/views/{$hash}.php");

        if (!file_exists($cached) || filemtime($cached) < filemtime($file)) {
            $content = file_get_contents($file);

            // Replace {{ ... }} with htmlentities echo
            $content = preg_replace_callback(
                '/{{\s*(.+?)\s*}}/',
                fn($m) => "<?php echo htmlentities({$m[1]}); ?>",
                $content
            );

            // Directives
            $directives = [
                '/@if\s*\((.+?)\)/'      => fn($m) => "<?php if ({$m[1]}): ?>",
                '/@elseif\s*\((.+?)\)/'  => fn($m) => "<?php elseif ({$m[1]}): ?>",
                '/@else/'                => fn()   => "<?php else: ?>",
                '/@endif/'               => fn()   => "<?php endif; ?>",
                '/@foreach\s*\((.+?)\)/' => fn($m) => "<?php foreach ({$m[1]}): ?>",
                '/@endforeach/'          => fn()   => "<?php endforeach; ?>",
                '/@php/'                 => fn()   => "<?php ",
                '/@endphp/'              => fn()   => " ?>",
                '/@vite\s*\((.+?)\)/' => fn($m) => "<?php vite({$m[1]}); ?>",
            ];

            foreach ($directives as $pattern => $callback) {
                $content = preg_replace_callback($pattern, $callback, $content);
            }

            file_put_contents($cached, $content);
        }

        extract($data);
        require $cached;
    }

}
