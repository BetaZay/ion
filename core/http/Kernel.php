<?php

namespace core\http;

use core\middleware\Middleware;

/**
 * HTTP Kernel
 *
 * Manages the HTTP request handling process and middleware pipeline.
 * Serves as the central point of entry for all requests entering the application.
 */
class Kernel
{
    /**
     * List of middleware classes to be executed
     *
     * @var array<int, class-string<Middleware>>
     */
    protected array $middleware = [];

    /**
     * Register middleware to be used for all requests
     *
     * @param array<int, class-string<Middleware>> $middlewareList List of middleware class names
     * @return void
     */
    public function register(array $middlewareList): void
    {
        $this->middleware = $middlewareList;
    }

    /**
     * Handle the request through the middleware pipeline
     *
     * Creates a middleware pipeline where each middleware is executed in the
     * order it was registered and then passes control to the core handler.
     *
     * @param callable $core The core application handler that runs after all middleware
     * @return void
     */
    public function handle(callable $core): void
    {
        $pipeline = array_reduce(
            array_reverse($this->middleware),
            fn($next, $middleware) => fn() => (new $middleware)->handle($next),
            $core
        );

        $pipeline();
    }
}