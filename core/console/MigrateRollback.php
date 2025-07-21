<?php

namespace core\console;

use core\contracts\ConsoleCommand;
use core\Database\Migrator;

class MigrateRollback implements ConsoleCommand
{
    public function name(): string
    {
        return 'migrate:rollback';
    }

    public function handle(array $args): void
    {
        echo "[>] Rolling back last migration...\n";
        Migrator::rollback();
    }
}
