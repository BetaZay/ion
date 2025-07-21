<?php

namespace core\support;

class Env
{
    public static function load(string $path): void
    {
        if (!file_exists($path)) return;

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '' || str_starts_with($line, '#')) continue;

            [$name, $value] = explode('=', $line, 2);
            $name = trim($name);
            $value = trim($value);

            // Remove surrounding quotes
            $value = trim($value, "\"'");

            // Set into both $_ENV and $_SERVER
            $_ENV[$name] = $_SERVER[$name] = $value;
        }
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        return $_ENV[$key] ?? $default;
    }
}
