<?php

namespace core\console;

use core\contracts\ConsoleCommand;

class Serve implements ConsoleCommand
{
    public function name(): string
    {
        return 'serve';
    }

    public function handle(array $args): void
    {
        // Defaults
        $host = '127.0.0.1';
        $port = 8000;
        $tries = 1;

        // Parse args
        foreach ($args as $arg) {
            if (str_starts_with($arg, '--host=')) {
                $host = substr($arg, 7);
            } elseif (str_starts_with($arg, '--port=')) {
                $port = (int) substr($arg, 7);
            } elseif (str_starts_with($arg, '--tries=')) {
                $tries = (int) substr($arg, 8);
            }
        }

        $public = base_path('public');

        for ($i = 0; $i < $tries; $i++) {
            $tryPort = $port + $i;

            echo "➜ Starting Ion dev server at http://$host:$tryPort\n";
            echo "➜ Press Ctrl+C to stop\n";

            $cmd = "php -S $host:$tryPort -t $public";
            passthru($cmd, $exitCode);

            if ($exitCode === 0) break;

            echo "[!] Port $tryPort failed, trying next...\n";
        }
    }
}
