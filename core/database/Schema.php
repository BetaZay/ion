<?php

namespace core\database;

use core\logging\Logger;

class Schema
{
    private string $tableName;
    private array $columns = [];
    private bool $isAlter = false;

    public static function table(string $name, callable $callback): void
    {
        $instance = new self($name);
        $callback($instance);

        if (Database::tableExists($name)) {
            $instance->isAlter = true;
            $instance->alter();
        } else {
            $instance->create();
        }
    }

    public static function drop(string $name): void
    {
        $sql = "DROP TABLE IF EXISTS `$name`;";
        Database::getConnection()->exec($sql);
        Logger::info("Dropped table `$name`");
    }

    public static function dropColumn(string $table, string $column): void
    {
        $sql = "ALTER TABLE `$table` DROP COLUMN `$column`;";
        Database::getConnection()->exec($sql);
        Logger::info("Dropped column `$column` from `$table`");
    }

    public function __construct(string $tableName)
    {
        $this->tableName = $tableName;
    }

    public function id(string $name = 'id'): static
    {
        $this->columns[] = "$name INT AUTO_INCREMENT PRIMARY KEY";
        return $this;
    }

    public function string(string $name, int $length = 255): ColumnDefinition
    {
        return $this->addColumn(new ColumnDefinition("VARCHAR($length)", $name, $this));
    }

    public function text(string $name): ColumnDefinition
    {
        return $this->addColumn(new ColumnDefinition("TEXT", $name, $this));
    }

    public function longText(string $name): ColumnDefinition
    {
        return $this->addColumn(new ColumnDefinition("LONGTEXT", $name, $this));
    }

    public function timestamp(string $name): ColumnDefinition
    {
        return $this->addColumn(new ColumnDefinition("TIMESTAMP", $name, $this));
    }

    public function timestamps(): static
    {
        $this->columns[] = "created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP";
        $this->columns[] = "updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP";
        return $this;
    }

    public function boolean(string $name): ColumnDefinition
    {
        return $this->addColumn(new ColumnDefinition("TINYINT(1)", $name, $this));
    }

    public function integer(string $name): ColumnDefinition
    {
        return $this->addColumn(new ColumnDefinition("INT", $name, $this));
    }

    public function bigInteger(string $name): ColumnDefinition
    {
        return $this->addColumn(new ColumnDefinition("BIGINT", $name, $this));
    }

    public function foreignId(string $name): ColumnDefinition
    {
        return $this->addColumn(new ColumnDefinition("BIGINT UNSIGNED", $name, $this));
    }


    public function raw(string $definition): static
    {
        $this->columns[] = $definition;
        return $this;
    }

    private function addColumn(ColumnDefinition $column): ColumnDefinition
    {
        $this->columns[] = &$column->ref;
        return $column;
    }

    public function create(): void
    {
        $sql = "CREATE TABLE `{$this->tableName}` (\n" . $this->getSQL() . "\n);";
        Database::getConnection()->exec($sql);
        Logger::info("Created table `{$this->tableName}`");
    }

    public function alter(): void
    {
        foreach ($this->columns as $definition) {
            $sql = "ALTER TABLE `{$this->tableName}` ADD $definition;";
            Database::getConnection()->exec($sql);
            Logger::info("Altered `{$this->tableName}`: ADD $definition");
        }
    }

    private function getSQL(): string
    {
        return implode(",\n", $this->columns);
    }
}

class ColumnDefinition
{
    public string $ref;
    private string $name;
    private Schema $schema;

    public function __construct(string $type, string $name, Schema $schema)
    {
        $this->name = $name;
        $this->ref = "`$name` $type";
        $this->schema = $schema;
    }

    public function primary(): static
    {
        $this->ref .= " PRIMARY KEY";
        return $this;
    }

    public function nullable(): static
    {
        $this->ref .= " NULL";
        return $this;
    }

    public function notNull(): static
    {
        $this->ref .= " NOT NULL";
        return $this;
    }

    public function unsigned(): static
    {
        $this->ref .= " UNSIGNED";
        return $this;
    }

    public function autoIncrement(): static
    {
        $this->ref .= " AUTO_INCREMENT";
        return $this;
    }

    public function unique(): static
    {
        $this->ref .= " UNIQUE";
        return $this;
    }

    public function index(): static
    {
        $this->ref .= " INDEX";
        return $this;
    }

    public function default(string $val): static
    {
        $this->ref .= " DEFAULT $val";
        return $this;
    }

    public function after(string $column): static
    {
        $this->ref .= " AFTER `$column`";
        return $this;
    }

    public function first(): static
    {
        $this->ref .= " FIRST";
        return $this;
    }
}
