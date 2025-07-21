<?php

namespace core\console;

use core\contracts\ConsoleCommand;

class MakeModel implements ConsoleCommand
{
    public function name(): string
    {
        return 'make:model';
    }

    public function handle(array $args): void
    {
        $name = $args[0] ?? null;

        if (!$name) {
            echo "[!] Usage: php forge make:model {Name}\n";
            return;
        }

        $class = ucfirst($name);
        $stub = <<<PHP
        <?php

        class {$class} extends Model
        {
            protected static string \$table = strtolower(__CLASS__); // Override if needed
        }
        PHP;

        $path = __DIR__ . '/../../models/' . $class . '.php';

        if (file_exists($path)) {
            echo "[!] Model already exists: {$class}\n";
            return;
        }

        file_put_contents($path, $stub);
        echo "[✓] Model created: models/{$class}.php\n";
    }
}
