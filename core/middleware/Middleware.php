<?php

namespace core\middleware;

/**
 * middleware Interface
 *
 * Defines the contract for all middleware components in the application.
 * middleware allows for filtering HTTP requests entering the application.
 */
interface Middleware
{
    /**
     * Handle the request and pass it to the next middleware
     *
     * Implementations should either handle the request and call the next middleware
     * or perform some action and terminate the chain.
     *
     * @param callable $next The next middleware in the pipeline
     * @return void
     */
    public function handle(callable $next): void;
}