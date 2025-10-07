<?php

use App\Controllers\UserController;

// Definir rutas de la API
return [
    // Rutas de usuarios
    'GET /api/users' => [UserController::class, 'index'],
    'GET /api/users/{id}' => [UserController::class, 'show'],
    'POST /api/users' => [UserController::class, 'store'],
    'PUT /api/users/{id}' => [UserController::class, 'update'],
    'DELETE /api/users/{id}' => [UserController::class, 'destroy'],
    
    // Ruta de ejemplo para health check
    'GET /api/health' => function() {
        return (new \App\Utils\Response())->success([
            'status' => 'OK',
            'timestamp' => date('Y-m-d H:i:s'),
            'version' => '1.0.0'
        ], 'API funcionando correctamente');
    },
    
    // Ruta de bienvenida
    'GET /api' => function() {
        return (new \App\Utils\Response())->success([
            'message' => 'Bienvenido a Cogela Suave API',
            'version' => '1.0.0',
            'endpoints' => [
                'GET /api/health' => 'Health check',
                'GET /api/users' => 'Listar usuarios',
                'GET /api/users/{id}' => 'Obtener usuario',
                'POST /api/users' => 'Crear usuario',
                'PUT /api/users/{id}' => 'Actualizar usuario',
                'DELETE /api/users/{id}' => 'Eliminar usuario'
            ]
        ]);
    }
];