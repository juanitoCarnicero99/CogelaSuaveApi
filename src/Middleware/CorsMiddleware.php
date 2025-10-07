<?php

namespace App\Middleware;

use App\Utils\Response;

class CorsMiddleware extends BaseMiddleware
{
    private Response $response;

    public function __construct()
    {
        $this->response = new Response();
    }

    public function handle(): bool
    {
        $config = require __DIR__ . '/../../config/app.php';
        $corsConfig = $config['cors'];

        // Configurar headers CORS
        $origin = $_SERVER['HTTP_ORIGIN'] ?? '*';
        
        if ($corsConfig['allowed_origins'] !== '*') {
            $allowedOrigins = explode(',', $corsConfig['allowed_origins']);
            if (!in_array($origin, $allowedOrigins)) {
                $origin = '';
            }
        }

        header("Access-Control-Allow-Origin: " . $origin);
        header("Access-Control-Allow-Methods: " . $corsConfig['allowed_methods']);
        header("Access-Control-Allow-Headers: " . $corsConfig['allowed_headers']);
        header("Access-Control-Max-Age: 86400");

        // Manejar preflight requests
        if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            http_response_code(200);
            exit();
        }

        return true;
    }
}