<?php

use core\pulse\View;

if (!function_exists('view')) {
    function view(string $name, array $data = []): void
    {
        View::render($name, $data);
    }
}

if (!function_exists('pulse')) {
    function pulse(string $file, array $data = []): void
    {
        View::renderFile($file, $data);
    }
}