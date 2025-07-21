<?php

namespace core\http;

use core\support\View;

class Router
{
    protected array $routes = [];

    public function get(string $path, callable $action): void
    {
        $this->addRoute('GET', $path, $action);
    }

    public function post(string $path, callable $action): void
    {
        $this->addRoute('POST', $path, $action);
    }

    public function addRoute(string $method, string $path, callable $action): void
    {
        $this->routes[$method][$path] = $action;
    }


    public function dispatch(): void
    {
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/';

        if ($uri !== '/' && str_ends_with($uri, '/')) {
            $uri = rtrim($uri, '/');
        }

        $action = $this->routes[$method][$uri] ?? null;

        if (is_callable($action)) {
            try {
                $action();
            } catch (\Throwable $e) {
                http_response_code(500);
                \core\Support\ErrorHandler::handleException($e);
            }
        } else {
            http_response_code(404);
            View::error('404');
        }
    }

    public function loadRoutes(): void
    {
        $path = base_path('resources/routes/app.php');
        if (!file_exists($path)) {
            echo "[!] Route file not found: $path\n";
            return;
        }

        $router = $this;
        require $path;
    }
}
