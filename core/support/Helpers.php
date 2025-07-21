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
