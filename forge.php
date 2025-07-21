#!/usr/bin/env php
<?php

use core\Bootstrap\ConsoleKernel;

require_once __DIR__ . '/autoload.php';

$command = $argv[1] ?? null;
$args = array_slice($argv, 2);

$kernel = new ConsoleKernel();

if (!$command || in_array($command, ['list', '--help', '-h'])) {
    $kernel->list();
    exit;
}

$kernel->run($command, $args);