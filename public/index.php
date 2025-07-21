<?php

/**
 * Application entry point
 * 
 * This script bootstraps the application by:
 * 1. Loading the autoloader
 * 2. Creating a new app instance
 * 3. Running the application
 * 
 * @package core
 */

require_once __DIR__ . '/../autoload.php';

use core\bootstrap\App;

/**
 * Initialize the application
 * 
 * @var \core\Bootstrap\App $app The main application instance
 */
$app = new App();

// Start the application
$app->run();