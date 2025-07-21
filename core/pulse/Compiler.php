<?php

namespace core\pulse;

use core\logging\Logger;

class Compiler
{
    public static function compile(string $file): string
    {
        $content = file_get_contents($file);
        file_put_contents('/tmp/compiler_raw.txt', $content);


        $isTemplate = str_contains($content, '{{') || str_contains($content, '@') || str_contains($content, '<ion-');

        if (!$isTemplate) {
            return "<?php echo " . var_export($content, true) . "; ?>";
        }

        // Directives
        $content = preg_replace_callback(
            '/{{\s*(.+?)\s*}}/',
            fn($m) => "<?php echo htmlentities({$m[1]}); ?>",
            $content
        );

        $directives = DirectiveRegistry::getDirectives();
        foreach ($directives as $pattern => $callback) {
            $content = preg_replace_callback($pattern, $callback, $content);
        }

// Slots first
        $content = preg_replace_callback(
            '/<ion-slot name="([^"]+)">(.*?)<\/ion-slot>/s',
            fn($m) => "<?php \\core\\pulse\\View::slot('{$m[1]}', function() { ?>{$m[2]}<?php }); ?>",
            $content
        );

// Then components (after inner slot content has been replaced)
        $content = preg_replace_callback(
            '/<ion-([a-zA-Z0-9\-_]+)(\s+[^>]*)?>(.*?)<\/ion-\1>/s',
            fn($m) => "<?php \\core\\pulse\\View::component('{$m[1]}', " . Compiler::parseAttributes($m[2] ?? '') . ", function() { ?>{$m[3]}<?php }); ?>",
            $content
        );

        file_put_contents('/tmp/compiler_compiled.txt', $content);

        return $content;
    }

    public static function parseAttributes(string $input): string
    {
        $attributes = [];
        preg_match_all('/([a-zA-Z_][a-zA-Z0-9_-]*)\s*=\s*"([^"]*)"/', $input, $matches, PREG_SET_ORDER);

        foreach ($matches as $match) {
            $key = $match[1];
            $val = addslashes($match[2]);
            $attributes[] = "'$key' => \"$val\"";
        }

        return '[' . implode(', ', $attributes) . ']';
    }
}
