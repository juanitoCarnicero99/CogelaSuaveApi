<?php

return [
    'name' => $_ENV['APP_NAME'] ?? 'Cogela Suave API',
    'env' => $_ENV['APP_ENV'] ?? 'production',
    'debug' => $_ENV['APP_DEBUG'] === 'true',
    'url' => $_ENV['APP_URL'] ?? 'http://localhost',
    
    'timezone' => 'America/Mexico_City',
    
    'jwt' => [
        'secret' => $_ENV['JWT_SECRET'] ?? 'default_secret',
        'expire' => (int) ($_ENV['JWT_EXPIRE'] ?? 3600),
        'algorithm' => 'HS256'
    ],
    
    'cors' => [
        'allowed_origins' => $_ENV['CORS_ALLOWED_ORIGINS'] ?? '*',
        'allowed_methods' => $_ENV['CORS_ALLOWED_METHODS'] ?? 'GET,POST,PUT,DELETE,OPTIONS',
        'allowed_headers' => $_ENV['CORS_ALLOWED_HEADERS'] ?? 'Content-Type,Authorization'
    ],
    
    'pagination' => [
        'default_per_page' => 15,
        'max_per_page' => 100
    ]
];