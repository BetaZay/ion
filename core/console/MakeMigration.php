<?php

namespace core\console;

use core\contracts\ConsoleCommand;

class MakeMigration implements ConsoleCommand
{
    public function name(): string
    {
        return 'make:migration';
    }

    public function handle(array $args): void
    {
        $name = $args[0] ?? null;

        if (!$name) {
            echo "[!] Usage: php forge make:migration {name}\n";
            return;
        }

        $timestamp = date('Ymd_His');
        $fileName = "{$timestamp}_{$name}.php";

        // Extract table name from migration name
        $table = 'your_table';
        if (preg_match('/(?:create|add|drop|update|remove)?_?([a-z0-9_]+)_table$/i', $name, $matches)) {
            $table = $matches[1];
        }

        $stub = <<<PHP
    <?php

    use core\Database\Schema;

    return new class {
        public function up() {
            Schema::create('$table', function(\$table) {
                \$table->id();
                // \$table->string('name');
                // \$table->timestamps();
            });
        }

        public function down() {
            Schema::drop('$table');
        }
    };
    PHP;

        $path = base_path('database/migrations/' . $fileName);

        file_put_contents($path, $stub);

        echo "[✓] Migration created: database/migrations/{$fileName}\n";
    }
}
