<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Utils\Router;
use App\Utils\Response;
use App\Middleware\CorsMiddleware;

// Cargar variables de entorno
if (file_exists(__DIR__ . '/../.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
    $dotenv->load();
}

// Configurar zona horaria
$config = require __DIR__ . '/../config/app.php';
date_default_timezone_set($config['timezone']);

// Manejar errores de PHP como JSON
set_error_handler(function($severity, $message, $file, $line) {
    if (!(error_reporting() & $severity)) {
        return;
    }
    
    $response = new Response();
    $response->error("Error interno del servidor", 500, [
        'message' => $message,
        'file' => $file,
        'line' => $line
    ]);
});

// Manejar excepciones no capturadas
set_exception_handler(function($exception) {
    $response = new Response();
    $response->error("Error interno del servidor", 500, [
        'message' => $exception->getMessage(),
        'file' => $exception->getFile(),
        'line' => $exception->getLine()
    ]);
});

try {
    // Aplicar middleware CORS
    $corsMiddleware = new CorsMiddleware();
    $corsMiddleware->handle();

    // Configurar headers para JSON
    header('Content-Type: application/json; charset=utf-8');

    // Crear router y cargar rutas
    $router = new Router();
    $routes = require __DIR__ . '/../routes/api.php';
    
    foreach ($routes as $route => $handler) {
        [$method, $path] = explode(' ', $route, 2);
        $router->addRoute($method, $path, $handler);
    }

    // Manejar la request
    $requestMethod = $_SERVER['REQUEST_METHOD'] ?? 'GET';
    $requestUri = parse_url($_SERVER['REQUEST_URI'] ?? '/api/health', PHP_URL_PATH);

    $router->handleRequest($requestMethod, $requestUri);

} catch (Exception $e) {
    $response = new Response();
    $response->error("Error interno del servidor", 500, [
        'message' => $e->getMessage()
    ]);
}