<?php

namespace App\Utils;

use PDO;
use PDOException;

class Database
{
    private static ?Database $instance = null;
    private PDO $connection;
    private array $config;

    private function __construct()
    {
        $this->config = require __DIR__ . '/../../config/database.php';
        $this->connect();
    }

    public static function getInstance(): Database
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    private function connect(): void
    {
        $config = $this->config['connections'][$this->config['default']];

        try {
            if ($config['driver'] === 'mysql') {
                $dsn = "mysql:host={$config['host']};port={$config['port']};dbname={$config['database']};charset={$config['charset']}";
            } elseif ($config['driver'] === 'sqlite') {
                $dsn = "sqlite:{$config['database']}";
            } else {
                throw new PDOException("Driver no soportado: {$config['driver']}");
            }

            $this->connection = new PDO(
                $dsn,
                $config['username'] ?? null,
                $config['password'] ?? null,
                $config['options'] ?? []
            );

        } catch (PDOException $e) {
            throw new PDOException("Error de conexión a la base de datos: " . $e->getMessage());
        }
    }

    public function getConnection(): PDO
    {
        return $this->connection;
    }

    /**
     * Ejecuta una consulta SELECT
     */
    public function query(string $sql, array $params = []): array
    {
        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            throw new PDOException("Error en consulta: " . $e->getMessage());
        }
    }

    /**
     * Ejecuta una consulta INSERT, UPDATE o DELETE
     */
    public function execute(string $sql, array $params = []): bool
    {
        try {
            $stmt = $this->connection->prepare($sql);
            return $stmt->execute($params);
        } catch (PDOException $e) {
            throw new PDOException("Error en ejecución: " . $e->getMessage());
        }
    }

    /**
     * Obtiene el último ID insertado
     */
    public function lastInsertId(): string
    {
        return $this->connection->lastInsertId();
    }

    /**
     * Inicia una transacción
     */
    public function beginTransaction(): bool
    {
        return $this->connection->beginTransaction();
    }

    /**
     * Confirma una transacción
     */
    public function commit(): bool
    {
        return $this->connection->commit();
    }

    /**
     * Revierte una transacción
     */
    public function rollback(): bool
    {
        return $this->connection->rollback();
    }

    /**
     * Verifica si hay una transacción activa
     */
    public function inTransaction(): bool
    {
        return $this->connection->inTransaction();
    }

    /**
     * Ejecuta múltiples consultas en una transacción
     */
    public function transaction(callable $callback)
    {
        $this->beginTransaction();

        try {
            $result = $callback($this);
            $this->commit();
            return $result;
        } catch (\Exception $e) {
            $this->rollback();
            throw $e;
        }
    }
}