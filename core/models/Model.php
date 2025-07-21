<?php

namespace core\models;

use core\database\Database;
use PDO;

abstract class Model
{
    protected static string $table;
    protected static string $primaryKey = 'id';
    protected array $attributes = [];

    public function __construct(array $attributes = [])
    {
        $this->attributes = $attributes;
    }

    public static function find($id): ?static
    {
        $stmt = Database::getConnection()->prepare("SELECT * FROM " . static::getTable() . " WHERE " . static::$primaryKey . " = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? new static($row) : null;
    }

    public static function all(): array
    {
        $stmt = Database::getConnection()->query("SELECT * FROM " . static::getTable());
        return array_map(fn($row) => new static($row), $stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    public static function where(string $column, $value): ?static
    {
        $stmt = Database::getConnection()->prepare("SELECT * FROM " . static::getTable() . " WHERE `$column` = ?");
        $stmt->execute([$value]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? new static($row) : null;
    }

    public function save(): bool
    {
        return Database::insert(static::getTable(), $this->attributes);
    }

    public function update(array $data): void
    {
        $id = $this->attributes['id'] ?? null;
        if (!$id) {
            throw new \Exception("Cannot update: model has no ID");
        }

        Database::update($this->getTable(), $data, "id = ?", [$id]);

        // Optionally merge updated values
        $this->attributes = array_merge($this->attributes, $data);
    }

    public function delete(): bool
    {
        return Database::delete(
            static::getTable(),
            static::$primaryKey . " = ?",
            [$this->attributes[static::$primaryKey]]
        );
    }

    public function get(string $key): mixed
    {
        return $this->attributes[$key] ?? null;
    }

    public function set(string $key, mixed $value): void
    {
        $this->attributes[$key] = $value;
    }

    public static function getTable(): string
    {
        return static::$table ?? strtolower(class_basename(static::class));
    }
}
