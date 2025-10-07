<?php

namespace App\Utils;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtHelper
{
    private string $secret;
    private string $algorithm;
    private int $expiration;

    public function __construct()
    {
        $config = require __DIR__ . '/../../config/app.php';
        $this->secret = $config['jwt']['secret'];
        $this->algorithm = $config['jwt']['algorithm'];
        $this->expiration = $config['jwt']['expire'];
    }

    /**
     * Genera un token JWT
     */
    public function generateToken(array $payload): string
    {
        $now = time();
        
        $payload = array_merge($payload, [
            'iat' => $now,
            'exp' => $now + $this->expiration,
            'iss' => $_ENV['APP_URL'] ?? 'localhost'
        ]);

        return JWT::encode($payload, $this->secret, $this->algorithm);
    }

    /**
     * Verifica y decodifica un token JWT
     */
    public function verifyToken(string $token): ?object
    {
        try {
            return JWT::decode($token, new Key($this->secret, $this->algorithm));
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Extrae el token del header Authorization
     */
    public function extractTokenFromHeader(): ?string
    {
        $headers = getallheaders();
        $authHeader = $headers['Authorization'] ?? $_SERVER['HTTP_AUTHORIZATION'] ?? null;

        if ($authHeader && preg_match('/Bearer\s+(.*)$/i', $authHeader, $matches)) {
            return $matches[1];
        }

        return null;
    }

    /**
     * Verifica si un token ha expirado
     */
    public function isTokenExpired(object $payload): bool
    {
        return isset($payload->exp) && $payload->exp < time();
    }

    /**
     * Refresca un token (genera uno nuevo con el mismo payload)
     */
    public function refreshToken(string $token): ?string
    {
        $payload = $this->verifyToken($token);
        
        if (!$payload) {
            return null;
        }

        // Remover campos de tiempo para generar nuevo token
        $newPayload = (array) $payload;
        unset($newPayload['iat'], $newPayload['exp'], $newPayload['iss']);

        return $this->generateToken($newPayload);
    }
}