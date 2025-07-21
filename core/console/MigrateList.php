<?php

namespace core\console;

use core\contracts\ConsoleCommand;
use core\Database\Database;
use PDO;

class MigrateList implements ConsoleCommand
{
    public function name(): string
    {
        return 'migrate:list';
    }

    public function handle(array $args): void
    {
        echo "[>] Listing All migrations...\n";

        $applied = [];

        if (Database::tableExists('migrations')) {
            $applied = Database::getConnection()
                ->query("SELECT name FROM migrations")
                ->fetchAll(PDO::FETCH_COLUMN) ?? [];
        }

        $files = glob(base_path('database/migrations/*.php'));
        sort($files);

        if (empty($files)) {
            echo "[•] No migration files found.\n";
            return;
        }

        foreach ($files as $file) {
            $name = basename($file, '.php');
            $status = in_array($name, $applied) ? '✓ APPLIED' : '– UNAPPLIED';
            $pad = str_pad($name, 50);
            echo "  $pad  [$status]\n";
        }
    }
}
