<?php

namespace core\bootstrap;

use core\contracts\ConsoleCommand;

/**
 * console Kernel for handling command registration and execution.
 */
class ConsoleKernel
{
    /**
     * Array of registered console commands.
     *
     * @var array<string, ConsoleCommand>
     */
    protected array $commands = [];

    /**
     * Initialize the console kernel and load available commands.
     */
    public function __construct()
    {
        $this->loadCommands(base_path('core/console'), 'core\\console');
        $this->loadCommands(base_path('app/console'), 'app\\console');
    }

    /**
     * Load commands from the specified directory path.
     *
     * @param string $path The directory path to load commands from
     * @param string $namespace The namespace to use for commands
     * @return void
     */
    protected function loadCommands(string $path, string $namespace): void
    {
        if (!is_dir($path)) return;

        foreach (glob($path . '/*.php') as $file) {
            require_once $file;

            $base = basename($file, '.php');
            $class = "$namespace\\$base";

            if (class_exists($class)) {
                $instance = new $class();
                if ($instance instanceof ConsoleCommand) {
                    $this->commands[$instance->name()] = $instance;
                }
            }
        }
    }

    /**
     * Get the fully qualified class name from a file path.
     *
     * @param string $file The file path
     * @return string The fully qualified class name
     */
    protected function getClassFromFile(string $file): string
    {
        $base = basename($file, '.php');

        if (str_starts_with(realpath($file), realpath(__DIR__ . '/console'))) {
            return "core\\console\\$base";
        }

        return "app\\console\\$base";
    }

    /**
     * Run a specified console command with optional arguments.
     *
     * @param string $command The command name to run
     * @param array $args Optional arguments to pass to the command
     * @return void
     */
    public function run(string $command, array $args = []): void
    {
        if (!isset($this->commands[$command])) {
            echo "[!] Unknown command: $command\n";
            return;
        }

        $this->commands[$command]->handle($args);
    }

    /**
     * List all available console commands.
     *
     * @return void
     */
    public function list(): void
    {
        echo "Available commands:\n";
        foreach ($this->commands as $name => $cmd) {
            echo "  - $name\n";
        }
    }
}