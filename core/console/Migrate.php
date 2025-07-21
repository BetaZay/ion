<?php

namespace core\console;

use core\contracts\ConsoleCommand;
use core\Database\Migrator;

class Migrate implements ConsoleCommand
{
    public function name(): string
    {
        return 'migrate';
    }

    public function handle(array $args): void
    {
        echo "[>] Running migrations...\n";
        Migrator::run();
        echo "[✓] migrations complete.\n";
    }
}
