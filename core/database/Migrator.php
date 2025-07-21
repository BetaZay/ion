<?php

namespace core\database;

use core\logging\Logger;
use PDO;
use PDOException;
use Throwable;

class Migrator
{
    protected static function migrationDir(): string
    {
        return base_path('database/migrations');
    }

    public static function run(): void
    {
        try {
            if (!Database::tableExists('migrations')) {
                Logger::info("Creating migrations table...");
                Schema::table('migrations', function ($table) {
                    $table->id();
                    $table->string('name', 255);
                    $table->timestamp('applied_at')->default('CURRENT_TIMESTAMP');
                });
            }
        } catch (Throwable $e) {
            Logger::critical("Failed to create migrations table: " . $e->getMessage());
            echo "[!] Migration system boot failed: {$e->getMessage()}\n";
            return;
        }

        try {
            $applied = Database::getConnection()
                ->query("SELECT name FROM migrations")
                ->fetchAll(PDO::FETCH_COLUMN) ?? [];
        } catch (PDOException $e) {
            Logger::critical("Failed to read applied migrations: " . $e->getMessage());
            echo "[!] Could not fetch migration status: {$e->getMessage()}\n";
            return;
        }

        $migrations = glob(self::migrationDir() . '/*.php');
        sort($migrations);

        foreach ($migrations as $file) {
            $name = basename($file, '.php');

            if (in_array($name, $applied)) {
                Logger::debug("Skipping already applied migration: $name");
                continue;
            }

            try {
                Logger::info("Applying migration: $name");

                $migration = require $file;

                if (is_object($migration) && method_exists($migration, 'up')) {
                    $migration->up();
                } else {
                    Logger::warning("Migration $name does not implement up(); skipped.");
                    continue;
                }

                Database::insert('migrations', ['name' => $name]);
                echo "[✓] Migration applied: $name\n";
            } catch (Throwable $e) {
                Logger::error("Migration failed: $name → " . $e->getMessage());
                echo "[✗] Migration failed: $name\n";
                echo "    → Error: {$e->getMessage()}\n";
                break;
            }
        }
    }

    public static function rollback(): void
    {
        $applied = Database::getConnection()
            ->query("SELECT name FROM migrations ORDER BY applied_at DESC LIMIT 1")
            ->fetchColumn();

        if (!$applied) {
            echo "[!] No migrations to roll back.\n";
            return;
        }

        $file = self::migrationDir() . "/{$applied}.php";

        if (!file_exists($file)) {
            echo "[!] Migration file not found: $applied\n";
            return;
        }

        try {
            $migration = require $file;

            if (is_object($migration) && method_exists($migration, 'down')) {
                $migration->down();
                Database::getConnection()
                    ->prepare("DELETE FROM migrations WHERE name = ?")
                    ->execute([$applied]);
                echo "[✓] Rolled back: $applied\n";
            } else {
                echo "[!] Migration $applied has no down() method.\n";
            }
        } catch (Throwable $e) {
            echo "[✗] Rollback failed: {$e->getMessage()}\n";
        }
    }
}