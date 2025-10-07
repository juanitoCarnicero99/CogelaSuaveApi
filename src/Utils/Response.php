<?php

namespace App\Utils;

class Response
{
    /**
     * Envía una respuesta exitosa
     */
    public function success($data = null, string $message = 'Success', int $code = 200)
    {
        $this->sendResponse([
            'success' => true,
            'message' => $message,
            'data' => $data,
            'timestamp' => date('Y-m-d H:i:s')
        ], $code);
    }

    /**
     * Envía una respuesta de error
     */
    public function error(string $message = 'Error', int $code = 400, $errors = null)
    {
        $response = [
            'success' => false,
            'message' => $message,
            'timestamp' => date('Y-m-d H:i:s')
        ];

        if ($errors !== null) {
            $response['errors'] = $errors;
        }

        $this->sendResponse($response, $code);
    }

    /**
     * Envía una respuesta con paginación
     */
    public function paginated(array $data, int $total, int $page = 1, int $perPage = 15, string $message = 'Success')
    {
        $totalPages = ceil($total / $perPage);
        
        $this->sendResponse([
            'success' => true,
            'message' => $message,
            'data' => $data,
            'pagination' => [
                'current_page' => $page,
                'per_page' => $perPage,
                'total' => $total,
                'total_pages' => $totalPages,
                'has_next' => $page < $totalPages,
                'has_prev' => $page > 1
            ],
            'timestamp' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Envía la respuesta JSON
     */
    private function sendResponse(array $data, int $code = 200)
    {
        http_response_code($code);
        echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        exit;
    }

    /**
     * Respuesta para recursos no encontrados
     */
    public function notFound(string $message = 'Recurso no encontrado')
    {
        $this->error($message, 404);
    }

    /**
     * Respuesta para métodos no permitidos
     */
    public function methodNotAllowed(string $message = 'Método no permitido')
    {
        $this->error($message, 405);
    }

    /**
     * Respuesta para datos no válidos
     */
    public function validationError(array $errors, string $message = 'Datos no válidos')
    {
        $this->error($message, 422, $errors);
    }

    /**
     * Respuesta para no autorizado
     */
    public function unauthorized(string $message = 'No autorizado')
    {
        $this->error($message, 401);
    }

    /**
     * Respuesta para prohibido
     */
    public function forbidden(string $message = 'Acceso prohibido')
    {
        $this->error($message, 403);
    }
}