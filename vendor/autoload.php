<?php
// Simple fallback autoload used when Composer is not available.
// Registers PSR-4 for the project's namespace and includes simple vendor stubs.

spl_autoload_register(function ($class) {
    // Normalize namespace separators
    $class = ltrim($class, '\\');

    // Project namespace prefix
    $prefix = 'Dwes\\ProyectoVideoclub\\';
    $base_dir = __DIR__ . '/../app/Dwes/ProyectoVideoclub/';

    if (strpos($class, $prefix) === 0) {
        $relative_class = substr($class, strlen($prefix));
        $file = $base_dir . str_replace('\\\\', '/', $relative_class) . '.php';
        $file = str_replace('\\', '/', $file);
        if (file_exists($file)) {
            require $file;
            return true;
        }
    }

    // Fallback: try PSR-0-ish resolution in app/
    $file = __DIR__ . '/../app/' . str_replace('\\', '/', $class) . '.php';
    if (file_exists($file)) {
        require $file;
        return true;
    }

    return false;
});

// Include lightweight monolog stub if present
if (file_exists(__DIR__ . '/monolog/Monolog/Logger.php')) {
    require_once __DIR__ . '/monolog/Monolog/Logger.php';
}

// You can add other vendor stubs similarly if needed.
