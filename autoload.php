<?php

/**
 * Dynamic PSR-4 Autoloader + Bootstrap
 */

require_once __DIR__ . '/core/support/Helpers.php';
require_once __DIR__ . '/core/support/Env.php';

/**
 * PSR-4 Autoloader
 */
spl_autoload_register(function (string $class): void {
    $prefixes = [
        'core\\' => __DIR__ . '/core/',
        'app\\'  => __DIR__ . '/app/',
    ];

    foreach ($prefixes as $prefix => $baseDir) {
        if (str_starts_with($class, $prefix)) {
            $relativeClass = substr($class, strlen($prefix));
            $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

            if (is_file($file)) {
                require $file;
            }
            return;
        }
    }
});

/**
 * Load environment
 */
\core\support\Env::load(__DIR__ . '/.env');

/**
 * Register error handling
 */
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
 * Logging
 */
require_once __DIR__ . '/core/logging/Logger.php';
\core\logging\Logger::init();

if (!file_exists(__DIR__ . '/.env')) {
    \core\logging\Logger::warning('.env file not found — using defaults');
} else {
    \core\logging\Logger::debug('.env loaded successfully');
}

/**
 * Database
 */
require_once __DIR__ . '/core/database/Database.php';
$config = require __DIR__ . '/config/database.php';
\core\database\Database::connect($config);

/**
 * Bootstrap
 */
require_once __DIR__ . '/core/bootstrap/Setup.php';
\core\bootstrap\Setup::run();
