<?php

namespace App\Middleware;

abstract class BaseMiddleware
{
    abstract public function handle(): bool;
    
    protected function getAuthorizationHeader(): ?string
    {
        $headers = getallheaders();
        
        if (isset($headers['Authorization'])) {
            return $headers['Authorization'];
        }
        
        if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
            return $_SERVER['HTTP_AUTHORIZATION'];
        }
        
        return null;
    }
    
    protected function getBearerToken(): ?string
    {
        $header = $this->getAuthorizationHeader();
        
        if ($header && preg_match('/Bearer\s+(.*)$/i', $header, $matches)) {
            return $matches[1];
        }
        
        return null;
    }
}