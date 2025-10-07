<?php
// Archivo de debug simple
echo "PHP funcionando correctamente!\n";
echo "Probando autoloader de Composer...\n";

// Verificar si existe el autoloader
if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    echo "✅ Autoloader encontrado\n";
    
    try {
        require_once __DIR__ . '/../vendor/autoload.php';
        echo "✅ Autoloader cargado correctamente\n";
        
        // Probar cargar una clase
        $response = new App\Utils\Response();
        echo "✅ Clase Response cargada correctamente\n";
        
    } catch (Exception $e) {
        echo "❌ Error cargando autoloader: " . $e->getMessage() . "\n";
    }
} else {
    echo "❌ No se encontró vendor/autoload.php\n";
}

// Verificar .env
if (file_exists(__DIR__ . '/../.env')) {
    echo "✅ Archivo .env encontrado\n";
} else {
    echo "❌ Archivo .env no encontrado\n";
}
?>