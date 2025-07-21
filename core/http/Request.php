<?php

// core/http/Request.php
namespace core\http;

class Request
{
    public function method(): string
    {
        return $_SERVER['REQUEST_METHOD'] ?? 'GET';
    }

    public function uri(): string
    {
        return parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/';
    }

    public function input(string $key, $default = null)
    {
        return $_REQUEST[$key] ?? $default;
    }

    public function all(): array
    {
        return $_REQUEST;
    }
}
