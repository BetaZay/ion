<?php

// core/http/Router.php
namespace core\http;

use core\pulse\View;

class Router
{
    protected static array $routes = [];

    public static function get(string $uri, callable|array $action): void
    {
        self::addRoute('GET', $uri, $action);
    }

    public static function post(string $uri, callable|array $action): void
    {
        self::addRoute('POST', $uri, $action);
    }

    protected static function addRoute(string $method, string $uri, callable|array $action): void
    {
        self::$routes[$method][$uri] = $action;
    }

    public function dispatch(): void
    {
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/';
        if ($uri !== '/' && str_ends_with($uri, '/')) {
            $uri = rtrim($uri, '/');
        }

        $action = self::$routes[$method][$uri] ?? null;

        if (!$action) {
            http_response_code(404);
            View::error('404');
            return;
        }

        try {
            $request = new Request();
            $response = new Response();

            if (is_array($action)) {
                [$controller, $method] = $action;
                (new $controller)->$method($request, $response);
            } else {
                $action($request, $response);
            }
        } catch (\Throwable $e) {
            http_response_code(500);
            \core\Support\ErrorHandler::handleException($e);
        }
    }

    public function loadRoutes(): void
    {
        require base_path('resources/routes/app.php');
    }
}
