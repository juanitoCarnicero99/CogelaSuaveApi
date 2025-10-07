# Cogela Suave API

Una API REST moderna desarrollada con PHP y Composer, siguiendo las mejores prácticas y patrones de diseño.

## 🚀 Características

- **Arquitectura MVC**: Separación clara de responsabilidades
- **Router personalizado**: Manejo dinámico de rutas con parámetros
- **Middleware**: Sistema de middleware para CORS, autenticación, etc.
- **Base de datos**: Abstracción de base de datos con soporte para MySQL y SQLite
- **JWT**: Autenticación basada en tokens JWT
- **Validación**: Sistema de validación de datos
- **Tests**: Configuración para PHPUnit
- **Autoloading**: PSR-4 autoloading con Composer
- **CORS**: Configuración completa para peticiones cross-origin

## 📁 Estructura del Proyecto

```
CogelaSuaveAPI/
├── config/                 # Archivos de configuración
│   ├── app.php             # Configuración principal
│   └── database.php        # Configuración de base de datos
├── database/               # Base de datos
│   ├── migrations/         # Scripts de migración
│   └── seeds/             # Datos de prueba
├── public/                 # Punto de entrada público
│   ├── index.php          # Archivo principal
│   └── .htaccess          # Configuración Apache
├── routes/                 # Definición de rutas
│   └── api.php            # Rutas de la API
├── src/                   # Código fuente
│   ├── Controllers/       # Controladores
│   ├── Models/           # Modelos de datos
│   ├── Middleware/       # Middleware
│   ├── Services/         # Servicios de negocio
│   └── Utils/           # Utilidades y helpers
├── tests/                # Tests unitarios
├── vendor/              # Dependencias de Composer
├── .env.example         # Variables de entorno de ejemplo
├── composer.json        # Configuración de Composer
└── README.md           # Este archivo
```

## 🛠️ Instalación

### Prerrequisitos

- PHP 8.0 o superior
- Composer
- MySQL o SQLite (opcional)
- Servidor web (Apache/Nginx) o PHP built-in server

### Pasos de instalación

1. **Clonar o descargar el proyecto**

2. **Instalar dependencias**
   ```bash
   composer install
   ```

3. **Configurar variables de entorno**
   ```bash
   cp .env.example .env
   ```
   Edita el archivo `.env` con tus configuraciones.

4. **Configurar base de datos** (opcional)
   - Crea una base de datos MySQL
   - Ejecuta las migraciones en `database/migrations/`

5. **Iniciar el servidor**
   ```bash
   composer serve
   # o manualmente:
   php -S localhost:8000 -t public/
   ```

## 📚 Uso de la API

### Endpoints disponibles

#### Health Check
```http
GET /api/health
```

#### Usuarios
```http
GET    /api/users        # Listar usuarios
GET    /api/users/{id}   # Obtener usuario específico
POST   /api/users        # Crear nuevo usuario
PUT    /api/users/{id}   # Actualizar usuario
DELETE /api/users/{id}   # Eliminar usuario
```

### Ejemplos de uso

#### Listar usuarios
```bash
curl -X GET http://localhost:8000/api/users
```

#### Crear usuario
```bash
curl -X POST http://localhost:8000/api/users \
  -H "Content-Type: application/json" \
  -d '{"name": "Juan Pérez", "email": "juan@example.com"}'
```

#### Actualizar usuario
```bash
curl -X PUT http://localhost:8000/api/users/1 \
  -H "Content-Type: application/json" \
  -d '{"name": "Juan Carlos", "email": "juancarlos@example.com"}'
```

## 🔧 Desarrollo

### Comandos de Composer

```bash
# Ejecutar el servidor de desarrollo
composer serve

# Ejecutar tests
composer test

# Verificar estilo de código
composer cs-check

# Corregir estilo de código
composer cs-fix

# Análisis estático
composer analyse
```

### Agregar nuevas rutas

Edita `routes/api.php`:

```php
'GET /api/productos' => [ProductController::class, 'index'],
'POST /api/productos' => [ProductController::class, 'store'],
```

### Crear un nuevo controlador

```php
<?php

namespace App\Controllers;

class ProductController extends BaseController
{
    public function index()
    {
        // Lógica para listar productos
        return $this->response->success($products);
    }
    
    public function store()
    {
        // Lógica para crear producto
        $data = $this->getRequestData();
        // ...
        return $this->response->success($product, 'Producto creado', 201);
    }
}
```

### Crear un modelo

```php
<?php

namespace App\Models;

class Product extends BaseModel
{
    protected string $table = 'products';
    
    protected array $fillable = [
        'name',
        'description',
        'price'
    ];
}
```

## 🔒 Seguridad

- Headers de seguridad configurados
- Validación de datos de entrada
- Preparación de consultas SQL (previene SQL injection)
- Manejo seguro de errores
- Configuración CORS

## 🧪 Testing

Ejecutar tests:

```bash
composer test
```

Ejecutar tests con cobertura:

```bash
composer test-coverage
```

## 📝 Configuración

### Variables de entorno (.env)

```env
APP_NAME="Cogela Suave API"
APP_ENV=development
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=localhost
DB_DATABASE=cogela_suave_api
DB_USERNAME=root
DB_PASSWORD=

JWT_SECRET=tu_clave_secreta_muy_segura_aqui
```

### Configuración de CORS

Edita `config/app.php` para configurar CORS:

```php
'cors' => [
    'allowed_origins' => 'http://localhost:3000,https://miapp.com',
    'allowed_methods' => 'GET,POST,PUT,DELETE,OPTIONS',
    'allowed_headers' => 'Content-Type,Authorization'
]
```

## 🤝 Contribución

1. Fork el proyecto
2. Crea una rama para tu feature (`git checkout -b feature/nueva-funcionalidad`)
3. Commit tus cambios (`git commit -am 'Agregar nueva funcionalidad'`)
4. Push a la rama (`git push origin feature/nueva-funcionalidad`)
5. Abre un Pull Request

## 📄 Licencia

MIT License. Ver `LICENSE` para más details.

## 🙋‍♂️ Soporte

Si tienes preguntas o necesitas ayuda, por favor abre un issue en el repositorio.

---

**¡Desarrollado con ❤️ para la comunidad PHP!**