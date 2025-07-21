<?php

/**
 * Application Autoloader and bootstrap
 * 
 * This file handles:
 * 1. PSR-4 class autoloading for core and app namespaces
 * 2. Loading helper functions
 * 3. Environment configuration via .env file
 * 4. logging setup
 * 5. database connection initialization
 * 6. Initial application setup
 *
 */

/**
 * PSR-4 autoloader implementation for core and app namespaces
 * 
 * @param string $class The fully-qualified class name to load
 * @return void
 */
spl_autoload_register(function ($class) {
    /**
     * Namespace prefix to directory mappings
     * @var array<string, string> $prefixes
     */
    $prefixes = [
        'core\\' => __DIR__ . '/core/',
        'app\\' => __DIR__ . '/app/',
    ];

    foreach ($prefixes as $prefix => $base_dir) {
        if (str_starts_with($class, $prefix)) {
            $relative = substr($class, strlen($prefix));
            $file = $base_dir . str_replace('\\', '/', $relative) . '.php';
            if (file_exists($file)) {
                require $file;
                return;
            }
        }
    }
});

/**
 * Load common helper functions
 */
require_once __DIR__ . '/core/support/Helpers.php';


/**
 * Load environment configuration
 */
require_once __DIR__ . '/core/support/Env.php';;
\core\support\Env::load(__DIR__ . '/.env');


if (php_sapi_name() === 'cli') {
    set_exception_handler(function ($e) {
        fwrite(STDERR, "[Exception] " . $e->getMessage() . "\n");
        fwrite(STDERR, "In " . $e->getFile() . " on line " . $e->getLine() . "\n");
        exit(1);
    });
} else {
    require_once __DIR__ . '/core/support/ErrorHandler.php';
    \core\support\ErrorHandler::register();
}

/**
 * Initialize logging system
 */
require_once __DIR__ . '/core/logging/Logger.php';;
\core\logging\Logger::init();

if (!file_exists(__DIR__ . '/.env')) {
    \core\logging\Logger::warning('.env file not found — using defaults');
} else {
    \core\logging\Logger::debug('.env loaded successfully');
}

/**
 * Configure and connect to the database
 * 
 * @var array $config database configuration parameters
 */
require_once __DIR__ . '/core/database/Database.php';;
$config = require __DIR__ . '/config/database.php';
\core\database\Database::connect($config);

/**
 * Run initial application setup procedures
 */
require_once __DIR__ . '/core/bootstrap/Setup.php';
\core\bootstrap\Setup::run();