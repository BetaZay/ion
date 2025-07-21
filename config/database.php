<?php

use core\Support\Env;

return [
    'dsn' => sprintf(
        'mysql:host=%s;dbname=%s;charset=%s',
        Env::get('DB_HOST', '127.0.0.1'),
        Env::get('DB_NAME', 'test'),
        Env::get('DB_CHARSET', 'utf8mb4')
    ),
    'user' => Env::get('DB_USER', 'root'),
    'password' => Env::get('DB_PASSWORD', ''),
];