<?php

namespace core\contracts;

interface ConsoleCommand
{
    public function name(): string;
    public function handle(array $args): void;
}
