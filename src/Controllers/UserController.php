<?php

namespace App\Controllers;

class UserController extends BaseController
{
    /**
     * GET /api/users
     */
    public function index()
    {
        try {
            // Simulamos datos de usuarios
            $users = [
                ['id' => 1, 'name' => 'Juan Pérez', 'email' => 'juan@example.com'],
                ['id' => 2, 'name' => 'Ana García', 'email' => 'ana@example.com'],
                ['id' => 3, 'name' => 'Carlos López', 'email' => 'carlos@example.com']
            ];

            return $this->response->success($users, 'Usuarios obtenidos correctamente');
        } catch (\Exception $e) {
            return $this->response->error('Error al obtener usuarios', 500);
        }
    }

    /**
     * GET /api/users/{id}
     */
    public function show($id)
    {
        try {
            // Simulamos búsqueda de usuario
            $user = ['id' => $id, 'name' => 'Usuario ' . $id, 'email' => "user{$id}@example.com"];

            if (!$user) {
                return $this->response->error('Usuario no encontrado', 404);
            }

            return $this->response->success($user, 'Usuario encontrado');
        } catch (\Exception $e) {
            return $this->response->error('Error al obtener usuario', 500);
        }
    }

    /**
     * POST /api/users
     */
    public function store()
    {
        try {
            $data = $this->getRequestData();
            
            $errors = $this->validateRequest($data, [
                'name' => 'required',
                'email' => 'required|email'
            ]);

            if (!empty($errors)) {
                return $this->response->error('Datos inválidos', 422, $errors);
            }

            // Simulamos creación de usuario
            $user = [
                'id' => rand(1, 1000),
                'name' => $data['name'],
                'email' => $data['email'],
                'created_at' => date('Y-m-d H:i:s')
            ];

            return $this->response->success($user, 'Usuario creado correctamente', 201);
        } catch (\Exception $e) {
            return $this->response->error('Error al crear usuario', 500);
        }
    }

    /**
     * PUT /api/users/{id}
     */
    public function update($id)
    {
        try {
            $data = $this->getRequestData();
            
            $errors = $this->validateRequest($data, [
                'name' => 'required',
                'email' => 'required|email'
            ]);

            if (!empty($errors)) {
                return $this->response->error('Datos inválidos', 422, $errors);
            }

            // Simulamos actualización de usuario
            $user = [
                'id' => $id,
                'name' => $data['name'],
                'email' => $data['email'],
                'updated_at' => date('Y-m-d H:i:s')
            ];

            return $this->response->success($user, 'Usuario actualizado correctamente');
        } catch (\Exception $e) {
            return $this->response->error('Error al actualizar usuario', 500);
        }
    }

    /**
     * DELETE /api/users/{id}
     */
    public function destroy($id)
    {
        try {
            // Simulamos eliminación de usuario
            return $this->response->success(null, 'Usuario eliminado correctamente');
        } catch (\Exception $e) {
            return $this->response->error('Error al eliminar usuario', 500);
        }
    }
}