<?php

namespace App\Models;

class User extends BaseModel
{
    protected string $table = 'users';
    
    protected array $fillable = [
        'name',
        'email',
        'password',
        'created_at',
        'updated_at'
    ];

    /**
     * Busca un usuario por email
     */
    public function findByEmail(string $email): ?array
    {
        $result = $this->where('email', $email);
        return !empty($result) ? $result[0] : null;
    }

    /**
     * Crea un usuario con password hash
     */
    public function createUser(array $data): ?array
    {
        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        
        return $this->create($data);
    }

    /**
     * Actualiza un usuario
     */
    public function updateUser($id, array $data): ?array
    {
        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        
        $data['updated_at'] = date('Y-m-d H:i:s');
        
        return $this->update($id, $data);
    }

    /**
     * Verifica la contraseña
     */
    public function verifyPassword(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }
}