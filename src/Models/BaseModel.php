<?php

namespace App\Models;

use App\Utils\Database;
use PDO;

abstract class BaseModel
{
    protected Database $db;
    protected string $table;
    protected array $fillable = [];
    protected string $primaryKey = 'id';

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Obtiene todos los registros
     */
    public function all(): array
    {
        $sql = "SELECT * FROM {$this->table}";
        return $this->db->query($sql);
    }

    /**
     * Busca un registro por ID
     */
    public function find($id): ?array
    {
        $sql = "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = :id";
        $result = $this->db->query($sql, ['id' => $id]);
        
        return !empty($result) ? $result[0] : null;
    }

    /**
     * Crea un nuevo registro
     */
    public function create(array $data): ?array
    {
        $filteredData = $this->filterFillable($data);
        
        if (empty($filteredData)) {
            return null;
        }

        $fields = implode(', ', array_keys($filteredData));
        $placeholders = ':' . implode(', :', array_keys($filteredData));
        
        $sql = "INSERT INTO {$this->table} ({$fields}) VALUES ({$placeholders})";
        
        if ($this->db->execute($sql, $filteredData)) {
            $id = $this->db->lastInsertId();
            return $this->find($id);
        }
        
        return null;
    }

    /**
     * Actualiza un registro
     */
    public function update($id, array $data): ?array
    {
        $filteredData = $this->filterFillable($data);
        
        if (empty($filteredData)) {
            return null;
        }

        $setParts = [];
        foreach (array_keys($filteredData) as $field) {
            $setParts[] = "{$field} = :{$field}";
        }
        
        $setClause = implode(', ', $setParts);
        $filteredData['id'] = $id;
        
        $sql = "UPDATE {$this->table} SET {$setClause} WHERE {$this->primaryKey} = :id";
        
        if ($this->db->execute($sql, $filteredData)) {
            return $this->find($id);
        }
        
        return null;
    }

    /**
     * Elimina un registro
     */
    public function delete($id): bool
    {
        $sql = "DELETE FROM {$this->table} WHERE {$this->primaryKey} = :id";
        return $this->db->execute($sql, ['id' => $id]);
    }

    /**
     * Filtra solo los campos permitidos
     */
    protected function filterFillable(array $data): array
    {
        return array_intersect_key($data, array_flip($this->fillable));
    }

    /**
     * Busca registros con condiciones
     */
    public function where(string $column, $value): array
    {
        $sql = "SELECT * FROM {$this->table} WHERE {$column} = :{$column}";
        return $this->db->query($sql, [$column => $value]);
    }
}