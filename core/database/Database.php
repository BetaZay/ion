<?php

namespace core\database;

use core\logging\Logger;
use PDO;
use PDOException;

/**
 * database connection and query management class
 * 
 * Provides a static interface for database operations using PDO.
 */
class Database
{
    /**
     * PDO connection instance
     * 
     * @var PDO|null
     */
    private static ?PDO $pdo = null;

    /**
     * Establish database connection
     * 
     * Creates a PDO connection with the provided configuration.
     * Logs success or failure of the connection attempt.
     * 
     * @param array $config Connection configuration with 'dsn', 'user', and 'password' keys
     * @return void
     * @throws PDOException When connection fails (caught internally)
     */
    public static function connect(array $config): void
    {
        try {
            self::$pdo = new PDO(
                $config['dsn'],
                $config['user'],
                $config['password'],
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]
            );
            Logger::info("database connection established.");
        } catch (PDOException $e) {
            Logger::error("database connection failed: " . $e->getMessage());
            die("database connection failed.");
        }
    }

    /**
     * Get the PDO connection instance
     * 
     * @return PDO The active PDO connection
     * @throws \RuntimeException If connect() has not been called
     */
    public static function getConnection(): PDO
    {
        if (!self::$pdo) {
            Logger::critical("Tried to use database before calling connect().");
            throw new \RuntimeException("database not connected.");
        }
        return self::$pdo;
    }

    /**
     * Check if a table exists in the database
     * 
     * @param string $table Name of the table to check
     * @return bool True if the table exists, false otherwise
     */
    public static function tableExists(string $table): bool
    {
        $stmt = self::getConnection()->prepare("SHOW TABLES LIKE ?");
        $stmt->execute([$table]);
        return $stmt->rowCount() > 0;
    }

    /**
     * Insert a new row into a table
     * 
     * @param string $table Table name
     * @param array $data Associative array of column => value pairs to insert
     * @return bool True if insertion was successful
     */
    public static function insert(string $table, array $data): bool
    {
        $columns = implode(',', array_keys($data));
        $placeholders = implode(',', array_fill(0, count($data), '?'));

        $stmt = self::getConnection()->prepare("INSERT INTO `$table` ($columns) VALUES ($placeholders)");
        return $stmt->execute(array_values($data));
    }

    /**
     * Update rows in a table that match the given criteria
     * 
     * @param string $table Table name
     * @param array $data Associative array of column => value pairs to update
     * @param string $whereClause SQL WHERE clause (without the "WHERE" keyword)
     * @param array $whereParams Values to bind to placeholders in the WHERE clause
     * @return void
     */
    public static function update(string $table, array $data, string $whereClause, array $whereParams = []): void
    {
        $sets = [];
        $values = [];

        foreach ($data as $column => $value) {
            $sets[] = "`$column` = ?";
            $values[] = $value;
        }

        $sql = "UPDATE `$table` SET " . implode(', ', $sets) . " WHERE $whereClause";
        $stmt = self::getConnection()->prepare($sql);
        $stmt->execute([...$values, ...$whereParams]);
    }

    /**
     * Delete rows from a table that match the given criteria
     * 
     * @param string $table Table name
     * @param string $where SQL WHERE clause (without the "WHERE" keyword)
     * @param array $bindings Values to bind to placeholders in the WHERE clause
     * @return bool True if deletion was successful
     */
    public static function delete(string $table, string $where, array $bindings = []): bool
    {
        $stmt = self::getConnection()->prepare("DELETE FROM `$table` WHERE $where");
        return $stmt->execute($bindings);
    }

    /**
     * Execute a raw SQL query
     * 
     * @param string $sql Raw SQL query to execute
     * @return bool True if query execution was successful
     */
    public static function raw(string $sql): bool
    {
        return self::getConnection()->exec($sql) !== false;
    }
}