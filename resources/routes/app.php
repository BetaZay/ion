<?php

use core\http\router;

/** @var Router $router */

$router->get('/', function () {
    \core\Support\View::render('welcome');
});

$router->get('/status', function () {
    echo "app is alive.";
});

// You can include more route files like this:
//require_once __DIR__ . '/api.php';