<?php

namespace App\Utils;

use App\Utils\Response;

class Router
{
    private array $routes = [];
    private Response $response;

    public function __construct()
    {
        $this->response = new Response();
    }

    /**
     * Registra una ruta
     */
    public function addRoute(string $method, string $path, $handler): void
    {
        $this->routes[] = [
            'method' => strtoupper($method),
            'path' => $path,
            'handler' => $handler,
            'pattern' => $this->pathToPattern($path)
        ];
    }

    /**
     * Maneja la request actual
     */
    public function handleRequest(string $method, string $uri): void
    {
        $method = strtoupper($method);
        
        foreach ($this->routes as $route) {
            if ($route['method'] === $method && preg_match($route['pattern'], $uri, $matches)) {
                // Extraer parámetros de la URL
                $params = array_slice($matches, 1);
                
                $this->executeHandler($route['handler'], $params);
                return;
            }
        }

        // Si no se encuentra la ruta
        $this->response->notFound('Endpoint no encontrado');
    }

    /**
     * Ejecuta el handler de la ruta
     */
    private function executeHandler($handler, array $params = []): void
    {
        try {
            if (is_callable($handler)) {
                // Si es una función anónima
                call_user_func_array($handler, $params);
            } elseif (is_array($handler) && count($handler) === 2) {
                // Si es [Controller::class, 'method']
                [$controllerClass, $method] = $handler;
                
                if (!class_exists($controllerClass)) {
                    throw new \Exception("Controller {$controllerClass} no encontrado");
                }

                $controller = new $controllerClass();
                
                if (!method_exists($controller, $method)) {
                    throw new \Exception("Método {$method} no encontrado en {$controllerClass}");
                }

                call_user_func_array([$controller, $method], $params);
            } else {
                throw new \Exception("Handler inválido");
            }
        } catch (\Exception $e) {
            $this->response->error("Error al ejecutar la ruta: " . $e->getMessage(), 500);
        }
    }

    /**
     * Convierte un path con parámetros a un pattern regex
     */
    private function pathToPattern(string $path): string
    {
        // Escapar caracteres especiales excepto {}
        $pattern = preg_quote($path, '/');
        
        // Reemplazar {param} con ([^/]+)
        $pattern = preg_replace('/\\\{([^}]+)\\\}/', '([^/]+)', $pattern);
        
        return '/^' . $pattern . '$/';
    }

    /**
     * Métodos de conveniencia para registrar rutas
     */
    public function get(string $path, $handler): void
    {
        $this->addRoute('GET', $path, $handler);
    }

    public function post(string $path, $handler): void
    {
        $this->addRoute('POST', $path, $handler);
    }

    public function put(string $path, $handler): void
    {
        $this->addRoute('PUT', $path, $handler);
    }

    public function patch(string $path, $handler): void
    {
        $this->addRoute('PATCH', $path, $handler);
    }

    public function delete(string $path, $handler): void
    {
        $this->addRoute('DELETE', $path, $handler);
    }

    public function options(string $path, $handler): void
    {
        $this->addRoute('OPTIONS', $path, $handler);
    }

    /**
     * Registra un grupo de rutas con prefijo
     */
    public function group(string $prefix, callable $callback): void
    {
        $originalRoutes = $this->routes;
        $this->routes = [];
        
        $callback($this);
        
        // Agregar prefijo a las rutas del grupo
        foreach ($this->routes as &$route) {
            $route['path'] = $prefix . $route['path'];
            $route['pattern'] = $this->pathToPattern($route['path']);
        }
        
        $this->routes = array_merge($originalRoutes, $this->routes);
    }
}