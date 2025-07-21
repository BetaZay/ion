<?php

use core\http\Router as Route;

Route::get('/',  function () {
    \core\pulse\View::render('welcome');
});

Route::get('/status', fn($req, $res) => $res->send('app is alive'));
// You can include more route files like this:
//require_once __DIR__ . '/api.php';