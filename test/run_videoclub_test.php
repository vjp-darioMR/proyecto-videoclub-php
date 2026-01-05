<?php
require_once __DIR__ . '/../autoload.php';

use Dwes\ProyectoVideoclub\Videoclub;

$vc = new Videoclub('TestVC');

// Crear socios y productos
$vc->incluirSocio('Test User', 1, 'testuser', 'pass');
$vc->incluirCintaVideo('Test Movie', 2.5, 100);
$vc->incluirDvd('Test DVD', 5, 'es,en', '16:9');

// Realizar alquiler válido
$vc->alquilaSocioProducto(1, 1);

// Intentar alquilar mismo producto para generar warning
try {
    $vc->alquilaSocioProducto(1, 1);
} catch (Exception $e) {
    // ignore
}

// Intentar buscar producto inexistente to generate warning
try {
    $vc->alquilaSocioProducto(1, 999);
} catch (Exception $e) {
    // ignore
}

echo "Test script executed. Check logs/videoclub.log\n";
