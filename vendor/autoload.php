<?php
// Minimal project autoload fallback when Composer isn't installed.
spl_autoload_register(function ($class) {
    $class = ltrim($class, '\\');

    // Project namespace
    $prefix = 'Dwes\\ProyectoVideoclub\\';
    $base_dir = __DIR__ . '/../app/Dwes/ProyectoVideoclub/';

    if (strpos($class, $prefix) === 0) {
        $relative = substr($class, strlen($prefix));
        $file = $base_dir . str_replace('\\', '/', $relative) . '.php';
        if (file_exists($file)) {
            require $file;
            return true;
        }
    }

    // Try PSR-0-ish fallback inside app/
    $file = __DIR__ . '/../app/' . str_replace('\\', '/', $class) . '.php';
    if (file_exists($file)) {
        require $file;
        return true;
    }

    return false;
});
