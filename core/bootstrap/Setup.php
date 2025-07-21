<?php

namespace core\bootstrap;

use core\database\Migrator;
use core\logging\Logger;

class Setup
{
    public static function run(): void
    {
        Logger::info("Running migration manager...");
        Migrator::run();
    }
}