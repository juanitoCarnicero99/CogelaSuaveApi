<?php

namespace App\Controllers;

use App\Utils\Response;

abstract class BaseController
{
    protected Response $response;

    public function __construct()
    {
        $this->response = new Response();
    }

    /**
     * Valida los datos de entrada
     */
    protected function validateRequest(array $data, array $rules): array
    {
        $errors = [];
        
        foreach ($rules as $field => $rule) {
            if ($rule === 'required' && (!isset($data[$field]) || empty($data[$field]))) {
                $errors[$field] = "El campo {$field} es requerido";
            }
            
            if (isset($data[$field]) && $rule === 'email' && !filter_var($data[$field], FILTER_VALIDATE_EMAIL)) {
                $errors[$field] = "El campo {$field} debe ser un email válido";
            }
        }
        
        return $errors;
    }

    /**
     * Obtiene los datos JSON del request
     */
    protected function getRequestData(): array
    {
        $input = file_get_contents('php://input');
        return json_decode($input, true) ?? [];
    }
}