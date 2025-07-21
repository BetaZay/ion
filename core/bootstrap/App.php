<?php

namespace core\Bootstrap;

use core\database\Database;
use core\http\Kernel;
use core\http\Router;
use core\middleware\Session;
use core\support\Env;

/**
 * Main application class
 * 
 * Handles application bootstrapping and execution.
 * Provides information about the application name and version.
 */
class App
{
    /**
     * Get the application name
     * 
     * Retrieves the application name from environment variables or returns default.
     * 
     * @return string The application name
     */
    public static function name(): string
    {
        return Env::get('APP_NAME', 'Ion');
    }
    
    /**
     * Get the application version
     * 
     * Retrieves the application version from environment variables or returns default.
     * 
     * @return string The application version string
     */
    public static function version(): string
    {
        return Env::get('APP_VERSION', '1.0.0');
    }

    /**
     * Run the application
     * 
     * Bootstraps and executes the application:
     * 1. Verifies the database connection
     * 2. Sets up the HTTP kernel
     * 3. Registers middleware
     * 4. Handles the request
     * 
     * @return void
     */
    public function run(): void
    {

        if (!Database::getConnection()) {
            throw new \Exception('database connection failed.');
        }

        $router = new Router();
        $router->loadRoutes();

        $kernel = new Kernel();
        $kernel->register([
            Session::class,
            // other middleware...
        ]);

        $kernel->handle(fn() => $router->dispatch());
    }
}